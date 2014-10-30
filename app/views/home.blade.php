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
    <?php if(!empty($userFeed) && count($userFeed) > 1): ?>

        <!-- BEGIN Logs feed -->
        <div class="logs-feed">
            <?php foreach ($userFeed as $feedPost): ?>
                <div class="feed-post">
                    <div class="app">
                        <div class="app-icon"><img src="<?php echo URL::to('icons/home/application.png'); ?>"></div>
                        <div class="app-name"><?php echo $feedPost['Name']; ?></div>
                    </div>
                    <div class="app-logs">
                        <?php $evidence = true; ?>
                        <?php foreach($feedPost['Logs'] as $log): ?>
                            <?php
                                // Check what log class should be displayed
                                if($evidence) {
                                    $class = 'app-log';
                                    $evidence = false;
                                } else {
                                    $class = 'app-log-obscure';
                                    $evidence = true;
                                }
                                // Select a color in base of log level
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
                            <div class="<?php echo $class; ?>">
                                <div class="log-level <?php echo $textColor; ?>"><?php echo ucfirst($log->Level); ?></div>
                                <div class="line-number">
                                    <span class="line-icon">#</span><?php echo $log->Line; ?>
                                </div>
                                <div class="message">
                                    <img src="<?php echo URL::to('icons/home/log-message.png'); ?>" class="log-message-icon">
                                    <span><?php echo $log->Message; ?></span>
                                </div>
                                <div class="file">
                                    <img src="<?php echo URL::to('icons/home/log-file.png'); ?>" class="log-file">
                                    <span><?php //echo $log->File; ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if($feedPost['NumberOfLogs'] > $feedPost['LogsInPost']): ?>
                        <div class="more-logs">
                            <span class="more-logs-text">Show more logs</span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- END Logs feed -->

        <!-- BEGIN Logs statistics -->
        <div class="logs-statistics">
            <div class="statistics-title">Statistics</div>
        </div>
        <!-- END Logs statistics -->

    <?php elseif (!empty($userFeed) && count($userFeed) == 1): ?>
        <!-- Display user feed with one post -->
    <?php elseif (isset($feedError)): ?>
        <!-- User feed was not returned. An error occurred -->
    <?php else: ?>
        <!-- No feed available, display something else -->
    <?php endif; ?>
<?php endif; ?>
@stop