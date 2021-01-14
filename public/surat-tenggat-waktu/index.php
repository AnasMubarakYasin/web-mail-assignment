<?php

require_once '../../src/config.php';
require_once '../../src/mail-tb.php';
require_once '../../src/log-script.php';
require_once 'view.php';

$tbMail = new TB\Mail($_configini);

$const = $_configini['mail'];

global $count, $data, $username;

isset($_GET['user']) && $username = $_GET['user'];

if (isset($_POST['search']) && $query = $_POST['search']) {
    $data = $tbMail->searchOnMailDeadline($query);
    
    if ($data) {
        $count = count($data);
    } else {
        $data = [];
        $count = 0;
    }
} else {
    $data = $tbMail->getMailDeadline();

    if ($data) {
        $count = count($data);
    } else {
        $data = [];
        $count = 0;
    }

    // foreach ($data as $key => $value) {
    //     $listId[] = (int) $value['id'];
    // }

    // $isSuccess = $tbMail->updateAlreadyReadMail($listId);

    // $data = $tbMail->getMailOut();
}

render($const);
