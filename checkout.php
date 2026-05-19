<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    $customer_name = $_POST['name'];
    $customer_phone = $_POST['phone'];
    $customer_address = $_POST['address'];
    $order_items = json_encode($_SESSION['cart']); // store cart as JSON
    $grand_total = 0;

    foreach ($_SESSION['cart'] as $item) {
        $grand_total += $item['price'] * $item['quantity'];
    }

    $query = "INSERT INTO orders (customer_name, customer_phone, customer_address, items, total, status) 
              VALUES ('$customer_name', '$customer_phone', '$customer_address', '$order_items', '$grand_total', 'Pending')";
    mysqli_query($conn, $query);

    unset($_SESSION['cart']); // clear cart
    echo "<script>alert('Order placed successfully!'); window.location='index.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2 class="mb-4 text-center">Checkout</h2>
  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Phone</label>
      <input type="text" name="phone" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Address</label>
      <textarea name="address" class="form-control" required></textarea>
    </div>
    <button type="submit" name="place_order" class="btn btn-success w-100">Place Order</button>
  </form>
</div>
</body>
</html>
