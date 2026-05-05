<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Enregistrement d'article</title>
    <link rel="stylesheet" href="../styles/enregistrement.css" />
</head>
<body>

    <div class="container">
        <div class="form-section">
            <h1>Enregistrement d'article</h1>
            <?php

                if (isset($_POST["art"])) {
                    $code = trim($_POST["art"][0]);
                    $designation = trim($_POST["art"][1]);
                    $prixInput = trim($_POST["art"][2]);
                    $prixInput = str_replace(',', '.', $prixInput);
                    $prix = is_numeric($prixInput) ? number_format((float) $prixInput, 2, '.', '') : '0.00';
                    $categorie = trim($_POST["art"][3]);

                    include_once("connexion.php");

                    $con = connexion($base);
                    

                    $sql = "INSERT IGNORE INTO article (`code_article`, `design`, `prix`, `catégorie`) VALUES ($code, $designation, $prix, $categorie)";
                    $result = $con->query($sql);
                    if (!$result) {
                        echo '<div class="error">Erreur lors de l\'exécution de la requête : ' . htmlspecialchars($con->error, ENT_QUOTES, 'UTF-8') . '</div>';
                    } elseif ($con->affected_rows > 0) {
                        echo '<div class="success">Article enregistré avec succès.</div>';
                    } else {
                        echo '<div class="error">L\'article existe déjà ou une erreur est survenue lors de l\'enregistrement.</div>';
                    }

                    $con->close();
                } else {
                    echo '<div class="error">Aucune donnée reçue.</div>';
                }
            ?>

            <div class="links" style="margin-top: 20px;">
                <a class="button-link" href="formulaireEnrArticle.php">Retour au formulaire</a>
                <a class="button-link" href="afficherArticle.php">Voir les articles</a>
            </div>
        </div>
    </div>
</body>
</html>