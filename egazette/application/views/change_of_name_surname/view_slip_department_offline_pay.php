<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="<?php echo base_url(); ?>assets/js/vendor/filestyle/bootstrap-filestyle.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script> 
<link rel="stylesheet" type="text/css" nonce="8f0882ce3be14f201cadd0eff5726cbd" href="<?php echo base_url(); ?>assets/css/croppie.css"/>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2" src="<?php echo base_url(); ?>assets/js/croppie.min.js"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2" src="<?php echo base_url(); ?>assets/js/croppie.js"></script>
<style nonce="8f0882ce3be14f201cadd0eff5726cbd">
    #place-error{
        position: absolute;
        width: 100%;
        /* margin-left: -125px; */
    }
    #notice_date-error{
        position: absolute;
        width: 100%;
        left: 0;
        bottom: -28px;
    }
    #salutation-error{
        position: absolute;
        width: 100%;
    }
    #name_for_notice-error{
        position: absolute;
        width: 100%;
        /* margin-left: -125px; */
    }
    #age-error{
        position: absolute;
        width: 100%;
        /* margin-left: -125px; */
    }
    #old_name-error{
        position: absolute;
        width: 100%;
        /* margin-left: -125px; */
    }
    #new_name-error{
        position: absolute;
        width: 100%;
        /* margin-left: -125px; */
    }
    #new_name_one-error{
        position: absolute;
        width: 100%;
        /* margin-left: -125px; */
    }
    #new_name_two-error{
        /* position: absolute; */
        width: 100%;
        left:0;
        text-align: left;
        /* margin-left: -227px; */
    }
    #new_name_two_minor-error{
        position: absolute;
        width: 100%;
        left:0;
        text-align: left;
    }
    #docu_1-error,#docu_2-error,#docu_4-error,#docu_5-error,#docu_6-error,#docu_7-error {
        display: block;
        position: absolute;
        bottom: 0px;
    }

    .loader{
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        display: none;
        background: url('<?php echo base_url(); ?>assets/images/loader.gif') 50% 50% no-repeat rgb(249,249,249);
    }
    .error {
        color: #D9534F !important;
    }
    .remove_buttom_border{
        border-bottom:none !important;
    }
    .left-align-text {
        text-align: left !important;
    }
    .age_text {
        width: 100px;
    }
    .gazette-nav-bar {
        padding: 0px 0px; 
        border-top: 0px solid #000;
        text-align:center;         
    }
    .div_sign {
        text-align:right;         
    }   
    .notice_date_design {
        border: 0;
    background-image: linear-gradient(#49cdd0, #49cdd0),linear-gradient(#D2D2D2, #D2D2D2);
    background-size: 0 2px, 100% 1px;
    background-repeat: no-repeat;
    background-position: center bottom,center calc(100% - 1px);
    background-color: transparent;
    transition: background 0s ease-out;
    float: none;
    box-shadow: none;
    border-radius: 0;
    font-weight: 200 !important;
    }
    .custom-radio-btn label{
        padding-left: 22px;
        margin-right: 20px;
    }
    .custom-radio-btn .circle{
        left: 0px;
    }
    .custom-radio-btn{
        /* border: 1px solid #ddd; */
        padding: 5px 0px;
        margin: 0;
    }
    
    .custom-radio-btn .check{
        left: 0;
     }
    .file_icons_add{
        padding: 0;
        font-size: 14px;
     }
     .file_icons_add .i{
        font-size: 14px;
     }
     .custom-file-find .btn{
       padding:0;
     }
     .bootstrap-filestyle .btn-default{
        margin-top: -7px;
     }
    #address{
        height: 37px;
        position: relative;
        top: 21px;
        width: 330px;
    }
    #address_minor{
        height: 37px;
        position: relative;
        top: 21px;
        width: 330px;
    }
    #place_minor-error{
        position:absolute;
    }
    #name_for_notice_minor-error{
        position:absolute;
    }
    #old_name_minor-error{
        position:absolute;
    }
    #new_name_minor-error{
        position:absolute;
    }
    #new_name_one_minor-error{
        position:absolute;
    }
    #new_name_one_minor{
        padding:5px 15px !important;
    }
    #address_minor {
    height: 37px;
    position: relative;
    top: 21px;
    }
    .btn.btn-default:hover{
      background:#4caf50 !important;
    }
    #address-error{
        position: absolute;
        bottom: -38px;
    }
    #address_minor-error{
        position: absolute;
        bottom: -38px;
    }
    .form-group .checkbox label, .form-group .radio label, .form-group label {
    font-size: 13px;
    }
    .custom_notic_width{
        width:250px;
    }
    .custom-date{
        position:relative;
    }
