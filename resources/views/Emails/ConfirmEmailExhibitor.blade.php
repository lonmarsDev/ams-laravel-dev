@extends('Emails.Layouts.Master')

@section('message_content')

<p>Hi {{$first_name}}</p>
<p>
    You are invited tobe an exhibitor on {{ $event_name }}.
</p>

<p>
    
 @if ($is_new_user === 1)
    You can manage your own ticket and attendees on the event and confirm your email using the link below.
 @else

 @endif

</p>


	@if ($is_new_user === 1)
		<div style="padding: 5px; border: 1px solid #ccc;">
	    	{{route('confirmEmailExhibitor', ['confirmation_code' => $confirmation_code])}}
	 	</div>
 	@else

 	@endif
   

<br>
<p>
	@if ($is_new_user === 1)
    	email: {{$email}}
	    <br>
	    password: {{$password}}
 	@else

 	@endif

    
</p>
<br><br>
<p>
    If you have any questions, feedback or suggestions feel free to reply to this email.
</p>
<p>
    Thank you
</p>

@stop

@section('footer')


@stop
