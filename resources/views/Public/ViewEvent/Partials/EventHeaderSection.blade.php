@if(!$event->is_live)
<section id="goLiveBar">
    <div class="container">
        @if(!$event->is_live)
        This event is not visible to the public - <a style="background-color: green; border-color: green;" class="btn btn-success btn-xs" href="{{route('MakeEventLive' , ['event_id' => $event->id])}}" >Publish Event</a>
        @endif
    </div>
</section>
@endif
<section id="organiserHead" class="container-fluid">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div onclick="window.location='{{$event->event_url}}#organiser'" class="event_organizer">
                    <b>{{$event->organiser->name}}</b> Presents
                </div>
            </div>
        </div>
    </div>
</section>


<section class="imagesData">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <img src="{{ url('/assets/images/group.png') }} "
                class="Group" alt="imageData">
            </div>
        </div>
    </div>
</section>

<section id="intro" class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- <h1 property="name" class="colorTitle">{{$event->title}}</h1> -->
            <div class="event_venue">
                <!-- <span class="eventDate">  
                  <div>Wed Jan 17th - 6PM-10PM</div>
                  <div>Thu Jan 18th - 12PM-7PM</div>
                  <div>Fri Jan 19th - 12PM-7PM</div>
                  <div>Sat Jan 20th - 12PM-6PM</div>
                </span>   -->
                <span property="startDate" class="eventDate" content="{{ $event->start_date->toIso8601String() }}">
                    <!-- {{ $event->start_date->format('D d M H:i A') }} -->

                <!--   Wed Jan 17th - 6PM-10PM
                  <br>Thu Jan 18th - 12PM-7PM
                  <br>Fri Jan 19th - 12PM-7PM
                  <br>Sat Jan 20th - 12PM-6PM  -->

                </span>
               <!--  -
                <span property="endDate" class="eventDate" content="{{ $event->end_date->toIso8601String() }}">
                 @if($event->start_date->diffInHours($event->end_date) <= 12)
                 {{ $event->end_date->format('H:i A') }}
                 @else
                 {{ $event->end_date->format('D d M H:i A') }}
                 @endif
             </span> -->
            
            <!--  
             <span property="startDate" class="eventDate" content="{{ $event->start_date->toIso8601String() }}">
                Wed Jan 17th - 6PM-10PM
                <br>Thu Jan 18th - 12PM-7PM
                <br>Fri Jan 19th - 12PM-7PM
                <br>Sat Jan 20th - 12PM-6PM 

              </span> -->

              <center><span>Wed Jan 17th - 6PM-10PM</span></center>
              <center><span>Thu Jan 18th - 12PM-7PM</span></center>
              <center><span>Fri Jan 19th - 12PM-7PM</span></center>
              <center><span>Sat Jan 20th - 12PM-7PM</span></center>
              <center><span>Sun Jan 21th - 12PM-6PM</span></center>
              <center>{{$event->venue_name}}</center>
             <!--  <ul class="eventDate" class="eventDate">
                <li>Wed Jan 17th - 6PM-10PM</li>
                <li>Thu Jan 18th - 12PM-7PM</li>
                <li>Fri Jan 19th - 12PM-7PM</li>
                <li>Sat Jan 20th - 12PM-6PM</li>

              </ul> -->

             <!--  <span property="startDate" class="eventDate" content="{{ $event->start_date->toIso8601String() }}">Wed Jan 17th - 6PM-10PM</span>
              *
              <span property="startDate" class="eventDate" content="{{ $event->start_date->toIso8601String() }}">Wed Jan 17th - 6PM-10PM</span>
              *
              <span property="startDate" class="eventDate" content="{{ $event->start_date->toIso8601String() }}">Wed Jan 17th - 6PM-10PM</span>
              *
              <span property="startDate" class="eventDate" content="{{ $event->start_date->toIso8601String() }}">Wed Jan 17th - 6PM-10PM</span> -->

             
             <span property="location" class="eventDate" typeof="Place">
                <!-- {{$event->venue_name}} -->
                <meta property="address" content="{{ urldecode($event->venue_name) }}">
            </span>
        </div>

        <div class="event_buttons">
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <a class="btn btn-event-link btn-lg customCode" href="{{{$event->event_url}}}#tickets">TICKETS</a>
                </div>
                <div class="col-md-4 col-sm-4">
                    <a class="btn btn-event-link btn-lg customCode" href="{{{$event->event_url}}}#details">DETAILS</a>
                </div>
                <div class="col-md-4 col-sm-4">
                    <a class="btn btn-event-link btn-lg customCode" href="{{{$event->event_url}}}#location">LOCATION</a>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

<style type="text/css">

/*.Group {
  width: 100%;
  height: 163px;
  object-fit: contain;
}
body{

    background:white !important;
}
.colorTitle{

    color:#B7B7B7 !important;
    font-family: Lato;
}
#event_page_wrap {

    background:white !important;
}
a.btn.btn-event-link.btn-lg.customCode {
     background: white !important;
     color:#B7B7B7 !important;
     font-weight: 900;

}
#event_page_wrap #intro .event_venue {
   
     color:#B7B7B7 !important;
}
.imagesData{

    padding:50px;
}
*/
</style>