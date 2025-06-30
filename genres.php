<?php require 'includes/db.php'; ?>
<?php include 'includes/header.php'; ?>

<h1>Gérer les genres</h1>

<section class="genres">
    <div class="add">
        <a href="ajouter-genres.php">✚ Ajouter un genre</a>
    </div>
    <div class="genres-list">
        <table class='genres-tab'>
            <tr>
                <th>Genre</th>
                <th>Actions</th>
            </tr>
            <?php
            $sql = "SELECT g.id, g.nom FROM genres g ORDER BY g.nom ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $genres = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($genres as $genre) { ?>
                <tr>
                    <td><span class="genre-item"><?= $genre['nom'] ?></span></td>
                    <td>
                        <div class="genres-actions"><a class="genre-item" href="modifier-genres.php?id=<?= $genre['id'] ?>">Modifier</a>
                            <a class="genre-item delete" href="supprimer-genres.php?id=<?= $genre['id'] ?>">❌</a>
                        </div>
                    </td>
                </tr>

            <?php } ?>


        </table>
    </div>
</section>





<?php include 'includes/footer.php'; ?>