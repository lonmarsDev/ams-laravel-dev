<div role="dialog"  class="modal fade " style="display: none;">
   {!! Form::open(array('url' => route('postInviteExhibitor', array('event_id' => $event->id)), 'class' => 'ajax')) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3 class="modal-title">
                    <i class="ico-user"></i>
                    Invite Exhibitor</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                       

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                {!! Form::label('first_name', 'First Name', array('class'=>'control-label')) !!}

                                {!!  Form::text('first_name', Input::old('first_name'),
                                            array(
                                            'class'=>'form-control'
                                            ))  !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                {!! Form::label('last_name', 'Last Name', array('class'=>'control-label')) !!}

                                {!!  Form::text('last_name', Input::old('last_name'),
                                            array(
                                            'class'=>'form-control'
                                            ))  !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('email', 'Email Address', array('class'=>'control-label required')) !!}

                            {!!  Form::text('email', Input::old('email'),
                                                array(
                                                'class'=>'form-control'
                                                ))  !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('company', 'Company Name', array('class'=>'control-label required')) !!}

                            {!!  Form::text('company', Input::old('company'),
                                                array(
                                                'class'=>'form-control'
                                                ))  !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('contact_no', 'Contact Number', array('class'=>'control-label')) !!}

                            {!!  Form::text('contact_no', Input::old('contact_no'),
                                                array(
                                                'class'=>'form-control'
                                                ))  !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('booth_no', 'Booth Number', array('class'=>'control-label')) !!}

                            {!!  Form::text('booth_no', Input::old('booth_no'),
                                                array(
                                                'class'=>'form-control'
                                                ))  !!}
                        </div>

                        <div class="form-group">
                            <div class="checkbox custom-checkbox">
                                <input type="checkbox" name="email_ticket" id="email_ticket" value="1" />
                                <label for="email_ticket">&nbsp;&nbsp;Send invitation via email.</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="checkbox custom-checkbox">
                                <input type="checkbox" name="ticket_exhibitor" id="ticket_exhibitor" value="1" />
                                <label for="ticket_exhibitor">&nbsp;&nbsp;Associate ticket with access code</label>
                            </div>
                        </div>

                        <!-- Exhibitor ticket section -->

                        <div class="form-group more-options">
                            {!! Form::label('title', 'Ticket Title', array('class'=>'control-label required')) !!}
                            {!!  Form::text('title', $access_code,
                                        array(
                                        'class'=>'form-control',
                                        'placeholder'=>'E.g: General Admission'

                                        ))  !!}
                        </div>

                        <div class="row more-options">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('price', 'Ticket Price', array('class'=>'control-label required')) !!}
                                    {!!  Form::text('price', $ticket_price ,
                                                array(
                                                'class'=>'form-control',
                                                'placeholder'=>'E.g: 25.99'
                                                ))  !!}


                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('quantity_available', 'Quantity Available', array('class'=>' control-label')) !!}
                                    {!!  Form::text('quantity_available', Input::old('quantity_available'),
                                                array(
                                                'class'=>'form-control',
                                                'placeholder'=>'E.g: 100 (Leave blank for unlimited)'
                                                )
                                                )  !!}
                                </div>
                            </div>

                        </div>

                        <div class="row more-options">

                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('access_code', 'Access Code', array('class'=>'control-label required')) !!}
                                    {!!  Form::text('access_code', $access_code,
                                                array(
                                                'class'=>'form-control',
                                                'placeholder'=>'ex123'
                                                ))  !!}
                                </div>
                            </div>

                        </div>


                        <div class="form-group more-options">
                            {!! Form::label('description', 'Ticket Description', array('class'=>'control-label')) !!}
                            {!!  Form::text('description', Input::old('description'),
                                        array(
                                        'class'=>'form-control'
                                        ))  !!}
                        </div>

                        <div class="row more-options more-options">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('start_sale_date', 'Start Sale On', array('class'=>' control-label')) !!}
                                    {!!  Form::text('start_sale_date', Input::old('start_sale_date'),
                                                    [
                                                'class'=>'form-control start hasDatepicker ',
                                                'data-field'=>'datetime',
                                                'data-startend'=>'start',
                                                'data-startendelem'=>'.end',
                                                'readonly'=>''

                                            ])  !!}
                                </div>
                            </div>

                            <div class="col-sm-6 ">
                                <div class="form-group">
                                    {!!  Form::label('end_sale_date', 'End Sale On',
                                                [
                                            'class'=>' control-label '
                                        ])  !!}
                                    {!!  Form::text('end_sale_date', Input::old('end_sale_date'),
                                            [
                                        'class'=>'form-control end hasDatepicker ',
                                        'data-field'=>'datetime',
                                        'data-startend'=>'end',
                                        'data-startendelem'=>'.start',
                                        'readonly'=>''
                                    ])  !!}
                                </div>
                            </div>
                        </div>

                        <div class="row more-options">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('min_per_person', 'Minimum Tickets Per Order', array('class'=>' control-label')) !!}
                                    {!! Form::selectRange('min_per_person', 1, 100, 1, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('max_per_person', 'Maximum Tickets Per Order', array('class'=>' control-label')) !!}
                                    {!! Form::selectRange('max_per_person', 1, 100, 4, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row more-options">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="custom-checkbox">
                                        {!! Form::checkbox('is_hidden', 1, false, ['id' => 'is_hidden']) !!}
                                        {!! Form::label('is_hidden', 'Hide this ticket', array('class'=>' control-label')) !!}
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- End of Exhibitor ticket section -->
                    </div>

                    <div class="col-md-12">
                        <a href="javascript:void(0);" class="show-more-options">
                            More Options
                        </a>
                    </div>

                </div>
            </div> <!-- /end modal body-->
            <div class="modal-footer">
               {!! Form::button('Cancel', ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
               {!! Form::submit('Invite Exhibitor', ['class'=>"btn btn-success"]) !!}
            </div>
        </div><!-- /end modal content-->
       {!! Form::close() !!}
    </div>
</div>
