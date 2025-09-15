<div class="invoice-box">
    <div style="text-align:center;">
        <img src="assets/img/users/Aslan b.jpg" alt="Company Logo" style="max-height:50px;">
        <h5>Aslan style</h5>
        <p>Phone Number: +252 61xxxxx</p>
        <p>Email: aslanb@gmail.com</p>
        <h4>Invoice</h4>
    </div>

    <div class="divider"></div>

    <div class="row-line">
        <span><strong>Name:</strong> <?= htmlspecialchars($invoiceData['supplierName']) ?></span>
        <span><strong>Tell:</strong> <?= htmlspecialchars($invoiceData['supplierTell']) ?></span>
    </div>
    <div class="row-line">
        <span><strong>Invoice No:</strong> <?= htmlspecialchars($invoiceData['po_no']) ?></span>
        <span><strong>Date:</strong> <?= htmlspecialchars($invoiceData['order_date']) ?></span>
    </div>

    <div class="divider"></div>

    <div class="row-line bold">
        <span># Item</span>
        <span>Cost</span>
        <span>Qty</span>
        <span class="right">Total</span>
    </div>

    <?php foreach ($invoiceData['items'] as $index => $item): ?>
    <div class="row-line">
        <span><?= $index+1 ?>. <?= htmlspecialchars($item['name']) ?></span>
        <span>$<?= number_format($item['cost'], 2) ?></span>
        <span><?= intval($item['qty']) ?></span>
        <span class="right">$<?= number_format($item['qty'] * $item['cost'], 2) ?></span>
    </div>
    <?php endforeach; ?>

    <div class="divider"></div>

    <div class="row-line total">
        <span>Sub Total :</span>
        <span class="right">$<?= number_format($invoiceData['totalBeforeDiscount'], 2) ?></span>
    </div>
    <div class="row-line total">
        <span>Discount :</span>
        <span class="right">-$<?= number_format($invoiceData['totalDiscount'], 2) ?></span>
    </div>
    <div class="row-line total grand">
        <span>Total :</span>
        <span class="right">$<?= number_format($invoiceData['totalAfterDiscount'], 2) ?></span>
    </div>

    <div style="text-align:center; margin-top:15px;">
        <p>Thank You For Shopping With Us. Please Come Again</p>
    </div>

    <button id="printBtn" onclick="window.print()">Print Receipt</button>
</div>
