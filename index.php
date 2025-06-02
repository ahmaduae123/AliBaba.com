<?php
session_start();
require_once 'db.php';

$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 6");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alibaba Clone - Wholesale Marketplace</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; }
        header { background: #232f3e; color: white; padding: 20px; text-align: center; }
        header h1 { margin: 0; font-size: 36px; }
        nav { background: #febd69; padding: 10px; }
        nav a { color: #232f3e; text-decoration: none; margin: 0 15px; font-weight: bold; }
        nav a:hover { color: #131921; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .products { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .product-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center; }
        .product-card img { max-width: 100%; height: auto; border-radius: 5px; }
        .product-card h3 { margin: 10px 0; color: #232f3e; }
        .product-card p { color: #555; }
        .btn { background: #febd69; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .btn:hover { background: #f3a847; }
        footer { background: #232f3e; color: white; text-align: center; padding: 10px; margin-top: 20px; }
    </style>
</head>
<body>
    <header>
        <h1>Alibaba Clone</h1>
        <nav>
            <a href="#" onclick="redirect('index.php')">Home</a>
            <a href="#" onclick="redirect('products.php')">Products</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="#" onclick="redirect('profile.php')">Profile</a>
                <a href="#" onclick="redirect('logout.php')">Logout</a>
            <?php else: ?>
                <a href="#" onclick="redirect('login.php')">Login</a>
                <a href="#" onclick="redirect('signup.php')">Signup</a>
            <?php endif; ?>
        </nav>
    </header>
    <div class="container">
        <h2>Trending Products</h2>
        <div class="products">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($product['image'] ?: 'placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                    <h3><?php echo htmlspecialchars($product['title']); ?></h3>
                    <p>Price: $<?php echo htmlspecialchars($product['price']); ?> | MOQ: <?php echo htmlspecialchars($product['moq']); ?></p>
                    <button class="btn" onclick="redirect('product.php?id=<?php echo $product['id']; ?>')">View Details</button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <footer>
        <p>&copy; 2025 Alibaba Clone. All rights reserved.</p>
    </footer>
    <script>
        function redirect(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
