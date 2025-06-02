<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Alibaba Clone</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; }
        header { background: #232f3e; color: white; padding: 20px; text-align: center; }
        .container { max-width: 800px; margin: 20px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h2 { color: #232f3e; }
        p { color: #555; }
        .btn { background: #febd69; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .btn:hover { background: #f3a847; }
    </style>
</head>
<body>
    <header>
        <h1>Profile</h1>
        <nav>
            <a href="#" onclick="redirect('index.php')">Home</a>
            <a href="#" onclick="redirect('products.php')">Products</a>
            <a href="#" onclick="redirect('logout.php')">Logout</a>
        </nav>
    </header>
    <div class="container">
        <h2><?php echo htmlspecialchars($user['username']); ?>'s Profile</h2>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
        <p>User Type: <?php echo htmlspecialchars($user['user_type']); ?></p>
        <p>Company Name: <?php echo htmlspecialchars($user['company_name']); ?></p>
        <?php if ($_SESSION['user_type'] === 'supplier'): ?>
            <button class="btn" onclick="redirect('add_product.php')">Add New Product</button>
        <?php endif; ?>
    </div>
    <script>
        function redirect(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
