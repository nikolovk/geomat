<?php
include_once 'inc/config.php';
include_once 'class/jobs.php';
if ($_SESSION['logged_in'] != true) {
    header('Location: login.php');
    exit;
} else {
    $take_id = $_GET['take_id'];
    $id_project = $_GET['id_project'];
    $user_id = $_SESSION['user']['id'];
    $id_activiti = $_GET['id_activiti'];
    $work_id = $_GET['work_id'];
    $id = $_POST['id'];
    $id_model = $_POST['id_model'];
    $count = $_POST['count'];
    $time = $_POST['time'];
    $stage = $_POST['stage'];
    if ($id) {
        $finish = jobs::FinishModel($id, $count, $time, $id_model, $stage, $db);
    }
    if ($take_id) {
        $start = jobs::StartModel($take_id, $id_activiti, $id_project, $db);
    }
    if ($work_id) {
        $sql_select = "SELECT work.time, work.count, work.id_model, models.stage as stage
            FROM work 
            JOIN models ON models.id = work.id_model
            WHERE work.id = $work_id";
        echo $sql_select;
        $result_model = $db->query($sql_select);
        $model = $result_model->fetch();
    }
    $sql_select_models = "SELECT projects.name as project, models.lot, models.term, models.model, activities.name as activiti, 
        work.id as id, work.start, work.end, work.count, work.time
        FROM work
        JOIN projects ON work.id_project = projects.id
        JOIN models ON work.id_model = models.id
        JOIN activities ON work.id_activiti = activities.id
        WHERE id_user = $user_id ORDER BY work.end ASC, work.start DESC";
    $result_select = $db->query($sql_select_models);
    while ($row = $result_select->fetch()) {
        $models_list .= '<tr id="' . $row['id'] . '"><td>' . $row['id'] . '</td><td>' . $row['project'] . '</td><td>' . $row['lot'] . '</td><td>' .
                $row['model'] . '</td><td>' . date("d-M-Y", $row['term']) . '</td><td>' . $row['activiti'] . '</td><td>' .
                date("d-M-Y H:i ", $row[start]) . '</td><td>' . ($row['end'] ? date("d-M-Y H:i ", $row['end']) : "in process") . '</td><td>' . $row['count'] .
                '</td><td>' . $row['time'] . '</td>';
        $models_list .= ($row['end'] ? '<td><button onclick="EditPopup(' . $row['id'] . ')">Edit</button></td>' : '<td><a href="list_job.php?work_id=' .
                        $row[id] . '">Finish model</a></td>');
        $models_list .= '</tr>';
    }

    include 'inc/head.php';
    echo $start . '<br />';
    echo $finish . '<br />';
    if ($model) {
        ?>
        <form action="list_job.php" method="POST">
            <fieldset>
                <legend>Finish model - ID(<?php echo $work_id; ?>)</legend>
                <input type="hidden" name="id" value="<?php echo $work_id; ?>" />
                <input type="hidden" name="id_model" value="<?php echo $model['id_model']; ?>" />
                <input type="hidden" name="stage" value="<?php echo $model['stage']; ?>" />
                <p class="clearfix">
                    <label for="time">Total time</label>
                    <input type="text" value="<?php echo $model['time']; ?>" id="time" name="time" />
                </p>
                <p class="clearfix">
                    <label for="count">Count</label>
                    <input type="text" value="<?php echo $model['count']; ?>" id="count" name="count" />
                </p>
                <input type="submit" value="Save" />
            </fieldset>

        </form>

        <?php
    }
    ?>
    <div id="popup">
        <a href="#" class="close" >x</a>
        <form>
            <fieldset>
                <legend></legend>
                <p class="clearfix">
                    <label for="count">Count</label>
                    <input type="text" value="" id="count" name="count" />
                </p>
                <p class="clearfix">
                    <label for="time">Total time</label>
                    <input type="text" value="" id="time" name="time" />
                </p>
                <a id="send" ></a>
            </fieldset>
        </form>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Project</th>
                <th>Lot</th>
                <th>Model</th>
                <th>Term</th>
                <th>Activiti</th>
                <th>Start</th>
                <th>End</th>
                <th>Count</th>
                <th>Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php echo $models_list; ?>
        </tbody>
    </table>
    <?php
    include 'inc/footer.php';
}
?>