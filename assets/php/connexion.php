<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion à la base de données</title>
</head>
<body>
    
    <?php

        $base = "essaiebdd";

        function connexion($base)
        {
            define("HOST", "127.0.0.1");
            define("USER", "root");
            define("PASS", "");
            define("PORT", 3306);

            $idcom = new mysqli(HOST, USER, PASS, $base, PORT);
            if (!$idcom) {
                echo("Connexion impossible à la base de données : " . $idcom->connect_error);
            }

            return $idcom;
        }

    ?>
    
</body>
</html>