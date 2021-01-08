<?php
include_once ('header.php');


$categories = array();

if ($tcat = $db->selectQuery("select * from edu_category order by parent_category")) {
    foreach ($tcat as $catt) {
        if ($catt['parent_category'] == "") {
//            str_clean
            $categories[$db->str_clean($catt['category_name'])] = array("name" => $catt['category_name'], "sub" => array());
        } else {
            $categories[$db->str_clean($catt['parent_category'])]["sub"][] = $catt['category_name'];
        }
    }
}

if (isset($_GET['study_id']) && $_GET['study_id'] != "") {
    $update_id = $_GET['study_id'];

    $updateData = $db->selectQuery("select * from study_material where id = '$update_id'");
}
?>

<style>
    .list-group {
        display: -webkit-box;
        display: -ms-flexbox;
        display: inline; 
        /* -webkit-box-orient: vertical; */
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        padding-left: 0;
        margin-bottom: 0;
    }
</style>

<div class="container-fluid">
    <main role="main" class="col-md-12 pt-3">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2"><i class="fa fa-"></i><?php echo isset($update_id) ? 'Update' : 'Add'; ?> Study Material</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a class="btn btn-primary ml-1" href="view-study-material.php"><i class="fa fa-list pr-2"></i>Study Material List</a> 
            </div>
        </div>
    </main>   

    <div class="col-lg-8 col-md-8 col-sm-12 mx-auto">

        <?php
        if (isset($_SESSION['study_material'])) {
            ?>
            <p class="alert alert-success"><?php echo $_SESSION['study_material']; ?></p>
            <?php
        }
        ?>

        <form class="border rounded p-4" action="controller/edu_controller.php" method="post" enctype="multipart/form-data">

            <!--            <div class="form-group mb-2">
                            Study Type<span class="required">*</span>
                            <select for="study_type" name="study_type" class="form-control" required="">  
                                <option value="">-Select-</option>
                                <option value="ebook">E-Book</option>
                                <option value="vbook">V-Book</option>
                            </select>
                        </div>-->

            <div class="form-group mb-2">
                <label for="ebook">Material Type</label><br/>
                <div class="form-group">
                    <label for="ebook" style="<?php echo isset($updateData[0]['ebook']) && $updateData[0]['ebook'] != "" ? 'background-color:green;' : 'background-color: #325D88;'; ?> color: white; padding: 7px; border-radius: 5px;"><input type="radio" id="ebook" class="d-none" name="study_type"  value="ebook" <?php echo isset($updateData[0]['ebook']) && $updateData[0]['ebook'] != "" ? 'checked' : ''; ?> required><span class="">E-Book </span></label>
                    <!--<label for="V-Book"><input type="radio" id="vbook" name="study_type" class="ml-2" value="vbook" <?php echo isset($updateData[0]['url']) && $updateData[0]['url'] != "" ? 'checked' : ""; ?> required ><span class="ml-1">V-Book </span></label>-->
                    <label for="embeded" style="<?php echo isset($updateData[0]['embed_link']) && $updateData[0]['embed_link'] != "" ? 'background-color:green;' : 'background-color: #325D88;'; ?> color: white; padding: 7px; border-radius: 5px;"><input type="radio" id="embeded" name="study_type" class=" d-none" value="vbook" <?php echo isset($updateData[0]['embed_link']) && $updateData[0]['embed_link'] != "" ? 'checked' : ''; ?> required ><span class="">V-Book</span></label>

                </div>
            </div>

            <div class="form-group mb-2 showebook" style="<?php echo isset($updateData[0]['ebook']) && $updateData[0]['ebook'] != '' ? 'display: block' : 'display: none'; ?>;">
                <label for="ebook">E-Book</label>
                <input type="file" name="ebook" id="ebookk" class="form-control" value="<?php echo isset($updateData[0]['ebook']) && $updateData[0]['ebook'] != "" ? $updateData[0]['ebook'] : ''; ?>">
            </div>

