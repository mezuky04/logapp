@extends('base-layout')
@section('content')
<h2>Two factor auth</h2>
<p>We're happy to answer any question you have so just send a message in the form bellow. Also you can read the <a href="help/faq">FAQ</a> to find a response to your question
<form name="verification-code-form" class="verification-code-form" role="form" method="post" action="<?php echo URL::to('verification-code'); ?>">
    <input type="text" name="verification-code" placeholder="Verification code">
    <button class="verification-code-button">Check</button>
</form>

@stop