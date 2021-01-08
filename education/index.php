<?php
include_once("include/quiz_header.php");
//    $url = $db->site . "cron.php";
// echo $url;die;
//
//
//$getcatData = $db->selectQuery("select * from `edu_category`");
//print_r($getcatData);die;
?>

<style type="text/css">
    .table.table-hover>thead>tr>td,
    .table.table-hover>thead>tr>th {
        border-top: 1px solid #F5F5F5;
        font-size: 14px;
        color: black;
        padding: 15px 15px;
    }

    .table.table-hover>tbody>tr>td,
    .table.table-hover>tbody>tr>td {
        border-top: 1px solid #F5F5F5;
        font-size: 14px;
        color: black;
        padding: 15px 15px;
    }

    .tb_content {
        display: none;
        /* Hide all elements by default */
    }

    .show {
        display: block;
    }

    .jconfirm-box {
        border: 8px solid #007bff;
        background: #f8f9fa;
        text-align: center;
    }

    /*    .rounded-pill_shadow {
            box-shadow: 0px 3px 7px 2px #0c080838;
        }*/

    .jconfirm.jconfirm-light .jconfirm-box .jconfirm-buttons {
        float: none;
    }
</style>

<!--<div class="container">-->

<section>


    <div id="demo" class="carousel slide" data-ride="carousel">
        <!--Indicators-->
        <ul class="carousel-indicators">
            <li data-target="#demo" data-slide-to="0" class="active"></li>
            <li data-target="#demo" data-slide-to="1"></li>
            <li data-target="#demo" data-slide-to="2"></li>
        </ul>

        <!--The slideshow-->
        <div class="carousel-inner">
            <?php
            $data = $db->selectQuery("select * from w_slider_images where type = 'education'");

            foreach ($data as $slider) {
            ?>
                <div class="carousel-item active">
                    <img src="<?php echo $db->site . $slider['img1']; ?>" class="banner_img">
                </div>

                <div class="carousel-item">
                    <img src="<?php echo $db->site . $slider['img2']; ?>" class="banner_img">
                </div>
                <?php if ($slider['img3'] != "") {
                ?>
                    <div class="carousel-item ">
                        <img src="<?php echo $db->site . $slider['img3']; ?>" class="banner_img">
                    </div>
                <?php
                }
                if ($slider['img4'] != "") {
                ?>
                    <div class="carousel-item ">
                        <img src="<?php echo $db->site . $slider['img4']; ?>" class="banner_img">
                    </div>
            <?php
                }
            }
            ?>
        </div>
    </div>
</section>
<!--</div>-->


<!-- view all contests -->


<?php
if (isset($_SESSION["ludouser"])) {

    $user_id = $_SESSION["ludouser"]["ID"];
}

