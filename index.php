<?php 

//TODO: Vendors Panel , Product Images Upload , UI Design

if(isset($_GET['order_req']) && (bool)$_GET['order_req']){
  $su = (bool) $_GET['success'];
  if($su==0){
    echo "<script> alert('Successfully Placed Order $su'); </script>";
  }else{
    echo "<script> alert('Failed Placed Order ');</script>";
  }
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>E-commerce </title>
    <link rel="stylesheet" href="index.css"/>
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
    <div class="content">
      <?php foreach ($products as $p): ?>
        <div class="p-box">
          <!--TODO: slideshow to be created  -->
          <img class="p-tn" src=<?=$p->imgs[0]?> alt="thumbnail" >
        
        <div class="p-info">
        <p id="p-title" class="p-title"><?=$p->product_name ?>  </p>
        <p id="p-price" class="p-price"><?=$p->product_price ?>  ₹ </p>
        <!-- <p id="p-ven-name" class="p-ven-name"  style="display:none;"><?=$p->vendor_name ?>  </p> -->
        <!-- <p id="p-ven-email" class="p-ven-email" style="display:none;"><?=$p->vendor_email ?>  </p> -->
        <p id="p-qty" class="p-qty" style="display:none;"><?=$p->quantity ?>  </h1>
        </div>
        
        <div class="op-btns">
          <form action="place_order.php" method="post" style="width:100%;height:100%">
            <input type='hidden' name="pro_name" value=<?=$p->product_name  ?>>  
            <input type='hidden' name="pro_price" value=<?=$p->product_price . '₹' ?>>   
            <input type='hidden'  name="ven_name" value=<?=$p->vendor_name   ?>>  
            <input type='hidden'  name="ven_email" value=<?=$p->vendor_email  ?>>  
            <input type='hidden' name="quantity" value=<?=$p->quantity ?> > 
            <input type='hidden'  name="category" value=<?=$p->category  ?>>  
            <input type='hidden' name="id" value=<?=$p->id ?> > 
            <input type='hidden' name="product_imgs" value=<?= serialize($p->imgs)?> > 

            
            <button type="submit" class="op-btn buy" > Buy </button>
          </form>
          <button  class="op-btn add_cart">Add To Cart</button>             
        </div>
      </div> 
    <?php endforeach; ?>
    </div>

  </body>
  <script>
    const buy = document.getElementById('buy');
    const title = document.getElementById('p-title');
    const price = document.getElementById('p-price');
    const ven_name = document.getElementById('p-ven-name');
    const ven_email = document.getElementById('p-ven-email');
    const qty = document.getElementById('p-qty');

    buy.onclick = ev => {
      console.log('Order Placed');
      fetch('orders.php',{
        'title':`${title.textContent}`,
        'price':`${price.textContent}`,
        'ven_name':`${ven_name.textContent}`,
        'ven_email':`${ven_email.textContent}`,
        'qty':`${qty.textContent}`,
      },'POST').then(console.log);
    }
  </script>
</html>
