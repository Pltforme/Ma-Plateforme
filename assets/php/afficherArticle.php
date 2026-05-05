<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Affichage des articles</title>
    <link rel="stylesheet" href="../styles/afficherArticle.css" />
</head>

<body>
    <div class="container">
        <?php
            include_once("connexion.php");
            $con = connexion($base);
            $requete = "SELECT * FROM article ORDER BY catégorie";
            $result = $con->query($requete);
            if (!$result) {
                echo '<div class="error">Lecture impossible des articles.</div>';
            } else {
                $nbart = $result->num_rows;
                echo '<div class="action-bar">';
                echo '<div>';
                echo '<h2>Tous nos articles par catégorie</h2>';
                echo '<p>Il y a <strong>' . $nbart . '</strong> article(s) en stock.</p>';
                echo '</div>';
                echo '<div>';
                echo '<button type="button" onclick="location.href=\'formulaireEnrArticle.php\'">Ajouter un article</button>';

                echo '<button type="button" onclick="location.href=\'acceuil.php\'">Retour</button>';

                echo '</div>';
                echo '</div>';

                echo '<div class="table-section">';
                echo '<table>';
                echo '<thead><tr>';
                echo '<th>Code article</th>';
                echo '<th>Description</th>';
                echo '<th>Prix</th>';
                echo '<th>Catégorie</th>';
                echo '</tr></thead>';
                echo '<tbody>';

                while ($ligne = $result->fetch_assoc()) {
                    echo '<tr>';
                    foreach ($ligne as $nomChamp => $valeur) {
                        if ($nomChamp === 'prix') {
                            $valeur = is_numeric($valeur) ? number_format((float) $valeur, 2, ',', ' ') : $valeur;
                        }
                        echo '<td>' . htmlspecialchars($valeur, ENT_QUOTES, 'UTF-8') . '</td>';
                    }
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
                echo '</div>';
            }
            if (isset($result) && $result instanceof mysqli_result) {
                $result->free();
            }
            $con->close();
        ?>
    </div>
</body>

</html>