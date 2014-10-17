<!-- BEGIN Admin bar -->
<div class="admin-bar">

    <!-- BEGIN Users feature -->
    <div class="feature">
        <a href="<?php echo URL::to('control-panel/users'); ?>">
            <div class="background turquoise">
                <div class="icon users"></div>
            </div>
        </a>
        <div class="text">
            <div class="value"><?php if(isset($totalUsers)): ?><?php echo $totalUsers; ?><?php else: ?>0<?php endif; ?></div>
            <div class="name"><?php if(isset($totalUsers) && $totalUsers > 1): ?>users<?php else: ?>user<?php endif; ?></div>
        </div>
    </div>
    <!-- END Users feature -->

    <!-- BEGIN Subscriptions feature -->
    <div class="feature">
        <a href="<?php echo URL::to('control-panel/subscriptions'); ?>">
            <div class="background red">
                <div class="icon subscriptions"></div>
            </div>
        </a>
        <div class="text">
            <div class="value">4</div>
            <div class="name">subscriptions</div>
        </div>
    </div>
    <!-- END Subscriptions feature -->

    <!-- BEGIN Settings feature -->
    <div class="feature">
        <a href="#">
            <div class="background yellow">
                <div class="icon settings"></div>
            </div>
        </a>
        <div class="text">
            <div class="special-name">Settings</div>
        </div>
    </div>
    <!-- END Settings feature -->

    <!-- BEGIN Statistics feature -->
    <div class="feature">
        <a href="#">
            <div class="background blue">
                <div class="icon statistics"></div>
            </div>
        </a>
        <div class="text">
            <div class="special-name">Statistics</div>
        </div>
    </div>
    <!-- END Statistics feature -->

</div>
<!-- END Admin bar -->