<?php
/*
|--------------------------------------------------------------------------
| Application listeners
|--------------------------------------------------------------------------
|
| Application listeners are registered here
|
*/

Event::queue('user.register', 'UserHandler@onRegister');