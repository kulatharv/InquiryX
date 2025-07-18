<?php
include('includes/db.php');
session_start();

if (!isset($_SESSION['user'])) {
  header('Location: login.php');
  exit();
}

// Fetch counts for demo (add your queries here)
$new_inquiries = 10;     // example static count, replace with your query
$ongoing_inquiries = 5;  // example static count, replace with your query
$important_topic_count = 3; // example

$username = $_SESSION['user']; // assuming username stored here
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Dashboard - InquiryX</title>

<!-- FontAwesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0; padding: 0;
    background: #f5f7fa;
  }
  header {
    background-color: #2a4d69;
    color: white;
    padding: 20px 30px;
    font-size: 1.5rem;
    font-weight: 600;
  }
  header span.username {
    font-weight: normal;
    color: #9bc1bc;
  }

  .status-bar {
    display: flex;
    justify-content: center;
    gap: 40px;
    margin: 20px auto;
    max-width: 700px;
  }
  .status-card {
    background: white;
    box-shadow: 0 4px 8px rgb(0 0 0 / 0.1);
    border-radius: 10px;
    padding: 20px 30px;
    width: 180px;
    text-align: center;
    font-weight: 600;
    font-size: 1.2rem;
    color: #2a4d69;
    transition: transform 0.3s ease;
  }
  .status-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgb(0 0 0 / 0.15);
  }
  .status-card .count {
    font-size: 2.5rem;
    color: #4a90e2;
    margin-top: 10px;
  }

  /* Scroll-in container */
  .scroll-container {
    max-width: 800px;
    margin: 40px auto;
    background: white;
    border-radius: 12px;
    box-shadow: 0 6px 15px rgb(0 0 0 / 0.1);
    padding: 30px;
    overflow-y: auto;
    max-height: 350px;
    animation: scrollIn 1s ease forwards;
    opacity: 0;
    transform: translateY(40px);
  }

  @keyframes scrollIn {
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .options-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 25px;
    justify-content: center;
  }
  .option-card {
    flex: 1 1 180px;
    max-width: 180px;
    background: #4a90e2;
    color: white;
    border-radius: 10px;
    padding: 25px 10px;
    cursor: pointer;
    text-align: center;
    font-size: 1.1rem;
    box-shadow: 0 4px 10px rgb(74 144 226 / 0.5);
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
  }
  .option-card:hover {
    background: #357ABD;
    box-shadow: 0 6px 15px rgb(53 122 189 / 0.8);
  }
  .option-card i {
    font-size: 2.8rem;
    margin-bottom: 15px;
    display: block;
  }

  /* Sidebar style - simple fixed vertical */
  .sidebar {
    position: fixed;
    top: 0; left: 0;
    width: 220px;
    height: 100vh;
    background-color: #2a4d69;
    padding-top: 60px;
    box-shadow: 2px 0 5px rgba(0,0,0,0.15);
    display: flex;
    flex-direction: column;
    align-items: center;
    color: white;
  }
  .sidebar a {
    color: white;
    text-decoration: none;
    padding: 15px 20px;
    width: 100%;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 10px;
    border-left: 4px solid transparent;
    transition: background-color 0.3s, border-color 0.3s;
  }
  .sidebar a:hover {
    background-color: #1f3751;
    border-left: 4px solid #4a90e2;
  }
  .sidebar a i {
    font-size: 1.5rem;
  }
  .sidebar .profile {
    margin-top: auto;
    padding: 20px;
    font-size: 1rem;
    text-align: center;
    border-top: 1px solid #3b5a7f;
  }
  
  /* Main content pushed right for sidebar */
  main {
    margin-left: 240px;
    padding: 30px 50px;
  }

</style>

</head>
<body>

<div class="sidebar">
  <a href="dashboard.php"><i class="fas fa-home"></i> Home</a>
  <a href="add_customer.php"><i class="fas fa-user-plus"></i> Add Customer</a>
  <a href="add_inquiry.php"><i class="fas fa-plus-circle"></i> Add Inquiry</a>
  <a href="followup.php"><i class="fas fa-phone"></i> Followup Inquiry</a>
  <a href="view_inquiries.php"><i class="fas fa-list"></i> View Inquiries</a>
  <a href="import_inquiries.php"><i class="fas fa-file-import"></i> Import Data</a>
  <a href="profile.php"><i class="fas fa-user-circle"></i> Profile</a>
  <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>

  <div class="profile">
    Logged in as:<br />
    <strong><?= htmlspecialchars($username) ?></strong>
  </div>
</div>

<main>
  <header>
    Welcome, <span class="username"><?= htmlspecialchars($username) ?></span>!
  </header>

  <div class="status-bar">
    <div class="status-card">
      New Inquiries
      <div class="count"><?= $new_inquiries ?></div>
    </div>
    <div class="status-card">
      Ongoing Inquiries
      <div class="count"><?= $ongoing_inquiries ?></div>
    </div>
    <div class="status-card">
      Important Topics
      <div class="count"><?= $important_topic_count ?></div>
    </div>
  </div>

  <div class="scroll-container">
    <div class="options-grid">
      <div class="option-card" onclick="location.href='add_customer.php'">
        <i class="fas fa-user-plus"></i>
        Add Customer
      </div>
      <div class="option-card" onclick="location.href='add_inquiry.php'">
        <i class="fas fa-plus-circle"></i>
        Add Inquiry
      </div>
      <div class="option-card" onclick="location.href='followup.php'">
        <i class="fas fa-phone"></i>
        Followup Inquiry
      </div>
      <div class="option-card" onclick="location.href='view_inquiries.php'">
        <i class="fas fa-list"></i>
        View Inquiries
      </div>
      <div class="option-card" onclick="location.href='import_inquiries.php'">
        <i class="fas fa-file-import"></i>
        Import Data
      </div>
    </div>
  </div>
</main>

</body>
</html>
