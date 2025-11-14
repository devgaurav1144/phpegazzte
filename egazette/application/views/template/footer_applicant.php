</div>
<!-- Vendor JavaScripts -->
<script src="<?php echo base_url(); ?>assets/bundles/libscripts.bundle.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<!--<script src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>-->
<script src="<?php echo base_url(); ?>assets/bundles/vendorscripts.bundle.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<!--/ vendor javascripts -->
<script src="<?php echo base_url(); ?>assets/bundles/flotscripts.bundle.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="<?php echo base_url(); ?>assets/bundles/d3cripts.bundle.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="<?php echo base_url(); ?>assets/bundles/sparkline.bundle.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="<?php echo base_url(); ?>assets/bundles/raphael.bundle.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="<?php echo base_url(); ?>assets/bundles/morris.bundle.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="<?php echo base_url(); ?>assets/bundles/loadercripts.bundle.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="<?php echo base_url(); ?>assets/bundles/jquery.validate.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?php echo base_url(); ?>assets/js/vendor/file-upload/js/vendor/jquery.ui.widget.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="<?php echo base_url(); ?>assets/bundles/fileuploadscripts.bundle.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>	
<!-- page Js -->
<script src="<?php echo base_url(); ?>assets/bundles/mainscripts.bundle.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<!--<script src="<?php echo base_url(); ?>assets/js/page/index.js"></script>-->
<script src="<?php echo base_url(); ?>assets/js/vendor/daterangepicker/moment.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="<?php echo base_url(); ?>assets/js/vendor/jquery-ui/jquery-ui.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="<?php echo base_url(); ?>assets/js/vendor/datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    $(document).ready(function () {
        //Restrict alphabetic input only using class number_only in the input field
        $('input.alpha_num_dash').keypress(function (e) {
            var exp = "^[a-zA-Z0-9\ ,-.()&]$";
            var regex = new RegExp(exp);
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });

        $(".report_date").datepicker({
            // date format in adding issue date in Press End.
            dateFormat: 'yy-mm-dd',
            todayHighlight: true
        });
        // Date picker
        $("#issue_date").datepicker({
            // date format in adding issue date in Press End.
            dateFormat: 'DD, MM dd, yy',
            todayHighlight: true
        }).datepicker("setDate", new Date());

        // Datepicker change
        $('#issue_date').change(function () {
            //startDate = $(this).datepicker('getDate');
            startDate = $('#issue_date').val();
            $('#date_selected').text(startDate);
        });

        // Reject user account
        $('.reject_account').on('click', function () {
            var id = $(this).attr('id');
            $('#user_id').val(id);
        });
    });
    
    /*
    * Restrict alphabetic input only using class number_only in the input field
    */
   $('input.pass_vali').keypress(function (e) {
       var regex = new RegExp("^[A-Za-z0-9\!-@._*#$&]$");
       var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
       if (regex.test(str)) {
           return true;
       }
       e.preventDefault();
       return false;
   });

    // Change password in profile
    $.validator.addMethod("pwcheck", function(value) {
        return /^[A-Za-z0-9\d=!\!-@._*#$&]*$/.test(value) // consists of only these
            && /[A-Z]/.test(value) // has a lowercase letter
            && /[a-z]/.test(value) // has a lowercase letter
            && /\d/.test(value) // has a digit
            && /[!-@._*#$&]/.test(value); // has a digit
    }, 'Password must contain a upper case,lower case character & a digit & special characters(A-Za-z0-9!-@._*#$&)');
    
    $("#form1_change_password").validate({
        rules: {
            old_password: {
                required: true,
                minlength: 4,
                maxlength: 16
            },
            password: {
                required: true,
                pwcheck: true,
                minlength: 6,
                maxlength: 16
            },
            match_password: {
                required: true,
                equalTo: "#password",
                minlength: 6,
                maxlength: 16
            }
        },
        message: {
            old_password: {
                required: 'Please enter old password',
                minlength: 'Password minimum of 4 characters',
                maxlength: 'Password maximum of 16 characters'
            },
            password: {
                required: 'Please enter password',
                //pwcheck: '',
                minlength: 'Password minimum of 6 characters',
                maxlength: 'Password maximum of 16 characters'
            },
            match_password: {
                required: 'Please enter confirm password',
                equalTo: 'Password & comfirm password are not same',
                minlength: 'Password minimum of 6 characters',
                maxlength: 'Password maximum of 16 characters'
            }
        }
    });

   

        $(document).ready(function () {
            // every 30 second interval, call the function
            setInterval(function () {
                check_notification_list();
            }, 30000);
            
            /*
            * check the session expiry in every 10 seconds
            */
            setInterval(function(){ 
                $.getJSON('<?php echo base_url(); ?>user/check_session_expiry', function(response) {
                    if (typeof response.redirect != 'undefined') {
                        window.location.href = response.redirect;
                    }
                });
            }, 60000);
        
            /*
            * Notification click
            */
            $('.notif_click').on("click", function () {
                var id = $(this).attr('id');
                // alert(id);
                //var gazette_id = $(this).siblings('.gazetee_id_val').val();
                // make ajax request
                $.ajax({
                    type: "post",
                    url: '<?php echo base_url(); ?>' + "notification/set_viewed",
                    data: {
                        'id' : id,
                        '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                    },
                    dataType: 'json',
                    // if success, returns success message
                    success: function (data) {

                    },
                    error: function (data) {

                    }
                });
            });
            
            /*
            * User approve/reject 
            */
            $('.togglebutton').one('click', function (e) {
                e.preventDefault();
                var user_id = $(this).attr('id');
                var status = $(this).attr('title');
                // make ajax request
                $.ajax({
                    type: "post",
                    url: '<?php echo base_url(); ?>' + "user/account_approve",
                    data: {
                        'user_id' : user_id,
                        'status' : status,
                        '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                    },
                    dataType: 'json',
                    // if success, returns success message
                    success: function (data) {
                        location.reload();
                    },
                    error: function (data) {
                        location.reload();
                    }
                });
            });

            //Applicant User notification read
            $('.applicant_read').click(function () { 
                let id = $(this).data('view_id');
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>' + "applicants_login/set_read_applicant",
                    data:{'id':id,
                        '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                    },
                    complete: function () {
                        
                    }     
                });
            });

            //C&T User notification read

            $('.cnt_read').click(function () { 
            debugger;
                let id = $(this).data('view_id');
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>' + "applicants_login/set_read_cnt",
                    data:{'id':id,
                        '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                    },
                    complete: function () {
                        
                    }     
                });
            });

            //IGR User notification read
            $('.igr_read').click(function () { 
            debugger;
                let id = $(this).data('view_id');
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>' + "applicants_login/set_read_igr",
                    data:{'id':id,
                        '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                    },
                    complete: function () {
                        
                    }     
                });
            });
        });

    /*
     * Load the notification list in header
     */
    function check_notification_list() {
        // make ajax request
        $.ajax({
            type: "GET",
            url: '<?php echo base_url(); ?>' + "notification/get_notification_list",
            data: '',
            dataType: 'html',
            // if success, returns success message
            success: function (html) {
                $('.dropdown-alerts').html(html);
            },
            error: function (html) {
                $('.dropdown-alerts').html(html);
            }
        });
    }
</script>
</body>
</html>