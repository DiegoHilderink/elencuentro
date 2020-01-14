<?php

function nombreForm($errores)
{ ?>
    <main>
        <?= mostrarErrores($errores) ?>
        <section id="form">
            <h2>nombre</h2>

            <form action="../../index.php" method="post">
                <label for="userid">User ID</label>
                <input type="text" id="userid" name="userid" placeholder="Username....">
                <label for="passwd">Contraseña</label>
                <input type="password" id="passwd" name="passwd" placeholder="Password....">

                <button id="sub" type="submit"><span>Ingress</span></button>
            </form>
        </section>
    </main>
<?php
}

function comprobarParametrosnombre($par, $errores){
    $res = [];
    foreach ($par as $k => $v) {
        if (isset($v['def'])) {
            $res[$k] = $v['def'];
        }
    }

    $peticion = peticion();
    if ((es_GET() && !empty($peticion)) || es_POST()) {
        if ((es_GET() || es_POST() && !empty($peticion))
            && empty(array_diff_key($res, $peticion))
            && empty(array_diff_key($peticion, $res))
        ) {
            $res = array_map('trim', $peticion);
        } else {
            $errores += ['Los parametros recibidos son erroneros' => 'error'];
        }
    }

    return $res;
}


function comprobarValoresnombre(&$args, $pdo, &$errores)
{
    if (!empty($errores) || empty($_POST)) {
        return;
    }

    extract($args);

    if (isset($args['nombre'])) {
        if ($nombre === '') {
            $errores += ['El nombre de usuario no puede estar vacio' => 'warning'];
        } elseif (mb_strlen($nombre) > 255) {
            $errores += ['El nombre de usuario no puede tener mas de 255 caracteres' => 'warning'];
        } else {
            // Comprobar si el usuario existe
            $sent = $pdo->prepare('SELECT *
                                     FROM usuarios
                                    WHERE nombre = :nombre');
            $sent->execute(['nombre' => $nombre]);
            if (($fila = $sent->fetch()) === false) {
                $errores += ['El nombre de usuario no existe' => 'warning'];
            }
        }
    }

    if (isset($args['passwd'])) :
        if ($passwd === '') :
            $errores += ['La contraseña no puede estar vacia' => 'warning'];
        elseif ($fila !== false):
            // Comprobar contraseña
            if (!password_verify($passwd, $fila['passwd'])):
                $args['passwd'] = '';
                $errores += ['Contraseña incorrecta' => 'warning'];
            endif;
        endif;
    endif;
}


function registry(){
    ?>
    <main id="form">
        <section>
            <h2>nombre</h2>

            <form action="../../index.php" method="post">
                <label for="userid">User ID</label>
                <input type="text" id="userid" name="userid" placeholder="Username....">
                <label for="passwd">Contraseña</label>
                <input type="password" id="passwd" name="passwd" placeholder="Password....">
                <label for="maile">Email</label>
                <input type="mail" id="maile" name="maile" placeholder="Email....">
                
                <button id="sub" type="submit"><span>Ingress</span></button>
            </form>
        </section>
    </main>
<?php
}