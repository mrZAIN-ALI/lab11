<?php
// Include session management file
include_once "session.php";

// Include database connection
include_once "db.php";

// Check if user is not logged in, redirect to login page
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Check if product ID is provided
if (!isset($_GET['id'])) {
    header("Location: view_product.php");
    exit;
}

$product_id = $_GET['id'];

// Fetch product details
$query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];

    // Handle image upload if a new image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image']['name'];
        $target = "uploads/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    } else {
        $image = $product['image'];
    }

    // Update product details
    $query = "UPDATE products SET name = ?, price = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $name, $price, $image, $product_id);
    $stmt->execute();

    // Redirect to view product page
    header("Location: view_product.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product - Computer Store</title>
    <style>
        /* Add your existing styles here */
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-brand">Computer Store</div>
        <div class="navbar-links">
            <a href="add_product.php">Add Product</a>
            <a href="view_product.php">View Product</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <!-- Content -->
    <div class="container">
        <h2>Update Product</h2>
        <form id="updateProductForm" action="update_product.php?id=<?php echo $product_id; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>
            <div class="form-group">
                <label for="image">Pick Image:</label>
                <input type="file" id="image" name="image" accept="image/*">
                <p>Current Image: <?php echo htmlspecialchars($product['image']); ?></p>
            </div>
            <div class="form-group">
                <button type="submit">Update Product</button>
            </div>
        </form>
    </div>
</body>
</html>
