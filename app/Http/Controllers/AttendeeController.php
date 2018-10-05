<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Organiser;
use App\Models\Attendee;
use Carbon\Carbon;

class AttendeeController extends MyBaseController
{
    /**
     * Show the attendee dashboard
     *
     * @param $attendee_id
     * @return mixed
     */
    public function showDashboard($attendee_id)
    {
        $attendee = Attendee::scope()->findOrFail($attendee_id);
        $upcoming_events = $attendee->event()->where('end_date', '>=', Carbon::now())->get();
        $calendar_events = [];

         // Prepare JSON array for events for use in the dashboard calendar 
        // $event = $attendee->event;
        foreach ($attendee->event()->get() as $event) {
            $calendar_events[] = [
                'title' => $event->title,
                'start' => $event->start_date->toIso8601String(),
                'end'   => $event->end_date->toIso8601String(),
                'url'   => route('showEventDashboard', [
                    'event_id' => $event->id
                ]),
                'color' => '#4E558F'
            ];
        }

        $data = [
            'attendee'       => $attendee,
            'upcoming_events' => $upcoming_events,
            'calendar_events' => json_encode($calendar_events),
        ];

        return view('ManageAttendee.Dashboard', $data);
    }

    /**
     * Show the attendee event
     *
     * @param $attendee_id, $event_id
     * @return mixed
     */
    public function showEvent($attendee_id, $event_id){
        $attendee = Attendee::scope()->findOrFail($attendee_id);
        $event = $attendee->event()->where('id', $event_id)->get()->first();
        $data = [
            'attendee'          => $attendee,
            'event'             => $event
        ];
        return view('ManageAttendee.Event', $data);
    }
}
