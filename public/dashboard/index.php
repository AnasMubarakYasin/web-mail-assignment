<?php

require_once '../../src/config.php';
require_once '../../src/administrator-tb.php';
require_once '../../src/mail-tb.php';
require_once '../../src/log-script.php';
require_once 'view.php';

global $dataMailDeadline, $dataMailOut, $dataMailIn, $username, $count;


$tbMail = new TB\Mail($_configini);

$tbAdmin = new TB\Administrator($_configini);

session_start();

if (isset($_SESSION['session_login']) && $sessionId = $_SESSION['session_login']) {
    $data = $tbAdmin->getBySession($sessionId);

    $username = $data['username'];
    $count = $tbMail->getCountMail();

    $key1 = 'already_read';
    $key2 = 'count';

    $dataMailIn[$key1] = $tbMail->getCountAlreadyReadMailIn();
    $dataMailIn[$key2] = $tbMail->getCountMailIn();

    $dataMailOut[$key1] = $tbMail->getCountAlreadyReadMailOut();
    $dataMailOut[$key2] = $tbMail->getCountMailOut();

    $dataMailDeadline[$key1] = $tbMail->getCountAlreadyReadMailDeadline();
    $dataMailDeadline[$key2] = $tbMail->getCountMailDeadline();

    render();
}
