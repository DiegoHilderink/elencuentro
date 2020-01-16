<?php
session_start();
require "../aux.php";
require __DIR__ . "/aux.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?= linkcss() ?>
    <link rel='stylesheet' href='../css/borrar.css'>
    <title>Document</title>
</head>

<body>
    <?php

    const PAR = [
        'nombre' => [
            'def' => '',
            'tipo' => TIPO_CADENA,
            'etiqueta' => 'Nombre'
        ],
        'passwd' => [
            'def' => '',
            'tipo' => TIPO_CADENA,
            'etiqueta' => 'Contraseña'
        ],
    ];
    $errores = [];
    $args = comprobarParametrosnombre(PAR, $errores);
    $pdo = conectar();
    comprobarValoresNombre($args, $pdo, $errores);
    if(es_POST() && empty($errores)){
         $_SESSION['nombre'] = $args['nombre'];
         $_SESSION['token'] = md5(uniqid(mt_rand(), true));
         if (isset($_SESSION['retorno'])) {
             $retorno = $_SESSION['retorno'];
             unset($_SESSION['retorno']);
             header("Location: $retorno");
             return;
         }
         header('Location: /index.php');
         return;
    }

    
    ?>
    <?= navbar() ?>
    <?= nombreForm($errores) ?>
    <?= feet() ?>
</body>

</html>