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
  static $s_instance = null;

  static function instance(){
     if(Database::$s_instance ==null){
       Database::$s_instance=new Database;
     }
     return Database::$s_instance;
  }

  function fetch_Products(){
    $con  = $this->db;

    if(!isset($con)) {
      return null;
    }
    $results = $con->query("SELECT * FROM Products");
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
  function __construct($vendor_name,$vendor_email,$product_name,$product_price,$quantity){
    $this->vendor_name=$vendor_name;
    $this->vendor_email=$vendor_email;
    $this->product_name=$product_name;
    $this->product_price=$product_price;
    $this->quantity=$quantity;
  }
  static function from_db($result){
    extract($result);
    return new Product($ven_name,$ven_email,$pro_name,$pro_price,$quantity);
  }

  function save($db){
    if(!isset($db->db)) {
      die("Authentication Error ! Unable to Connect Server DB");
      exit();
    }
    $con = $db->db;

    $insert_q="INSERT INTO Products (ven_name,ven_email,pro_name,pro_price,quantity) VALUES ('$this->vendor_name','$this->vendor_email','$this->product_name','$this->product_price','$this->quantity')";
    $success = $con->query($insert_q);

     return $success;
  }
}

?>
