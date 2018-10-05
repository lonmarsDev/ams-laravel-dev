<style>

.table>thead>tr>th {

    border-bottom:none !important;
}


/*.order_header {
    text-align: center
}

.order_header .massive-icon {
    display: block;
    width: 120px;
    height: 120px;
    font-size: 100px;
    margin: 0 auto;
    color: #63C05E;
}


.order_details.well, .offline_payment_instructions {
    margin-top: 25px;
    background-color: #FCFCFC;
    line-height: 30px;
    text-shadow: 0 1px 0 rgba(255,255,255,.9);
    color: #656565;
    overflow: hidden;
}*/

</style>

<section id="order_form" class="container">
    <div class="row">
        <div class="col-md-12 order_header">
            <span class="massive-icon">
                <img src="{{ url('assets/images/shape.png') }}" alt="checkImage"  >
            </span>
            <h1 class="thankOrder">Thank you for your order!</h1>
            <h2 class="thankOrder1">
                Your <a title="Download Tickets" class="linkemail" href="{{route('showOrderTickets', ['order_reference' => $order->order_reference])}}?download=1">tickets</a> and a confirmation email have been sent to you.
            </h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="content event_view_order">

                @if($event->post_order_display_message)
                <div class="alert alert-dismissable alert-info">
                    {{ nl2br(e($event->post_order_display_message)) }}
                </div>
                @endif

                <div class="order_details well">
                    <div class="row">
                        <div class="col-sm-4 col-xs-6">
                            <b class="formName">First Name</b><br> {{$order->first_name}}
                        </div>

                        <div class="col-sm-4 col-xs-6">
                            <b class="formName">Last Name</b><br> {{$order->last_name}}
                        </div>

                        <div class="col-sm-4 col-xs-6">
                            <b class="formName">Amount</b><br> {{$order->event->currency_symbol}}{{number_format($order->total_amount,2)}}
                        </div>

                        <div class="col-sm-4 col-xs-6">
                            <b class="formName">Reference</b><br> {{$order->order_reference}}
                        </div>

                        <div class="col-sm-4 col-xs-6">
                            <b class="formName">Date</b><br> {{$order->created_at->toDateTimeString()}}
                        </div>

                        <div class="col-sm-4 col-xs-6">
                            <b class="formName">Email</b><br> {{$order->email}}
                        </div>
                    </div>
                </div>


                @if(!$order->is_payment_received)
                <h3>
                    Payment Instructions
                </h3>
                <div class="alert alert-info">
                    This order is awaiting payment. Please read the below instructions on how to make payment.
                </div>
                <div class="offline_payment_instructions well">
                    {!! Markdown::parse($event->offline_payment_instructions) !!}
                </div>

                @endif

                <h3 class="thankOrder1">
                    Order Items
                </h3>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="quantity">
                                <th>
                                    Ticket
                                </th>
                                <th>
                                    Quantity
                                </th>
                                <th>
                                    Price
                                </th>
                                <th>
                                    Booking Fee
                                </th>
                                <th>
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $order_item)
                            <tr class="quantity">
                                <td>
                                    {{$order_item->title}}
                                </td>
                                <td>
                                    {{$order_item->quantity}}
                                </td>
                                <td>
                                    @if((int)ceil($order_item->unit_price) == 0)
                                    FREE
                                    @else
                                    {{money($order_item->unit_price, $order->event->currency)}}
                                    @endif

                                </td>
                                <td>
                                    @if((int)ceil($order_item->unit_price) == 0)
                                    -
                                    @else
                                    {{money($order_item->unit_booking_fee, $order->event->currency)}}
                                    @endif

                                </td>
                                <td>
                                    @if((int)ceil($order_item->unit_price) == 0)
                                    FREE
                                    @else
                                    {{money(($order_item->unit_price + $order_item->unit_booking_fee) * ($order_item->quantity), $order->event->currency)}}
                                    @endif

                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                    <b class="subTotal">Sub Total</b>
                                </td>
                                <td colspan="2">
                                    <b class="subTotal1"> {{money($order->total_amount, $order->event->currency)}}</b>
                                </td>
                            </tr>
                            @if($order->is_refunded || $order->is_partially_refunded)
                            <tr>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                    <b>Refunded Amount</b>
                                </td>
                                <td colspan="2">
                                    {{money($order->amount_refunded, $order->event->currency)}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                    <b>Total</b>
                                </td>
                                <td colspan="2">
                                    {{money($order->total_amount - $order->amount_refunded, $order->event->currency)}}
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                </div>

                <h3 class="orderAttendee">
                    Order Attendees
                </h3>

                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <tbody class="attendesslist">
                            @foreach($order->attendees as $attendee)
                            <tr class="quantity1">
                                <td>
                                    {{$attendee->first_name}}
                                    {{$attendee->last_name}}
                                    (<a href="mailto:{{$attendee->email}}" class="linkemail">{{$attendee->email}}</a>)
                                </td>
                                <td>
                                    {{{$attendee->ticket->title}}}
                                </td>
                                <td>
                                    @if($attendee->is_cancelled)
                                    Cancelled
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>
</section>


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



