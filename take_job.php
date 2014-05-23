<?php
include_once 'inc/config.php';
if ($_SESSION['logged_in'] != true) {
    header('Location: login.php');
    exit;
} else {
    $submit = $_POST['_submit_check'];
    include 'inc/head.php';
    if ($submit) {
        $id_project = $_POST['project'];
        $id_activiti = $_POST['activiti'];
        // Search stage in activities
        $sql = "SELECT stage FROM activities WHERE id = $id_activiti";
        $result = $db->query($sql);
        if ($result) {
            $row = $result->fetch();
            $stage_select = $row[stage] - 1;
            $sql_models = "SELECT id, lot, model, term 
                FROM models WHERE stage = $stage_select AND id_project = $id_project
                ORDER BY term,id";
            $stmt_models = $db->prepare($sql_models);
            $stmt_models->execute();
            $models_list = "";
            while ($row = $stmt_models->fetch()) {
                $models_list .= '<tr><td>' . $row[lot] . '</td><td>' . $row[model] . '</td><td>' .
                        date("d-M-Y ", $row[term]) . '</td>';
                $models_list .= '<td><a href="list_job.php?take_id=' .
                        $row[id] . '&id_activiti=' . $id_activiti . '&id_project=' . $id_project . '">Take model</a></td></tr>';
            }
        } else {
            $message = '<p class="error">No such activiti for this project!</p>';
        }
    }
    echo $message;
    ?>
    <form action="take_job.php" method="post">
        <fieldset>
            <legend>Take Job</legend>
            <input type="hidden" name="_submit_check" value="1" />
            <p class="clearfix">
                <label for="project">Projects:</label>
                <select name="project" id="project" onchange="ReadActivities()">
                    <option value="">----</option>
                    <?php
                    $sql_projects = "SELECT id, name FROM projects";
                    foreach ($db->query($sql_projects) as $projects) {
                        echo '<option value="' . $projects[id] . '"';
                        if ($projects[name] == $name_project) {
                            echo 'selected="selected"';
                        }
                        //echo 'onclick="ReadActivities()"';
                        echo '>' . $projects[name] . '</option>';
                    }
                    ?>
                </select>
            </p>
            <p class="clearfix">
                <label for="activiti">Activities:</label>
                <select name="activiti" id="activiti">
                    <option value="">----</option>
                </select>
            </p>
            <input type="submit" name="submit" value="Submit">
        </fieldset>
    </form>
    <?php if ($models_list > "") { ?>
        <table>
            <thead>
                <tr>
                    <th>Lot</th>
                    <th>Model</th>
                    <th>Term</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $models_list; ?>
            </tbody>
        </table>

        <?php
    }
    include 'inc/footer.php';
}
?>