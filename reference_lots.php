<?php
include_once 'inc/config.php';
if (($_SESSION['logged_in'] != true) || ((int) $_SESSION['user']['rights'] < 5 )) {
    header('Location: login.php');
    exit;
} else {
    $submit = $_POST['_submit_check'];
    include 'inc/head.php';
    if ($submit) {
        $id_project = $_POST['project'];
        if ($id_project) {
            $sql = "SELECT projects.name as project, 
                SUM(work.count) as count, SUM(work.time) as time, models.lot as lot
                FROM work 
                JOIN projects ON projects.id = work.id_project
                JOIN models ON models.id = work.id_model
                WHERE work.id_project >= :id_project
                GROUP BY lot
                ORDER BY lot";
            $st = $db->prepare($sql);
            $st->bindParam(":id_project", $id_project);
            $result = $st->execute();
        } else {
            echo '<p>Error! Please check selected information!</p>';
        }
    }
    ?>
    <form action="reference_lots.php" method="post">
        <fieldset>
            <legend>Filter project</legend>
            <input type="hidden" name="_submit_check" value="1" />
            <p class="clearfix">
                <label for="project">Projects:</label>
                <select name="project" id="project" onchange="">
                    <option value="">----</option>
                    <?php
                    $sql_projects = "SELECT id, name FROM projects";
                    foreach ($db->query($sql_projects) as $projects) {
                        echo '<option value="' . $projects[id] . '" ';
                        if ($id_project == $projects['id']) {
                            echo 'selected="selected"';
                        }
                        echo ' >' . $projects[name] . '</option>';
                    }
                    ?>
                </select>
            </p>
            <input type="submit" name="submit" value="Submit">
        </fieldset>
    </form>
    <?php if ($result) { ?>
        <table>
            <caption>Projects reference</caption>
            <tr>
                <th>Lot</th>
                <th>Hours</th>
                <th>Count</th>
                <th>Count per time</th>
            </tr>
            <?php
            while ($lot = $st->fetch()) {
                echo '<tr>';
                if ($lot['time'] == 0) {
                    $count_per_hour = 0;
                } else {
                    $count_per_hour = round($lot['count'] / $lot['time'], 2);
                }
                echo '<td>' . $lot['lot'] . '</td>';
                echo '<td>' . $lot['time'] . '</td>';
                echo '<td>' . $lot['count'] . '</td>';
                echo '<td>' . $count_per_hour . '</td>';
                echo '<tr>';
            }
            ?>
        </table>
        <?php
    }
     include 'inc/footer.php';
}
    
