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
<script src="<?php echo base_url(); ?>assets/js/additional-methods.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?php echo base_url(); ?>assets/js/vendor/file-upload/js/vendor/jquery.ui.widget.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="<?php echo base_url(); ?>assets/bundles/fileuploadscripts.bundle.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>	
<!-- page Js -->
<script src="<?php echo base_url(); ?>assets/bundles/mainscripts.bundle.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<!--<script src="<?php echo base_url(); ?>assets/js/page/index.js"></script>-->
<!-- <script src="<?php //echo base_url(); ?>assets/js/vendor/daterangepicker/moment.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script> -->
<script src="<?php echo base_url(); ?>assets/js/vendor/jquery-ui/jquery-ui.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>


<!-- <script src="<?php //echo base_url(); ?>assets/js/vendor/datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script> -->
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">

    $(document).ready(function () {
        
        // Hide loader
        $(".loader").hide();
        //Restrict alphabetic input only using class number_only in the input field
        $('input.alpha_num_dash').keypress(function (e) {
            var exp = "^[a-zA-Z0-9\,-.()&]$";
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
            todayHighlight: true,
            maxDate: "now",
            minDate: "-7d "
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
       var regex = new RegExp("^[A-Za-z0-9@!-@._*#$&]$");
       var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
       if (regex.test(str)) {
           return true;
       }
       e.preventDefault();
       return false;
   });
     //Change password in profile
    $.validator.addMethod("pwcheck", function(value) {
        return /^[A-Za-z0-9\d=!\!-@._*#$&]*$/.test(value) // consists of only these
            && /[A-Z]/.test(value) // has a lowercase letter
            && /[a-z]/.test(value) // has a lowercase letter
            && /\d/.test(value) // has a digit
            && /[!-@._*#$&]/.test(value); // has a digit
    }, 'Password must contain a upper case,lower case character & a digit & special characters(A-Za-z0-9@!-@._*#$&)');
    
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
        messages: {
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
                equalTo: 'Password & comfirm password not matched',
                minlength: 'Password minimum of 6 characters',
                maxlength: 'Password maximum of 16 characters'
            }
        }
    });

    // validator file size to be in MB
    $.validator.addMethod('filesize', function (value, element, param) {
        return this.optional(element) || (element.files[0].size <= param * 1000000)
    }, 'File size must be less than {0} MB');

    // Extraordinary gazette form
    $("#dept_gazetee_form").validate({
        rules: {
            subject: {
                required: true,
                minlength: 5,
                maxlength: 200
            },
            notification_type_id: {
                required: true
            },
            notification_number: {
                required: true,
                minlength: 4,
                maxlength: 100
            },
            keywords: {
                required: true,
                minlength: 4,
                maxlength: 200
            },
            doc_files: {
                required: true,
                extension: "docx",
                filesize: 30
            }
        },
        messages: {
            subject: {
                required: 'Please enter subject',
                minlength: 'Subject should be 5 chars',
                maxlength: 'Subject cannot be more than 200 chars'
            },
            notification_type_id: {
                required: 'Please select notification type'
            },
            notification_number: {
                required: 'Please enter order number',
                minlength: 'Order number should be minimum 4 characters',
                maxlength: 'Order number should be maximum 100 characters'
            },
            keywords: {
                required: 'Please enter Tags/Keywords',
                minlength: 'Keywords should be minimum 4 chars',
                maxlength: 'Keywords should be minimum 200 chars'
            },
            doc_files: {
                required: 'Please upload document',
                extension: "Only .docx allowed",
                filesize: 'Maximum 30 MB allowed'
            }
        },
        submitHandler: function (form, event) {
            $("#form4Submit").attr("disabled", true);
            $(".loader").show();
            form.submit();
        }
    });

    // Weekly gazette form submit
    $("#weekly_gazetee_form").validate({
        rules: {
            // dept_id: {
            //     required: true
            // },
            subject: {
                required: true,
                minlength: 5,
                maxlength: 200
            },
            // gazette_type_id: {
            //     required: true
            // },
            notification_type_id: {
                required: true
            },
            notification_number: {
                required: true,
                minlength: 4,
                maxlength: 100
            },
            keywords: {
                required: true,
                minlength: 4,
                maxlength: 200
            },
            part_id: {
                required: true
            },
            // week: {
            //     required: true
            // },
            doc_files: {
                required: true,
                extension: "docx",
                filesize: 30
            }
        },
        messages: {
            // dept_name: {
            //     required: 'Department name cannot be blank'
            // },
            subject: {
                required: 'Please enter subject',
                minlength: 'Subject should be 5 chars',
                maxlength: 'Subject cannot be more than 200 chars'
            },
            // gazette_type_id: {
            //     required: 'Please select gazette type'
            // },
            notification_type_id: {
                required: 'Please select notification type'
            },
            notification_number: {
                required: 'Please enter notification number',
                minlength: 'Order number should be minimum 4 characters',
                maxlength: 'Order number should be maximum 100 characters'
            },
            keywords: {
                required: 'Please enter Tags/Keywords',
                minlength: 'Keywords should be minimum 4 chars',
                maxlength: 'Keywords should be minimum 200 chars'
            },
            part_id: {
                required: 'Please select part'
            },
            // week: {
            //     required: 'Please select week'
            // },
            doc_files: {
                required: 'Please upload document',
                extension: "Only .docx allowed",
                filesize: 'Maximum 30 MB allowed'
            }
        },
        submitHandler: function (form, event) {
            $("#form4Submit").attr("disabled", true);
            $(".loader").show();
            form.submit();
        }
    });

    $.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[A-Z\s]+$/i.test(value);
    }, "Only alphabetical characters");
        
    // add Nodal officer in Admin panel form
    $("#add_nodal_officer").validate({
        rules: {
            name: {
                required: true,
                minlength: 4,
                maxlength: 40
            },
            designation: {
                required: true
            },
            username: {
                required: true,
                minlength: 4,
                maxlength: 10
            },
            email: {
                required: true,
                email: true,
                minlength: 6,
                maxlength: 96
            },
            dept_id: {
                required: true
            },
            mobile: {
                required: true,
                minlength: 10,
                maxlength: 10,
                digits: true
            },
            gpf_no: {
                required: true,
                minlength: 6,
                maxlength: 6,
                lettersonly: true
            }
        },
        messages: {
            name: {
                required: 'Please enter name',
                minlength: 'Name should be minimum 4 characters',
                maxlength: 'Name should be minimum 40 characters'
            },
            designation: {
                required: 'Please select designation'
            },
            username: {
                required: 'Please enter username',
                minlength: 'Username should be minimum 4 characters',
                maxlength: 'Username should be maximum 10 characters'
            },
            email: {
                required: 'Please enter email',
                email: 'Please enter a valid email',
                minlength: 'Email should be minimum 6 characters',
                maxlength: 'Email should be maximum 96 characters'
            },
            dept_id: {
                required: 'Please select department'
            },
            mobile: {
                required: 'Please enter mobile number',
                minlength: 'Mobile should be minimum 10 digits',
                maxlength: 'Mobile should be minimum 10 digits',
                digits: 'Please enter digits only'
            },
            gpf_no: {
                required: 'Please enter HRMS ID',
                minlength: 'HRMS ID should be minimum 6 alphabets',
                maxlength: 'HRMS ID should be maximum 6 alphabets',
                lettersonly: 'Please enter alphabets only'
            }
        },
        submitHandler: function (form, event) {
            debugger;
            $("#form4Submit").attr("disabled", true);
            $(".loader").show();
            form.submit();
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
            var gazette_id = $(this).siblings('.gazetee_id_val').val();
            // alert(id);
            // make ajax request
            $.ajax({
                type: "post",
                // url: '<?php echo base_url(); ?>' + "notification/set_read",
                url: '<?php echo base_url(); ?>' + "gazette/set_read_admin_dept",
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
        $('.notif_click_weekly').on("click", function () {
            var id = $(this).attr('id');
            var gazette_id = $(this).siblings('.gazetee_id_val').val();
            // alert(id);
            // make ajax request
            $.ajax({
                type: "post",
                // url: '<?php echo base_url(); ?>' + "notification/set_read",
                url: '<?php echo base_url(); ?>' + "gazette/set_read_admin_dept_weekly",
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
        $('.change_name_surname_admin').on("click", function () {
            var id = $(this).attr('id');
            var gazette_id = $(this).siblings('.gazetee_id_val').val();
            // alert(id);
            // make ajax request
            $.ajax({
                type: "post",
                url: '<?php echo base_url(); ?>' + "applicants_login/set_read_admin",
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
        $('.change_partnership_admin').on("click", function () {
            var id = $(this).attr('id');
            var gazette_id = $(this).siblings('.gazetee_id_val').val();
            // alert(id);
            // make ajax request
            $.ajax({
                type: "post",
                url: '<?php echo base_url(); ?>' + "applicants_login/set_read_partnership_admin",
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
            var dept_id = $(this).attr('name');
            // alert(dept_id);
            $('#change_status').show();
            $('#id').val(user_id);
            $('#status').val(status);
            $('#dept_id').val(dept_id);
        });

        // Weekly gazette form part selection
        $('#part_id').on('change', function () {
            if ($(this).val() !== "") {
                
                $.ajax({
                    type: "post",
                    url: '<?php echo base_url(); ?>' + "weekly_gazette/get_part_section",
                    data: {
                        'part_id' : $(this).val(),
                        '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                    },
                    dataType: 'html',
                    // if success, returns success message
                    success: function (data) {
                        $('#section').val(data);
                    },
                    error: function (data) {

                    }
                });
            } else {
                $('#section').val('');
            }
        });
        
        // Display filename in the span help block
        $("input[name='doc_files']").change(function (e) {
            $('.files').html($(this).val().split('\\').pop());
        });

        // SRO No field show/hide
//        $('#sro_no_check').on('change', function () {
//            if ($(this).val() == '1') {
//                $('.sro_no').show();
//            } else {
//                $('.sro_no').hide();
//            }
//        });

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