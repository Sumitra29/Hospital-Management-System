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
/*universal selector*/
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/*sidebar*/
.sidebar {
    width: 250px;
    height: 100vh;
    background: #14243d;
    padding: 25px 15px;
    color: white;
    position: fixed;
    top: 0;
    left: 0;
    overflow-y: auto;
}
.sidebar a {
    display: block;
    text-decoration: none;
    color: #c5d1df;
    padding: 12px;
    margin-bottom: 5px;
    border-radius: 6px;
    transition: 0.3s;
}
.sidebar a:hover,
.sidebar a.active {
    background: #28517a;
    color: #ffffff;
}

/*logo*/
.logo {
   /* padding: 10px;*/
    padding: 0 10px;
    margin-top: -20px; /*-10px moves the logo section 10px upward*/
    margin-bottom: 35px;
    text-align: center;
}
.logo h2{
    font-size: 19px;
    color: #ffffff;
}
.logo small {
    color: #aebfd3;
    font-size: 18px;
}
.logo img{
    width: 50px;
    height: 50px;
    object-fit: contain;
    display: block;
    margin: 0 auto 5px;
}

/*menu-title*/
.menu-title {
    color: #c3c7ce;
    font-size: 12px;
    margin: 20px 10px 10px;
}

/*menu-toggle*/
.menu-toggle {
    width: 100%;
    display: flex;
    justify-content: space-between;
    padding: 12px;
    background: transparent;
    border: none;
    color: #c5d1df;
    font-size: 15px;
    cursor: pointer;
    border-radius: 6px;
}
.menu-toggle:hover {
    background: #28517a;
    color: #ffffff;
}

/*submenu*/
.submenu {
    display: none;
    padding-left: 15px;
}
.submenu.show {
    display: block;
}
.submenu a {
    font-size: 15px;
}

/*arrow*/
.arrow {
    width: 8px;
    height: 8px;
    border-right: 1.8px solid #aebfd3;
    border-bottom: 1.8px solid #aebfd3;
    transform: rotate(45deg);
    transition: transform 0.25s ease;
    margin-right: 5px;
    margin-top: -4px;
}
.menu-toggle.active .arrow {
    transform: rotate(225deg);
}

/*mobile responsiveness*/
/*mobile-menu-btn*/
.mobile-menu-btn {
    display: none;
    position: fixed;
    top: 15px;
    left: 15px;
    z-index: 1001;
    background: #14243d;
    color: white;
    border: none;
    padding: 10px 14px;
    font-size: 22px;
    border-radius: 6px;
    cursor: pointer;
}

/* Sidebar overlay */
.sidebar-overlay {
    display: none;
}

/* Mobile responsive */
@media screen and (max-width: 768px) {
    .mobile-menu-btn {
        display: block;
    }
    .sidebar {
        width: 260px;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        z-index: 1002;
    }
    .sidebar.open {
        transform: translateX(0);
    }
    .sidebar-overlay.show {
        display: block;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
    }
}
/*mobile responsiveness ends*/
</style>
<body>
    <!--mobile responsiveness--->
    <button class="mobile-menu-btn" id="mobileMenuBtn">☰</button>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <!--mobile responsiveness ends--->

    <aside class="sidebar">

<!---logo and name--->
        <div class="logo">
            <img src="images/hospital-logo.png" alt="Hospital Logo">
            <h2>Hospital</h2>
            <small>Management System</small>
        </div>

<!---dashboard-->
        <!--<a href="#" class="active">Dashboard</a>-->
<!--here it will be index.php ~ benefit i don't have to call the layout everytime it will directly be fetch from index.php--->
<!-- added here [page=page_name] so that main in index can call easily--->
<!--- ***note: here the page_name should be same without .php--->        
        <a href="index.php?page=dashboard" class="active">Dashboard</a>

<!---master--->
        <p class="menu-title"> Master </p>

<!--group--->
        <div class="menu-group">
            <button class="menu-toggle">
                <p>Master Management</p>
                <span class="arrow"></span>
            </button>
            <div class="submenu">
                <a href="index.php?page=staff">Staff Management</a>
                <a href="index.php?page=user">User Management</a>
                <a href="index.php?page=patient">Patient Management</a>
            </div><!--submenu div--->
        </div><!--menu-group div-->

<!---transactions--->
        <p class="menu-title"> Transactions </p>

<!--group--->
        <div class="menu-group">
            <button class="menu-toggle">
                <p>Transactions</p>
                <span class="arrow"></span>
            </button>
            <div class="submenu">
                <a href="index.php?page=patient_registration">Patient Registration</a>
                <a href="index.php?page=appointments">Appointments</a>
                <a href="index.php?page=billing">Billing</a>
            </div><!--submenu div--->
        </div><!--menu-group div-->

<!---reports--->
        <p class="menu-title"> Reports </p>

<!--group--->
        <div class="menu-group">
            <button class="menu-toggle">
                <p>Reports</p>
                <span class="arrow"></span>
            </button>
            <div class="submenu">
                <a href="index.php?page=patient_reports">Patient Reports</a>
                <a href="index.php?page=appointment_reports">Appointment Reports</a>
                <a href="index.php?page=billing_reports">Billing Reports</a>
            </div><!--submenu div--->
        </div><!--menu-group div-->

    </aside>
    
</body>
<!---using for loop--->
<script>
    const menuButtons = document.querySelectorAll(".menu-toggle");
    for (let i=0; i<menuButtons.length; i++) {
        menuButtons[i].addEventListener("click", function() {
            const subMenu = this.nextElementSibling;

            //to close other sub menu
            for (let j=0; j<menuButtons.length; j++) {
                if (menuButtons[j] !== this) {
                    menuButtons[j].classList.remove("active");
                    menuButtons[j].nextElementSibling.classList.remove("show");
                }
            }

            //toggle
            subMenu.classList.toggle("show");
            this.classList.toggle("active");
        })
    }
</script>

<!---using foreach --->
<!---
<script>
    const menuButtons = document.querySelectorAll(".menu-toggle");
    menuButtons.forEach(button => {
        button.addEventListener("click", function() {
            const subMenu = this.nextElementSibling;
            /*subMenu.classList.toggle("show");
            this.classList.toggle("active");*/

            //to close all other sub menu
            menuButtons.forEach(otherButton => {
                if (otherButton !== this) {
                    otherButton.classList.remove("active");
                    otherButton.nextElementSibling.classList.remove("show");
                }
            })

            //toggle
            subMenu.classList.toggle("show");
            this.classList.toggle("active");
        })
    })
</script>--->

<!--mobile responsiveness--->
<script>
    const mobileMenuBtn = document.getElementById("mobileMenuBtn");
    const sidebar = document.querySelector(".sidebar");
    const sidebarOverlay = document.getElementById("sidebarOverlay");

    //open-and-close-sidebar hamburger
    mobileMenuBtn.addEventListener("click", function() {
        sidebar.classList.toggle("open");
        sidebarOverlay.classList.toggle("show");
    })

    //close sidebar when clicking outside
    sidebarOverlay.addEventListener("click", function() {
        sidebar.classList.remove("open");
        sidebarOverlay.classList.remove("show");
    })

    //close sidebar after clicking a link
    const sidebarLinks = sidebar.querySelectorAll("a");
    for (let i=0; i<sidebarLinks.length; i++) {
        sidebarLinks[i].addEventListener("click", function() {
            sidebar.classList.remove("open");
            sidebarOverlay.classList.remove("show");
        })
    }
</script>
</html>
