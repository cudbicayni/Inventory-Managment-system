<?php
session_start();
$conn = new mysqli("localhost", "root", "", "invent");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$categories = $conn->query("SELECT * FROM categories") or die("Category Query Failed: " . $conn->error);
$items = $conn->query("SELECT * FROM items") or die("Items Query Failed: " . $conn->error);
$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>POS Display</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ✅ Bootstrap 4 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f9fafb;
            font-family: 'Segoe UI', sans-serif;
        }
        h3 {
            color: #4f46e5;
            font-weight: bold;
        }
        .category-btn {
            margin: 5px;
            border-radius: 25px;
            padding: 8px 20px;
        }
        .category-btn.active-category,
        .category-btn:hover {
            background-color: #6366f1;
            color: #fff;
        }
        .btn-cart {
            position: relative;
            background-color: #4f46e5;
            color: white;
            padding: 8px 20px;
            border-radius: 30px;
            font-weight: 600;
        }
        .btn-cart .badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #facc15;
            color: #1f2937;
            font-weight: bold;
            border-radius: 50%;
            font-size: 18px;
            padding: 6px 9px;
        }
        .product-card {
            border: 1px solid #e4e4e4;
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            background: #fdfdff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: 0.3s;
        }
        .product-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .product-card img {
            max-height: 180px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .plus-btn {
            background: #2196f3;
            color: white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            border: none;
            font-size: 20px;
            line-height: 40px;
        }
        .price {
            color: green;
            font-weight: bold;
        }
        .stock {
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body class="container mt-4">

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>
        <img src="https://cdn-icons-png.freepik.com/256/5705/5705161.png" alt="POS Icon" style="width:30px; height:35px;">
        POS Shop
    </h3>
    <a href="cart.php" class="btn btn-cart">
        <img src="https://cdn-icons-png.flaticon.com/32/1170/1170678.png" 
            alt="Cart Icon" 
            style="filter: brightness(0) invert(1); width: 32px; height: 32px;">
        Cart <span class="badge" id="cart-count"><?= $cartCount ?></span>
    </a>
</div>

<!-- Categories -->
<div class="d-flex flex-wrap mb-4">
    <?php while ($cat = $categories->fetch_assoc()): ?>
        <button class="btn btn-outline-primary category-btn"
                onclick="filterItems(<?= (int)$cat['cat_no'] ?>)"
                id="cat-<?= (int)$cat['cat_no'] ?>">
            <?= htmlspecialchars($cat['cat_name']) ?>
        </button>
    <?php endwhile; ?>
</div>

<!-- Product Grid -->
<div class="row" id="product-grid">
    <?php while ($row = $items->fetch_assoc()): ?>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 item" data-category="<?= (int)$row['cat_no'] ?>">
            <div class="product-card">
                <img src="<?= htmlspecialchars($row['image']) ?>" class="img-fluid" alt="<?= htmlspecialchars($row['item_name']) ?>">
                <h5 class="mb-1"><?= htmlspecialchars($row['item_name']) ?></h5>
                <div class="price">$<?= number_format($row['Price'], 2) ?></div>
                <div class="stock">Stock: <?= (int)$row['balance'] ?> pcs</div>
                <div class="mt-2">
                    <button class="plus-btn" onclick="addToCart(<?= (int)$row['item_no'] ?>)">+</button>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<!-- Scripts -->
<script>
function filterItems(categoryId) {
    document.querySelectorAll('.item').forEach(item => {
        item.style.display = item.getAttribute('data-category') == categoryId ? 'block' : 'none';
    });
    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.classList.remove('active-category');
    });
    document.getElementById('cat-' + categoryId).classList.add('active-category');
}

function addToCart(itemId) {
    fetch('add_to_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'item_id=' + itemId
    })
    .then(response => response.text())
    .then(cartCount => {
        document.getElementById('cart-count').innerText = cartCount;
    })
    .catch(err => console.error('Error adding to cart:', err));
}
</script>

<!-- ✅ Bootstrap 4 JS dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
