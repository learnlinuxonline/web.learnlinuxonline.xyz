<?php
// Load environment variables from .env
$env = parse_ini_file(__DIR__.'/.env');

DB_HOST=
DB_PORT=5432
DB_NAME=sqllearnlinuxonline
DB_USER=
DB_PASS=

// Connect to PostgreSQL
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password sslmode=require");

if (!$conn) {
    die("❌ Database connection failed: " . pg_last_error());
}

// Handle form submission
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password_plain = $_POST['password'];
    $country = $_POST['country'];

    // Hash password for security
    $password_hashed = password_hash($password_plain, PASSWORD_BCRYPT);

    // Insert into database
    $query = "INSERT INTO users (full_name, email, password, country) VALUES ($1, $2, $3, $4)";
    $result = pg_query_params($conn, $query, array($full_name, $email, $password_hashed, $country));

    if ($result) {
        $message = "✅ Registration successful!";
    } else {
        $message = "❌ Error: " . pg_last_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Registration</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f2f2f2; display: flex; height: 100vh; justify-content: center; align-items: center; }
    .form-container { background: #fff; padding: 20px 30px; border-radius: 10px; box-shadow: 0px 4px 6px rgba(0,0,0,0.2); width: 400px; }
    h2 { text-align: center; color: #333; }
    input, select, button { width: 100%; padding: 10px; margin: 8px 0; border-radius: 5px; border: 1px solid #ccc; }
    button { background: #28a745; color: #fff; font-weight: bold; border: none; cursor: pointer; }
    button:hover { background: #218838; }
    .message { text-align: center; font-weight: bold; margin-top: 10px; color: #d9534f; }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Register</h2>
    <form method="POST">
      <input type="text" name="full_name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email Address" required>
      <input type="password" name="password" placeholder="Password" required>
      <select name="country" required>
        <option value="">Select Country</option>
        <option value="Bangladesh">Bangladesh</option>
        <option value="India">India</option>
        <option value="Pakistan">Pakistan</option>
        <option value="USA">USA</option>
        <option value="UK">UK</option>
      </select>
      <button type="submit">Register</button>
    </form>
    <div class="message"><?php echo $message; ?></div>
  </div>
</body>
</html>
