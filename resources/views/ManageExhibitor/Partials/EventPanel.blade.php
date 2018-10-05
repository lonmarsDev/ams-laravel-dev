<div class="panel panel-success event">
    <div class="panel-heading" data-style="background-color: {{{$event->bg_color}}};background-image: url({{{$event->bg_image_url}}}); background-size: cover;">
        <div class="event-date">
            <div class="month">
                {{strtoupper($event->start_date->format('M'))}}
            </div>
            <div class="day">
                {{$event->start_date->format('d')}}
            </div>
        </div>
        <ul class="event-meta">
            <li class="event-title">
                <a title="{{{$event->title}}}" href="{{route('showEventExhibitorDashboard', ['event_id'=>$event->id])}}">
                    {{{ str_limit($event->title, $limit = 75, $end = '...') }}}
                </a>
            </li>
            <li class="event-organiser">
                By <a href='{{route('showOrganiserDashboard', ['organiser_id' => $event->organiser->id])}}'>{{{$event->organiser->name}}}</a>
            </li>
        </ul>

    </div>

    <div class="panel-body">
        <ul class="nav nav-section nav-justified mt5 mb5">
            <li>
                <div class="section">



                    <h4 class="nm">{{ $event->statsExhibitors->where('access_code', 


                        App\Models\Ticket::where('id',

                            App\Models\Exhibitor_ticket::where('event_id',$event->id)
                            ->where('exhibitor_id', 
                                        App\Models\Exhibitor::where('event_id', $event->id)->where('user_id' , $exhibitor->id )->first()['id'] 
                            )
                            ->first()['ticket_id']

                        )->first()['access_code']


                    )
                    ->where('event_id' , $event->id )
                    ->sum('tickets_sold') }}</h4>
                    <p class="nm text-muted">Ticket Sold</p>
                </div>
            </li>

            <li>
                <div class="section">
                    <h4 class="nm">{{ $event->statsExhibitors->where('access_code', 

                          App\Models\Ticket::where('id',

                            App\Models\Exhibitor_ticket::where('event_id',$event->id)
                            ->where('exhibitor_id', 
                                        App\Models\Exhibitor::where('event_id', $event->id)->where('user_id' , $exhibitor->id )->first()['id'] 
                            )
                            ->first()['ticket_id']

                        )->first()['access_code']

                     )
                    ->where('event_id' , $event->id )
                    ->sum('views') }}</h4>
                    <p class="nm text-muted">Event Viewed</p>
                </div>
            </li>
        </ul>
    </div>
    

</div>