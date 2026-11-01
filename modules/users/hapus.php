<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

require '../../config/database.php';

$id = $_GET['id'];

mysqli_query(
    $conn,
    "DELETE FROM users
     WHERE id='$id'"
);

header("Location: index.php");
exit;