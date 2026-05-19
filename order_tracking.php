<?php
include('db.php');

$status_message = "";
$order_details = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search_value = $_POST['search_value'];

    // Check if input is numeric → treat as order ID, else treat as phone
    // Check if input length looks like a phone number or an order ID
if (strlen($search_value) > 6) {
    // Treat as phone number
    $query = "SELECT * FROM orders WHERE customer_phone = '$search_value' ORDER BY created_at DESC LIMIT 1";
} else {
    // Treat as order ID
    $query = "SELECT * FROM orders WHERE id = $search_value";
}


    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $order_details = mysqli_fetch_assoc($result);
        $status_message = "Order found!";
    } else {
        $status_message = "No order found for this input.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Tracking</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h2 class="text-center mb-4">Track Your Order</h2>

  <form method="POST" class="mb-4">
    <div class="input-group">
      <input type="text" name="search_value" class="form-control" placeholder="Enter Order ID or Phone Number" required>
      <button type="submit" class="btn btn-success">Track</button>
    </div>
  </form>

  <?php if (!empty($status_message)) { ?>
    <div class="alert alert-info"><?php echo $status_message; ?></div>
  <?php } ?>

  <?php if ($order_details) { ?>
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title">Order #<?php echo $order_details['id']; ?></h5>
        <p><strong>Name:</strong> <?php echo $order_details['customer_name']; ?></p>
        <p><strong>Phone:</strong> <?php echo $order_details['customer_phone']; ?></p>
        <p><strong>Address:</strong> <?php echo $order_details['customer_address']; ?></p>
        <p><strong>Total:</strong> $<?php echo $order_details['total']; ?></p>
        <p><strong>Status:</strong> 
          <?php 
            if ($order_details['status'] == 'Pending') {
              echo "<span class='badge bg-warning'>Pending</span>";
            } elseif ($order_details['status'] == 'Preparing') {
              echo "<span class='badge bg-info'>Preparing</span>";
            } elseif ($order_details['status'] == 'Delivered') {
              echo "<span class='badge bg-success'>Delivered</span>";
            } else {
              echo $order_details['status'];
            }
          ?>
        </p>
        <p><strong>Placed At:</strong> <?php echo $order_details['created_at']; ?></p>

        <h6>Items:</h6>
        <ul>
          <?php
            $items = json_decode($order_details['items'], true);
            if (!empty($items)) {
              foreach ($items as $item) {
                echo "<li>{$item['name']} (x{$item['quantity']}) - $".$item['price']."</li>";
              }
            }
          ?>
        </ul>
      </div>
    </div>
  <?php } ?>
</div>

</body>
</html>
