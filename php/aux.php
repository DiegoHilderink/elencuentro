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
            <?php if(!logueado()): ?> 
                <li class="spec"><a href="/php/login/login.php">Login</a></li>
                <li class="spec"><a href="/php/login/registry.php">Registry</a></li>
            <?php else: ?>
                <li class="spec"><a href="/php/login/logout.php">Logout</a></li>
                <li class="spec"><a href="/php/usuario/user.php?nombre=<?=logueado()?>" disabled><?= ucwords(logueado())?></a></li>
                <li class="spec"><a href="/php/anadir.php" >Nueva Nota</a></li>
            <?php endif ?>
        </ul>
    </header>
<?php
}

function logueado()
{
    return isset($_SESSION['nombre']) ? $_SESSION['nombre'] : false;
}

function indexMain($sent)
{
    ?>
        <div class="card">
            <?php foreach ($sent as $k => $v) : ?>
                <div>
                    <a href="php/borrar.php?id=<?= $v['id'] ?>" class="button">x</a>
                    <a href="php/modificar.php?id=<?= $v['id'] ?>" class="button" id='mod'>⚙️</a>
                    <h4><b><?=maxLentgh($v['titulo']) ?></b></h4>
                    <p class='interior'><?= maxLentgh($v['header']) ?>...</p>
                    <p class="cardfoot"><small><?= $v['fecha'] ?></small></p>
                </div>
            <?php endforeach ?>

            
        </div>
<?php
}

function insertMain($errores)
{ ?>
    <main >
        <?= mostrarErrores($errores); ?>
        <section id="form">
            <h2>Crear nueva Nota</h2>

            <form action="" method="post">
                    <label for='titulo'>Titulo</label>
                    <input type="text" id='titulo' name='titulo' placeholder='Titulo'>

                    <label for='header'>Cabecera</label>
                    <input type="text" id='header' name='header' placeholder='Cabecera'>
                    
                    <label for='cuerpo'>Cuerpo</label>
                    <input type="textarea" id='cuerpo' name='cuerpo' placeholder='Cuerpo'>
                    
                    <label for='categorias'>Categoria</label>
                    <input type="text" id='categorias' name='categorias' placeholder="Categoria">
                <button id="sub" type="submit"><span>Ingress</span></button>
            </form>
        </section>
    </main>
<?php
}

function maxLentgh($palabras){
    $salida = ''; $array = explode(' ', $palabras);
    foreach($array as $v):
        if(mb_strlen($v) > 15):
            $salida .= substr($v, 0, 15);
            return $salida;
        else:
            $salida .= $v .' ';
        endif;
    endforeach;
    return $salida;
}



function lista($pdo){
    $par = generateSql($pdo);
    ?><div id='lista'>
            <ul>
        <?php foreach($par as $k => $v): ?>
                <li><a href="/php/modificar.php?$id=<?=$v['id']?>"><?=$v['titulo']?></a><label id='votos'>10</label></li>
        <?php endforeach ?>
            </ul>
    </div><?php
}

function generateSql($pdo){
    $sql = 'SELECT * FROM notas;';
    return $par = ejecutarConsulta($sql,  $pdo);
}

function deleteMain($id, $errores, $pdo)
{
    $sql = $pdo->prepare('SELECT * FROM notas n JOIN categorias c ON n.cat_id = c.id  WHERE n.id = :id;');
    $sql->execute(['id'=>$id]);
    $sql=$sql->fetch(); ?>
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
    
        <div id='nota'>
            <h2><?=$sql['titulo']?></h2>
            <div>
                <label for="header">Cabecera</label>
                <p id='header'><?=$sql['header']?>...</p>
                <label for="cuerpo">Cuerpo</label>
                <p id='cuerpo'><?=$sql['cuerpo']?>...</p>
                <label for="cat">Categoria</label>
                <p id='cat'><?=ucwords($sql['nombre'])?></p>
            </div>
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

    if (isset($args['header'])) {
        if ($header === '') {
            $errores += ['El header es obligatorio' => 'warning'];
        } else {
            $sent = $pdo->prepare('SELECT COUNT(*)
                                     FROM notas
                                    WHERE header = :header');
            $sent->execute(['header' => $header]);
            if ($sent->fetchColumn() > 0) {
                $errores += ['La cabecera ya existe.' => 'warning'];
            }
        }
    }

    if (isset($args['cuerpo'])) {
        if ($header === '') {
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

function ejecutarConsulta($sql, $pdo)
{
    $sent = $pdo->query($sql);
    return $sent;
}

function ejecutarUsuarioConsulta($pdo, $sql, $k, $v){
    $sent = $pdo->prepare($sql);
    $sent->execute([$k => $v]);
    return $sent;
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
