<?php

require_once("../../database.php");
require_once("userClass.php");
	if(isset($_SESSION["ludouser"]))
	{
		unset($_SESSION["ludouser"]);
		 // unset($_SESSION['ludouser']);
                 unset($_SESSION['userw']);
                unset($_SESSION['quizq']);
                
	}
	header('location:../../index.php');  

	

// $usreClass = new userClass();
// if(isset($_REQUEST["type"])){
// 	if($_REQUEST["type"] =="logout"){
// 		// unset($_SESSION);
// 		unset($_SESSION['admin']);
// 		unset($_SESSION["user"]["id"]);
// 		session_destroy();
                
// //		header("location:../");die;
//                header('location:../../index.php');   
                // echo "<script type='text/javascript'> document.location = '../'; </script>";exit;
// 	}
// }

?>        


