<?php 
    session_start();
    if (isset($_GET['signout']) && $_GET['signout']==='true') {
        $_SESSION['auth_name'] = '';
    }


    if (!isset($_SESSION['auth_name']) || $_SESSION['auth_name']==='') {
        /* redirect to select.php */
        header('Location: login.php');
        // good priactice to use call exit() afterwords
        exit();
    }
