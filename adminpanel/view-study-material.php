<?php
session_start();
$title = "View study material";
$apage = "viewstudymaterial";
include_once('header.php');

$categories = array();
if ($rrData = $db->selectRows("study_material")) {
    foreach ($rrData as $dd) {
        $categories[$dd['parent_category']] = $dd['parent_category'];
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
    $condtion[] = "(sub_category like '%" . $request['q'] . "%' or parent_category like '%" . $request['q'] . "%' or subject like '%" . $request['q'] . "%' or board like '%" . $request['q'] . "%' or study_type like '%" . $request['q'] . "%'or lesson like '%" . $request['q'] . "%')";
}

if (isset($request['parent_category']) && $request['parent_category'] != '') {
    $condtion[] = "parent_category='{$request['parent_category']}'";
}

if (count($condtion) > 0)
    $condtion = 'where ' . implode(" and ", $condtion);
else
    $condtion = '';

if ($totaldd = $db->selectQuery('SELECT count(ID) as totaldata from study_material ' . $condtion)) {
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
                <h2 class="h2"><i class="fa fa-trophy pr-2"></i>View Study Material</h2>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <form method='get' action='<?php echo $currenturl; ?>' class="form-inline mr-4">
                        <div class="input-group mr-1">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class='fa fa-search'></i></div>
                            </div>
                            <select class="form-control" id="category" name='parent_category'>
                                <option value=''>All Categories</option>
                                <?php
                                foreach ($categories as $catid => $catname) {
                                    $selected = $request['parent_category'] == $catname ? "selected" : "";
                                    echo "<option value='$catname' $selected>$catname</option>";
                                }
                                ?>
                            </select>
                            <input type="search" class="form-control" id="q" name='q' value='<?php echo $q; ?>' placeholder="Search">
                        </div>
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a class="btn btn-primary mx-1" href="add_sub_material.php">Add Study Material</a>
                    </form>
                </div>
            </div>
            <div class="col-12 col-md-12 mx-auto">
                <?php
                if (isset($_SESSION['updatecon'])) {
                    ?>
                    <h6 class="alert alert-success"><?php echo $_SESSION['updatecon']; ?></h6>
                    <?php
                } else if (isset($_SESSION['study_added'])) {
                    ?>
                    <h6 class="alert alert-success"><?php echo $_SESSION['study_added']; ?></h6>

                    <?php
                } else if (isset($_SESSION['AddMoreContest'])) {
                    ?>
                    <h6 class="alert alert-success"><?php echo $_SESSION['AddMoreContest']; ?></h6>
                    <?php
                } else if (isset($_SESSION['success'])) {
                    ?>
                    <h6 class="alert alert-success"><?php echo $_SESSION['success']; ?></h6>
                    <?php
                } 

                $rslt = mysqli_query($db->con, "SELECT * FROM `study_material` $condtion order by id DESC LIMIT $limit OFFSET $offset");
                if (mysqli_num_rows($rslt) > 0) {
                    ?>
                    <table class="table table-sm table-hover">
                        <thead class="bg-white">
                            <tr>
                                <th>#ID</th>
                                <th>Parent Category</th>
                                <th>Sub Category</th>
                                <th>Study Type</th>
                                <th>Subject</th>
                                <th>Lesson</th>
                                <!--<th>Description</th>-->
                                <th>Board</th>
                                <th>Ebook</th>
<!--                                <th>URL</th>
                                <th>Embed Link</th>-->
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_array($rslt)) {
                                ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo isset($row['parent_category']) ? $row['parent_category'] : '-'; ?></td> 
                                    <td><?php echo $row['sub_category']; ?></td>  
                                    <td><?php echo $row['study_type']; ?></td>
                                    <td><?php echo isset($row['subject'])&&$row['subject']!="" ? $row['subject'] : $row['sub_category']; ?></td>
                                    <td><?php echo $row['lesson']; ?></td>
                                    <!--<td><?php echo ($row['description']!="") ? $row['description'] : "-"; ?></td>-->
                                    <td><?php echo ($row['board']!='') ? $row['board'] : '-'; ?></td>
                                    <td><?php echo $row['ebook']; ?></td>
<!--                                    <td><?php echo $row['url']; ?></td>
                                     <td><?php echo $row['embed_link']; ?></td>-->

                                    <td class="text-right">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <!--<a target="_blank" href="#" class="btn btn-primary"><i class="fa fa-eye"></i></a>-->

                                            <a href="add_sub_material.php?study_id=<?php echo $row['id']; ?>" class="btn btn-success"><i class="fa fa-edit"></i></a>
                                            <button type="button" id="deletestudy" study-id="<?php echo $row['id']; ?>" class="btn btn-danger"><i class="fa fa-trash-alt"></i></button>

                                            <!--<a target="_blank" href="page-coins-list.php?roomID=<?php echo $row['id']; ?>" class="btn btn-info"><i class="fa fa-coins"></i></a>-->
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


if (isset($_SESSION['updatecon']) || isset($_SESSION['study_added']) || isset($_SESSION['AddMoreContest']) || isset($_SESSION['success'])) {
    unset($_SESSION['updatecon']);
    unset($_SESSION['study_added']);
    unset($_SESSION['AddMoreContest']);
    unset($_SESSION['success']);
}
?>


<script>
    $(document).ready(function ()
    {

        $('body').on('click', '#deletestudy', function ()
        {
            var ele = $(this);
            var study_id = ele.attr('study-id');
            $.confirm({
                title: 'Confirm!',
                content: 'Are you sure?',
                buttons: {
                    yes: function () {
                        $.ajax({
                            type: "post",
                            url: "controller/edu_ajaxcontroller.php",
                            data: {study_id: study_id, req_type:'deletestudy'},
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
