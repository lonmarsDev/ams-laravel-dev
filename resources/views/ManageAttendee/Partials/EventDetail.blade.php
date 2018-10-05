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
                <a title="{{{$event->title}}}" href="{{route('showEventDashboard', ['event_id'=>$event->id])}}">
                    {{{ str_limit($event->title, $limit = 75, $end = '...') }}}
                </a>
            </li>
            <li class="event-organiser">
                By <a href='{{route('showOrganiserHome', ['organiser_id' => $event->organiser->id])}}'>{{{$event->organiser->name}}}</a>
            </li>
        </ul>

    </div>

    <div class="panel-body">
        <ul class="nav nav-section nav-justified">
            <li>
                <?php 
                if($attendee->has_arrived == 1){
                    $success = 'btn-success';
                }else{
                    $success = '';
                }
                ?>
                <a href="javascript:void(0);" onclick="checkin({{$event->id}}, {{$attendee->id}})">
                    <i class="ico-checkbox-checked {{$success}}"></i> Check-in
                </a>
            </li>

            <li>
                <a target="_blank" href="{{route('showOrderTickets', ['order_reference' => $attendee->order->order_reference])}}">
                    <i class="ico-ticket"></i> Ticket Preview
                </a>
            </li>

            <li>
                <a target="_blank" href="{{route('showExportTicket', ['event_id' => $event->id, 'attendee_id'=> $attendee->id])}}">
                    <i class="ico-ticket"></i> Print Ticket
                </a>
            </li>
        </ul>
        <object type="text/html" data="{{$event->event_url}}" scrolling="no" onload="resizeIframe(this)" style="width:100%; margin:0;">
        </object>
    </div>

    <div class="panel-footer">
        
    </div>
</div>
<script type="text/javascript">
    function checkin(event_id, attendee_id){
        if($('i.ico-checkbox-checked').hasClass('btn-success'))
            inout = 'out'
        else
            inout = 'in'
        var data = {'attendee_id':attendee_id, "checking" : inout};
        $.ajax({
            url: "/event/"+event_id+"/check_in",
            data: data, 
            type: "POST",
            success: function(result){
                if(result.checked == 'out'){
                    $('i.ico-checkbox-checked').removeClass('btn-success')
                }else{
                    $('i.ico-checkbox-checked').addClass('btn-success')
                }
                showMessage(result.message);
            }
        });
    }

</script>