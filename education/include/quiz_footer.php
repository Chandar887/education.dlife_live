<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
<script src="js/jquery-confirm.min.js"></script>


<script>
    $(document).ready(function() {
        $('#logout').click(function() {

            $.confirm({
                title: '',
                content: 'Are you sure!',
                buttons: {
                    yes: function() {
                        $.ajax({
                            type: "POST",
                            url: "controller/ajaxcontroller.php",
                            data: {
                                req_type: "logout"
                            },
                            //                            dataType: "json",
                            success: function(data) {
                                var obj = jQuery.parseJSON(data);
                                if (obj.data == 1) {
                                    $('body').append('<div class="loader"><div class="spinner-border text-primary" role="status"><span class="sr-only"></span></div></div>');
                                    window.location = "../index.php";
                                }
                            }
                        });
                    },
                    no: function() {

                    }
                }
            });
        });

        $('.loading').click(function() {
            $('body').append('<div class="loader"><div class="spinner-border text-primary" role="status"><span class="sr-only"></span></div></div>');
            var path = $(this).attr('href');
            window.location.href = path;
        });
        
    });
</script>

</body>

</html>