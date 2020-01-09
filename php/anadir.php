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
    <link rel='stylesheet' href='../css/anadir.css'>
    <title>Index de mi servidor</title>
</head>
<body>
    <?php
    const PAR = [
        'titulo' => [
            'def' => '',
            'tipo' => TIPO_CADENA,
        ],
        'header' => [
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
    var_dump($_POST);
    if (es_POST() && empty($errores)) {
        $sent = $pdo->prepare("INSERT INTO notas(titulo, header, cuerpo, cat_id, fecha) VALUES (:titulo, :header, :cuerpo, (SELECT id FROM categorias WHERE nombre = :categorias), :fecha)");
        $sent->execute($args);
        header('Location: /index.php');
    }
    insertMain($errores);
    feet() ?>
</body>

</html>