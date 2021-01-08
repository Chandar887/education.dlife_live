<?php
include_once('header.php');

if (isset($_GET['update_cat']) && $_GET['update_cat'] != "") {
    $update_cat = $_GET['update_cat'];

    $getData = $db->selectQuery("select * from edu_category where id = '$update_cat'");
//   print_r($getData);die;
}
?>

<div class="container-fluid">
    <main role="main" class="col-md-12 pt-3">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">

            <h1 class="h2"><i class="fa fa-"></i>Add Education Category</h1>

            <div class="btn-toolbar mb-2 mb-md-0">
                          <!--   <a class="btn btn-primary ml-1" href="page-userlist.php"><i class="fa fa-list pr-2"></i>Users List</a> -->
            </div>
        </div>

        <?php
        if (isset($update_cat)) {
            ?>
            <a href="add_edu_category.php" class="btn btn-primary mb-2">Add New</a>
            <?php
        }
        ?>

        <div class="row">

            <div class="col-lg-6 col-md-6 col-sm-12 edu_sub_category">
                <?php
                if (isset($_SESSION['cat_inserted'])) {
                    ?>
                    <p class="alert alert-success"><?php echo $_SESSION['cat_inserted']; ?></p>
                    <?php
                } else if (isset($_SESSION['checkCat'])) {
                    ?>
                    <p class="alert alert-danger"><?php echo $_SESSION['checkCat']; ?></p>
                    <?php
                } else if (isset($_SESSION['cat_updated'])) {
                    ?>
                    <p class="alert alert-success"><?php echo $_SESSION['cat_updated']; ?></p>
                    <?php
                } else if (isset($_SESSION['checkparentt'])) {
                    ?>
                    <p class="alert alert-danger"><?php echo $_SESSION['checkparentt']; ?></p>
                    <?php
                } else if (isset($_SESSION['cat_exist'])) {
                    ?>
                    <p class="alert alert-danger"><?php echo $_SESSION['cat_exist']; ?></p>
                    <?php
                }
                ?>


                <form class="border rounded p-4 submitform" action="controller/edu_controller.php" method="post" enctype="multipart/form-data">

                    <?php
                        if(isset($update_cat)){
                            ?>
                    <input type="hidden" name="old_cat" value="<?php echo $getData[0]['category_name']; ?>">
                    <?php
                        }
                    ?>
                    
                    <div class="form-group mb-2">
                        <label for="category_name">Category Name<span class="required">*</span></label>
                        <input type="text" name="category_name" class="form-control" value="<?php echo isset($update_cat) ? $getData[0]['category_name'] : ''; ?>"  required>
                    </div>

                    <div class="form-group mb-2 <?php echo isset($update_cat) && $getData[0]['parent_category'] == '' ? 'd-none' : ''; ?>">
                        <label for="category_id">Parent Category</label>
                        <?php // echo isset($update_cat) ? $getData[0]['parent_category'] : ''; ?>
                        <select class="form-control" id="parentselect" name="parent_category">
                            <option value="">No Parent</option>
                            <?php
                            $get_parent_cat = $db->selectQuery("SELECT category_name FROM `edu_category` where parent_category = ''");

                            foreach ($get_parent_cat as $parent_cat_name) {
                                if ($parent_cat_name['category_name'] != "") {
                                    ?>
                                    <option value="<?php echo $parent_cat_name['category_name']; ?>" <?php echo isset($update_cat) && $getData[0]['parent_category'] == $parent_cat_name['category_name'] ? 'selected' : ''; ?>><?php echo $parent_cat_name['category_name']; ?></option>
                                    <?php
                                }
                            }
                            ?>

                        </select> 
                    </div>

                    <div class="form-group mb-2 <?php echo isset($update_cat) && $getData[0]['parent_category'] != '' ? 'd-none' : ''; ?>" id="catimage">
                        <label for="cat_image">Category Image</label>
                        <input type="file" name="cat_image" class="form-control" value="<?php echo isset($update_cat) ? $getData[0]['cat_image'] : ''; ?>">
                    </div>

                    <div class="form-group mb-2" id="amount">
                        <label for="course amount">Course Amount</label>
                        <input type="number" name="course_amount" class="form-control" value="<?php echo isset($update_cat) ? $getData[0]['course_amount'] : ''; ?>">
                    </div>


                    <div class="form-group mb-2 <?php echo isset($update_cat) && $getData[0]['parent_category'] != '' ? '' : 'd-none'; ?>" id="demolink">
                        <label for="Demo Link">Demo Link(Optional)</label>
                        <input type="text" name="demo" class="form-control" value="<?php echo isset($update_cat) ? $getData[0]['demo'] : ''; ?>">
                    </div>

                    <?php
                    if (isset($update_cat)) {
                        ?>
                        <input type="hidden" name="id" value="<?php echo $update_cat ?>">
                        <?php
                    }
                    ?>

                    <div class="form-group mt-3">    
                        <button type="submit" name="<?php echo isset($update_cat) ? 'update' : 'submit'; ?>" value="<?php echo isset($update_cat) ? 'update_edu_category' : 'add_edu_category'; ?>" class="btn btn-primary btn-block button_submit"><?php echo isset($update_cat) ? 'Update' : 'Submit'; ?></button>
                    </div>

                </form>

            </div>

            <?php
