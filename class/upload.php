<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of upload
 *
 * @author krasi
 */
class upload {

    public static function UploadLotInDatabase($full_name, $id_project, $lot, $term, $common, $db) {
        $sql = "INSERT INTO models (lot, model, term, id_project, note, stage) 
            VALUES (:lot,:model,:term,:id_project,:note,:stage)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':lot', $lot);
        $stmt->bindParam(':term', $term);
        if ($common) {
            $stage = 100;
        } else {
            $stage = 0;
        }
                    $stmt->bindParam(':stage', $stage);
        $stmt->bindParam(':id_project', $id_project);
        $lines = file($full_name);
        foreach ($lines as $line) {
            $value = explode(',', $line);
            $stmt->bindParam(':model', $value[0]);
            $stmt->bindParam(':note', $value[1]);
            $result = $stmt->execute();
        }
        return $result;
    }

}

?>
