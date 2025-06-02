<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $user_type = $_POST['user_type'];
    $company_name = $_POST['company_name'];

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, user_type, company_name) VALUES (?, ?, ?, ?, ?)");
    try {
        $stmt->execute([$username, $email, $password, $user_type, $company_name]);
        header("Location: login.php");
        exit;
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Alibaba Clone</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 400px; }
        h2 { text-align: center; color: #232f3e; }
        label { display: block; margin: 10px 0 5px; color: #555; }
        input, select { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px; }
        .btn { background: #febd69; padding: 10px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-weight: bold; }
        .btn:hover { background: #f3a847; }
        .error { color: red; text-align: center; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Signup</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <label for="user_type">User Type</label>
            <select id="user_type" name="user_type" required>
                <option value="buyer">Buyer</option>
                <option value="supplier">Supplier</option>
            </select>
            <label for="company_name">Company Name</label>
            <input type="text" id="company_name" name="company_name" required>
            <button type="submit" class="btn">Signup</button>
        </form>
        <p>Already have an account? <a href="#" onclick="redirect('login.php')">Login</a></p>
    </div>
    <script>
        function redirect(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
