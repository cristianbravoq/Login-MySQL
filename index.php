<?php
    require 'databases.php';

    /////////////// Registar usuario nuevo ////////////////
    $message = '';

    if (!empty($_POST['email']) && !empty($_POST['password'])) {
      $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':email', $_POST['email']);
      $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
      $stmt->bindParam(':password', $password);

        if ($stmt->execute()) {
            $message = 'Ha sido creado un nuevo usuario';
        } else {
            $message = 'Ha ocurrido un error';
        }
    }

    ///////////////////////////////////////////////////////

    session_start();

    if (!empty($_POST['email_log']) && !empty($_POST['password_log'])) {
        $records = $conn->prepare('SELECT id, email, password FROM users WHERE email = :email');
        $records->bindParam(':email', $_POST['email_log']);
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);

        if (count($results) > 0 && password_verify($_POST['password_log'], $results['password'])) {
            $_SESSION['user_id'] = $results['id'];
            header("Location: /Proyecto-login/login.php");
        } else {
            $message = 'Sorry, those credentials do not match';
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>I.E. El placer</title>

    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>

    <main>

        <div class="contenedor__todo">
            <div class="caja__trasera">
                <div class="caja__trasera-login">
                    <h3>¿Ya tienes una cuenta?</h3>

                    <p>Inicia sesión para entrar en la página</p>
                    <button id="btn__iniciar-sesion">Iniciar Sesión</button>
                </div>
                <div class="caja__trasera-register">
                    <h3>¿Aún no tienes una cuenta?</h3>
                    
                    <?php if(!empty($message)): ?>
                    <p> <?= $message ?></p>
                    <?php endif; ?>

                    <p>Regístrate para que puedas iniciar sesión</p>
                    <button id="btn__registrarse">Regístrarse</button>
                </div>
            </div>

            <!--Formulario de Login y registro-->
            <div class="contenedor__login-register">
                <!--Login-->
                <form action="index.php" method="POST" class="formulario__login">
                    <h2>Iniciar Sesión</h2>
                    <input type="text" name="email_log" placeholder="Correo Electronico">
                    <input type="password" name="password_log" placeholder="Contraseña">
                    <button type="submit">Entrar</button>
                </form>

                <!--Register-->
                <form action="index.php" method="POST" class="formulario__register">
                    <h2>Regístrarse</h2>
                    <input type="text" placeholder="Nombre completo">
                    <input type="text" name="email" placeholder="Correo Electronico">
                    <input type="text" placeholder="Usuario">
                    <input type="password" name="password" placeholder="Contraseña">
                    <button type="submit">Regístrarse</button>
                </form>
            </div>
        </div>

    </main>

<script src="assets/js/script.js"></script>

</body>
</html>