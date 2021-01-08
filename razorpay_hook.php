<?php //
include_once('database.php');


if (!empty($_REQUEST)) {
  //    echo "<pre>";
  //    print_r($_POST);
  $data = json_encode($_REQUEST);
  $db->insertData("temp_order", array("response" => $data));
}
