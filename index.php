<?php
session_start();
require __DIR__ . "/php/aux.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel='stylesheet' href='../../css/style.css'>
    <link rel='stylesheet' href='../../css/index.css'>
    <title>Index de mi servidor</title>
</head>

<body>
    <?php
        $pdo = conectar();
        $sql = 'SELECT * FROM notas;';
        $sent = ejecutarConsulta($sql, $pdo);
    ?>
    <?= navbar() ?>
    <main>
    <?= indexMain($sent) ?>
    <?= lista($pdo) ?>
    </main>
    <?= feet() ?>
</body>

</html>