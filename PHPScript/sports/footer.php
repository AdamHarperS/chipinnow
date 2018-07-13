<!-- footer content -->
<footer>
    <div class="pull-right">
        <!--Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>-->
    </div>
    <div class="clearfix">
    </div>
</footer>
<!-- /footer content -->
</div>
</div>

<!-- jQuery -->
<script src="vendors/jquery/dist/jquery.min.js"></script>
<!-- Bootst            rap -->
<script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- iCheck -->
<script src="vendors/iCheck/icheck.min.js"></script>
<!-- validator -->
<script src="vendors/validator/validator.js"></script>

<!-- Custom Theme Scripts -->
<script src="build/js/custom.min.js"></script>

<script type="text/javascript" src="vendors/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="vendors/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<!--<script type="text/javascript" src="wickedpicker.min.js"></script>-->
<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
<style>
    .remove_field
    {
        color:#f00;
        display: inline-block;
    }
    .ctrlFile
    {
        display: inline-block!important;
        border:1px solid #ccc;
        margin: 5px 0px;
    }

</style>
<script>
    $(document).ready(function () {
        $("#course_city").change(function () {

            $("#course_country").prop('selectedIndex', $("#course_city option:selected").index());


        });
        $('.form_datetime').datetimepicker({
            //language:  'fr',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: 1
        });

        $('#datatable-responsive').DataTable({
            "columnDefs": [{
                    "targets": 0,
                    "orderable": false
                }]
        });
<?php
date_default_timezone_set('Asia/Kolkata');
?>


        $(".btnPhotoDelete").click(function () {
            alert($(this).data('id'));
            $("#" + $(this).data('id')).remove();
        });

        var max_fields = 10; //maximum input boxes allowed
        var wrapper = $(".input_fields_wrap"); //Fields wrapper
        var add_button = $(".add_field_button"); //Add button ID

        var x = 1; //initlal text box count
        $(add_button).click(function (e) { //on add input button click
            e.preventDefault();
            if (x < max_fields) { //max input box allowed
                x++; //text box increment
                $(wrapper).append('<div><input type="file" class="ctrlFile" name="gallery[]"/><a href="#" class="remove_field">&nbsp;Remove</a></div>'); //add input box
            }
        });

        $(wrapper).on("click", ".remove_field", function (e) { //user click on remove text
            e.preventDefault();
            $(this).parent('div').remove();
            x--;
        })

    });
</script>


