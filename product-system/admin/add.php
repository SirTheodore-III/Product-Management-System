<?php
require "../db_connect.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ---- Validate input ----
    $name = trim($_POST['name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($name === '' || $price === '' || !is_numeric($price)) {
        $error = "Please enter a valid name and price.";
    } else {
        // ---- Insert product ----
        $stmt = $conn->prepare("INSERT INTO products (name, price, description) VALUES (?, ?, ?)");
        $stmt->bind_param("sds", $name, $price, $description);
        $stmt->execute();
        $product_id = $conn->insert_id;
        $stmt->close();

        // ---- Handle image upload (optional) ----
        if (!empty($_FILES['image']['name'])) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            if (in_array($ext, $allowed)) {
                $newFileName = "product_" . $product_id . "_" . time() . "." . $ext;
                $destination = "../uploads/" . $newFileName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                    $stmt2 = $conn->prepare("INSERT INTO product_images (product_id, image_path) VALUES (?, ?)");
                    $stmt2->bind_param("is", $product_id, $newFileName);
                    $stmt2->execute();
                    $stmt2->close();
                }
            }
        }

        header("Location: index.php?msg=Product added successfully");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Product</title>
<link rel="stylesheet" href="../style.css">
</head>
<body>

<header>
    <h1>Add Product</h1>
    <a href="index.php">Back to Dashboard</a>
</header>

<div class="container">
    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form class="card" method="POST" enctype="multipart/form-data">
        <label>Product Name</label>
        <input type="text" name="name" required>

        <label>Price</label>
        <input type="number" step="0.01" name="price" required>

        <label>Description</label>
        <textarea name="description" rows="4"></textarea>

        <label>Product Image</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit">Save Product</button>
    </form>
</div>

</body>
</html>
