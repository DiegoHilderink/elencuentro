<?php
session_start();
require __DIR__ . "/aux.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel='stylesheet' href='../css/style.css'>
    <link rel='stylesheet' href='../css/borrar.css'>
    <title>Index de mi servidor</title>
</head>

<body>
    <?php
    $pdo = conectar();
        if (es_GET()) {
            if (isset($_GET['id'])) {
                $id = trim($_GET['id']);
            } else {
                $errores += ['Nota no indicada' => 'warning'];
            }
        } else {
            if (es_POST()) {
                if (isset($_POST['id'])) {
                    $id = trim($_POST['id']);
                    borrar($pdo, $id, $errores);
                } else {
                    $errores += ['Esa categoria no existe.' => 'error'];
                }
            }
        }

        
    navbar();
    $errores = [];
    deleteMain($id, $errores, $pdo);
    feet();
    ?>
</body>

</html>