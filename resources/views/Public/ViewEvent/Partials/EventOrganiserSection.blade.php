
<section id="organiser" class="container">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <img src="{{ url('/assets/images/group-11.png') }} "
                class="Group-11" alt="imageData">
            </div>


            <div class="row organizerPalm1">
                <h4 class="organizerPalm">
                    Organizer of Art Palm Beach 2018
                </h4>

                <p class="organizerDescription"> Next Level Fairs are the quintissential fine art fair organizers. In striving to reach the next level of fair presentation committed to developing the ever more innovative fairs for dealers, collectors and cultural visitors attending our events. </p>
            </div>

        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="event_organiser_details" property="organizer" typeof="Organization">
              <!--   <div class="logo">
                    <img alt="{{$event->organiser->name}}" src="{{asset($event->organiser->full_logo_path)}}" property="logo">
                </div>
                    @if($event->organiser->enable_organiser_page)
                    <a href="{{route('showOrganiserHome', [$event->organiser->id, Str::slug($event->organiser->name)])}}" title="Organiser Page">
                        {{$event->organiser->name}}
                    </a>
                    @else
                        {{$event->organiser->name}}
                    @endif
                </h3>

                <p property="description">
                    {!! nl2br($event->organiser->about)!!}
                </p> -->
                <p>
                    <!-- @if($event->organiser->facebook)
                    <a property="sameAs" href="https://fb.com/{{$event->organiser->facebook}}" class="btn btn-facebook">
                        <i class="ico-facebook"></i>&nbsp; Facebook
                    </a>
                    @endif
                    @if($event->organiser->twitter)
                    <a property="sameAs" href="https://twitter.com/{{$event->organiser->twitter}}" class="btn btn-twitter">
                        <i class="ico-twitter"></i>&nbsp; Twitter
                    </a>
                    @endif -->
                    <button onclick="$(function(){ $('.contact_form').slideToggle(); });" type="button" class="btn btn-primary newButton">
                       </i>&nbsp; Contact us
                    </button>

                     <button type="button" class="btn btn-primary newButton1">
                       </i>&nbsp; Visit Website
                    </button>
                </p>
                <div class="contact_form well well-sm">
                    {!! Form::open(array('url' => route('postContactOrganiser', array('event_id' => $event->id)), 'class' => 'reset ajax')) !!}
                    <h3 class="contactForm">Contact <i>{{$event->organiser->name}}</i></h3>
                    <div class="form-group">
                        {!! Form::label('Your Name',null, array('class' => 'contactLabel')) !!}
                        {!! Form::text('name', null,
                        array('required',
                        'class'=>'form-control contactName'
                       )) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('Your E-mail Address',null, array('class' => 'contactLabel')) !!}
                        {!! Form::text('email', null,
                        array('required',
                        'class'=>'form-control contactForm1'
                        )) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('Your Message',null, array('class' => 'contactLabel')) !!}
                        {!! Form::textarea('message', null,
                        array('required',
                        'class'=>'form-control contactForm1',
                        'rows' =>1

                        )) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::submit('Send Message',
                        array('class'=>'btn btn-primary submitContact')) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>

