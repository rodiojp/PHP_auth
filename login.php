<?php session_start(); ?>

<!DOCTYPE html>
<html>

<head>
    <title>PHP login form</title>
</head>

<body>
<?php
    //readfile('navigation.tmpl.html');
    $name = '';
    $password = '';

    if (isset($_POST['submit'])) {
        $ok = true;
        if (!isset($_POST['name']) || $_POST['name']==='') {
            $ok=false;
            echo('Please, enter Name<br/>');
        } else {
            $name = $_POST['name'];
        }
        if (!isset($_POST['password']) || $_POST['password']==='') {
            $ok=false;
            echo('Please, enter password<br/>');
        } else {
            $password = $_POST['password'];
        }
        if ($ok) {
            // connect to db
            $db_host = '127.0.0.1';
            $db_user = 'root';
            $db_password = 'Mozyr';
            $db_name = 'php';

            $mysqli = new mysqli($db_host, $db_user, $db_password, $db_name);

            /* check connection */
            if (mysqli_connect_errno()) {
                printf("Connect failed: %s<br/>", mysqli_connect_error());
                exit();
            }
            $sql = sprintf(
                "SELECT * FROM users WHERE name = '%s'",
                $mysqli->real_escape_string($name)
            );
            if ($result = $mysqli->query($sql)) {
                /* fetch associative array */
                while ($row = $result->fetch_assoc()) {
                    $hash= $row['password'];
                    $isAdmin= $row['isAdmin'];
                    if (password_verify($password, $hash)) {
                        $_SESSION['auth_name'] = $name;
                        /* redirect to select.php */
                        header('Location: select.php');
                        // good priactice to use call exit() afterwords
                        exit();
                    }
                    printf('Password %s is incorrect', $password);
                }
            }
            /* close connection */
            //$mysqli->close();
        }
    }
?>
    <form method="post" action="">
        User name:
        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
        <br/> 
        Password:
        <input type="password" name="password" value="<?php echo htmlspecialchars($password); ?>">
        <br/>
        <input type="submit" name="submit" value="Submit">
    </form>
</body>

</html>