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


<h1>Répertoire de Films</h1>

<a href="ajouter.php">✚ Ajouter un film</a>

<!-- Barre de recherche -->
<form action="index.php" method="GET">
    <input type="text" name="search" id="">
    <input type="submit" value="Rechercher">
    <a href="index.php">Réinitialiser</a>
</form>

<table>
    <tr>
        <th>titre <a href="index.php?tri=titre&ordre=ASC">↑</a><a href="index.php?tri=titre&ordre=DESC">↓</a></th>
        <th>Réalisateur <a href="index.php?tri=realisateur&ordre=ASC">↑</a><a href="index.php?tri=realisateur&ordre=DESC">↓</a></th>
        </th>
        <th>Année <a href="index.php?tri=annee&ordre=ASC">↑</a><a href="index.php?tri=annee&ordre=DESC">↓</a></th>
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
            <td><a href="film.php?id=<?= $film['id'] ?>">En savoir plus</a></td>
            <td>
                <a href="modifier.php?id=<?= $film['id'] ?>">Modifier</a> |
                <a href="supprimer.php?id=<?= $film['id'] ?>" onclick="return confirm('Supprimer ce film ?')">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

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

<?php include 'includes/footer.php'; ?>