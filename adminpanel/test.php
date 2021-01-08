<?php
$title = "Reset Matrix";
$apage = "resetmatrix";
include_once("header.php");
//print_r($_SESSION['f_admin']['ID']);
$uData = array("uMobile" => "", "review" => "");
if (isset($request["uid"]) && $request["uid"] != '') {
    if ($userData = $db->selectRows("w_users", "", "ID={$request["uid"]}")) {
        $uData = $userData[0];
    }
}
?>
<main role="main" class="col-md-12 pt-3">
    <?php
    $duplicate = array();
    $unActive = array();
    /*     * *****RESET ALL******** */
    if ($allUsers = $db->selectQuery("select ID,uSponsor,isActive from w_users")) {
        foreach ($allUsers as $uData) {
            $query = "SELECT * FROM `w_user_downline` WHERE userID='{$uData['ID']}' and level!=0 GROUP by under_sp";
//            echo $query . "<br>";
            $result = $db->con->query($query);
//            if ($data = $db->selectQuery($query)) {
            $counter = 0;
            while ($data = mysqli_fetch_assoc($result)) {
                echo "<p class='text-danger'>".json_encode($data)."</p>";
                $counter++;
            }
            if ($counter > 0) {
                echo "<b>{$uData["ID"]}#</b> $counter<br>";
                if ($uData['isActive'] == 0) {
                    if ($counter > 0)
                        $unActive[] = $uData;
                } else if ($uData['isActive'] == 1) {
                    if ($counter > 1) {
                        $duplicate[] = $uData;
                    }
                }
            }
        }
    }

    echo"<h4>Unactive (" . count($unActive) . ")</h4>";
    echo"<pre>";
    print_r($unActive);
    echo"</pre><hr>";
    echo"<h4>Duplicates (" . count($duplicate) . ")</h4>";
    echo"<pre>";
    print_r($duplicate);
    echo"</pre>";
    /*     * ************* */
    ?>
</main>
<?php include_once("footer.php"); ?>