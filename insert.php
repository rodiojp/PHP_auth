<?php 
    require 'auth.php'; 
?>
<!DOCTYPE html>
<html>

<head>
    <title>PHP insert form</title>
</head>

<body>
    <?php
    readfile('navigation.tmpl.html');
    $name = '';
    $password = '';
    $gender = '';
    $color = '';

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
        if (!isset($_POST['gender']) || $_POST['gender']==='') {
            $ok=false;
            echo('Please, enter Gender<br/>');
        } else {
            $gender = $_POST['gender'];
        }
        if (!isset($_POST['color']) || $_POST['color']==='') {
            $ok=false;
            echo('Please, enter Color<br/>');
        } else {
            $color = $_POST['color'];
        }
        if ($ok) {
            // connect to db
            $db_host = '127.0.0.1';
            $db_user = 'root';
            $db_password = 'Mozyr';
            $db_name = 'php';

            $mysqli = new mysqli($db_host, $db_user, $db_password, $db_name);
     
            $hash = password_hash($password, PASSWORD_DEFAULT);

            /* check connection */
            if (mysqli_connect_errno()) {
                printf("Connect failed: %s<br/>", mysqli_connect_error());
                exit();
            }
            $sql = sprintf(
                "INSERT INTO users (name, password, gender, color) values ('%s','%s','%s','%s');",
                $mysqli->real_escape_string($name),
                $mysqli->real_escape_string($hash),
                $mysqli->real_escape_string($gender),
                $mysqli->real_escape_string($color)
            );
            $row_cnt = 0;
            if ($result = $mysqli->query($sql)) {
                if ($result) {
                    /* redirect to select.php */
                    header('Location: select.php');
                    // good priactice to use call exit() afterwords
                    exit();
                }
                printf('Row id=%s cannot be deleted', $id);
            }
        }
        /* close connection */
        $mysqli->close();
    }
?>
    <form method="post" action="">
        User name:
        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
        <br/> Password:
        <input type="password" name="password" value="<?php echo htmlspecialchars($password); ?>">
        <br/> Gender:
        <input type="radio" name="gender" value="f" <?php if ($gender==='f') {
    echo 'checked';
}?> >female
        <input type="radio" name="gender" value="m" <?php if ($gender==='m') {
    echo 'checked';
}?> >male
        <br/> Favorite color:
        <select name="color">
        <option value="#f00" <?php if ($color==='#f00') {
    echo ' selected';
}?>>red</option>
        <option value="#0f0"
            <?php 
                if ($color==='#0f0') {
                    echo ' selected';
                }?> 
        >green</option>
        <option value="#00f"
            <?php 
                if ($color==='#00f') {
                    echo ' selected';
                }?> 
        >blue</option>
        </select>
            <br/>
            <input type="submit" name="submit" value="Submit">
    </form>
</body>

</html>