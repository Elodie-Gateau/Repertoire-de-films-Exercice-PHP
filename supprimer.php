<?php require 'includes/db.php'; ?>
<?php include 'includes/header.php'; ?>
<?php
if (isset($_GET['id'])) {
    $id = htmlspecialchars($_GET['id']);
    $sql = "DELETE FROM films WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id' => $id
    ]);
    header("Location: ./index.php?valid=delete");
    exit;
}
?>

<?php include 'includes/footer.php'; ?>