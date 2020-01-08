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
    const PAR = [
        'titulo' => [
            'def' => '',
            'tipo' => TIPO_CADENA,
        ],
        'cuerpo' => [
            'def' => '',
            'tipo' => TIPO_CADENA,
        ],
        'categorias' => [
            'def' => '',
            'tipo' => TIPO_CADENA,
        ],
    ];

    $pdo = conectar();
        if(es_GET()){
            if(isset($_GET['id'])){
                $id = trim($_GET['id']);
            } else {
                $errores[] = 'Nota no indicada';
            }
        } else {
            if(es_POST()){
                if(isset($_POST['id'])){
                    $id = trim($_POST['id']);
                    borrar($pdo, $id, $errores);
                } else {
                    $errores[]='Ha ocurrido un error';
                }
            }
        }

        if(!isset($errores)){
            var_dump($errores);
        }

    $errores = [];
    $args = comprobarParametros(PAR, $errores);

    navbar();
    deleteMain($id);
    feet();
    ?>
</body>

</html>