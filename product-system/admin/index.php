<?php
require "../db_connect.php";

$sql = "SELECT p.id, p.name, p.price, i.image_path
        FROM products p
        LEFT JOIN product_images i ON p.id = i.product_id
        ORDER BY p.created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="../style.css">
</head>
<body>

<header>
    <h1>Admin Dashboard</h1>
    <a href="add.php">+ Add Product</a>
    <a href="../index.php">View Public Catalog</a>
</header>

<div class="container">
    <?php if (isset($_GET['msg'])): ?>
        <p class="success"><?php echo htmlspecialchars($_GET['msg']); ?></p>
    <?php endif; ?>

    <table>
        <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?php if (!empty($row['image_path'])): ?>
                            <img class="thumb" src="../uploads/<?php echo htmlspecialchars($row['image_path']); ?>">
                        <?php else: ?>
                            &mdash;
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td>Rs. <?php echo number_format($row['price'], 2); ?></td>
                    <td class="actions">
                        <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this product?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">No products yet.</td></tr>
        <?php endif; ?>
    </table>
</div>

</body>
</html>
