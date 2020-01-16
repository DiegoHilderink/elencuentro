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
    $errores = [];
    
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

    $sql = 'SELECT u.nombre FROM usuarios u JOIN notas n ON n.user_id = u.id AND n.id = :id';
    $sent = ejecutarUsuarioConsulta($pdo, $sql, 'id', $id);
    $sent = $sent->fetch();


    if(logueado() !== $sent['nombre']){
        $errores += ['No eres dueño de esta nota, no puedes borrarla' => 'error'];
        header('location: /index');
        return;
    }
        
    navbar();
    deleteMain($id, $errores, $pdo);
    feet();
    ?>
</body>

</html>