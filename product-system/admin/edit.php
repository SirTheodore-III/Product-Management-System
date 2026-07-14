<?php
require "../db_connect.php";

$id = $_GET['id'] ?? $_POST['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($name === '' || $price === '' || !is_numeric($price)) {
        $error = "Please enter a valid name and price.";
    } else {
        $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?");
        $stmt->bind_param("sdsi", $name, $price, $description, $id);
        $stmt->execute();
        $stmt->close();

        // Replace image only if a new one was uploaded
        if (!empty($_FILES['image']['name'])) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            if (in_array($ext, $allowed)) {
                $newFileName = "product_" . $id . "_" . time() . "." . $ext;
                $destination = "../uploads/" . $newFileName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                    // Remove old image row (and optionally old file) then insert new one
                    $old = $conn->prepare("SELECT image_path FROM product_images WHERE product_id = ?");
                    $old->bind_param("i", $id);
                    $old->execute();
                    $oldResult = $old->get_result();
                    if ($oldRow = $oldResult->fetch_assoc()) {
                        $oldFile = "../uploads/" . $oldRow['image_path'];
                        if (file_exists($oldFile)) unlink($oldFile);
                    }
                    $old->close();

                    $conn->query("DELETE FROM product_images WHERE product_id = $id");

                    $stmt2 = $conn->prepare("INSERT INTO product_images (product_id, image_path) VALUES (?, ?)");
                    $stmt2->bind_param("is", $id, $newFileName);
                    $stmt2->execute();
                    $stmt2->close();
                }
            }
        }

        header("Location: index.php?msg=Product updated successfully");
        exit;
    }
}

// Load current product data to pre-fill the form
$stmt = $conn->prepare("SELECT p.*, i.image_path FROM products p LEFT JOIN product_images i ON p.id = i.product_id WHERE p.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Product</title>
<link rel="stylesheet" href="../style.css">
</head>
<body>

<header>
    <h1>Edit Product</h1>
    <a href="index.php">Back to Dashboard</a>
</header>

<div class="container">
    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php if (!empty($product['image_path'])): ?>
        <img class="thumb" src="../uploads/<?php echo htmlspecialchars($product['image_path']); ?>" style="width:100px;height:100px;margin-bottom:10px;">
    <?php endif; ?>

    <form class="card" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

        <label>Product Name</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

        <label>Price</label>
        <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>

        <label>Description</label>
        <textarea name="description" rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>

        <label>Replace Image (optional)</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit">Update Product</button>
    </form>
</div>

</body>
</html>
