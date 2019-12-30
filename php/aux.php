<?php
const TIPO_CADENA = 0;
const TIPO_ENTERO = 1;
const TIPO_PASSWD = 2;



function navbar()
{
?>
    <header>
        <ul id="nav">
            <li><a href="/">El Encuentro</a></li>
            <li><a href="../life-circle/html">MiniJuego</a></li>
            <li><a href="../converter">Converter</a></li>
            <li><a href="../calculadora/html">Calculadora</a></li>
            <li><a href="../chat/html">Chat</a></li>
            <li id="spec"><a href="/php/login/login.php">Login</a></li>
        </ul>
    </header>
<?php
}

function feet()
{ ?>
    <footer id="feet">
        <p><small>Designed by Diego Hilderink Dominguez</small>
        <small id="fecha"><?php echo date('l j F Y')?></small></p>
    </footer> 
<?php
}

function conectar(){
    return new PDO('pgsql:host=localhost; dbname=datos', 'usuario', 'usuario');
}




