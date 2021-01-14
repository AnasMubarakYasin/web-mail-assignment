<?php 

require_once '../../src/config.php';
require_once '../../src/administrator-tb.php';
require_once '../../src/log-script.php';

$tb = new TB\Administrator($_configini);

$data = $tb->genDefaultData($_POST);

$signupId = uniqid('signup');

$data['signup_id'] = $signupId;

$isSuccess = $tb->insert($data);

if ($isSuccess) {
    header("Location: ../login/?signupid=$signupId");
} else {
    header("Location: ../signup/");
}
