<?php
session_start();
include('../connect.php');

// Ensure user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Fetch only active products
$sql = "SELECT * FROM products WHERE status = 'active'";
$result = $conn->query($sql);
$products = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Handle Add to Cart form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $user_id = $_SESSION['user_id']; // Get user ID from session
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Check if enough quantity is available
    $checkQuantitySql = "SELECT quantity FROM products WHERE id = '$product_id' AND status = 'active'";
    $quantityResult = $conn->query($checkQuantitySql);
    $productData = $quantityResult->fetch_assoc();

    if ($productData && $productData['quantity'] >= $quantity) {
        // Insert into cart
        $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$quantity')";
        if ($conn->query($sql) === TRUE) {
            // Update the product quantity
            $newQuantity = $productData['quantity'] - $quantity;
            $updateSql = "UPDATE products SET quantity = '$newQuantity' WHERE id = '$product_id'";
            $conn->query($updateSql);
            echo "<script>alert('Product added to cart!');</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Error: Not enough quantity available!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechNook</title>
    <link rel="stylesheet" href="../../css/shop.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="navdiv">
            <div class="logo">
                <a href="home.html"><img src="../../img/logo.png" alt="Logo" class="logo-img"></a> 
            </div>
            <ul class="nav-links">
                <li>
                    <a class="link" href="home.html">Home</a>
                    <a class="link" href="shop.php">Shop</a>
                    <a class="link" href="about.html">About Us</a>
                    <a href="cart.php"><i class="bi bi-cart"></i></a>
                </li>
            </ul>
            <div class="nav-buttons">
            <a href="user-profile.php" class="user">
                        <i class="bi bi-person"></i> <!-- Icon only for User -->
                    </a>
                <a href="../logout.php" class="btn">Log out</a>
            </div>
        </div>
    </nav>

    <!-- Dynamically showing products based on categories -->
    <h1 id="computer">Laptop and PCs</h1>
    <div class="shop" id="shop">
        <?php
        foreach ($products as $product) {
            if ($product['category'] == 'Computer') {
                echo "<div class='card'>";
                echo "<img src='../admin/uploads/" . $product['image'] . "' alt='" . $product['name'] . "'>";
                echo "<h2>" . $product['name'] . "</h2>";
                echo "<p>" . $product['description'] . "</p>";
                echo "<p>Price: $" . $product['price'] . "</p>";
                echo "<p>Quantity Left: " . $product['quantity'] . "</p>"; // Show available quantity
                echo "<p>Status: " . $product['status'] . "</p>"; // Show product status

                // Add to Cart form
                echo "<form method='post' action=''>";
                echo "<input type='hidden' name='product_id' value='" . $product['id'] . "'>";
                echo "<label for='quantity'>Quantity: </label>";
                echo "<input type='number' name='quantity' value='1' min='1' max='" . $product['quantity'] . "' required>"; // Limit max to available quantity
                echo "<button type='submit' name='add_to_cart'>Add to Cart</button>";
                echo "</form>";

                echo "</div>";
            }
        }
        ?>
    </div>

    <!-- Miscellaneous category -->
    <h1 id="keyboard">Miscellaneous</h1>
    <div class="shop" id="key">
        <?php
        foreach ($products as $product) {
            if ($product['category'] == 'Miscellaneous') {
                echo "<div class='card'>";
                echo "<img src='../admin/uploads/" . $product['image'] . "' alt='" . $product['name'] . "'>";
                echo "<h2>" . $product['name'] . "</h2>";
                echo "<p>" . $product['description'] . "</p>";
                echo "<p>Price: $" . $product['price'] . "</p>";
                echo "<p>Quantity Left: " . $product['quantity'] . "</p>"; // Show available quantity
                echo "<p>Status: " . $product['status'] . "</p>"; // Show product status

                // Add to Cart form
                echo "<form method='post' action=''>";
                echo "<input type='hidden' name='product_id' value='" . $product['id'] . "'>";
                echo "<label for='quantity'>Quantity: </label>";
                echo "<input type='number' name='quantity' value='1' min='1' max='" . $product['quantity'] . "' required>"; // Limit max to available quantity
                echo "<button type='submit' name='add_to_cart'>Add to Cart</button>";
                echo "</form>";

                echo "</div>";
            }
        }
        ?>
    </div>

    <!-- Drives and Parts category -->
    <h1 id="drive">Drives and Parts</h1>
    <div class="shop" id="con">
        <?php
        foreach ($products as $product) {
            if ($product['category'] == 'Drives and Parts') {
                echo "<div class='card'>";
                echo "<img src='../admin/uploads/" . $product['image'] . "' alt='" . $product['name'] . "'>";
                echo "<h2>" . $product['name'] . "</h2>";
                echo "<p>" . $product['description'] . "</p>";
                echo "<p>Price: $" . $product['price'] . "</p>";
                echo "<p>Quantity Left: " . $product['quantity'] . "</p>"; // Show available quantity
                echo "<p>Status: " . $product['status'] . "</p>"; // Show product status

                // Add to Cart form
                echo "<form method='post' action=''>";
                echo "<input type='hidden' name='product_id' value='" . $product['id'] . "'>";
                echo "<label for='quantity'>Quantity: </label>";
                echo "<input type='number' name='quantity' value='1' min='1' max='" . $product['quantity'] . "' required>"; // Limit max to available quantity
                echo "<button type='submit' name='add_to_cart'>Add to Cart</button>";
                echo "</form>";

                echo "</div>";
            }
        }
        ?>
    </div>

</body>
</html>
