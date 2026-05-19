<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    // Handle image upload
    $image = $_FILES['image']['name'];
    $target = "images/" . basename($image);

    // Insert into database
    $query = "INSERT INTO menu_items (name, description, price, category, image)
              VALUES ('$name', '$description', '$price', '$category', '$image')";

    if (mysqli_query($conn, $query)) {
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        echo "<div class='alert alert-success text-center'>Item added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>Error: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Menu Item</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color:#f8f9fa;">
<div class="container mt-5">
  <h2 class="text-center mb-4">Add New Menu Item</h2>
  <form method="POST" enctype="multipart/form-data" class="shadow p-4 bg-white rounded">
    <div class="mb-3">
      <label class="form-label">Food Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea name="description" class="form-control" rows="3" required></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">Price ($)</label>
      <input type="number" name="price" step="0.01" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Category</label>
      <select name="category" class="form-select" required>
        <option value="Pizza">Pizza</option>
        <option value="Burger">Burger</option>
        <option value="Drink">Drink</option>
        <option value="Side">Side</option>
        <option value="Dessert">Dessert</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Image</label>
      <input type="file" name="image" class="form-control" accept="image/*" required>
    </div>
    <button type="submit" class="btn btn-success w-100">Add Item</button>
  </form>
</div>
</body>
</html>
