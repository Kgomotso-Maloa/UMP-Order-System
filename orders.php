<?php
include_once("partials/header.php");
include_once("connection/connection.php");
include_once("admin/model/Order.php");
include_once("functions/orders_functions.php");

// Initialize Food class with database connection
$food = new Order($conn);

// Retrieve orders for the logged-in user
$user_id = $_SESSION['id'];
$orders = $food->getOrdersByUserId($user_id);

// Separate orders into upcoming, past, and canceled orders
$upcomingOrders = [];
$pastOrders = [];
$canceledOrders = [];

foreach ($orders as $order) {
    if ($order['status'] === 'completed') {
        $pastOrders[] = $order;
    } elseif ($order['status'] === 'cancelled') {
        $canceledOrders[] = $order;
    } else {
        $upcomingOrders[] = $order;
    }
}

// Group orders by date
$groupedUpcomingOrders = groupOrdersByDate($upcomingOrders);
$groupedPastOrders = groupOrdersByDate($pastOrders);
$groupedCanceledOrders = groupOrdersByDate($canceledOrders);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">

    <style>
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050; /* Adjust as needed */
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <h1 class="mt-4">My Orders</h1>

    <div class="d-flex justify-content-center">
        <div class="btn-group" role="group" aria-label="Order Toggle">
            <button type="button" class="btn btn-success" id="upcomingOrdersBtn">Upcoming Orders</button>
            <button type="button" class="btn btn-secondary" id="pastOrdersBtn">Past Orders</button>
            <button type="button" class="btn btn-danger" id="canceledOrdersBtn">Canceled Orders</button>
        </div>
    </div>

    <div class="container-fluid pt-5 mt-5">
        <!-- Upcoming Orders -->
        <div id="upcomingOrders" class="orders-table">
            <?php if (!empty($groupedUpcomingOrders)) : ?>
                <?php foreach ($groupedUpcomingOrders as $formattedDate => $orders) : ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4 class="card-title"><?php echo $formattedDate; ?></h4>
                        </div>
                        <div class="card-body">
                            <?php foreach ($orders as $order) : ?>
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Order Details</h5>
                                        <p class="card-text"><strong>Total Amount:</strong> R<?php echo number_format($order['total_amount'], 2); ?></p>
                                        <p class="card-text"><strong>Status:</strong> <span class="badge <?php echo getStatusBadgeClass($order['status']); ?>"><?php echo strtoupper($order['status']); ?></span></p>
                                        <p class="card-text"><strong>Food Items:</strong> <?php echo $order['food_items']; ?></p>
                                        <!-- Cancel Order Button -->
                                        <button type="button" class="btn btn-danger cancel-order-btn" data-order-id="<?php echo $order['id']; ?>">Cancel Order</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="alert alert-info" role="alert">
                    You have no upcoming orders.
                </div>
            <?php endif; ?>
        </div>

        <!-- Past Orders -->
        <div id="pastOrders" class="orders-table" style="display: none;">
            <?php if (!empty($groupedPastOrders)) : ?>
                <?php foreach ($groupedPastOrders as $formattedDate => $orders) : ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4 class="card-title"><?php echo $formattedDate; ?></h4>
                        </div>
                        <div class="card-body">
                            <?php foreach ($orders as $order) : ?>
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Order Details</h5>
                                        <p class="card-text"><strong>Total Amount:</strong> R<?php echo number_format($order['total_amount'], 2); ?></p>
                                        <p class="card-text"><strong>Status:</strong> <span class="badge <?php echo getStatusBadgeClass($order['status']); ?>"><?php echo strtoupper($order['status']); ?></span></p>
                                        <p class="card-text"><strong>Food Items:</strong> <?php echo $order['food_items']; ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="alert alert-info" role="alert">
                    You have no past orders.
                </div>
            <?php endif; ?>
        </div>

        <!-- Canceled Orders -->
        <div id="canceledOrders" class="orders-table" style="display: none;">
            <?php if (!empty($groupedCanceledOrders)) : ?>
                <?php foreach ($groupedCanceledOrders as $formattedDate => $orders) : ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4 class="card-title"><?php echo $formattedDate; ?></h4>
                        </div>
                        <div class="card-body">
                            <?php foreach ($orders as $order) : ?>
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Order Details</h5>
                                        <p class="card-text"><strong>Total Amount:</strong> R<?php echo number_format($order['total_amount'], 2); ?></p>
                                        <p class="card-text"><strong>Status:</strong> <span class="badge <?php echo getStatusBadgeClass($order['status']); ?>"><?php echo strtoupper($order['status']); ?></span></p>
                                        <p class="card-text"><strong>Food Items:</strong> <?php echo $order['food_items']; ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="alert alert-info" role="alert">
                    You have no canceled orders.
                </div>
            <?php endif; ?>
        </div>
                
        <a href="index.php" class="btn btn-primary">Back to Home</a>
    </div>
</div>

<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelOrderModalLabel">Confirm Cancellation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to cancel this order?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="confirmCancelOrderBtn">Confirm Cancel</button>
            </div>
        </div>
    </div>
</div>
<div class="toast" id="toastMessage" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-body text-center text-danger">
        <!-- Toast message content will be dynamically inserted here -->
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/orders.js"></script> 
</body>
</html>
