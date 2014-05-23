<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Geomat</title>
        <link href="style.css" rel="stylesheet" />
<!--        <script src="js/modernizr-latest.js"></script>-->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="js/scripts.js"></script>
    </head>
    <body>
        <header>
            <div id="welcome">
                <?php
                if ($_SESSION['logged_in'] === true) {
                    echo '<p>Hello, ';
                    echo($_SESSION['user']['name']);
                    echo '</p>';
                }
                ?>
            </div>
            <nav class="clearfix">
                <ul>
                    <li>
                        <a href="index.php">Home</a>
                    </li>
                    <?php
                    if ($_SESSION['logged_in'] != true) {
                        ?>
                        <li>
                            <a href="login.php">Login</a>
                        </li>
                        <li>
                            <a href="register.php">Register</a>
                        </li>
                        <?php
                    } else {
                        ?>
                        <li>
                            <a href = "take_common.php">Take Job</a>
                        </li>
                        <li>
                            <a href = "list_common_job.php">List my activities</a>
                        </li>
                        <li>
                            <a href = "list_lot.php">List lot</a>
                        </li>
                        <?php if ((int) $_SESSION['user']['rights'] >= 9) { ?>
                            <li><a href="#">References</a>
                                <ul class="dropdown">
                                    <li>
                                        <a href="full_list_job.php">Jobs in process</a>
                                    </li>
                                    <li>
                                        <a href="reference_projects.php">Projects Reference</a>
                                    </li>
                                    <li> 
                                        <a href="reference_lots.php">Lots Reference</a>
                                    </li>
                                    <li>
                                        <a href="reference_users.php">Users Reference</a>
                                    </li>
                                    <li>
                                        <a href="reference_users_hours.php">Users Hours</a>
                                    </li>
                                    <li>
                                        <a href="leave.php">Leaves</a>
                                    </li>
                                    <li>
                                        <a href="admin_common_job.php">Users job</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="#">Administration</a>
                                <ul class="dropdown">
                                    <li>
                                        <a href="activities.php">Activities</a>
                                    </li>
                                    <li>
                                        <a href="models.php">Models</a>
                                    </li>
                                    <li>
                                        <a href="projects.php">Projects</a>
                                    </li>
                                    <li>
                                        <a href="users.php">Users</a>
                                    </li>
                                    <li>
                                        <a href="upload_lot.php">Upload Lot</a>
                                    </li>
                                    <li>
                                        <a href="send_job.php">Send Models</a>
                                    </li>
                                </ul>
                            </li>

                        <?php } ?>
                        <li>
                            <a href="logout.php">Logout</a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </nav>
        </header>
        <section>

