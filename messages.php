<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT m.*, u.username, p.title FROM messages m 
                       JOIN users u ON m.sender_id = u.id 
                       LEFT JOIN products p ON m.product_id = p.id 
                       WHERE m.receiver_id = ? OR m.sender_id = ? 
                       ORDER BY m.created_at DESC");
$stmt->execute([$_SESSION['user_id'], $_SESSION['user_id']]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Alibaba Clone</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; }
        header { background: #232f3e; color: white; padding: 20px; text-align: center; }
        .container { max-width: 800px; margin: 20px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .message { border-bottom: 1px solid #ccc; padding: 10px 0; }
        .message p { color: #555; }
        .btn { background: #febd69; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .btn:hover { background: #f3a847; }
    </style>
</head>
<body>
    <header>
        <h1>Messages</h1>
        <nav>
            <a href="#" onclick="redirect('index.php')">Home</a>
            <a href="#" onclick="redirect('products.php')">Products</a>
            <a href="#" onclick="redirect('profile.php')">Profile</a>
        </nav>
    </header>
    <div class="container">
        <h2>Your Messages</h2>
        <?php foreach ($messages as $message): ?>
            <div class="message">
                <p><strong>From:</strong> <?php echo htmlspecialchars($message['username']); ?></p>
                <p><strong>Product:</strong> <?php echo htmlspecialchars($message['title'] ?: 'N/A'); ?></p>
                <p><?php echo htmlspecialchars($message['message']); ?></p>
                <p><em><?php echo $message['created_at']; ?></em></p>
            </div>
        <?php endforeach; ?>
    </div>
    <script>
        function redirect(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
