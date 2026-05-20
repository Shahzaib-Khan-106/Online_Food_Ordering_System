<?php
// db.php connection file should be included here
include('db.php');

// Handle search and category filter
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';

// Pagination setup
$limit = 9; // items per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Build query with filters
$query = "SELECT * FROM menu_items WHERE 1=1";
if (!empty($search)) {
    $query .= " AND name LIKE '%$search%'";
}
if (!empty($category)) {
    $query .= " AND category='$category'";
}

// Count total items for pagination
$countQuery = str_replace("SELECT *", "SELECT COUNT(*) as total", $query);
$countResult = mysqli_query($conn, $countQuery);
$totalItems = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalItems / $limit);

// Add LIMIT for pagination
$query .= " LIMIT $limit OFFSET $offset";
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
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="index.php#menu">Menu</a></li>
          <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <div class="bg-light text-center py-5 shadow-sm">
    <h1 class="display-4">Welcome to Food Ordering</h1>
    <p class="lead">Delicious meals delivered to your doorstep</p>
    <a href="#menu" class="btn btn-success btn-lg">Start Order</a>
  </div>

  <!-- Search & Filter -->
  <div class="container mt-4">
    <form method="GET" class="row g-3 mb-4">
      <div class="col-md-6">
        <input type="text" name="search" class="form-control" placeholder="Search for food..." value="<?php echo htmlspecialchars($search); ?>">
      </div>
      <div class="col-md-4">
        <select name="category" class="form-select">
          <option value="">All Categories</option>
          <option value="Pizza" <?php if($category=='Pizza') echo 'selected'; ?>>Pizza</option>
          <option value="Burgers" <?php if($category=='Burgers') echo 'selected'; ?>>Burgers</option>
          <option value="Drinks" <?php if($category=='Drinks') echo 'selected'; ?>>Drinks</option>
          <option value="Sides" <?php if($category=='Sides') echo 'selected'; ?>>Sides</option>
          <option value="Desserts" <?php if($category=='Desserts') echo 'selected'; ?>>Desserts</option>
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-success w-100">Filter</button>
      </div>
    </form>
  </div>

  <!-- Menu Display -->
  <div class="container" id="menu">
    <h2 class="text-center mb-4">Menu</h2>
    <div class="row">
      <?php if(mysqli_num_rows($result) > 0) { ?>
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
      <?php } else { ?>
        <p class="text-center">No menu items found for your search/filter.</p>
      <?php } ?>
    </div>

    <!-- Pagination -->
        <!-- Pagination -->
    <?php if ($totalPages > 1) { ?>
      <nav>
        <ul class="pagination justify-content-center">
          <!-- Previous button -->
          <li class="page-item <?php if($page <= 1) echo 'disabled'; ?>">
            <a class="page-link" 
               href="?page=<?php echo max(1, $page-1); ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>">
              Previous
            </a>
          </li>

          <!-- Page numbers -->
          <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
            <li class="page-item <?php if($i == $page) echo 'active'; ?>">
              <a class="page-link" 
                 href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>">
                <?php echo $i; ?>
              </a>
            </li>
          <?php } ?>

          <!-- Next button -->
          <li class="page-item <?php if($page >= $totalPages) echo 'disabled'; ?>">
            <a class="page-link" 
               href="?page=<?php echo min($totalPages, $page+1); ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>">
              Next
            </a>
          </li>
        </ul>
      </nav>
    <?php } ?>

  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
