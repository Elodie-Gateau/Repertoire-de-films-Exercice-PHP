<?php require 'includes/db.php'; ?>
<?php include 'includes/header.php'; ?>

<!-- Pagination -->
<?php
if (isset($_GET['page']) && ctype_digit($_GET['page'])) {
    $page = (int) $_GET['page'];
} else {
    $page = 1;
}

$offset = ($page - 1) * 5;
?>

<?php
$valid = "";
if (isset($_GET['valid'])) {
    $valid = htmlspecialchars($_GET['valid']);
    $messageAdd = "Le film est ajouté à la liste.";
    $messageUpdate = "Le film a été modifié.";
    $messageDelete = "Le film a été supprimé.";
}

?>


<h1>Répertoire de Films</h1>

<div class="add">
    <a href="ajouter.php">✚ Ajouter un film</a>
</div>
<!-- Barre de recherche -->
<div class="search-film">
    <form class="search-film__form" action="index.php" method="GET">
        <input type="text" name="search" id="" placeholder="Saisir un film ou un réalisateur">
        <input type="submit" value="Rechercher">
        <a href="index.php">Réinitialiser</a>
    </form>
</div>
<div class="table-film">
    <table>
        <tr>
            <th><span>Titre</span><span><a href="index.php?tri=titre&ordre=ASC">↑</a><a href="index.php?tri=titre&ordre=DESC">↓</a></span></th>
            <th><span>Réalisateur</span><span><a href="index.php?tri=realisateur&ordre=ASC">↑</a><a href="index.php?tri=realisateur&ordre=DESC">↓</a></span></th>
            </th>
            <th><span>Année</span><span><a href="index.php?tri=annee&ordre=ASC">↑</a><a href="index.php?tri=annee&ordre=DESC">↓</a></span></th>
            </th>
            <th>Genre</th>
            <th>Actions</th>
        </tr>

        <?php
        if (isset($_GET['search'])) {
            $search = '%' . htmlspecialchars($_GET['search']) . '%';
            $sql = "SELECT f.id, f.titre, f.realisateur, f.annee, g.nom AS 'genre' FROM films f INNER JOIN genres g ON f.genre_id = g.id WHERE titre LIKE :search OR realisateur LIKE :search ORDER BY annee DESC LIMIT 5 OFFSET " . (int)$offset;
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'search' => $search
            ]);
            $films = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else if (isset($_GET['tri']) && isset($_GET['ordre'])) {
            $allowedSort = ['titre', 'annee', 'realisateur'];
            $allowedDirection = ['ASC', 'DESC'];
            if (in_array($_GET['tri'], $allowedSort)) {
                $sort = htmlspecialchars($_GET['tri']);
            } else {
                $sort = 'annee';
            }
            if (in_array($_GET['ordre'], $allowedDirection)) {
                $direction = htmlspecialchars($_GET['ordre']);
            } else {
                $direction = 'DESC';
            }

            $sql = "SELECT f.id, f.titre, f.realisateur, f.annee, g.nom AS 'genre' FROM films f LEFT JOIN genres g ON f.genre_id = g.id ORDER BY $sort $direction LIMIT 5 OFFSET " . (int)$offset;
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $films = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {

            $sql = "SELECT f.id, f.titre, f.realisateur, f.annee, g.nom AS 'genre' FROM films f LEFT JOIN genres g ON f.genre_id = g.id ORDER BY annee DESC LIMIT 5 OFFSET " . (int)$offset;
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $films = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        foreach ($films as $film):
        ?>
            <tr>
                <td><?= htmlspecialchars($film['titre']) ?></td>
                <td><?= htmlspecialchars($film['realisateur']) ?></td>
                <td><?= $film['annee'] ?></td>
                <td><?php if ($film['genre']) {
                        echo htmlspecialchars($film['genre']);
                    } else {
                        echo "À définir";
                    } ?></td>
                <td>
                    <a class="read-more" href="film.php?id=<?= $film['id'] ?>">💡 En savoir plus...</a>
                    <a href="modifier.php?id=<?= $film['id'] ?>">Modifier</a>
                    <a class="delete" href="supprimer.php?id=<?= $film['id'] ?>" onclick="return confirm('Supprimer ce film ?')">❌</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="pages">
    <?php
    $sql = "SELECT COUNT(id) AS 'nbreFilms' FROM films;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $nbreFilms = $stmt->fetch(PDO::FETCH_ASSOC);
    $nbrePage = ceil($nbreFilms['nbreFilms'] / 5);


    for ($i = 1; $i <= $nbrePage; $i++) { ?>
        <a href="index.php?page=<?= $i ?>">Page <?= $i ?></a>

    <?php }

    ?>

</div>
<div class="confirm">
    <?php if ($valid === "add") {
        echo $messageAdd;
    } else if ($valid === "update") {
        echo $messageUpdate;
    } else if ($valid === "delete") {
        echo $messageDelete;
    } ?></div>

<?php include 'includes/footer.php'; ?>