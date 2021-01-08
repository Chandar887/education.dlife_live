<?php
include_once("include/quiz_header.php");
// where DATE_FORMAT(start_time, '%Y-%m-%d') = DATE(NOW())
//echo $formatDate;die;

$live_classData = $db->selectQuery("select * from live_class_data where (status = 0 or status = 1) and DATE_FORMAT(start_time, '%Y-%m-%d') = DATE(NOW()) order by id DESC");
?> 

<style>
    .jconfirm-box{
        border: 8px solid #007bff;
        background: #f8f9fa;
        text-align: center;
    }

    .rounded-pill_shadow {
        box-shadow: 0px 3px 7px 2px #0c080838;
    }

    .jconfirm.jconfirm-light .jconfirm-box .jconfirm-buttons{
        float:none;
    }
</style>

<section>
    <div class="container-fluid">
        <div class="col-12">
            <div class="rounded bg-light my-3" style="border-left: 10px solid #007bff;">
                <h5 class="text-center my-auto p-1"><b>LIVE CLASSES</b></h5>
            </div>
            
            <div class="content">
            <?php
            foreach ($live_classData as $data) {
                $currntDate = date("Y-m-d h:i:s");
                $currntDate = strtotime($currntDate);

                $start_time = $data['start_time'];
                $start_time = strtotime($start_time);
                $id = $data['id'];
                if ($currntDate >= $start_time) {
                    mysqli_query($db->con, "update live_class_data set status = 1 where id = '$id'");
                }
                ?>
                <!-- Card -->
                <div style="border:4px solid #0069D9;" class="card booking-card  mb-3 p-2 checktime checkendtime" starttime="<?php echo $data['start_time']; ?>" endtime="<?php echo $data['end_time']; ?>" data-id="<?php echo $data['id']; ?>">

                    <!-- Card image -->
                    <div>
                        <img class="card-img-top" src="<?php echo $data['thumbnail']; ?>" alt="Card image cap" >
                    </div>

                    <div class="showdiv" style="display: none;">
                        <?php echo $data['url']; ?>
                    </div>

                    <!-- Card content -->
                    <div class="card-body">

                        <!-- Title -->
                        <h6 class="card-title font-weight-bold mb-1"><?php echo $data['title']; ?></h6>

                        <!-- Text -->
                        <p class="card-text m-0 text-secondary cardp"><?php echo $data['description']; ?></p>

                        <div class="mt-1" style="display:flex;">

                            <p class="card-text text-secondary cardp mb-1"><?php echo $data['category_name']; ?></p>


                            <p class="card-text ml-auto text-secondary cardp">₹<?php echo $data['amount']; ?></p>
                        </div>

                        <?php
//                        $currntDate = date("Y-m-d h:i:s");
//                        $currntDate = strtotime($currntDate);
//                        $start_time = $data['start_time'];
//                        $start_time = strtotime($start_time);
//                        $end_time = $data['end_time'];
//                        $end_time = strtotime($end_time);
//
//                        $start = time_elapsed_string($data['start_time']);
//                        $enddtime = time_elapsed_string($data['end_time']);
                        ?>
                        <div style="display:flex;">
                            <p class="card-text text-secondary cardp showtext mb-1" id="showtext"></p>
                            <p class="card-text ml-auto text-success cardp play_time end_time"></p>
                        </div> 

                        <div style="display:flex;">

                            <p class="card-text text-secondary cardp quiztext mb-1"></p>

                            <p class="card-text ml-auto text-success cardp quiztime"></p>

                        </div> 

                        <?php
                        $user_id = $userData['ID'];
                        $checkvideouser = $db->countRows("w_user_coins", "roomID='{$data['id']}' and type='livevideoquiz' and uID='$user_id'");
                        ?>
                        <div>
                            <button type="button" live-class-id="<?php echo $data['id']; ?>" amount="<?php echo $data['amount']; ?>" video-title="<?php echo $data['title']; ?>" class="btn btn-sm btn-primary ml-auto w-100 d-none mt-2 mb-1 <?php echo ($checkvideouser == 0) ? 'play_video' : 'videoPurchased'; ?>">Play Video</button>

                            <button type="button" live-class-id="<?php echo $data['id']; ?>" totalque="<?php echo $data['no_of_que']; ?>" class="btn btn-sm btn-primary ml-auto w-100 d-none play_quiz mt-2">Play Quiz</button>
                        </div>


                        <!-- Button -->
                    </div>
                </div>

                <?php
            }
            ?>
            </div>   
        </div>
    </div>
