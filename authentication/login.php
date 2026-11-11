<?php
require_once __DIR__ . "/../config.php";
error_reporting(E_ALL);
ini_set("display_errors", 1);

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    /*find username or email */
    $sql = "SELECT id, username, email, password, role, status
            FROM users WHERE username = ? OR email = ? LIMIT 1";

    $stmt = $connection->prepare($sql); //prepares a SQL statement
    $stmt->bind_param("ss", $username, $username); /*why 2 username WHERE username = 'admin' OR email = 'admin' it checks twice*/
    $stmt->execute(); //executes the query

    $result = $stmt->get_result(); //retrieves query results

    if ($result->num_rows == 1) { /*counts returned records */
        $user = $result->fetch_assoc(); //converts a database row into an associative array
        if($user['status'] === 'active' && password_verify($password, $user['password'])) {

        //regerate session id
        session_regenerate_id(true);

        //store user information
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        //redirect to main dashboard [[index.php]] (imp)
        header("Location: ../index.php");
        exit;

        } else {
            $error = "Invalid username/email or password";
        }

    } else {
        $error = "Invalid username/email or password";
    }

    $stmt->close();
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
/* universal selector */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    min-height: 100vh;
    font-family: Arial, sans-serif;
    background: #1d2633;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

/* login-form-container (login main container) */
#login-form-container {
    width: 100%;
    max-width: 500px;
    min-height: 500px;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.12);
}

/* Login section */
.name-logo {
    width: 100%;
    padding: 45px;
}

/* Header and logo */
.header-title {
    text-align: center;
    margin-bottom: 35px;
}

.logo {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}
.logo img {
    width: 65px;
    height: 65px;
    object-fit: contain;
}
.logo h2 {
    color: #14243d;
    font-size: 25px;
}

/* Login form */
.login-form {
    width: 100%;
    max-width: 400px;
    margin: auto;
}

.form-group {
    margin-bottom: 20px;
}
.form-group label {
    display: block;
    color: #333;
    font-weight: 600;
    margin-bottom: 8px;
    text-align: left;
}
.form-group input {
    width: 100%;
    padding: 13px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 15px;
    outline: none;
}
.form-group input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

/* Show password */
.show-password {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 18px;
    font-size: 14px;
}
.show-password input {
    cursor: pointer;
}
.show-password label {
    cursor: pointer;
    color: #555;
}

/* Forgot password */
.forgot-password {
    text-align: right;
    margin-bottom: 22px;
}
.forgot-password a {
    color: #2563eb;
    text-decoration: none;
    font-size: 14px;
}
.forgot-password a:hover {
    text-decoration: underline;
}

/* Login button */
.login-btn {
    width: 100%;
    padding: 14px;
    background: #14243d;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s;
}
.login-btn:hover {
    background: #243f65;
}
/*login-error*/
.login-error {
    color: #dc2626;
    background: #fee2e2;
    padding: 12px;
    border-radius: 5px;
    margin-bottom: 15px;
    text-align: center;
}

/* Tablet */
@media screen and (max-width: 768px) {
    #login-form-container {
        max-width: 520px;
        min-height: auto;
    }
    .name-logo {
        padding: 35px 25px;
    }
    .logo h2 {
        font-size: 21px;
    }
}

/* Mobile */
@media screen and (max-width: 480px) {
    body {
        padding: 15px;
    }
    #login-form-container {
        border-radius: 9px;
    }
    .name-logo {
        padding: 30px 20px;
    }
    .header-title {
        margin-bottom: 28px;
    }
    .logo {
        flex-direction: column;
        gap: 10px;
    }
    .logo img {
        width: 55px;
        height: 55px;
    }
    .logo h2 {
        font-size: 19px;
        text-align: center;
    }
    .form-group input {
        padding: 12px;
        font-size: 16px;
    }
    .login-btn {
        padding: 13px;
    }
}
</style>
<body>
<div id="login-form-container">
<div class="name-logo">
     <!--header title--->
        <header class="header-title">
            <!-- Logo and name -->
        <div class="logo">
            <img src="../images/hospital-logo.png" alt="Hospital Logo">
            <div>
                <h2>Hospital Management System</h2>
            </div>
        </div>
        </header>

<div class="login-form">
    <?php
    if ($error != "") {?>
        <p class="login-error">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php }?>
    <form method="POST">

        <div class="form-group">
            <label>Username or Email</label>
            <input type="text" name="username" placeholder="Enter Username or Email" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter Password" required>
        </div>

        <div class="show-password">
            <input type="checkbox" id="showPassword">
            <label for="showPassword">Show Password</label>
        </div>
        
        <div class="forgot-password">
            <a href="forgot_password.php">Forgot Password?</a>
        </div>
        
        <button type="submit" class="login-btn">Login</button>
    
    </form>
</div><!---login-form </div>--->
</div><!---name-logo </div>--->
</div><!---login-form-container </div>--->

</body>
<script>
    const showPwd = document.getElementById("showPassword");
    const pwd = document.querySelector('input[name="password"]');

    showPwd.addEventListener("change", function() {
        pwd.type = this.checked ? "text" : "password";
    })
</script>
</html>