<?php

use app\Application;

session_start();

require (dirname(__FILE__)) . '/autoload.php';

$config = require (dirname(__FILE__)) . '/config/app.php';

Application::init($config)->run();
