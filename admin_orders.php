<?php
session_start();
include('db.php');

// Update order status
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    $query = "UPDATE orders SET status='$new_status' WHERE id=$order_id";
    mysqli_query($conn, $query);
    echo "<script>alert('Order status updated successfully!'); window.location='admin_orders.php';</script>";
}

// Fetch all orders
$query = "SELECT * FROM orders ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h2 class="mb-4 text-center">Admin Dashboard - Orders</h2>
  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Customer Name</th>
        <th>Phone</th>
        <th>Address</th>
        <th>Total</th>
        <th>Status</th>
        <th>Created At</th>
        <th>Items</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
          <td><?php echo $row['id']; ?></td>
          <td><?php echo $row['customer_name']; ?></td>
          <td><?php echo $row['customer_phone']; ?></td>
          <td><?php echo $row['customer_address']; ?></td>
          <td>$<?php echo $row['total']; ?></td>
          <td><?php echo $row['status']; ?></td>
          <td><?php echo $row['created_at']; ?></td>
          <td>
            <?php
              $items = json_decode($row['items'], true);
              if (!empty($items)) {
                echo "<ul class='list-unstyled'>";
                foreach ($items as $item) {
                  echo "<li>{$item['name']} (x{$item['quantity']}) - $".$item['price']."</li>";
                }
                echo "</ul>";
              } else {
                echo "No items";
              }
            ?>
          </td>
          <td>
            <form method="POST" class="d-flex">
              <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
              <select name="status" class="form-select form-select-sm me-2">
                <option value="Pending" <?php if($row['status']=='Pending') echo 'selected'; ?>>Pending</option>
                <option value="Preparing" <?php if($row['status']=='Preparing') echo 'selected'; ?>>Preparing</option>
                <option value="Delivered" <?php if($row['status']=='Delivered') echo 'selected'; ?>>Delivered</option>
              </select>
              <button type="submit" name="update_status" class="btn btn-primary btn-sm">Update</button>
            </form>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

</body>
</html>
