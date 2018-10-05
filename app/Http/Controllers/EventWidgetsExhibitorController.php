<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Exhibitor;
use App\Models\Exhibitor_ticket;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
/*
  Attendize.com   - Event Management & Ticketing
 */

class EventWidgetsExhibitorController extends MyBaseController
{

    /**
     * Show the event widgets page
     *
     * @param Request $request
     * @param $event_id
     * @return mixed
     */
    public function showEventWidgets(Request $request, $event_id)
    {
        $event = Event::scope()->findOrFail($event_id);

        $exhibitor = Exhibitor::where('user_id', Auth::user()->id)
                                ->where('event_id', $event_id)
                                ->first();
         //var_dump($exhibitor);die();                      
        $exhibitor_ticket = Exhibitor_ticket::where('event_id',$event_id)
                            ->where('exhibitor_id', $exhibitor->id)
                            ->first();

        $ticket = Ticket::where('id',$exhibitor_ticket->ticket_id)->first(); 

        $data = [
            'event' => $event,
            'access_code' => $ticket->access_code
        ];
        return view('ManageEventExhibitor.Widgets', $data);
    }


}
