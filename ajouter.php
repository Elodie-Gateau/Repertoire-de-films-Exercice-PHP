<?php require 'includes/db.php'; ?>
<?php include 'includes/header.php'; ?>

<?php
$sqlGenre = "SELECT * FROM genres;";
$stmtGenre = $pdo->prepare($sqlGenre);
$stmtGenre->execute();
$genres = $stmtGenre->fetchAll(PDO::FETCH_ASSOC);


if (isset($_POST) && !empty($_POST)) {
    $errors = [];


    // Validation de l'année
    $year = filter_var($_POST['year'], FILTER_VALIDATE_INT);
    $checkYear = true;
    if ($year === false || $year < 1900 || $year > date('Y')) {
        $checkYear = false;
    }
    if (!$checkYear) {
        $errors[] = "L'année doit être un nombre entier.";
    }

    // Validation du titre
    $title = htmlspecialchars($_POST['title']);
    $checkTitle = true;

    if (empty($title)) {
        $checkTitle = false;
    }
    if (!$checkTitle) {
        $errors[] = "Le titre ne doit pas être vide.";
    }

    // Validation du genre
    $checkGenre = true;
    $sqlGenre = "SELECT COUNT(*) FROM genres WHERE id = :genre_id";
    $stmtGenre = $pdo->prepare($sqlGenre);
    $stmtGenre->execute([
        'genre_id' => $_POST['genre']
    ]);
    $countGenre = $stmtGenre->fetchColumn();
    if ($countGenre !== 1) {
        $checkGenre = false;
    }

    if (!$checkGenre) {
        $errors[] = "Le genre doit se trouver dans la liste proposée.";
    }

    if ($checkGenre && $checkTitle && $checkYear) {
        $sql = "INSERT INTO films 
    (titre, realisateur, annee, genre_id, resume)
    VALUES (
    :title,
    :director,
    :year,
    :genre,
    :summary
    );";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'title' => $_POST['title'],
            'director' => $_POST['director'],
            'year' => $year,
            'genre' => $_POST['genre'],
            'summary' => $_POST['summary']
        ]);

        header('Location: ./index.php');
        exit;
    } else {
        echo "<pre>" . print_r($errors) . "</pre>";
    }
}


?>
<h1>Ajouter un film</h1>
<div class="add-form">
    <form action="ajouter.php" method="POST">
        <div class="form__questions">
            <div class="form__item">
                <label for="title">Titre : </label>
                <input type="text" name="title" id="title">
            </div>

            <div class="form__item">
                <label for="director">Réalisateur : </label>
                <input type="text" name="director" id="director">
            </div>

            <div class="form__item">
                <label for="year">Année : </label>
                <input type="number" name="year" id="year">
            </div>
            <div class="form__item">
                <label for="genre">Genre : </label>
                <select name="genre" id="genre">
                    <option value="none"></option>
                    <?php foreach ($genres as $genre) { ?>
                        <option value="<?= $genre['id'] ?>"><?= $genre['nom'] ?></option>

                    <?php } ?>


                </select>
            </div>
            <div class="form__item"><label for="summary">Résumé : </label>
                <textarea name="summary" id="summary"></textarea>
            </div>
        </div>
        <input type="submit" value="Ajouter">

    </form>

</div>
<?php include 'includes/footer.php'; ?>