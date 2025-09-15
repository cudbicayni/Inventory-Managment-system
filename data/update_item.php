<?php
// update_item.php
header('Content-Type: application/json');

try {
  // 1) DB connection: change dbname, user, pass to yours
  $pdo = new PDO(
    'mysql:host=localhost;dbname=invent;charset=utf8mb4',
    'root',
    '',
    [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
  );

  // 2) Collect and validate inputs
  $item_no   = isset($_POST['item_no']) ? (int) $_POST['item_no'] : 0;
  $cat_no    = isset($_POST['i_cat']) ? (int) $_POST['i_cat'] : 0;
  $item_name = isset($_POST['i_name']) ? trim($_POST['i_name']) : null; // nullable per schema
  $price     = isset($_POST['i_price']) ? (float) $_POST['i_price'] : 0.0;

  if ($item_no <= 0) throw new Exception('Invalid item_no.');
  if ($cat_no <= 0) throw new Exception('Invalid category (i_cat).');
  if (!($price > 0)) throw new Exception('Invalid price.');

  // 3) Ensure the item exists
  $chk = $pdo->prepare('SELECT item_no FROM items WHERE item_no = ?');
  $chk->execute([$item_no]);
  if (!$chk->fetch()) {
    throw new Exception('Item not found.');
  }

  // 4) Determine whether a new image was uploaded
  $hasNewImage = isset($_FILES['txtfile'])
    && is_uploaded_file($_FILES['txtfile']['tmp_name'])
    && $_FILES['txtfile']['error'] === UPLOAD_ERR_OK;

  if ($hasNewImage) {
    // Optional validations for the image
    $tmpPath = $_FILES['txtfile']['tmp_name'];
    $size    = $_FILES['txtfile']['size'];
    if ($size > 5 * 1024 * 1024) {
      throw new Exception('Image too large (max 5MB).');
    }
    // Validate MIME using finfo (more reliable)
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($tmpPath);
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($mime, $allowed, true)) {
      throw new Exception('Unsupported image type.');
    }

    // Read binary contents for BLOB
    $imgData = file_get_contents($tmpPath);
    if ($imgData === false) {
      throw new Exception('Failed to read uploaded image.');
    }

    // 5a) Update including image BLOB
    $sql = 'UPDATE items
              SET cat_no = :cat_no,
                  item_name = :item_name,
                  Price = :price,
                  image = :image
            WHERE item_no = :item_no';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':cat_no', $cat_no, PDO::PARAM_INT);
    // item_name is nullable; pass null if empty string
    $stmt->bindValue(':item_name', ($item_name === '' ? null : $item_name), $item_name === '' ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':price', $price); // double/float
    $stmt->bindParam(':image', $imgData, PDO::PARAM_LOB);
    $stmt->bindValue(':item_no', $item_no, PDO::PARAM_INT);
    $stmt->execute();

  } else {
    // 5b) Update without touching image
    $sql = 'UPDATE items
              SET cat_no = :cat_no,
                  item_name = :item_name,
                  Price = :price
            WHERE item_no = :item_no';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':cat_no', $cat_no, PDO::PARAM_INT);
    $stmt->bindValue(':item_name', ($item_name === '' ? null : $item_name), $item_name === '' ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':price', $price);
    $stmt->bindValue(':item_no', $item_no, PDO::PARAM_INT);
    $stmt->execute();
  }

  echo json_encode(['success' => true]);
} catch (Throwable $e) {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}