<?php

function mostrarUsuario($pdo, $sql, $errores, $nombre){
    ?> 
    <main>
    <?php if(empty($errores)): ?> 
    <?php 
        $sent = ejecutarUsuarioConsulta($pdo, $sql, 'nombre', $nombre); 
        $sent = $sent->fetch();
    ?>
    <div>
        <p><?= $sent['nombre']?></p>
    </div>
    
    <?php else: ?>
        <?= mostrarErrores($errores); ?>
    <?php endif ?>

    </main>
     <?php
    
}
