@extends('base-layout')
@section('content')

<div class="list-group">
    <a href="#" class="list-group-item active">
        Errors returned by Logger API
    </a>
    <a class="list-group-item">
        <strong>Error 100 - empty API key</strong><div class="row"></div>
        This error appears when 'api-key' field is missing from the API request
    </a>
    <a class="list-group-item">
        <strong>Error 101 - invalid API key</strong><div class="row"></div>
        The given 'api-key' is not valid or does not exists
    </a>
    <a class="list-group-item">
        <strong>Error 102 - empty log message</strong><div class="row"></div>
        This error appears when 'message' field is missing from the API request
    </a>
    <a class="list-group-item">
        <strong>Error 103 - empty log level</strong><div class="row"></div>
        This error appears when 'log-level' field is missing from the API request
    </a>
    <a class="list-group-item">
        <strong>Error 104 - empty log file</strong><div class="row"></div>
        This error appears when 'log-file' field is missing from the API request
    </a>
    <a class="list-group-item">
        <strong>Error 105 - empty log line</strong><div class="row"></div>
        This error appears when 'log-line' field is missing from the API request
    </a>
    <a class="list-group-item">
        <strong>Error 106 - log message is too long</strong><div class="row"></div>
        Appears when max length of 'message' field is surpassed. The max length of log message is 65535 characters
    </a>
    <a class="list-group-item">
        <strong>Error 107 - log file is too long</strong><div class="row"></div>
        This error is returned when max length of 'log-file' field is surpassed. The max length of log file is 65535 characters
    </a>
    <a class="list-group-item">
        <strong>Error 108 - log line is too big</strong><div class="row"></div>
        This error appears when 'log-line' field max value is exceeded. The max value for 'log-line' field is 65535
    </a>
    <a class="list-group-item">
        <strong>Error 109 - invalid log level</strong><div class="row"></div>
        This error is returned when 'log-level' field has an invalid value. Allowed values: 'info', 'debug', 'warning', 'error' and 'emergency'
    </a>
</div>
@stop