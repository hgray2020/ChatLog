<?php
session_start();
$s = $_REQUEST["s"];
$_SESSION['selected'] = $s;
$i = $_REQUEST["i"];
$_SESSION['toID'] = $i;

?>