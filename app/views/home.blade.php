<?php
if (!$loggedIn) {
    $layout = 'home-layout';
} else {
    $layout = 'base-layout';
}
?>

<?php
if (!isset($loggedIn)): ?>
<div class="homepage-header">
    <div class="header-img">
        <a href="<?php echo URL::to('login'); ?>"><button class="login">Log In</button></a>
        <div class="welcome">Welcome to LogApp</div>
        <div class="desc">The easiest way to log and monitorize errors</div>
        <a href="<?php echo URL::to('plans'); ?>"><button class="get-started">Get started here</button></a>
    </div>
</div>
<?php endif; ?>

@extends($layout)
@section('content')
<?php if(!isset($loggedIn)): ?>
    <div class="why"><p>Why LogApp? See below a few reasons</p></div>
    <div class="pres">
        <div class="col-1">
            <div class="image one"></div>
            <div class="title">Simple error logging</div>
            <div class="description">
                <p>Logging errors was never so easy. Using LogApp you can log errors very simple and fast</p>
            </div>
        </div>
        <div class="col-2">
            <div class="image two"></div>
            <div class="title">Get notified</div>
            <div class="description">
                <p>Be updated with the status of your applications. With our real time notification system you will be notified via SMS and email when emergency errors are logged</p>
            </div>
        </div>
        <div class="col-3">
            <div class="image three"></div>
            <div class="title">Easy logs monitorization</div>
            <div class="description">
                <p>Search for errors in your logs files is no more needed. With LogApp it's very simple and easy to monitorize your logs</p>
            </div>
        </div>
    </div>
<?php elseif(isset($loggedIn)): ?>
    <?php if(isset($noLogs)): ?>
        <h3 class="text-primary">You don't have any logs</h3>
    <?php else: ?>
        <h3 class="text-primary">Last <?php echo $numberOfLogs; ?> logs: </h3>
        <table class="table table-striped table-hover ">
            <thead>
            <tr>
                <th>Log level</th>
                <th>Message</th>
                <th>Line</th>
                <th>File</th>
                <th>Application</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($lastLogs as $log): ?>
                <tr>
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
                    <td><?php echo $log->File; ?></td>
                    <td><a href="#">Bitller</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
<?php endif; ?>
@stop