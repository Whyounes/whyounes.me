@extends('_layout')

@section('body')
<div class="pageHeader">
    <a class="link--grey" href="/">
        <i class="fa fa-home fa-3x home-button"></i>
    </a>
</div>
<div class="container">
    @include('partials.post')
    <div id="comments">
    	{{ Disqus::comments() }}
    </div>
</div>
@stop
