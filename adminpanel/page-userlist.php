<?php
$title = "Users List";
$apage = "users";
?>
<?php include_once("header.php"); ?>
<?php
$limit = 25;
$offset = 0;
$page = 1;
$totaldata = 0;
$q = '';
$condtion = array();

if (isset($request['q']) && $request['q'] != '') {
    $q = $request['q'];
    $condtion[] = "(uniqueID like '%" . $request['q'] . "%' or uName like '%" . $request['q'] . "%' or uMobile like '%" . $request['q'] . "%' or uEmail like '%" . $request['q'] . "%' or uSponsor like '%" . $request['q'] . "%')";
}
if (isset($request['activedate']) && $request['activedate'] != '') {
    $condtion[] = "(activateDate like '%" . $request['activedate'] . "%' or createdAt like '%" . $request['activedate'] . "%' )";
}
if (isset($request['limit']) && $request['limit'] != '') {
    $limit = $request['limit'];
}

if (isset($request['isactive']) && $request['isactive'] != '') {
    $condtion[] = "isActive='{$request['isactive']}'";
}
if (isset($request['franchiseID']) && $request['franchiseID'] != '') {
    $condtion[] = "franchiseID='{$request['franchiseID']}'";
}

if (count($condtion) > 0)
    $condtion = 'where ' . implode(" and ", $condtion);
else
    $condtion = '';

$totaldata = $db->countRows('w_users', $condtion);
if (isset($request['page']) && $request['page'] > 1) {
    $page = $request['page'];
    $offset = $limit * ($page - 1);
}
$query = "SELECT * from w_users $condtion order by ID desc LIMIT $limit OFFSET $offset";
?>
<main role="main" class="col-md-12 pt-3">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h2 class="h2"><i class="fa fa-users"></i> Users</h2>
        <div class="btn-toolbar mb-2 mb-md-0">
            <form method='get' action='<?php echo $currenturl; ?>' class="form-inline mr-2">

                <label class="sr-only" for="limit">Records</label>
                <div class="input-group mr-1">
                    <select class="form-control" id="limit" name='limit' onchange="this.form.submit()">
                        <option value='25' <?php echo $limit == "25" ? "selected" : ""; ?>>25</option>
                        <option value='50' <?php echo $limit == "50" ? "selected" : ""; ?>>50</option>
                        <option value='100' <?php echo $limit == "100" ? "selected" : ""; ?>>100</option>
                        <option value='150' <?php echo $limit == "150" ? "selected" : ""; ?>>150</option>
                        <option value='200' <?php echo $limit == "200" ? "selected" : ""; ?>>200</option>
                    </select>
                </div>
                <label class="sr-only" for="q">Username</label>
                <div class="input-group mr-1">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class='fa fa-search'></i></div>
                    </div>

                    <select class="form-control" id="isactive" name='isactive'>
                        <option value=''>All Users</option>
                        <option value='1' <?php echo isset($request['isactive']) && $request['isactive'] == "1" ? "selected" : ""; ?>>Active Users</option>
                        <option value='0' <?php echo isset($request['isactive']) && $request['isactive'] == "0" ? "selected" : ""; ?>>Unactive Users</option>
                    </select>
                    <input type="date" class="form-control" id="activedate" name='activedate' value='<?php echo isset($request['activedate']) ? $request['activedate'] : ""; ?>' placeholder="Search">
                    <input type="search" class="form-control" id="q" name='q' value='<?php echo $q; ?>' placeholder="Search">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
                <!--<a class="btn btn-danger mx-1" target="_blank" href="../export_data.php?query=<?php echo $query; ?>"><i class="fa fa-file pr-2"></i>Export</a>-->

