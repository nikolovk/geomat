<?php
include_once 'inc/config.php';
if (($_SESSION['logged_in'] != true) || ((int) $_SESSION['user']['rights'] < 5 )) {
    header('Location: login.php');
    exit;
} else {
    include 'inc/head.php';
    $result = $db->query('SELECT id, nickname, email, name, egn, phone FROM users ORDER BY nickname');
    ?>
<!--    <button id="add_user" onclick="ShowAddUser()">Add User</button>-->
    <table>
        <caption>Users</caption>
        <tr>
            <th>ID</th>
            <th>Nick Name</th>
            <th>Email</th>
            <th>Name</th>
            <th>EGN</th>
            <th>Phone</th>
            <th>Edit</th>
            <th>Change Password</th>
<!--            <th>Delete</th>-->
        </tr>
        <?php while ($row = $result->fetch()) { ?>
            <tr id="<?php echo $row['id']; ?>">
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nickname']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['egn']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><button onclick="ShowEditUser(<?php echo $row['id']; ?>)">Edit</button></td>
                <td><button onclick="ShowChangePassword(<?php echo $row['id']; ?>)">Change</button></td>
<!--                <td><button onclick="DeleteUser(<?php echo $row['id']; ?>)">Delete</button></td>-->
            </tr>
        <?php } ?>
    </table>
    <div id="popup">
        <a href="#" class="close" >x</a>
        <form>
            <fieldset>
                <legend></legend>
                <p class="clearfix">
                    <label for="nickname">Nick Name</label>
                    <input type="text" value="" id="nickname" name="nickname" />
                </p>
                <p class="clearfix">
                    <label for="email">Email</label>
                    <input type="text" value="" id="email" name="email" />
                </p>
                <p class="clearfix">
                    <label for="name">Name</label>
                    <input type="text" value="" id="name" name="name" />
                </p>
                <p class="clearfix">
                    <label for="egn">EGN</label>
                    <input type="text" value="" id="egn" name="egn" />
                </p>
                <p class="clearfix">
                    <label for="phone">Phone</label>
                    <input type="tel" value="" id="phone" name="phone" />
                </p>
                <a class="submit" ></a>
            </fieldset>
        </form>
    </div>
    <div id="change_pass" class="popup">
        <a href="#" class="close" >x</a>
        <form>
            <fieldset>
                <legend></legend>
                <p class="clearfix">
                    <label for="password">Password</label>
                    <input type="password" value="" id="password" name="password" />
                </p>
                <p class="clearfix">
                    <label for="confirm">Confirm Password</label>
                    <input type="password" value="" id="confirm" name="confirm" />
                </p>
                <a class="submit" ></a>
            </fieldset>
        </form>
    </div>

    <?php
    include 'inc/footer.php';
}
