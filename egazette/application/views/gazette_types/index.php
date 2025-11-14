<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!--  CONTENT  -->
<section id="content">
    <div class="page page-tables-footable">
        <!-- bradcome -->
        <div class="b-b mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">Gazette Types</h1>
                    <small class="text-muted"></small>
                </div>
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
<!--                        <a href="<?php echo base_url(); ?>gazette_type/add" class="btn-bg btn btn-success btn-right-align">Add</a>-->
                        
                        <table id="searchTextResults" data-filter="#filter" data-page-size="5" class="footable table table-custom">
                            <thead>
                                <tr>
                                    <th>Sl No</th>
                                    <th>Gazette Type</th>
<!--                                    <th>Action</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($gazette_types)) { $cntr = 1; ?>
                                    <?php foreach ($gazette_types as $type) { ?>
                                        <tr>
                                            <td><?php echo $cntr; ?></td>
                                            <td><?php echo $type->gazette_type; ?></td>
<!--                                            <td>
                                                <div class="btn-group mr5 btn-grp-mr">
                                                    <button type="button" class="btn btn-default btn-pad">Action</button>
                                                    <button type="button" class="btn btn-default dropdown-toggle btn-pad" data-toggle="dropdown">
                                                        <span class="caret"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="<?php //echo $gazette->id; ?>">Edit</a></li>
                                                        <li><a href="<?php //echo $gazette->id; ?>">Delete</a></li>
                                                    </ul>
                                                </div>
                                            </td>-->
                                        </tr>
                                    <?php $cntr++; } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="3" class="center">No data to display</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot class="hide-if-no-paging">
                                <tr>
                                    <td colspan="5" class="text-right">
                                        <ul class="pagination">
                                        </ul>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
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