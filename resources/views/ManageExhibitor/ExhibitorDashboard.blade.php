@extends('ManageExhibitor.Shared.Layouts.Master')

@section('title')
    @parent
    Dashboard
@stop

@section('top_nav')
    @include('ManageExhibitor.Partials.TopNav')
@stop
@section('page_title')
    Exhibitor Dashboard
@stop

@section('menu')
    @include('ManageExhibitor.Partials.Sidebar')
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
       
        

    </div>

    <div class="row">

        <div class="col-md-12">

            <h4 style="margin-bottom: 25px;margin-top: 20px;">Event Calendar</h4>
                    <div id="calendar"></div>


           <!--  <h4 style="margin-bottom: 25px;margin-top: 20px;">Upcoming Events</h4> -->
            
        </div>
        
    </div>
@stop
