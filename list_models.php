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
        $lot = $_POST['lot'];
        $stmt_models = $db->prepare('SELECT projects.name as project, 
            models.id, models.lot, models.model, models.term, models.send, models.stage, models.note
            FROM models
            JOIN projects ON models.id_project = projects.id 
            WHERE id_project = :id_project AND lot = :lot AND stage < 100
            ORDER BY id');
        $stmt_models->bindParam('id_project', $id_project);
        $stmt_models->bindParam('lot', $lot);
        $stmt_models->execute();

        $stmt_jobs = $db_second->prepare('SELECT work.id_model, work.start, work.end, work.count, work.time,
                activities.name as activiti, users.name as user 
                FROM work
                JOIN activities ON work.id_activiti = activities.id
                JOIN users ON work.id_user = users.id
                WHERE id_model = :id_model
                ORDER BY work.id_activiti');
        while ($model = $stmt_models->fetch()) {
            if (!$content) {
                $project = $model['project'];
                $content = '<tr>
                <th>Model</th>
                <th>Name</th>
                <th>Start</th>
                <th>End</th>
                <th>Time</th>
                <th>Count</th>
                <th>Count/Time</th>
                <th>Name</th>
                <th>Start</th>
                <th>End</th>
                <th>Time</th>
                <th>Count</th>
                <th>Count/Time</th>
                <th>Send date</th>
                <th>Note</th>
            </tr>';
            }
            $id_model = $model['id'];
            $stmt_jobs->bindParam('id_model', $id_model);
            $stmt_jobs->execute();
            $content .= '<tr>
                <td>' . $model['model'] . '</td>';
            for ($i = 0; $i < 2; $i++) {
                if ($job = $stmt_jobs->fetch()) {
                    $content .= '<td>' . $job['user'] . '</td>';
                    $content .= '<td>' . date("d-M-Y H:i ", $job[start]) . '</td>';
                    $content .= '<td>';
                    if ($job[end] > 0) {
                        $content .= date("d-M-Y H:i ", $job[end]);
                    } else {
                        $content .= 'in progress';
                    }
                    $content .= '</td>';
                    $content .= '<td>' . $job['time'] . '</td>';
                    $content .= '<td>' . $job['count'] . '</td>';
                    if ($job['time'] > 0) {
                        $content .= '<td>' . round($job['count'] / $job['time'], 2) . '</td>';
                    } else {
                        $content .= '<td></td>';
                    }
                } else {
                    $content .= '<td></td><td></td><td></td><td></td><td></td><td></td>';
                }
            }
            $content .= '<td>';
            if ($model[send] == 0) {
                $content .= 'not send';
            } else {
                $content .= date("d-M-Y", $model[send]);
            }
            $content .= '</td>
                <td class="note" title="edit" onclick="EditNote(' . $model['id'] . ')" id="' . $model['id'] . '">' . $model['note'] . '</td>
                </tr>';
        }
    }
    ?>
    <form action="list_lot.php" method="post">
        <fieldset>
            <legend>List lot</legend>
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
    <div id="popup">
        <a href="#" class="close" >x</a>
        <form>
            <fieldset>
                <legend></legend>
                <p>
                    <label for="note">Note</label>
                    <input type="text" value="" id="note" name="note" />
                </p>
                <a id="edit_note" ></a>
            </fieldset>
        </form>
    </div>
    <?php if ($submit && $content) { ?>
        <table>
            <legend><?php echo 'Project - ' . $project . ' Lot - ' . $lot; ?></legend>
            <?php echo $content; ?>
        </table>
        <?php
    }
    include 'inc/footer.php';
}