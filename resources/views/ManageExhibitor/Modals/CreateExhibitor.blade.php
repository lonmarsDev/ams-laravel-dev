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
    <div class="col-md-12">
        <ol class="list-group">
            <div class="panel">
                <div class="panel-body">
                    <h2>Add New Exhibitor</h2>

                    {!! Form::open(array('url' => route('postCreateExhibitor'), 'class' => 'ajax')) !!}
                    <!-- @if(@$_GET['first_run'] == '1')
                        <div class="alert alert-info">
                            Before you create events you'll need to create an organiser. An organiser is simply whoever is organising the event. It can be anyone, from a person to an organisation.
                        </div>
                    @endif -->

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('first_name', 'First Name', array('class'=>'required control-label ')) !!}
                                {!!  Form::text('first_name', Input::old('first_name'),
                                            array(
                                            'class'=>'form-control'
                                            ))  !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('email', 'Email', array('class'=>'control-label required')) !!}
                                {!!  Form::text('email', Input::old('email'),
                                            array(
                                            'class'=>'form-control ',
                                            'placeholder'=>''
                                            ))  !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('last_name', 'Last Name', array('class'=>'required control-label ')) !!}
                                {!!  Form::text('last_name', Input::old('last_name'),
                                            array(
                                            'class'=>'form-control'
                                            ))  !!}
                            </div>
                        </div>
                    </div>
                    {{ Form::hidden('event_id', $event->id, array('id' => 'event_id')) }}
                    {!! Form::submit('Create Exhibitor', ['class'=>"btn btn-success"]) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </ol>
    </div>
</div>

 @stop
