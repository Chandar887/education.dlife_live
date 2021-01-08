<?php
session_start();
$title = "View Questions";
$apage = "viewquestions";
include_once('header.php');

$categories = array();
if ($rrData = $db->selectRows("contest_que", "DISTINCT category_name")) {
    foreach ($rrData as $dd) {
        $categories[] = $dd['category_name'];
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
    $condtion[] = "(category_name like '%" . $request['q'] . "%' or questions like '%" . $request['q'] . "%' or suggestions like '%" . $request['q'] . "%')";
}

if (isset($request['category']) && $request['category'] != '') {
    $condtion[] = "category_name='{$request['category']}'";
}

if (count($condtion) > 0)
    $condtion = 'where ' . implode(" and ", $condtion);
else
    $condtion = '';

if ($totaldd = $db->selectQuery('SELECT count(ID) as totaldata from contest_que ' . $condtion)) {
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
                <h2 class="h2"><i class="fa fa-trophy pr-2"></i>View All Questions</h2>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <form method='get' action='<?php echo $currenturl; ?>' class="form-inline mr-2">
                        <div class="input-group mr-1">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class='fa fa-search'></i></div>
                            </div>
                            <select class="form-control" id="category" name='category'>
                                <option value=''>All Categories</option>
                                <?php
                                foreach ($categories as $catid => $catname) {
                                    $selected = $request['category'] == $catname ? "selected" : "";
                                    echo "<option value='$catname' $selected>$catname</option>";
                                }
                                ?>
                            </select>
                            <input type="search" class="form-control" id="q" name='q' value='<?php echo $q; ?>' placeholder="Search">
                        </div>
                        <button type="submit" class="btn btn-primary">Search</button>

<!--<a class="btn btn-primary mx-1" href="page-useradd.php"><i class="fa fa-user-plus pr-2"></i>New User</a>-->
                    </form>


                    <!--************upload excel file************-->

                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#uploadfile">
                        Import Questions
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="uploadfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Upload Excel File</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="controller/controller.php" method="post" enctype="multipart/form-data">
                                        <div class="form-group input-group mb-3">
                                            <div class="custom-file">
                                                <input type="file" name="question_file" class="custom-file-input" accept=".xls,.xlsx" id="inputGroupFile01">
                                                <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                            </div>
                                        </div>



                                        <div class="form-group">
                                            <button type="submit" name="upload_file" value="upload_question_file" class="btn btn-primary">Upload</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <!--                            <button type="button" class="btn btn-primary">Save changes</button>-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--************upload excel file section ends************-->


                </div>
            </div>
            <div class="col-12 col-md-12 mx-auto">
                <?php
                if (isset($_SESSION['update_question'])) {
                    ?>
                    <h6 class="alert alert-success"><?php echo $_SESSION['update_question']; ?></h6>

                    <?php
                } else if (isset($_SESSION['que_imported'])) {
                    ?>
                    <h6 class="alert alert-success"><?php echo $_SESSION['que_imported']; ?></h6>
                    <?php
                }


                $rslt = mysqli_query($db->con, "SELECT * FROM `contest_que` $condtion order by id DESC LIMIT $limit OFFSET $offset");
                if (mysqli_num_rows($rslt) > 0) {
                    ?>
                    <table class="table table-sm table-hover">
                        <thead class="bg-white">
                            <tr>
                                <th>#ID</th>
                                <th>Category Name</th>
                                <th>Question</th>
                                <!--<th>Question Image</th>-->
                                <th>A</th>
                                <th>B</th>
                                <th>C</th>
                                <th>D</th>
                                <th>Answer</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_array($rslt)) {
                                ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['category_name']; ?></td>  
                                    <td><?php echo $row['questions']; ?></td>  
                                    <!--<td class="text-center"><?php echo ($row['que_image']!='') ? "<img src='$row[que_image]' style='height:70px!important;'>" : "<b>-</b>"; ?></td>-->
                                    <?php
                                    $get_suggestions = $row['suggestions'];
                                    $jsonData = json_decode($get_suggestions, true);
                                    ?>
                                    <td><?php echo (filter_var($jsonData['0'], FILTER_VALIDATE_URL)) ? "<img src='$jsonData[0]' width='70px' height='70px'>" : $jsonData['0']; ?></td>
                                    <td><?php echo (filter_var($jsonData['1'], FILTER_VALIDATE_URL)) ? "<img src='$jsonData[1]' width='70px' height='70px'>" : $jsonData['1']; ?></td>
                                    <td><?php echo (filter_var($jsonData['2'], FILTER_VALIDATE_URL)) ? "<img src='$jsonData[2]' width='70px' height='70px'>" : $jsonData['2']; ?></td>
                                    <td><?php echo (filter_var($jsonData['3'], FILTER_VALIDATE_URL)) ? "<img src='$jsonData[3]' width='70px' height='70px'>" : $jsonData['3']; ?></td>
                                    <td><?php echo $row['answer']; ?></td>

                                    <td class="text-right">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <!--<a target="_blank" href="view_contest.php?contest_id=<?php echo $row['id']; ?>" class="btn btn-primary"><i class="fa fa-eye"></i></a>-->

                                            <a href="add_questions.php?update_id=<?php echo $row['id']; ?>" class="btn btn-success"><i class="fa fa-edit"></i></a>
                                            <button type="button" id="deleteque" question-id="<?php echo $row['id']; ?>" class="btn btn-danger"><i class="fa fa-trash-alt"></i></button>

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


if (isset($_SESSION['update_question']) || isset($_SESSION['que_imported'])) {
    unset($_SESSION['update_question']);
    unset($_SESSION['que_imported']);
}
?>


<script>
    $(document).ready(function ()
    {
        $('body').on('click', '#deleteque', function ()
        {
            var ele = $(this);
            var question_id = ele.attr('question-id');
            $.confirm({
                title: 'Confirm!',
                content: 'Are you sure?',
                buttons: {
                    yes: function () {

//                console.log(question_id);
                        $.ajax({
                            type: "post",
                            url: "controller/ajaxcontroller.php",
                            data: {question_id: question_id, req_type: "delete_question"},
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
