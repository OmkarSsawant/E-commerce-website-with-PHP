<?php
include_once 'Repository.php';

$db = Database::instance();

if(isset($_POST['vendor-email'])){
  $ven_name = $_POST['vendor-name'];
  $ven_email= $_POST['vendor-email'];
  $pro_name= $_POST['product-name'];
  $pro_price= $_POST['product-price'];
  $qty= $_POST['quantity'];


$pro = new Product($ven_name,$ven_email,$pro_name,$pro_price,$qty);
$success = $pro->save($db);
if($success){
  echo "Successfully Added Product";
}else{
  echo "Failed To add Product";
}
}

 ?>



<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>E-commerce</title>
  </head>
  <body>

    <form class="upload-product-content" action="upload.php" method="post">
      <fieldset>
        <legend> Upload Product </legend>

      <?php

          $feilds = array("vendor name" => "text","vendor email"=> "email","product name"=> "text","product price"=>"number","quantity"=>"number");
        function input_field($label,$type){
          $name=str_replace(" ","-",$label);
          return "<div class='fld-content'> <label class='fld-label' for='$name'>$label</label> <input class='fld-in' name='$name' type='$type'/> </div> <br>";
        }

        foreach($feilds as $label=>$type){
          echo input_field($label,$type);
        }

        ?>

        <button class="upload-btn" type="submit"> upload</button>
      </fieldset>


    </form>
  </body>
</html>