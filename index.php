<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <?php
    include_once 'Repository.php';

    $db = Database::instance();

    $products = $db->fetch_Products();
    if($products==null) {
      echo "No Products";
    }

     ?>
    <a href="upload.php">upload</a>

    <?php foreach ($products as $p): ?>
      <h1 ><?=$p->product_name ?>  </h1>
      <h1 ><?=$p->product_price ?>  </h1>
      <h1 ><?=$p->vendor_name ?>  </h1>
      <h1 ><?=$p->vendor_email ?>  </h1>
      <h1 ><?=$p->product_price ?>  </h1>
      <h1 ><?=$p->quantity ?>  </h1>

    <?php endforeach; ?>

  </body>
</html>
