<div role="dialog"  class="modal fade" style="display: none;">
   {!! Form::open(array('url' => route('postResendTicketToExhibitor', array('exhibitor_id' => $exhibitor->id)), 'class' => 'ajax reset closeModalAfter')) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">
                    <i class="ico-envelope"></i>
                    Resend invitation to {{{$exhibitor->fist_name}}} {{{$exhibitor->last_name}}}
                </h3>
            </div>
            <div class="modal-body">
                <div class="help-block">
                    The exhibitor will receive new set of password <b>{{$exhibitor->email}}</b>
                </div>
            </div> <!-- /end modal body-->
            <div class="modal-footer">
               {!! Form::button('Cancel', ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
               {!! Form::submit('Send Inivitation', ['class'=>"btn btn-success"]) !!}
            </div>
        </div><!-- /end modal content-->
        {!! Form::close() !!}
    </div>
</div>