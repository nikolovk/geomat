<?php

include_once 'config.php';
$data = 0;
if ($_SESSION['logged_in'] != true) {
    exit;
} else {
    if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        $id_model = trim($_REQUEST['id_model']);
        $id_project = trim($_REQUEST['id_project']);
        $id_activiti = trim($_REQUEST['id_activiti']);
        // Take stage for activiti
        $sql_stage = "SELECT stage FROM activities WHERE id = $id_activiti";
        $row = $db->query($sql_stage)->fetch();
        $stage = $row['stage'];
        if ($stage < 100) {
            // Check if model is still available
            $sql_check = "SELECT * FROM models WHERE id = $id_model AND stage = $stage -1";
            $result = $db->query($sql_check);
            if ($result->rowCount() > 0) {
                // Insert new record in work for started model
                $st_start_job = $db->prepare("INSERT INTO work (id_activiti,id_model,id_user,start, id_project)
                VALUES (:id_activiti, :id_model, :id_user, :start, :id_project)");
                $st_start_job->bindParam(':id_activiti', $id_activiti);
                $st_start_job->bindParam(':id_model', $id_model);
                $st_start_job->bindParam(':id_user', $_SESSION['user']['id']);
                $st_start_job->bindParam(':start', time());
                $st_start_job->bindParam(':id_project', $id_project);
                $result_insert = $st_start_job->execute();

                // add stage in models
                $db->query("UPDATE models SET stage = stage + 1 WHERE id = $id_model");
                if ($result_insert) {
                    $data = 1;
                }
            }
        } else {
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
                $data = 1;
            }
        }
    }
    echo json_encode($data);
}

    