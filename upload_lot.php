<?php
include_once 'inc/config.php';
include_once 'class/upload.php';
if (($_SESSION['logged_in'] != true) || ((int)$_SESSION['user']['rights'] < 5 )) {
    header('Location: login.php');
    exit;
} else {
    $submit = $_POST['_submit_check'];
    include 'inc/head.php';
    $stmt = $db->query('SELECT id, name FROM projects ORDER BY id');
    while ($row = $stmt->fetch()){
        $projects[] = $row['id'];
        $project_names[] = $row['name'];
    }
    if ($submit) {
        $common = trim($_POST['common']);
        $term = (int) strtotime($_POST['term']);
        $current_folder = getcwd();
        $upload_folder = $current_folder . '/upload/';
        $full_name = $upload_folder . $_FILES["lot"]["name"];
        $file_name = explode(".", $_FILES["lot"]["name"]);
        $name_number = explode("_", $file_name[0]);
        $id_project = $name_number[0];
        $upload_message = "";
        if ($term >= time()) {
            if (in_array($id_project, $projects) && $file_name[1] == "csv" && ($_FILES["file"]["size"] < 200000)) {
                if ($_FILES["lot"]["error"] > 0) {
                    $upload_message .= '<p class="error">Return Code: ' . $_FILES['file']['error'] . '</p>';
                } else {
                    if (file_exists($upload_folder . $_FILES["lot"]["name"])) {
                        $upload_message .= '<p class="error">' . $_FILES['lot']['name'] . ' already exists.</p>';
                    } else {
                        $upload_message .= "<p>Upload: " . $_FILES["lot"]["name"] . "</p>";
                        $upload_message .= "<p>Size: " . ($_FILES["lot"]["size"] / 1024) . " kB</p>";
                        move_uploaded_file($_FILES["lot"]["tmp_name"], $upload_folder . $_FILES["lot"]["name"]);
                        echo "<p>Stored in: " . $upload_folder . $_FILES["lot"]["name"] . "</p>";
                        $uploadInDatabase = upload::UploadLotInDatabase($full_name, $id_project, $name_number[1], $term,$common, $db);
                        if ($uploadInDatabase) {
                            $upload_message .= '<p>Lot successfully uploaded!</p>';
                        }
                    }
                }
            } else {
                $upload_message .= '<p class="error">Invalid file</p>';
            }
        } else {
            $upload_message .= '<p class="error">Term must be at least today.</p>';
        }
    }
    echo $upload_message;
    ?>
    <form action="upload_lot.php" method="post"  enctype="multipart/form-data">
        <fieldset>
            <legend>Upload Lot</legend>
            <p class="clearfix">
                <input type="hidden" name="_submit_check" value="1" />
                <label for="file">Filename:</label>
                <input type="file" name="lot" id="file">
            </p>
            <p class="clearfix">
                <label for="term">Term:</label>
                <input type="date" name="term" id="term">
            </p>
            <p class="clearfix">
                <label for="common">Common:</label>
                <input type="checkbox" name="common" id="common">
            </p>
            <input type="submit" name="submit" value="Submit">
        </fieldset>
    </form>
    <div class="help">
        <p>File name should be in format: (ID)_(lot).csv</p>
        <p>Content should be csv file, that use comma as delimitar. Program use only first two rows, name and note.</p>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
            </tr>
            <?php
                foreach ($projects as $key => $value) {
                    echo '<tr>';
                    echo '<td>'.$value.'</td>';
                    echo '<td>'.$project_names[$key].'</td>';
                    echo '</tr>';
                }
            ?>
        </table>
    </div>
    <?php
    include 'inc/footer.php';
}
?>
