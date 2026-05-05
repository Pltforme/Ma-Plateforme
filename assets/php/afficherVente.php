<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Affichage des ventes</title>
    <link rel="stylesheet" href="../styles/afficherArticle.css" />
</head>

<body>
    <div class="container">
        <?php
            include_once("connexion.php");
            $con = connexion($base);
            $requete = "SELECT c.id_commande, c.date_commande, c.montant,
                               cl.nom, cl.prenom, cl.mail,
                               GROUP_CONCAT(CONCAT(a.design, ' (', co.qte_comm, ')') SEPARATOR ', ') as articles
                        FROM commande c
                        LEFT JOIN client cl ON c.id_client = cl.id_client
                        LEFT JOIN contenir co ON c.id_commande = co.id_commande
                        LEFT JOIN article a ON co.code_article = a.code_article
                        GROUP BY c.id_commande, c.date_commande, c.montant, cl.nom, cl.prenom, cl.mail
                        ORDER BY c.date_commande DESC";

            $result = $con->query($requete);
            if (!$result) {
                echo '<div class="error">Lecture impossible des ventes.</div>';
            } else {
                $nbVentes = $result->num_rows;
                echo '<div class="action-bar">';
                echo '<div>';
                echo '<h2>Toutes nos ventes</h2>';
                echo '<p>Il y a <strong>' . $nbVentes . '</strong> vente(s) enregistrée(s).</p>';
                echo '</div>';
                echo '<div>';

                echo '<button type="button" onclick="location.href=\'acceuil.php\'">Ajouter un article</button>';

                echo '</div>';
                echo '</div>';

                echo '<div class="table-section">';
                echo '<table>';
                echo '<thead><tr>';
                echo '<th>ID Commande</th>';
                echo '<th>Date</th>';
                echo '<th>Client</th>';
                echo '<th>Email</th>';
                echo '<th>Articles</th>';
                echo '<th>Montant</th>';
                echo '</tr></thead>';
                echo '<tbody>';

                while ($ligne = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($ligne['id_commande'], ENT_QUOTES, 'UTF-8') . '</td>';
                    echo '<td>' . htmlspecialchars($ligne['date_commande'], ENT_QUOTES, 'UTF-8') . '</td>';
                    echo '<td>' . htmlspecialchars($ligne['nom'] . ' ' . $ligne['prenom'], ENT_QUOTES, 'UTF-8') . '</td>';
                    echo '<td>' . htmlspecialchars($ligne['mail'], ENT_QUOTES, 'UTF-8') . '</td>';
                    echo '<td>' . htmlspecialchars($ligne['articles'], ENT_QUOTES, 'UTF-8') . '</td>';
                    echo '<td>' . number_format((float)$ligne['montant'], 2, ',', ' ') . ' €</td>';
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