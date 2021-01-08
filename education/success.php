<?php
    include_once('../database.php');
	
    if(empty($_REQUEST)){
		header('location:index.php');
    } else {
		$json = json_encode($_REQUEST);
		$db->updateData("w_orders", array("status"=>'success',"response"=>$json), "orderID='{$_REQUEST['razorpay_order_id']}'");
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Payment Success</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<style>
body {
	font-family: 'Varela Round', sans-serif;
}
.modalconfirm {
    color: #636363;
    margin: auto;
    width: 92%;
    font-size: 14px;
}
.modalconfirm .modalcontent {
    padding: 20px;
    border-radius: 5px;
    background: white;
    border: none;
}
.modalconfirm .modalheader {
	border-bottom: none;   
	position: relative;
}
.modalconfirm h4 {
    text-align: center;
    font-size: 26px;
    margin: 35px 0 -15px;
}
.modalconfirm .form-control, .modalconfirm .btn {
	min-height: 40px;
	border-radius: 3px; 
}
.modalconfirm .close {
	position: absolute;
	top: -5px;
	right: -5px;
}	
.modalconfirm .modal-footer {
	border: none;
	text-align: center;
	border-radius: 5px;
	font-size: 13px;
}	
.modalconfirm .icon-box {
	color: #fff;		
	position: absolute;
	margin: 0 auto;
	left: 0;
	right: 0;
	top: -95px;
	width: 95px;
	height: 95px;
	border-radius: 50%;
	z-index: 9;
	background: #82ce34;
	padding: 15px;
	text-align: center;
	box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.1);
}
.modalconfirm .icon-box i {
	font-size: 58px;
	position: relative;
	top: 3px;
}
.modalconfirm.modaldialog {
	margin-top: 80px;
}
.modalconfirm .btn {
	color: #fff;
	border-radius: 4px;
	background: #82ce34;
	text-decoration: none;
	transition: all 0.4s;
	line-height: normal;
	border: none;
}
.modalconfirm .btn:hover, .modalconfirm .btn:focus {
	background: #6fb32b;
	outline: none;
}
.trigger-btn {
	display: inline-block;
	margin: 100px auto;
}
</style>
</head>
<body>
<div class="text-center">
	<!-- Button HTML (to Trigger Modal) -->
	<a href="#myModal" class="trigger-btn" data-toggle="modal"></a>
</div>

<!-- Modal HTML -->
<div id="myModal" class="modal fade">
	<div class="modaldialog modalconfirm">
		<div class="modalcontent">
			<div class="modalheader">
				<div class="icon-box">
					<i class="material-icons">&#xE876;</i>
				</div>				
				<h4 class="modal-title w-100">Success!</h4>	
			</div>
			<div class="modal-body">
				<p class="text-center">Your payment sent successfully.</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-block dismiss" data-dismiss="modal">OK</button>
			</div>
		</div>
	</div>
</div>     

<script>
    $(document).ready(function(){
        $('.trigger-btn').click();

        $(".dismiss").click(function(){
            window.location = 'index.php';
        });
    });
</script>
</body>
</html>