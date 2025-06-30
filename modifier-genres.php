<?php require 'includes/db.php'; ?>
<?php include 'includes/header.php'; ?>

<?php
$genre = ['id' => '', 'nom' => ''];

if (isset($_GET['id'])) {
    $id = htmlspecialchars($_GET['id']);
    $sqlId = "SELECT COUNT(*) FROM genres WHERE id= :id;";
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

        $sql = "SELECT g.id, g.nom FROM genres g WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
        $genre = $stmt->fetch();
    } else {
        echo "<pre>" . print_r($errors) . "</pre>";
    }
}


if (isset($_POST['name'])) {
    if (isset($_POST['idUpdate'])) {
        $idUpdate = filter_var($_POST['idUpdate'], FILTER_VALIDATE_INT);
    } else {
        $idUpdate = null;
    }
    $name = htmlspecialchars($_POST['name']);
    $checkName = true;
    if (strlen($name) > 200 || empty($name)) {
        $checkName = false;
        $errors[] = "Le nom du genre n'est pas correcte.";
    }

    if ($checkName) {
        $sqlUpdate = "UPDATE genres SET nom = :name WHERE id = :id;";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([
            'name' => $name,
            'id' => $idUpdate
        ]);
        header('Location: genres.php');
        exit;
    } else {
        echo "<pre>" . print_r($errors) . "</pre>";
    }
}
?>

<form action="modifier-genres.php" method="POST">
    <input type="hidden" name="idUpdate" value="<?= $genre['id'] ?>">
    <label for="name">Nom du genre</label>
    <input type="text" name="name" id="" value="<?= $genre['nom'] ?>">
    <input type="submit" value="Modifier"></input>
</form>

<?php include 'includes/footer.php'; ?>