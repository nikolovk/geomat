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
            models.id, models.lot, models.model, models.stage, models.term
            FROM models
            JOIN projects ON models.id_project = projects.id 
            WHERE id_project = :id_project AND lot = :lot
            ORDER BY id');
        $stmt_models->bindParam('id_project', $id_project);
        $stmt_models->bindParam('lot', $lot);
        $stmt_models->execute();

        while ($model = $stmt_models->fetch()) {
            if (!$content) {
                $project = $model['project'];
                $content = '<tr>
                <th>Model</th>
                <th>Stage</th>
                <th>Term</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>';
            }
            $id_model = $model['id'];
            $content .= '<tr id="' . $model['id'] . '">
                <td>' . $model['model'] . '</td>
                <td>' . $model['stage'] . '</td>
                <td class="' . date("Y-m-d", $model['term']) . '">' . date("d-M-Y", $model['term']) . '</td>
                <td><button onclick="ShowEditModel(' . $model['id'] . ')">Edit</button></td>
                <td><button onclick="DeleteModel(' . $model['id'] . ')">Delete</button></td>
                </tr>';
        }
    }
    ?>
    <form action="models.php" method="post">
        <fieldset class="choose">
            <legend>Choose Lot</legend>
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
                <p class="clearfix">
                    <label for="p_project">Project</label>
                    <select name="p_project" id="p_project"></select>
                </p>
                <p class="clearfix">
                    <label for="model">Model</label>
                    <input type="text" value="model" id="model" name="model" />
                </p>
                <p class="clearfix">
                    <label for="stage">Stage</label>
                    <input type="text" value="100" id="stage" name="stage" /><br />
                </p>
                <p class="clearfix">
                    <label for="note">Note</label>
                    <input type="text" value="no note" id="note" name="note" /><br />
                </p>
                <p class="clearfix">
                    <label for="popup_lot">Lot</label>
                    <input type="text" value="" id="popup_lot" name="popup_lot" /><br />
                </p>
                <p class="clearfix">
                    <label for="term">Term</label>
                    <input type="date" value="<?php echo date("Y-m-d"); ?>" id="term" name="term" />
                </p>
                <a class="submit" ></a>
            </fieldset>
        </form>
    </div>

    <?php if ($content) { ?>
        <table>
            <legend>
                <?php echo 'Project - ' . $project . ' Lot - ' . $lot; ?>
                <button onclick="ShowAddModel(<?php echo $id_project . ',\'' . $lot . '\''; ?>)">Add Model</button>
            </legend>
            <?php echo $content; ?>
        </table>
        <?php
    } else {
        echo '<button onclick="ShowAddModel(' . $id_project . ')">Add Model</button>';
    }
    include 'inc/footer.php';
}