<?php
require "../db_connect.php";

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

// Delete the image file from disk if one exists
$stmt = $conn->prepare("SELECT image_path FROM product_images WHERE product_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $file = "../uploads/" . $row['image_path'];
    if (file_exists($file)) unlink($file);
}
$stmt->close();

// product_images row is auto-deleted by ON DELETE CASCADE when the product is deleted
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: index.php?msg=Product deleted successfully");
exit;
?>
