<?php
include_once 'inc/config.php';
if (($_SESSION['logged_in'] != true) || ((int) $_SESSION['user']['rights'] < 5 )) {
    header('Location: index.php');
    exit;
} else {
    $submit = $_POST['_submit_check'];
    include 'inc/head.php';

    if ($submit) {
        $id_project = $_POST['id_project'];
        $type = $_POST['type'];
        $activiti = trim($_POST['activiti']);
        if ($type && $activiti && $id_project) {
            if ($type == 'common') {
                $sql = "SELECT MAX(stage) as stage FROM activities WHERE id_project = :id_project AND stage > 100";
                $stmt_activiti = $db->prepare($sql);
                $stmt_activiti->bindParam(":id_project", $id_project);
                $stmt_activiti->execute();
                $result = $stmt_activiti->fetch();
                if ($result['stage'] > 0) {
                    $stage = $result['stage'] + 1;
                } else {
                    $stage = 101;
                }
            } else if ($type == 'stage') {
                $sql = "SELECT MAX(stage) as stage FROM activities WHERE id_project = :id_project AND stage < 100";
                $stmt_activiti = $db->prepare($sql);
                $stmt_activiti->bindParam(":id_project", $id_project);
                $stmt_activiti->execute();
                $result = $stmt_activiti->fetch();
                if ($result['stage'] > 0) {
                    $stage = $result['stage'] + 2;
                } else {
                    $stage = 1;
                }
            }
            $sql = "INSERT INTO activities (id_project, name, stage) 
                VALUES(:id_project, :activiti, :stage)";
            $stmt_insert = $db->prepare($sql);
            $stmt_insert->bindParam(':id_project', $id_project);
            $stmt_insert->bindParam(':activiti', $activiti);
            $stmt_insert->bindParam(':stage', $stage);
            $result = $stmt_insert->execute();
            if ($result) {
                $message = 'Activiti added successfully';
                $id_project = null;
                $activiti = null;
            }
        } else {
            echo '<p>Error! Please check selected information!';
        }
    }
    if ($message) {
        echo '<p>' . $message . '</p>';
    }
    ?>
    <form action="activities.php" method="POST">
        <fieldset>
            <legend>Add new activiti</legend>
            <input type="hidden" name="_submit_check" value="1" />
            <p class="clearfix">
                <label for="project">Projects:</label>
                <select name="id_project" id="project" onchange="">
                    <option value="">----</option>
                    <?php
                    $sql_projects = "SELECT id, name FROM projects";
                    foreach ($db->query($sql_projects) as $projects) {
                        echo '<option value="' . $projects[id] . '"';
                        if ($id_project == $projects[id]) {
                            echo 'selected="selected"';
                        }
                        echo '>' . $projects[name] . '</option>';
                    }
                    ?>
                </select>
            </p>
            <p class="clearfix">
                <label for="activiti">Activiti</label>
                <input type="text" value="<?php echo $activiti; ?>" id="activiti" name="activiti" />
            </p>
            <p class="clearfix radio">
                <span>Job type:</span>
                <label for="common">common</label>
                <?php if ($type == 'common') { ?>
                    <input type="radio" name="type" id="common" value="common" checked="checked" />
                <?php } else { ?>
                    <input type="radio" name="type" id="common" value="common" />
                <?php } ?>
                <label for="stage">stage</label>
                <?php if ($type == 'stage') { ?>
                    <input type="radio" name="type" id="stage" value="stage" checked="checked" />
                <?php } else { ?>
                    <input type="radio" name="type" id="stage" value="stage" />
                <?php } ?>
            </p>
            <input type="submit" value="Add" />
        </fieldset>
    </form>
    <?php
    $sql = "SELECT activities.id, activities.id_project,activities.stage,  activities.name as activiti,
        projects.name as project
        FROM activities
        JOIN projects ON activities.id_project = projects.id
        ORDER BY project, stage";
    $stmt = $db->query($sql);
    if ($stmt->rowCount() > 0) {
        ?>
        <table>
            <caption>Activities</caption>
            <tr>
                <th>Project</th>
                <th>Activiti</th>
                <th>Stage</th>
                <th>Action</th>
            </tr>
            <?php
            while ($activiti = $stmt->fetch()) {
                echo '<tr id="' . $activiti['id'] . '" >';
                echo '<td>' . $activiti['project'] . '</td>';
                echo '<td>' . $activiti['activiti'] . '</td>';
                echo '<td>' . $activiti['stage'] . '</td>';
                echo '<td>';
                echo '<button onclick="RemoveActiviti(' . $activiti['id'] . ')">Remove</button>';
                echo '</td>';
                echo '<tr>';
            }
            ?>
        </table>
        <?php
    }
}
    
