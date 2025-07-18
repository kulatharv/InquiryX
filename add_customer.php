<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit();
}

include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $address = trim($_POST['address']);

    $stmt = $conn->prepare("INSERT INTO customers (name, email, contact, address, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $name, $email, $contact, $address);
    $stmt->execute();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Customer | InquiryX</title>
    <style>
        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f0f4f8, #d9e2ec);
        }

        nav {
            background-color: #0056b3;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        nav h1 {
            font-size: 24px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 16px;
        }

        .main-content {
            padding: 30px;
        }

        .flex-container {
            display: flex;
            justify-content: space-between;
            gap: 40px;
            margin-top: 20px;
        }

        .customer-form {
            flex: 1;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .customer-form form {
            display: flex;
            flex-direction: column;
        }

        .customer-form h2 {
            margin-bottom: 20px;
            color: #0056b3;
        }

        .customer-form label {
            margin-top: 10px;
            font-weight: bold;
        }

        .customer-form input,
        .customer-form textarea {
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .customer-form button {
            background-color: #0056b3;
            color: white;
            border: none;
            padding: 12px;
            margin-top: 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .customer-form button:hover {
            background-color: #00408a;
        }

        .recent-customers {
            width: 300px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .recent-customers h3 {
            color: #0056b3;
            margin-bottom: 15px;
        }

        .recent-customers ul {
            list-style: none;
            padding: 0;
        }

        .recent-customers li {
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>

<nav>
    <h1>InquiryX</h1>
    <div class="nav-links">
        <a href="dashboard.php">Home</a>
        <a href="add_customer.php">Add Customer</a>
        <a href="add_inquiry.php">Add Inquiry</a>
        <a href="followup.php">Follow-up</a>
        <a href="view_inquiries.php">View Inquiries</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="main-content">
    <div class="flex-container">
        <!-- Customer Form -->
        <div class="customer-form">
            <h2>Add New Customer</h2>
            <form method="POST">
                <label>Name:</label>
                <input type="text" name="name" required>

                <label>Email:</label>
                <input type="email" name="email" required>

                <label>Contact:</label>
                <input type="text" name="contact" required>

                <label>Address:</label>
                <textarea name="address" rows="3" required></textarea>

                <button type="submit">Add Customer</button>
            </form>
        </div>

        <!-- Recently Added Customers -->
        <div class="recent-customers">
            <h3>Recently Added</h3>
            <ul>
                <?php
                $result = $conn->query("SELECT name, created_at FROM customers ORDER BY id DESC LIMIT 5");
                while ($row = $result->fetch_assoc()) {
                    echo "<li><strong>" . htmlspecialchars($row['name']) . "</strong><br><small>" . $row['created_at'] . "</small></li>";
                }
                ?>
            </ul>
        </div>
    </div>

    <div class="footer">
        &copy; <?php echo date('Y'); ?> InquiryX. All rights reserved.
    </div>
</div>

</body>
</html>
