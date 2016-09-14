<?php

namespace Messenger;

require(__DIR__ . '/../src/Bootstrap.php');
Bootstrap::getInstance()->process();

// ########################################

$messenger = new Messenger;
$messenger->process();