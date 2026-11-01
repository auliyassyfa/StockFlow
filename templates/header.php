<?php
// Calculate relative path to project root dynamically
$rootDir = str_replace('\\', '/', realpath(dirname(__DIR__)));
$currentScriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME']));

$pathToRoot = '';
if (strpos($currentScriptDir, $rootDir) === 0) {
    $subDir = substr($currentScriptDir, strlen($rootDir));
    $subDir = trim($subDir, '/');
    if ($subDir !== '') {
        $parts = explode('/', $subDir);
        $pathToRoot = str_repeat('../', count($parts));
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Inventory JWP</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Custom CSS -->
    <link
        rel="stylesheet"
        href="<?= $pathToRoot; ?>assets/css/style.css">

</head>

<body>