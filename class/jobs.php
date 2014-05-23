<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of jobs
 *
 * @author krasi
 */
class jobs {

    public static function StartCommonModel($id_model, $id_activiti, $id_project, $db) {
        $sql = "";

        // Insert new record in work for started model
        $st_start_job = $db->prepare("INSERT INTO work (id_activiti,id_model,id_user,start, id_project)
                VALUES (:id_activiti, :id_model, :id_user, :start, :id_project)");
        $st_start_job->bindParam(':id_activiti', $id_activiti);
        $st_start_job->bindParam(':id_model', $id_model);
        $st_start_job->bindParam(':id_user', $_SESSION['user']['id']);
        $st_start_job->bindParam(':start', time());
        $st_start_job->bindParam(':id_project', $id_project);
        $result_insert = $st_start_job->execute();

        if ($result_insert) {
            return 'Model started successfully';
        }
    }

    public static function StartModel($take_id, $id_activiti, $id_project, $db) {
        // Take stage for activiti
        $sql_stage = "SELECT stage FROM activities WHERE id = $id_activiti";
        $row = $db->query($sql_stage)->fetch();
        // Check if model is still available
        $sql_check = "SELECT * FROM models WHERE id = $take_id AND stage = $row[stage]-1";
        $result = $db->query($sql_check);

        if ($result->rowCount() > 0) {

            // Insert new record in work for started model
            $st_start_job = $db->prepare("INSERT INTO work (id_activiti,id_model,id_user,start, id_project)
                VALUES (:id_activiti, :id_model, :id_user, :start, :id_project)");
            $st_start_job->bindParam(':id_activiti', $id_activiti);
            $st_start_job->bindParam(':id_model', $take_id);
            $st_start_job->bindParam(':id_user', $_SESSION['user']['id']);
            $st_start_job->bindParam(':start', time());
            $st_start_job->bindParam(':id_project', $id_project);
            $result_insert = $st_start_job->execute();

            // add stage in models
            $db->query("UPDATE models SET stage = stage + 1 WHERE id = $take_id");
            if ($result_insert) {
                return 'Model started successfully';
            }
        }
    }

    public static function FinishModel($id, $count, $time, $id_model, $stage, $db) {
        $st = $db->prepare("UPDATE work SET count = :count, time = :time, end = :end  WHERE id = :id");
        $st->bindParam(':count', $count);
        $st->bindParam(':time', $time);
        $st->bindParam(':end', time());
        $st->bindParam(':id', $id);
        $st->execute();
        $db->query("UPDATE models SET stage = $stage + 1 WHERE id = $id_model");
        if ($st->rowCount() > 0) {
            return 'Model Finished Successfully!';
        }
    }

    public static function GetCol($array, $col) {
        $list = array();
        foreach ($array as $row) {
            if (!in_array($row[$col], $list)) {
                $list[$row[id_project]] = $row[$col];
            }
        }
        return $list;
    }

}

?>
