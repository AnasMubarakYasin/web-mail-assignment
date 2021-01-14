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
    $data = $tbMail->searchOnMailIn($query);
    
    if ($data) {
        $count = count($data);
    } else {
        $data = [];
        $count = 0;
    }
} else {
    $data = $tbMail->getMailIn();

    if ($data === null) {
        $data = [];
        $count = 0;
    } else {
        $count = count($data);
    }

    $listId = [];

    foreach ($data as $key => $value) {
        $listId[] = (int) $value['id'];
    }

    if ($count > 0) {
        $isSuccess = $tbMail->updateAlreadyReadMail($listId);

        $data = $tbMail->getMailIn();
        
        $data = array_reverse($data);
    }
}

render($const);
