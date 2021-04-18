<?php

// This Class is created just for mechanism that
// closes connection when page closes


class Database{
  function __construct(){
    #create a DB connection
    $username="root";
    $pswd="";
    $server="localhost";
    $this->db = new mysqli($server,$username,$pswd,'e_com_db');

  }
  static $db_instance = null;

  static function instance(){
     if(Database::$db_instance ==null){
       Database::$db_instance=new Database;
     }
     return Database::$db_instance;
  }

  function fetch_Products(){
    $con  = $this->db;

    if(!isset($con)) {
      return null;
    }
    $results = $con->query("SELECT * FROM products");

    $products = array();
    if(!empty($results)){

      while ($row = $results->fetch_assoc()) {
        array_push($products,Product::from_db($row));
      }
    }
    return $products;
  }

  function fetch_orders_of($ven_email){
    $con = $this->db;
    if(!isset($con)) return null;
    
    $ven_orders = array();
    #get the product for vendor_email and product name price 
    $ven_order_q = "SELECT * FROM Orders WHERE ven_email='$ven_email'";

    $order = $con->query($ven_order_q);
    while ($raw_order = $order->fetch_assoc()) {
      array_push($ven_orders,Order::from_db($raw_order));
    }
    return $ven_orders;
  }

function fetch_product($product_id){
    $con = $this->db;
    if(!isset($con)) return null;
    
    #get the product for vendor_email and product name price 
    $ven_prod_q = "SELECT * FROM products WHERE id='$product_id'";
    $product=null;
    $product_res = $con->query($ven_prod_q);
    while ($raw_product = $product_res->fetch_assoc()) {
      $product = Product::from_db($raw_product);
    }
    return $product;
  }



  function __destruct()
 {
   mysqli_close($this->db);
 }
}


class Product{
  function __construct($id=null,$vendor_name,$vendor_email,$product_name,$product_price,$quantity,$category,$imgs=array()){
    $this->vendor_name=$vendor_name;
    $this->vendor_email=$vendor_email;
    $this->product_name=$product_name;
    $this->product_price=$product_price;
    $this->quantity=$quantity;
    $this->id=$id;
    $this->category=$category;
    $this->imgs=$imgs;
  }

  function add_product_img($img_path){
    array_push($this->imgs, $img_path);
  }

  function save_imgs($db){
    $con = $db->db;
    $ser_imgs = serialize($this->imgs);
    $res =  $con->query("UPDATE products SET product_imgs='$ser_imgs' WHERE id='$this->id'");
    return $res;
  }
  static function from_db($result){
    extract($result);
    //deserialize images

    return new Product($id,$ven_name,$ven_email,$pro_name,$pro_price,$quantity,$category,unserialize($product_imgs));
  }

  function save($db){
    if(!isset($db->db)) {
      die("Authentication Error ! Unable to Connect Server DB");
      exit();
    }
    $con = $db->db;

    $no_imgs = serialize(array());
    $insert_q="INSERT INTO products (ven_name,ven_email,pro_name,pro_price,quantity,category,product_imgs) VALUES ('$this->vendor_name','$this->vendor_email','$this->product_name','$this->product_price','$this->quantity','$this->category','$no_imgs')";
    $success = $con->query($insert_q);
    $this->id = $con->insert_id;
     return $success;
  }
}


class Order{

   function __construct($id=null,$p_id,$qty,$cust_name,$cust_email,$cust_phone,$address,$ven_email,$shopped_at=null) {
    $this->id = $id;
    $this->product_id = $p_id;
    $this->quantity = $qty;
    $this->customer_name = $cust_name;
    $this->customer_email = $cust_email;
    $this->customer_phone = $cust_phone;
    $this->shopped_at = $shopped_at;
    $this->address=$address;
    $this->ven_email=$ven_email;
  }

  static function from_db($result){
    extract($result);
    return new Order($id,$product_id,$quantity,$customer_name,$customer_email,$customer_phone,$address,$ven_email,$shopped_at);
  }

  function save($db){
    $con = $db->db;
    $insert_q = "INSERT INTO orders(id,product_id,quantity,customer_name,customer_email,customer_phone,`address`,ven_email,shopped_at)
     VALUES (null,'$this->product_id','$this->quantity','$this->customer_name','$this->customer_email','$this->customer_phone','$this->address','$this->ven_email',CURRENT_TIMESTAMP)";
    $success = $con->query($insert_q);
    
    if(!$success) {
      return null;
    }
   $this->id = $con->insert_id;  
 
    return json_encode($this);
  }

}

?>
