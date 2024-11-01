<?php
session_start();
include('connect.php');

// Redirect to login page if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../../index.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch current user information
$result = $conn->query("SELECT firstname, lastname, email FROM users WHERE email = '$email'");
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare new data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $newEmail = $user['email']; // Retain the current email
    $password = $_POST['password'];

    // Validate first name and last name length (max 15 characters)
    if (strlen($firstname) > 15) {
        echo "<script>alert('First Name must be 15 characters or less.');</script>";
    } elseif (strlen($lastname) > 15) {
        echo "<script>alert('Last Name must be 15 characters or less.');</script>";
    } elseif ( strlen($password) > 10) {
        // Validate password length (min 10 characters)
        echo "<script>alert('Password must not exceed 10 characters.');</script>";
    } else {
        // Hash the password if it's provided
        if (!empty($password)) {
            $hashedPassword = md5($password); // Using MD5 as requested
            // Update user information with new password
            $updateSql = "UPDATE users SET firstname = '$firstname', lastname = '$lastname', password = '$hashedPassword' WHERE email = '$email'";
        } else {
            // Update user information without changing the password
            $updateSql = "UPDATE users SET firstname = '$firstname', lastname = '$lastname' WHERE email = '$email'";
        }

        // Execute the update
        if ($conn->query($updateSql) === TRUE) {
            echo "<script>alert('Profile updated successfully!');</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/panel.css">
    <link rel="icon" type="image/jpg/png" href="../../../img/logo.png">
    <title>PC ZONE - Update Profile</title>
</head>
<body>
    <!-- NAVBAR -->
     <nav>
    <a href="../admin-panel.html" class="brand">
            <p>PC ZONE</p>
        </a>
        <ul class="nav-menu">
            <li><a href="../dashboard.php"><span class="text">Dashboard</span></a></li>
            <li><a href="../add-products.php"><span class="text">Add Products</span></a></li>
            <li><a href="../view-orders.php"><span class="text">View Orders</span></a></li>
            <li><a href="../accounts.php"><span class="text">Accounts</span></a></li>
            <li><a href="../logout.php" class="logout"><span class="text">Logout</span></a></li>
        </ul>
    </nav>
    
    <!-- CONTENT -->
    <section id="content">
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Update Profile</h1>
                </div>
            </div>
            <div class="wrapper" id="update-profile">
                <form method="post" action="update-profile.php">
                    <div class="input-box">
                        <p>First Name</p>
                        <input type="text" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" required>
                    </div>
                    <div class="input-box">
                        <p>Last Name</p>
                        <input type="text" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" required>
                    </div>
                    <div class="input-box">
                        <p>Email</p>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly required>
                    </div>
                    <div class="input-box">
                        <p>New Password (leave blank to keep current password)</p>
                        <input type="password" name="password">
                    </div>
                    <button type="submit" class="btn" name="update">Update Profile</button>
                </form>
            </div>
        </main>
    </section>
</body>
</html>
