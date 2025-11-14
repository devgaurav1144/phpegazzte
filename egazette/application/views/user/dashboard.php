<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
    .success_login {
        color: #28a745;
    }
    .error {
        color: #D9534F;
    }
</style>
<section id="content">
    <div class="page dashboard-page">
        <!-- bradcome -->
        <div class="b-b mb-20">
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <?php if ($this->session->userdata('is_admin')) { ?>
                        <h1 class="h3 m-0">Govt. Press Dashboard</h1>
                    <?php } else { ?>
                        <h1 class="h3 m-0"><?php echo $dept_name->department_name; ?> Dashboard</h1>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php if ($this->session->flashdata('success')) { ?>
            <span class="error"><?php echo $this->session->flashdata('success'); ?></span>
        <?php } ?>
        <?php if ($this->session->flashdata('error')) { ?>
            <span class="error"><?php echo $this->session->flashdata('error'); ?></span>
        <?php } ?>
        <div class="row clearfix">
            <?php if ($this->session->userdata('is_admin')) { ?>
                <div class="col-lg-5-cols col-sm-6 col-md-6 col-xs-12">
                    <div class="boxs top_report_chart l-salmon">
                        <div class="boxs-body">
                            <h3 class="mt-0"><?php echo $total_submitted; ?></h3>
                            <p>Total <span>Submitted</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5-cols col-sm-6 col-md-6 col-xs-12">
                    <div class="boxs top_report_chart l-seagreen">
                        <div class="boxs-body">
                            <h3 class="mt-0"><?php echo $extra_published; ?></h3>
                            <p>Extraordinary <span>Published</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5-cols col-sm-6 col-md-6 col-xs-12">
                    <div class="boxs top_report_chart l-amber">
                        <div class="boxs-body">
                            <h3 class="mt-0"><?php echo $extra_pending; ?></h3>
                            <p>Extraordinary <span>Pending</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5-cols col-sm-6 col-md-6 col-xs-12">
                    <div class="boxs top_report_chart l-parpl">
                        <div class="boxs-body">
                            <h3 class="mt-0"><?php echo $weekly_published; ?></h3>
                            <p>Weekly <span>Published</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5-cols col-sm-6 col-md-6 col-xs-12">
                    <div class="boxs top_report_chart l-parpl">
                        <div class="boxs-body">
                            <h3 class="mt-0"><?php echo $weekly_pending; ?></h3>
                            <p>Weekly <span>Pending</span></p>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="col-lg-5-cols col-sm-6 col-xs-12">
                    <div class="boxs top_report_chart l-seagreen">
                        <div class="boxs-body">
                            <h3 class="mt-0"><?php echo $total_submitted; ?></h3>
                            <p>Total <span>Submitted</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5-cols col-sm-6 col-xs-12">
                    <div class="boxs top_report_chart l-amber">
                        <div class="boxs-body">
                            <h3 class="mt-0"><?php echo $extra_published_gazettes; ?></h3>
                            <p>Extraordinary <span>Published</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5-cols col-sm-6 col-xs-12">
                    <div class="boxs top_report_chart l-parpl">
                        <div class="boxs-body">
                            <h3 class="mt-0"><?php echo $extra_unpublished_gazettes; ?></h3>
                            <p>Extraordinary <span>Pending</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5-cols col-sm-6 col-xs-12">
                    <div class="boxs top_report_chart l-salmon">
                        <div class="boxs-body">
                            <h3 class="mt-0"><?php echo $weekly_published; ?></h3>
                            <p>Weekly <span>Published</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5-cols col-sm-6 col-xs-12">
                    <div class="boxs top_report_chart l-coral">
                        <div class="boxs-body">
                            <h3 class="mt-0"><?php echo $weekly_pending; ?></h3>
                            <p>Weekly <span>Pending</span></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="row clearfix">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue">Recent Pending Gazette</h3>
                    </div>
                    <div class="boxs-body">
                        <div class="bs-example bs-example-tabs" data-example-id="togglable-tabs">
                            <ul class="nav nav-tabs" id="myTabs" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#extraordinary" id="extraordinary-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">Extraordinary</a>
                                </li>
                                <li role="presentation" class="">
                                    <a href="#weekly" role="tab" id="weekly-tab" data-toggle="tab" aria-controls="profile" aria-expanded="false">Weekly</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade active in" role="tabpanel" id="extraordinary" aria-labelledby="extraordinary-tab">
                                    <?php if ($this->session->userdata('is_admin')) { ?>
                                        <div class="boxs-header filter-with-add flex-end-btn">
                                            <div class="">
                                                <a class="btn btn-success btn-raised" id="show_filter" title="Filter" href="<?php echo base_url(); ?>gazette">View All</a>
                                            </div>
                                        </div>
                                    <?php } else {?>
                                        <div class="boxs-header filter-with-add flex-end-btn">
                                            <div class="">
                                                <a class="btn btn-success btn-raised" id="show_filter" title="Filter" href="<?php echo base_url(); ?>gazette">View All</a>
                                            </div>
                                        </div>
                                    <?php }?>

                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Sl. No</th>
                                                <?php if ($this->session->userdata('is_admin')) { ?>
                                                    <th>Department</th>
                                                <?php } ?>
                                                <th>Subject</th>                                    
                                                <th>Date</th>
                                                <th>Dept. Document</th>
                                                <th>Status</th>
                                                <th class="hidden-md hidden-sm">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($recent_extra_gazettes)) {
                                                $cntr = 1; ?>
                                                <?php foreach ($recent_extra_gazettes as $gazette) { ?>
                                                    <tr>
                                                        <td><?php echo $cntr; ?></td>
                                                        <?php if ($this->session->userdata('is_admin')) { ?>
                                                            <td><?php echo $gazette->department_name; ?></td>
                                                        <?php } ?>
                                                        <td><?php echo $gazette->subject; ?></td>
                                                        <td><?php echo get_formatted_datetime($gazette->created_at); ?></td>
                                                        <td><a href="<?php echo base_url() . $gazette->dept_pdf_file_path; ?>" target="_blank">
                                                                <i class="fa fa-file-pdf-o"></i> View
                                                            </a>
                                                        </td>
                                                        <td><?php echo $gazette->status_name; ?></td>
                                                        <td class="center">
                                                            <?php if ($this->session->userdata('is_admin')) { ?>
                                                                <a href="<?php echo base_url(); ?>gazette/press_view/<?php echo $gazette->id; ?>"><i class="fa fa-eye"></i></a>
                                                            <?php } else { ?>
                                                                <?php if ($gazette->status_id == 2) { ?>
                                                                    <a href="<?php echo base_url(); ?>gazette/dept_preview/<?php echo $gazette->id; ?>/<?php echo $gazette->user_id; ?>"><i class="fa fa-eye"></i></a>
                                                                <?php } else { ?>
                                                                    <a href="<?php echo base_url(); ?>gazette/dept_view/<?php echo $gazette->id; ?>/<?php echo $gazette->user_id; ?>"><i class="fa fa-eye"></i></a>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                    <?php $cntr++;
                                                } ?>
                                            <?php } else { ?>
                                                <tr>
                                                    <td colspan="8" class="center">No data to display</td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="tab-pane fade" role="tabpanel" id="weekly" aria-labelledby="weekly-tab">

                                    <?php if ($this->session->userdata('is_admin')) { ?>
                                        <div class="boxs-header filter-with-add flex-end-btn">
                                            <div class="">
                                                <a class="btn btn-success btn-raised" id="show_filter" title="Filter" href="<?php echo base_url(); ?>weekly_gazette/index">View All</a>
                                            </div>
                                        </div>
                                    <?php } else {?>
                                        <div class="boxs-header filter-with-add flex-end-btn">
                                            <div class="">
                                                <a class="btn btn-success btn-raised" id="show_filter" title="Filter" href="<?php echo base_url(); ?>weekly_gazette/index">View All</a>
                                            </div>
                                        </div>
                                    <?php }?>

                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Sl. No</th>
                                                <?php if ($this->session->userdata('is_admin')) { ?>
                                                    <th>Department</th>
                                                <?php } ?>
                                                <th>Subject</th>                                    
                                                <th>Date</th>
                                                <th>Dept. Document</th>
                                                <th>Status</th>
                                                <th class="hidden-md hidden-sm">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($recent_weekly_gazettes)) {
                                                $cntr = 1; ?>
                                                <?php foreach ($recent_weekly_gazettes as $gazette) { ?>
                                                    <tr>
                                                        <td><?php echo $cntr; ?></td>
                                                        <?php if ($this->session->userdata('is_admin')) { ?>
                                                            <td><?php echo $gazette->department_name; ?></td>
                                                        <?php } ?>
                                                        <td><?php echo $gazette->subject; ?></td>
                                                        <td><?php echo get_formatted_datetime($gazette->created_at); ?></td>
                                                        <td><a href="<?php echo base_url() . $gazette->dept_pdf_file_path; ?>" target="_blank">
                                                                <i class="fa fa-file-pdf-o"></i> View
                                                            </a>
                                                        </td>
                                                        <td><?php echo $gazette->status_name; ?></td>
                                                        <td class="center">
                                                            <?php if ($this->session->userdata('is_admin')) { ?>
                                                                <a href="<?php echo base_url(); ?>weekly_gazette/press_view/<?php echo $gazette->id; ?>"><i class="fa fa-eye"></i></a>
                                                            <?php } else { ?>
                                                                <?php if ($gazette->status_id == 2) { ?>
                                                                    <a href="<?php echo base_url(); ?>weekly_gazette/dept_preview/<?php echo $gazette->id; ?>/<?php echo $gazette->user_id; ?>"><i class="fa fa-eye"></i></a>
                                                                <?php } else { ?>
                                                                    <a href="<?php echo base_url(); ?>weekly_gazette/dept_view/<?php echo $gazette->id; ?>/<?php echo $gazette->user_id; ?>"><i class="fa fa-eye"></i></a>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                    <?php $cntr++;
                                                } ?>
                                            <?php } else { ?>
                                                <tr>
                                                    <td colspan="8" class="center">No data to display</td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>

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
        }, 15 * 60 * 1000); // if user inactive for 15 minutes then it will automatically LOGOUT
    }

    document.addEventListener("mousemove", resetInactivityTimeout);
    document.addEventListener("keypress", resetInactivityTimeout);

    // Initialize timeout on page load
    resetInactivityTimeout();
</script>