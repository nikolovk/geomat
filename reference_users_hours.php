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
        if ($start && $end) {
            $sql = "SELECT users.name as name,
                SUM(time) as time
                FROM work  
                JOIN users ON users.id = work.id_user
                WHERE work.end >= :start AND work.end < :end
                GROUP BY work.id_user
                ORDER BY name";
            $st = $db->prepare($sql);
            $st->bindParam(":start", strtotime($start));
            $st->bindParam(":end", strtotime('+1 day',strtotime($end)));
            $result = $st->execute();
        } else {
            echo '<p>Error! Please check selected information!';
        }
    }
    ?>
    <form action="reference_users_hours.php" method="POST">
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
            <caption>Users reference</caption>
            <tr>
                <th>Name</th>
                <th>Time</th>
            </tr>
            <?php
            while ($activiti = $st->fetch()) {
                echo '<tr>';
                echo '<td>' . $activiti['name'] . '</td>';
                echo '<td>' . $activiti['time'] . '</td>';
                echo '<tr>';
            }
            ?>
        </table>
        <?php
    }
}
    