</section>

<?php
include_once("include/quiz_footer.php");
?>

<script>
    $(document).ready(function ()
    {
        
        $('.button').removeClass("btn btn-default");
        $('.button').addClass("btn btn-primary");
//        show the message if no live classes are available
         if ($('.content *').length === 0) {
            $('.content').append('<div class="my-3"><p class="alert text-center" style="color: white; background-color: #e03c3c;;border-color: #e03c3c;">No Content Available!</p></div>');
        }
        
        
//        live class check time of play video
        var x = {};
        $('.checktime').each(function () {
            var ele = $(this);
            var starttime = ele.attr("starttime");
            var data_id = ele.attr("data-id");
            //            console.log(starttime);
            // Update the count down every 1 second

            x[data_id] = setInterval(function () {

                var countDownDate = new Date(starttime).getTime();

//                ele.find('.quiztext').hide();
//                ele.find('.quiztext').hide();
                ele.find('.showtext').text('Play Video After');
                // Get today's date and time
                var now = new Date().getTime();

                // Find the distance between now and the count down date
                var distance = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds

                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Output the result in an element with id="demo"

                ele.find('.play_time').text(hours + "h " + minutes + "m " + seconds + "s ");
                //                console.log(document.getElementsByClassName("play_time").innerHTML = hours + "h "
                //                        + minutes + "m " + seconds + "s ");

                // If the count down is over, write some text 
                if (distance < 0) {

                    ele.find('.play_time').addClass('d-none');
                    ele.find('.showtext').addClass('d-none');
                    ele.find('.play_video').removeClass('d-none');
                    ele.find('.videoPurchased').removeClass('d-none');
                    //                    ele.find('.play_quiz').removeClass('d-none');

                    clearInterval(x[data_id]);
                    document.getElementsByClassName("play_time").innerHTML = "Play Quiz!";
                    //                     $('.play_video').addClass('d-none');
                    //                    $('.play_quiz').removeClass('d-none');
                }

            }, 1000);
        });



        //        check end time of video after start scrtipt
        var y = {};
        $('.checkendtime').each(function () {
            var ele = $(this);
            var endtime = ele.attr("endtime");
            var data_id = ele.attr("data-id");
            console.log(endtime);
            // Update the count down every 1 second

            y[data_id] = setInterval(function () {

                var countDownDate = new Date(endtime).getTime();
                //                console.log(countDownDate);
                ele.find('.quiztext').text('Play Quiz After');
                // Get today's date and time
                var now = new Date().getTime();

                // Find the distance between now and the count down date
                var distancee = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds

                var hours = Math.floor((distancee % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distancee % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distancee % (1000 * 60)) / 1000);

                // Output the result in an element with id="demo"
                //                document.getElementsByClassName("play_time").innerHTML = hours + "h "
                //                        + minutes + "m " + seconds + "s ";
                ele.find('.quiztime').text(hours + "h " + minutes + "m " + seconds + "s ");
                //                console.log(document.getElementsByClassName("play_time").innerHTML = hours + "h "
                //                        + minutes + "m " + seconds + "s ");

                // If the count down is over, write some text 
                if (distancee < 0) {
                    ele.find('.quiztext').hide();
                    ele.find('.play_time').addClass('d-none');
                    ele.find('.showtext').addClass('d-none');
                    ele.find('.play_video').addClass('d-none');
                    ele.find('.quiztime').addClass('d-none');
                    ele.find('.videoPurchased').addClass('d-none');
                    ele.find('.play_quiz').removeClass('d-none');

                    clearInterval(y[data_id]);
                    //                    document.getElementsByClassName("play_time").innerHTML = "Play Quiz!";
                    //                    $('.play_video').addClass('d-none');
                    //                    $('.play_quiz').removeClass('d-none');
                }

            }, 1000);
        });




        //        ********play video script
        $('body').on('click', '.play_video', function ()
        {
            var ele = $(this);
            var live_class_id = ele.attr('live-class-id');
            var user_id = '<?php echo $userData['ID']; ?>';
            var amount = ele.attr('amount');
            var video_title = ele.attr('video-title');
            //            console.log(user_id);
            $.confirm({
                title: "<div class='pt-2 pb-2 pr-3 pl-3'><h6 class='my-auto'>Want to Purchase Video?</h6></div>",
                content: "<div class='text-center text-primary' style=margin-bottom:-20px;'><b style='font-size: 45px;'>₹" + amount + "</b></div><hr class='w-50 mx-auto'><br><div class='text-center text-primary' style='margin-top: -37px; margin-bottom:-20px;'><b style='font-size: 25px;'>" + video_title + "</b></div><br><small>The Amount will be debited from your account.</small>",
//                title: 'Are you sure to play video?',
//                content: '<small>The Video Amount <b style="font-size: 16px;color: #e42929;">Rs.' + amount + '</b> will be debited from your account.</small>',
                closeIcon: true,
                buttons: {
                    Play: {
                        text: 'PAY NOW',
                        btnClass: 'btn-primary',
                        action: function () {
                            $.ajax({
                                type: "POST",
                                url: "controller/ajaxcontroller.php",
                                data: {live_class_id: live_class_id, user_id: user_id, req_type: "playlivevideo"},
                                //                            dataType: "json",
                                success: function (data) {
                                    var obj = jQuery.parseJSON(data);
                                    //                                alert(data);
                                    //                                        console.log(data);
                                    if (obj.data == 0)
                                    {
                                        $.alert('Not Enough Coins!');
                                    } else if (obj.data == 1)
                                    {
                                        $.confirm({
                                            title: 'Congratulations!',
                                            content: '<small>Video Purchased Successfully!</small>',
                                            type: 'green',
                                            typeAnimated: true,
                                            buttons: {
                                                yes: {
                                                    text: 'OK',
                                                    btnClass: 'btn-green',
                                                    action: function () {
                                                        window.location = "preview.php?live_class_id=" + live_class_id;
                                                    }
                                                },
//                                            close: function () {
//                                            }
                                            }
                                        });
                                    }

                                }
                            });
                        }
                    },
//                    Cancel: function () {
//
//                    }

                }
            }
            );

        });



        //        play quiz script***************
        $('.play_quiz').click(function ()
        {
            var ele = $(this);
            var live_class_id = ele.attr('live-class-id');
            var totalque = ele.attr('totalque');
            var user_id = '<?php echo $userData['ID']; ?>';


            $.confirm({
                title: "<div class='pt-2 pb-2 pr-3 pl-3'><h6 class='my-auto'>'Want to Play Quiz?</h6></div>",
                content: "<div class='text-center text-primary' style='margin-top: -30px; margin-bottom:-20px;'></div><br><small>Winning Amount Depends Upon No. of Users.</small><br><small>Total Questions - "+ totalque +"</small>",
//                title: 'Want to Play Quiz?',
//                content: '<span class="text-danger">Note: </span><small>Winning Amount Depends Upon No. of Users.</small><br><span class="text-danger">Note: </span><small>Total Questions : <b>' + totalque + '</b></small>',
                closeIcon: true,
                buttons: {
                    Play: {
                        text: 'PLAY',
                        btnClass: 'btn-primary',
                        action: function () {
                            $.ajax({
                                type: "POST",
                                url: "controller/ajaxcontroller.php",
                                data: {live_class_id: live_class_id, user_id: user_id, req_type: "quiz_play_detail"},
//                            dataType: "json",
                                success: function (data) {
                                    var obj = jQuery.parseJSON(data);
//                                alert(data);
                                    if (obj.data == 1)
                                    {
                                        window.location = "play_quiz.php?live_class_id=" + live_class_id;
                                    } else if (obj.data == 0)
                                    {
                                        $.alert('Quiz Already Played!');
                                    }
                                }
                            });
                        }

                    },
//                    no: function () {
//
//                    }

                }
            });
        });



//        video purchased 
        $('.videoPurchased').click(function ()
        {
            var ele = $(this);
            var live_class_id = ele.attr('live-class-id');

            window.location = "preview.php?live_class_id=" + live_class_id;
        });

    });
    
</script>

