<?php

function loginForm()
{ ?>
    <main id="form">
        <section>
            <h2>Login</h2>

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

function loguearse(){

}
