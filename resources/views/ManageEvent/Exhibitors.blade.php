@extends('Shared.Layouts.Master')

@section('title')
@parent
Event Exhibitors
@stop


@section('page_title')
<i class="ico-users"></i>
Exhibitors
@stop

@section('top_nav')
@include('ManageEvent.Partials.TopNav')
@stop

@section('menu')
@include('ManageEvent.Partials.Sidebar')
@stop


@section('head')

@stop

@section('page_header')

<div class="col-md-9">
    <div class="btn-toolbar" role="toolbar">
        <div class="btn-group btn-group-responsive">
            <button data-modal-id="InviteAttendee" href="javascript:void(0);"  data-href="{{route('showInviteExhibitor', ['event_id'=>$event->id])}}" class="loadModal btn btn-success" type="button"><i class="ico-user-plus"></i> Add / Invite Exhibitor</button>
        </div>
    
    </div>
</div>
<div class="col-md-3">
   {!! Form::open(array('url' => route('showEventExhibitors', ['event_id'=>$event->id,'sort_by'=>$sort_by]), 'method' => 'get')) !!}
    <div class="input-group">
        <input name="q" value="{{$q or ''}}" placeholder="Search Exhibitors.." type="text" class="form-control" />
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit"><i class="ico-search"></i></button>
        </span>
    </div>
   {!! Form::close() !!}
</div>
@stop


@section('content')

<!--Start Attendees table-->
<div class="row">
    <div class="col-md-12">
        @if($exhibitors->count())
        <div class="panel">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                               {!!Html::sortable_link('Name', $sort_by, 'first_name', $sort_order, ['q' => $q , 'page' => $exhibitors->currentPage()])!!}
                            </th>
                            <th>
                               {!!Html::sortable_link('Email', $sort_by, 'email', $sort_order, ['q' => $q , 'page' => $exhibitors->currentPage()])!!}
                            </th>
                            <th>
                               {!!Html::sortable_link('Company', $sort_by, 'company_name', $sort_order, ['q' => $q , 'page' => $exhibitors->currentPage()])!!}
                            </th>
                            <th>
                               {!!Html::sortable_link('Contact No.', $sort_by, 'phone', $sort_order, ['q' => $q , 'page' => $exhibitors->currentPage()])!!}
                            </th>
                            <th>
                               {!!Html::sortable_link('Booth No.', $sort_by, 'booth_no', $sort_order, ['q' => $q , 'page' => $exhibitors->currentPage()])!!}
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($exhibitors as $exhibitor)
                        <tr class="attendee_{{$exhibitor->id}} {{$exhibitor->is_cancelled ? 'danger' : ''}}">
                            <td>
                                {{$exhibitor->first_name}} {{$exhibitor->last_name}} </td>
                            <td>
                                <a data-modal-id="MessageAttendee" href="javascript:void(0);" class="loadModal"
                                    data-href="{{route('showMessageAttendee', ['attendee_id'=>$exhibitor->id])}}"
                                    > {{$exhibitor->email}}
                                </a>
                            </td>
                            <td>
                               {{$exhibitor->company_name}} 
                            </td>
                            <td>
                               {{$exhibitor->phone}} 
                            </td>
                             <td>
                               {{$exhibitor->booth_no}} 
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        @if($exhibitor->email)
                                        <!-- <li><a
                                            data-modal-id="MessageAttendee"
                                            href="javascript:void(0);"
                                            data-href="{{route('showMessageAttendee', ['attendee_id'=>$exhibitor->id])}}"
                                            class="loadModal"
                                            > Message</a></li> -->
                                        @endif
                                        <li><a
                                            data-modal-id="ResendTicketToAttendee"
                                            href="javascript:void(0);"
                                            data-href="{{route('showResendTicketToExhibitor', ['exhibitor_id'=>$exhibitor->id])}}"
                                            class="loadModal"
                                            > Resend email invitation</a></li>
                                        
                                    </ul>
                                </div>

                                <a
                                    data-modal-id="EditAttendee"
                                    href="javascript:void(0);"
                                    data-href="{{route('showEditExhibitor', ['event_id'=>$event->id, 'exhibitor_id'=>$exhibitor->id])}}"
                                    class="loadModal btn btn-xs btn-primary"
                                    > Edit</a>

                                <a
                                    data-modal-id="CancelAttendee"
                                    href="javascript:void(0);"
                                    data-href="{{route('showCancelExhibitor', ['event_id'=>$event->id, 'exhibitor_id'=>$exhibitor->id])}}"
                                    class="loadModal btn btn-xs btn-danger"
                                    > Cancel</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else

        @if(!empty($q))
        @include('Shared.Partials.NoSearchResults')
        @else
        @include('ManageEvent.Partials.ExhibitorsBlankSlate')
        @endif

        @endif
    </div>
    <div class="col-md-12">
        {!!$exhibitors->appends(['sort_by' => $sort_by, 'sort_order' => $sort_order, 'q' => $q])->render()!!}
    </div>
</div>    <!--/End attendees table-->

@stop


