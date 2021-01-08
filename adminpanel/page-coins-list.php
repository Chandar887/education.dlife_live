<?php
$title = "Coins List";
$apage = "coinslist";

include_once("header.php");
$limit = 50;
$offset = 0;
$page = 1;
$totaldata = 0;
$q = '';
$condtion = array();
if (isset($request['roomID']) && $request['roomID'] != '') {
    $condtion[]="roomID='{$request['roomID']}'";
}
if (isset($request['q']) && $request['q'] != '') {
    $q = $request['q'];
    $condtion[] = "(w_user_coins.roomID like '%" . $request['q'] . "%' or w_user_coins.uCoin like '%" . $request['q'] . "%' or w_user_coins.review like '%" . $request['q'] . "%' or w_users.uName like '%" . $request['q'] . "%' or w_users.uMobile like '%" . $request['q'] . "%')";
}
if (isset($request['datetime']) && $request['datetime'] != '') {
    $condtion[] = "w_user_coins.createdAt like '%" . $request['datetime'] . "%'";
}
if (isset($request['uid']) && $request['uid'] != '') {
    $condtion[] = "uID='{$request['uid']}'";
}
if (isset($request['reviewtype']) && $request['reviewtype'] != '') {
    $condtion[] = "review='{$request['reviewtype']}'";
}
if (isset($request['isCredit']) && $request['isCredit'] != '') {
    $condtion[] = "isCredit='{$request['isCredit']}'";
} 
if (isset($request['type']) && $request['type'] != '') {
    $condtion[] = "type='{$request['type']}'";
} 

if (count($condtion) > 0)
    $condtion = 'where ' . implode(" and ", $condtion);
else
    $condtion = '';
?>
<main role="main" class="col-md-12 pt-3">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h2 class="h2"><i class="fa fa-"></i> Coins List</h2>
        <div class="btn-toolbar mb-2 mb-md-0">
            <form method='get' action='<?php echo $currenturl; ?>' class="form-inline mr-4">
                <label class="sr-only" for="q">Username</label>
                <div class="input-group mr-1">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class='fa fa-search'></i></div>
                    </div>
                    <input type="date" class="form-control" id="datetime" name='datetime' value='<?php echo isset($request['datetime']) ? $request['datetime'] : ""; ?>' placeholder="date">
                    <select class="form-control" id="reviewcoin" name='reviewtype'>
                        <option value=''>- All Review -</option>
                        <?php
                        $reviewTypes = array();
                        if ($rrData = $db->selectRows("w_user_coins", "DISTINCT review")) {
//                            print_r($rrData);
                            $reviewTypes = $rrData;
                        }
                        foreach ($reviewTypes as $reData) {
                            $selected = isset($request['reviewtype']) && $request['reviewtype'] == $reData['review'] ? "selected" : "";
                            echo "<option value='{$reData['review']}' {$selected}>" . $reData['review'] . "</option>";
                        }
                        ?>
                    </select>
                    <select class="form-control" id="isCredit" name='isCredit'>
                        <option value=''>- All -</option>
                        <option value='1' <?php echo isset($request['isCredit']) && $request['isCredit'] == "1" ? "selected" : ""; ?>>Credit</option>
                        <option value='0' <?php echo isset($request['isCredit']) && $request['isCredit'] == "0" ? "selected" : ""; ?>>Debit</option>
                    </select>
                    <input type="text" class="form-control" id="q" name='q' value='<?php echo $q; ?>' placeholder="Search">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
                <!--<a class="btn btn-primary mx-1" href="page-coins-add.php"><i class="fa fa-user-plus pr-2"></i>Add Coins</a>-->
            </form>
            <form action="../export_data.php" method="post">
                <input type="hidden" name="query" value="<?php echo "SELECT * from w_user_coins $condtion order by ID desc"; ?>"/>
                <button type="submit" class="btn btn-danger mx-1"><i class="fa fa-file pr-2"></i>Export All</button>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-sm table-striped">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>RoomID</th>
                    <th>Coin</th>
                    <th>Before/After</th>
                    <th>Review</th>
                    <!--<th>Type</th>-->
                    <th>DateTime</th>
                    <!--<th class="text-right">Action</th>-->
                </tr>
            </thead>
            <tbody id="tabledata">
                <?php
                if ($countData = $db->selectQuery("SELECT count(*) as totaldata from w_user_coins inner join w_users on w_users.ID=w_user_coins.uID $condtion")) {
                    $totaldata = $countData[0]['totaldata'];
                }
                if (isset($request['page']) && $request['page'] > 1) {
                    $page = $request['page'];
                    $offset = $limit * ($page - 1);
                }

                if ($data = $db->selectQuery("SELECT w_user_coins.*,w_users.uMobile,w_users.uName from w_user_coins inner join w_users on w_users.ID=w_user_coins.uID $condtion order by ID desc LIMIT $limit OFFSET $offset")) {
                    foreach ($data as $d) {
//                        $userData = $db->selectQuery("select uName,uMobile from w_users where ID={$d['uID']}");
//                        $user = $userData[0];
                        ?>
                        <tr id="datarow-<?php echo $d['ID'] ?>">
                            <td><?php echo $d['ID'] ?></td>
                            <td><?php echo $d['uName'] ?></td>
                            <td><?php echo $d['uMobile'] ?></td>
                            <td><?php echo "<p class='badge badge-secondary rounded'>{$d['roomID']}</p>"; ?></td>
                            <td>
                                <?php
                                if ($d['isCredit'] == 0) {
                                    echo "<p class='text-danger m-1'>₹ {$d['uCoin']}</i></p>";
                                } else {
                                    echo "<p class='text-success m-1'>₹ {$d['uCoin']}</p>";
                                }
                                ?>
                            </td>
                            <td>
                                <?php echo "<p class='badge badge-danger rounded m-1'>Before: ₹ {$d['beforeCoin']}</i></p>"; ?>
                                <?php echo "<p class='badge badge-primary rounded m-1'>After: ₹ {$d['afterCoin']}</i></p>"; ?>
                            </td>       
                            <td><?php echo $d['review'] ?></td>                  
                            <td><?php echo $d['createdAt'] ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr><td colspan="14" class="text-danger h3 text-center">No Record Found</td></tr>  
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php pagination($totaldata, $limit, $offset, $page, $q, $currenturl); ?>
</main>

<?php
include_once("footer.php");
?>