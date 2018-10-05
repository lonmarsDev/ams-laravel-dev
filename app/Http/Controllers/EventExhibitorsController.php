<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateTicket;
use App\Jobs\SendAttendeeInvite;
use App\Jobs\SendAttendeeTicket;
use App\Jobs\SendMessageToAttendees;
use App\Models\Attendee;
use App\Models\Account;
use App\Models\User;
use App\Models\Exhibitor;
use App\Models\Exhibitor_ticket;
use App\Models\Event;
use App\Models\EventStats;
use App\Models\Message;
use App\Models\Order;
use App\Models\Organiser;
use App\Models\OrderItem;
use App\Models\Ticket;
use Auth;
use Config;
use DB;
use Excel;
use Illuminate\Http\Request;
use Log;
use Mail;
use Omnipay\Omnipay;
use PDF;
use Validator;
use Hash;
use Carbon\Carbon;

class EventExhibitorsController extends MyBaseController
{
    /**
     * Show the attendees list
     *
     * @param Request $request
     * @param $event_id
     * @return View
     */
    public function showExhibitors(Request $request, $event_id)
    {

        $allowed_sorts = ['first_name', 'email', 'ticket_id', 'order_reference'];
        $searchQuery = $request->get('q');
        $sort_order = $request->get('sort_order') == 'asc' ? 'asc' : 'desc';
        $sort_by = (in_array($request->get('sort_by'), $allowed_sorts) ? $request->get('sort_by') : 'id');

        $event = Event::scope()->find($event_id);
        $exhibitors = Exhibitor::where('event_id',$event_id)
                        ->orderBy(($sort_by == 'order_reference' ? 'orders.' : 'exhibitors.') . $sort_by, $sort_order)
                        ->paginate();


        //var_dump($exhibitors->get());die();
        if ($searchQuery) {
            $attendees = $event->attendees()
                ->withoutCancelled()
                ->join('orders', 'orders.id', '=', 'attendees.order_id')
                ->where(function ($query) use ($searchQuery) {
                    $query->where('orders.order_reference', 'like', $searchQuery . '%')
                        ->orWhere('attendees.first_name', 'like', $searchQuery . '%')
                        ->orWhere('attendees.email', 'like', $searchQuery . '%')
                        ->orWhere('attendees.last_name', 'like', $searchQuery . '%');
                })
                ->orderBy(($sort_by == 'order_reference' ? 'orders.' : 'attendees.') . $sort_by, $sort_order)
                ->select('attendees.*', 'orders.order_reference')
                ->paginate();

           $exhibitors = Exhibitor::where('event_id',$event_id)
                            ->Where('exhibitors.first_name', 'like', $searchQuery . '%')
                            ->orWhere('exhibitors.email', 'like', $searchQuery . '%')
                            ->orWhere('exhibitors.last_name', 'like', $searchQuery . '%')
                            ->orderBy(($sort_by == 'order_reference' ? 'orders.' : 'exhibitors.') . $sort_by, $sort_order)
                            ->paginate();

        } else {
            $attendees = $event->attendees()
            
                ->join('orders', 'orders.id', '=', 'attendees.order_id')
                ->withoutCancelled()
                ->orderBy(($sort_by == 'order_reference' ? 'orders.' : 'attendees.') . $sort_by, $sort_order)
                ->select('attendees.*', 'orders.order_reference')
                ->paginate();
        }

        $data = [
            'attendees'  => $attendees,
            'exhibitors'  => $exhibitors,
            'event'      => $event,
            'sort_by'    => $sort_by,
            'sort_order' => $sort_order,
            'q'          => $searchQuery ? $searchQuery : '',
        ];

        return view('ManageEvent.Exhibitors', $data);
    }