<!--            <div class="form-group mb-2 showvbook" style="<?php echo isset($updateData[0]['url']) && $updateData[0]['url'] != '' ? 'display: block' : 'display: none'; ?>;">
                <label for="V-Book">V-Book</label>
                <input type="file" name="url" class="form-control" value="<?php echo isset($updateData[0]['url']) && $updateData[0]['url'] != "" ? $updateData[0]['url'] : ''; ?>">
            </div>-->

            <div class="form-group mb-2 showembed" style="<?php echo isset($updateData[0]['embed_link']) && $updateData[0]['embed_link'] != '' ? 'display: block' : 'display: none'; ?>;">
                <label for="embed_link">V-Book</label>
                <textarea name="embed_link"  id="vbookk" rows="4" class="form-control" cols="50"><?php echo isset($updateData[0]['embed_link']) && $updateData[0]['embed_link'] != "" ? $updateData[0]['embed_link'] : ''; ?></textarea>
            </div>



            <div class="form-group mb-2">
                Parent Category<span class="required">*</span>
                <?php
                if (isset($updateData[0]['parent_category'])) {
                    $parett = $str = str_replace(' ', '_', $updateData[0]['parent_category']);
                }
                ?>
                <select for="Parent Category" id="selected_parent" class="form-control checkparentcat" required=""> 
                    <option value="">Select Any One</option>
                    <?php
                    foreach ($categories as $parentCat => $cat_array) {
                        ?>
                        <option value="<?php echo $parentCat; ?>" <?php echo isset($parett) && $parett == $parentCat ? 'selected' : ""; ?>><?php echo $parentCat; ?></option>

                        <?php
                    }
                    ?>
                </select>
            </div>

            <input type="hidden" class="checkparent" id="parentcategory" name="parent_category" value="<?php echo isset($updateData[0]['parent_category']) && $updateData[0]['parent_category'] != '' ? $updateData[0]['parent_category'] : ''; ?>">


            <div class="form-group mb-2">
                Sub Category<span class="required">*</span>
                <select for="sub_cat" id="subcategory" name="sub_category" class="form-control" required="">
                    <option value="">Select Any One</option>
                    <?php
                    if (isset($updateData[0]['parent_category'])) {
                        $parent = $updateData[0]['parent_category'];
                        $subcat = $db->selectQuery("select * from edu_category where parent_category = '$parent'");
                        foreach ($subcat as $subCatt) {
                            ?>
                            <option value="<?php echo $subCatt['category_name']; ?>" <?php echo isset($updateData[0]['sub_category']) && $updateData[0]['sub_category'] == $subCatt['category_name'] ? 'selected' : ""; ?>><?php echo $subCatt['category_name']; ?> </option>
                            <?php
                        }
                    }
                    ?>


                </select>
            </div>

            <div class="form-group mb-2 <?php echo isset($updateData[0]['parent_category']) && $updateData[0]['parent_category'] == '6th To 12th' ? '' : 'd-none'; ?>" id="subject">
                <label for="subject">Subject<span class="required">*</span></label>
                <input type="text" name="subject" class="form-control" value="<?php echo isset($updateData[0]['subject']) ? $updateData[0]['subject'] : ''; ?>">
            </div>  

            <div class="form-group mb-2">
                <label for="lesson">Lesson<span class="required">*</span></label>
                <input type="text" name="lesson" class="form-control" value="<?php echo isset($updateData[0]['lesson']) ? $updateData[0]['lesson'] : ''; ?>"  required>
            </div>

            <div class="form-group mb-2 <?php echo isset($updateData[0]['parent_category']) && $updateData[0]['parent_category'] == '6th To 12th' ? '' : 'd-none'; ?>" id="board">
                Select Board<span class="required">*</span>
                <select for="board" name="board" class="form-control" <?php echo ($parentname == '6th To 12th') ? 'required' : ''; ?>>
                    <option value="">-Select-</option>
                    <option value="PSEB" <?php echo isset($updateData[0]['board']) && $updateData[0]['board'] == 'PSEB' ? 'selected' : ''; ?>>PSEB</option>
                    <option value="CBSE" <?php echo isset($updateData[0]['board']) && $updateData[0]['board'] == 'CBSE' ? 'selected' : ''; ?>>CBSE</option>
                    <option value="Rajasthan" <?php echo isset($updateData[0]['board']) && $updateData[0]['board'] == 'Rajasthan' ? 'selected' : ''; ?>>Rajasthan</option>
                    <option value="Haryana" <?php echo isset($updateData[0]['board']) && $updateData[0]['board'] == 'Haryana' ? 'selected' : ''; ?>>Haryana</option>
                    <?php
