
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!--  CONTENT  -->
<section id="content">
    <div class="page page-tables-footable">
        <!-- bradcome -->
        <div class="b-b mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0"></h1>
                    <small class="text-muted"></small>
                </div>
            </div>
        </div>
        
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
            <li>Partnership Offline Payment Approve</li>
            <!--<li class="active">Change of Name/Surname</li>-->
        </ol>
              
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                    <h3 class="custom-font hb-blue"><strong>Partnership Offline Payment List</strong></h3>
                    </div>
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
                                    <th width="120">View Details</th>
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
										<?php echo form_open("offline_payment_users/partnership_offline_payment_list", array("method" => "POST", "id" => "form1", "name" => "form1")); ?>
                                        <tr>
                                            <td><?php echo $cntr; ?></td>
                                            <td>Extraordinary</td>
                                            <td><?php echo $data->file_no; ?></td>
                                            <td><?php echo  strftime('%d %b %Y, %I:%M %p', strtotime($data->created_at)); ?></td>
                                            <td><?php echo $data->status_name; ?></td>
                                            <td class="center">
											<input type="hidden" name="btn_hid_id" value="<?php echo $data->id; ?>"/>
											<input type="submit" name="btn_get_details" class="btn btn-raised btn-success edit_btn" value="Details"/>
                                                <!--<a href="<?php echo base_url(); ?>make_payment/make_payment_name_surname/<?php echo $data->id; ?>"><i class="fa fa-eye"></i></a>-->
                                            </td>
                                        </tr>
										<?php echo form_close(); ?>
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
                    
					<?php if(!empty($user_details)) { ?> 
							<div class="boxs-body">
                        
                        <div class="row">
                            
                            <div class="form-group col-md-9">
							
                                <table class="table" id="table_print">
								<?php echo form_open("offline_payment_users/partnership_offline_payment_list", array("method" => "POST", "id" => "form_offline_pay", "name" => "form_offline_pay")); ?>
								<tbody>
									<tr>
									  <td>Name</td> 
									  <td><?php echo $user_details->name; ?></td>
									</tr>
									
									<tr>
									  <td>File No</td> 
									  <td><?php echo $user_details->file_no; ?></td>
									</tr>
									
									<tr>
									  <td>Mobile No</td> 
									  <td><?php echo $user_details->mobile; ?></td>
									</tr>
									
									<tr>
									  <td>Email</td> 
									  <td><?php echo $user_details->email; ?></td>
									</tr>
									
									<tr>
									  <td>State</td> 
									  <td><?php echo $user_details->state_name; ?></td>
									</tr>
									
									<tr>
									  <td>District</td> 
									  <td><?php echo $user_details->district_name; ?></td>
									</tr>
									
									<tr>
									  <td>Block</td> 
									  <td><?php echo $user_details->block_name; ?></td>
									</tr>
									
									<tr>
									  <td>Payment Type</td> 
									  <td> 
									  <select class="form-control"  name="pay_mode" id="pay_mode">
										<option value="">Payment Mode</option>
										<option value="DD">DD</option>
										<option value="CHECK">CHECK</option>
										<option value="CHALAN">CHALAN</option>
										<option value="CASH">CASH</option>
									  </select> 
									  </td>
									  
									  <td>Ref. No</td> 
									  <td> 
									  <input type="textbox" class="form-control underline-input" name="ref_no" id="ref_no" value=""/>
									  </td>
									  
									  <td>Amount</td> 
									  <td> 
									  <input type="textbox" class="form-control underline-input" name="amount" id="amount" value="<?php echo $user_details->total_price_to_paid; ?>" readonly />
									  </td>
									  
									  <td><input type="submit" name="btn_off_pay_accept" id="btn_off_pay_accept" class="btn btn-raised btn-success edit_btn" style="background-color: #358138;" value="Approve Payment"/></td>
									  
									</tr>
									
								</tbody>
								<input type="hidden" name="hid_record_id" id="hid_record_id" value="<?php echo $record_id; ?>"/>
								<input type="hidden" name="hid_file_num" id="hid_file_num" value="<?php echo $user_details->file_no; ?>"/>
								<?php echo form_close(); ?>
								</table>
							
                        </div>
					</div>
				</div>
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
        
		$("#btn_off_pay_accept").click(function(){
			if($("#pay_mode option:selected").val() == '')
			{
				alert("Please select Payment Mode");
				$("#pay_mode").focus();
				return false;
			}
			else if($("#ref_no").val() == '')
			{
				alert("Please enter ref no.");
				$("#ref_no").focus();
				return false;
			}
			else if($("#amount").val() == '')
			{
				alert("Please enter amount");
				$("#amount").focus();
				return false;
			}
			else{
				return true;
			}
			
		});
        
    });
</script>
