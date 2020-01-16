<?php 
    session_start();
    require __DIR__ . '/aux.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel='stylesheet' href='../../css/style.css'>
    <link rel='stylesheet' href='../css/borrar.css'>
    <title>Document</title>
</head>
<body>
    <?php
        $pdo = conectar();
        navbar();
        
        feet();
    ?>
</body>
</html>