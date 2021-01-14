<?php 

require_once '../../src/config.php';
require_once '../../src/administrator-tb.php';
require_once '../../src/log-script.php';

$tb = new TB\Administrator($_configini);

session_start();

if (isset($_SESSION['session_login']) && $sessionId = $_SESSION['session_login']) {
    $data = $tb->getBySession($sessionId);
    
    if ($data) {
        unset($_SESSION['session_login']);

        $isSuccess = $tb->updateSession($data['id'], null);

        header("Location: ../login/login.php");
    }
}
