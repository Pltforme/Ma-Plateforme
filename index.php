<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Connexion</title>
</head>

<body>

    <div class="box">
        <form name="formulaire" autocomplete="off" method="POST">
            <h2>Connexion</h2>
            <div class="inputbox">
                <label for="login">Login</label>
                <input type="text" id="login" name="login" required="required">
            </div>
            <div class="inputbox">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required="required">
            </div>
            <br>
            <div class="buttons">
                <input id="login" type="submit" value="Login">
                <div>
                    <p>Vous n'êtes pas inscrit ?</p>
                    <a href="assets/php/inscription.php" id="inscription">Inscrivez-vous</a>
                </div>
            </div>
        </form>

        <?php
        $successMsg = "";
        $errorMsg = "";

        if (!empty($_POST["login"]) && !empty($_POST["password"])) {
            $login = trim($_POST["login"]);
            $password = trim($_POST["password"]);

            include_once('assets/php/connexion.php');
            $con = connexion('essaiebdd');

            $loginEscaped = $con->real_escape_string($login);
            $sql = "SELECT `password` FROM users WHERE `login` = '$loginEscaped' LIMIT 1";
            $result = $con->query($sql);

            if ($result && $result->num_rows === 1) {
                $row = $result->fetch_assoc();
                $storedPassword = $row['password'];

                if (password_verify($password, $storedPassword) || $password === $storedPassword) {
                    session_start();
                    $_SESSION['login'] = $login;
                    echo '<script>window.location.href = "assets/php/acceuil.php";</script>';
                    $successMsg = "Connexion réussie.";
                } else {
                    $errorMsg = "Utilisateur non existant ou mot de passe incorrect.";
                }
            } else {
                $errorMsg = "Utilisateur non existant ou mot de passe incorrect.";
            }

            $con->close();
        } else {
            $errorMsg = "Merci de saisir le login et le mot de passe.";
        }

        if ($successMsg) {
            echo '<div class="message success">';
            echo '<p class="success">' . htmlspecialchars($successMsg) . '</p>';
            echo '</div>';
        } elseif ($errorMsg) {
            echo '<div class="message error">';
            echo '<p class="error">' . htmlspecialchars($errorMsg) . '</p>';
            echo '</div>';
        }
        ?>
    </div>


</body>

</html>