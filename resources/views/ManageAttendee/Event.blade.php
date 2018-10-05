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
    <script>
      function resizeIframe(obj) {
        obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
      }
    </script>

    {!! HTML::script('https://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places') !!}
    {!! HTML::script('vendor/geocomplete/jquery.geocomplete.min.js')!!}
    {!! HTML::script('vendor/moment/moment.js')!!}
    {!! HTML::script('vendor/fullcalendar/dist/fullcalendar.min.js')!!}
    {!! HTML::style('vendor/fullcalendar/dist/fullcalendar.css')!!}
@stop

@section('content')
    <div class="row">

        <div class="col-md-12">
            @if(isset($event))
                @include('ManageAttendee.Partials.EventDetail')
            @endif
        </div>
    </div>
@stop
