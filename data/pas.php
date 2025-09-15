<?php
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
    <style>
        body {
            background-color: #f9fafb;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }
        h3 {
            color: #4f46e5;
            font-weight: bold;
        }
        .category-btn {
            margin: 5px;
            border-radius: 25px;
            padding: 8px 20px;
            border: 1px solid #6366f1;
            background: white;
            cursor: pointer;
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
            text-decoration: none;
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
            width: 100%;
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
            cursor: pointer;
        }
        .plus-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        .price {
            color: green;
            font-weight: bold;
        }
        .stock {
            font-size: 14px;
            color: #777;
        }
        .stock .alert {
            color: red;
            font-weight: bold;
            margin-top: 5px;
        }
        /* ✅ Product Grid: 5 per row */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
        }
        @media (max-width: 1200px) {
            .product-grid { grid-template-columns: repeat(4, 1fr); }
        }
        @media (max-width: 992px) {
            .product-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 768px) {
            .product-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 480px) {
            .product-grid { grid-template-columns: 1fr; }
        }
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }
        .d-flex { display: flex; }
        .justify-content-between { justify-content: space-between; }
        .align-items-center { align-items: center; }
        .mb-4 { margin-bottom: 1.5rem; }
        .mt-4 { margin-top: 1.5rem; }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>
            <img src="https://cdn-icons-png.freepik.com/256/5705/5705161.png" alt="POS Icon" style="width:30px; height:35px;">
            POS Shop
        </h3>
        <a href="cart.php" class="btn-cart">
            <img src="https://cdn-icons-png.flaticon.com/32/1170/1170678.png" 
                alt="Cart Icon" 
                style="filter: brightness(0) invert(1); width: 32px; height: 32px;">
            Cart <span class="badge" id="cart-count"><?= $cartCount ?></span>
        </a>
     
    </div>

    <!-- 🔘 Categories -->
    <div class="d-flex flex-wrap mb-4">
        <?php while ($cat = $categories->fetch_assoc()): ?>
            <button class="category-btn"
                    onclick="filterItems(<?= (int)$cat['cat_no'] ?>)"
                    id="cat-<?= (int)$cat['cat_no'] ?>">
                <?= htmlspecialchars($cat['cat_name']) ?>
            </button>
        <?php endwhile; ?>
    </div>

    <!-- ✅ Product Grid -->
    <div class="product-grid" id="product-grid">
    <?php while ($row = $items->fetch_assoc()): ?>
        <?php
            $item_no   = (int)$row['item_no'];
            $stock     = (int)$row['balance'];
            $in_cart   = isset($_SESSION['cart'][$item_no]) ? (int)$_SESSION['cart'][$item_no] : 0;
            $disabled  = ($stock <= $in_cart) ? 'disabled' : ''; // ✅ disable if no stock left
        ?>
        <div class="item" data-category="<?= (int)$row['cat_no'] ?>">
            <div class="product-card">
                <img src="<?= htmlspecialchars($row['image']) ?>" class="img-fluid" alt="<?= htmlspecialchars($row['item_name']) ?>">
                <h5 class="mb-1"><?= htmlspecialchars($row['item_name']) ?></h5>
                <div class="price">$<?= number_format($row['Price'], 2) ?></div>
                <div class="stock">
                    Stock: <?= $stock ?> pcs
                    <?php if ($stock < 2): ?>
                        <div class="alert">⚠️ Almost End!</div>
                    <?php endif; ?>
                </div>
                <div class="mt-2">
                    <button class="plus-btn"
                            onclick="addToCart(<?= $item_no ?>)"
                            <?= $disabled ?>>
                        +
                    </button>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

</div>

<!-- 🧠 Scripts -->
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
    fetch('data/add_to_cart.php', {
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

// ✅ Live low stock check (every 10 seconds)



</script>

</body>
</html>
<?php $conn->close(); ?>