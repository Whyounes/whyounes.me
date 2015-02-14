@extends('_layout')

@section('content')
@include('partials.post')
<div id="comments">
	{{ Disqus::comments() }}
</div>
@stop

@section('scripts')
<script>
$(function() {
    var preElement = $('.js-blog-post-body pre').each(function (index) {
        var lineNumbers = '<div class="line-numbers">';
        var numberOfLines = $(this).find('code').html().split(/\n/).length - 1;

        for (var i = 1; i <= numberOfLines; i++) {
            lineNumbers = lineNumbers + i.toString() + "\n";
        }

        lineNumbers = lineNumbers + '</div>';

        $(this).append(lineNumbers);
    });
});
</script>
@stop
