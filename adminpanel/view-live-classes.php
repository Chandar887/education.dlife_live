<?php
session_start();
$title = "View Live Classes";
$apage = "viewliveclasses";
include_once('header.php');

$categories = array();
if ($rrData = $db->selectRows("live_class_data","DISTINCT category_name")) {
    foreach ($rrData as $dd) {
        $categories[$dd['category_name']] = $dd['category_name'];
    }
}
?>
<?php
$limit = 20;
$offset = 0;
$page = 1;
$totaldata = 0;
$q = '';
$condtion = array();

if (isset($request['q']) && $request['q'] != '') {
    $q = $request['q'];
    $condtion[] = "(category_name like '%" . $request['q'] . "%' or title like '%" . $request['q'] . "%' or description like '%" . $request['q'] . "%' or amount like '%" . $request['q'] . "%' or no_of_que like '%" . $request['q'] . "%'or embed_link like '%" . $request['q'] . "%')";
}

if (isset($request['category_name']) && $request['category_name'] != '') {
    $condtion[] = "category_name='{$request['category_name']}'";
}

if (count($condtion) > 0)
    $condtion = 'where ' . implode(" and ", $condtion);
else
    $condtion = '';

if ($totaldd = $db->selectQuery('SELECT count(ID) as totaldata from live_class_data ' . $condtion)) {
    $totaldata = $totaldd[0]['totaldata'];
}
if (isset($request['page']) && $request['page'] > 1) {
    $page = $request['page'];
    $offset = $limit * ($page - 1);
}
?>
<style>
    button.btn.btn-warning {
        background-color: yellow;
        border: yellow;
    }
</style>

<div class="container-fluid">
    <div class="row">

        <main role="main" class="col-md-12 pt-3">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h2 class="h2"><i class="fa fa-trophy pr-2"></i>View Live Classes</h2>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <form method='get' action='<?php echo $currenturl; ?>' class="form-inline mr-4">
                        <div class="input-group mr-1">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class='fa fa-search'></i></div>
                            </div>
                            <select class="form-control" id="category" name='category_name'>
                                <option value=''>All Categories</option>
                                <?php
                                foreach ($categories as $catid => $catname) {
                                    $selected = $request['category_name'] == $catname ? "selected" : "";
                                    echo "<option value='$catname' $selected>$catname</option>";
                                }
                                ?>
                            </select>
                            <input type="search" class="form-control" id="q" name='q' value='<?php echo $q; ?>' placeholder="Search">
                        </div>
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a class="btn btn-primary mx-1" href="add-live-class.php">Add New Class</a>
                    </form>
                </div>
            </div>
            <div class="col-12 col-md-12 mx-auto">
                <?php
                if (isset($_SESSION['live_class_update'])) {
                    ?>
                    <h6 class="alert alert-success"><?php echo $_SESSION['live_class_update']; ?></h6>
                    <?php
                } else if (isset($_SESSION['update_question'])) {
                    ?>
                    <h6 class="alert alert-success"><?php echo $_SESSION['update_question']; ?></h6>

                    <?php
                }

                $rslt = mysqli_query($db->con, "SELECT * FROM `live_class_data` $condtion order by id DESC LIMIT $limit OFFSET $offset");
                if (mysqli_num_rows($rslt) > 0) {
                    ?>
                    <table class="table table-sm table-hover">
                        <thead class="bg-white">
                            <tr>
                                <th>#ID</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Video Start Time</th>
                                <th>Quiz Start Time</th>
                                <!--<th>Embeded</th>-->
                                <!--<th>Video URL</th>-->
                                <th>Video Poster</th>
                                <th>Video Amount</th>
                                <th>Quiz Time</th>
                                <th>Total Questions</th>
                                <th>Status</th>

                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_array($rslt)) {
                                ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo isset($row['title']) ? $row['title'] : '-'; ?></td> 
                                    <td><?php echo $row['description']; ?></td>  

                                    <td><?php echo $row['category_name']; ?></td>
                                    <td><?php echo $row['start_time']; ?></td>
                                    <td><?php echo $row['end_time']; ?></td>

                                                                            <!--<td><?php echo isset($row['url']) ? $row['url'] : '-'; ?></td>-->
                                                                            <!--<td><?php echo isset($row['embed_link']) ? $row['embed_link'] : '-'; ?></td>-->
                                    <td><img src="<?php echo $row['thumbnail']; ?>" width="100px" height="100px"></td>
                                    <td><?php echo $row['amount']; ?></td>
                                    <td><?php echo $row['quiz_time']; ?></td>
                                    <td><?php echo $row['no_of_que']; ?></td>
                                    <td><?php
                                        if ($row['status'] == 0) {
                                            ?>
                                            <button type="button" class="btn btn-warning" title="Upcoming"></button>
                                            <?php
                                        } else if ($row['status'] == 1) {
                                            ?>
                                            <button type="button" class="btn btn-success" title="Pending"></button>
                                            <?php
                                        } else if ($row['status'] == 2) {
                                            ?>
                                            <button type="button" class="btn btn-danger" title="Completed"></button>
                                            <?php
                                        }
                                        ?>
                                    </td>

                                    <td class="text-right">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <?php
                                            if ($row['status'] == 0) {
                                                ?>
                                                <a target="_blank" href="live_class_questions.php?live_class_id=<?php echo $row['id']; ?>" title="View Questions" class="btn btn-primary"><i class="fa fa-eye"></i></a>

                                                <a href="add-live-class.php?live_class_id=<?php echo $row['id']; ?>" class="btn btn-success"><i class="fa fa-edit"></i></a>
                                                <button type="button" id="delliveclass" class-id="<?php echo $row['id']; ?>" class="btn btn-danger"><i class="fa fa-trash-alt"></i></button>
                                                <?php
                                            } else {
                                                ?>
                                                <a target="_blank" href="live_class_quiz.php?live_class_id=<?php echo $row['id']; ?>" title="View Quiz Details" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                                                <a target="_blank" href="page-coins-list.php?roomID=<?php echo $row['id']; ?>&type=livevideoquiz" class="btn btn-info"><i class="fa fa-coins"></i></a>
                                                    <?php
                                                }
                                                ?>
                                        </div>    
                                    </td>
                                </tr>
                                <?php
                            }
                            ?></tbody>
                        <?php
                    }
                    ?>

                </table>
            </div>
            <?php pagination($totaldata, $limit, $offset, $page, $q, $currenturl); ?>
        </main>

    </div>
</div>
<?php
include_once('footer.php');


if (isset($_SESSION['live_class_update']) || isset($_SESSION['update_question']) || isset($_SESSION['AddMoreContest'])) {
    unset($_SESSION['live_class_update']);
    unset($_SESSION['update_question']);
    unset($_SESSION['AddMoreContest']);
}
?>


<script>
    $(document).ready(function ()
    {
        $('body').on('click', '#delliveclass', function ()
        {
            var ele = $(this);
            var class_id = ele.attr('class-id');
            $.confirm({
                title: 'Confirm!',
                content: 'Are you sure?',
                buttons: {
                    yes: function () {
                        $.ajax({
                            type: "post",
                            url: "controller/edu_ajaxcontroller.php",
                            data: {class_id: class_id, req_type: 'delliveclass'},
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
