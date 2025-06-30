<?php require 'includes/db.php'; ?>
<?php include 'includes/header.php'; ?>
<?php


if (isset($_POST['update'])) {
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
        $sql = "UPDATE films 
    SET titre = :title,
        realisateur = :director,
        annee = :year,
        genre_id =  :genre,
        resume = :summary
    WHERE id = :id    
        ;";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'title' => $_POST['title'],
            'director' => $_POST['director'],
            'year' => $_POST['year'],
            'genre' => $_POST['genre'],
            'summary' => $_POST['summary'],
            'id' => $_POST['id']
        ]);
        header("Location: ./index.php");
        exit;
    } else {
        echo "<pre>" . print_r($errors) . "</pre>";
    }
}

if (isset($_GET['id'])) {
    $id = htmlspecialchars($_GET['id']);
    $sqlId = "SELECT COUNT(*) FROM films WHERE id= :id;";
    $stmtId = $pdo->prepare($sqlId);
    $stmtId->execute([
        'id' => $id
    ]);
    $nbreId = $stmtId->fetchColumn();
    $checkID = true;
    if ($nbreId !== 1) {
        $checkID = false;
        $errors[] = "ID invalide";
    }


    if ($checkID) {
        $sql = "SELECT * FROM films WHERE films.id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
        $currentFilm = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($currentFilm['genre_id']) {
            $sqlGenre = "SELECT g.nom FROM genres g LEFT JOIN films f ON g.id = f.genre_id WHERE f.id = :id";
            $stmtGenre = $pdo->prepare($sqlGenre);
            $stmtGenre->execute([
                'id' => $id
            ]);
            $currentGenre = $stmtGenre->fetch(PDO::FETCH_ASSOC);
            $currentFilm['genre'] = $currentGenre['nom'];
        } else {
            $currentFilm['genre'] = "À définir";
        }
        $sqlGenreSelect = "SELECT * FROM genres";
        $stmtGenreSelect = $pdo->prepare($sqlGenreSelect);
        $stmtGenreSelect->execute();
        $genres = $stmtGenreSelect->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "<pre>" . print_r($errors) . "</pre>";
    }
}


?>

<form action="modifier.php?id=<?= $currentFilm['id']; ?>" method="POST">
    <input type="hidden" name="id" value="<?= $currentFilm['id']; ?>">
    <label for="title">Titre :</label>
    <input type="text" name="title" id="title" value="<?= htmlspecialchars($currentFilm['titre']); ?>">

    <label for="director">Réalisateur :</label>
    <input type="text" name="director" id="director" value="<?= htmlspecialchars($currentFilm['realisateur']); ?>">

    <label for="year">Année</label>
    <input type="number" name="year" id="year" value="<?= htmlspecialchars($currentFilm['annee']); ?>">

    <select name="genre" id="genre">
        <option value="<?= htmlspecialchars($currentFilm['genre_id']); ?>"><?= htmlspecialchars($currentFilm['genre']); ?></option>
        <option value="none">Sélectionner un genre</option>
        <?php foreach ($genres as $genre) { ?>
            <option value="<?= $genre['id'] ?>"><?= $genre['nom'] ?></option>
        <?php } ?>

    </select>

    <label for="summary">Résumé : </label>
    <textarea name="summary" id="summary"><?= htmlspecialchars($currentFilm['resume']); ?></textarea>

    <input type="submit" name="update" value="Modifier">

</form>

<?php include 'includes/footer.php'; ?>