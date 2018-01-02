<?php
    require 'auth.php';
    if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
        $id = $_GET['id'];
    }
?>
<!DOCTYPE html>
<html>

<head>
    <title>PHP select from DB</title>
    <style>
        table, th, td {
            border: 1px solid black;
        }
        </style>
</head>
<body>
<?php
    readfile('navigation.tmpl.html');

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
    if (!isset($id)) {
        $sql = 'SELECT * FROM users;';
    } else {
        $sql = sprintf(
            'SELECT * FROM users WHERE id = %s;',
            $mysqli->real_escape_string($id)
        );
    }
 
    $row_cnt = 0;
    if ($result = $mysqli->query($sql)) {
        /* determine number of rows result set */
        $row_cnt = $result->num_rows;
        printf("Result set has %d rows.<br/>", $row_cnt);
        
        echo '<table ><tbody><tr><th>Id</th><th>User name</th><th>Gender</th><th>Favorite color</th><th>Actions</th><th>Delete</th><th>Select</th></tr>';
    
        foreach ($result as $row) {
            $id = $row['id'];
            $name= $row['name'];
            $gender = $row['gender'];
            $color = $row['color'];
            printf("<tr style='background-color: %s;'>", $color);
            if (!$id || $id==='') {
                echo('<td></td>');
            } else {
                printf('<td>%s</td>', htmlspecialchars($id));
            }
            if (!$name || $name==='') {
                echo('<td></td>');
            } else {
                printf('<td>%s</td>', htmlspecialchars($name));
            }
            if (!isset($gender) || $gender==='') {
                echo('<td></td>');
            } else {
                printf('<td>%s</td>', htmlspecialchars($gender));
            }
            if (!isset($color) || $color==='') {
                echo('<td></td>');
            } else {
                printf('<td>%s</td>', htmlspecialchars($color));
            }
            if (!$id || $id==='') {
                echo('<td></td>');
                echo('<td></td>');
                echo('<td></td>');
            } else {
                printf("<td><a href='update.php?id=%s'>Update</a></td>", htmlspecialchars($id));
                printf("<td><a href='delete.php?id=%s'>Delete</a></td>", htmlspecialchars($id));
                printf("<td><a href='select.php?id=%s'>Select</a></td>", htmlspecialchars($id));
            }
            echo('</tr>');
        }
        /* close result set */
        $result->close();

        echo '</tbody></table>';
    }
    /* close connection */
    $mysqli->close();
    echo('<br/><br/>');
?>
</body>

</html>