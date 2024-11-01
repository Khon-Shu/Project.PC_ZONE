<?php
session_start(); // Start the session

include '../connect.php'; // Assuming this file connects to the practicedb

if (isset($_POST['next'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $address = $_POST['address'];
    $province = $_POST['province'];
    $city = $_POST['city'];
    $phone = $_POST['phone'];

    // Retrieve user_id from the session
    if(isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; // This is the logged-in user's ID
        
        // Insert data into shipping_info table
        $query = "INSERT INTO shipping_info (user_id, fname, lname, address, province, city, phone) 
                  VALUES ('$user_id', '$fname', '$lname', '$address', '$province', '$city', '$phone')";
        $result = mysqli_query($conn, $query);

        if ($result) {
             echo "<script>alert('Shipping Information Saved!'); window.location.href = 'payment.html';</script>";
           
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Error: User is not logged in.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC ZONE - Shipping Information</title>
    <link rel="stylesheet" href="shipping.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <nav class="nav">
    <a href="home.html" id="logo-link">  
    <img src="../../img/logo.png" alt="logo" name="logo" id="logo-image">
</a>
        <div class="nav-links">
             <a href="user-profile.php" class="user">
                        <i class="bi bi-person"></i> <!-- Icon only for User -->
                    </a>
            <a href="cart.php"><i class="bi bi-cart"></i></a>
            <a href="../logout.php" class="btn">Log Out</a>
        </div>
    </nav>

    <div class="container">
        <div class="head-title">
            <h1>Shipping Information</h1>
        </div>

        <form id="form" method="post" action="">
            <div class="input-box">
                <label for="fname">First Name</label>
                <input type="text" name="fname" id="fname" required>
            </div>

            <div class="input-box">
                <label for="lname">Last Name</label>
                <input type="text" name="lname" id="lname" required>
            </div>

            <div class="input-box">
                <label for="address">Street Address</label>
                <input type="text" name="address" id="address" required>
            </div>

            <div class="input-box">
                <label for="province">State/Province</label>
                <select name="province" id="province" required>
                    <option value="" selected>Please choose your province</option>
                    <option value="p1">Province 1/ Koshi Province</option>
                    <option value="p2">Province 2/ Madhesh Pradesh</option>
                    <option value="p3">Province 3/ Bagmati Province</option>
                    <option value="p4">Province 4/ Gandaki Province</option>
                    <option value="p5">Province 5/ Lumbini Province </option>
                    <option value="p6">Province 6/ Karnali Pradhesh</option>
                    <option value="p7">Province 7/ Sudurpashchim Pradesh</option>
                </select>
            </div>

            <div class="input-box">
                <label for="city">City</label>
                <input type="text" id="city" name="city" required>
            </div>

            <div class="input-box">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <button type="submit" id="next" name="next">Next</button>
        </form>
    </div>
</body>
</html>
