<?php session_start();
require './aux.php';
require '../aux.php'
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?= linkcss() ?>
    <title>Document</title>
</head>

<body>
    <?php

    const PAR = [
        'nombre' => [
            'def' => '',
            'tipo' => TIPO_CADENA,
        ],
        'passwd' => [
            'def' => '',
            'tipo' => TIPO_CADENA,
        ],
        'mail' => [
            'def' => '',
            'tipo' => TIPO_CADENA,
        ]
    ];
    $errores = [];
    $pdo = conectar();

    $args = comprobarParametrosNombre(PAR, $errores);
    comprobarValoresRegistrar($args, $pdo, $errores);
    if (es_POST() && empty($errores)) {
        $sent = $pdo->prepare('INSERT INTO usuarios (nombre, passwd, mail)
        VALUES (:nombre, :passwd, :mail)');
        if (!$sent->execute([
                'nombre' => $args['nombre'],
                'passwd' => password_hash($args['passwd'], PASSWORD_DEFAULT),
                'mail' => $args['mail'] ?: null,
        ])) {
            $errores += ['Ha ocurrido algún problema' => 'error'];
        } elseif ($sent->rowCount() !== 1) {
            $errores += ['Ha ocurrido algún problema' => 'error'];
        }

        header('Location: /index.php');
        return;
    }
    ?>

    <?= navbar() ?>
    <?= var_dump($args) ?>
    <?= registry($errores) ?>
    <?= feet() ?>
    <!-- <script src="./elencuentro/js/login.js"></script> -->
</body>

</html>