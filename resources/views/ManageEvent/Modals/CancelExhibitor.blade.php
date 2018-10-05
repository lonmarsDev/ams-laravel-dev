<div role="dialog"  class="modal fade " style="display: none;">
   {!! Form::model($exhibitor, array('url' => route('postCancelExhibitor', array('event_id' => $exhibitor->event_id, 'exhibitor_id' => $exhibitor->id)), 'class' => 'ajax')) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">
                    <i class="ico-cancel"></i>
                    Cancel <b>{{$exhibitor->first_name}} {{$exhibitor->last_name}}<b></h3>
            </div>
            <div class="modal-body">
                <p>
                    Cancelling Exhibitor will remove them from the Exhibitor list.
                </p>

               
                <br>
                <div class="form-group">
                    <div class="checkbox custom-checkbox">
                        <input type="checkbox" name="notify_exhibitor" id="notify_attendee" value="1">
                        <label for="notify_attendee">&nbsp;&nbsp;Notify <b>{{$exhibitor->first_name}} {{$exhibitor->last_name}}</b> that their exhibitor inivitation has been cancelled.</label>
                    </div>
                </div>
               
            </div> <!-- /end modal body -->
            <div class="modal-footer">
               {!! Form::hidden('exhibitor_id', $exhibitor->id) !!}
               {!! Form::button('Cancel', ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
               {!! Form::submit('Confirm Cancel Exhibitor', ['class'=>"btn btn-success"]) !!}
            </div>
        </div><!-- /end modal content-->
       {!! Form::close() !!}
    </div>
</div>

