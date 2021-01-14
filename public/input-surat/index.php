<?php

require_once 'view.php';

global $role;

if (isset($_GET['role'])) {
    $role = 'admin';
}

render();
