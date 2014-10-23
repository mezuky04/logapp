<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="<?php echo URL::to('style.css'); ?>">
    <script src="<?php echo URL::to('jquery-min.js'); ?>"></script>
    <script src="<?php echo URL::to('script.js'); ?>"></script>
    <link rel="stylesheet" href="<?php echo URL::to('bootstrap.min.css'); ?>">
</head>
<body<?php if(!empty($bodyId)): ?> id="<?php echo $bodyId; ?>"<?php endif; ?>>
<div class="mask">
    <div class="load">
        <div class="loader"></div>
    </div>
</div>
@include('includes.header')
<div class="container content">
    @yield('content')
</div>
@include('includes.footer')
</body>
</html>