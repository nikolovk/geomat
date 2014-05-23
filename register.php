<?php
include_once 'inc/config.php';
include_once 'class/register.class.php';
$submit = $_POST['_submit_check'];
if ($_SESSION['logged_in'] != true) {
    if ($submit == 1) {
        $user[nickname] = trim($_POST['nickname']);
        $user[pass] = trim($_POST['pass']);
        $user[pass2] = trim($_POST['pass2']);
        $user[email] = trim($_POST['email']);
        $user[name] = trim($_POST['name']);
        $user[sirname] = trim($_POST['sirname']);
        $user[family] = trim($_POST['family']);
        $user[phone] = trim($_POST['phone']);
        $user[egn] = trim($_POST['egn']);
        if (strlen($user[nickname]) < 3) {
            $error_array['login'] = 'nickname too short';
        }
        if (strlen($user[pass]) < 4) {
            $error_array['pass'] = 'password too short';
        }
        if ($user[pass] != $user[pass2]) {
            $error_array['pass2'] = 'password don\'t match';
        }
        if (register::valid_email($user[email]) != TRUE) {
            $error_array['email'] = 'invalid email';
        }
        if (strlen($user[name]) < 3) {
            $error_array['name'] = 'name too short';
        }
        if (!count($error_array) > 0) {
            if (!register::checkUnique('nickname', $user[nickname], $db)) {
                $error_array['login'] = 'This nickname is already taken!';
            } elseif (!register::checkUnique('email', $user[email], $db)) {
                $error_array['email'] = 'This email is already taken!';
            } else {
                if (!register::insertUser($user, $db)) {
                    $error_array ['sql'] = 'Error. Please try again!';
                }
            }
        }
    }
    include 'inc/head.php';
    if ($error_array || !$submit) {
        echo $error_array['sql'];
        ?>
        <section>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <fieldset>
                    <legend>Registration</legend>
                    <input type="hidden" name="_submit_check" value="1" />

                    <p class="clearfix"><label for="nickname">Nickname*:</label> 
                        <input type="text" id="nickname" name="nickname" size="32" value="<?php echo $user[nickname]; ?>" /> 
                        <?php echo $error_array['login']; ?></p>
                    <p class="clearfix"><label for="pass">Password*:</label>
                        <input type="password" id="pass" name="pass" size="32" value="" /> 
                        <?php echo $error_array['pass']; ?></p>
                    <p class="clearfix"><label for="pass2">Confirm password*:</label>
                        <input type="password" id="pass2" name="pass2" size="32" value="" /> 
                        <?php echo $error_array['pass2']; ?></p>
                    <p class="clearfix"><label for="name">Name*:</label>
                        <input type="text" id="name" name="name" size="32" value="<?php echo $user[name]; ?>" />
                        <?php echo $error_array['name']; ?></p>
                    <p class="clearfix"><label for="sirname">Surname:</label>
                        <input type="text" id="sirname" name="sirname" size="32" value="<?php echo $user[sirname]; ?>" />
                        <?php echo $error_array['sirname']; ?></p>
                    <p class="clearfix"><label for="name">Family:</label>
                        <input type="text" id="family" name="family" size="32" value="<?php echo $user[family]; ?>" />
                        <?php echo $error_array['family']; ?></p>
                    <p class="clearfix"><label for="email">Email*:</label> 
                        <input type="text" id="email" name="email" size="32" value="<?php echo $user[email]; ?>" /> 
                        <?php echo $error_array['email']; ?></p>
                    <p class="clearfix"><label for="phone">Phone:</label> 
                        <input type="text" id="phone" name="phone" size="32" value="<?php echo $user[phone]; ?>" /> 
                        <?php echo $error_array['phone']; ?></p>
                    <p class="clearfix"><label for="egn">EGN:</label> 
                        <input type="text" id="egn" name="egn" size="32" value="<?php echo $user[egn]; ?>" /> 
                        <?php echo $error_array['egn']; ?></p>
                    <p class="submit">
                        <input type="submit" value="Register"  />
                    </p>
                </fieldset>
            </form>
        </section>
        <?php
    } else {
        echo '<p>Registration successful!<br />Please login!</p>';
    }
} else {
    header('Location: index.php');
    exit;
}
include 'inc/footer.php';
?>