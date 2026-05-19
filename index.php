<?php
// db.php connection file should be included here
include('db.php');

// Fetch menu items from database
$query = "SELECT * FROM menu_items";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Online Food Ordering</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card-img-top {
      height: 200px; /* uniform image height */
      object-fit: cover; /* crop proportionally */
    }
  </style>
</head>
<body style="background-color:#f8f9fa;">

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Food Ordering</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
          <li class="nav-item"><a class="nav-link" href="order_tracking.php">Track Order</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Menu Display -->
  <div class="container mt-4">
    <h2 class="text-center mb-4">Menu</h2>
    <div class="row">
      <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <div class="col-md-4 mb-4">
          <div class="card h-100 shadow-sm">
            <img src="images/<?php echo $row['image']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>">
            <div class="card-body">
              <h5 class="card-title"><?php echo $row['name']; ?></h5>
              <p class="card-text"><?php echo $row['description']; ?></p>
              <p class="card-text"><strong>Price: $<?php echo $row['price']; ?></strong></p>
              <form method="POST" action="cart.php">
                <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
                <input type="hidden" name="item_name" value="<?php echo $row['name']; ?>">
                <input type="hidden" name="item_price" value="<?php echo $row['price']; ?>">
                <div class="mb-2">
                  <input type="number" name="quantity" value="1" min="1" class="form-control">
                </div>
                <button type="submit" name="add_to_cart" class="btn btn-success w-100">Add to Cart</button>
              </form>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
