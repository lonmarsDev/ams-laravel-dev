@extends('Shared.Layouts.Master')

@section('title')
    @parent
    Dashboard
@stop

@section('top_nav')
    @include('ManageExhibitor.Partials.TopNav')
@stop
@section('page_title')
    Manage Exhibitors
@stop

@section('menu')
    @include('ManageEvent.Partials.Sidebar')
@stop

@section('head')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    
    <style>
    svg {
        width: 100% !important;
    }
    </style>
@stop
@section('content')
  
<div class="row">
      <div class="col-md-9">
        <div class="btn-toolbar">
            <div class="btn-group btn-group-responsive">
                <a class="btn btn-success" href="{{route('showCreateExhibitor', ['event_id' => $event->id])}}"><i class="ico-plus"></i>Add New Exhibitor</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
    </div>
  </div>
  <div class="row">
  	<div class="col-md-12">
	  	<ol class="list-group">
	  		@foreach ($exhibitors as $user)
			    <li class="list-group-item">
			    	<a class="col-md-10" href="#">
			    		{{ $user->first_name}} {{$user->last_name }}
			    	</a>
			    	<a class="text-right btn btn-default btn-xs" href="">Edit</a>
			    	<a class="text-right btn btn-danger btn-xs" href="">Delete</a>
			    </li>
			@endforeach
	  	</ol>
	</div>
</div>

 @stop
