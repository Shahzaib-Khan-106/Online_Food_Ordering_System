<?php
session_start();
include('db.php');

// Add item to cart
if (isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $item_price = $_POST['item_price'];
    $item_quantity = $_POST['quantity'];

    $cart_item = array(
        'id' => $item_id,
        'name' => $item_name,
        'price' => $item_price,
        'quantity' => $item_quantity
    );

    // If cart exists, update or add
    if (isset($_SESSION['cart'])) {
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $item_id) {
                $item['quantity'] += $item_quantity;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $_SESSION['cart'][] = $cart_item;
        }
    } else {
        $_SESSION['cart'][] = $cart_item;
    }
}

// Remove item
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $remove_id) {
            unset($_SESSION['cart'][$key]);
        }
    }
}

// Clear cart
if (isset($_GET['clear'])) {
    unset($_SESSION['cart']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shopping Cart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2 class="mb-4 text-center">Your Cart</h2>
  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th>Item</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $grand_total = 0;
      if (!empty($_SESSION['cart'])) {
          foreach ($_SESSION['cart'] as $item) {
              $total = $item['price'] * $item['quantity'];
              $grand_total += $total;
              echo "<tr>
                      <td>{$item['name']}</td>
                      <td>\${$item['price']}</td>
                      <td>{$item['quantity']}</td>
                      <td>\${$total}</td>
                      <td><a href='cart.php?remove={$item['id']}' class='btn btn-danger btn-sm'>Remove</a></td>
                    </tr>";
          }
          echo "<tr>
                  <td colspan='3' class='text-end'><strong>Grand Total</strong></td>
                  <td colspan='2'><strong>\${$grand_total}</strong></td>
                </tr>";
      } else {
          echo "<tr><td colspan='5' class='text-center'>Your cart is empty</td></tr>";
      }
      ?>
    </tbody>
  </table>
  <div class="text-center">
    <a href="index.php" class="btn btn-primary">Continue Shopping</a>
    <a href="cart.php?clear=1" class="btn btn-warning">Clear Cart</a>
    <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
  </div>
</div>
</body>
</html>
