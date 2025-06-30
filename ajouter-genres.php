<?php require 'includes/db.php'; ?>
<?php include 'includes/header.php'; ?>
<?php
if (isset($_POST['name'])) {
    $checkName = true;
    $errors = [];
    $name = htmlspecialchars($_POST['name']);

    if (empty($name) || strlen($name) > 200) {
        $checkName = false;
        $errors[] = "Merci de saisir un nom de genre correct.";
    }

    if ($checkName) {
        $sql = "INSERT INTO genres (nom) 
        VALUES (:name);";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'name' => $name
        ]);

        header('Location: ./genres.php');
        exit;
    } else {
        echo "<pre>" . print_r($errors) . "</pre>";
    }
}


?>


<form action="ajouter-genres.php" method="POST">
    <label for="name">Nom du genre</label>
    <input type="text" name="name" id="">
    <input type="submit" value="Ajouter"></input>
</form>



<?php include 'includes/footer.php'; ?>