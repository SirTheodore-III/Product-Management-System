<?php
require "db_connect.php";

// Join products with their image (LEFT JOIN so products without an image still show)
$sql = "SELECT p.id, p.name, p.price, p.description, i.image_path
        FROM products p
        LEFT JOIN product_images i ON p.id = i.product_id
        ORDER BY p.created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Product Catalog</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>Our Products</h1>
    <a href="admin/index.php">Admin Dashboard</a>
</header>

<div class="container">
    <div class="product-grid">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <?php if (!empty($row['image_path'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                    <?php else: ?>
                        <img src="https://placehold.co/300x180?text=No+Image" alt="No image">
                    <?php endif; ?>
                    <div class="info">
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <div class="price">Rs. <?php echo number_format($row['price'], 2); ?></div>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products yet. Add some from the admin dashboard.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
