@extends('base-layout')
@section('content')
@include('includes.admin-bar')
<script src="<?php echo URL::to('Chart.min.js'); ?>"></script>

<!-- BEGIN Number of users -->
<div class="number-of-users">
    100 registered users of which 10 today
</div>
<!-- END Number of users -->

<!-- BEGIN Active users -->
<div class="active-users">
    <span class="title users-general-title">Monthly active users</span>
    <canvas id="active-users-chart" width="1150" height="400"></canvas>
</div>
<!-- END Active users -->

<script src="<?php echo URL::to('controlPanelUsersCharts.js'); ?>"></script>

<!-- BEGIN Dangerous actions and other details -->
<div class="dangerous-actions-and-other-details">

    <!-- BEGIN Dangerous actions -->
    <div class="dangerous-actions">
        <span class="title">Dangerous user actions</span>
        <div class="box">
            box text
        </div>
    </div>
    <!-- END Dangerous actions -->

    <div class="box-space"></div>

    <!-- BEGIN Other details -->
    <div class="other-details">
        <span class="title">Other statistics</span>
        <div class="box">
            <div class="row">

            </div>
            <div class="row"></div>
            <div class="row"></div>
        </div>
    </div>
    <!-- END Other details -->

</div>
<!-- END Dangerous actions and other details -->

<!-- BEGIN Search users -->
<div class="search-users">
    <span class="title users-general-title">Search for users</span>
    <form name="search-users">
        <input name="email" placeholder="User email">
    </form>
</div>
<!-- END Search for users -->

@stop