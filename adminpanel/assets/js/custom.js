$(document).ready(function () {
    $("[data-toggle=popover]").popover({
        html: true,
        trigger: 'hover',
        placement: 'top',
//        container: '.user',
        content: function () {
            return $('#popover-content').html();
        }
    }).on('click', function (e) {
        $('.user').not(this).popover('hide');
    });
    $('body').on('click', function (e) {
        //did not click a popover toggle or popover
        if ($(e.target).data('toggle') !== 'popover'
                && $(e.target).parents('.popover.in').length === 0) {
            $('[data-toggle="popover"]').popover('hide');
        }
    });

    feather.replace();
    $(".submitform").validate();

    var ajaxurl = "controller/ajaxcontroller.php";
    function PageName(url) {
        var index = url.lastIndexOf("/") + 1;
        var filenameWithExtension = url.substr(index);
        var filename = filenameWithExtension.split(".")[0];
        return filename;
    }
    var currentpage = PageName(window.location.href);

    var getURLParameter = function (sParam) {
        var sPageURL = window.location.search.substring(1);
        var sURLVariables = sPageURL.split('&');
        for (var i = 0; i < sURLVariables.length; i++)
        {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == sParam) {
                return decodeURIComponent(sParameterName[1]);
            }
        }
    };

    $("[name='userUID']").change(function () {
        var ele = $(this);
        ele.parent().find(".return_msg").remove();
        $.post(ajaxurl, {req_type: "get_user_UID", r_uid: ele.val()},
        function (result) {
            console.log(result);
            var obj = jQuery.parseJSON(result);
            ele.parent().append(obj["data"]);
        }).fail(function () {
            console.log("error");
        });
    });
    if ($("[name='userUID']").length && $("[name='userUID']").val() != '')
        $("[name='userUID']").change();

    $(".dayend").click(function () {
        var ele = $(this);
        ele.attr("disabled", "disabled");
        ele.prepend('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        $.post("../cron/cron_binary.php", {req_type: "cron_income"},
        function (result) {
            console.log(result);
            ele.removeAttr("disabled");
            ele.find(".spinner-border").remove();
            var obj = jQuery.parseJSON(result);
            if (obj["status"] == 1) {
                $.alert("Day End Successfuly!");
            } else {
                $.alert("Something Went Wrong");
            }
        }).fail(function (result) {
            console.log("error: " + result);
            ele.removeAttr("disabled");
            ele.find(".spinner-border").remove();
        });
    });



    if (currentpage == "user-profile") {

        $("#img").fileinput({
            //uploadUrl:"#",
            theme: "fa",
            allowedFileExtensions: ['jpg', 'png', 'gif', 'bmp'],
            showUpload: false,
            showRemove: true,
            maxFileSize: 400,
            browseClass: "btn btn-dark",
            dropZoneEnabled: false,
        });

        $("#pan_card_img").fileinput({
            //uploadUrl:"#",
            theme: "fa",
            allowedFileExtensions: ['jpg', 'png', 'gif', 'bmp'],
            showUpload: false,
            showRemove: true,
            maxFileSize: 400,
            browseClass: "btn btn-dark",
            dropZoneEnabled: false,
        });

        $("#cancel_cheque_img").fileinput({
            //uploadUrl:"#",
            theme: "fa",
            allowedFileExtensions: ['jpg', 'png', 'gif', 'bmp'],
            showUpload: false,
            showRemove: true,
            maxFileSize: 400,
            browseClass: "btn btn-dark",
            dropZoneEnabled: false,
        });

//        $("[name='bank_ifsc']").change(function () {
//            var ele = $(this);
//            if (ele.val() != '') {
//                ele.parent().find(".return_msg").remove();
//                var url = "https://ifsc.razorpay.com/" + ele.val();
//                $.ajax({
//                    url: url, //PUNB0000100
//                    dataType: "json",
//                    xhrFields: {
//                        withCredentials: false
//                    }
//                }).done(function (result) {
//                    console.log(result);
//                    try {
//                        //                    console.log(result["BANK"]);
//                        $("[name='bank_name']").val(result["BANK"]);
//                    } catch (err) {
//                        console.log("Error: " + result);
//                        ele.parent().append("<p class='return_msg text-danger pl-1 m-0'>Not Valid IFSC!</p>");
//                        $("[name='bank_name']").val("");
//                    }
//                }).fail(function (result) {
//                    ele.parent().append("<p class='return_msg text-danger pl-1  m-0'>Went Wrong!</p>");
//                    console.log(result);
//                    $("[name='bank_name']").val("");
//                });
//            }
//        });
    } else if (currentpage == "page-coins-request") {
        $(".cancelCoinsbt").click(function () {
            var ele = $(this);
            ele.attr("disabled", "disabled");
            $.confirm({
                title: 'Coin Request!',
                content: 'Are You Sure Want to Cancel!',
                buttons: {
                    yes: function () {
                        $.post(ajaxurl, {req_type: "cancelCoins_req", r_id: ele.attr('data-id')},
                        function (result) {
                            console.log(result);
                            ele.removeAttr("disabled");
                            var obj = jQuery.parseJSON(result);
                            if (obj["status"] == 1) {
                                location.reload();
                            } else {
                                $.alert("Something Went Wrong");
                            }
                        }).fail(function () {
                            console.log("error");
                            ele.removeAttr("disabled");
                        });
                    },
                    no: function () {
                        ele.removeAttr("disabled");
                    }
                }
            });
        });
    } else if (currentpage == "page-withdraw") {
        $(".paid_accept").click(function () {
            var ele = $(this);
            $.confirm({
                title: 'Confirm Payment!',
                content: 'Are You Sure Want to Accept Request!',
                buttons: {
                    yes: function () {
                        $.post("controller/ajaxcontroller.php", {req_type: "withdraw_accept", r_userID: ele.attr('data-uid'), r_ID: ele.attr('data-id'), r_amount: ele.attr('uCoin')},
                        function (result) {
                            // console.log(result);
                            var obj = jQuery.parseJSON(result);
                            if (obj["status"] == 1) {
                                location.reload();
                            }
                        }).fail(function () {
                            console.log("error");
                        });
                    },
                    no: function () {
                    }
                }
            });
        });

        $(".paid_reject").click(function () {
            var ele = $(this);
            $.confirm({
                title: ' Request Cancel ' + ele.attr('data-id') + '!',
                content: 'Are You Sure Want to Accept Request!',
                buttons: {
                    Yes: function () {
                        $.post("controller/ajaxcontroller.php", {req_type: "withdraw_reject", r_userID: ele.attr('data-uid'), r_ID: ele.attr('data-id'), r_amount: ele.attr('uCoin')},
                        function (result) {
                            console.log(result);
                            var obj = jQuery.parseJSON(result);
                            if (obj["status"] == 1) {
                                location.reload();
                            }
                        }).fail(function () {
                            console.log("error");
                        });
                    },
                    cancel: function () {
                    },
                }
            });
        });
    } else if (currentpage == "page-userlist") {
        $(".statusbt").click(function () {
            var ele = $(this);
            ele.attr("disabled", "disabled");
            $.confirm({
                title: 'Status!',
                content: 'Are You Sure Want to Change Status!',
                buttons: {
                    yes: function () {
                        $.post(ajaxurl, {req_type: "userstatus_update", r_id: ele.attr('data-id'), r_status: ele.attr('data-isdisabled')},
                        function (result) {
                            console.log(result);
                            ele.removeAttr("disabled");
                            var obj = jQuery.parseJSON(result);
                            if (obj["status"] == 1) {
                                location.reload();
                            } else {
                                $.alert("Something Went Wrong");
                            }
                        }).fail(function () {
                            console.log("error");
                            ele.removeAttr("disabled");
                        });
                    },
                    no: function () {
                        ele.removeAttr("disabled");
                    }
                }
            });
        });

        $('.table-responsive').on('show.bs.dropdown', function () {
            $('.table-responsive').css("overflow", "inherit");
        });

        $('.table-responsive').on('hide.bs.dropdown', function () {
            $('.table-responsive').css("overflow", "auto");
        })
    } else if (currentpage == "page-coins-add" || currentpage == "page-coins-remove" || currentpage == "page-activate") {

        $("[name='uMobile']").on('change', function () {
            ele = $(this);
            if (ele.val().length > 0) {
                var mobileNo = ele.val();
                $.post(ajaxurl, {req_type: "get_username", r_mob: mobileNo},
                function (result) {
                    console.log(result);
                    var obj = jQuery.parseJSON(result);
                    if (obj["status"] == 1) {
                        $(':input[type="submit"]').removeAttr("disabled", "disabled");
                        $('.U_coin').removeAttr("disabled", "disabled");
                        $('.uid').val(obj["data"]['ID']);
                        $('.userName').html("Coins Send To: <b>" + obj["data"]['uName'] + "</b>");
                        $('.userName').addClass("text-primary form-control");
                        $('.userName').removeClass("text-danger");
                        //                            $('.userName').append("<span class='return_msg form-control border p-3 text-primary pl-1 m-0'> Coins Send To : <b>" + obj["data"]['uName'] + "</b></span>");
                    } else {
                        $(':input[type="submit"]').attr("disabled", "disabled");
                        $('.U_coin').attr("disabled", "disabled");
                        $('.uid').val("");
                        $('.userName').addClass("text-danger form-control");
                        $('.userName').html("This Mobile Number is not Exist");
                        $('.userName').removeClass("text-primary");
                    }
                }).fail(function () {

                });
            }

        });
        $("[name='uMobile']").change();
    } else if (currentpage == "page-slider") {
        $("#img1,#img2,#img3,#img4").fileinput({
            //uploadUrl:"#",
            theme: "fa",
            allowedFileExtensions: ['jpg', 'png', 'gif', 'bmp'],
            showUpload: false,
            showRemove: true,
            maxFileSize: 200,
            browseClass: "btn btn-dark",
            dropZoneEnabled: false,
        });
    }

    console.log(currentpage);

    //Set attribute color picker
    //$("input[type=color]").spectrum({showInput: true, showInitial: true, showAlpha: false, preferredFormat: "hex", });

    //editor error with validate setting.
    $('form').each(function () {
        if ($(this).data('validator'))
            $(this).data('validator').settings.ignore = ".Editor-editor";
    });
});