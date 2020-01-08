<?php
const TIPO_CADENA = 0;
const TIPO_ENTERO = 1;
const TIPO_PASSWD = 2;

function linkcss()
{
    ?>
    <link rel='stylesheet' href='../../css/style.css'>
    <link rel='stylesheet' href='../../css/index.css'>
    <link rel='stylesheet' href='../../css/form.css'>

<?php
}

function navbar()
{
    ?>
    <header>
        <ul id="nav">
            <li><a href="/">El Encuentro</a></li>
            <li><a href="">MiniJuego</a></li>
            <li><a href="">Converter</a></li>
            <li><a href="">Calculadora</a></li>
            <li><a href="">Chat</a></li>
            <li class="spec"><a href="/php/login/login.php">Login</a></li>
            <li class="spec"><a href="/php/login/registry.php">Registry</a></li>

        </ul>
    </header>
<?php
}

function indexMain($sent)
{
    ?>
    <main>
        <div class="card">
            <?php foreach ($sent as $k => $v) : ?>
                <div>
                    <a href="php/borrar.php?id=<?= $v['id'] ?>" class="button">x</a>
                    <h4><b><?= $v['titulo'] ?></b></h4>
                    <p id='interior'><?= $v['cuerpo'] ?>...</p>
                    <p class="cardfoot"><small><?= $v['fecha'] ?></small></p>
                </div>
            <?php endforeach ?>

            <div id='anadir' onclick="document.location.href='php/anadir.php'">
                <h4><b>Nueva Nota</b></h4>
                <p id='img'>+</p>
                <p class="cardfoot"><small><?= date('j F Y') ?></small></p>
            </div>
        </div>
    </main>
<?php
}

function insertMain($par, $errores)
{ ?>
    <main >
        <?= mostrarErrores($errores); ?>
        <section id="form">
            <h2>Crear nueva Nota</h2>

            <form action="" method="post">
                <?php foreach ($par as $k => $v) : ?>
                    <label for=<?= $k ?>><?= ucwords($k) ?></label>
                    <input type="text" id=<?= $k ?> name=<?= $k ?> placeholder=<?= ucwords($k) ?>>
                <?php endforeach ?>
                <button id="sub" type="submit"><span>Ingress</span></button>
            </form>
        </section>
    </main>
<?php
}

function deleteMain($id, $errores)
{
    ?>
    <main>
    <?= mostrarErrores($errores); ?> 
        <div id='borrar'>
            <h2>¿Esta seguro de que desea borrar esta nota?</h2>
            <form action="" method="post">
                <input type="hidden" name="id" value="<?= $id ?>">
                <button type="submit">Si</button>
                <a href="/index.php">No</a>
            </form>
        </div>
    </main>
<?php
}

function feet()
{ ?>
    <footer id="feet">
        <p><small>Designed by Diego Hilderink Dominguez</small>
            <small id="fecha"><?php echo date('l j F Y') ?></small></p>
    </footer>
<?php
}

function ejecutarConsulta($sql, $pdo)
{
    $sent = $pdo->query($sql);
    return $sent;
}

function comprobarParametros($par, &$errores)
{
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

    $res +=  ['fecha' => date('j F Y')];
    return $res;
}

function comprobarValoresInsertar(&$args, $pdo, &$errores)
{
    if (!empty($errores) || empty($_POST)) {
        return;
    }

    extract($args);

    if (isset($args['titulo'])) {
        if ($titulo === '') {
            $errores += ['El titulo es obligatorio.' => 'warning'];
        } else {
            $sent = $pdo->prepare('SELECT COUNT(*)
                                FROM notas
                            WHERE titulo = :titulo');
            $sent->execute(['titulo' => $titulo]);
            if ($sent->fetchColumn() > 0) {
                $errores += ['El título ya existe.' => 'warning'];
            }
        }
    }

    if (isset($args['cuerpo'])) {
        if ($cuerpo === '') {
            $errores += ['El cuerpo es obligatorio' => 'warning'];
        }
    }

    if (isset($args['categorias'])) {
        if ($categorias === '') {
            $errores += ['La categoria es obligatoria.' => 'warning'];
        } else {
            $sent = $pdo->prepare('SELECT COUNT(*)
                                FROM categorias
                            WHERE nombre = :categorias');
            $sent->execute(['categorias' => $categorias]);
            if ($sent->fetchColumn() <= 0) {
                $errores += ['Esa categoria no existe.' => 'warning'];
            }
        }
    }
}

function borrar($pdo, $id, &$errores)
{
    $sql = $pdo->prepare('DELETE FROM notas WHERE id = :id');
    $sql->execute(['id' => $id]);
    if ($sql->rowCount() !== 1) {
        $errores += ['La nota no se ha borrado' => 'warning'];
    }

    header('Location: /index.php');
}

function mostrarErrores($errores)
{
    if (!empty($errores)) {
        foreach ($errores as $k => $v):
            ?>
            <div class='<?= $v ?>'>
                    <h4><?= $k ?></h4>
                </div>
                <?php
    endforeach;
    }
}

function conectar()
{
    return new PDO('pgsql:host=localhost; dbname=datos', 'usuario', 'usuario');
}

function es_GET()
{
    return metodo() === 'GET';
}

function es_POST()
{
    return metodo() === 'POST';
}

function metodo()
{
    return $_SERVER['REQUEST_METHOD'];
}
function peticion()
{
    return metodo() === 'GET' ? $_GET : $_POST;
}
