<?php
$title = "Course Payments List";
$apage = "payments";
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
    $condtion[] = "(uID like '%" . $request['q'] . "%' or amount like '%" . $request['q'] . "%' or status like '%" . $request['q'] . "%')";
}

if (isset($request['limit']) && $request['limit'] != '') {
    $limit = $request['limit'];
}

//if (isset($request['isactive']) && $request['isactive'] != '') {
//    $condtion[] = "isActive='{$request['isactive']}'";
//}
//if (isset($request['franchiseID']) && $request['franchiseID'] != '') {
//    $condtion[] = "franchiseID='{$request['franchiseID']}'";
//}

if (count($condtion) > 0)
    $condtion = 'where ' . implode(" and ", $condtion);
else
    $condtion = '';

$totaldata = $db->countRows('w_orders', $condtion);
if (isset($request['page']) && $request['page'] > 1) {
    $page = $request['page'];
    $offset = $limit * ($page - 1);
}
$query = "SELECT * from w_orders $condtion order by ID desc LIMIT $limit OFFSET $offset";
?>
<main role="main" class="col-md-12 pt-3">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h2 class="h2"><i class="fa fa-money-bill-alt"></i> Course Payments Details</h2>
        <div class="btn-toolbar mb-2 mb-md-0">
            <form method='get' action='<?php echo $currenturl; ?>' class="form-inline mr-2">
                <label class="sr-only" for="q">Status</label>
                <div class="input-group mr-1">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class='fa fa-search'></i></div>
                    </div>

                    <select class="form-control" id="isactive" name='status'>
                        <option value=''>All Status</option>
                        <option value='success' <?php echo isset($request['status']) && $request['status'] == "success" ? "selected" : ""; ?>>Success</option>
                        <option value='pending' <?php echo isset($request['status']) && $request['status'] == "pending" ? "selected" : ""; ?>>Pending</option>
                        <option value='failed' <?php echo isset($request['status']) && $request['status'] == "failed" ? "selected" : ""; ?>>Failed</option>
                    </select>
                    <input type="search" class="form-control" id="q" name='q' value='<?php echo $q; ?>' placeholder="Search">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
                <!--<a class="btn btn-danger mx-1" target="_blank" href="../export_data.php?query=<?php echo $query; ?>"><i class="fa fa-file pr-2"></i>Export</a>-->

<!--<a class="btn btn-danger mx-1" target="_blank" href="../export_data.php?query=<?php // echo "SELECT * from w_users $condtion order by ID desc"; ?>"><i class="fa fa-file pr-2"></i>Export All</a>-->
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-sm table-striped">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Course ID</th>
                    <th>Order ID</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody id="tabledata">
                <?php
                if ($data = $db->selectQuery($query)) {
                    foreach ($data as $d) {
                        $getuser = $db->selectQuery("select * from w_users where ID = {$d['uID']}");
                        ?>
                        <tr id="datarow-<?php echo $d['ID']; ?>">
                            <td><?php echo $d['ID']; ?></td>
                            <td><?php echo $d['uID']; ?></td>
                            <td><?php echo $getuser[0]['uName']; ?></td>
                            <td><?php echo $d['courseID']; ?></td>
                            <td><?php echo $d['orderID']; ?></td>
                            <td><?php echo $d['amount'] / 100; ?></td>
                            <td><?php echo ($d['status'] != "") ? $d['status'] : "-"; ?></td>
                            <td class="text-right">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-danger deleterecord" data-id="<?php echo $d['ID']; ?>" title="Delete Record"><i class="fa fa-trash-alt"></i></button>
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
include_once("footer.php");
?>
<script>
    $(document).ready(function () {
        $('.deleterecord').click(function ()
        {
            var ele = $(this);
            var orderID = ele.attr('data-id');
            $.confirm({
                title: 'Confirm!',
                content: 'Are you sure?',
                buttons: {
                    yes: function () {
                        $.ajax({
                            type: "post",
                            url: "controller/edu_ajaxcontroller.php",
                            data: {orderID: orderID, req_type: "delete_record"},
                            success: function (data) {
//                                alert(data);
                                location.reload();
                            }
                        });
                    },
                    no: function () {

                    }
                }
            });
        });
    });
</script>
