<?php
include_once 'inc/config.php';
include_once 'class/jobs.php';
if ($_SESSION['logged_in'] != true) {
    header('Location: login.php');
    exit;
} else {
    include 'inc/head.php';
    if ($_POST['_submit_check']) {
        $project_post = trim($_POST['project']);
        $lot_post = trim($_POST['lot']);
        $sql_select_models = 'SELECT projects.name as project, projects.id as id_project, models.lot, models.term, models.model, 
        activities.name as activiti, users.name as user,
        work.id as id, work.start, work.end, work.count, work.time
        FROM work
        JOIN projects ON work.id_project = projects.id
        JOIN models ON work.id_model = models.id
        JOIN activities ON work.id_activiti = activities.id
        JOIN users ON work.id_user = users.id
        WHERE projects.id = ' . $project_post . ' AND models.lot = "' . $lot_post . '"
        ORDER BY work.start, project, lot, id';
    } else {
        $sql_select_models = 'SELECT projects.name as project, projects.id as id_project, models.lot, models.term, models.model, 
        activities.name as activiti, users.name as user,
        work.id as id, work.start, work.end, work.count, work.time
        FROM work
        JOIN projects ON work.id_project = projects.id
        JOIN models ON work.id_model = models.id
        JOIN activities ON work.id_activiti = activities.id
        JOIN users ON work.id_user = users.id
        ORDER BY work.start DESC, project, lot, id';
    }
   // echo $sql_select_models;
    $result_select = $db->query($sql_select_models);
    if ($result_select) {


        $models = $result_select->fetchAll(PDO::FETCH_ASSOC);
        $projects = jobs::GetCol($models, "project");
        ?>
        <form action="full_list_job.php" method="post">
            <fieldset>
                <legend>Jobs in process</legend>
                <input type="hidden" name="_submit_check" value="1" />
                <p class="clearfix">
                    <label for="project">Projects:</label>
                    <select name="project" id="project" onchange="ReadLots()">
                        <option value="">----</option>
                        <?php
                        $sql_projects = "SELECT id, name FROM projects";
                        foreach ($db->query($sql_projects) as $projects) {
                            echo '<option value="' . $projects[id] . '"';
                            echo '>' . $projects[name] . '</option>';
                        }
                        ?>
                    </select>
                </p>
                <p class="clearfix">
                    <label for="lot">Lots:</label>
                    <select name="lot" id="lot">
                        <option value="">----</option>
                    </select>
                </p>
                <input type="submit" name="submit" value="Submit">
            </fieldset>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Project</th>
                    <th>Lot</th>
                    <th>Model</th>
                    <th>Term</th>
                    <th>Activiti</th>
                    <th>User</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Count</th>
                    <th>Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($models as $model) {
                    if ((($model['project'] == $project) || (!$project)) &&
                            (($model['lot'] == $lot) || (!$lot))) {
                        echo '<tr id="' . $model[id] . '"><td>' . $model['id'] . '</td><td>' . $model['project'] . '</td><td>' . $model['lot'] . '</td><td>' .
                        $model['model'] . '</td><td>' . date("d-M-Y H:i ", $model['term']) . '</td><td>' .
                        $model['activiti'] . '</td><td>' . $model['user'] . '</td><td>' .
                        date("d-M-Y H:i ", $model[start]) . '</td><td>' .
                        ($model['end'] ? date("d-M-Y H:i ", $model['end']) : "in process") . '</td><td>' . $model['count'] .
                        '</td><td>' . $model['time'] . '</td>';
                        echo '<td><a href="edit_job.php?work_id=' . $model[id] . '">Edit</a>
                        <a href="#" onclick="DeleteWork(' . $model['id'] . ')">Delete</a></td></tr>';
                    }
                }
                ?>
            </tbody>
        </table>
        <?php
    }
    include 'inc/footer.php';
}
?>