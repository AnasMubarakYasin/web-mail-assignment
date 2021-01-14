<?php

require_once '../../src/config.php';
require_once '../../src/administrator-tb.php';
require_once '../../src/log-script.php';

$tb = new TB\Administrator($_configini);

session_start();

if (isset($_SESSION['session_login']) && $sessionId = $_SESSION['session_login']) {
    $data = $tb->getBySession($sessionId);
    if ($data) {
        $username = $data['username'];
        $password = $data['password'];
    }
} else if (isset($_POST['username']) && $username = $_POST['username']) {
    $data = $tb->getByUsername($username);
    if ($data) {
        $password = $_POST['password'];
    }
}

if (isset($data)) {
    $hash = $data['algo_hash'];

    if (password_verify($password, $hash)) {

        if (isset($sessionId) === false) {
            $sessionId = uniqid('sessionid');

            $isSuccess = $tb->updateSession((int) $data['id'], $sessionId);

            $_SESSION['session_login'] = $sessionId;
        }

        header("Location: ../dashboard/?user=$username");

        exit;
    }
}

if (isset($sessionId)) {
    unset($_SESSION['session_login']);
}

header("Location: ../login/");
