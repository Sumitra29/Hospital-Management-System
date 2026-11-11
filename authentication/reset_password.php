
<?php
require_once __DIR__ . "/../config.php";

error_reporting(E_ALL);
ini_set("display_errors", 1);

$message = "";
$validToken = false;
$user = null;

// Get token from URL or form submission
$token = $_GET['token'] ?? $_POST['token'] ?? '';

// Check whether token exists
if (!empty($token) && preg_match('/^[a-f0-9]{64}$/', $token)) {

    // Hash the received token
    $tokenHash = hash('sha256', $token);

    // Check token and expiry
    $sql = "SELECT id FROM users
            WHERE reset_token_hash = ?
            AND reset_token_expires > NOW()
            AND status = 'active'
            LIMIT 1";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $tokenHash);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $validToken = true;
    } else {
        $message = "Invalid or expired password reset link.";
    }

    $stmt->close();

} else {
    $message = "Invalid or missing reset token.";
}


// Update password after form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && $validToken) {

    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (strlen($password) < 8) {
        $message = "Password must contain at least 8 characters.";

    } elseif ($password !== $confirmPassword) {
        $message = "Passwords do not match.";

    } else {

        // Hash the new password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Update password and clear reset token
        $update = $connection->prepare(
            "UPDATE users
            SET password = ?,
                reset_token_hash = NULL,
                reset_token_expires = NULL
            WHERE id = ?"
        );

        $update->bind_param("si", $hashedPassword, $user['id']);

        if ($update->execute() && $update->affected_rows === 1) {

            $message = "Password updated successfully. You can now log in.";

            // Token cannot be reused
            $validToken = false;

        } else {
            $message = "Unable to update password. Please try again.";
        }

        $update->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <title>Hospital Management System</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">  
</head>
<body>

    <h2>Reset Password</h2>

    <?php if ($validToken) { ?>

        <form method="POST">

            <input type="hidden" name="token"
                   value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">

            <label>New Password</label>
            <input type="password" name="password"
                   placeholder="Enter New Password" required minlength="8">

            <br><br>

            <label>Confirm Password</label>
            <input type="password" name="confirm_password"
                   placeholder="Confirm New Password" required minlength="8">

            <br><br>

            <button type="submit">Update Password</button>

        </form>

    <?php } ?>

    <?php if ($message !== "") { ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php } ?>

    <a href="authentication/login.php">Back to Login</a>

</body>
</html>
