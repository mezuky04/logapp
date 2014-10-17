@extends('base-layout')
@section('content')
<h4>Get the LogApp SDK</h4>
<span class="text-primary">
    To make easiest the usage of <a href="#">Logger API</a> by developers, we have built LogApp SDK that is available for
    multiple programming languages. With LogApp SDK you can easily log messages with a line of code.
    <div class="row"></div>
    For example, to log an error message using the <a href="#">LogApp PHP SDK</a> all you need to do is:
    <div class="row"></div>
    <span><code><?php echo htmlentities("<?php LogApp::log('An errormessage', 'error'); ?>"); ?></code></span>
</span>
@stop