<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
	.facebook_btn {
		border:none; overflow:hidden;margin-top: 3px;       
	} 
    .share-div {
            display: flex;
    justify-content: center;
    align-items: center;
        }
        .share-ii {
            padding: 3px 10px !important;
        }
</style>
<!--  CONTENT  -->
<section id="content">
    <div class="page page-tables-footable">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li class="active">Extraordinary Gazette</li>
        </ol>
        <!-- <div class="b-b mb-10">
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <?php //if ($this->session->userdata('is_admin')) { ?>
                        <h1 class="h3 m-0">Directorate of Printing, Stationery and Publication Extraordinary Gazette</h1>
                    <?php// } else { ?>
                        <h1 class="h3 m-0"><?php// echo $dept_name->department_name; ?> Extraordinary Gazette</h1>
                    <?php //} ?>
                    <small class="text-muted"></small>
                </div>
            </div>
        </div> -->
        <!--Filteration--->

        <!---Filteration Data-->
        <?php 
            $subline = "";
            $odrNo = "";
            if(isset($inputs['subline'])){
                $subline = $inputs['subline'];
            }
            if(isset($inputs['odrNo'])){
                $odrNo = $inputs['odrNo'];
            }
            if(isset($inputs['statusType'])){
                $statusType = $inputs['statusType'];
            }
            if(isset($inputs['dept'])){
                $dept = $inputs['dept'];
            }
            if(isset($inputs['nType'])){
                $nType = $inputs['nType'];
            }
            if(isset($inputs['fdate'])){
                $fdate = $inputs['fdate'];
            }
            if(isset($inputs['tdate'])){
                $tdate = $inputs['tdate'];
            }
         
         ?>


        <section class="boxs box_report d-none" id="show_filter_form">
            <div class="boxs-header">
                <h3 class="custom-font hb-blue"><strong>Filter</strong></h3>
            </div>
            <div class="boxs-body">
                <?php echo form_open('gazette/index', array('method' => 'post', 'id'=>'search_data', 'autocomplete' => 'off')); ?>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">    
                            <label for="filter" class="label_txt">From Date</label>
                            <input name="fdate" id="fdate" type="text" class="form-control rounded w-md mb-10 inline-block" placeholder="DD-MM-YYYY" autocomplete="off" value="<?php echo set_value('fdate'); ?>" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter" class="label_txt">To Date</label>
                            <input name="tdate" id="tdate" type="text" class="form-control rounded w-md mb-10 inline-block" placeholder="DD-MM-YYYY" autocomplete="off" value="<?php echo set_value('tdate'); ?>"/>
                        </div> 
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter" class="label_txt">Subject Line</label>
                            <input name="subline" id="subline" type="text" class="form-control rounded w-md mb-10 inline-block" placeholder="Subject Name" value="<?php echo set_value('subline'); ?>"/>
                        </div>   
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter" class="label_txt">Order No</label>
                            <input name="odrNo" id="odrNo" type="text" class="form-control rounded w-md mb-10 inline-block" placeholder="Notification/Order/Resolution No" value="<?php echo set_value('odrNo'); ?>"/>
                        </div> 
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">    
                            <label for="filter" class="label_txt">Department</label>
                            <select class="form-control" name="dept" id="dept">
                                <option value="">Select Department</option>
                                <?php foreach ($department_type as $departmentValue) { ?>
                                    <option value="<?php echo $departmentValue->id; ?>" <?php
                                            $dept = set_value('dept');
                                            if ($dept == $departmentValue->id) {
                                                echo "selected";
                                            }
                                            ?>><?php echo $departmentValue->department_name; ?></option>
                                        <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter" class="label_txt">Notification Type</label>
                            <select class="form-control" name="nType" id="nType">
                                <option value="">Select Notification Type</option>
                                        <?php foreach ($notification_type as $notificatioTypeValue) { ?>
                                    <option value="<?php echo $notificatioTypeValue->notification_type; ?>"<?php
                                            $sValue = set_value('nType');
                                            if ($sValue == $notificatioTypeValue->notification_type) {
                                                echo "selected";
                                            }
                                            ?>>
                                        <?php echo $notificatioTypeValue->notification_type; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>    
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter" class="label_txt">Status</label>
                            <select class="form-control" name="statusType" id="statusType">
                                <option value="">Select Status</option>
                                <?php foreach ($gz_status as $statusValue) { ?>
                                    <option value="<?php echo $statusValue->id; ?>"<?php
                                $statusType = set_value('statusType');
                                if ($statusType == $statusValue->id) {
                                    echo "selected";
                                }
                                ?>>
                                <?php echo $statusValue->status_name; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>    
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-3">
                        <input type="submit" value="Search" name="" class="btn-bg btn btn-success" id="search_btn">
                        <input type="button" id="reset_btn" value="Reset" name="" class="btn-bg btn btn-primary">
                        <span id="error"></span>
                    </div>
                    <?php echo form_close(); ?>
                </div>                            
            </div>
        </section> 

        <section class="boxs">
            <div class="boxs-header filter-with-add">
                <?php if ($this->session->userdata('is_admin')) { ?>
                    <h3 class="custom-font hb-blue"><strong>Govt. Press Pending Extraordinary Gazette</strong></h3>
                <?php } else { ?>
                    <h3 class="custom-font hb-blue"><strong>Pending Extraordinary Gazette</strong></h3>
                <?php } ?>
                <div class="">
                    <button class="btn btn-success btn-raised btn-fab btn-fab-mini btn-round" id="show_filter" title="Filter"><i class="fa fa-filter"></i></button>
                    <?php if (!$this->session->userdata('is_admin')) { ?>
                    <a href="<?php echo base_url(); ?>gazette/add" class="btn btn-success btn-raised btn-round">Add</a>
                <?php } ?>
                </div>
            </div>
            <div class="boxs-body">
                <?php if ($this->session->flashdata('success')) { ?>
                    <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('success'); ?></div>
                <?php } ?>
                <?php if ($this->session->flashdata('error')) { ?>
                    <div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('error'); ?></div>
                <?php } ?>
               
                <div class="bs-example bs-example-tabs" data-example-id="togglable-tabs">
                    <ul class="nav nav-tabs" id="myTabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#unpublished" id="unpublished-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">Pending</a>
                        </li>
                        <li role="presentation" class="">
                            <a href="#published" role="tab" id="published-tab" data-toggle="tab" aria-controls="profile" aria-expanded="false">Published</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade active in" role="tabpanel" id="unpublished" aria-labelledby="unpublished-tab">
                            <div class="table-responsive">
                            <table id="searchTextResults" data-filter="#filter" data-page-size="5" class="footable table table-custom">
                                <thead>
                                    <tr>
                                        <th width="80">Sl. No.</th>
                                        <?php if ($this->session->userdata('is_admin')) { ?>
                                            <th>Department</th>
                                        <?php } ?>
                                        <th width="250">Subject</th>
                                        <th>Payment Type</th>
                                        <th>Date</th>
                                        <th>Dept. Document</th>                                        
                                        <!-- <th>Press PDF</th> -->
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <?php if (!empty($gazettes_unpublished)) { ?>
                                        <?php
                                        if ($this->my_pagination->total_rows > $this->my_pagination->per_page) {
                                            $cntr = 1 + ($this->my_pagination->cur_page - 1) * $this->my_pagination->per_page;
                                        } else {
                                            $cntr = 1;
                                        }
                                        ?>
                                        
                                        <?php foreach ($gazettes_unpublished as $gazette) { ?>
                                            
                                            <tr>
                                                <td><?php echo $cntr; ?></td>
                                                <?php if ($this->session->userdata('is_admin')) { ?>
                                                    <td><?php echo $gazette->department_name; ?></td>
                                                <?php } ?>
                                                <td><?php echo $gazette->subject; ?></td>
                                                <td>
                                                    <?php
                                                        if ($gazette->is_paid == 0) {
                                                            echo 'Free';
                                                        } else {
                                                            echo 'Payment of Cost';
                                                        }
                                                    ?>
                                                </td>
                                                <td><?php echo get_formatted_datetime($gazette->created_at); ?></td>
                                                <td>
                                                    <?php if ($this->session->userdata('is_admin')) { ?>
                                                        <a href="<?php echo base_url() . $gazette->dept_signed_pdf_path; ?>" target="_blank">
                                                            <i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                    <?php } else { ?>
                                                        <a href="<?php echo base_url() . (!empty($gazette->dept_signed_pdf_path)) ? $gazette->dept_signed_pdf_path : $gazette->dept_pdf_file_path; ?>" target="_blank">
                                                            <i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                                <!-- <td>
                                                    <?php //if ($gazette->status_id == 5) { ?>
                                                        <a href="<?php //echo base_url() . $gazette->press_signed_pdf_path; ?>" target="_blank">
                                                            <i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                    <?php //} ?>
                                                </td> -->
                                                <td><?php echo $gazette->status_name; ?></td>
                                                <td class="center">
                                                    <?php if ($this->session->userdata('is_admin')) { ?>
                                                        <?php if ($gazette->is_paid == 0) { ?>
                                                            <a href="<?php echo base_url(); ?>gazette/press_view/<?php echo $gazette->id; ?>"><i class="fa fa-eye"></i></a>
                                                        <?php } else { ?>
                                                            <a href="<?php echo base_url(); ?>gazette/press_view_paid/<?php echo $gazette->id; ?>"><i class="fa fa-eye"></i></a>
                                                        <?php } ?>
                                                        
                                                    <?php } else { ?>
                                                        
                                                        <?php if ($gazette->is_paid == 0) { ?>
                                                            <a href="<?php echo base_url(); ?>gazette/dept_view/<?php echo $gazette->id; ?>/<?php echo $gazette->user_id; ?>"><i class="fa fa-eye"></i></a>
                                                        <?php } else { ?>
                                                            <a href="<?php echo base_url(); ?>gazette/dept_view_paid/<?php echo $gazette->id; ?>/<?php echo $gazette->user_id; ?>"><i class="fa fa-eye"></i></a>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </td>                                                
                                            </tr>
                                            <?php
                                            $cntr++;
                                        }
                                        ?>
                                        <?php } else { ?>
                                        <tr>
                                            <td colspan="9" class="center">No data to display</td>
                                        </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                                        </div>
                            <?php if (isset($links)) { ?>
                                <?php echo $links; ?>
                            <?php } ?>
                        </div>
                        <div class="tab-pane fade" role="tabpanel" id="published" aria-labelledby="published-tab">
                            <table id="searchTextResults" data-filter="#filter" data-page-size="5" class="footable table table-custom">
                                <thead>
                                    <tr>
                                        <th width="80">Sl. No.</th>
                                        <?php if ($this->session->userdata('is_admin')) { ?>
                                            <th>Department</th>
                                        <?php } ?>
                                        <th>Subject</th>
                                        <th>Payment Type</th>
                                        <th>Date</th>
                                        <th>Dept. Document</th>
                                        <th>Press PDF</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($gazettes_published)) { ?>
                                        <?php
                                        if ($this->my_pagination->total_rows > $this->my_pagination->per_page) {
                                            $cntr = 1 + ($this->my_pagination->cur_page - 1) * $this->my_pagination->per_page;
                                        } else {
                                            $cntr = 1;
                                        }
                                        ?>
                                        <?php foreach ($gazettes_published as $gazette) { ?>
                                            <tr>
                                                <td><?php echo $cntr; ?></td>
                                                <?php if ($this->session->userdata('is_admin')) { ?>
                                                    <td><?php echo $gazette->department_name; ?></td>
                                                <?php } ?>
                                                <td><?php echo $gazette->subject; ?></td>
                                                <td>
                                                    <?php
                                                        if ($gazette->is_paid == 0) {
                                                            echo 'Free';
                                                        } else {
                                                            echo 'Payment of Cost';
                                                        }
                                                    ?>
                                                </td>
                                                <td><?php echo get_formatted_datetime($gazette->created_at); ?></td>
                                                <td>
                                                    <a href="<?php echo base_url() . $gazette->dept_signed_pdf_path; ?>" target="_blank">
                                                        <i class="fa fa-file-pdf-o"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <?php if ($gazette->status_id == 5) { ?>
                                                        <a href="<?php echo base_url() . $gazette->press_signed_pdf_path; ?>" target="_blank">
                                                            <i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                                <td><?php echo $gazette->status_name; ?></td>
                                                <td class="center">
                                                    <?php if ($this->session->userdata('is_admin')) { ?>
                                                        <a href="<?php echo base_url(); ?>gazette/press_view/<?php echo $gazette->id; ?>"><i class="fa fa-eye"></i></a>
                                                    <?php } else { ?>
                                                        <a href="<?php echo base_url(); ?>gazette/dept_view/<?php echo $gazette->id; ?>/<?php echo $gazette->user_id; ?>"><i class="fa fa-eye"></i></a>
                                                        <?php if ($gazette->status_id == 5) { ?>
                                                            <?php $pdf = base_url() . $gazette->press_signed_pdf_path; ?>
                                                        <div class="share-div">
                                                            <iframe src="https://www.facebook.com/plugins/share_button.php?href=http%3A%2F%2Flocalhost%2Fegazette%2F%<?php echo $pdf; ?>&layout=button&size=small&appId=1762917924005815&width=25&height=20" width="70" height="20" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media" class="facebook_btn"></iframe>

                                                            <a href="https://twitter.com/intent/tweet?original_referer=https%3A%2F%2Fpublish.twitter.com%2F%3FbuttonText%3DGazette%2520Share%26buttonType%3DTweetButton%26buttonUrl%3Dhttp%253A%252F%252Flocalhost%252Fegazette%252F%26buttonVia%3Dntspl%26widget%3DButton&amp;ref_src=twsrc%5Etfw&amp;text=Gazette%20Share&amp;tw_p=tweetbutton&amp;url=http%3A%2F%2Flocalhost%2Fegazette%2F&amp;via=<?php echo $pdf; ?>" id="b"><i></i><span class="label label-info share-ii" id="l"><i class="fa fa-twitter"></i></span></a>
                                                            </div>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php $cntr++;
                                        }
                                        ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="8" class="center">No data to display</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php if (isset($links)) { ?>
                                <?php echo $links; ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</section>
<!--/ CONTENT -->

<script type="text/javascript" src="http://platform.twitter.com/widgets.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="<?php echo base_url(); ?>/assets/js/vendor/jquery/jquery-3.1.0.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
        //Date Filter
        
        $(document).ready(function(){
            $("#fdate").datepicker({
                dateFormat: 'dd-mm-yy',
                autoclose: true,
                todayHighlight: true,
                maxDate: new Date(),
                onSelect: function(selected) {
                    $("#tdate").datepicker("option","minDate", selected)
                }
            });	

            $("#tdate").datepicker({
                dateFormat: 'dd-mm-yy',
                autoclose: true,
                todayHighlight: true,
                maxDate: new Date(),
                onSelect: function(selected) {
                    $("#fdate").datepicker("option","maxDate", selected)
                }
            });


            $('#show_filter').click(function () {                
                if($("#show_filter_form").hasClass("d-none")){
                    $("#show_filter_form").removeClass("d-none");
                } else {
                    $("#show_filter_form").addClass("d-none");
                }                
            });

            $('#search_btn').click(function(){
            var odrNo = document.getElementById("odrNo").value;
            var dept = document.getElementById("dept").value;
            var statusType = document.getElementById("statusType").value;
            var f_data = document.getElementById("fdate").value;
            var t_data = document.getElementById("tdate").value;
            var subline = document.getElementById("subline").value;
            var nType = document.getElementById("nType").value;
            const error = document.getElementById('error');
            if (odrNo === "" && dept === "" && statusType === "" && f_data === "" && t_data === "" && subline === "" && nType === "") { 
                //$("#searcherror").html = "Error"
                error.textContent = 'Please enter any data to search';
                //alert("Input your data to search");
                return false;
            } else {
                return true;
            }
        });

            $('#reset_btn').click(function () { 
                // $('#search_data').reset(); 
                $('#search_data').trigger('reset');          
                // window.location.reload();   
                window.location.href = "<?php echo base_url(); ?>gazette"           
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