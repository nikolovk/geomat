<?php
include_once 'inc/config.php';
if (($_SESSION['logged_in'] != true) || ((int) $_SESSION['user']['rights'] < 5 )) {
    header('Location: login.php');
    exit;
} else {
    $submit = $_POST['_submit_check'];
    include 'inc/head.php';
    if ($submit) {
        $start = $_POST['start'];
        $end = $_POST['end'];
        echo strtotime($start) .'-'.strtotime('+1 day',strtotime($end));
        if ($start && $end) {
            $sql = "SELECT projects.name as project, activities.name as activiti,
                SUM(work.count) as count, SUM(work.time) as time, COUNT(*) as models
                FROM work 
                JOIN projects ON projects.id = work.id_project
                JOIN activities ON activities.id = work.id_activiti
                WHERE work.end >= :start AND work.end < :end
                GROUP BY work.id_project, work.id_activiti
                ORDER BY project, activiti";
            $st = $db->prepare($sql);
            $st->bindParam(":start", strtotime($start));
            $st->bindParam(":end", strtotime('+1 day',strtotime($end)));
            $result = $st->execute();
        } else {
            echo '<p>Error! Please check selected information!';
        }
    }
    ?>
    <form action="reference_projects.php" method="POST">
        <fieldset>
            <legend>Filter</legend>
            <input type="hidden" name="_submit_check" value="1" />
            <p class="clearfix">
                <label for="start">Start:</label>
                <input type="date" name="start" id="start" value="<?php echo $start; ?>" />
            </p>
            <p class="clearfix">
                <label for="end">End:</label>
                <input type="date" name="end" id="end" value="<?php echo $end; ?>" />
            </p>
            <input type="submit" value="Show" />
        </fieldset>
    </form>
    <?php if ($result) { ?>
        <table>
            <caption>Projects reference</caption>
            <tr>
                <th>Project</th>
                <th>Activiti</th>
                <th>Models</th>
                <th>Count</th>
                <th>Time</th>
                <th>Models/Time</th>
                <th>Count/Time</th>
            </tr>
            <?php
            while ($activiti = $st->fetch()) {
                echo '<tr>';
                if ($activiti['time'] == 0) {
                    $count_per_hour = 0;
                    $models_per_hour = 0;
                } else {
                    $count_per_hour = round($activiti['count'] / $activiti['time'], 2);
                    $models_per_hour = round($activiti['models'] / $activiti['time'], 2);
                }
                echo '<td>' . $activiti['project'] . '</td>';
                echo '<td>' . $activiti['activiti'] . '</td>';
                echo '<td>' . $activiti['models'] . '</td>';
                echo '<td>' . $activiti['count'] . '</td>';
                echo '<td>' . $activiti['time'] . '</td>';
                echo '<td>' . $models_per_hour . '</td>';
                echo '<td>' . $count_per_hour . '</td>';
                echo '<tr>';
            }
            ?>
        </table>
        <?php
    }
}
    
