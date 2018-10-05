@extends('Shared.Layouts.MasterAttendee')

@section('title')
    @parent
    Dashboard
@stop

@section('top_nav')
    @include('ManageAttendee.Partials.TopNav')
@stop
@section('page_title')
    {{ $attendee->first_name }} Dashboard
@stop

@section('menu')
    @include('ManageAttendee.Partials.Sidebar')
@stop

@section('head')

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

    {!! HTML::script('https://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places') !!}
    {!! HTML::script('vendor/geocomplete/jquery.geocomplete.min.js')!!}
    {!! HTML::script('vendor/moment/moment.js')!!}
    {!! HTML::script('vendor/fullcalendar/dist/fullcalendar.min.js')!!}
    {!! HTML::style('vendor/fullcalendar/dist/fullcalendar.css')!!}

    <script>
        $(function() {
           $('#calendar').fullCalendar({
               events: {!! $calendar_events !!},
            header: {
                left:   'prev,',
                center: 'title',
                right:  'next'
            },
            dayClick: function(date, jsEvent, view) {

               }
           });
        });
    </script>
@stop

@section('content')

    <div class="row">

        <div class="col-md-8">
            <h4 style="margin-bottom: 25px;margin-top: 20px;">Event List</h4>
            @if($upcoming_events->count())
                @foreach($upcoming_events as $event)
                    @include('ManageAttendee.Partials.EventPanel')
                @endforeach
            @else
                <div class="alert alert-success alert-lg">
                    You have no events coming up. 
                </div>
            @endif

            <h4 style="margin-bottom: 25px;margin-top: 20px;">Event Calendar</h4>
                    <div id="calendar"></div>
        </div>
        <div class="col-md-4">
            <h4 style="margin-bottom: 25px;margin-top: 20px;"> &nbsp;</h4>
            <ul class="list-group">
                <li class="list-group-item" >
                <div class="stat-box" style="border: 0px">
                    <h3>
                        {{$attendee->event()->count()}}
                    </h3>
                <span>
                    Events
                </span>
                </div>
                </li>
            </ul>

            <h4 style="margin-bottom: 25px;margin-top: 20px;">Recent Orders</h4>
              @if($attendee->order->count())
            <ul class="list-group">
                    @foreach($attendee->order()->orderBy('created_at', 'desc')->take(5)->get() as $order)
                        <li class="list-group-item">
                            <h6 class="ellipsis">
                                <a href="{{ route('showAttendeeEvent', ['attendee_id'=>$attendee->id,'event_id' => $order->event_id]) }}">
                                    {{ $order->event()->get()->first()->title }}
                                </a>
                            </h6>
                            <p class="list-group-text">
                                <a href="javascript:void(0);">
                                    <b>#{{ $order->order_reference }}</b></a> -
                                <a href="#" style="pointer-events: none; cursor: default;">{{ $order->full_name }}</a>
                            </p>
                            <h6>
                                {{ $order->created_at->diffForHumans() }}
                            </h6>
                        </li>
                    @endforeach
                  @else
                            <div class="alert alert-success alert-lg">
                                Looks like there are no recent orders.
                            </div>
                @endif
            </ul>

        </div>
    </div>
@stop
