<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!--  CONTENT  -->
<section id="content">
    <div class="page page-tables-footable">
        <div class="b-b mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">Module Wise Pricing</h1>
                    <small class="text-muted"></small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blush">Add Module Wise Pricing</h3>
                    </div>
                    <?php echo form_open('', array('name' => "pricing", 'role' => "form", 'id' => "pricing", 'method' => "post", 'enctype' => "multipart/form-data")); ?>
                    <div class="boxs-body">
                        <?php if ($this->session->flashdata('success')) { ?>
                            <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('success'); ?></div>
                        <?php } ?>
                        <?php if ($this->session->flashdata('error')) { ?>
                            <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('error'); ?></div>
                        <?php } ?>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="module_name">Module Name : <span class="asterisk">*</span></label>
                                <select name="module_id" id="module_id" class="form-control" required="">
                                    <option value="">Select Module</option>
                                    <?php if(!empty($modules)){ ?>
                                        <?php foreach ($modules as $module){ ?>
                                            <option value="<?php echo $module->id; ?>"><?php echo $module->module_name; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                                <?php if (form_error('module_name')) { ?>
                                    <span class="error"><?php echo form_error('module_name'); ?></span>
                                <?php } ?>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="pricing">Pricing Per Page : <span class="asterisk">*</span></label>
                                <input type="text" name="price_pp" id="price_pp" class="form-control number_only" required="required" autocomplete="off" maxlength="5">
                                <?php if (form_error('price_pp')) { ?>
                                    <span class="error"><?php echo form_error('price_pp'); ?></span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                        <button type="submit" class="btn btn-raised btn-success" id="form4Submit">Submit</button>
                    </div>
                    <?php echo form_close(); ?>
                </section>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-body">
                        <table id="searchTextResults" data-filter="#filter" data-page-size="5" class="footable table table-custom">
                            <thead>
                                <tr>
                                    <th width="80px">Sl No</th>
                                    <th>Module Name</th>
                                    <th>Pricing Per Page</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($pricings)){ ?>
                                    <?php 
                                        if ($this->my_pagination->total_rows > $this->my_pagination->per_page) {
                                            $cntr = 1 + ($this->my_pagination->cur_page - 1) * $this->my_pagination->per_page;
                                        } else {
                                            $cntr = 1;
                                        }
                                    ?>
                                    <?php foreach ($pricings as $pricing){ ?>
                                <tr>
                                    <td><?php echo $cntr; ?></td>
                                    <td><?php echo $pricing->module_name;?></td>
                                    <td><?php echo $pricing->pricing ?></td>
                                    <td>
                                        <div class="togglebutton stat_change"  id="<?php echo $pricing->id; ?>" title="<?php echo $pricing->status; ?>">
                                            <label><input type="checkbox" <?php echo ($pricing->status == 1) ? "checked" : ""; ?>></label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group mr5 btn-grp-mr">
                                            <button type="button" class="btn btn-default btn-pad">Action</button>
                                            <button type="button" class="btn btn-default dropdown-toggle btn-pad" data-toggle="dropdown">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a href="<?php echo base_url(); ?>module_wise_pricing/edit/<?php echo $pricing->id; ?>">Edit</a></li>
                                                <li class="delete_id" id="<?php echo $pricing->id; ?>"><a href="javascript:void(0);">Delete<div class="ripple-container"></div></a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                    <?php $cntr++;} ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="5" class="center">No data to display</td>
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
    <!-- Modal -->
        <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
            
            <?php echo form_open('user/account_reject', array('class' => "form1", 'name' => "form1", 'method' => "post")); ?>
                <input type="hidden" name="user_id" id="user_id" value=""/>
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Reject Nodal Officer Account</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Remark<span class="asterisk">*</span></label>
                                <textarea name="remarks" placeholder="Enter Remark" class="form-control remark_textarea" rows="6" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-raised btn-success">Submit</button>
                        </div>
                    </div>
                </div>
            <?php echo form_close(); ?>
        </div>
    <!-- Modal -->
</section>
<!--  /CONTENT  -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    $(document).ready(function () {
        $("#pricing").validate({
            rules: {
                module_id: {
                    required: true
                },
                price_pp: {
                    required: true
                }
            },
            messages: {
                module_id: {
                    required: "Please select module name"
                },
                price_pp: {
                    required: "Please enter pricing per page"
                }
            },
            submitHandler: function (form) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>module_wise_pricing/add",
                    //data: $('#shg_details_frm input[type=\'text\'], #shg_details_frm select, #shg_details_frm textarea, #shg_details_frm input[type="hidden"]'),
                    data: $(form).serialize(),
                    dataType: 'json',
                    success: function (json) {
                        if (json['redirect']) {
                            window.location = json['redirect'];
                        } else if (json['error']) {
                            if (json['error']['message']) {
                                $('#pricing').prepend('<div class="alert bg-danger"><button type="button" class="close" data-dismiss="alert"><span>Ã—</span><span class="sr-only">Close</span></button><span class="text-semibold"></span>' + json['error']['warning'] + '</div>');
                            }
                            
                            for (i in json['error']) {
                                var element = $('#' + i);
                                $(element).parent().find('.error').remove();
                                $(element).after('<span class="error">' + json['error'][i] + '</span>');
                            }
                        } else {
                            $(element).parent().find('.error').remove();
                        }
                    }
                });
            }
        });
        /*
         * Restrict number input only using class number_only in the input field
         */
        $('input.number_only').keyup(function (e) {
            if (/\D/g.test(this.value)) {
               // Filter non-digits from input value.
               this.value = this.value.replace(/\D/g, '');
            }
        });
        
        /*
         * Delete pricing
         */
        $('.delete_id').on('click', function(){
            var id = $(this).attr('id');
            if (confirm('Are you sure to delete the user')) {
                // make ajax request
                $.ajax({
                    type: "post",
                    url: '<?php echo base_url(); ?>' + "module_wise_pricing/delete",
                    data: {
                        'id' : id,
                        '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                    },
                    // if success, returns success message
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
         * Status change 
         */
        $('.stat_change').one('click', function (e) {
            e.preventDefault();
            var user_id = $(this).attr('id');
            var status = $(this).attr('title');
            // make ajax request
            $.ajax({
                type: "post",
                url: "<?php echo base_url(); ?>module_wise_pricing/status_change",
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