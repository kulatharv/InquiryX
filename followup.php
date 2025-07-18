<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit();
}

include 'includes/db.php';

$message = "";
$selectedCustomerId = isset($_GET['customer_id']) ? intval($_GET['customer_id']) : 0;
$previousData = ['contact_mode' => '', 'description' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customerId = $_POST['customer_id'];
    $followupDate = $_POST['followup_date'];
    $contactMode = $_POST['contact_mode'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO followups (customer_id, followup_date, contact_mode, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $customerId, $followupDate, $contactMode, $description);

    if ($stmt->execute()) {
        $message = "Follow-up recorded successfully!";
    } else {
        $message = "Error recording follow-up.";
    }
    $stmt->close();
}

$customers = $conn->query("SELECT id, name FROM customers ORDER BY name ASC");

if ($selectedCustomerId) {
    $prev = $conn->query("SELECT contact_mode, description FROM followups WHERE customer_id = $selectedCustomerId ORDER BY id DESC LIMIT 1");
    if ($prev && $prev->num_rows > 0) {
        $previousData = $prev->fetch_assoc();
    }
}

// Today's and upcoming followups
$today = date('Y-m-d');
$followups_today = $conn->query("SELECT f.*, c.name FROM followups f JOIN customers c ON c.id = f.customer_id WHERE f.followup_date = '$today' ORDER BY f.followup_date ASC");
$followups_next = $conn->query("SELECT f.*, c.name FROM followups f JOIN customers c ON c.id = f.customer_id WHERE f.followup_date > '$today' ORDER BY f.followup_date ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Follow-up Inquiry | InquiryX</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background: #e3f2fd;
        }
        header {
            background-color: #1565c0;
            color: white;
            padding: 20px;
            font-size: 22px;
            text-align: center;
        }
        nav {
            background: #1976d2;
            display: flex;
            justify-content: center;
            gap: 15px;
            padding: 10px;
        }
        nav a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .container {
            display: flex;
            gap: 20px;
            padding: 20px;
        }
        .left {
            flex: 1;
            background: white;
            padding: 20px;
            border-radius: 8px;
        }
        .right {
            flex: 1;
            background: #fffde7;
            padding: 20px;
            border-radius: 8px;
        }
        h2 {
            margin-bottom: 10px;
            color: #0d47a1;
        }
        label {
            margin-top: 10px;
            display: block;
            font-weight: bold;
        }
        select, input[type="date"], textarea, input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background: #1565c0;
            color: white;
            margin-top: 20px;
            font-weight: bold;
        }
        .message {
            color: green;
            font-weight: bold;
            margin-top: 10px;
        }
        .followup-box {
            background: #f1f8e9;
            padding: 10px;
            margin-bottom: 10px;
            border-left: 5px solid #43a047;
        }
        .followup-box h4 {
            margin: 0;
        }
        .take-btn {
            display: inline-block;
            margin-top: 5px;
            padding: 5px 10px;
            background: #0288d1;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }
        .take-btn:hover {
            background: #01579b;
        }
    </style>
</head>
<body>
<header>InquiryX - Follow-up Manager</header>
<nav>
    <a href="dashboard.php">Home</a>
    <a href="add_customer.php">Add Customer</a>
    <a href="add_inquiry.php">Add Inquiry</a>
    <a href="followup.php">Follow-up</a>
    <a href="view_inquiries.php">Inquiries</a>
    <a href="logout.php">Logout</a>
</nav>

<div class="container">
    <div class="left">
        <h2>Take Follow-up</h2>
        <?php if ($message) echo "<div class='message'>$message</div>"; ?>
        <form method="post" action="followup.php">
            <label for="customer_id">Customer</label>
            <select name="customer_id" id="customer_id" required onchange="location = 'followup.php?customer_id=' + this.value;">
                <option value="">-- Select Customer --</option>
                <?php while ($row = $customers->fetch_assoc()) {
                    $selected = ($selectedCustomerId == $row['id']) ? "selected" : "";
                    echo "<option value='{$row['id']}' $selected>{$row['name']}</option>";
                } ?>
            </select>

            <label for="followup_date">Next Follow-up Date</label>
            <input type="date" name="followup_date" required value="<?php echo date('Y-m-d'); ?>">

            <label for="contact_mode">Mode of Contact</label>
            <select name="contact_mode" required>
                <option value="Call" <?php if ($previousData['contact_mode'] == 'Call') echo 'selected'; ?>>Call</option>
                <option value="Message" <?php if ($previousData['contact_mode'] == 'Message') echo 'selected'; ?>>Message</option>
                <option value="Visit" <?php if ($previousData['contact_mode'] == 'Visit') echo 'selected'; ?>>Visit</option>
            </select>

            <label for="description">Follow-up Description</label>
            <textarea name="description" rows="4" required><?php echo htmlspecialchars($previousData['description']); ?></textarea>

            <input type="submit" value="Save Follow-up">
        </form>
    </div>

    <div class="right">
        <h2>Today's Follow-ups</h2>
        <?php while ($f = $followups_today->fetch_assoc()): ?>
            <div class="followup-box">
                <h4><?php echo $f['name']; ?></h4>
                <p><?php echo $f['contact_mode']; ?> - <?php echo $f['description']; ?></p>
                <a class="take-btn" href="followup.php?customer_id=<?php echo $f['customer_id']; ?>">Take Follow-up</a>
            </div>
        <?php endwhile; ?>

        <h2>Upcoming Follow-ups</h2>
        <?php while ($f = $followups_next->fetch_assoc()): ?>
            <div class="followup-box" style="border-left-color:#fbc02d;">
                <h4><?php echo $f['name']; ?> (<?php echo $f['followup_date']; ?>)</h4>
                <p><?php echo $f['contact_mode']; ?> - <?php echo $f['description']; ?></p>
                <a class="take-btn" href="followup.php?customer_id=<?php echo $f['customer_id']; ?>">Take Follow-up</a>
            </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>
