<?php
include_once 'Repository.php';

$db = Database::instance();

if(isset($_POST['vendor-email'])){
  $ven_name = $_POST['vendor-name'];
  $ven_email= $_POST['vendor-email'];
  $pro_name= $_POST['product-name'];
  $pro_price= $_POST['product-price'];
  $qty= $_POST['quantity'];
  $category = $_POST['category'];
  $files = $_FILES["product_imgs"]["tmp_name"];
  // echo "<pre>" ;
  // print_r($_FILES["product_imgs"]);
  // echo   " </pre>";
$pro = new Product(null,$ven_name,$ven_email,$pro_name,$pro_price,$qty,$category);
$success = $pro->save($db);
//pro->id is updated in save();
$new_id = $pro->id;
$skipped_files=0;
$supported_types = array("jpeg","jpg","png");
for($i=0;$i<count($files);$i++){
  $ext = explode('/', $_FILES['product_imgs']['type'][$i])[1];
  $size = (int) ( $_FILES['product_imgs']['size'][$i]);
//skips if file type supported or  files over 1MiB
  if(!in_array(strtolower($ext), $supported_types) || $size > 10000000){
    $skipped_files++;
    continue;
  }
  
  $dest_src = "pictures/" . $pro_name . $new_id . '/' . $i . '.' . $ext;
  $pro_folder = 'pictures/' . $pro_name . $new_id; 
  if(!file_exists($pro_folder))
    mkdir($pro_folder);
  move_uploaded_file($files[$i],$dest_src);
  $pro->add_product_img($dest_src);
}

$pro->save_imgs($db);
if($success){
  echo "Successfully Added Product : Skipped Files : $skipped_files";
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

    <form class="upload-product-content" enctype="multipart/form-data" action="upload.php" method="post">
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
        $categories = array("Electronic","Furniture","Luggage","Men Clothing","Women Clothing","Mobiles","Desktop","Laptop");
        function to_options($cats){
          $options="";
          foreach($cats as $cat){
            $options = $options . "<option>" . $cat . "</option>";
          }
          return $options;
        }
        $options = to_options($categories);
        echo "<div class='fld-content'> <label class='fld-label' for='category'> Category </label> <select name='category'> $options </select> </div> <br>";
        echo "<input type='file' name='product_imgs[]'  multiple /><br>";

        ?>

        <button class="upload-btn" type="submit"> upload</button>
      </fieldset>

    </form>
  </body>
</html>
