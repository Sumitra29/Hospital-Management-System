<?php
include "config.php";
error_reporting(E_ALL);
ini_set("display_errors", 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <title>Hospital Management System</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">  
</head>
<style>
/* Universal selector */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Header content */
.header-content {
    margin-left: 250px;
    width: calc(100% - 250px);
}

/* Header */
.header-title {
    height: 110px;
    background: #14243d;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 25px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.08);
}

/* Header heading */
.header-title h2 {
    font-size: 25px;
    color: #ffffff;
}

/* Header right */
.header-right {
    display: flex;
    align-items: center;
    gap: 20px;
}

/* Profile */
.header-profile {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #ffffff;
    cursor: pointer;
    position: relative;
}
/*profile-dropdown*/
/* Profile button */
.profile-btn {
    background: transparent;
    border: none;
    color: #ffffff;
    font-size: 16px;
    cursor: pointer;
    padding: 10px;
}
/* Dropdown */
.profile-dropdown {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    background: #14243d;
    min-width: 180px;
    border-radius: 5px;
    box-shadow: 0 4px 10px rgb(219, 206, 206);
    z-index: 1000;
}
.profile-dropdown a {
    display: block;
    padding: 12px;
    color: white;
    text-decoration: none;
}
.profile-dropdown a:hover {
    background: #ffffff;
    color: black;
}
.profile-dropdown.show {
    display: block;
}

/* Logo and name */
.header-title .logo {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
    padding: 0;
    text-align: left;
}

.header-title .logo img {
    width: 45px;
    height: 45px;
    object-fit: contain;
    margin: 0;
}

/* Mobile responsive */
@media screen and (max-width: 768px) {
    .header-content {
        margin-left: 0;
        width: 100%;
    }
    .header-title {
        padding: 0 15px 0 65px;
    }
    /* Header heading */
    .header-title h2 {
    font-size: 18px;
    }
}
</style>
<body>
    <div class="header-content">
        
    <!--header title--->
        <header class="header-title">
            <!-- Logo and name -->
        <div class="logo">
            <img src="images/hospital-logo.png" alt="Hospital Logo">
            <div>
                <h2>Hospital Management System</h2>
            </div>
        </div>

            <div class="header-right">

                <!---header profile---
                <div class="header-profile">
                    <span>Profile</span>
                </div>--->
                <div class="header-profile">
                    <button type="button" class="profile-btn" id="profileBtn">
                        <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>
                        &#9662; <!---downward-pointing triangle (▼)-->
                    </button>

                <div class="profile-dropdown" id="profileDropdown">
                    <a href="#">
                        <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>
                    </a>

                    <a href="authentication/logout.php">Logout</a>
                </div>

                </div> <!---header-profile---> 

            </div><!---header-right </div>--->

    </div><!--header-title </div>--->
</header>
</body>
<script>
    const profileButton = document.getElementById("profileBtn");
    const profileDrpdwn = document.getElementById("profileDropdown");

    profileButton.addEventListener("click", function() {
        profileDrpdwn.classList.toggle("show"); /*classList has const profileDrpdwn*/
    })

    //close dropdown when clicking outside
    document.addEventListener("click", function(event) {
        if (!profileBtn.contains(event.target) && !profileDrpdwn.contains(event.target)) {
            profileDrpdwn.classList.remove("show");
        }
    })
</script>
</html>