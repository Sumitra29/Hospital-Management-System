<?php 
include "config.php"; 
require_once "authentication/auth.php";
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
    html, body {
    min-height: 100%;
}

body {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/*main plays a big role*/
main {
    margin-left: 250px;
    width: calc(100% - 250px);
    flex: 1;
    padding: 25px;
}
/* Mobile */
@media screen and (max-width: 768px) {
    main {
        margin-left: 0;
        width: 100%;
    }
}
</style>
<body>

    <?php include "sidebar.php"; ?>
    <?php include "header.php"; ?>
<!--main plays a big role--->
    <!---<main>
        <h2>Dashboard</h2>
        <!-- Your content goes here --
    </main>--->
    <main>
        <?php
            $page = $_GET['page'] ?? 'dashboard';
/*here pages/ is folder name while $page (page_name) is calling from sidebar*/
/*benefit of using this that i don't have to call the layout in all pages each time*/ 
            $file = "pages/" . $page . ".php";
            if (file_exists($file)) {
                include $file;
            } else {
                echo "Page Not Found";
            }
        ?>
    </main>

    <?php include "footer.php"; ?>

</body>
</html>