<!-- validator -->
<script>
    // initial ize the validator function
    validator.message.date = 'not a real date';
    // validate a field on "blur" event, a 'select' on 'change' event & a '.reuired' classed multifield on 'keyup':
    $('form')
            .on('blur', 'input[required], input.optional, select.required', validator.checkField)
            .on('change', 'select.required', validator.checkField)
            .on('keypress', 'input[required][pattern]', validator.keypress);
    $('.multi.required').on('keyup blur', 'input', function () {
        validator.checkField.apply($(this).siblings().last()[0]);
    });
    $('form').submit(function (e) {
        e.preventDefault();
        var submit = true;
        // evaluatethe form using generic validaing
        if (!validator.checkAll($(this))) {
            submit = false;
        }
        if (submit)
            this.submit();
        return false;
    });

    $('#frmGallery').on('submit', (function (e) {
        e.preventDefault();
//        var formData = new FormData(this);
//        $.ajax({
//            type: "POST",
//            url: $(this).attr('action'),
//            data: formData,
//            enctype: 'multipart/form-data',
//            processData: false, // tell jQuery not to process the data
//            contentType: false, // tell jQuery not to set contentType
//            dataType: "json",
//            success: function (response)
//            {
//                // some code after succes from php
//            },
//            beforeSend: function ()
//            {
//                // some code before request send if required like LOADING....
//            }
//        });


        $.ajax({
            url: "upload_photo.php",
            type: "POST",
            //data: new FormData(this),
            contentType: false,
            cache: false,
            //processData: false,
            success: function (data) {
                alert(data);

            },
            error: function () {}
        });


//        var formData = new FormData(this);
//
//        $.ajax({
//            type: 'POST',
//            url: $(this).attr('action'),
//            data: formData,
//            cache: false,
//            contentType: false,
//            processData: false,
//            success: function (data) {
//                console.log("success");
//                console.log(data);
//            },
//            error: function (data) {
//                console.log("error");
//                console.log(data);
//            }
//        });
    }));

    $("#btnPhotoUpload").on("click", function () {
        //$("#frmGallery").submit();

        var myFormData = new FormData();
        myFormData.append('pictureFile', pictureInput.files[0]);

        $.ajax({
            url: "upload_photo.php",
            type: "POST",
            //data: new FormData(this),
            contentType: false,
            cache: false,
            //processData: false,
            success: function (data) {
                alert(data);

            },
            error: function () {}
        });

    });


    $('#btnDelete').click(function () {

        if ($("td .ckbox:checked").length == 0)
        {
            alert("Please select record(s)");
            return;
        }
        if (confirm('Do you want to delete?'))
        {
            $("#process_name").val("delete");
            $("#frmTrainee").submit();
        }


    });
    $('.sendNotification').click(function () {

        $("#trainee_id").val($(this).attr('id'));
        $('#pushNotify').modal('show');
    });
    $('.sendNotificationTrainer').click(function () {

        $("#trainee_id").val($(this).attr('id'));
        $('#pushNotify').modal('show');
    });
    $('#sendNotificationTrainer').click(function () {
        if ($("#notification_text").val() == '')
        {
            alert("Please enter notification");
            return;
        }
        if (confirm('Do you want to proceed?'))
        {

            $.ajax({
                url: "sendNotifyTrainer.php",
                type: "POST",
                data: $("#frmSend").serialize(),

                success: function (data) {
//                    $("#lblMsg").html(data);
                    $('#pushNotify').modal('hide');
                },
                error: function () {}
            });
            $("#notification_text").val('');
        }


    });
    $('#sendNotification').click(function () {
        if ($("#notification_text").val() == '')
        {
            alert("Please enter notification");
            return;
        }
        if (confirm('Do you want to proceed?'))
        {

            $.ajax({
                url: "sendNotify.php",
                type: "POST",
                data: $("#frmSend").serialize(),

                success: function (data) {
//                    $("#lblMsg").html(data);
                    $('#pushNotify').modal('hide');
                },
                error: function () {}
            });
            $("#notification_text").val('');
        }


    });
    $('#btnStatus').click(function () {

        if ($("td .ckbox:checked").length == 0)
        {
            alert("Please select record(s)");
            return;
        }
        if ($("#order_action").val() == '')
        {
            alert("Please select any action");
            return;
        }
        if (confirm('Do you want to proceed?'))
        {
            $("#frmTrainee").submit();
        }


    });
    $('#btnStatus_trainer').click(function () {

        if ($("td .ckbox:checked").length == 0)
        {
            alert("Please select record(s)");
            return;
        }
        if (confirm('Do you want to proceed?'))
        {
            $("#frmTrainee").submit();
        }


    });


    $("#select_all").change(function () { //"select all" change
        $(".ckbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });
    //".checkbox" change
    $('.ckbox').change(function () {
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if (false == $(this).prop("checked")) { //if this item is unchecked
            $("#select_all").prop('checked', false); //change "select all" checked status to false
        }
        //check "select all" if all checkbox items are chec    ked
        if ($('.ckbox:checked').length == $('.ckbox').length) {
            $("#select_all").prop('checked', true);
        }
    });
</script>
<!-- //validator -->
<script type="text/javascript">
    function changeLanguage(lang) {

        if (confirm('Do you want to change language') == 1) {

            var queryList = "";
            var qstring = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
            if (window.location.href.indexOf('?') >= 0) {
                for (var i = 0; i < qstring.length; i++) {
                    var urlparam = qstring[i].split('=');
                    if (urlparam[0] != "lang") {

                        if (queryList.length > 0) {
                            queryList = queryList + "&" + urlparam[0] + "=" + urlparam[1];
                        } else {
                            queryList = urlparam[0] + "=" + urlparam[1];
                        }

                    }
                }

                if (queryList.length > 0) {
                    window.location.href = window.location.pathname + "?" + queryList + "&lang=" + lang;
                } else {
                    window.location.href = window.location.pathname + "?" + "lang=" + lang;
                }
            } else {
                window.location.href = window.location.pathname + "?" + "lang=" + lang;
            }
        }
    }
</script>
</body>

</html>