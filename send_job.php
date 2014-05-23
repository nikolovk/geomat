<?php
include_once 'inc/config.php';
if (($_SESSION['logged_in'] != true) || ((int) $_SESSION['user']['rights'] < 5 )) {
    header('Location: login.php');
    exit;
} else {
    $submit = $_REQUEST['_submit_check'];

    include 'inc/head.php';
    if ($submit) {
        $id_project = $_REQUEST['project'];
        $lot = $_REQUEST['lot'];
        $stmt_models = $db->prepare('SELECT projects.name as project, 
            models.id, models.lot, models.model, models.send, models.note
            FROM models
            JOIN projects ON models.id_project = projects.id 
            WHERE id_project = :id_project AND lot = :lot AND stage < 100
            ORDER BY id');
        $stmt_models->bindParam('id_project', $id_project);
        $stmt_models->bindParam('lot', $lot);
        $stmt_models->execute();
        while ($model = $stmt_models->fetch()) {
            if (!$content) {
                $project = $model['project'];
                $content = '<tr>
                <th>Model</th>
                <th>Send date</th>
                <th>Note</th>
                <th><button id="check" type="button" onclick="CheckAll()">Check</button></th>
                      </tr>';
            }
            $id_model = $model['id'];
            $content .= '<tr>
                <td>' . $model['model'] . '</td>';
            $content .= '<td>';
            if ($model[send] == 0) {
                $content .= 'not send';
            } else {
                $content .= date("d-M-Y", $model['send']);
            }
            $content .= '</td>
                <td class="note" title="edit" onclick="EditNote(' . $model['id'] . ')" id="' . $model['id'] . '">' . $model['note'] . '</td>
                    <td><input type="checkbox" class="models" id="'.$model['id'].'" /></td>
                </tr>';
        }
    }
    ?>
    <form action="send_job.php" method="post">
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
        <form name="send_form" id="send_form" action="">
            <fieldset>
                <table>
                    <caption><?php echo 'Project - ' . $project . ' Lot - ' . $lot; ?>
                    <button type="button" onclick="SendChecked(<?php echo '\''.$id_project.'\',\''.$lot.'\'';?>)">Send</button></caption>
                    <?php echo $content; ?>
                </table>
            </fieldset>
        </form>
        <?php
    }
    include 'inc/footer.php';
}