<?php
session_start();
include('../connect.php');

// Ensure user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Fetch cart items
$sql = "SELECT c.*, p.name, p.price, p.image FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = '$user_id'";
$result = $conn->query($sql);
$cartItems = [];
$totalAmount = 0; // Initialize total amount

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
        $totalAmount += $row['price'] * $row['quantity']; // Calculate total amount
    }
}

// Handle Remove from Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_from_cart'])) {
    $cart_id = $_POST['cart_id'];

    // Remove item from cart
    $sql = "DELETE FROM cart WHERE id = '$cart_id' AND user_id = '$user_id'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Item removed from cart!');</script>";
        // Refresh the page
        header("Location: cart.php");
        exit();
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC Zone</title>
    <link rel="stylesheet" href="../../css/cart.css"> <!-- Add your CSS file for styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/jpg/png" href="../../../img/logo.png">
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
                    <a href="update-profile.php"><i class="bi bi-user"></i></a>
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

    <h1>Your Cart</h1>
    <div class="cart-container">
        <?php if (!empty($cartItems)): ?>
            <table>
                <tr>
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($cartItems as $item): ?>
                    <?php $subtotal = $item['price'] * $item['quantity']; // Calculate subtotal for each item ?>
                    <tr>
                        <td><img src="../admin/uploads/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" width="100"></td>
                        <td><?php echo $item['name']; ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" name="remove_from_cart">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <div class="total-amount">
                <h3>Grand Total: $<?php echo number_format($totalAmount, 2); ?></h3>
            </div>
        <?php else: ?>
            <p>Your cart is empty!</p>
        <?php endif; ?>
        
        <div class="payment">
            <a href="shipping.php">
                <button type="button">Proceed To Checkout</button>
            </a>
        </div>
    </div>
</body>
</html>
