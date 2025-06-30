<?php require 'includes/db.php'; ?>
<?php include 'includes/header.php'; ?>
<?php
if (isset($_GET['id'])) {
    $id = (int) htmlspecialchars($_GET['id']);
    $sql = "SELECT f.titre, f.realisateur, f.annee, f.resume, g.nom AS 'genre'
    FROM films f
    LEFT JOIN genres g ON g.id = f.genre_id
    WHERE f.id = :id;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $currentFilm = $stmt->fetch(PDO::FETCH_ASSOC);
}


?>
<div class="current-film">
    <h1 class="current-film__title"><?= $currentFilm['titre'] ?></h1>
    <h2 class="current-film__director"><?= $currentFilm['realisateur'] ?></h2>
    <div class="current-film__info">
        <p class="current-film__year"><?= $currentFilm['annee'] ?></p>
        <p class="current-film__genre"><?= $currentFilm['genre'] ?></p>
    </div>
    <p class="current-film__summary"><?= $currentFilm['resume'] ?></p>

</div>


<?php include 'includes/footer.php'; ?>