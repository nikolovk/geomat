<?php
include_once 'inc/config.php';
if (($_SESSION['logged_in'] != true) || ((int) $_SESSION['user']['rights'] < 5 )) {
    header('Location: login.php');
    exit;
} else {
    $work_id = $_REQUEST['work_id'];
    $submit = $_POST['_submit_check'];
    include 'inc/head.php';
    if ($submit) {
        $time = $_POST['time'];
        $count = $_POST['count'];
        $finished = $_POST['finished'];
        if ($time && $count && $finished && $work_id) {
            $st = $db->prepare("UPDATE work SET count = :count, time = :time, end = :end  WHERE id = :id");
            $st->bindParam(':count', $count);
            $st->bindParam(':time', $time);
            $st->bindParam(':end', strtotime($finished));
            $st->bindParam(':id', $work_id);
            $result = $st->execute();
        }
    } else {
        $st = $db->prepare("SELECT count,time,end FROM work WHERE id = :id");
        $st->bindParam(':id', $work_id);
        $st->execute();
        $work = $st->fetch();
        if ($work) {
            $time = $work['time'];
            $count = $work['count'];
            $finished = date("Y-m-d", $work['end']);
            echo $finished;
        }
    }
        if (!$result && $submit) {
        echo '<p>Error. Check information!</p>';
        }
    if ($result && $submit) {
        echo '<p>Work updated successfully</p>';
    } else {
        ?>
        <form action="edit_job.php" method="POST">
            <fieldset>
                <legend>Finish model - ID(<?php echo $work_id; ?>)</legend>
                <input type="hidden" name="_submit_check" value="1" />
                <input type="hidden" name="work_id" value="<?php echo $work_id; ?>" />
                <p class="clearfix">
                    <label for="time">Total time</label>
                    <input type="text" value="<?php echo $time; ?>" id="time" name="time" />
                </p>
                <p class="clearfix">
                    <label for="count">Count</label>
                    <input type="text" value="<?php echo $count; ?>" id="count" name="count" />
                </p>
                <p class="clearfix">
                    <label for="finished">Finished:</label>
                    <input type="date" name="finished" id="finished" value="<?php echo $finished; ?>" />
                </p>
                <input type="submit" value="Update" />
            </fieldset>

        </form>

        <?php
    }
}
