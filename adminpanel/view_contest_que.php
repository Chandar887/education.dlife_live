<?php
include_once('header.php');

    if(isset($_GET['contest_id']) && $_GET['contest_id'] != '')
    {
        $contest_id = $_GET['contest_id'];
        
        $contstq = mysqli_query($db->con, "SELECT * FROM `contest_que` where contest_id = '$contest_id'");
        
    }

?>
<div class="container-fluid">
    <main role="main" class="col-md-12 pt-3">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fa fa-"></i>View Contest Questions</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
                      <!--   <a class="btn btn-primary ml-1" href="page-userlist.php"><i class="fa fa-list pr-2"></i>Users List</a> -->
        </div>
    </div> 

<div class="col-12 col-md-12 mt-4">
    <?php
        if(mysqli_num_rows($contstq) > 0)
        {
    ?>
    <!--<h4 class="text-center">View Contest Questions</h4>-->
    <table class="table table-sm table-hover">
    <thead>
        <tr>
            <th>Sr No.</th>
            <!--<th>Contest Name</th>-->
            <th>Questions</th>
            <th colspan="4">Suggestions</th>
            <th>Answers</th>
            <th>Action</th>
        </tr>
    </thead>
   
        <?php
$x = 0;
                            while ($data=mysqli_fetch_assoc($contstq)) {
                                 $x++;
                                 ?>
                                  <tbody>
                                 <td><?php echo $x; ?></td>
                                 <?php
//                                    $contstname = mysqli_fetch_assoc(mysqli_query($db->con, "SELECT * FROM `quiz_contest` where id = '$contest_id'"));
                                 ?>  
                                 <!--<td><?php echo $contstname['contest_name']; ?></td>-->
                                 <td><?php echo $data['questions']; ?></td>
                                   <?php
                                    $dt = json_decode($data['suggestions']);
                                    ?>
                                   
                                        <td><?php echo $dt['0']; ?></td>
                                        <td><?php echo $dt['1']; ?></td>
                                        <td><?php echo $dt['2']; ?></td>
                                        <td><?php echo $dt['3']; ?></td>
                                   <td><?php echo $data['answer']; ?></td>     
                                   <td><a href="add_contest_que.php?update_id=<?php echo $data['id']; ?>" class="btn btn-primary"><i class="fa fa-edit"></i></a></td>
                              </tbody><?php  }
                               
                                ?>

    </table>
        <?php }else
        {
            echo "<h4 class='text-center'>No Records Found!</h4>";
        }
?>
    </main>
</div>  
    
    <?php
include_once('footer.php');


?>