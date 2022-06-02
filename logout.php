<?php
require_once('incs/login.check.inc.php');

@session_start();

unset($_SESSION['userid']);
unset($_SESSION['name']);
@session_destroy();
header('Location: home.php');
