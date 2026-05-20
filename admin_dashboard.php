<?php
session_start();
include('db.php');

// Check if logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Only superadmins can access this dashboard
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'superadmin') {
    die("Access denied. Only superadmins can view the dashboard.");
}

// Fetch stats
$totalOrdersQuery = "SELECT COUNT(*) AS total FROM orders";
$pendingOrdersQuery = "SELECT COUNT(*) AS total FROM orders WHERE status='Pending'";
$deliveredOrdersQuery = "SELECT COUNT(*) AS total FROM orders WHERE status='Delivered'";
$totalMessagesQuery = "SELECT COUNT(*) AS total FROM contact_messages";
$totalAdminsQuery = "SELECT COUNT(*) AS total FROM admins";

$totalOrders = mysqli_fetch_assoc(mysqli_query($conn, $totalOrdersQuery))['total'];
$pendingOrders = mysqli_fetch_assoc(mysqli_query($conn, $pendingOrdersQuery))['total'];
$deliveredOrders = mysqli_fetch_assoc(mysqli_query($conn, $deliveredOrdersQuery))['total'];
$totalMessages = mysqli_fetch_assoc(mysqli_query($conn, $totalMessagesQuery))['total'];
$totalAdmins = mysqli_fetch_assoc(mysqli_query($conn, $totalAdminsQuery))['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color:#f8f9fa;">

  <!-- Navbar -->

<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container-fluid">
    <a class="navbar-brand" href="admin_dashboard.php">Admin Panel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">

        <!-- Dashboard only for superadmins -->
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'superadmin') { ?>
          <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admin_dashboard.php' ? 'active' : ''; ?>" href="admin_dashboard.php">Dashboard</a>
          </li>
        <?php } ?>

        <!-- Orders always visible -->
        <li class="nav-item">
          <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admin_orders.php' ? 'active' : ''; ?>" href="admin_orders.php">Orders</a>
        </li>

        <!-- Messages visible to both admins and superadmins -->
        <li class="nav-item">
          <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admin_messages.php' ? 'active' : ''; ?>" href="admin_messages.php">Messages</a>
        </li>

        <!-- Add Item visible ONLY to normal admins -->
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { ?>
          <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admin_add_item.php' ? 'active' : ''; ?>" href="admin_add_item.php">Add Item</a>
          </li>
        <?php } ?>

        <!-- Add Admin only for superadmins -->
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'superadmin') { ?>
          <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admin_add.php' ? 'active' : ''; ?>" href="admin_add.php">Add Admin</a>
          </li>
        <?php } ?>

        <!-- Logout always visible -->
        <li class="nav-item">
          <a class="nav-link text-danger" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>



  <!-- Dashboard Stats -->
  <div class="container mt-5">
    <h2 class="text-center mb-4">Superadmin Dashboard Overview</h2>
    <div class="row">
      <div class="col-md-4 mb-4">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Total Orders</h5>
            <p class="display-6 text-success"><?php echo $totalOrders; ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Pending Orders</h5>
            <p class="display-6 text-warning"><?php echo $pendingOrders; ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Delivered Orders</h5>
            <p class="display-6 text-primary"><?php echo $deliveredOrders; ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-4">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Contact Messages</h5>
            <p class="display-6 text-info"><?php echo $totalMessages; ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-4">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Admin Users</h5>
            <p class="display-6 text-dark"><?php echo $totalAdmins; ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