<!--<a class="btn btn-danger mx-1" target="_blank" href="../export_data.php?query=<?php echo "SELECT * from w_users $condtion order by ID desc"; ?>"><i class="fa fa-file pr-2"></i>Export All</a>-->
            </form>
            <form action="../export_data.php" method="post">
                <input type="hidden" name="query" value="<?php echo "SELECT * from w_users $condtion order by ID desc"; ?>"/>
                <button type="submit" class="btn btn-danger mx-1"><i class="fa fa-file pr-2"></i>Export All</button>
            </form>
        </div>
    </div>

    <?php
    if (isset($_SESSION['activate_course'])) {
        ?>
        <p class="alert alert-success"><?php echo $_SESSION['activate_course']; ?></p>
        <?php
    }
    ?>

    <div class="table-responsive">
        <table class="table table-sm table-striped">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>UniqueID</th>
                    <th>Sponsor</th>
                    <th>Parent</th>
                    <th>Franchise</th>
                    <th>Coin</th>
                    <th>Date</th>
                    <th>Active</th>
                    <th>Enabled</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody id="tabledata">
                <?php
                if ($data = $db->selectQuery($query)) {
                    foreach ($data as $d) {
                        $sponsor = $db->selectRow("w_users", "uName,uniqueID", "ID='{$d['uSponsor']}'");
                        $parent = $db->selectRow("w_users", "uName,uniqueID", "ID='{$d['underplaceID']}'");
                        $franchise = $db->selectRow("w_users", "uName,uniqueID", "ID='{$d['franchiseID']}'");
                        ?>
                        <tr id="datarow-<?php echo $d['ID'] ?>">
                            <td><?php echo $d['ID'] ?></td>
                            <td><img src="<?php echo ($d['uImg'] != '') ? $db->site . $d['uImg'] : $db->site . '/uploads/noimage.jpg'; ?>" width="48"></td>
                            <td><?php echo $d['uName'] ?></td>
                            <td><?php echo $d['uMobile'] ?></td>
                            <td><?php echo $d['uEmail'] ?></td>
                            <td><?php echo $d['uniqueID'] ?></td>
                            <td><?php echo "{$sponsor['uName']}<br>{$sponsor['uniqueID']}" ?></td>
                            <td><?php echo "{$parent['uName']}<br>{$parent['uniqueID']}" ?></td>
                            <td><?php echo "{$franchise['uName']}<br>{$franchise['uniqueID']}" ?></td>
                            <td><?php echo $d['uCoin'] ?></td>
                            <td><?php
                                echo "<p class='badge badge-secondary m-1'>J: {$d['createdAt']}</p>";
                                if ($d['isActive'] == 1) {
                                    echo "<p class='badge badge-success m-1'>A: {$d['activateDate']}</p>";
                                }
                                ?></td>
                            <td>
                                <?php
                                if ($d['isActive']) {
                                    echo "<p class='badge badge-secondary m-1'><i class='fas fa-circle text-success'></i></p>";
                                } else {
                                    echo "<p class='badge badge-secondary m-1'><i class='fas fa-circle text-danger'></i></p>";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($d['isDisabled'] == 0) {
                                    echo "<button class='badge badge-secondary border-0 statusbt m-1' data-id='{$d['ID']}' data-isdisabled='1'><i class='fas fa-circle text-success'></i></button>";
                                } else {
                                    echo "<button class='badge badge-secondary border-0 statusbt m-1' data-id='{$d['ID']}' data-isdisabled='0'><i class='fas fa-circle text-danger'></i></button>";
                                }
                                ?>
                            </td>

                            <td class="text-right">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="user-profile.php?rid=<?php echo $d['ID'] ?>" class="btn btn-info editubt" title="Edit" target="_blank"><i class="fas fa-edit"></i></a>
                                    <!--<a href="#" class="btn btn-info editubt" title="Edit" target="_blank"><i class="fas fa-edit"></i></a>-->
                                    <a href="page-downline-sp.php?uid=<?php echo $d['ID'] ?>" class="btn btn-warning treebt" title="View Tree" target="_blank"><i class="fas fa-network-wired"></i></a>
                                    <!--<button type="button" class="btn btn-primary userlogin" data-id="<?php echo $d['ID'] ?>" title="Login User"><i class="fas fa-eye"></i></button>-->
                                    <button type="button" class="btn btn-primary " data-id="<?php echo $d['ID'] ?>" title="Login User"><i class="fas fa-eye"></i></button>
                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"></button>
                                    <div class="dropdown-menu">
                                        <a href="page-activate.php?uid=<?php echo $d['ID'] ?>" class="dropdown-item" title="Activate User" target="_blank">Activate User</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="page-activate-course.php?uid=<?php echo $d['ID'] ?>" class="dropdown-item" target="_blank" title="Activate Course">Activate Course</a>
                                        <a href="page-coins-add.php?uid=<?php echo $d['ID'] ?>" class="dropdown-item" title="Coins Send" target="_blank">Send Coins</a>

                                        <a href="page-coins-list.php?uid=<?php echo $d['ID'] ?>" class="dropdown-item" title="Coins Send" target="_blank">View List Coins</a>
                                      <!--<a href="page-activateuser.php?ruid=<?php echo $d['UID'] ?>" class="dropdown-item" title="Activate Plan" target="_blank">Activate Package</a>-->
                                      <!--<a href="page-sendbv.php?ruid=<?php echo $d['UID'] ?>" class="dropdown-item" title="Send BV" target="_blank">Send Amount</a>-->
                                    </div>
                                </div>
                            </td>
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
if (isset($_SESSION['activate_course'])) {
    unset($_SESSION['activate_course']);
}
include_once("footer.php");
?>