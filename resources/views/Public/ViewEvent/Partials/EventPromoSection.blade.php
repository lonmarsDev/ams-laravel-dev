<section id="details" class="container">
    <div class="row">
        <h1 class="section_head">
            Access Code
        </h1>
    </div>
    <div class="row">
        {!! Form::open(['method' => 'get','url' => route('showEventPage', ['event_id' => $event->id , 'event_slug' => $slug ] ) ]) !!}
        <div class="content event_promo" property="description">
            <div class="col-lg-6 col-md-6">
                <div class="form-group">
                     {!! Form::text("access_code", $access_code, ['required' => 'required', 'class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="form-group">
                     {!! Form::submit('Enter Access Code', ['class' => 'btn btn-lg btn-success card-submit submitCheckout', 'style' => 'width:100%;']) !!}
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    
    </div>
</section>