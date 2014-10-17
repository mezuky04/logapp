@extends('base-layout')
@section('content')
<?php if(isset($hasApplications)): ?>
    <div class="list-group">
        <a href="#" class="list-group-item active">
            Your applications:
        </a>
        <div class="apps">
        <?php foreach($applications as $application): ?>
            <a href="<?php echo URL::to('application/'.$application->ApplicationId); ?>" class="list-group-item"><?php echo $application->Name; ?><span class="badge"><?php echo $application->TotalLogs; ?> logs</span>
            </a>
        <?php endforeach; ?></div>
    </div>
<?php else: ?>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">Oops! You don't have any application</h3>
        </div>
        <div class="panel-body">
            <h4>Looks like you don't have any application added to your LogApp account. You can use the following form to add a new application</h4>
        </div>
    </div>
<?php endif; ?>
<form role="form" class="col-xs-24 col-centered new-app-form" method="post">
    <div id="form-input" class="form-group row has-success">
        <div class="col-xs-6 col-centered">
            <h3>Add new application</h3>
            <label for="code">Application name</label>
            <input type="text" name="app-name" class="app-name-input form-control" placeholder="Only alpha-numeric characters" autocomplete="off"/>

            <p class="app-name-error text-danger">
                <?php if(isset($emptyAppName)): ?>
                    Please enter a name for your new application
                <?php elseif(isset($invalidAppName)): ?>
                    Application name can contain only alpha-numeric characters
                <?php elseif(isset($tooLongAppName)): ?>
                    Application name can have a length of max <?php echo $appNameMaxLength; ?> characters
                <?php elseif(isset($alreadyUsedAppName)): ?>
                    You already have an application with this name. Please use another name
                <?php endif; ?>
            </p>
            <p class="app-name-success text-success">

            </p>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-xs-12">
            <button type="submit" class="btn btn-default add-new-app">Add new app</button>
        </div>
    </div>
</form>
@stop