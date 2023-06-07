<?php

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

$userId = $_POST['user_id'];

if ($userId) {

    $sql = "DELETE FROM users WHERE user_id = {$userId}";

    if ($connect->query($sql) === TRUE) {
        $valid['success'] = true;
        $valid['messages'] = "Account successfully deleted";
        
        // Log out the user
        session_start();
        session_unset();
        session_destroy();

        // Redirect to index.php
        header("Location: ../index.php");
        exit();
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error while deleting the account";
    }

    $connect->close();

    echo json_encode($valid);
}
