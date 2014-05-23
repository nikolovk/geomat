<?php
include_once 'inc/config.php';
$submit = $_POST['_submit_check'];
if ($_SESSION['logged_in'] != true) {
    if ($submit == 1) {
        $nickname = trim($_POST['nickname']);
        $password = trim($_POST['password']);  
        $stmt = $db->prepare("SELECT `name`,`rights`,`id` FROM users WHERE nickname = :nickname AND password = :password");
        $stmt->bindParam(':nickname', $nickname);
        $stmt->bindParam(':password', md5($password));
        $stmt->execute();
        if ($stmt->rowCount() == 1){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['logged_in'] = true;
            $_SESSION['user'] = $row;
            header('Location: index.php');
            exit;
        } else {
            $error = "Invalid username or password!";
        }
    }
    
} else {
    header('Location: index.php');
    exit;
}
include 'inc/head.php';
if ($error) {
    echo '<p class="error">'.$error.'</p>';
}
?>
<section>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <fieldset>
            <legend>Login</legend>
            <input type="hidden" name="_submit_check" value="1" /> 
            <p class="clearfix"><label for="nickname">Nickname:</label> <input type="text" id="nickname" name="nickname" /></p>
            <p class="clearfix"><label for="password">Password:</label> <input type="password"  id="password" name="password" /></p>
            <p class="submit"><input type="submit" value="login" /></p>
        </fieldset>
    </form>
    </section>
<?php
include 'inc/footer.php';
?>