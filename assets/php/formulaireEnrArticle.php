<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Formulaire d'enregistrement des articles</title>
    <link rel="stylesheet" href="../styles/formulaireEnrArticle.css" />
</head>
<body>
    <div class="container">
        <h2>Enregistrer un article</h2>

        <form action="enregistrement.php" method="post" class="form-section">
            <label for="code">Code article</label>
            <input type="text" id="code" name="art[]" required />

            <label for="designation">Désignation</label>
            <input type="text" id="designation" name="art[]" required />

            <label for="prix">Prix</label>
            <input type="number" id="prix" name="art[]" min="0" step="0.01" required />

            <label for="categorie">Catégorie</label>
            <input type="text" id="categorie" name="art[]" required />

            <div class="links">
                <input type="submit" name="enregistrer" value="Enregistrer" />
                <a class="button-link" href="afficherArticle.php">Voir les articles</a>
            </div>
        </form>
    </div>
</body>
</html>