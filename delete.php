<?php
    require 'auth.php';
    $id = '';
    if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        header('Location: select.php');
        // good priactice to use call exit() afterwords
        exit();
    }
?>
<!DOCTYPE html>
<html>

<head>
    <title>PHP delete from DB</title>
</head>

<body>
<?php 
    readfile('navigation.tmpl.html');

    // connect to db
    $host = '127.0.0.1';
    $user = 'root';
    $password = 'Mozyr';
    $database = 'php';

    $mysqli = new mysqli($host, $user, $password, $database);

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
        /* close result set */
        $result->close();

        if ($row_cnt == 0) {
            printf('There is no row where id = %s<br/>', $id);
        } elseif ($row_cnt > 1) {
            printf('Too many rows where id = %s<br/>', $id);
        } else {
            $sql = sprintf(
                "DELETE FROM users WHERE id = %s;",
                $mysqli->real_escape_string($id)
            );
            
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
    }
    /* close connection */
    $mysqli->close();

?>
</body>
</html>