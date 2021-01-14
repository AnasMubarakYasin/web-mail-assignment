<?php 

require_once '../../src/config.php';
require_once '../../src/administrator-tb.php';
require_once '../../src/log-script.php';
require_once 'view.php';

$tb = new TB\Administrator($_configini);

session_start();

if (isset($_SESSION['session_login']) && $sessionId = $_SESSION['session_login']) {
    $data = $tb->getBySession($sessionId);
    
    if ($data) {
        $hash = $data['algo_hash'];
        $password = $data['password'];

        header("Location: ../login/login.php");

        exit;
    }
}

if (isset($_GET['signupid']) && $id = $_GET['signupid']) {
    $data = $tb->getBySignup($id);
}

if (isset($data['username']) && isset($data['password'])) {
    login($data['username'], $data['password']);
} else {
    login();
}