//            echo "SELECT * FROM `edu_category` where parent='' order by id DESC";die;
            $categoriesData = $db->selectQuery("SELECT * FROM `edu_category` where parent_category='' order by id DESC");
//            print_r($categoriesData);die
            ?>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <?php foreach ($categoriesData as $catData) { ?>
                    <div class="row border p-2">
                        <div class="col-3">
                            <img src="<?php echo $catData['cat_image']; ?>" width="100px" height="100px">
                        </div>

                        <div class="col-6">
                            <p class="h4 font-weight-bold"><?php echo $catData['category_name']; ?></h5>
                            <p class="h2 <?php echo $catData['category_name'] == '6th To 12th' ? 'd-none' : ''; ?>">₹<?php echo $catData['course_amount']; ?></p>
                        </div>

                        <div class="col-3">
                            <div class="btn-group btn-group-sm" role="group">
                                <a title="Sub-Categories" data-toggle="collapse" href="#collapseExample<?php echo $catData['id']; ?>" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                                <a href="add_edu_category.php?update_cat=<?php echo $catData['id']; ?>" class="btn btn-success"><i class="fa fa-edit"></i></a>
                                <button type="button" id="deleteparent" cat-id="<?php echo $catData['id']; ?>" class="btn btn-danger"><i class="fa fa-trash-alt"></i></button>
                            </div>
                        </div>

                    </div>  
                    <div class="row border p-2">
                        <!--show sub categories-->
                        <div class="col-12">
                            <div class="collapse" id="collapseExample<?php echo $catData['id']; ?>">

                                <table class="table table-sm table-hover">
                                    <thead class="bg-white">
                                        <tr>
                                            <th>Sub Categories</th>
                                            <th class="text-left <?php echo $catData['category_name'] == '6th To 12th' ? 'text-center' : 'd-none'; ?>">Course Amount</th>
                                            <th class="text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        <?php
                                        $subcats = $db->selectQuery("select * from edu_category where parent_category = '{$catData['category_name']}'");
                                        foreach ($subcats as $subDt) {
                                            ?>
                                            <tr>
                                                <td class="text-left"><?php echo $subDt['category_name']; ?></td>
                                                <td class="<?php echo $catData['category_name'] == '6th To 12th' ? 'text-center' : 'd-none'; ?>">₹<?php echo $subDt['course_amount']; ?></td>
                                                <td class="text-right">
                                                    <div class="btn-group btn-group-sm" role="group">

                                                        <a href="add_edu_category.php?update_cat=<?php echo $subDt['id']; ?>" class="btn btn-success"><i class="fa fa-edit"></i></a>
                                                        <button type="button" id="deletecat" cat-id="<?php echo $subDt['id']; ?>" class="btn btn-danger"><i class="fa fa-trash-alt"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>    

                <?php } ?>


<!--                                <table class="table table-sm table-hover d-none">
    <thead class="bg-white">
        <tr>
            <th>Sub Category</th>
            <th>Course Amount</th>
        </tr>
    </thead>
    <tbody>
                <?php
//                        foreach ($categoriesData as $catData) {
                ?>
            <tr>
                <td><?php // echo $catData['id']           ?></td>
              
                <td><?php // echo $catData['category_name'];           ?></td>
              
                <td><?php // echo ($catData['course_amount'] != '') ? $catData['course_amount'] : '-';           ?></td>
                <td><a href="add_edu_category.php?update_cat=<?php echo $catData['id']; ?>" class="btn btn-success"><i class="fa fa-edit"></i></a></td>
                <td><button type="button" id="deletecat" cat-id="<?php echo $catData['id']; ?>" class="btn btn-danger"><i class="fa fa-trash-alt"></i></button></td>
            </tr>
                <?php
//                        }
                ?>
    </tbody>
</table>-->
            </div>
        </div>
    </main>

</div>
<?php
if (isset($_SESSION['cat_inserted']) || isset($_SESSION['checkCat']) || isset($_SESSION['cat_updated']) || isset($_SESSION['checkparentt']) || isset($_SESSION['cat_exist'])) {
    unset($_SESSION['cat_inserted']);
    unset($_SESSION['checkCat']);
    unset($_SESSION['cat_updated']);
    unset($_SESSION['checkparentt']);
    unset($_SESSION['cat_exist']);
}

include_once('footer.php');
?>



<script>
    $(document).ready(function ()
    {
        $('body').on('click', '#deletecat', function ()
        {
            var ele = $(this);
            var cat_id = ele.attr('cat-id');
            $.confirm({
                title: 'Confirm!',
                content: 'Are you sure?',
                buttons: {
                    yes: function () {

//                console.log(question_id);
                        $.ajax({
                            type: "post",
                            url: "controller/edu_ajaxcontroller.php",
                            data: {cat_id: cat_id, req_type: "delete_category"},
                            success: function (data) {
//                                alert(data);
                                var obj = jQuery.parseJSON(data);
                                var data = obj.data;
                                if (data == 1)
                                {
                                    location.reload();
                                } else {
                                    $.alert('Delete Process Failed!')
                                }
                            }
                        });
                    },
                    no: function () {

                    }

                }
            });
        });


//        delete whole parent category with sub categories
        $('body').on('click', '#deleteparent', function ()
        {
            var ele = $(this);
            var cat_id = ele.attr('cat-id');
            $.confirm({
                title: 'Confirm!',
                content: 'Are you sure?',
                buttons: {
                    yes: function () {

//                console.log(question_id);
                        $.ajax({
                            type: "post",
                            url: "controller/edu_ajaxcontroller.php",
                            data: {cat_id: cat_id, req_type: "deleteparent"},
                            success: function (data) {
//                                alert(data);
                                var obj = jQuery.parseJSON(data);
                                var data = obj.data;
//                                alert(data)
                                if (data == 1)
                                {
                                    location.reload();
                                } else {
                                    $.alert('Delete Process Failed!')
                                }
                            }
                        });
                    },
                    no: function () {

                    }

                }
            });
        });


        $('#parentselect').on('change', function () {
            if (this.value == "") {
                $('#catimage').removeClass('d-none');
                $('#amount').removeClass('d-none');
                $('#demolink').addClass('d-none');
            } else {
                $('#catimage').addClass('d-none');
                $('#amount').addClass('d-none');
                $('#demolink').removeClass('d-none');
            }
        });

    });

</script>
