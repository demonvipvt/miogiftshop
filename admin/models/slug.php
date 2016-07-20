<?php
class slugEntity{
	function saveslug($conn,$slug,$object,$oid){
	    $slugID = 0 ;
	    $sql_getslug = "SELECT id  FROM slug WHERE object='".$object."' AND object_id = ".$oid;
	    $getSlug = $conn->query($sql_getslug);
	    if( $getSlug->num_rows > 0 ){
	        $slugID = $getSlug->fetch_assoc()["id"] ;
	    }
	    if( $slugID == 0){
	    	$sql = "INSERT INTO slug (`slug`, `object`, `object_id`)
	                    VALUES ('".$slug."'
	                            , '".$object."'
	                            , ".$oid."
	                        )";
	    }else{
	        $sql = "UPDATE slug set `slug` = '".$slug."' WHERE id = ".$slugID;
	    }

	    if ($conn->query($sql) === FALSE) {
	        return false;
	    }
	    return true;
	}
}

?>