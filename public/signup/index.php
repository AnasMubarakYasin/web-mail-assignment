<?php 
require_once '../../src/log-script.php';
require_once 'view.php';

$username = '';
$password = '';

if (isset($_POST['username'])) {
    $username = $_POST['username'];
}
if (isset($_POST['password'])) {
    $password = $_POST['password'];
}

signup($username, $password);
