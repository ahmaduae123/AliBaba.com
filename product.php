<?php
session_start();
require_once 'db.php';

$product_id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_quote'])) {
    $quantity = $_POST['quantity'];
    $proposed_price = $_POST['proposed_price'];
    $stmt = $pdo->prepare("INSERT INTO quotations (buyer_id, product_id, quantity, proposed_price) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $product_id, $quantity, $proposed_price]);
    $message = "Quotation requested successfully!";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $message_text = $_POST['message'];
    $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, product_id, message) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $product['supplier_id'], $product_id, $message_text]);
    $message = "Message sent successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - Alibaba Clone</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; }
        header { background: #232f3e; color: white; padding: 20px; text-align: center; }
        .container { max-width: 800px; margin: 20px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        img { max-width: 100%; height: auto; border-radius: 5px; }
        h2 { color: #232f3e; }
        p { color: #555; }
        .form-container { margin-top: 20px; }
        label { display: block; margin: 10px 0 5px; color: #555; }
        input, textarea { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px; }
        .btn { background: #febd69; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .btn:hover { background: #f3a847; }
        .success { color: green; text-align: center; }
    </style>
</head>
<body>
    <header>
        <h1>Product Details</h1>
        <nav>
            <a href="#" onclick="redirect('index.php')">Home</a>
            <a href="#" onclick="redirect('products.php')">Products</a>
            <a href="#" onclick="redirect('profile.php')">Profile</a>
        </nav>
    </header>
    <div class="container">
        <?php if ($product): ?>
            <img src="<?php echo htmlspecialchars($product['image'] ?: 'placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
            <h2><?php echo htmlspecialchars($product['title']); ?></h2>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <p>Category: <?php echo htmlspecialchars($product['category']); ?></p>
            <p>Price: $<?php echo htmlspecialchars($product['price']); ?></p>
            <p>MOQ: <?php echo htmlspecialchars($product['moq']); ?></p>
            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'buyer'): ?>
                <div class="form-container">
                    <h3>Request Quotation</h3>
                    <?php if (isset($message)): ?>
                        <p class="success"><?php echo $message; ?></p>
                    <?php endif; ?>
                    <form method="POST">
                        <label for="quantity">Quantity</label>
                        <input type="number" id="quantity" name="quantity" required>
                        <label for="proposed_price">Proposed Price ($)</label>
                        <input type="number" id="proposed_price" name="proposed_price" step="0.01" required>
                        <button type="submit" name="request_quote" class="btn">Request Quote</button>
                    </form>
                    <h3>Send Message to Supplier</h3>
                    <form method="POST">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" required></textarea>
                        <button type="submit" name="send_message" class="btn">Send Message</button>
                    </form>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <p>Product not found.</p>
        <?php endif; ?>
    </div>
    <script>
        function redirect(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
