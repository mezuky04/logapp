@extends('base-layout')
@section('content')
<h2>Contact us</h2>
<p>We're happy to answer any question you have so just send a message in the form bellow. Also you can read the <a href="help/faq">FAQ</a> to find a response to your question
<form role="form" method="post">
    <div class="form-group row has-<?php if(isset($subjectError)): ?>error<?php else: ?>success<?php endif; ?>">
        <div class="col-xs-6 col-centered">
            <label for="code">Subject</label>
            <input type="text" name="subject" class="form-control" placeholder="Subject" <?php if(isset($_POST['subject'])): ?>value="<?php echo $_POST['subject']; ?>"<?php endif; ?>/>
            <?php if(isset($subjectError)): ?>
                <p class="text-danger">
                    <?php if(isset($emptySubject)): ?>
                        Empty subject
                    <?php elseif(isset($subjectTooShort)): ?>
                        Subject should have at least <?php echo $subjectMinLength; ?> characters length
                    <?php elseif(isset($subjectTooLong)): ?>
                        Subject can have a max length of <?php echo $subjectMaxLength; ?> characters
                    <?php elseif(isset($invalidSubject)): ?>
                        Invalid subject
                    <?php endif; ?>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <div class="form-group row has-<?php if(isset($nameError)): ?>error<?php else: ?>success<?php endif; ?>">
        <div class="col-xs-6 col-centered">
            <label for="code">Name</label>
            <input type="text" name="name" class="form-control" placeholder="Your name" <?php if(isset($_POST['name'])): ?>value="<?php echo $_POST['name']; ?>"<?php endif; ?> <?php if(isset($nameError)): ?>autofocus<?php endif; ?>/>
            <?php if(isset($nameError)): ?>
                <p class="text-danger">
                    <?php if (isset($emptyName)): ?>
                        Empty name
                    <?php elseif(isset($invalidName)): ?>
                        Name can contain only alphabetical characters
                    <?php elseif(isset($nameTooShort)): ?>
                        Name should have at least <?php echo $nameMinLength; ?> characters length
                    <?php elseif(isset($nameTooLong)): ?>
                        Name can have a max length of <?php echo $nameMaxLength; ?> characters
                    <?php endif; ?>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!isset($loggedIn)): ?>
    <div class="form-group row has-<?php if(isset($emailError)): ?>error<?php else: ?>success<?php endif; ?>">
        <div class="col-xs-6">
            <label for="code">Email</label>
            <input type="text" name="email" class="form-control" placeholder="Your email" <?php if(isset($_POST['email'])): ?>value="<?php echo $_POST['email']; ?>" <?php endif; ?> <?php if(isset($emailError)): ?>autofocus<?php endif; ?>/>
            <?php if(isset($emailError)): ?>
                <p class="text-danger">
                    <?php if(isset($emptyEmail)): ?>
                        Please enter an email where to receive the response
                    <?php elseif(isset($invalidEmail)): ?>
                        Please enter a valid email
                    <?php endif; ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="form-group row has-<?php if(isset($messageError)): ?>error<?php else: ?>success<?php endif; ?>">
        <div class="col-xs-6">
            <label for="code">Message</label>
            <textarea name="message" class="form-control" rows="5" placeholder="Type your message here"><?php if(isset($_POST['message'])) {echo $_POST['message'];} ?></textarea>
            <?php if(isset($messageError)): ?>
                <p class="text-danger">
                    <?php if (isset($emptyMessage)): ?>
                        Please enter a message
                    <?php elseif(isset($messageTooShort)): ?>
                        Message should have at least <?php echo $messageMinLength; ?> characters length
                    <?php elseif(isset($messageTooLong)): ?>
                        Message can have a max length of <?php echo $messageMaxLength; ?> characters
                    <?php endif; ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-xs-12">
            <button type="submit" class="btn btn-default">Contact</button>
        </div>
    </div>
</form>

@stop