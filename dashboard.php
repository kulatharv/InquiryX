<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>InquiryX Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    html, body {
      height: 100%;
      margin: 0;
      overflow: hidden;
    }

    .wrapper {
      display: flex;
      height: 100vh;
    }

    .sidebar {
      width: 220px;
      background: #003366;
      flex-shrink: 0;
      display: flex;
      flex-direction: column;
      height: 100vh;
      overflow-y: auto;
      position: fixed;
      top: 56px;
      left: 0;
    }

    .sidebar a {
      color: #cfd8dc;
      text-decoration: none;
      display: block;
      padding: 12px 15px;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background: #0055aa;
      color: #fff;
    }

    .main-content {
      margin-left: 220px;
      padding: 20px;
      flex-grow: 1;
      height: calc(100vh - 56px);
      overflow-y: auto;
    }

    .card-icon {
      font-size: 2rem;
      color: #0055aa;
    }

    .navbar {
      background: linear-gradient(90deg,#003366,#0055aa);
    }

    .table th {
      background: #003366;
      color: #fff;
    }
  </style>
</head>
<body>
<?php
include('includes/db.php');
session_start();
if (!isset($_SESSION['username'])) {
  $_SESSION['username'] = "Demo User";
}
$username = $_SESSION['username'];

$totalInquiries = $conn->query("SELECT COUNT(*) AS total FROM inquiries")->fetch_assoc()['total'] ?? 0;
$ongoingInquiries = $conn->query("SELECT COUNT(*) AS total FROM inquiries WHERE status='ongoing'")->fetch_assoc()['total'] ?? 0;
$followups = $conn->query("SELECT COUNT(*) AS total FROM followups WHERE status='pending'")->fetch_assoc()['total'] ?? 0;
$closedInquiries = $conn->query("SELECT COUNT(*) AS total FROM inquiries WHERE status='closed'")->fetch_assoc()['total'] ?? 0;
$todaysInquiries = $conn->query("SELECT COUNT(*) AS total FROM inquiries WHERE DATE(created_at)=CURDATE()")->fetch_assoc()['total'] ?? 0;

$activeInquiries = $conn->query("SELECT i.id, COALESCE(c.name,'Unknown') AS customer_name, i.subject, i.created_at FROM inquiries i LEFT JOIN customers c ON i.customer_id = c.id WHERE i.status IN ('new','ongoing') ORDER BY i.created_at DESC LIMIT 7");
$statusQuery = $conn->query("SELECT status, COUNT(*) AS total FROM inquiries GROUP BY status");
$statusLabels = [];
$statusCounts = [];
while ($row = $statusQuery->fetch_assoc()) {
  $statusLabels[] = ucfirst($row['status']);
  $statusCounts[] = $row['total'];
}
$monthlyQuery = $conn->query("SELECT DATE_FORMAT(created_at, '%b %Y') as month, COUNT(*) as total FROM inquiries WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) GROUP BY YEAR(created_at), MONTH(created_at) ORDER BY YEAR(created_at), MONTH(created_at)");
$monthLabels = [];
$monthCounts = [];
while ($row = $monthlyQuery->fetch_assoc()) {
  $monthLabels[] = $row['month'];
  $monthCounts[] = $row['total'];
}
?>

<nav class="navbar navbar-dark shadow-sm fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">InquiryX</a>
    <div class="text-white">
      Welcome, <b><?= htmlspecialchars($username) ?></b> |
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="wrapper">
  <div class="sidebar">
    <div class="text-center py-4 border-bottom">
      <img src="https://i.pravatar.cc/80" class="rounded-circle border" alt="profile">
      <h6 class="mt-2 text-white"><?= htmlspecialchars($username) ?></h6>
    </div>
    <a href="dashboard.php" class="active"><i class="fas fa-home me-2"></i> Dashboard</a>
    <a href="add_customer.php"><i class="fas fa-user-plus me-2"></i> Add Customer</a>
    <a href="add_inquiry.php"><i class="fas fa-file-signature me-2"></i> Add Inquiry</a>
    <a href="followup.php"><i class="fas fa-phone-volume me-2"></i> Followups</a>
    <a href="view_inquiries.php"><i class="fas fa-list me-2"></i> View Inquiries</a>
  </div>

  <div class="main-content">
    <h2 class="fw-bold mb-4">Dashboard</h2>

    <div class="row g-4 mb-4">
      <div class="col-md-4">
        <div class="card shadow-sm border-0 p-3 d-flex flex-row align-items-center">
          <i class="fas fa-database card-icon me-3"></i>
          <div>
            <h6 class="text-muted mb-1">Total Inquiries</h6>
            <h3 class="fw-bold"><?= $totalInquiries ?></h3>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm border-0 p-3 d-flex flex-row align-items-center">
          <i class="fas fa-spinner card-icon me-3"></i>
          <div>
            <h6 class="text-muted mb-1">Ongoing Inquiries</h6>
            <h3 class="fw-bold"><?= $ongoingInquiries ?></h3>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm border-0 p-3 d-flex flex-row align-items-center">
          <i class="fas fa-phone card-icon me-3"></i>
          <div>
            <h6 class="text-muted mb-1">Followups Pending</h6>
            <h3 class="fw-bold"><?= $followups ?></h3>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm border-0 p-3 d-flex flex-row align-items-center">
          <i class="fas fa-check-circle card-icon me-3"></i>
          <div>
            <h6 class="text-muted mb-1">Closed Inquiries</h6>
            <h3 class="fw-bold"><?= $closedInquiries ?></h3>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm border-0 p-3 d-flex flex-row align-items-center">
          <i class="fas fa-calendar-day card-icon me-3"></i>
          <div>
            <h6 class="text-muted mb-1">Today’s Inquiries</h6>
            <h3 class="fw-bold"><?= $todaysInquiries ?></h3>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4">
        <div class="card shadow-sm p-2">
          <h6 class="fw-bold">Inquiry Status Distribution</h6>
          <canvas id="statusChart" height="200"></canvas>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm p-3">
          <h6 class="fw-bold">Monthly Inquiries (Last 6 Months)</h6>
          <canvas id="monthlyChart" height="200"></canvas>
        </div>
      </div>
    </div>

    <div class="card shadow-sm mt-4">
      <div class="card-header bg-white fw-bold">Active Inquiries</div>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead>
            <tr>
              <th>#</th>
              <th>Customer</th>
              <th>Subject</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($activeInquiries && $activeInquiries->num_rows > 0) {
              $i = 1;
              while ($row = $activeInquiries->fetch_assoc()) {
                $date = date("d M Y, H:i", strtotime($row['created_at']));
                echo "<tr><td>{$i}</td><td>" . htmlspecialchars($row['customer_name']) . "</td><td>" . htmlspecialchars($row['subject']) . "</td><td>{$date}</td></tr>";
                $i++;
              }
            } else {
              echo "<tr><td colspan='4' class='text-center text-muted py-3'>No active inquiries found</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
      <div class="card-footer text-end">
        <a href="view_inquiries.php" class="btn btn-primary btn-sm">View All</a>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const statusLabels = <?= json_encode($statusLabels) ?>;
const statusCounts = <?= json_encode($statusCounts) ?>;
const monthLabels = <?= json_encode($monthLabels) ?>;
const monthCounts = <?= json_encode($monthCounts) ?>;
new Chart(document.getElementById('statusChart'), {
  type: 'pie',
  data: {
    labels: statusLabels,
    datasets: [{ data: statusCounts, backgroundColor: ['#007bff','#ffc107','#28a745','#dc3545'] }]
  },
  options: { responsive:true }
});
new Chart(document.getElementById('monthlyChart'), {
  type: 'bar',
  data: {
    labels: monthLabels,
    datasets: [{ label: 'Inquiries', data: monthCounts, backgroundColor: '#007bff' }]
  },
  options: {
    responsive:true,
    scales: { y: { beginAtZero:true } }
  }
});
</script>
</body>
</html>
