@extends('Shared.Layouts.MasterExhibitor')

@section('title')
@parent

Event Widgets
@stop

@section('top_nav')
@include('ManageEventExhibitor.Partials.TopNav')
@stop

@section('menu')
@include('ManageEventExhibitor.Partials.Sidebar')
@stop

@section('page_title')
<i class='ico-code mr5'></i>
Event Surveys
@stop

@section('head')

@stop

@section('page_header')
<style>
    .page-header {display: none;}
</style>
@stop


@section('content')
<div class="row">


    <div class="col-md-12">

        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>HTML Embed Code</h4>
                            <textarea rows="7" onfocus="this.select();"
                                      class="form-control"><!--Attendize.com Ticketing Embed Code-->
                                        <iframe style='overflow:hidden; min-height: 350px;' frameBorder='0' seamless='seamless' width='100%' height='100%' src="{{$event->embed_url}}?access_code={{$access_code}}" vspace='0' hspace='0' scrolling='auto' allowtransparency='true'></iframe>
                                    <!--/Attendize.com Ticketing Embed Code--></textarea>
                    </div>
                    <div class="col-md-6">
                        <h4>Instructions</h4>

                        <p>
                            Simply copy and paste the HTML provided into your website wherever you would like the widget to appear.
                        </p>

                        <h5>
                            <b>Embed Preview</b>
                        </h5>

                        <div class="preview_embed" style="border:1px solid #ddd; padding: 5px;">
                            <!--Attendize.com Ticketing Embed Code-->
                                        <iframe style='overflow:hidden; min-height: 350px;' frameBorder='0' seamless='seamless' width='100%' height='100%' src="{{$event->embed_url}}?access_code={{$access_code}}" vspace='0' hspace='0' scrolling='auto' allowtransparency='true'></iframe>
                                    <!--/Attendize.com Ticketing Embed Code-->
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@stop
