<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT o.*, p.title FROM orders o JOIN products p ON o.product_id = p.id WHERE o.buyer_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_price = $product['price'] * $quantity;

    $stmt = $pdo->prepare("INSERT INTO orders (buyer_id, product_id, quantity, total_price) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $product_id, $quantity, $total_price]);
    $message = "Order placed successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - Alibaba Clone</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; }
        header { background: #232f3e; color: white; padding: 20px; text-align: center; }
        .container { max-width: 800px; margin: 20px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .order { border-bottom: 1px solid #ccc; padding: 10px 0; }
        .form-container { margin-top: 20px; }
        label { display: block; margin: 10px 0 5px; color: #555; }
        input { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px; }
        .btn { background: #febd69; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .btn:hover { background: #f3a847; }
        .success { color: green; text-align: center; }
    </style>
</head>
<body>
    <header>
        <h1>Orders</h1>
        <nav>
            <a href="#" onclick="redirect('index.php')">Home</a>
            <a href="#" onclick="redirect('products.php')">Products</a>
            <a href="#" onclick="redirect('profile.php')">Profile</a>
        </nav>
    </header>
    <div class="container">
        <h2>Your Orders</h2>
        <?php if (isset($message)): ?>
            <p class="success"><?php echo $message; ?></p>
        <?php endif; ?>
        <?php foreach ($orders as $order): ?>
            <div class="order">
                <p><strong>Product:</strong> <?php echo htmlspecialchars($order['title']); ?></p>
                <p><strong>Quantity:</strong> <?php echo htmlspecialchars($order['quantity']); ?></p>
                <p><strong>Total Price:</strong> $<?php echo htmlspecialchars($order['total_price']); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
            </div>
        <?php endforeach; ?>
        <div class="form-container">
            <h3>Place New Order</h3>
            <form method="POST">
                <label for="product_id">Product ID</label>
                <input type="number" id="product_id" name="product_id" required>
                <label for="quantity">Quantity</label>
                <input type="number" id="quantity" name="quantity" required>
                <button type="submit" name="place_order" class="btn">Place Order</button>
            </form>
        </div>
    </div>
    <script>
        function redirect(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
