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

    if(!logueado()){
        $errores += ['Debe estar logueado para crear una nota' => 'warning'];
        header('location: /index.php');
        return;
    }
    
    $args = comprobarParametros(PAR, $errores);
    $sql = 'SELECT id FROM usuarios WHERE nombre = :nombre';
    $user_id = ejecutarUsuarioConsulta($pdo, $sql,'nombre', logueado());
    $user_id = $user_id->fetch();
    comprobarValoresInsertar($args, $pdo, $errores);
    $args += ['user_id' => $user_id['id']];
    $args += ['fecha' => date('j F Y')];

    if (es_POST() && empty($errores)) {
        $sent = $pdo->prepare("INSERT INTO notas(titulo, header, cuerpo, cat_id, user_id, fecha) VALUES (:titulo, :header, :cuerpo, (SELECT id FROM categorias WHERE nombre = :categorias), :user_id, :fecha)");
        $sent->execute($args);
        header('Location: /index.php');
    }
    insertMain($errores);
    feet() ?>
</body>

</html>