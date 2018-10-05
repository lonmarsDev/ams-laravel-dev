<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Exhibitor;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Auth;

use Illuminate\Support\Facades\DB;

class ExhibitorDashboardController extends MyBaseController
{
    /**
     * Show the event dashboard
     *
     * @param bool|false $event_id
     * @return \Illuminate\View\View
     */
    public function showDashboard($event_id)
    {   
        $event = Event::where('id',$event_id)->first(); 
        
        $exhibitors = User::select('id','account_id','is_registered','is_confirmed','first_name', 'last_name')->where('role','1')->get();
        return view('ManageExhibitor.Dashboard', ['exhibitors'=>$exhibitors,'event'=>$event]);

    }

    public function ExhibitorDashboard()
    {   
        //var_dump(Auth::user()->id);die();
        // $exhibitor_user = Auth::user()->account_id;
        //var_dump($exhibitor_user);die();

        

        //$exhibitor_events = Event::where('account_id',Auth::user()->account_id );
        //$exhibitor_events = Exhibitor::where('account_id',Auth::user()->account_id )->event_id;
        //var_dump($exhibitor_events);die();
        $result_events=DB::table('events')
                    ->whereIn('id',function ($query) {
                                    $query->select('event_id')->from('exhibitors')
                                    ->Where('account_id','=', Auth::user()->account_id );

                                })
                    ->get();

        $result_events_upcoming = DB::table('events')
                    ->where('end_date', '>=', Carbon::now())
                    ->whereIn('id',function ($query) {
                                    $query->select('event_id')->from('exhibitors')
                                    ->Where('account_id','=', Auth::user()->account_id );

                                })
                    ->get();
        
        //var_dump($result_events_upcoming);die();                      
        //var_dump($result_events);            
        //die();

        // $event = Event::where('id',1)->first(); 
        
        // $exhibitors = User::select('id','account_id','is_registered','is_confirmed','first_name', 'last_name')->where('role','1')->get();
        // return view('ManageExhibitor.Dashboard', ['exhibitors'=>$exhibitors,'event'=>$event]);
        

        //var_dump($exhibitor_id);die();
        
        // $Exhibitor = Exhibitor::where('use',1)->first(); 
        // var_dump( $Exhibitor ); die();
        $upcoming_events = $result_events_upcoming;
        $calendar_events = [];

        foreach ($result_events as $event) {
            $calendar_events[] = [
                'title' => $event->title,
                'start' => $event->start_date,
                'end'   => $event->end_date,
                'url'   => route('showEventDashboard', [
                'event_id' => $event->id
                ]),
                'color' => '#4E558F'
            ];
        }

        $data = [
            'organiser'       => null,
            'upcoming_events' => $upcoming_events,
            'calendar_events' => json_encode($calendar_events),
        ];

        return view('ManageExhibitor.ExhibitorDashboard', $data);


/*
        $organiser = Organiser::scope()->findOrFail($organiser_id);
        $upcoming_events = $organiser->events()->where('end_date', '>=', Carbon::now())->get();
        $calendar_events = [];

        foreach ($organiser->events as $event) {
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
            'organiser'       => $organiser,
            'upcoming_events' => $upcoming_events,
            'calendar_events' => json_encode($calendar_events),
        ];

*/
    }

}