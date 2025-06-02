<?php
session_start();
require_once 'db.php';

$category = $_GET['category'] ?? '';
$min_price = $_GET['min_price'] ?? 0;
$max_price = $_GET['max_price'] ?? 10000;

$query = "SELECT * FROM products WHERE price >= ? AND price <= ?";
$params = [$min_price, $max_price];

if ($category) {
    $query .= " AND category = ?";
    $params[] = $category;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Alibaba Clone</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; }
        header { background: #232f3e; color: white; padding: 20px; text-align: center; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .filter-form { background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
        .filter-form label { margin-right: 10px; color: #555; }
        .filter-form select, .filter-form input { padding: 8px; border: 1px solid #ccc; border-radius: 5px; }
        .products { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .product-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center; }
        .product-card img { max-width: 100%; height: auto; border-radius: 5px; }
        .btn { background: #febd69; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .btn:hover { background: #f3a847; }
    </style>
</head>
<body>
    <header>
        <h1>Products</h1>
        <nav>
            <a href="#" onclick="redirect('index.php')">Home</a>
            <a href="#" onclick="redirect('profile.php')">Profile</a>
            <a href="#" onclick="redirect('logout.php')">Logout</a>
        </nav>
    </header>
    <div class="container">
        <form class="filter-form" method="GET">
            <label for="category">Category</label>
            <select id="category" name="category">
                <option value="">All</option>
                <option value="electronics">Electronics</option>
                <option value="clothing">Clothing</option>
                <option value="machinery">Machinery</option>
            </select>
            <label for="min_price">Min Price</label>
            <input type="number" id="min_price" name="min_price" value="<?php echo $min_price; ?>">
            <label for="max_price">Max Price</label>
            <input type="number" id="max_price" name="max_price" value="<?php echo $max_price; ?>">
            <button type="submit" class="btn">Filter</button>
        </form>
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
    <script>
        function redirect(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
