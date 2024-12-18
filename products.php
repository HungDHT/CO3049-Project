<?php
// Database connection parameters
include("connection.php");

// Handle autocomplete request
if (isset($_GET['term'])) {
    $searchTerm = trim($_GET['term']);
    
    // Query to find products starting with the search term
    $query = "SELECT name FROM products WHERE name ILIKE $1 LIMIT 10";
    $result = pg_query_params($conn, $query, array($searchTerm . '%'));
    
    $suggestions = [];
    while ($row = pg_fetch_assoc($result)) {
        $suggestions[] = $row['name'];
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($suggestions);
    pg_close($conn);
    exit;
}

// Regular product search logic
$searchQuery = '';
$products = [];

if (isset($_GET['search'])) {
    $searchQuery = trim($_GET['search']);
}

$query = "SELECT id, name, price FROM products";
if ($searchQuery) {
    $query .= " WHERE name ILIKE $1";
    $result = pg_query_params($conn, $query, array('%' . $searchQuery . '%'));
} else {
    $result = pg_query($conn, $query);
}

while ($row = pg_fetch_assoc($result)) {
    $products[] = $row;
}

pg_close($conn);

// Check if it's an AJAX request
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

if ($isAjax) {
    // If it's an AJAX request, return only the product table
    echo '<table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>';
    foreach ($products as $row) {
        echo '<tr>
                <td>' . htmlspecialchars($row['id']) . '</td>
                <td>' . htmlspecialchars($row['name']) . '</td>
                <td>' . htmlspecialchars($row['price']) . '</td>
              </tr>';
    }
    echo '</tbody></table>';
    
    if (count($products) === 0) {
        echo '<div class="alert alert-warning" role="alert">
                No products found matching your search criteria.
              </div>';
    }
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-4">
    <h2>Products</h2>

    <!-- Search form with autocomplete -->
    <form id="searchForm" class="mb-3">
        <div class="input-group">
            <input type="text" id="searchInput" class="form-control" 
                   placeholder="Search for products..." 
                   value="<?php echo htmlspecialchars($searchQuery); ?>">
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>

    <!-- Product results container -->
    <div id="productResults">
        <?php if (count($products) > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['price']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning" role="alert">
                No products found matching your search criteria.
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
$(document).ready(function() {
    // Autocomplete functionality
    $("#searchInput").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'products.php',
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 1 // Start suggesting after 1 character
    });

    // Form submission
    $("#searchForm").on('submit', function(e) {
        e.preventDefault();
        var searchQuery = $("#searchInput").val();
        
        $.ajax({
            url: 'products.php',
            method: 'GET',
            data: { search: searchQuery },
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            },
            success: function(response) {
                // Update only the product results
                $("#productResults").html(response);
            },
            error: function(xhr, status, error) {
                console.error("Search error:", error);
                $("#productResults").html('<div class="alert alert-danger">Error performing search</div>');
            }
        });
    });
});
</script>
</body>
</html>