<?php
include_once 'inc/config.php';
include_once 'class/jobs.php';
if ($_SESSION['logged_in'] != true) {
    header('Location: login.php');
    exit;
} else {

    $user_id = $_SESSION['user']['id'];
    $sql_select_models = "SELECT projects.name as project, models.lot, models.term, models.model, activities.name as activiti, 
        work.id as id, work.start, work.end, work.count, work.time
        FROM work
        JOIN projects ON work.id_project = projects.id
        JOIN models ON work.id_model = models.id
        JOIN activities ON work.id_activiti = activities.id
        WHERE id_user = $user_id AND work.end > 0 ORDER BY work.end DESC, work.start DESC";
    $result_select = $db->query($sql_select_models);
    while ($row = $result_select->fetch()) {
        $models_list .= '<tr id="' . $row['id'] . '"><td>' . $row['id'] . '</td><td>' . $row['project'] . '</td><td>' . $row['lot'] . '</td><td>' .
                $row['model'] . '</td><td>' . date("d-M-Y", $row['term']) . '</td><td>' . $row['activiti'] . '</td><td>' .
                date("d-M-Y H:i ", $row[start]) . '</td><td>' . ($row['end'] ? date("d-M-Y H:i ", $row['end']) : "in process") . '</td><td>' . $row['count'] .
                '</td><td>' . $row['time'] . '</td>';
        if ($row['end']) {
            $now = time();
            if ((($now - $row['end']) < 259200)|| ($_SESSION['user']['rights'] > 4) ) {
                $models_list .= '<td><button onclick="EditPopup(' . $row['id'] . ')">Edit</button></td>';
            } else{
                $models_list .= '<td>Finished</td>';
            }
        } else {
            $models_list .= '<td><button onclick="FinishJobPopup(' . $row['id'] . ')">Finish</button></td>';
        }
        $models_list .= '</tr>';
    }
//
    include 'inc/head.php';
    echo $start . '<br />';
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
                <a id="finish" ></a>
                <a id="edit" ></a>
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