    /**
     * Show the 'Invite Attendee' modal
     *
     * @param Request $request
     * @param $event_id
     * @return string|View
     */
    public function showInviteExhibitor(Request $request, $event_id)
    {
        $event = Event::scope()->find($event_id);

        /*
         * If there are no tickets then we can't create an attendee
         * @todo This is a bit hackish
         */
        if ($event->tickets->count() === 0) {
            return '<script>showMessage("You need to create a ticket before you can invite an attendee.");</script>';
        }

        $randAccesCode = strtoupper( substr(md5(microtime()),rand(0,26),4) );

        return view('ManageEvent.Modals.InviteExhibitor', [
            'event'   => $event,
            'tickets' => $event->tickets()->lists('title', 'id'),
            'access_code' => $randAccesCode,
            'ticket_price' => 0
        ]);
    }

    /**
     * Invite an attendee
     *
     * @param Request $request
     * @param $event_id
     * @return mixed
     */
    public function postInviteExhibitor(Request $request, $event_id)
    {   
        $rules = [
            'email'    => 'email|required',
            'company'  => 'required'
            
        ];

        $messages = [
            'email.email'   => 'Invalid email',
            'email.required'   => 'The email address field is required',
            'company.required' => 'The Company field is required. ',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error',
                'messages' => $validator->messages()->toArray(),
            ]);
        }

       
        $exhibitor_first_name = $request->get('first_name');
        $exhibitor_last_name = $request->get('last_name');
        $exhibitor_email = $request->get('email');
        $email_exhibitor = $request->get('email_ticket');
        $ticket_exhibitor = $request->get('ticket_exhibitor'); 
        $exhibitor_company = $request->get('company');
        $exhibitor_booth_no = $request->get('booth_no');
        $exhibitor_phone = $request->get('contact_no');

        DB::beginTransaction();

        try {

            /*
             * Create/select account validate by email
            */
           //$exhibitor_AccountID = Account::where('email',$exhibitor_email)->first();
            /*if(!$exhibitor_AccountID){
                
                $account = new Account();
                $account->first_name = $exhibitor_first_name;
                $account->last_name = $exhibitor_last_name;
                $account->email = $exhibitor_email;
                $account->currency_id = config('attendize.default_currency');
                $account->timezone_id = config('attendize.default_timezone');
                $account->save();
                $exhibitor_AccountID = $account->id;
            }else{
                $exhibitor_AccountID = $exhibitor_AccountID->id;
            }
            */
            /*
             * Create/select User  by email // use for login
            */

            $randPassword = substr(md5(microtime()),rand(0,26),5);
            $exhibitor_UserID = User::where('email',$exhibitor_email)->first();

            $IsNewUser = 0;
            if(!$exhibitor_UserID){
                $user = new User();
                $user->first_name = $exhibitor_first_name;
                $user->last_name = $exhibitor_last_name;
                $user->email = $exhibitor_email;
               // $user->account_id = $exhibitor_AccountID;
                $user->account_id = Auth::user()->account_id ;
                $user->password = Hash::make( $randPassword );
                $user->role = 1; //setting exhibitor user
                $user->save();
                $exhibitor_UserID = $user->id;
                $IsNewUser = 1;
            }else{
                $exhibitor_UserID = $exhibitor_UserID->id;    
                $user = User::where('id',$exhibitor_UserID )->first();
                $IsNewUser = 0;
            }


            /*
             * Create the Exhibior
            */
            $exhibitor = new Exhibitor();
            $exhibitor->first_name = $exhibitor_first_name;
            $exhibitor->last_name = $exhibitor_last_name;
            $exhibitor->email = $exhibitor_email;
            $exhibitor->company_name = $exhibitor_company;
            $exhibitor->booth_no = $exhibitor_booth_no;
            $exhibitor->phone = $exhibitor_phone;
            $exhibitor->event_id = $event_id ;
            
            //$exhibitor->account_id = $exhibitor_AccountID;
            $exhibitor->account_id = Auth::user()->account_id;
            $exhibitor->user_id = $exhibitor_UserID;

            $exhibitor->organizer_account_id = Auth::user()->account_id;
            
            $exhibitor->save();


           
            /*
             * Update the event stats
             */
          
            if ($email_exhibitor == '1') {
                //$this->dispatch(new SendAttendeeInvite($attendee));
                $event = Event::where('id',$event_id)->first();

                Mail::send('Emails.ConfirmEmailExhibitor',
                ['first_name' => $user->first_name, 'confirmation_code' => $user->confirmation_code , 'password' => $randPassword , 'email' => $user->email  , 'is_new_user' => $IsNewUser , 'event_name'=> $event->title ],
                function ($message) use ($request) {
                    $message->to($request->get('email'), $request->get('first_name'))
                        ->subject('You are invited to an exhibitor on the event');
                });
            
            }

            if ($ticket_exhibitor == '1') {
                
                $ticket = Ticket::createNew();
                $ticket->event_id = $event_id;
                $ticket->title = $request->get('title');
                $ticket->quantity_available = !$request->get('quantity_available') ? null : $request->get('quantity_available');
                $ticket->start_sale_date = $request->get('start_sale_date') ? Carbon::createFromFormat('d-m-Y H:i',
                $request->get('start_sale_date')) : null;
                $ticket->end_sale_date = $request->get('end_sale_date') ? Carbon::createFromFormat('d-m-Y H:i',
                $request->get('end_sale_date')) : null;
                $ticket->price = $request->get('price');
                $ticket->min_per_person = $request->get('min_per_person');
                $ticket->max_per_person = $request->get('max_per_person');
                $ticket->description = $request->get('description');
                $ticket->is_hidden = $request->get('is_hidden') ? 1 : 0;
                $ticket->with_accesscodes = 1;
                $ticket->access_code = empty($request->access_code) ? "" : $request->access_code;
                $ticket->save();


                $exhibitor_ticket = new Exhibitor_ticket;
                $exhibitor_ticket->exhibitor_id = $exhibitor->id ;
                $exhibitor_ticket->ticket_id = $ticket->id ;
                $exhibitor_ticket->event_id = $event_id;
                $exhibitor_ticket->save();


            }

            session()->flash('message', 'Exhibitor Successfully Invited');

            DB::commit();

            return response()->json([
                'status'      => 'success',
                'redirectUrl' => route('showEventExhibitors', [
                    'event_id' => $event_id,
                ]),
            ]);

        } catch (Exception $e) {

            Log::error($e);
            DB::rollBack();

            return response()->json([
                //'err' => $e,
                'status' => 'error',
                'error'  => 'An error occurred while inviting this attendee. Please try again.'
            ]);
        }

    }

    /**
     * Show the 'Import Attendee' modal
     *
     * @param Request $request
     * @param $event_id
     * @return string|View
     */
    public function showImportAttendee(Request $request, $event_id)
    {
        $event = Event::scope()->find($event_id);

        /*
         * If there are no tickets then we can't create an attendee
         * @todo This is a bit hackish
         */
        if ($event->tickets->count() === 0) {
            return '<script>showMessage("You need to create a ticket before you can add an attendee.");</script>';
        }

        return view('ManageEvent.Modals.ImportAttendee', [
            'event'   => $event,
            'tickets' => $event->tickets()->lists('title', 'id'),
        ]);
    }


    /**
     * Import attendees
     *
     * @param Request $request
     * @param $event_id
     * @return mixed
     */
    public function postImportAttendee(Request $request, $event_id)
    {
        $rules = [
            'ticket_id'      => 'required|exists:tickets,id,account_id,' . \Auth::user()->account_id,
            'attendees_list' => 'required|mimes:csv,txt|max:5000|',
        ];

        $messages = [
            'ticket_id.exists' => 'The ticket you have selected does not exist',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error',
                'messages' => $validator->messages()->toArray(),
            ]);

        }

        $ticket_id = $request->get('ticket_id');
        $ticket_price = 0;
        $email_attendee = $request->get('email_ticket');
        $num_added = 0;
        if ($request->file('attendees_list')) {

            $the_file = Excel::load($request->file('attendees_list')->getRealPath(), function ($reader) {
            })->get();

            // Loop through
            foreach ($the_file as $rows) {
                if (!empty($rows['first_name']) && !empty($rows['last_name']) && !empty($rows['email'])) {
                    $num_added++;
                    $attendee_first_name = $rows['first_name'];
                    $attendee_last_name = $rows['last_name'];
                    $attendee_email = $rows['email'];

                    error_log($ticket_id . ' ' . $ticket_price . ' ' . $email_attendee);


                    /**
                     * Create the order
                     */
                    $order = new Order();
                    $order->first_name = $attendee_first_name;
                    $order->last_name = $attendee_last_name;
                    $order->email = $attendee_email;
                    $order->order_status_id = config('attendize.order_complete');
                    $order->amount = $ticket_price;
                    $order->account_id = Auth::user()->account_id;
                    $order->event_id = $event_id;
                    $order->save();

                    /**
                     * Update qty sold
                     */
                    $ticket = Ticket::scope()->find($ticket_id);
                    $ticket->increment('quantity_sold');
                    $ticket->increment('sales_volume', $ticket_price);
                    $ticket->event->increment('sales_volume', $ticket_price);

                    /**
                     * Insert order item
                     */
                    $orderItem = new OrderItem();
                    $orderItem->title = $ticket->title;
                    $orderItem->quantity = 1;
                    $orderItem->order_id = $order->id;
                    $orderItem->unit_price = $ticket_price;
                    $orderItem->save();

                    /**
                     * Update the event stats
                     */
                    $event_stats = new EventStats();
                    $event_stats->updateTicketsSoldCount($event_id, 1);
                    $event_stats->updateTicketRevenue($ticket_id, $ticket_price);

                    /**
                     * Create the attendee
                     */
                    $attendee = new Attendee();
                    $attendee->first_name = $attendee_first_name;
                    $attendee->last_name = $attendee_last_name;
                    $attendee->email = $attendee_email;
                    $attendee->event_id = $event_id;
                    $attendee->order_id = $order->id;
                    $attendee->ticket_id = $ticket_id;
                    $attendee->account_id = Auth::user()->account_id;
                    $attendee->reference_index = 1;
                    $attendee->save();

                    if ($email_attendee == '1') {
                        $this->dispatch(new SendAttendeeInvite($attendee));
                    }
                }
            };
        }

        session()->flash('message', $num_added . ' Attendees Successfully Invited');

        return response()->json([
            'status'      => 'success',
            'id'          => $attendee->id,
            'redirectUrl' => route('showEventAttendees', [
                'event_id' => $event_id,
            ]),
        ]);
    }

    /**
     * Show the printable attendee list
     *
     * @param $event_id
     * @return View
     */
    public function showPrintAttendees($event_id)
    {
        $data['event'] = Event::scope()->find($event_id);
        $data['attendees'] = $data['event']->attendees()->withoutCancelled()->orderBy('first_name')->get();

        return view('ManageEvent.PrintAttendees', $data);
    }

    /**
     * Show the 'Message Attendee' modal
     *
     * @param Request $request
     * @param $attendee_id
     * @return View
     */
    public function showMessageAttendee(Request $request, $attendee_id)
    {
        $attendee = Attendee::scope()->findOrFail($attendee_id);

        $data = [
            'attendee' => $attendee,
            'event'    => $attendee->event,
        ];

        return view('ManageEvent.Modals.MessageAttendee', $data);
    }

    /**
     * Send a message to an attendee
     *
     * @param Request $request
     * @param $attendee_id
     * @return mixed
     */
    public function postMessageAttendee(Request $request, $attendee_id)
    {
    
        $rules = [
            'subject' => 'required',
            'message' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error',
                'messages' => $validator->messages()->toArray(),
            ]);
        }

        $attendee = Attendee::scope()->findOrFail($attendee_id);

        $data = [
            'attendee'        => $attendee,
            'message_content' => $request->get('message'),
            'subject'         => $request->get('subject'),
            'event'           => $attendee->event,
            'email_logo'      => $attendee->event->organiser->full_logo_path,
        ];

        //@todo move this to the SendAttendeeMessage Job
        Mail::send('Emails.messageReceived', $data, function ($message) use ($attendee, $data) {
            $message->to($attendee->email, $attendee->full_name)
                ->from(config('attendize.outgoing_email_noreply'), $attendee->event->organiser->name)
                ->replyTo($attendee->event->organiser->email, $attendee->event->organiser->name)
                ->subject($data['subject']);
        });

        /* Could bcc in the above? */
        if ($request->get('send_copy') == '1') {
            Mail::send('Emails.messageReceived', $data, function ($message) use ($attendee, $data) {
                $message->to($attendee->event->organiser->email, $attendee->event->organiser->name)
                    ->from(config('attendize.outgoing_email_noreply'), $attendee->event->organiser->name)
                    ->replyTo($attendee->event->organiser->email, $attendee->event->organiser->name)
                    ->subject($data['subject'] . '[ORGANISER COPY]');
            });
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Message Successfully Sent',
        ]);
    
    }

    /**
     * Shows the 'Message Attendees' modal
     *
     * @param $event_id
     * @return View
     */
    public function showMessageAttendees(Request $request, $event_id)
    {
        $data = [
            'event'   => Event::scope()->find($event_id),
            'tickets' => Event::scope()->find($event_id)->tickets()->lists('title', 'id')->toArray(),
        ];

        return view('ManageEvent.Modals.MessageAttendees', $data);
    }

    /**
     * Send a message to attendees
     *
     * @param Request $request
     * @param $event_id
     * @return mixed
     */
    public function postMessageAttendees(Request $request, $event_id)
    {
        $rules = [
            'subject'    => 'required',
            'message'    => 'required',
            'recipients' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error',
                'messages' => $validator->messages()->toArray(),
            ]);
        }

        $message = Message::createNew();
        $message->message = $request->get('message');
        $message->subject = $request->get('subject');
        $message->recipients = ($request->get('recipients') == 'all') ? 'all' : $request->get('recipients');
        $message->event_id = $event_id;
        $message->save();

        /*
         * Queue the emails
         */
        $this->dispatch(new SendMessageToAttendees($message));

        return response()->json([
            'status'  => 'success',
            'message' => 'Message Successfully Sent',
        ]);
    }

    /**
     * Downloads the ticket of an attendee as PDF
     *
     * @param $event_id
     * @param $attendee_id
     */
    public function showExportTicket($event_id, $attendee_id)
    {
        $attendee = Attendee::scope()->findOrFail($attendee_id);

        Config::set('queue.default', 'sync');
        Log::info("*********");
        Log::info($attendee_id);
        Log::info($attendee);


        $this->dispatch(new GenerateTicket($attendee->order->order_reference . "-" . $attendee->reference_index));

        $pdf_file_name = $attendee->order->order_reference . '-' . $attendee->reference_index;
        $pdf_file_path = public_path(config('attendize.event_pdf_tickets_path')) . '/' . $pdf_file_name;
        $pdf_file = $pdf_file_path . '.pdf';


        return response()->download($pdf_file);
    }

    /**
     * Downloads an export of attendees
     *
     * @param $event_id
     * @param string $export_as (xlsx, xls, csv, html)
     */
    public function showExportAttendees($event_id, $export_as = 'xls')
    {

        Excel::create('attendees-as-of-' . date('d-m-Y-g.i.a'), function ($excel) use ($event_id) {

            $excel->setTitle('Attendees List');

            // Chain the setters
            $excel->setCreator(config('attendize.app_name'))
                ->setCompany(config('attendize.app_name'));

            $excel->sheet('attendees_sheet_1', function ($sheet) use ($event_id) {

                DB::connection()->setFetchMode(\PDO::FETCH_ASSOC);
                $data = DB::table('attendees')
                    ->where('attendees.event_id', '=', $event_id)
                    ->where('attendees.is_cancelled', '=', 0)
                    ->where('attendees.account_id', '=', Auth::user()->account_id)
                    ->join('events', 'events.id', '=', 'attendees.event_id')
                    ->join('orders', 'orders.id', '=', 'attendees.order_id')
                    ->join('tickets', 'tickets.id', '=', 'attendees.ticket_id')
                    ->select([
                        'attendees.first_name',
                        'attendees.last_name',
                        'attendees.email',
                        'orders.order_reference',
                        'tickets.title',
                        'orders.created_at',
                        DB::raw("(CASE WHEN attendees.has_arrived THEN 'YES' ELSE 'NO' END) AS has_arrived"),
                        'attendees.arrival_time',
                    ])->get();

                $sheet->fromArray($data);
                $sheet->row(1, [
                    'First Name',
                    'Last Name',
                    'Email',
                    'Order Reference',
                    'Ticket Type',
                    'Purchase Date',
                    'Has Arrived',
                    'Arrival Time',
                ]);

                // Set gray background on first row
                $sheet->row(1, function ($row) {
                    $row->setBackground('#f5f5f5');
                });
            });
        })->export($export_as);
    }

    /**
     * Show the 'Edit Attendee' modal
     *
     * @param Request $request
     * @param $event_id
     * @param $attendee_id
     * @return View
     */
    public function showEditExhibitor(Request $request, $event_id, $exhibitor_id)
    {
        $exhibitor = Exhibitor::where('id',$exhibitor_id)->first();
        $data = [
            'exhibitor' => $exhibitor,
        ];

        return view('ManageEvent.Modals.EditExhibitor', $data);
    }

    /**
     * Updates an attendee
     *
     * @param Request $request
     * @param $event_id
     * @param $attendee_id
     * @return mixed
     */
    public function postEditExhibitor(Request $request, $event_id, $exhibitor_id)
    {
         $rules = [
            'company_name'  => 'required'
            
        ];

        $messages = [
            'company_name.required' => 'The Company field is required. ',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error',
                'messages' => $validator->messages()->toArray(),
            ]);
        }

            $exhibitor_first_name = $request->get('first_name');
            $exhibitor_last_name = $request->get('last_name');
            $exhibitor_email = $request->get('email');
            $email_exhibitor = $request->get('email_ticket');
            $exhibitor_company = $request->get('company_name');
            $exhibitor_booth_no = $request->get('booth_no');
            $exhibitor_phone = $request->get('phone');

            /*
                edit exhibitor
            */
            $exhibitor = Exhibitor::where('id',$exhibitor_id)->first();
            $exhibitor->first_name = $exhibitor_first_name;
            $exhibitor->last_name = $exhibitor_last_name;
            $exhibitor->company_name = $exhibitor_company;
            $exhibitor->booth_no = $exhibitor_booth_no;
            $exhibitor->phone = $exhibitor_phone;
            $exhibitor->save();
        
      
        session()->flash('message', 'Successfully Updated Attendee');

        return response()->json([
            'status'      => 'success',
            'id'          => $exhibitor->id,
            'redirectUrl' => '',
        ]);
    }

    /**
     * Shows the 'Cancel Attendee' modal
     *
     * @param Request $request
     * @param $event_id
     * @param $attendee_id
     * @return View
     */
    public function showCancelExhibitor(Request $request, $event_id, $exhibitor_id)
    {
        $exhibitor = Exhibitor::where('id',$exhibitor_id)->first();
        $data = [
            'exhibitor' => $exhibitor,
        ];

        return view('ManageEvent.Modals.CancelExhibitor', $data);
    }

    /**
     * Cancels an attendee
     *
     * @param Request $request
     * @param $event_id
     * @param $attendee_id
     * @return mixed
     */
    public function postCancelExhibitor(Request $request, $event_id, $exhibitor_id)
    {
        
        /*if ($attendee->is_cancelled) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Attendee Already Cancelled',
            ]);
        }
        */

      
        $exhibitor = Exhibitor::where('id',$exhibitor_id)->first();
        $exhibitor->is_cancelled = 1;
        $exhibitor->save();
        $event = Event::where('id',$exhibitor->event_id)->first();
        $organiser = Organiser::where('id',$event->organiser_id)->first();


        
        $data = [
            'exhibitor'   => $exhibitor,
            'event' => $event ,
            'organiser' => $organiser ,
            'email_logo' => $organiser->full_logo_path,
        ];
        //var_dump( ['event_title' => $event->title, 'organiser_name' => $organiser->name , 'organiser_email' => $organiser->email  ]);
        //die();

        if ($request->get('notify_exhibitor') == '1') {
            
            Mail::send('Emails.notifyCancelledExhibitor', ['event_title' => $event->title, 'organiser_name' => $organiser->name , 'organiser_email' => $organiser->email  ] , function ($message) use ($data) {
                $message->to($data['exhibitor']->email, $data['exhibitor']->first_name)
                    ->from(config('attendize.outgoing_email_noreply'), $data['organiser']->name)
                    ->replyTo($data['organiser']->email , $data['organiser']->name)
                    ->subject('You\'re Invitation has been cancelled');
            });

        }

        
        /*

        if ($error_message) {
            return response()->json([
                'status'  => 'error',
                'message' => $error_message,
            ]);

        }*/

        session()->flash('message', 'Successfully Cancelled Invitation');

        return response()->json([
            'status'      => 'success',
            'id'          => $exhibitor->id,
            'redirectUrl' => '',
        ]);
    }

    /**
     * Show the 'Message Attendee' modal
     *
     * @param Request $request
     * @param $attendee_id
     * @return View
     */
    public function showResendTicketToExhibitor(Request $request, $exhibitor_id)
    {
        $exhibitor = Exhibitor::where('id',$exhibitor_id)->first();
        

        $data = [
            'exhibitor' => $exhibitor,
            
        ];

        return view('ManageEvent.Modals.ResendTicketToExhibitor', $data);
    }

    /**
     * Send a message to an attendee
     *
     * @param Request $request
     * @param $attendee_id
     * @return mixed
     */
    public function postResendTicketToExhibitor(Request $request, $exhibitor_id)
    {
        $randPassword = substr(md5(microtime()),rand(0,26),5);
        $exhibitor = Exhibitor::where('id',$exhibitor_id)->first();
        $user = User::where('id',$exhibitor->user_id)->first();
        //var_dump($user->first_name);die();
        $user->password = Hash::make( $randPassword );
        $user->save();
        
        $event = Event::scope()->find( $exhibitor->event_id )->first();

        Mail::send('Emails.ConfirmEmailExhibitor',
            ['first_name' => $user->first_name, 'confirmation_code' => $user->confirmation_code , 'password' => $randPassword , 'email' => $user->email , 'event_name' => $event->title , 'is_new_user' => 0],
            function ($message) use ($user) {
                $message->to( $user->email , $user->first_name )
                    ->subject('You are invited to an exhibitor on the event');
            });

        //$this->dispatch(new SendAttendeeTicket($attendee));

        return response()->json([
            'status'  => 'success',
            'message' => 'Ticket Successfully Resent',
        ]);
    }


    /**
     * Show an attendee ticket
     *
     * @param Request $request
     * @param $attendee_id
     * @return bool
     */
    public function showAttendeeTicket(Request $request, $attendee_id)
    {
        $attendee = Attendee::scope()->findOrFail($attendee_id);

        $data = [
            'order'     => $attendee->order,
            'event'     => $attendee->event,
            'tickets'   => $attendee->ticket,
            'attendees' => [$attendee],
            'css'       => file_get_contents(public_path('assets/stylesheet/ticket.css')),
            'image'     => base64_encode(file_get_contents(public_path($attendee->event->organiser->full_logo_path))),

        ];

        if ($request->get('download') == '1') {
            return PDF::html('Public.ViewEvent.Partials.PDFTicket', $data, 'Tickets');
        }
        return view('Public.ViewEvent.Partials.PDFTicket', $data);
    }

}
