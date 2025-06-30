<?php require 'includes/db.php'; ?>
<?php include 'includes/header.php'; ?>

<h1>Gérer les genres</h1>

<section class="genres">
    <a href="ajouter-genres.php">➕ Ajouter un genre</a>
    <ul>
        <?php
        $sql = "SELECT g.id, g.nom FROM genres g ORDER BY g.nom ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $genres = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($genres as $genre) { ?>
            <li><?= $genre['nom'] ?>
                <a href="modifier-genres.php?id=<?= $genre['id'] ?>">Modifier</a>
                <a href="supprimer-genres.php?id=<?= $genre['id'] ?>">Supprimer</a>
            </li>

        <?php } ?>


    </ul>
</section>





<?php include 'includes/footer.php'; ?>