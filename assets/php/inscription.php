<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscrivez-vous</title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eff4fb;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: #1f2937;
        }

        form {
            width: min(500px, calc(100% - 32px));
            background: #ffffff;
            padding: 28px 30px;
            border-radius: 22px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
        }

        fieldset {
            border: none;
            padding: 0;
            margin: 0;
        }

        legend {
            font-size: 1.7rem;
            font-weight: 700;
            margin-bottom: 24px;
            color: #111827;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 18px;
        }

        label {
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 0.95rem;
        }

        input {
            border: 1px solid #d1d5db;
            border-radius: 14px;
            padding: 14px 16px;
            font-size: 1rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.16);
        }

        button {
            width: 100%;
            border: none;
            border-radius: 14px;
            background: #2563eb;
            color: #ffffff;
            font-size: 1rem;
            font-weight: 700;
            padding: 14px 18px;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        button:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        button:active {
            transform: translateY(0);
        }

        .success,
        .error {
            margin-top: 18px;
            padding: 14px 16px;
            border-radius: 14px;
            font-size: 0.95rem;
        }

        .success {
            background: #ecfdf5;
            color: #166534;
            border: 1px solid #d1fae5;
        }

        .error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        @media (max-width: 600px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <form method="POST">

        <fieldset>
            <legend>Inscrivez-vous</legend>
            <div class="grid-2">
                <div class="form-group">
                    <label for="nom">Nom *</label>
                    <input type="text" id="nom" name="nom" required placeholder="Votre nom">
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom *</label>
                    <input type="text" id="prenom" name="prenom" required placeholder="Votre prénom">
                </div>
            </div>

            <div class="grid-2">

                <div class="form-group">
                    <label for="telephone">Numéro de téléphone *</label>
                    <input type="tel" id="telephone" name="telephone" required placeholder="Ex: 01 92 34 56 78">
                </div>

            </div>

            <div class="form-group">
                <label for="login">Login *</label>
                <input type="text" id="login" name="login" required placeholder="Nom d'utilisateur">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe *</label>
                <input type="password" id="password" name="password" required placeholder="Mot de passe">
            </div>

        </fieldset>

        <button type="submit">S'inscrire</button>

        <?php
        $successMsg = "";
        $errorMsg = "";

        if (!empty($_POST["nom"]) && !empty($_POST["prenom"]) && !empty($_POST["telephone"]) && !empty($_POST["login"]) && !empty($_POST["password"])) {
            $nom = trim($_POST["nom"]);
            $prenom = trim($_POST["prenom"]);
            $contact = trim($_POST["telephone"]);
            $login = trim($_POST["login"]);
            $password = trim($_POST["password"]);

            include_once("connexion.php");
            $base = 'essaiebdd';
            $con = connexion($base);

            $nom = $con->real_escape_string($nom);
            $prenom = $con->real_escape_string($prenom);
            $contact = $con->real_escape_string($contact);
            $login = $con->real_escape_string($login);

            $checkSql = "SELECT id FROM users WHERE `login` = '$login' LIMIT 1";
            $checkResult = $con->query($checkSql);

            if ($checkResult && $checkResult->num_rows > 0) {
                $errorMsg = "Ce login est déjà utilisé. Choisissez un autre login.";
            } else {
                $sql = "INSERT INTO users (`nom`, `prenom`, `contact`, `login`, `password`) VALUES ('$nom', '$prenom', '$contact', '$login', '$password')";
                if ($con->query($sql)) {
                    $successMsg = "Utilisateur enregistré avec succès.";
                } else {
                    $errorMsg = "Erreur lors de l'enregistrement : " . htmlspecialchars($con->error);
                }
            }

            $con->close();
        }

        if ($successMsg) {
            echo '<div class="success">' . htmlspecialchars($successMsg) . '</div>';
        } elseif ($errorMsg) {
            echo '<div class="error">' . htmlspecialchars($errorMsg) . '</div>';
        }
        ?>

    </form>

</body>

</html>