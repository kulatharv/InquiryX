<?php
session_start();
include 'includes/db.php';

// Redirect if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit();
}

// Handle export
if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=inquiries.csv');

    $output = fopen("php://output", "w");
    fputcsv($output, ['ID', 'Customer', 'Date', 'Product', 'Description', 'Priority', 'Status']);

    $query = "SELECT i.*, c.name as customer_name FROM inquiries i JOIN customers c ON i.customer_id = c.id";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['id'],
            $row['customer_name'],
            $row['inquiry_date'],
            $row['product'],
            $row['description'],
            $row['priority'] ?? 'Normal',
            $row['status']
        ]);
    }
    fclose($output);
    exit();
}

// Filtering
$monthFilter = $_GET['month'] ?? date('Y-m');
$inquiries = $conn->query("SELECT i.*, c.name as customer_name FROM inquiries i JOIN customers c ON i.customer_id = c.id WHERE DATE_FORMAT(inquiry_date, '%Y-%m') = '$monthFilter'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Inquiries | InquiryX</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background: #f0f4f8;
        }
        header {
            background-color: #00695c;
            color: white;
            padding: 15px 30px;
            font-size: 24px;
        }
        nav {
            background: #004d40;
            padding: 10px;
            text-align: center;
        }
        nav a {
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            font-weight: bold;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .container {
            padding: 20px;
        }
        .actions {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #009688;
            color: white;
        }
        .filter-form {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        input[type="month"], .btn {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #bbb;
        }
        .btn {
            background-color: #00695c;
            color: white;
            cursor: pointer;
        }
    </style>
</head>
<body>

<header>InquiryX - View Inquiries</header>
<nav>
    <a href="dashboard.php">Home</a>
    <a href="add_customer.php">Add Customer</a>
    <a href="add_inquiry.php">Add Inquiry</a>
    <a href="followup.php">Follow-up</a>
    <a href="view_inquiries.php">Inquiries</a>
    <a href="logout.php">Logout</a>
</nav>

<div class="container">
    <form class="filter-form" method="get">
        <label>Select Month:</label>
        <input type="month" name="month" value="<?php echo $monthFilter; ?>">
        <button class="btn" type="submit">Filter</button>
        <a href="?export=csv" class="btn">Export CSV</a>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Date</th>
            <th>Product</th>
            <th>Description</th>
            <th>Priority</th>
            <th>Status</th>
        </tr>
        <?php while ($row = $inquiries->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['customer_name']) ?></td>
                <td><?= $row['inquiry_date'] ?></td>
                <td><?= htmlspecialchars($row['product']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td><?= $row['priority'] ?? 'Normal' ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
