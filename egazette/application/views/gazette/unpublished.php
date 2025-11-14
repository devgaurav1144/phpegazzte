<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
	.search_txt {
		padding-top: 5px
	}
</style>
<!--  CONTENT  -->
<section id="content">
    <div class="page page-tables-footable">
        <!-- bradcome -->
        <div class="b-b mb-10">
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <?php if ($this->session->userdata('is_admin')) { ?>
                        <h1 class="h3 m-0">Directorate of Printing, Stationary and Publication Gazette</h1>
                    <?php } else { ?>
                        <h1 class="h3 m-0"><?php echo $dept_name->department_name; ?> Unpublished Gazette</h1>
                    <?php } ?>
                    <small class="text-muted"></small>
                </div>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <a href="<?php echo base_url(); ?>gazette/add" class="btn-bg btn btn-success btn-right-align">Add</a>
                    <div class="boxs-body">
                        <div class="form-group">
                            <label for="filter" class="search_txt">Search:</label>
                            <input id="filter" type="text" class="form-control rounded w-md mb-10 inline-block" />
                        </div>
                        <table id="searchTextResults" data-filter="#filter" data-page-size="5" class="footable table table-custom">
                            <thead>
                                <tr>
                                    <th>Sl No</th>
                                    <?php if ($this->session->userdata('is_admin')) { ?>
                                        <th>Department</th>
                                    <?php } ?>
                                    <th>Gazette Type</th>
                                    <th width="150">Subject</th>
                                    <th>Date</th>
                                    <th>Dept. PDF</th>
                                    <th>Press PDF</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($gazettes)) { ?>
                                    <?php 
                                        if ($this->my_pagination->total_rows > $this->my_pagination->per_page) {
                                            $cntr = 1 + ($this->my_pagination->cur_page - 1) * $this->my_pagination->per_page;
                                        } else {
                                            $cntr = 1;
                                        }
                                    ?>
                                        <?php foreach ($gazettes as $gazette) { ?>
                                        <tr>
                                            <td><?php echo $cntr; ?></td>
                                            <?php if ($this->session->userdata('is_admin')) { ?>
                                                <td><?php echo $gazette->department_name; ?></td>
                                            <?php } ?>
                                            <td><?php echo $gazette->gazette_type; ?></td>
                                            <td><?php echo $gazette->subject; ?></td>
                                            <td><?php echo $gazette->issue_date; ?></td>
                                            <td><a href="<?php echo base_url() . $gazette->dept_pdf_file_path; ?>" target="_blank">
                                                    <i class="fa fa-file-pdf-o"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <?php if ($gazette->status_id == 5) { ?>
                                                    <a href="<?php echo base_url() . $gazette->press_pdf_file_path; ?>" target="_blank">
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
                </section>
            </div>
        </div>
    </div>
</section>
<!--/ CONTENT -->

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