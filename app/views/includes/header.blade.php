<div class="navbar navbar-inverse navbar-<?php if(isset($fixedHeader)): ?>fixed<?php else: ?>static<?php endif; ?>-top">
    <div class="container">
        <div class="navbar-header">
            <a href="<?php echo URL::to('/'); ?>" class="navbar-brand">LogApp.co</a>
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
            <ul class="nav navbar-nav">
                <li>
                    <a href="<?php echo URL::to('get-started'); ?>">Get started</a>
                </li>
                <li>
                    <?php if(isset($loggedIn)): ?>
                        <a href="<?php echo URL::to('applications'); ?>">Applications</a>
                    <?php else: ?>
                        <a href="<?php echo URL::to('why-logapp'); ?>">Why LogApp</a>
                    <?php endif; ?>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        Documentation
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?php echo URL::to('docs/sdks'); ?>">SDKs</a>
                        </li>
                        <li>
                            <a href="<?php echo URL::to('docs/logger-api'); ?>">Logger API</a>
                        </li>
                        <li>
                            <a href="<?php echo URL::to('docs/error-codes'); ?>">Error codes</a>
                        </li>
                    </ul>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <?php if (isset($loggedIn)): ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <span class="account-email"><?php echo $email; ?></span>
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <?php if($level >= $adminLevel): ?>
                        <li>
                            <a href="<?php echo URL::to('control-panel'); ?>">Control panel</a>
                        </li>
                        <?php endif; ?>
                        <li>
                            <a href="<?php echo URL::to('settings'); ?>">Settings</a>
                        </li>
                        <li>
                            <a href="<?php echo URL::to('logout'); ?>">Log out</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="<?php echo URL::to('help'); ?>">Help</a>
                        </li>
                        <li>
                            <a href="<?php echo URL::to('report-problem'); ?>">Report a problem</a>
                        </li>
                    </ul>
                </li>
                <?php else: ?>
                <li><a href="<?php echo URL::to('login'); ?>">Log in</a></li>
                <li><a href="register">Create new account</a></li>
                <?php endif; ?>
            </ul>

        </div>
    </div>
</div>