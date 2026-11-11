<?php

require __DIR__.'/../vendor/autoload.php';

// Use an ephemeral encryption key for this test process, never a committed key.
$_ENV['APP_KEY'] = $_SERVER['APP_KEY'] = 'base64:'.base64_encode(random_bytes(32));
putenv('APP_KEY='.$_ENV['APP_KEY']);
