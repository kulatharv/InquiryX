<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit();
}

include 'includes/db.php';

// Fetch customers for datalist suggestions
$customers = $conn->query("SELECT id, name FROM customers ORDER BY name ASC");

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customerName = $_POST['customer'];
    $inquiryProduct = $_POST['product'];
    $nextFollowup = $_POST['next_followup'];
    $description = $_POST['description'];
    $inquiryDate = date("Y-m-d"); // Automatically set today's date

    // Fetch customer ID
    $result = $conn->query("SELECT id FROM customers WHERE name = '" . $conn->real_escape_string($customerName) . "' LIMIT 1");
    if ($result->num_rows > 0) {
        $customerId = $result->fetch_assoc()['id'];

        $stmt = $conn->prepare("INSERT INTO inquiries (customer_id, product, inquiry_date, next_followup, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $customerId, $inquiryProduct, $inquiryDate, $nextFollowup, $description);

        if ($stmt->execute()) {
            $message = "Inquiry added successfully!";
        } else {
            $message = "Error adding inquiry.";
        }
        $stmt->close();
    } else {
        $message = "Customer not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Inquiry | InquiryX</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to bottom right, #e0f7fa, #e1f5fe);
        }
        header {
            background-color: #0056b3;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
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
        nav a:hover {
            background: #005bb5;
        }
        .container {
            max-width: 650px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #0056b3;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 15px;
            font-weight: bold;
        }
        input, textarea, select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 5px;
        }
        input[type="submit"] {
            margin-top: 25px;
            background-color: #0056b3;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #004080;
        }
        .message {
            text-align: center;
            margin-top: 20px;
            color: green;
            font-weight: bold;
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

<div class="container">
    <h2>Add New Inquiry</h2>
    <?php if ($message) echo "<div class='message'>$message</div>"; ?>
    <form method="post">
        <label for="customer">Select Customer:</label>
        <input list="customerList" name="customer" id="customer" required>
        <datalist id="customerList">
            <?php while ($row = $customers->fetch_assoc()) {
                echo "<option value=\"{$row['name']}\">";
            } ?>
        </datalist>

        <label for="product">Inquiry Product:</label>
        <input type="text" name="product" id="product" required>

        <label for="next_followup">Next Follow-up Date:</label>
        <input type="date" name="next_followup" id="next_followup">

        <label for="description">Description:</label>
        <textarea name="description" id="description" rows="4" placeholder="Enter details about the inquiry..."></textarea>

        <input type="submit" value="Add Inquiry">
    </form>
</div>
</body>
</html>
