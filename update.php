<?php
    require 'auth.php';
    // connect to db
    $db_host = '127.0.0.1';
    $db_user = 'root';
    $db_password = 'Mozyr';
    $db_name = 'php';


    $id = '';
    $name = '';
    $gender = '';
    $color = '';

    if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
        $id = $_GET['id'];

        $mysqli = new mysqli($db_host, $db_user, $db_password, $db_name);

        /* check connection */
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s<br/>", mysqli_connect_error());
            exit();
        }
        $sql = sprintf(
            'SELECT * FROM users WHERE id = %s;',
            $id
        );
    
        //printf('<br/>SQL: %s<br/>', $sql);
        $row_cnt = 0;
        if ($result = $mysqli->query($sql)) {
            /* determine number of rows result set */
            $row_cnt = $result->num_rows;
            //printf("Result set has %d rows.<br/>", $row_cnt);

            foreach ($result as $row) {
                if (isset($row['id']) && $row['id']!=='') {
                    $id = $row['id'];
                }
                if (isset($row['name']) && $row['name']!=='') {
                    $name = $row['name'];
                }
                if (isset($row['gender']) && $row['gender']!=='') {
                    $gender = $row['gender'];
                }
                if (isset($row['color']) && $row['color']!=='') {
                    $color = $row['color'];
                }
            }
        }
        /* close connection */
        $mysqli->close();
    } else {
        header('Location: select.php');
        // good priactice to use call exit() afterwords
        exit();
    }
?>
<!DOCTYPE html>
<html>

<head>
    <title>PHP update from DB</title>
</head>

<body>
<?php
    readfile('navigation.tmpl.html');
    if (isset($_POST['submit'])) {
        $ok = true;
        if (!isset($_POST['id']) || !ctype_digit($_POST['id'])) {
            $ok=false;
            echo('ID is lost<br/>');
        } else {
            $id = $_POST['id'];
        }
        if (!isset($_POST['name']) || $_POST['name']==='') {
            $ok=false;
            echo('Please, enter Name<br/>');
        } else {
            $name = $_POST['name'];
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
            $mysqli = new mysqli($db_host, $db_user, $db_password, $db_name);

            /* check connection */
            if (mysqli_connect_errno()) {
                printf("Connect failed: %s<br/>", mysqli_connect_error());
                exit();
            }
            $sql = sprintf(
                'SELECT * FROM users WHERE id = %s;',
                $id
            );
        
            //printf('<br/>SQL: %s<br/>', $sql);
            $row_cnt = 0;
            if ($result = $mysqli->query($sql)) {
                /* determine number of rows result set */
                $row_cnt = $result->num_rows;
                //printf("Result set has %d rows.<br/>", $row_cnt);
                /* close result set */
                $result->close();

                if ($row_cnt == 0) {
                    printf('There is no row where id = %s<br/>', $id);
                } elseif ($row_cnt > 1) {
                    printf('Too many rows where id = %s<br/>', $id);
                } else {
                    $sql = sprintf(
                        "UPDATE users SET name = '%s', gender = '%s', color = '%s'  WHERE id = %s;",
                        $mysqli->real_escape_string($name),
                        $mysqli->real_escape_string($gender),
                        $mysqli->real_escape_string($color),
                        $id
                    );
                    //printf('<br/>SQL: %s<br/>', $sql);
                    
                    if ($result = $mysqli->query($sql)) {
                        /* determine number of rows updated */
                        //printf("%d Row updated.<br/>", $mysqli->affected_rows);
                        header('Location: select.php');
                        // good priactice to use call exit() afterwords
                        exit();
                    }
                }
            }
            /* close connection */
            $mysqli->close();
        };
    }
    echo('<br/><br/>');
?>
    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        User name:
        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
        <br/> 
        Gender:
        <input type="radio" name="gender" value="f"
            <?php 
            if ($gender==='f') {
                echo 'checked';
            }?> 
        >female
        <input type="radio" name="gender" value="m"
        <?php 
            if ($gender==='m') {
                echo 'checked';
            }?> 
        >male
        <br/> 
        Favorite color:
        <select name="color" >
            <option value="#f00"
                <?php 
                    if ($color==='#f00') {
                        echo ' selected';
                    }?> 
            >red</option>
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