//                    $boardName = $db->selectQuery("SELECT * FROM `study_board`");
//                    foreach ($boardName as $board) {
                    ?>
                        <!--<option value="<?php echo $board['board']; ?>" <?php // echo isset($updateData[0]['board']) && $updateData[0]['board'] == $board['board'] ? 'selected' : '';  ?>><?php // echo $board['board'];  ?></option>-->
                    <?php
//                    }
                    ?>
                </select>
            </div>


            <div class="form-group mb-2 <?php echo isset($updateData[0]['parent_category']) && $updateData[0]['parent_category'] == '6th To 12th' ? '' : 'd-none'; ?>" id="demolink">
                <label for="Demo Link">Demo Link</label>
                <input type="text" name="demo" class="form-control" value="<?php echo isset($update_id) ? $updateData[0]['demo'] : ''; ?>">
            </div>



            <div class="form-group mt-3"> 
                <?php
                if (isset($update_id)) {
                    ?>
                    <input type="hidden" name="id" value="<?php echo $update_id; ?>">

                    <button type="submit" name="update" value="update_study_material" class="btn btn-primary btn-block button_submit">Update</button>   
                    <?php
                } else {
                    ?>
                    <button type="submit" name="submit" value="add_study_material" class="btn btn-primary btn-block button_submit">Submit</button>           
                    <?php
                }
                ?>
            </div>

        </form>

    </div>
</div> 

<?php
if (isset($_SESSION['study_material'])) {
    unset($_SESSION['study_material']);
}

include_once ('footer.php');
?>

<script>
    $(document).ready(function () {
        $('#ebook').click(function ()
        {
            $("#vbookk").prop('required', false);
            $("#ebookk").prop('required', true);
            $('#vbookk').val('');
            $('.showvbook').hide();
            $('.showembed').hide();
            $('.showebook').css("display", "block");
        });

        $('#embeded').click(function ()
        {
            $("#ebookk").prop('required', false);
            $("#vbookk").prop('required', true);
            $('#ebookk').attr('value', '');
            $('.showvbook').hide();
            $('.showebook').hide();
            $('.showembed').css("display", "block");
        });

//        $('#vbook').click(function ()
//        {
//            $('.showebook').hide();
//            $('.showembed').hide();
//            $('.showvbook').css("display", "block");
//        });
    });
</script>

<script>

    var categories = JSON.parse('<?php echo json_encode($categories) ?>');
//    console.log(categories);
    $(document).ready(function () {
        $('#selected_parent').on('change', function () {
            var parent_category = $(this).val();
            $('#subcategory').find("option").remove();
            $('#subcategory').append("<option value=''>-Select-</option>");
            $('#parentcategory').val(categories[parent_category]['name']);
            $.each(categories[parent_category]['sub'], function (key, value) {
//                console.log(value);
                $('#subcategory').append("<option value='" + value + "'>" + value + "</option>");
//                $('#subcatname').val(parent_category);
            });

//            console.log(parent_category);
        });



//        check parent
        $('.checkparentcat').on('change', function () {
            var parent_category = $('.checkparent').val();
            if (parent_category != '6th To 12th') {
                $('#board').addClass('d-none');
                $('#subject').addClass('d-none');
                $('#demolink').addClass('d-none');
            } else {
                $('#board').removeClass('d-none');
                $('#subject').removeClass('d-none');
                $('#demolink').removeClass('d-none');
            }
        });

    });
</script>