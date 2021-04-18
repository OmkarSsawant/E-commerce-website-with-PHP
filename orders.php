<?php 
	include_once 'Repository.php';
	$db  = Database::instance();
?>

<?php if(!isset($_GET['vendor_email'])) : ?>
<form>
	<input type="email" name="vendor_email">
	<button type="submit"> show orders </button>
</form>
<?php else: ?>
	<style type="text/css"> @import 'index.css'; </style>
	<!-- <pre><?php print_r($db->fetch_orders_of($_GET['vendor_email'])) ?></pre> -->
	<?php 
		$orders = $db->fetch_orders_of($_GET['vendor_email']);
		$order_with_products = array();

		#get product img ,name , price 
		foreach ($orders as $order) {
			$related_product = $db->fetch_product($order->product_id);
			$order_with_products[$order->id] = $related_product;
		}
		// echo "<pre>";
		// print_r($order_with_products);
		// echo "</pre>";
	?>

	<?php 

	foreach ($order_with_products as $order_id => $product) {
		
		echo "<div class='p-box' style='height: 30vh'>";
		echo "<div class='slideshow'>";
             foreach ($product->imgs as $img){
             	echo "<img class='p-tn' width='12vw' height='22vh' src=$img alt='thumbnail' >";
             }
                          
         echo " </div>";

        echo "<div class='p-info'>
        <p id='p-title' class='p-title'>$product->product_name   </p>
        <p id='p-price' class='p-price'>$product->product_price ₹ </p>
        <p id='p-qty' class='p-qty' style='display:none;'>$product->quantity </h1>
        </div>";
        	function check_key($o){
        		global $order_id ;
        		return intval($o->id) == $order_id;
        	}
        	$order = array_filter($orders,'check_key');
        	print_r($order);
		echo "<div class='order-info'>
        	
        </div>";
	} 

	?>
		     
         
          
        
        

        
   
<?php endif; ?>