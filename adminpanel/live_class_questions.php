<?php
session_start();
//$title = "View Questions";
//$apage = "viewquestions";
include_once('header.php');

if (isset($_GET['live_class_id']) && $_GET['live_class_id'] != "") {
    $live_class_id = $_GET['live_class_id'];

//    $liveClassQue = $db->selectQuery("select * from live_class_que where live_class_id = '$live_class_id'");
}

//$categories = array();
//if ($rrData = $db->selectRows("live_class_que", "id=".$live_class_id)) {
//    foreach ($rrData as $dd) {
//        $categories[] = $dd['category_name'];
//    }
//}
?>
<?php
//$limit = 20;
//$offset = 0;
//$page = 1;
//$totaldata = 0;
//$q = '';
//$condtion = array();
//
//if (isset($request['q']) && $request['q'] != '') {
//    $q = $request['q'];
//    $condtion[] = "(category_name like '%" . $request['q'] . "%' or questions like '%" . $request['q'] . "%' or suggestions like '%" . $request['q'] . "%')";
//}
//
//if (isset($request['category']) && $request['category'] != '') {
//    $condtion[] = "category_name='{$request['category']}'";
//}
//
//if (count($condtion) > 0)
//    $condtion = 'where ' . implode(" and ", $condtion);
//else
//    $condtion = '';
//
//if ($totaldd = $db->selectQuery('SELECT count(ID) as totaldata from contest_que ' . $condtion)) {
//    $totaldata = $totaldd[0]['totaldata'];
//}
//if (isset($request['page']) && $request['page'] > 1) {
//    $page = $request['page'];
//    $offset = $limit * ($page - 1);
//}
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
                <h2 class="h2"><i class="fa fa-trophy pr-2"></i>Live Class Questions</h2>
            </div>
            <div class="col-12 col-md-12 mx-auto">
               <?php

                $rslt = mysqli_query($db->con, "select * from live_class_que where live_class_id = '$live_class_id'");
                if (mysqli_num_rows($rslt) > 0) {
                    ?>
                    <table class="table table-sm table-hover">
                        <thead class="bg-white">
                            <tr>
                                <th>#ID</th>
                                <th>Live Class ID</th>
                                <th>Category Name</th>
                                <th>Question</th>
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
                                    <td><?php echo $row['live_class_id']; ?></td>
                                    <td><?php echo $row['category_name']; ?></td>  
                                    <td><?php echo $row['questions']; ?></td>  

                                    <?php
                                    $get_suggestions = $row['suggestions'];
                                    $jsonData = json_decode($get_suggestions, true);
                                    ?>
                                    <td><?php echo $jsonData['0']; ?></td>
                                    <td><?php echo $jsonData['1']; ?></td>
                                    <td><?php echo $jsonData['2']; ?></td>
                                    <td><?php echo $jsonData['3']; ?></td>
                                    <td><?php echo $row['answer']; ?></td>

                                    <td class="text-right">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <!--<a target="_blank" href="view_contest.php?contest_id=<?php echo $row['id']; ?>" class="btn btn-primary"><i class="fa fa-eye"></i></a>-->

                                                    <a href="update_live_que.php?question_id=<?php echo $row['id']; ?>" class="btn btn-success"><i class="fa fa-edit"></i></a>
                                                    <!--<button type="button" id="live_class_que" question-id="<?php echo $row['id']; ?>" class="btn btn-danger"><i class="fa fa-trash-alt"></i></button>-->
                                        </div>    
                                    </td>
                                </tr>
                                <?php
                            }
                            ?></tbody>
                        <?php
                    } else {
                        ?>
                        <div class="m-4 p-5">
                            <div class="alert alert-danger">
                                <strong>Missing!</strong> Record not Found.
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </table>
            </div>

        </main>

    </div>
</div>
<?php
include_once('footer.php');
?>


<script>
    $(document).ready(function ()
    {
        $('body').on('click', '#live_class_que', function ()
        {
            var ele = $(this);
            var question_id = ele.attr('question-id');
//            console.log(question_id);
            $.confirm({
                title: 'Confirm!',
                content: 'Are you sure?',
                buttons: {
                    yes: function () {

//                console.log(question_id);
                        $.ajax({
                            type: "post",
                            url: "controller/edu_ajaxcontroller.php",
                            data: {question_id: question_id, req_type: "live_que_deletion"},
                            success: function (data) {
//                                alert(data);
//                                console.log(data);
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
