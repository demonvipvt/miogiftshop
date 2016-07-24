<?php 
include "connect.php";
var_dump( $_SERVER['REQUEST_METHOD'] );
var_dump($_POST);
if($_SERVER['REQUEST_METHOD'] == "POST"){
	$product_id 	= empty($_POST['product_id'])?0:$_POST['product_id'];
	$price_id 		= empty($_POST['price_id'])?0:$_POST['price_id'];
	$price 			= empty($_POST['price'])?0:$_POST['price'];
	$sale_percent 	= empty($_POST['sale_percent'])?0:$_POST['sale_percent'];
	$comment 			= empty($_POST['comment'])?0:$_POST['comment'];
	$from_date 		= empty($_POST['start'])?'':$_POST['start'];
	$to_date 		= empty($_POST['end'])?'':$_POST['end'];
	var_dump($product_id);
	var_dump($price_id);
	if( $product_id != 0 ){
		if( $price_id == 0 ){
            $sql = "INSERT INTO price (`price`, `sale_percent`, `comment`
                                        , `from_date`, `to_date`, `product_id`)
            VALUES (".$price."
                , ".$sale_percent."
                , '".$comment."'
                , STR_TO_DATE('".$from_date."', '%d.%m.%Y')
                , STR_TO_DATE('".$to_date."', '%d.%m.%Y')
                , ".$product_id."
                )";
            if ($conn->query($sql) === TRUE) {
				header("Location: product-detail.html?product=".$product_id);
            }else{
            	echo $conn->error;
            }
		}else{
            $sql = "UPDATE price SET 
            	  `price`=".$price."
                , `sale_percent`=".$sale_percent."
                , `comment`='".$comment."'
                , `from_date`=STR_TO_DATE('".$from_date."', '%d.%m.%Y')
                , `to_date`=STR_TO_DATE('".$to_date."', '%d.%m.%Y')
                ";
            if ($conn->query($sql) === TRUE) {
				header("Location: product-detail.html?product=".$product_id);
            }else{
            	echo $conn->error;
            }
		}
	}
}
// header("Location: index.html");
?>