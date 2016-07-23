<?php 
include "connect.php";

$id  = empty( $_GET["id"] ) ?"":$_GET["id"];
$code  = empty( $_POST["code"] ) ?"":$_POST["code"];
if($code == ""){
	echo json_encode(false);
}else{
	if( $id == "" ){
		$sql = "SELECT code FROM product WHERE code = '".$code."' ";
		$checking = $conn->query($sql);
		if($checking->num_rows > 0){
			echo json_encode(false);
		}else{
			echo json_encode(true);
		}
	}else{
		$sql = "SELECT code FROM product WHERE `code` = '".$code."' AND `id` != ".$id." ";
		$checking = $conn->query($sql);
		if($checking->num_rows > 0){
			echo json_encode(false);
		}else{
			echo json_encode(true);
		}
	}
}
?>