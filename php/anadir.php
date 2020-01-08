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
    <?= linkcss() ?>
    <title>Index de mi servidor</title>
</head>
<body>
    <?php
    const PAR = [
        'titulo' => [
            'def' => '',
            'tipo' => TIPO_CADENA,
        ],
        'cuerpo' => [
            'def' => '',
            'tipo' => TIPO_CADENA,
        ],
        'categorias'=> [
            'def' => '',
            'tipo' => TIPO_CADENA,
        ],
    ];
    
    navbar();
    $pdo = conectar();
    $errores = [];
    
    $args = comprobarParametros(PAR, $errores);
    comprobarValoresInsertar($args, $pdo, $errores);
    if (es_POST() && empty($errores)) {
        $sent = $pdo->prepare("INSERT INTO notas(titulo, cuerpo, cat_id, fecha) VALUES (:titulo, :cuerpo, (SELECT id FROM categorias WHERE nombre = :categorias), :fecha)");
        $sent->execute($args);
        header('Location: /index.php');
    }
    insertMain(PAR, $errores);
    feet() ?>
</body>

</html>