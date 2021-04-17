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
  function __destruct()
 {
   mysqli_close($this->db);
 }
}


class Product{
  function __construct($id=null,$vendor_name,$vendor_email,$product_name,$product_price,$quantity,$category){
    $this->vendor_name=$vendor_name;
    $this->vendor_email=$vendor_email;
    $this->product_name=$product_name;
    $this->product_price=$product_price;
    $this->quantity=$quantity;
    $this->id=$id;
    $this->category=$category;
  }
  static function from_db($result){
    extract($result);
    return new Product($id,$ven_name,$ven_email,$pro_name,$pro_price,$quantity,$category);
  }

  function save($db){
    if(!isset($db->db)) {
      die("Authentication Error ! Unable to Connect Server DB");
      exit();
    }
    $con = $db->db;

    $insert_q="INSERT INTO products (ven_name,ven_email,pro_name,pro_price,quantity,category) VALUES ('$this->vendor_name','$this->vendor_email','$this->product_name','$this->product_price','$this->quantity','$this->category')";
    $success = $con->query($insert_q);

     return $success;
  }
}


class Order{

   function __construct($id=null,$p_id,$qty,$cust_name,$cust_email,$cust_phone,$address,$shopped_at=null) {
    $this->id = $id;
    $this->product_id = $p_id;
    $this->quantity = $qty;
    $this->customer_name = $cust_name;
    $this->customer_email = $cust_email;
    $this->customer_phone = $cust_phone;
    $this->shopped_at = $shopped_at;
    $this->address=$address;
  }

  static function from_db($result){
    extract($result);
    return new Order($id,$product_id,$quantity,$customer_name,$customer_email,$customer_phone,$address,$shopped_at);
  }

  function save($db){
    $con = $db->db;

  $insert_q = "INSERT INTO orders(id,product_id,quantity,customer_name,customer_email,customer_phone,`address`,shopped_at)
     VALUES (null,'$this->product_id','$this->quantity','$this->customer_name','$this->customer_email','$this->customer_phone','$this->address',CURRENT_TIMESTAMP)";
    $success = $con->query($insert_q);
    
    if(!$success) {
      return null;
    }
   $this->id = $con->insert_id;  
 
    return json_encode($this);
  }

}

?>
