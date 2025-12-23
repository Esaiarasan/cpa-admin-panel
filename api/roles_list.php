<?php
include '../config/database.php';
$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM roles WHERE deleted_at IS NULL ORDER BY r_id DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
