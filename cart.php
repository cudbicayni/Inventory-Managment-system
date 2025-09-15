<?php
session_start();

// Connect to database
$conn = new mysqli("localhost", "root", "", "invent");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];
$items = [];

if (!empty($cart)) {
    $ids = implode(',', array_keys($cart));
    $sql = "SELECT * FROM items WHERE item_no IN ($ids)";
    $result = $conn->query($sql);
$sql1 = "
    SELECT i.*, c.cat_name AS cat_name
    FROM items i
    LEFT JOIN categories c ON c.cat_no = i.cat_no
    WHERE i.item_no IN ($ids)
";
$result = $conn->query($sql1);


    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }
}


// Join categories to get cat_name per item

// Fetch customers for combo box
$customer = $conn->query("SELECT per_no, name FROM people  ");
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Cart</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f4f4f4; 
            margin: 0; 
            padding: 0; 
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }
        .main-wrapper {
            max-width: 900px;
            width: 100%;
            padding: 30px 20px;
            box-sizing: border-box;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .cart-container { 
            background: #fff; 
            border-radius: 8px; 
            padding: 20px; 
            margin-bottom: 20px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.1); 
        }
        .store-header { 
            font-weight: bold; 
            font-size: 16px; 
            margin-bottom: 5px; 
            display: flex; 
            align-items: center; 
        }
        .store-header input { margin-right: 8px; }
        .shipping-msg { 
            font-size: 12px; 
            color: #666; 
            margin-bottom: 10px; 
        }
        .cart-item { 
            display: flex; 
            align-items: flex-start; 
            padding: 12px 0; 
            border-bottom: 1px solid #eee; 
        }
        .cart-item:last-child { border-bottom: none; }
        .cart-item input[type=checkbox] { 
            margin-right: 12px; 
            margin-top: 45px; 
        }
        .item-img { 
            width: 150px; 
            height: 150px; 
            object-fit: cover; 
            border-radius: 6px; 
            margin-right: 18px; 
        }
        .item-details { flex: 1; }
        .item-title { 
            font-weight: bold; 
            font-size: 17px; 
            margin-bottom: 6px; 
        }
        .item-meta { 
            font-size: 14px; 
            color: #666; 
            margin-bottom: 10px; 
        }
        .price { 
            color: #E63946; 
            font-weight: bold; 
            font-size: 18px; 
            margin-right: 8px; 
        }
        .old-price { 
            text-decoration: line-through; 
            color: #999; 
            font-size: 13px; 
        }
        .item-actions {
            display: flex;
            gap: 15px;
            align-items: center;
            margin-top: 12px;
        }
        .item-actions form {
            margin: 0;
        }
        select {
            padding: 6px 10px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            cursor: pointer;
            appearance: none;
        }
        .remove-btn {
            font-size: 20px;
            background: #fff;
            border: 1px solid #ccc;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background 0.3s ease;
            cursor: pointer;
        }
        .remove-btn:hover {
            background: #ffe6e6;
        }
        .cart-footer {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: right;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .btn {
            padding: 10px 18px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 15px;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .btn-secondary {
            background: #e0e0e0; 
            color: #333;
            margin-right: 10px;
        }
        .btn-secondary:hover {
            background: #d5d5d5;
        }
        .btn-success {
            background: #28a745; 
            color: #fff;
        }
        .btn-success:hover {
            background: #218838;
        }
        .qty-box {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
            width: 110px;
        }
        .qty-btn {
            width: 35px;
            height: 35px;
            border: none;
            background: #f2f2f2;
            font-size: 20px;
            cursor: pointer;
        }
        .qty-btn:hover { background: #e6e6e6; }
        .qty-input {
            width: 40px;
            text-align: center;
            border: none;
            font-size: 16px;
        }
        .item-subtotal { margin-left: 15px; font-weight: bold; color: #333; }
        .cart-footer {
    background: #ffffff;
    border-radius: 10px;
    transition: box-shadow 0.3s ease;
}
.cart-footer:hover {
    box-shadow: 0 6px 16px rgba(0,0,0,0.15);
}
#cart-total {
    font-size: 1.5rem;
    font-weight: bold;
}

    </style>
</head>
<body>

<div class="main-wrapper">
    <h2>🛒 My Cart</h2>

    <?php if (empty($items)): ?>
        <div class="cart-container">Your cart is empty.</div>
    <?php else: ?>
        <?php $total = 0; ?>

        <div class="cart-container">
            <div class="store-header">
                <input type="checkbox" checked>
                <span> Store</span>
            </div>
            <div class="shipping-msg">Eligible for FREE STANDARD SHIPPING on Store products!</div>

            <?php foreach ($items as $item): ?>
                <?php
                    $quantity = isset($cart[$item['item_no']]) ? (int)$cart[$item['item_no']] : 1;
                    $price = isset($item['Price']) ? (float)$item['Price'] : 0;
                    $subtotal = $price * $quantity;
                    $total += $subtotal;
                ?>
                <div class="cart-item">
                    <input type="checkbox" checked>
                    <img src="<?= htmlspecialchars($item['image']) ?>" class="item-img" alt="">
                    <div class="item-details">
                        <div class="item-title"><?= htmlspecialchars($item['item_name']) ?></div>
                        <div class="item-meta">Category: <?= htmlspecialchars($item['cat_name'] ?? 'Uncategorized') ?></div>
                        <div>
                            <span class="price">$<?= number_format($item['Price'], 2) ?></span>
                            <?php if (!empty($item['old_price'])): ?>
                                <span class="old-price">$<?= number_format($item['old_price'], 2) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="item-actions">
                            <form method="post" action="update_qty.php" class="qty-form">
                                <input type="hidden" name="item_id" value="<?= $item['item_no'] ?>">
                                <div class="qty-box" data-id="<?= $item['item_no'] ?>">
                                    <button type="button" class="qty-btn" onclick="changeQty(this, -1)">−</button>
                                    <input type="number" value="<?= $quantity ?>" min="1" readonly class="qty-input">
                                    <button type="button" class="qty-btn" onclick="changeQty(this, 1)">+</button>
                                </div>
                                <span class="item-subtotal" id="subtotal-<?= $item['item_no'] ?>">
                                    $<?= number_format($subtotal, 2) ?>
                                </span>
                            </form>
                            <form method="post" action="remove_item.php">
                                <input type="hidden" name="item_id" value="<?= $item['item_no'] ?>">
                                <button type="submit" class="remove-btn">🗑</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="cart-footer col-md-6">
            <h3>Total: <span id="cart-total">$<?= number_format($total, 2) ?></span></h3>

            <!-- Checkout form with VAT & Discount -->
            <form method="post" action="data/checkout.php" class="mt-3">

                <!-- ✅ Customer Combo Box with Walk-in default -->
<div class="form-group col-md-6">
    <label>Customer</label>
    <select name="per_no" class="form-control select2">
        <?php
        // Always put Walk-in (14) first
        echo '<option value="14" selected>Walk-in</option>';

        // Add all other customers, but skip 14 to avoid duplicate
        foreach ($customer as $s):
            if ($s['per_no'] == 14) continue;
        ?>
            <option value="<?= $s['per_no'] ?>"><?= htmlspecialchars($s['name']) ?></option>
        <?php endforeach; ?>
    </select>
</div>


                <div class="form-group mb-6">
    <label>VAT (%)</label>
    <input type="number" name="vat_rate" id="vat_rate" class="form-control" step="0.0" min="0.0" value="5">
</div>

<div class="form-group mb-6">
    <label>Discount (%)</label>
    <input type="number" name="discount_rate" id="discount_rate" class="form-control" step="0.0" min="0.0"  value="0">
</div>

<div class="form-group mb-6">
    <label>Amount Paid</label>
   <input type="number" name="paid_amount" id="paid_amount" class="form-control" step="0.01" min="0" inputmode="decimal" />
</div>


                <a href="pas.php" class="btn btn-secondary mt-4">Continue Shopping</a>
                <button type="submit" class="btn btn-success mt-4">Checkout</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<script>
function changeQty(btn, change) {
    let box = btn.closest(".qty-box");
    let input = box.querySelector(".qty-input");
    let itemId = box.getAttribute("data-id");

    let newQty = parseInt(input.value) + change;
    if (newQty < 1) return;

    input.value = newQty;

    fetch("data/update_qty.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "item_id=" + itemId + "&qty=" + newQty
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById("subtotal-" + itemId).textContent = "$" + data.subtotal.toFixed(2);
            document.getElementById("cart-total").textContent = "$" + data.total.toFixed(2);
        }
    });
}

function updatePaidAmount() {
    // Get total and remove $ and commas
    let totalText = document.getElementById("cart-total").textContent.replace('$','').replace(/,/g,'');
    let total = parseFloat(totalText) || 0;

    let vat = parseFloat(document.getElementById("vat_rate").value) || 0;
    let discount = parseFloat(document.getElementById("discount_rate").value) || 0;

    let vatAmount = (total * vat) / 100;
    let discountAmount = (total * discount) / 100;
    let finalTotal = total + vatAmount - discountAmount;

    // Prevent negative totals
    finalTotal = finalTotal < 0 ? 0 : finalTotal;

    // Update the Amount Paid field
    document.getElementById("paid_amount").value = finalTotal.toFixed(2);
}

// Recalculate whenever VAT or Discount changes
document.getElementById("vat_rate").addEventListener("input", updatePaidAmount);
document.getElementById("discount_rate").addEventListener("input", updatePaidAmount);

// Initialize when page loads
updatePaidAmount();


</script>
</body>
</html>
