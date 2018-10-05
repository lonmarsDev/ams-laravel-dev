<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Organiser;
use App\Models\Exhibitor;
use App\Models\Exhibitor_ticket;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\Account;

class ExhibitorEventsController extends MyBaseController
{
    /**
     * Show the organiser events page
     *
     * @param Request $request
     * @param $organiser_id
     * @return mixed
     */
    public function showEvents(Request $request)
    {
        //$organiser = Organiser::scope()->findOrfail($organiser_id);
/*
        $exhibitor = Exhibitor::where('user_id', Auth::user()->id)
                                ->where('event_id', $event_id)
                                ->first();
         //var_dump($exhibitor);die();                      
        $exhibitor_ticket = Exhibitor_ticket::where('event_id',$event_id)
                            ->where('exhibitor_id', $exhibitor->id)
                            ->first();

        $ticket = Ticket::where('id',$exhibitor_ticket->ticket_id)->first();
*/


        $allowed_sorts = ['created_at', 'start_date', 'end_date', 'title'];

        $searchQuery = $request->get('q');
        $sort_by = (in_array($request->get('sort_by'), $allowed_sorts) ? $request->get('sort_by') : 'start_date');
        
        $events = $searchQuery
            ? Event::scope()->where('title', 'like', '%' . $searchQuery . '%')->orderBy($sort_by,
                'desc')->paginate(12)
            : Event::scope()
                ->whereIn('id',function ($query) {
                        $query->select('event_id')->from('exhibitors')
                        ->Where('user_id','=', Auth::user()->id )
                        ->Where('is_cancelled',0);

                    })->orderBy($sort_by, 'desc')->paginate(12);
        $data = [
            'events'    => $events ,
            'organiser' => null ,
            'tickets' => Ticket::where('deleted_at',NULL) ,
            'exhibitor_ticket' => Exhibitor_ticket::where('id', '>', 0) ,
            'exhibitors' => Exhibitor::where('deleted_at',NULL) ,
            'exhibitor' => Auth::user() ,
            'search'    => [
                'q'        => $searchQuery ? $searchQuery : '' ,
                'sort_by'  => $request->get('sort_by') ? $request->get('sort_by') : '' ,
                'showPast' => $request->get('past'),
            ],
        ];

        return view('ManageExhibitor.Events', $data);
    
    }


}