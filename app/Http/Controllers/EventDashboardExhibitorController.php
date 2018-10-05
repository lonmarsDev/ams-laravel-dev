<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventStats;
use App\Models\EventStatsExhibitor;
use App\Models\Exhibitor_ticket;
use App\Models\Exhibitor;
use App\Models\Ticket;
use App\Models\Attendee;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\Auth;

class EventDashboardExhibitorController extends MyBaseController
{
    /**
     * Show the event dashboard
     *
     * @param bool|false $event_id
     * @return \Illuminate\View\View
     */
    public function showDashboard($event_id = false)
    {

        $event = Event::scope()->findOrFail($event_id);

        $num_days = 20;

        /*
         * This is a fairly hackish way to get the data for the dashboard charts. I'm sure someone
         * with better SQL skill could do it in one simple query.
         *
         * Filling in the missing days here seems to be fast(ish) (with 20 days history), but the work
         * should be done in the DB
         */
        $exhibitor = Exhibitor::where('user_id', Auth::user()->id)
                                ->where('event_id', $event_id)
                                ->first();
         //var_dump($exhibitor);die();                      
        $exhibitor_ticket = Exhibitor_ticket::where('event_id',$event_id)
                            ->where('exhibitor_id', $exhibitor->id)
                            ->first();

        $ticket = Ticket::where('id',$exhibitor_ticket->ticket_id)->first();

        $order_count = Attendee::where('ticket_id',$exhibitor_ticket->ticket_id)->count('order_id');;                       


        $exhibitor_sales_volume = EventStatsExhibitor::where('event_id',$event_id)
                            ->where('access_code', $ticket->access_code  )
                            ->sum('sales_volume');

        $exhibitor_organiser_fee_volume = EventStatsExhibitor::where('event_id',$event_id)
                            ->where('access_code', $ticket->access_code )
                            ->sum('organiser_fees_volume');

        $exhibitor_ticket_sold = EventStatsExhibitor::where('event_id',$event_id)
                            ->where('access_code', $ticket->access_code )
                            ->sum('tickets_sold');                     

         $exhibitor_event_view = EventStatsExhibitor::where('event_id',$event_id)
                            ->where('access_code', $ticket->access_code  )
                            ->sum('views');                   

        $chartData = EventStatsExhibitor::where('event_id', '=', $event->id)
            ->where('date', '>', Carbon::now()->subDays($num_days)->format('Y-m-d'))
            ->where('access_code', $ticket->access_code )
            ->get()
            ->toArray();

        $startDate = new DateTime("-$num_days days");
        $dateItter = new DatePeriod(
            $startDate, new DateInterval('P1D'), $num_days
        );

        /*
         * Iterate through each possible date, if no stats exist for this date set default values
         * Otherwise, if a date does exist use these values
         */
        $result = [];
        $tickets_data = [];
        foreach ($dateItter as $date) {
            $views = 0;
            $sales_volume = 0;
            $unique_views = 0;
            $tickets_sold = 0;
            $organiser_fees_volume = 0;

            foreach ($chartData as $item) {
                if ($item['date'] == $date->format('Y-m-d')) {
                    $views = $item['views'];
                    $sales_volume = $item['sales_volume'];
                    $organiser_fees_volume = $item['organiser_fees_volume'];
                    $unique_views = $item['unique_views'];
                    $tickets_sold = $item['tickets_sold'];

                    break;
                }
            }

            $result[] = [
                'date'         => $date->format('Y-m-d'),
                'views'        => $views,
                'unique_views' => $unique_views,
                'sales_volume' => $sales_volume + $organiser_fees_volume,
                'tickets_sold' => $tickets_sold,
            ];
        }

       /* foreach ($event->tickets as $ticket) {
            $tickets_data[] = [
                'value' => $exhibitor_ticket_sold,
                'label' => $ticket->access_code,
            ];
        }*/
        $tickets_data[] = [
                'value' => $exhibitor_ticket_sold,
                'label' => $ticket->access_code,
            ];

        $data = [
            'event'      => $event,
            'exhibitor_sales_volume'  =>  $exhibitor_sales_volume,
            'exhibitor_organiser_fee_volume' => $exhibitor_organiser_fee_volume ,  
            'exhibitor_ticket_sold' => $exhibitor_ticket_sold ,
            'exhibitor_event_view' => $exhibitor_event_view , 
            'exhibitor_access_code' =>  $ticket->access_code ,
            'chartData'  => json_encode($result),
            'ticketData' => json_encode($tickets_data),
            'order_count' => $order_count ,
        ];

        return view('ManageEventExhibitor.Dashboard', $data);
    
    }
}