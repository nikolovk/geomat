<?php

include_once 'config.php';
if (($_SESSION['logged_in'] != true) || ((int) $_SESSION['user']['rights'] < 10 )) {
    exit;
} else {
    if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        $id = trim($_REQUEST['id']);
        $password = trim($_REQUEST['password']);
        $confirm = trim($_REQUEST['confirm']);
        if (strlen($password) < 4) {
            $data['pass'] = 'Password should be at least 4 letters';
        }
        if ($password != $confirm) {
            $data['match'] = 'Passwords don\'t match';
        }
        if (!$data) {
            $stmt = $db->prepare("UPDATE users
                SET password=:password
                WHERE id=:id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':password', md5($password));
            $stmt->execute();
            $data = 0;
        }
    }
    echo json_encode($data);
}
