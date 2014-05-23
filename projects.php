<?php
include_once 'inc/config.php';
if (($_SESSION['logged_in'] != true) || ((int) $_SESSION['user']['rights'] < 5 )) {
    header('Location: login.php');
    exit;
} else {
    include 'inc/head.php';
    $result = $db->query('SELECT id, name FROM projects ORDER BY id');
    ?>
    <button id="add_project" onclick="ShowAddProject()">Add Project</button>
    <table>
        <caption>Projects</caption>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        <?php while ($row = $result->fetch()) { ?>
            <tr id="<?php echo $row['id']; ?>">
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><button onclick="ShowEditProject(<?php echo $row['id']; ?>)">Edit</button></td>
                <td><button onclick="DeleteProject(<?php echo $row['id']; ?>)">Delete</button></td>
            </tr>
        <?php } ?>
    </table>
    <div id="popup">
        <a href="#" class="close" >x</a>
        <form>
            <fieldset>
                <legend></legend>
                <p class="clearfix">
                    <label for="name">Project</label>
                    <input type="text" value="" id="name" name="name" />
                </p>
                <a class="submit" ></a>
            </fieldset>
        </form>
    </div>


    <?php
    include 'inc/footer.php';
}
