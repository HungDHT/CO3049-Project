<?php
// Determine which page to load based on the query parameter
$page = isset($_GET['page']) ? $_GET['page'] : 'home'; // Default to 'home'

switch ($page) {
    case 'products':
        $pageTitle = "Products Page";
        $pageFile = "products.php";
        break;
    case 'category1':
        $pageTitle = "Category1 Page";
        $pageFile = "category1.php";
        break;
    case 'category2':
        $pageTitle = "Category2 Page";
        $pageFile = "category2.php";
        break;    
    case 'category3':
        $pageTitle = "Category3 Page";
        $pageFile = "category3.php";
        break;
    case 'login':
        $pageTitle = "Login Page";
        $pageFile = "login.php";
        break;
    case 'register':
        $pageTitle = "Register Page";
        $pageFile = "register.php";
        break;
    case 'contact' :
        $pageTitle = "Contact Page";
        $pageFile = "contact.php";
        break;
    case 'home':
    default:
        $pageTitle = "Home Page";
        $pageFile = "home.php";
        break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
    /* Show the dropdown menu on hover */
    .nav-item.dropdown:hover .dropdown-menu {
        display: block;
    }
</style>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand">My Website</span>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page === 'home' ? 'active' : ''; ?>" href="index.php?page=home">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo $page === 'products' ? 'active' : ''; ?>" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Products
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="index.php?page=products">All Products</a></li>
                            <li><a class="dropdown-item" href="index.php?page=category1">Category 1</a></li>
                            <li><a class="dropdown-item" href="index.php?page=category2">Category 2</a></li>
                            <li><a class="dropdown-item" href="index.php?page=category3">Category 3</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page === 'login' ? 'active' : ''; ?>" href="index.php?page=login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page === 'register' ? 'active' : ''; ?>" href="index.php?page=register">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page === 'contact' ? 'active' : ''; ?>" href="index.php?page=contact">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
    
    <main class="container" style="min-height: 425px;">
        <?php include($pageFile); // Include the content based on the page ?>
    </main>
    
    <footer class="bg-dark text-white p-3 mt-5">
        <p>&copy; 2024 HCMUT Website.</p>
    </footer>
</body>
</html>