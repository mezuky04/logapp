@extends('base-layout')
@section('content')
<h3 class="text-primary">Application name: <?php echo $application->Name; ?></h3>

<div class="alert alert-dismissable alert-warning">
    API key: <strong><?php echo $application->APIKey; ?></strong>
</div>

<ul class="nav nav-tabs" style="margin-bottom: 15px;">
    <li class="active"><a href="#logs" data-toggle="tab">Logs</a></li>
    <li class=""><a href="#gen-info" data-toggle="tab">General info</a></li>
    <li class=""><a href="#home" data-toggle="tab">Logs info</a></li>
    <li class=""><a href="#settings" data-toggle="tab">Settings</a></li>
</ul>


<div id="myTabContent" class="tab-content">
    <div class="tab-pane fade active in" id="logs">
        <div class="list-group">
            <?php if(!$noLogs): ?>
                <h3 class="text-primary"><?php echo $application->Name ?> application has no logs </h3>
            <?php else: ?>
                <table class="table table-striped table-hover ">
                    <thead>
                    <tr>
                        <th>Select</th>
                        <th>Log level</th>
                        <th class="message-tbl">Message</th>
                        <th>Line</th>
                        <th>File</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($logs as $log): ?>
                        <tr>
                            <td><input type="checkbox"></td>
                            <?php
                            $textColor = "";
                            switch($log->Level) {
                                case "info":
                                    $textColor = "text-success";
                                    break;
                                case "debug":
                                    $textColor = "text-info";
                                    break;
                                case "warning":
                                    $textColor = "text-primary";
                                    break;
                                case "error":
                                    $textColor = "text-warning";
                                    break;
                                case "emergency":
                                    $textColor = "text-danger";
                                    break;
                            }
                            ?>
                            <td>
                                <strong class="<?php echo $textColor; ?>">
                                    <?php echo $log->Level; ?>
                                </strong>
                            </td>
                            <td>
                                <?php if(strlen($log->Message) > $maxLengths['message']): ?>
                                    <?php echo substr($log->Message, 0, $maxLengths['message'] - 3).'...'; ?>
                                <?php else: ?>
                                    <?php echo $log->Message; ?>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $log->Line; ?></td>
                            <td>
                                <?php if(strlen($log->File) > $maxLengths['file']): ?>
                                    <?php echo substr($log->File, 0, $maxLengths['file'] - 3).'...'; ?>
                                <?php else: ?>
                                    <?php echo $log->File; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php echo $logs->links(); ?>
            <?php endif; ?>
        </div>

    </div>
    <div class="tab-pane" id="gen-info">
        <div class="list-group">
            <a href="#" class="list-group-item active">
                General info:
            </a>
            <a href="#" class="list-group-item">Creation time:<span class="badge"><?php echo '30.07.2014'; ?></span></a>
            <a href="#" class="list-group-item">SMS alerts sent:<span class="badge"><?php echo 2; ?></span></a>
            <a href="#" class="list-group-item">Email alerts sent:<span class="badge"><?php echo 6 ?></span></a>
        </div>

    </div>
    <div class="tab-pane" id="home">
        <div class="list-group">
            <a href="#" class="list-group-item active">
                Logs info:
            </a>
            <a href="#" class="list-group-item">Total logs:<span class="badge"><?php echo $application->TotalLogs; ?></span>
            </a>
            <a href="#" class="list-group-item">Total <strong class="text-success">info</strong> logs:<span class="badge"><?php echo $application->TotalInfoLogs; ?></span></a>
            <a href="#" class="list-group-item">Total <strong class="text-info">debug</strong> logs:<span class="badge"><?php echo $application->TotalDebugLogs; ?></span></a>
            <a href="#" class="list-group-item">Total <strong class="text-primary">warning</strong> logs:<span class="badge"><?php echo $application->TotalWarningLogs; ?></span></a>
            <a href="#" class="list-group-item">Total <strong class="text-warning">error</strong> logs:<span class="badge"><?php echo $application->TotalErrorLogs; ?></span></a>
            <a href="#" class="list-group-item">Total <strong class="text-danger">emergency</strong> logs:<span class="badge"><?php echo $application->TotalEmergencyLogs; ?></span></a>
        </div>

    </div>

    <div class="tab-pane" id="settings">
        <div class="list-group">
            <form id="edit-app" role="form" class="col-xs-24 col-centered" method="post">
                <div class="form-group row has-success" id="app-form">
                    <div class="col-xs-6 col-centered">

                    <div class="text-success success-message"></div>
                    <div class="text-danger fail-message"></div>
                        <input type="hidden" name="application-id" value="<?php echo $application->ApplicationId; ?>">
                        <label for="code"><h4>Application name</h4></label>
                        <input type="text" name="app-name" id="app-name" class="form-control" value="<?php echo $application->Name; ?>" autocomplete="off"/>
                        <p id="fail-message-app" class="text-danger"></p>
                        <p id="success-message-app" class="text-success"></p>


                        <br><br>
                        <h4>SMS and email notifications:</h4>
                        <div class="row"></div>
                        <input name="sms-notifications" type="checkbox"> Notify me via sms when an emergency log appear
                        <div class="row"></div>
                        <input name="email-notifications" type="checkbox"> Notify me via email when an emergency log appear
                        <br><br>
                        <div class="form-group row">
                            <div class="col-xs-12">
                                <button id="save-app-changes" type="submit" class="btn btn-default">Save changes</button>
                            </div>
                        </div>

                        <div class="row"></div>
                        <br>

                        <h4>Reset API key</h4>
                        <div class="row"></div>
                        <p class="text-muted">You can reset your API key using the bellow button. Remember, if you reset your API key you also need to update with the new one your application. Don't share with anyone your API key.</p>
                        <button id="reset-api-key" class="btn btn-info">Reset API key</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

@stop