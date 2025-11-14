<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
	.facebook_btn {
		border:none; overflow:hidden;
	}
</style>
<!--  CONTENT  -->
<section id="content">
    <div class="page page-tables-footable">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
            <li>Payment of Cost</li>
            <li class="active">Extraordinary Gazette</li>
        </ol>
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Extraordinary Gazette</strong></h3>
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
                                <!-- <li role="presentation" class="">
                                    <a href="#published" role="tab" id="published-tab" data-toggle="tab" aria-controls="profile" aria-expanded="false">Published</a>
                                </li>-->
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade active in" role="tabpanel" id="unpublished" aria-labelledby="unpublished-tab">
                                    <table id="searchTextResults" data-filter="#filter" data-page-size="5" class="footable table table-custom">
                                        <thead>
                                            <tr>
                                                <th>Sl No</th>
                                                <th>Department</th>
                                                <th width="150">Subject</th>
                                                <th>Date</th>
                                                <th>Dept. Document</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                                <th>Press PDF</th>
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
                                                        <td><?php echo $gazette->department_name; ?></td>
                                                        <td><?php echo $gazette->subject; ?></td>
                                                        <td><?php echo get_formatted_datetime($gazette->created_at); ?></td>
                                                        <td>
                                                            <a href="<?php echo base_url() . $gazette->dept_signed_pdf_path; ?>" target="_blank">
                                                                <i class="fa fa-file-pdf-o"></i>
                                                            </a>
                                                        </td>
                                                        <td><?php echo $gazette->status_name; ?></td>
                                                        <td class="center">
                                                            <a href="<?php echo base_url(); ?>extraordinary_poc/view/<?php echo $gazette->id; ?>"><i class="fa fa-eye"></i></a>
                                                        </td>
                                                        <td>
                                                            <?php if ($gazette->status_id == 5) { ?>
                                                                <a href="<?php echo base_url() . $gazette->press_signed_pdf_path; ?>" target="_blank">
                                                                    <i class="fa fa-file-pdf-o"></i>
                                                                </a>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $cntr++;
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
                                <div class="tab-pane fade" role="tabpanel" id="published" aria-labelledby="published-tab">
                                    <table id="searchTextResults" data-filter="#filter" data-page-size="5" class="footable table table-custom">
                                        <thead>
                                            <tr>
                                                <th>Sl No</th>
                                                <th>Department</th>
                                                <th>Subject</th>
                                                <th>Date</th>
                                                <th>Dept. Document</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                                <th>Press PDF</th>
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
                                                        <td><?php echo $gazette->department_name; ?></td>
                                                        <td><?php echo $gazette->subject; ?></td>
                                                        <td><?php echo get_formatted_datetime($gazette->created_at); ?></td>
                                                        <td>
                                                            <a href="<?php echo base_url() . $gazette->dept_pdf_file_path; ?>" target="_blank">
                                                                <i class="fa fa-file-pdf-o"></i>
                                                            </a>
                                                        </td>
                                                        <td><?php echo $gazette->status_name; ?></td>
                                                        <td class="center">
                                                            <a href="<?php echo base_url(); ?>gazette/press_view/<?php echo $gazette->id; ?>"><i class="fa fa-eye"></i></a>
                                                        </td>
                                                        <td>
                                                        <?php if ($gazette->status_id == 5) { ?>
                                                            <a href="<?php echo base_url() . $gazette->press_signed_pdf_path; ?>" target="_blank">
                                                                <i class="fa fa-file-pdf-o"></i>
                                                            </a>
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
        </div>
    </div>
</section>
<!--/ CONTENT -->
<script type="text/javascript" src="http://platform.twitter.com/widgets.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>

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