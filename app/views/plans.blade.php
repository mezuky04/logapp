<!doctype html>
<html>

<!-- BEGIN Head -->
<head>
    <title><?php if(isset($pageTitle)) echo $pageTitle; ?></title>
    <link rel="stylesheet" href="<?php echo URL::to('style.css'); ?>">
    <script src="<?php echo URL::to('jquery-min.js'); ?>"></script>
    <script src="<?php echo URL::to('script.js') ?>"></script>
<!--    <link rel="stylesheet" href="--><?php //echo URL::to('bootstrap.min.css'); ?><!--">-->
</head>
<!-- END Head -->

<!-- BEGIN Body -->
<body id="plans-page">
    <span class="text">Choose a plan in order to create an account</span>
    <?php if (isset($plans) && count($plans)): ?>
        <!-- BEGIN Plans -->
        <div class="plans">
            <?php foreach($plans as $plan): ?>
                <div class="<?php if($plan->Special): ?>special-plan<?php else: ?>normal-plan<?php endif; ?>">
                    <div class="name"><?php echo $plan->Name; ?></div>
                    <div class="price"><?php if ($plan->Price < 1): ?>FREE<?php else: ?><?php echo $plan->Price.' '; ?><span class="dollar">$</span><?php endif; ?></div>
                    <div class="items">
                        <?php foreach($plan->Items as $item): ?>
                            <div class="item<?php if(end($plan->Items) == $item): ?> padding-bottom<?php endif; ?>">
                                <div class="<?php if($item->IsAvailable): ?>icon<?php else: ?>icon-not-available<?php endif; ?>"></div>
                                <div class="description"><?php echo $item->Description; ?></div>
                            </div>
                        <?php endforeach; ?>
                        <div class="select">
                            <a href="<?php echo URL::to('register?subscription-plan='.$plan->Key); ?>"><button class="select-plan">Select</button></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- END Plans -->

        <!-- BEGIN Already have an account -->
        <div class="already-have-an-account">
            <div class="login-text">Already have an account?</div>
            <a href="<?php echo URL::to('login'); ?>"<button class="to-login">Log In</button>
        </div>
        <!-- END Already have an account -->
    <?php else: ?>
    <?php endif; ?>
</body>
<!-- END Body -->

</html>