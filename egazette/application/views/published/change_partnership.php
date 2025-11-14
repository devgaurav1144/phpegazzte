
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!--  CONTENT  -->
<section id="content">
    <div class="page page-tables-footable">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
            <li class="active">Published Change of Partnership Details</li>
        </ol>

        <div class="b-b mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">Change of Partnership - Published Gazettes</h1>
                    <small class="text-muted"></small>
                </div>
            </div>
        </div>
        <!---Filter Section--->

        <?php 
            $app_name = "";
            $file_no = "";
            $notice_date_form = "";
            $notice_date_to = "";
            if(isset($inputs['app_name'])){
                $app_name = $inputs['app_name'];
            }
            if(isset($inputs['file_no'])){
                $file_no = $inputs['file_no'];
            }
            if(isset($inputs['notice_date_form'])){
                $notice_date_form = $inputs['notice_date_form'];
            }
            if(isset($inputs['notice_date_to'])){
                $notice_date_to = $inputs['notice_date_to'];
            }
         
         ?>


        <div class="col-md-15">
                <section class="boxs box_report">
                    <?php echo form_open('published/filter_for_published_partnership', array('method' => 'post','id'=>'search_data','autocomplete' => 'off')); ?>
                        <div class="boxs-body">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="name">Applicant Name </label>
                                    <input type="text" name="app_name" id="app_name" class="form-control" autocomplete="off" placeholder="Name">
                                    <?php if (form_error('app_name')) { ?>
                                        <span class="error"></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="File Number">File Number</label>
                                    <input type="text" name="file_no" id="file_no" class="form-control" autocomplete="off" placeholder="XP-00001-2022">
                                    <?php if (form_error('file_no')) { ?>
                                        <span class="error"></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="f_date">From Date </label>
                                        <input type="text" class="form-control" name="notice_date_form" placeholder = "YYYY-DD-MM" id="notice_date_form">
                                </div>
                                <div class="form-group col-md-3 custom-dts">
                                    <label for="f_date">To date </label>
                                        <input type="text" class="form-control" name="notice_date_to" placeholder = "YYYY-DD-MM" id="notice_date_to">
                                </div>
                                
                                <div class="clearfix"></div>
                                    <div class="col-md-5">
                                        <div class="form-group" >
                                            <input type="submit" value="Search" name="search_name" id="search_name"class="btn-bg btn btn-success">
                                            <span id="error"></span>
                                        </div>   
                                    </div>
                                </div>
                        </div>
                    <?php echo form_close(); ?>
                </div>
        </div>
              
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-body">
                        <?php if ($this->session->flashdata('success')) { ?>
                            <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('success'); ?></div>
                        <?php } ?>
                        <?php if ($this->session->flashdata('error')) { ?>
                            <div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('error'); ?></div>
                        <?php } ?>
                            
                        
                        <table id="searchTextResults" data-filter="#filter" data-page-size="5" class="footable table table-custom">
                            <thead>
                                <tr>
                                    <th>Sl No</th>
                                    <th>Gazette Type</th>
                                    <th>File Number</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th width="120">Press PDF</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($partners)) { ?>
                                    <?php 
                                        if ($this->my_pagination->total_rows > $this->my_pagination->per_page) {
                                            $cntr = 1 + ($this->my_pagination->cur_page - 1) * $this->my_pagination->per_page;
                                        } else {
                                            $cntr = 1;
                                        }
                                    ?>
                                        <?php //print_r($partners);
                                        foreach ($partners as $data) { ?>
                                        <tr>
                                            <td><?php echo $cntr; ?></td>
                                            <td>Extraordinary</td>
                                            <td><?php echo $data->file_no; ?></td>
                                            <td><?php echo  strftime('%d %b %Y, %I:%M %p', strtotime($data->created_at)); ?></td>
                                            <td>Govt. Press Published</td>
                                            <td class="center">
                                                <a href="<?php echo base_url() . $data->press_signed_pdf; ?>" target="blank"><i class="fa fa-file-pdf-o"></i></a>
                                            </td>
                                        </tr>
                                        <?php $cntr++;
                                    } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="6" class="center">No data to display</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php if (isset($links)) { ?>
                            <?php echo $links; ?>
                        <?php } ?>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>
<!--/ CONTENT -->
<script src="<?php echo base_url(); ?>/assets/js/vendor/jquery/jquery-3.1.0.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    $(document).ready(function(){

        $("#notice_date_form").datepicker({
                dateFormat: 'yy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                maxDate: new Date(),
                onSelect: function(selected) {
                    $("#notice_date_to").datepicker("option","minDate", selected)
                }
            });	

            $("#notice_date_to").datepicker({
                dateFormat: 'yy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                maxDate: new Date(),
                onSelect: function(selected) {
                    $("#notice_date_form").datepicker("option","maxDate", selected)
                }
            });

        jQuery.validator.addMethod("lettersonly", function(value, element) {
            return this.optional(element) || /^[a-z\s]+$/i.test(value);
            }, "Please enter only alphabetical characters");

        $("#search_data").validate({
            rules: {
                app_name: {
                    lettersonly:true
                },
            },
            message:{
                app_name:{
                    letteresonly:"Please enter only alphabatic caracter"
                }
            }
        })


        $('.delete_id').on('click', function(){
            var id = $(this).attr('id');
            
            if (confirm('Are you sure to delete the user')) {
                //alert();
                
                // make ajax request
                $.ajax({
                    type: "post",
                    url: '<?php echo base_url(); ?>' + "Commerce_transport_department/delete",
                    data: {
                        'id' : id,'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                        
                    },
                    //if success, returns success message
                    success: function (data) {
                        location.reload();
                    },
                    error: function (data) {
                        location.reload();
                    }
                });
            }
        });
        
        /*
         * User approve/reject 
         */
        $('.stat_change_ct').one('click', function (e) {
              e.preventDefault();
            var user_id = $(this).attr('id');
            var status = $(this).attr('title');
            //alert('ok');
            // make ajax request
            $.ajax({
              
                type: "post",
                url: '<?php echo base_url(); ?>' + "Commerce_transport_department/candt_status",
                data: {
                    'user_id' : user_id,
                    'status' : status,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                dataType: 'json',
                 //if success, returns success message
                success: function (data) {
                    location.reload();
                },
                error: function (data) {
                    location.reload();
                }
            });
        });
        
    });
</script>


<?php
    $userLocation = '';
    if ($this->session->userdata('is_applicant')) {
        $userLocation = 'applicants_login/logout';
    } else if ($this->session->userdata('is_igr')) {
        $userLocation = 'Igr_user/logout';
    } else if ($this->session->userdata('is_c&t')) {
        $userLocation = 'Commerce_transport_department/logout';
    } else {
        $userLocation = 'user/logout';
    }
?>
<script>
    var inactivityTimeout;

    function resetInactivityTimeout() {
        clearTimeout(inactivityTimeout);
        inactivityTimeout = setTimeout(function() {
            window.location.href = "<?php echo base_url($userLocation); ?>";
        }, 15 * 60 * 1000); 
    }

    document.addEventListener("mousemove", resetInactivityTimeout);
    document.addEventListener("keypress", resetInactivityTimeout);

    // Initialize timeout on page load
    resetInactivityTimeout();
</script>