<?php
require_once __DIR__ . "/../config.php";

error_reporting(E_ALL);
ini_set("display_errors", 1);

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please Enter a valid Email Address";
    } else {
        $sql = "SELECT id, email FROM users WHERE email = ? AND status = 'active' LIMIT 1";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        /*$result = $stmt->get_result();*/
        //temporary adding start
        // Get database result
        $result = $stmt->get_result();
        echo "Email submitted: " . htmlspecialchars($email);
        echo "<br>Matching accounts: " . $result->num_rows;
        //temporary adding ends

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            //generate a secure random token
            $token = bin2hex(random_bytes(32));

            //store only the token hash in the database
            $tokenHash = hash('sha256', $token);

            //token expires after 15 minutes
            $expiry = date("Y-m-d H:i:s", time() + 900);

            $update = $connection->prepare(
                "UPDATE users
                SET reset_token_hash = ?, reset_token_expires = ?
                WHERE id = ?"
            );

            $update->bind_param("ssi", $tokenHash, $expiry, $user['id']);
            /*$update->execute();*/
//temporary add start
if ($update->execute()) {
    echo "<br>Token saved successfully";
} else {
    die("Database update failed: " . $update->error);
}
//temporary add ends

            //create reset link [[here reset_password.php]]
            $resetLink = $base_url . "/authentication/reset_password.php?token=" . urlencode($token);
            
            $subject = "Hospital Management System - Password Reset";

            $body = "
                <h2>Password Reset Request</h2>
                <p>You requested to reset your password</p>
                <p>Click the link below to create a new password:</p>
                <a href='$resetLink'>Reset Password</a>
                <p>This link expires in 15 minutes</p>
                <p>If you did not request this, please ignore this email or change your password</p>
                ";

                $header = "From: $sender_name <$sender_email>\r\n";
                $header .= "MIME-Version: 1.0\r\n";
                $header .= "Content-Type: text/html; charset=UTF-8\r\n";

                if (mail($email, $subject, $body, $header)) {
                    $message = "If an active account exists with that email, a password reset link has been sent.";
                } else {
                    //clear token if email sending fails
                    $clear = $connection->prepare(
                        "UPDATE users SET reset_token_hash = NULL,
                                          reset_token_expires = NULL WHERE id = ?"
                    );

                    $clear->bind_param("i", $user['id']);
                    $clear->execute();

                    $message = "Unable to send the email. Please try again later.";
                }

                $update->close();

        } else {
            //do not reveal whether account exists
            $message = "If an active account exists with that email, a password reset link has been sent.";
        }

        $stmt->close();

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
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #1d2633;
    font-family: Arial, sans-serif;
}

.forgot-container {
    width: 100%;
    max-width: 420px;
    background: #ffffff;
    padding: 35px;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.25);
}

.forgot-container h2 {
    text-align: center;
    color: #14243d;
    margin-bottom: 25px;
    font-size: 24px;
}

.forgot-container label {
    display: block;
    margin-bottom: 8px;
    color: #333333;
    font-weight: 600;
}

.forgot-container input {
    width: 100%;
    padding: 12px;
    border: 1px solid #cccccc;
    border-radius: 5px;
    font-size: 15px;
    margin-bottom: 20px;
    outline: none;
}

.forgot-container input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 4px rgba(37, 99, 235, 0.3);
}

.forgot-container button {
    width: 100%;
    padding: 12px;
    background: #14243d;
    color: #ffffff;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.forgot-container button:hover {
    background: #2563eb;
}

.forgot-message {
    margin-top: 18px;
    text-align: center;
    color: #333333;
    font-size: 14px;
    line-height: 1.5;
}

.forgot-container a {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: #2563eb;
    text-decoration: none;
    font-size: 14px;
}

.forgot-container a:hover {
    text-decoration: underline;
}

@media screen and (max-width: 480px) {
    .forgot-container {
        width: calc(100% - 30px);
        padding: 25px 20px;
    }
}
</style>
<body>
<div class="forgot-container">

<!---/*title*/--->
<h2>Forgot Password</h2>

<!---/*forgot password form */--->
<form method="POST">
    <label>Enter Registered Email</label>
    <input type="email" name="email" placeholder="Enter Your Email" required>
    
    <button type="submit">Send Reset Link</button>
</form>

<?php if ($message != "") { ?>
    <p> <?php echo htmlspecialchars($message); ?></p>
<?php } ?>

<a href = "login.php">Back to Login<a>

</div><!---forgot-container </div>--->
</body>
</html>