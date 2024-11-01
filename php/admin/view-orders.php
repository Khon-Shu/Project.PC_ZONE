<?php
error_reporting(E_ALL); // Report all errors
ini_set('display_errors', 1); // Display errors on the page

session_start();
include('connect.php');

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php"); // Redirect to login if not logged in
    exit();
}

// Update order status if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $orderId = intval($_POST['order_id']);
    $newStatus = $_POST['status'];

    // Update the order status
    $updateSql = "UPDATE orders SET status = '$newStatus' WHERE id = $orderId";

    if ($conn->query($updateSql) === TRUE) {
        echo "<script>alert('Order status updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating order status: " . $conn->error . "');</script>";
    }
}

// Fetch all orders
$ordersSql = "SELECT o.id, o.quantity, o.status, o.order_date, p.name AS product_name, p.price
               FROM orders o
               JOIN products p ON o.product_id = p.id
               ORDER BY o.order_date DESC";

$ordersResult = $conn->query($ordersSql);

// Check if the query was successful
if (!$ordersResult) {
    die("Query failed: " . $conn->error); // Display query error
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg/png" href="../../img/logo.png">
    <link rel="stylesheet" href="../../css/panel.css"> <!-- Ensure the CSS file exists -->
    <title>PC ZONE - View Orders</title>
    <style>
        .order-list {
            display: flex;
            flex-wrap: wrap; /* Allow items to wrap to the next line */
            gap: 20px; /* Gap between orders */
        }
        .order-box {
            border: 1px solid #ccc; /* Border for each order box */
            border-radius: 5px; /* Rounded corners */
            padding: 15px; /* Padding inside order boxes */
            flex: 1 1 calc(30% - 20px); /* Responsive flex items (3 per row with gap) */
            box-shadow: 2px 2px 5px rgba(0,0,0,0.1); /* Subtle shadow */
        }
        .btn {
            padding: 10px 20px; /* Padding for buttons */
            margin-top: 10px; /* Margin above button */
            background-color: #4CAF50; /* Green background */
            color: white; /* White text */
            border: none; /* Remove border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor */
            transition: background-color 0.3s; /* Smooth transition */
        }
        .btn:hover {
            background-color: #45a049; /* Darker green on hover */
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav>
    <a href="admin-panel.html" class="brand">

            <p>PC ZONE</p>
        </a>
        <ul class="nav-menu">
            <li><a href="dashboard.php"><span class="text">Dashboard</span></a></li>
            <li><a href="add-products.php"><span class="text">Add Products</span></a></li>
            <li><a href="view-orders.php"><span class="text">View Orders</span></a></li>
            <li><a href="accounts.php"><span class="text">Accounts</span></a></li>
            <li><a href="logout.php" class="logout"><span class="text">Logout</span></a></li>
        </ul>
    </nav>
    <!-- NAVBAR -->

    <!-- CONTENT -->
    <section id="content">
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>View Orders</h1>
                </div>
            </div>

            <div class="order-list">
                <?php if ($ordersResult->num_rows > 0) { 
                    while ($order = $ordersResult->fetch_assoc()) {
                        // Calculate total price
                        $totalPrice = $order['quantity'] * $order['price']; ?>
                        <div class="order-box">
                            <h3>Order ID: <?= htmlspecialchars($order['id']); ?></h3>
                            <p><strong>Product Name:</strong> <?= htmlspecialchars($order['product_name']); ?></p>
                            <p><strong>Quantity:</strong> <?= htmlspecialchars($order['quantity']); ?></p>
                            <p><strong>Status:</strong> <?= htmlspecialchars($order['status']); ?></p>
                            <p><strong>Order Date:</strong> <?= htmlspecialchars($order['order_date']); ?></p>
                            <p><strong>Price per Item:</strong> $<?= htmlspecialchars(number_format($order['price'], 2)); ?></p>
                            <p><strong>Total Price:</strong> $<?= htmlspecialchars(number_format($totalPrice, 2)); ?></p> <!-- Display total price -->

                            <!-- Update Status Form -->
                            <form method="post" action="">
                                <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['id']); ?>">
                                <label for="status">Change Status:</label>
                                <select name="status" required>
                                    <option value="Pending" <?= ($order['status'] === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Completed" <?= ($order['status'] === 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                    <option value="Canceled" <?= ($order['status'] === 'Canceled') ? 'selected' : ''; ?>>Canceled</option>
                                </select>
                                <button type="submit" class="btn">Update Status</button>
                            </form>
                            <!-- End of Update Status Form -->
                        </div>
                <?php }
                } else { ?>
                    <p>No orders found.</p>
                <?php } ?>
            </div>
        </main>
    </section>
    <!-- CONTENT -->
</body>
</html>
