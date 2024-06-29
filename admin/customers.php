<?php 
include("model/login_check.php"); 
require_once '../connection/connection.php';
require_once 'model/Admin.php';

$admin = new Admin($conn);
$customers = $admin->getAllCustomers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customers</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<!-- Header -->
<header class="header">
    <div class="d-flex justify-content-between align-items-center">
      <h1 class="logo">Admin Dashboard</h1>
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fas fa-user"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
          <li><a class="dropdown-item" href="#">Settings</a></li>
          <li><a class="dropdown-item" href="profile.php">Profile</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="model/logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
</header>

<!-- Main Content -->
<div class="container-fluid mt-4">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-3">
      <div class="sidebar">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link" href="dashboard.php">
              <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="customers.php">
              <i class="fas fa-users"></i> Customers
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="orders.php">
              <i class="fas fa-clipboard-list"></i> Orders
            </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="menuDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-utensils"></i> Menu
            </a>
            <ul class="dropdown-menu" aria-labelledby="menuDropdown">
              <li><a class="dropdown-item" href="all_categories.php">All Categories</a></li>
              <li><a class="dropdown-item" href="add_category.php">Add Category</a></li>
              <li><a class="dropdown-item" href="add_menu.php">Add Menu</a></li>
              <li><a class="dropdown-item" href="all_menus.php">All Menus</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>

    <!-- Main Content Area -->
    <div class="col-md-9">
      <div class="content">
        <h2>Customers</h2>
        <table class="table table-bordered table-striped table-hover">
          <thead class="table-dark">
            <tr>
              <th>Name</th>
              <th>Surname</th>
              <th>Email</th>
              <th>Role</th>
              <th>Registration Number</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($customers as $customer): ?>
            <tr>
              <td><?php echo htmlspecialchars($customer['name']); ?></td>
              <td><?php echo htmlspecialchars($customer['surname']); ?></td>
              <td><?php echo htmlspecialchars($customer['email']); ?></td>
              <td><?php echo htmlspecialchars($customer['role']); ?></td>
              <td><?php echo htmlspecialchars($customer['registration_number']); ?></td>
              <td>
                <a href="update_customer.php?id=<?php echo $customer['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                <form action="model/delete_customer.php" method="POST" style="display: inline-block;">
                    <input type="hidden" name="id" value="<?php echo $customer['id']; ?>">
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this customer?')">Delete</button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="footer">
  <div class="container">
    <p>&copy; 2024 Admin Dashboard</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="admin.js"></script>
</body>
</html>
