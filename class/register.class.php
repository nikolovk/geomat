<?php

/**
 * Description of register
 *
 * @author krasi
 */
class register {

    /**
     * Validate email
     */
    public static function valid_email($str) {
        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str) ) ? FALSE : TRUE;
    }

    /**
     * Check unique
     * Performs a check to determine if one parameter is unique in the database
     */
    public static function checkUnique($field, $compared, $db) {
        $stmt = $db->prepare("SELECT id FROM `users` WHERE :field = :compared");
        $stmt->bindParam(':field', $field);
        $stmt->bindParam(':compared', $compared);
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function insertUser($user, $db) {

        $stmt = $db->prepare("INSERT INTO `users`(`nickname`, `password`, `email`, `name`, `sirname`, `family`, `egn`, `phone`) 
            VALUES (:nickname,:password,:email,:name,:sirname,:family,:egn,:phone)");
        $stmt->bindParam(':nickname', $user[nickname]);
        $stmt->bindParam(':password', md5($user[pass]));
        $stmt->bindParam(':email', $user[email]);
        $stmt->bindParam(':name', $user[name]);
        $stmt->bindParam(':sirname', $user[sirname]);
        $stmt->bindParam(':family', $user[family]);
        $stmt->bindParam(':egn', $user[egn]);
        $stmt->bindParam(':phone', $user[phone]);
        $insert = false;
        $stmt->execute();
        if ($stmt->errorCode() == 0) {
            $insert = true;
        }else {
            $errors =$stmt->errorInfo();
            echo($errors[2]);
        }
       
        
        return $insert;
    }

}

?>