</style>

<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    $image_crop = $('#dp_preview').croppie({
        enableExif: true,
        enableResize: true,
        enableOrientation: true,
        viewport: {
            width: 200,
            height: 300,
            type: 'rectangle' //circle
        },
        boundary: {
            width: 500,
            height: 300
        }
    });
	
	function printDiv() 
	{
		var divToPrint=document.getElementById('table_print');
		htmlToPrint = divToPrint.outerHTML;
		newWin = window.open("");
		newWin.document.write("<h3 align='center'>Print Page</h3>");
		newWin.document.write(htmlToPrint);
		newWin.print();
		newWin.close();
	}
	/* var htmlToPrint = '' +
        '<style type="text/css">' +
        'table th, table td {' +
        'border:1px solid #000;' +
        'padding;0.5em;' +
        '}' +
        '</style>';
    htmlToPrint += divToPrint.outerHTML; */
</script>
<div class="loader"></div>
<section id="content">

    <div class="page page-forms-validate">
        <!-- bradcome -->

        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
            <li>Extraordinary Gazette</li>
            <li class="active">Offline Pay Slip</li>
        </ol>

        <!-- <div class="bg-light lter b-b wrapper-md mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">Change of Name/Surname Application</h1>
                </div>
            </div>
        </div> -->

        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>OFFLINE PAY SLIP FOR EXTRAORDINARY GAZETTE </strong></h3>
                    </div>
                    
                    <div class="boxs-body">
                        
                        <div class="row">
                            
                            <div class="form-group col-md-9">
							<?php if($status_set == '1') { ?>
                                <table class="table" id="table_print">
								  <tbody>
                                    <tr>
                                      <td>Department Name</td> 
                                      <td><?php echo $dept_name; ?></td>
                                    </tr>

									<tr>
									  <td>Name</td> 
									  <td><?php echo $depositor_name; ?></td>
									</tr>
									
									<tr>
									  <td>Head Of Account</td> 
									  <td>0058-00-200-0127-02082-000</td>
									</tr>
									
									<tr>
									  <td>Description</td> 
									  <td>Change of Surname Gazette Publication</td>
									</tr>
									
									<tr>
									  <td>Mobile No</td> 
									  <td><?php echo $mobile; ?></td>
									</tr>
									
									<tr>
									  <td>Email</td> 
									  <td><?php echo $email; ?></td>
									</tr>
									
									<tr>
									  <td>Price</td> 
									  <td><?php echo $tot_amnt; ?></td>
									</tr>
									
								  </tbody>
								</table>
							<?php } else { ?>
									<p> Unable to generate offline slip </p>
							<?php } ?>
                        </div>
					</div>
            <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                <button class="btn btn-raised btn-success change-name-sur-submit" id="form4Submit" onclick='printDiv();'>Print</button>
            </div>
        </div>
		</section>
    </div>
</div>
</section>
<div class="modal fade" id="imageCropModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog croppie-container cr-resizer-horisontal">
        <div class="modal-content modal-size">
            <div class="modal-header">
                <h4 class="modal-title">Crop</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body" >
                <div class="row marginT8">
                    <div class="col-md-8 text-center">
                        <div class="image_crop" id="dp_preview"></div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="for_documents" value="0">
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="crop_identity" data-dismiss="modal">Crop</button>
            </div>
        </div>
    </div>
</div>
