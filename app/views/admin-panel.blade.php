@extends('base-layout')
@section('content')
@include('includes.admin-bar')
<script src="<?php echo URL::to('Chart.min.js'); ?>"></script>

<!-- BEGIN Logs chart -->
<canvas id="active-users-chart" width="1150" height="400"></canvas>
<!-- END Logs chart -->

<!-- BEGIN Used browsers chart -->
<canvas id="used-browsers" width="200" height="200"></canvas>
<!-- END Used browsers chart -->

<!-- BEGIN Active subscriptions chart -->
<canvas id="active-subscriptions" width="200" height="200"></canvas>
<!-- END Active subscriptions chart -->

<!-- Include control panel index page charts -->
<script src="<?php echo URL::to('controlPanelIndexCharts.js'); ?>"></script>
@stop