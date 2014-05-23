<?php
include_once 'inc/config.php';
include_once 'class/jobs.php';
if ($_SESSION['logged_in'] != true) {
    header('Location: login.php');
    exit;
} else {
    $submit = $_REQUEST['_submit_user'];
    if ($submit) {
        $user_id = $_REQUEST['user'];
    } else {
    $user_id = $_SESSION['user']['id'];
    }
    $sql_select_models = "SELECT projects.name as project, models.lot, models.term, models.model, activities.name as activiti, 
        work.id as id, work.start, work.end, work.count, work.time
        FROM work
        JOIN projects ON work.id_project = projects.id
        JOIN models ON work.id_model = models.id
        JOIN activities ON work.id_activiti = activities.id
        WHERE id_user = $user_id ORDER BY work.end DESC, work.start DESC";
    $result_select = $db->query($sql_select_models);
    while ($row = $result_select->fetch()) {
        $models_list .= '<tr id="' . $row['id'] . '"><td>' . $row['id'] . '</td><td>' . $row['project'] . '</td><td>' . $row['lot'] . '</td><td>' .
                $row['model'] . '</td><td>' . date("d-M-Y", $row['term']) . '</td><td>' . $row['activiti'] . '</td><td>' .
                date("d-M-Y H:i ", $row[start]) . '</td><td>' . ($row['end'] ? date("d-M-Y H:i ", $row['end']) : "in process") . '</td><td>' . $row['count'] .
                '</td><td>' . $row['time'] . '</td>';
        $models_list .= ($row['end'] ? '<td><button onclick="EditPopup(' . $row['id'] . ')">Edit</button></td>' :
                        '<td><button onclick="FinishJobPopup(' . $row['id'] . ')">Finish</button></td>');
        $models_list .= '</tr>';
    }

    include 'inc/head.php';
    ?>
    <form action="admin_common_job.php" method="post">
        <fieldset>
            <legend>Choose user</legend>
            <input type="hidden" name="_submit_user" value="1" />
            <p class="clearfix">
                <label for="user">User:</label>
                <select name="user" id="user" onchange="">
                    <option value="">----</option>
                    <?php
                    $sql_users = "SELECT id, name FROM users ORDER BY name";
                    foreach ($db->query($sql_users) as $user) {
                        echo '<option value="' . $user[id] . '"';
                        if ($user_id == $user['id']) {
                            echo 'selected="selected" ';
                        }
                        echo '>' . $user[name] . '</option>';
                    }
                    ?>
                </select>
            </p>
            <input type="submit" name="submit" value="Submit">
        </fieldset>
    </form>
    <?php
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