@extends('Emails.Layouts.Master')

@section('message_content')

<p>Hi there,</p>
<p>
    Your invitation as exhibitor on <b>{{{$event_title}}}</b> has been cancelled.
</p>

<p>
    You can contact <b>{{{$organiser_name}}}</b> directly at <a href='mailto:{{{$organiser_email}}}'>{{{$organiser_email}}}</a> or by replying to this email should you require any more information.
</p>
@stop

@section('footer')

@stop
