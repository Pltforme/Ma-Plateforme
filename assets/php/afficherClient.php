<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Affichage des clients</title>
    <link rel="stylesheet" href="../styles/afficherArticle.css" />
</head>

<body>
    <div class="container">
        <?php
            include_once("connexion.php");
            $con = connexion($base);
            $requete = "SELECT * FROM client ORDER BY nom";
            $result = $con->query($requete);
            if (!$result) {
                echo '<div class="error">Lecture impossible des clients.</div>';
            } else {
                $nbart = $result->num_rows;
                echo '<div class="action-bar">';
                echo '<div>';
                echo '<h2>Tous nos clients</h2>';
                echo '<p>Il y a <strong>' . $nbart . '</strong> client(s) enregistré(s).</p>';
                echo '</div>';
                echo '<div>';

                echo '<button type="button" onclick="location.href=\'acceuil.php\'">Retour</button>';

                echo '</div>';
                echo '</div>';

                echo '<div class="table-section">';
                echo '<table>';
                echo '<thead><tr>';
                echo '<th>Code client</th>';
                echo '<th>Nom</th>';
                echo '<th>Prénom</th>';
                echo '<th>Age</th>';
                echo '<th>Adresse</th>';
                echo '<th>Ville</th>';
                echo '<th>Email</th>';
                echo '</tr></thead>';
                echo '<tbody>';

                while ($ligne = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($ligne['id_client'], ENT_QUOTES, 'UTF-8') . '</td>';
                    echo '<td>' . htmlspecialchars($ligne['nom'], ENT_QUOTES, 'UTF-8') . '</td>';
                    echo '<td>' . htmlspecialchars($ligne['prenom'], ENT_QUOTES, 'UTF-8') . '</td>';
                    echo '<td>' . htmlspecialchars($ligne['age'], ENT_QUOTES, 'UTF-8') . '</td>';
                    echo '<td>' . htmlspecialchars($ligne['adresse'], ENT_QUOTES, 'UTF-8') . '</td>';
                    echo '<td>' . htmlspecialchars($ligne['ville'], ENT_QUOTES, 'UTF-8') . '</td>';
                    echo '<td>' . htmlspecialchars($ligne['mail'], ENT_QUOTES, 'UTF-8') . '</td>';
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