<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="<?php echo URL::to('style.css'); ?>">
    <script src="<?php echo URL::to('jquery-min.js'); ?>"></script>
    <link href="//maxcdn.bootstrapcdn.com/bootswatch/3.2.0/flatly/bootstrap.min.css" rel="stylesheet">
    <!--<link rel="stylesheet" href="<?php URL::to('bootstrap.min.css'); ?>">-->
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300' rel='stylesheet' type='text/css'>
</head>
<body>
<div class="container content">
    @yield('content')
</div>
@include('includes.footer')
</body>
</html>