$getcatData = $db->selectQuery("select DISTINCT parent_category from `edu_category`");
//$getcatData = $db->selectQuery("select * from `edu_category`");
//echo "<pre>";
//print_r($getcatData);die;
?>
<section>
    <div class="container-fluid">



        <div class="col-md-12 col-12">

            <div class="row mt-3">

                <?php
                $checkActiveCourse = array();
                if ($check = $db->selectQuery("select * from w_active_courses where uID={$userData['ID']}")) {
                    foreach ($check as $ccat)
                        $checkActiveCourse[$ccat['active_courses']] = $ccat;
                }

                foreach ($getcatData as $catData) {
                    if ($catData['parent_category'] != "") {
                        $parentcategory = $catData['parent_category'];
                        $catImage = $db->selectQuery("select * from `edu_category` where category_name = '$parentcategory'");
                        //                        echo $parentcategory;die;
                        //                        echo $catImage[0]['id']; 
                        $expdate = "";
                        $curntDate = "";
                        if (in_array($catImage[0]['id'], array_keys($checkActiveCourse))) {
                            $expdate = $checkActiveCourse[$catImage[0]['id']]['expiryTime'];
                            //                            echo "expdate ". $expdate;
                            $expdate = strtotime($expdate);
                            $curntDate = date("m-d-y");
                            //                            echo "curntdate ". $curntDate;
                            $curntDate = strtotime($curntDate);
                        }
                        $getSubcategories = $db->selectQuery("select * from `edu_category` where parent_category = '$parentcategory'");

                        $array = array();
                        $i = 0;
                        foreach ($getSubcategories as $subcat) {
                            $array[] = array($subcat['category_name'] => $subcat['demo']);
                            $i++;
                        }

                        $cat_demo = json_encode($array);
                ?>
                        <div class="col-md-3 col-4 text-center <?php echo (in_array($catImage[0]['id'], array_keys($checkActiveCourse)) && $curntDate < $expdate) ? ($parentcategory == '6th To 12th') ? 'course_purchased' : 'course_purchased' : 'category_name'; ?>" parent_cateogory="<?php echo (in_array($catImage[0]['id'], array_keys($checkActiveCourse)) && $curntDate < $expdate) ? ($parentcategory == '6th To 12th') ? $parentcategory : $catImage[0]['id'] : ''; ?>" parent="<?php echo $catImage[0]['category_name']; ?>" parentID="<?php echo $catImage[0]['id']; ?>" amount="<?php echo $catImage[0]['course_amount']; ?>" category-demo='<?php echo $cat_demo; ?>'>
                            <?php
                            if (in_array($catImage[0]['id'], array_keys($checkActiveCourse)) && $curntDate < $expdate) {
                                $exptime = time_elapsed_string($checkActiveCourse[$catImage[0]['id']]['expiryTime']);
                            ?>

                                <span class="bg-danger text-white rounded" style="font-size: 10px;padding: 2px 3px 2px 3px;position: absolute;">
                                    <?php echo ($parentcategory === '6th To 12th') ? '' : $exptime . ' Left'; ?></span>

                            <?php
                            }
                            ?>
                            <img src="<?php echo $catImage[0]['cat_image']; ?>" class="rounded-circle" style="width: 100vw; height: 22vw;">
                            <p style="font-size:12px;"><b><?php echo $parentcategory; ?></b></p>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</section>


<?php
include_once('include/quiz_footer.php');
?>


<script type="text/javascript">
    $(document).ready(function() {
        $('body').on('click', '.category_name', function() {
            var ele = $(this);
            var parent_category = ele.attr('parent_cateogory');
            var parent = ele.attr('parent');
            var parentID = ele.attr('parentID');


            if (parent == '6th To 12th') {
                $('body').append('<div class="loader"><div class="spinner-border text-primary" role="status"><span class="sr-only"></span></div></div>');
                window.location = "sub-category.php?parent_category=" + parent;
                exit();
            }

            // var amount = ele.attr('amount');
            var user_id = '<?php echo $userData['ID']; ?>';
            var cat_demo = ele.attr('category-demo');
            var obj = jQuery.parseJSON(cat_demo);
            var demo = "";
            if (Object.keys(obj).length > 0) {
                var demo = "<div class='col-12 p-2 mb-2 border'><div class='h5 text-center' style='letter-spacing:2px'>DEMO</div>";

                console.log(obj);
                $.each(obj, function(kk, vv) {
                    var key = Object.keys(vv)[0];
                    var value = Object.values(vv)[0];
                    value = value === null ? "#" : value;
                    console.log(key + "====" + value);
                    if (value != "") {
                        demo += "<a href=" + value + " class='btn btn-sm m-1 btn-success btn-inline' target='_blank'>" + key + "</a>";
                    }
                });

                demo += "</div>";
            }
            $.confirm({
                title: "<div class='pt-2 pb-2 pr-3 pl-3'><h6 class='my-auto'>Want to Purchase Course?</h6></div>",
                content: "<div class='text-center text-primary' style=margin-bottom:-20px;'><b style='font-size: 45px;'></b></div><hr class='w-50 mx-auto'><br><div class='text-center text-primary' style='margin-top: -40px;margin-bottom: 14px;'><b style='font-size: 25px;'>" + parent + "</b></div><form action='payment.php' method='post' class='form-inline' style='justify-content: center;'><span style='font-size:22px;font-weight: 500;'>Rs.</span> <input type='number' name='amount' class='form-control w-50 amount' placeholder='Enter Amount'></form><br>" + demo + " ",
                closeIcon: true,
                buttons: {
                    Pay: {
                        text: 'PURCHASE NOW',
                        btnClass: 'btn-primary',
                        action: function() {
                            var amount = this.$content.find('.amount').val();
                            if (amount == "") {
                                $.alert('Please Enter Amount!');
                                return false;
                            } else {
                                $('body').append('<div class="loader"><div class="spinner-border text-primary" role="status"><span class="sr-only"></span></div></div>');
                                window.location = "payment.php?parent_category=" + parentID + "& uid=" + user_id + "& amount=" + amount;
                            }
                        }
                    },
                    //                    Cancel: function () {
                    //
                    //                    }

                },

                //                content: "<div class='pt-2 pb-2 pr-3 pl-3'><h6 class='my-auto'>Want to Purchase Course?</h6></div>"

            });

        });
        //        course purchased
        $('.course_purchased').click(function() {

            $('body').append('<div class="loader"><div class="spinner-border text-primary" role="status"><span class="sr-only"></span></div></div>');

            var ele = $(this);
            var parent_category = ele.attr('parent');
            window.location = "sub-category.php?parent_category=" + parent_category;
        });
    });
</script>