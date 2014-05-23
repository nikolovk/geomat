<?php
include_once 'inc/config.php';
if (($_SESSION['logged_in'] != true) || ((int) $_SESSION['user']['rights'] < 10 )) {
    header('Location: index.php');
    exit;
} else {

    $submit = $_POST['_submit_check'];
    include 'inc/head.php';
    if ($submit) {
        $start = $_POST['start'];
        $end = $_POST['end'];
        //echo strtotime($start);
        //echo '<br>';
        //echo strtotime('+1 day', strtotime($end));
        $sql = "SELECT `leave`.`id`, `leave`.`id_user`, `leave`.`type`, `leave`.`from`, `leave`.`to`, `leave`.`days`,
                `users`.`name` as name
                FROM `leave`
                JOIN `users` ON `users`.`id` = `leave`.`id_user`
                WHERE (:start <= `leave`.`from` AND `leave`.`from` <= :end) OR
                    (:start <= `leave`.`to` AND `leave`.`to` <= :end)
                ORDER BY `leave`.`to`, `leave`.`from`";
    } else {
        $sql = "SELECT `leave`.`id`, `leave`.`id_user`, `leave`.`type`, `leave`.`from`, `leave`.`to`, `leave`.`days`,
                `users`.`name` as name
                FROM `leave`
                JOIN `users` ON `users`.`id` = `leave`.`id_user`
                ORDER BY `leave`.`to`, `leave`.`from`";
    }

    $st = $db->prepare($sql);
    $st->bindParam(":start", strtotime($start));
    $st->bindParam(":end", strtotime('+1 day', strtotime($end)));
    $result = $st->execute();
    ?>

    <form action="leave.php" method="POST">
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
            <input type="submit" value="Filter" />
            <button class="big" type="button" onclick="ShowAddLeave()">Add</button>
        </fieldset>
    </form>
    <?php if ($result) { ?>
        <table>
            <caption>Leave</caption>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>From</th>
                <th>To</th>
                <th>Days</th>
                <th>Edit</th>
            </tr>
            <?php
            while ($leave = $st->fetch()) {
                echo '<tr id="' . $leave['id'] . '">';
                echo '<td>' . $leave['name'] . '</td>';
                echo '<td>' . $leave['type'] . '</td>';
                echo '<td>' . date('d-M-Y', $leave['from']) . '</td>';
                echo '<td>' . date('d-M-Y', $leave['to']) . '</td>';
                echo '<td>' . $leave['days'] . '</td>';
                echo '<td><button onclick="ShowEditLeave(' . $leave['id'] .
                ',\'' . date('Y-m-d', $leave['from']) . '\',\'' . date('Y-m-d', $leave['to']) . '\')">Edit</button>
                            <button onclick="DeleteLeave(' . $leave['id'] . ')">Delete</button></td>';
                echo '<tr>';
            }
            ?>
        </table>
        <?php
    }
    ?>
    <div id="popup">
        <a href="#" class="close" >x</a>
        <form>
            <fieldset>
                <legend>Leave</legend>
                <p class="clearfix">
                    <label for="name">Name</label>
                    <select id="name" name="name">
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
                <p class="clearfix">
                    <label for="type">Type</label>
                    <select id="type" name="type">
                        <?php
                        $types = array(1 => 'paid leave', 'unpaid leave', 'sick leave', 'maternity leave');
                        foreach ($types as $key => $value) {
                            echo '<option value="' . $key . '"';
                            if ($key == $type) {
                                echo 'selected="selected" ';
                            }
                            echo '>' . $value . '</option>';
                        }
                        ?>
                    </select>
                </p>
                <p class="clearfix">
                    <label for="from">From</label>
                    <input type="date" value="" id="from" name="from" />
                </p>
                <p class="clearfix">
                    <label for="to">To</label>
                    <input type="date" value="" id="to" name="to" />
                </p>
                <p class="clearfix">
                    <label for="days">Days</label>
                    <input type="text" value="" id="days" name="days" />
                </p>
                <a class="submit" ></a>
            </fieldset>
        </form>
    </div>
    <?php
    include 'inc/footer.php';
}
    
