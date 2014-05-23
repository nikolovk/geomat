<?php
include_once 'inc/config.php';
if ($_SESSION['logged_in'] != true) {
    header('Location: login.php');
    exit;
} else {
    $submit = $_POST['_submit_check'];
    include 'inc/head.php';
    
}
