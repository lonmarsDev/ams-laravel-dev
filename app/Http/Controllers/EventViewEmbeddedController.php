<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventViewEmbeddedController extends Controller
{

    /**
     * Show an embedded version of the event page
     *
     * @param $event_id
     * @return mixed
     */
    public function showEmbeddedEvent(Request $request, $event_id)
    {
        $event = Event::findOrFail($event_id);

        $data = array();
        if ( $access_code = $request->get('access_code') ) {
            $data = [
                'event'       => $event,
                'tickets'     => $event->tickets()->where('is_hidden', 0)->where('with_accesscodes',1)->where('access_code', $request->get('access_code') )->orderBy('sort_order', 'asc')->get(),
                'is_embedded' => '1',
            ];
        }else{
            $data = [
                'event'       => $event,
                'tickets'     => $event->tickets()->where('is_hidden', 0)->orderBy('sort_order', 'asc')->get(),
                'is_embedded' => '1',
            ];    
        }
        return view('Public.ViewEvent.Embedded.EventPage', $data);
    }

   
}
