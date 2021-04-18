<?php 
include_once 'Repository.php';
$db = Database::instance();


function fail()
{
  
  http_response_code(400);
  header('Location:index.php?order_req=true&success=false');
  echo "Failed To Place Order";
      
}

function remove_order($con){
  $del_q = "DELETE FROM orders WHERE id=`$con->insert_id`";
  $has_deleted =  $con->query(  $del_q);
}

$con=$db->db;
  $is_loading=isset($_POST['customer-email']) ;
  if($is_loading){
    $qty = $_POST['quantity'];
    $max_qty = $_POST['max_qty'];
      if($qty<=$max_qty){
            #insert in orders db
            $cust_email = $_POST['customer-email'];
            $cust_name = $_POST['customer-name'];
            $cust_phone = $_POST['customer-phone'];
            $product_id = $_POST['id']; 
            $pro_name = $_POST['pro_name'];
            $address=$_POST['address'];
            $imgs = unserialize($_POST['product_imgs']);
            $ven_email = $_POST['ven_email'];
            
          $order = new Order(null,$product_id,$qty,$cust_name,$cust_email,$cust_phone,$address,$ven_email,null);
          #this inserted_json var can later be used for REST API 
          $inserted_json =  $order->save($db);

          if($inserted_json){
            $rem = intval($max_qty) - intval($qty); 
            $req_proc = false;
            if($rem<=0){
              #the last product was sold
              $update_product = "DELETE FROM products WHERE id=$product_id";
              $updated_product_res = $con->query($update_product);
              $req_proc = $updated_product_res;
            
              #also delete the product related imgs
              foreach($imgs as $img){
                unlink($img);
              }
              #then remove parent dir
              rmdir('pictures/'.$pro_name . $product_id);
            }else{
            #decrease the quantity s the order is placed
            $update_product = "UPDATE products SET quantity=$rem WHERE id=$product_id";
            $updated_product_res = $con->query($update_product);
             $req_proc =$updated_product_res; 
            }
            if($req_proc){
                http_response_code(200);
              header('Location:index.php?order_req=true&success=true');   
            }else{
              remove_order($con);
              fail();
            }
          }else{
            fail();
          }
        } else{
        die( " <h1> Unable To Place Order Currently </h1> <br> Stock Not Availalbe <br>" . "Avalialbe Quantity : $max_qty");
        exit();
      }
  }
?>

<?php if(isset($_POST['pro_name']) && !isset($_POST['customer-email'])):?>
    <form action="place_order.php" method="post">
        <?php

        include_once 'Repository.php';
      $feilds = array("customer name" => "text","customer email"=> "email","customer phone"=> "text","quantity"=>"number");
        function input_field($label,$type){
            $name=str_replace(" ","-",$label);
          return "<div class='fld-content'> <label class='fld-label' for='$name'>$label</label> <input class='fld-in' name='$name' type='$type' required/> </div> <br>";
        }

        foreach($feilds as $label=>$type){
          echo input_field($label,$type);
        }
        echo "<div class='fld-content'> <label class='fld-label' for='address'> Address </label> <textarea class='fld-in' name='address' ></textarea> </div> <br>";
       
        $p = Product::from_db($_POST);
?>
             <input type='hidden' name="pro_name"  value=<?=$p->product_name  ?>>  
            <input type='hidden' name="pro_price"  value=<?=$p->product_price . '₹' ?>>   
            <input type='hidden'  name="ven_name"  value=<?=$p->vendor_name?> >  
            <input type='hidden'  name="ven_email" value=<?=$p->vendor_email  ?>>  
            <input type='hidden'  name="category" value=<?=$p->category  ?>>  
            <input type='hidden' name="id" value=<?=$p->id ?> > 
            <input type="hidden" name="max_qty" value=<?=$p->quantity?>>

        <button type="submit"> Place Order </button>
    </form>
<?php endif; ?>

<?php if($is_loading) :?>
<style>
 @import 'index.css';
</style>
<div class='load ' style='margin-left:48%;margin-top:15%;'></div>  <h1 class='cen'> Placing Order <h1> <br> <h3 class='plz-wt' style='margin-left:48%;margin-top:17%;'> Please Wait ...</h3>
<?php endif;?>




















