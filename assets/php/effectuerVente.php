<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Effectuer une vente</title>
    <link rel="stylesheet" href="../styles/effectuerVente.css" />
</head>

<body>
    <div class="container">
        <h1>Effectuer une vente</h1>

        <form action="traiterVente.php" method="post" class="form-vente">
            <!-- Section Client -->
            <fieldset class="section-client">
                <legend>Informations du client</legend>


                <div class="form-group">
                    <label for="nom_client">Nom</label>
                    <input type="text" id="nom_client" name="nom_client" required />
                </div>

                <div class="form-group">
                    <label for="prenom_client">Prénom</label>
                    <input type="text" id="prenom_client" name="prenom_client" required />
                </div>

                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" required />
                </div>


                <div class="form-group">
                    <label for="adresse_client">Adresse</label>
                    <input type="text" id="adresse_client" name="adresse_client" required />
                </div>


                <div class="form-group">
                    <label for="ville_client">Ville</label>
                    <input type="text" id="ville_client" name="ville_client" required />
                </div>

                <div class="form-group">
                    <label for="email_client">Email</label>
                    <input type="email" id="email_client" name="email_client" required />
                </div>

            </fieldset>

            <!-- Section Commande et Articles -->
            <fieldset class="section-commande-articles">
                <legend>Commande et articles</legend>

                <div class="form-group">
                    <label for="date_commande">Date de commande</label>
                    <input type="date" id="date_commande" name="date_commande" required />
                </div>

                <table class="articles-table">
                    <thead>
                        <tr>
                            <th>Article</th>
                            <th>Quantité</th>
                            <th>Prix unitaire</th>
                            <th>Sous-total</th>

                        </tr>
                    </thead>
                    <tbody id="articles-body">
                        <tr class="article-row">
                            <td>
                                <select name="articles[]" class="article-select" required>
                                    <option value="">-- Sélectionner un article --</option>
                                    <?php
                                    include_once("connexion.php");
                                    $con = connexion('essaiebdd');
                                    $sql = "SELECT code_article, design, prix FROM article ORDER BY design";
                                    $result = $con->query($sql);
                                    if ($result && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<option value="' . htmlspecialchars($row['code_article']) . '" data-prix="'
                                                . htmlspecialchars($row['prix']) . '">'
                                                . htmlspecialchars($row['design'] . ' (' . $row['code_article'] . ')')
                                                . '</option>';
                                        }
                                    }
                                    $con->close();
                                    ?>
                                </select>
                            </td>
                            <td>
                                <input type="number" name="quantites[]" class="quantite-input" min="1" value="1" required />
                            </td>
                            <td>
                                <input type="number" name="prix_unitaires[]" class="prix-input" min="0" step="0.01" readonly />
                            </td>
                            <td>
                                <input type="number" name="sous_totals[]" class="sous-total-input" readonly />
                            </td>
                            
                        </tr>
                    </tbody>
                </table>

            </fieldset>

            <!-- Section Total -->
            <div class="section-total">
                <div class="total-line">
                    <span>Total :</span>
                    <input type="number" id="total_commande" name="total_commande" readonly value="0.00" />
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="form-actions">
                <input type="submit" value="Enregistrer la vente" class="btn-submit" />
                <a href="acceuil.php" class="btn-cancel">Annuler</a>
            </div>
        </form>
    </div>

    <script>

        function ajouterEvenementsArticle(row) {
            const select = row.querySelector('.article-select');
            const quantiteInput = row.querySelector('.quantite-input');

            select.addEventListener('change', function() {
                const prixUnitaire = this.options[this.selectedIndex].dataset.prix || 0;
                row.querySelector('.prix-input').value = parseFloat(prixUnitaire).toFixed(2);
                calculerSousTotal(row);
            });

            quantiteInput.addEventListener('input', function() {
                calculerSousTotal(row);
            });
        }

        function calculerSousTotal(row) {
            const quantite = parseFloat(row.querySelector('.quantite-input').value) || 0;
            const prixUnitaire = parseFloat(row.querySelector('.prix-input').value) || 0;
            const sousTotal = (quantite * prixUnitaire).toFixed(2);

            row.querySelector('.sous-total-input').value = sousTotal;
            calculerTotal();
        }

        function calculerTotal() {
            let total = 0;
            document.querySelectorAll('.sous-total-input').forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            document.getElementById('total_commande').value = total.toFixed(2);
        }

        // Ajouter les événements aux articles existants
        document.querySelectorAll('.article-row').forEach(row => {
            ajouterEvenementsArticle(row);
        });

        // Définir la date d'aujourd'hui par défaut
        document.getElementById('date_commande').valueAsDate = new Date();
    </script>
</body>

</html>