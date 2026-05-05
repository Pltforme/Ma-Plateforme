<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Traitement de la vente</title>
    <link rel="stylesheet" href="../styles/traiterVente.css" />
</head>

<body>
    <div class="container">
        <?php
        include_once("connexion.php");

        $con = connexion('essaiebdd');

        function generateUniqueId($con, $table, $column, $length = 10)
        {
            if ($table === 'client') {
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            } else {
                $chars = '0123456789abcdefghijklmnopqrstuvwxyz';
            }
            
            do {
                $id = '';
                for ($i = 0; $i < $length; $i++) {
                    $id .= $chars[random_int(0, strlen($chars) - 1)];
                }
                $idEscaped = $con->real_escape_string($id);
                $result = $con->query("SELECT 1 FROM $table WHERE $column = '$idEscaped' LIMIT 1");
            } while ($result && $result->num_rows > 0);

            return $id;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            

            try {
                // Récupérer les données du client
                $nom_client = trim($_POST['nom_client']);
                $age = intval($_POST['age']);
                $prenom_client = trim($_POST['prenom_client']);
                $adresse_client = trim($_POST['adresse_client']);
                $ville_client = trim($_POST['ville_client']);
                $email_client = trim($_POST['email_client']);

                // Vérifier les données obligatoires
                if (empty($nom_client) || empty($prenom_client) || empty($adresse_client) || empty($ville_client) || empty($email_client)) {
                    throw new Exception("Tous les champs client sont obligatoires.");
                }

                // Créer le client
                $id_client = generateUniqueId($con, 'client', 'id_client');
                $nom_escaped = $con->real_escape_string($nom_client);
                $prenom_escaped = $con->real_escape_string($prenom_client);
                $adresse_escaped = $con->real_escape_string($adresse_client);
                $ville_escaped = $con->real_escape_string($ville_client);
                $email_escaped = $con->real_escape_string($email_client);

                $sql_client = "INSERT INTO client (id_client, nom, prenom, age, adresse, ville, mail) VALUES ('$id_client', '$nom_escaped', '$prenom_escaped', $age, '$adresse_escaped', '$ville_escaped', '$email_escaped')";

                if (!$con->query($sql_client)) {
                    throw new Exception("Erreur lors de la création du client : " . $con->error);
                }

                // Créer la commande
                $id_commande = generateUniqueId($con, 'commande', 'id_commande');
                $date_commande = trim($_POST['date_commande']);
                $total_commande = floatval($_POST['total_commande']);

                if (empty($date_commande)) {
                    throw new Exception("La date de commande est obligatoire.");
                }

                $sql_commande = "INSERT INTO commande (id_commande, id_client, date_commande, montant) VALUES ('$id_commande', '$id_client', '$date_commande', $total_commande)";

                if (!$con->query($sql_commande)) {
                    throw new Exception("Erreur lors de la création de la commande : " . $con->error);
                }

                // Ajouter les articles à la table contenir
                $articles = $_POST['articles'] ?? [];
                $quantites = $_POST['quantites'] ?? [];
                $prix_unitaires = $_POST['prix_unitaires'] ?? [];

                if (empty($articles) || count($articles) === 0) {
                    throw new Exception("Vous devez ajouter au moins un article.");
                }

                foreach ($articles as $index => $code_article) {
                    if (empty($code_article)) {
                        continue;
                    }

                    $quantite = intval($quantites[$index] ?? 0);
                    $prix_unitaire = floatval($prix_unitaires[$index] ?? 0);

                    if ($quantite <= 0 || $prix_unitaire <= 0) {
                        throw new Exception("Les quantités et prix doivent être supérieurs à 0.");
                    }

                    $code_escaped = $con->real_escape_string($code_article);
                    $sql_contenir = "INSERT INTO contenir (id_commande, code_article, qte_comm) VALUES ('$id_commande', '$code_escaped', $quantite)";

                    if (!$con->query($sql_contenir)) {
                        throw new Exception("Erreur lors de l'ajout de l'article : " . $con->error);
                    }
                }

                // Succès
                echo '<div class="success-box">';
                echo '<h2>✓ Vente enregistrée avec succès</h2>';
                echo '<p>La commande n°<strong>' . $id_commande . '</strong> a été créée.</p>';
                echo '<p>Client : <strong>' . htmlspecialchars($nom_client . ' ' . $prenom_client) . '</strong></p>';
                echo '<p>Total : <strong>' . number_format($total_commande, 2, ',', ' ') . ' €</strong></p>';
                echo '<div class="links">';
                echo '<a href="effectuerVente.php" class="btn-link">Nouvelle vente</a>';
                echo '<a href="acceuil.php" class="btn-link secondary">Retour</a>';
                echo '</div>';
                echo '</div>';

            } catch (Exception $e) {
                echo '<div class="error-box">';
                echo '<h2>✗ Erreur</h2>';
                echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
                echo '<a href="effectuerVente.php" class="btn-link">Retourner au formulaire</a>';
                echo '</div>';
            }

            $con->close();
        } else {
            header('Location: effectuerVente.php');
            exit;
        }
        ?>
    </div>
</body>

</html>
