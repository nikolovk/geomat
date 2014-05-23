<?php

include_once 'config.php';
include_once '../class/register.class.php';
//$data = 0;
if (($_SESSION['logged_in'] != true) || ((int) $_SESSION['user']['rights'] < 5 )) {
    exit;
} else {
    if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        $id = trim($_REQUEST['id']);
        $nickname = trim($_REQUEST['nickname']);
        $email = trim($_REQUEST['email']);
        $name = trim($_REQUEST['name']);
        $egn = trim($_REQUEST['egn']);
        $phone = trim($_REQUEST['phone']);
        if (strlen($nickname) < 3) {
            $data['nickname'] = 'Nickname should be at least 3 letters';
        }
        if (strlen($name) < 3) {
            $data['name'] = 'Name should be at least 3 letters';
        }
        if (register::valid_email($email) != TRUE) {
            $data['email'] = 'Email is invalid';
        }
        if (!$data) {
            $stmt = $db->prepare("UPDATE users
                SET nickname=:nickname, email=:email, name=:name, egn=:egn, phone=:phone
                WHERE id=:id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nickname', $nickname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':egn', $egn);
            $stmt->bindParam(':phone', $phone);
            $stmt->execute();
            $data = 0;
        }
    }
    echo json_encode($data);
}
