<!DOCTYPE html>
<!-- saved from url=(0045)https://es-staging.cdac.in/esign2.1level1/OTP -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/frontend/esign_sample/bootstrap.min.css">

<script src="<?php echo base_url(); ?>assets/frontend/esign_sample/jquery.min.js.download"></script>

<script src="<?php echo base_url(); ?>assets/frontend/esign_sample/bootstrap.min.js.download"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/esign_sample/log4js.js.download"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/esign_sample/esapi.js.download"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/esign_sample/ESAPI_Standard_en_US.properties.js.download"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/esign_sample/Base.esapi.properties.js.download"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/esign_sample/esignVid.js.download"></script>

<script type="text/javascript">
	org.owasp.esapi.ESAPI.initialize();
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/esign_sample/jsencrypt.js.download"></script>

<title>C-DAC's eSign Service</title>
<link rel="shortcut icon" type="image/png" href="https://es-staging.cdac.in/esign2.1level1/resources/images/eHastaksharTab.png">
</head>
<body cz-shortcut-listen="true">
	<div class="container">
		<div class="container-fluid" style="margin-top: 10px;">
                    <img id="eSignBanner" alt="" width="100%" src="<?php echo base_url(); ?>assets/frontend/esign_sample/eSignHeaderBanner.png">
		</div>
		<div class="container" style="padding: 1px 0; text-align:center; color: #FFFFF0; background-color: #393;font-size: 18px;"></div>
		<div id="domainDisplay" style=" padding: 4px 0;text-align:center;font-size:15px;color: #31708f; margin-bottom: 10px;">
			You are currently using C-DAC eSign Service and have been redirected from
		</div>
		<div id="loginbox" style="margin-top: 50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
			<div>
                            <img id="eSignHashtasharImg" style="margin-left:35%;" src="<?php echo base_url(); ?>assets/frontend/esign_sample/eSign-eHashtakshar.png" width="103" height="51">
			</div>
			<div class="panel panel-info">
				<div class="panel-heading">
					<div class="panel-title">
						<b>Aadhaar Based e-Authentication</b>
					</div>
				</div>
				<div style="padding-top: 30px" class="panel-body">
					<div style="display: none" id="login-alert" class="alert alert-danger col-sm-12"></div>
					<form id="loginform" method="POST" class="form-horizontal" role="form">
						<div style="margin-bottom: 3px" class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
							<input id="VidId" type="text" class="form-control" name="VirtualNumber" placeholder="Enter Your Virtual ID" required="" maxlength="16">
						</div>
						<div id="VidRef" style="margin-top: 1%;margin-left: 83%;text-decoration: underline;margin-bottom: 1%;" class="input-group">
								<a id="vId" target="_black" href="https://resident.uidai.gov.in/web/resident/vidgeneration"><small style="font-size: 92%;"><b>Get Virtual ID</b></small></a>
						</div>
						<div style="margin-bottom: 15px" class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                                        <input id="OTPId" type="password" class="form-control" name="OTP" maxlength="6" placeholder="Enter Your Aadhaar OTP" required="" disabled="">
						</div>

						<!-- adding model for consent -->
						<div id="chkId" style="display: none;">
							<input id="chk" type="checkbox" name="Terms" value="Conditions">
							I have read and provide my <a id="cont" href="https://es-staging.cdac.in/esign2.1level1/OTP#myModal1" style="text-decoration: underline;" rel="nofollow noopener" data-toggle="modal"><b>consent</b></a>

							<!-- Modal -->
							<div class="modal fade" id="myModal1" role="dialog">
								<div class="modal-dialog">

									<!-- Modal content-->
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal">×</button>
											<h4 class="modal-title">Consent</h4>
										</div>
										<div class="modal-body">
											<p style="text-align: justify;">
												 I hereby state that I have no objection in
					authenticating myself with Aadhaar based authentication system and
					consent to providing my Aadhaar number and One Time Pin (OTP) data for
					Aadhaar based authentication. I understand that the OTP I provide
					for authentication shall be used only for authenticating my
					identity through the Aadhaar Authentication system and for obtaining
					my e-KYC through Aadhaar e-KYC service only for the purpose of esigning.
					
											</p>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
										</div>
									</div>

								</div>
							</div>
							<!-- end model consent -->
							
							<!-- adding modal for help -->
						
						<!-- <a id="cont" href="#myModal2" style="text-decoration: underline; margin-left: 30%"  rel="nofollow noopener" data-toggle="modal"><small
								style="font-size: 92%;"><b>Help</b></small></a> -->
						
						<!-- Modal -->
						<div class="modal fade" id="myModal2" role="dialog">
						<div class="modal-dialog">
						
						<!-- Modal content-->
						<div class="modal-content">
						<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">×</button>
						<h4 class="modal-title">Help</h4>
						</div>
						<div class="modal-body">
						<p style="text-align: justify;">1. The Aadhaar number of the eSign signer shall be displayed as <b>xxxxxxxx1234</b>, where "1234" shall be the last 4 digits of the Aadhaar number.<br>
							2. Read the consent details and provide the consent for eKYC transaction.<br>
							3. The signer can view the document information as received by C-DAC ESP from the ASP by clicking the <b>View 
									document information</b> button.<br>
							4. For OTP based authentication, Enter <b>6 digit</b> valid OTP.<br>
							5. In case a signer enters incorrect OTP,  signer can get <b>1 attempt</b> to reuse recently received OTP.<br>
							6. In case a signer does not receive OTP or in case of unsuccessful authentication, the signer can use <b>Resend OTP</b> Link to re-generate OTP.<br>
							7. The signer can attempt <b>3 times</b> for successful authentication with UIDAI.<br>
							8. Click on <b>Submit</b> Button to proceed with eSigning.<br>
							9. The signer can cancel a transaction before submitting by using <b>Cancel</b> Link.<br>
					    </p>
						</div>
						<div class="modal-footer">
						<button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
						</div>
						</div>
						
						</div>
						</div>
						<!-- end modal help -->
								
						<!-- <a id="viewInfoId" href="javascript:void(0);" style="text-decoration: underline; margin-left: 3%"> <small
								style="font-size: 92%;"><b>Cancel</b></small></a>-->
						</div> 
						<div id="msg" style="color: #B22222; margin-bottom: -5px" class="input-group">
							
						</div>
						<div style="margin-bottom: 6%;margin-top: 1%;">
						<a id="viewInfoId" href="https://es-staging.cdac.in/esign2.1level1/OTP#myModal" style="float: right;text-decoration: underline;" rel="nofollow noopener" data-toggle="modal"><small style="font-size: 92%;"><b>View 
									Document Information</b></small></a>
									</div>
						
						

						

						<div class="form-group">
							<div class="col-md-12 control">
								<div style="border-top: 1px solid #888; padding-top: 15px; font-size: 85%">

								</div>
							</div>
						</div>

						<div style="margin-top: 10px" class="form-group">
							<!-- Button -->

							<div class="col-sm-12 controls">
                                                            <button type="button" id="getOtpId" value="Submit" style="width: 20%" class="btn btn-success">Get OTP</button>
                                                            <?php 
                                                                $this->session->set_userdata(array(
                                                                        'demo_signed' => true
                                                                    ));
                                                                $this->session->set_flashdata('success', 'Document Signed Successfully'); 
                                                            ?>
                                                            <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" type="button" id="redirectId" value="Submit" style="width: 20%" class="btn btn-success">Submit</a>
                                                            <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" type="button" id="cancelId" value="CANCEL" style="width: 20%" class="btn btn-success">Cancel</a>
							<div id="resOtpDiv" style="font-size: 92%; margin-left: 6%; float: right; margin-top: 2%;">
									<small style="font-size: 98%; color: red">Not Received
										OTP? <a id="rsOtp" href="javascript:void(0);" title="You have 3 attempt(s) left to regenerate OTP" style="text-decoration: underline;"> Resend OTP</a>
									</small>
								</div>

								<!-- Modal -->
								<div class="modal fade" id="myModal" role="dialog">
									<div class="modal-dialog">

										<!-- Modal content-->
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">×</button>
												<h4 class="modal-title">
													Document(s) Information (as provided by ASP)
												</h4>
											</div>
											<div class="modal-body">
												<table class="table table-bordered">
													<thead style="background-color: #d9edf7; color: #31708f;">
														<tr>
															<th class="col-xs-2">ID</th>
															<th class="col-xs-10">Hash of the Document</th>
														</tr>
													</thead>
													<tbody>
														
															<tr>
																<td class="col-xs-2">1</td>
																<td class="col-xs-2"><a data-toggle="tooltip" data-placement="top" title="" data-original-title="mydoc">cf2ed3713bbbe30b380593fac25ea60c4d607c1240244ccea3816b68d0617063</a></td>
															</tr>
														
													</tbody>
												</table>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>

				</div>
			</div>
			<div id="infoId" style="font-weight: bold; color: #1172D9; margin-bottom: 25px;">
				
			</div>

			<div id="loading-indicator" style="position: absolute; top: 58%; left: 45%; display: none;">
				<img src="<?php echo base_url(); ?>assets/frontend/esign_sample/ajax-loader.gif">
			</div>
			<div id="loading-indicator-text" style="position: absolute; top: 60%; left: 42%; display: none;">
				<br> <b>processing...</b>
			</div>

			<div></div>

		</div>

		<div class="container" style="margin-bottom: 2cm;"></div>
		<div style="margin-bottom: 25px" class="input-group"></div>
	</div>

	<textarea id="hitc" style="display: none;">3</textarea>
	<textarea id="reusec" style="display: none;">2</textarea>
	<textarea id="pubkey" rows="15" cols="65" style="display: none;">-----BEGIN PUBLIC KEY-----MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA2rERrw/UerMkJJHnVSqbrSuV/QQrfJ3D0kK7ULQkwwYV6YWaF5JJ9RQKigQwNHGPr58ve27nSUQPOqDYNRhOrwI3QtuAAII013XGJQyJOJk4yrB9jzHkZZHqpFp74LC3Fdg8/hDYKIdHw7RswiiiDvnTezpTY+CyZn2HmdKtB0DEEBP0MmIHCdiJ6VKdJRmT3c/gWr7SzOE0INH7utf0mNUT1+r9QBaRkynCYANkFFeu2v4TsFAb68zQct75dQDzeAG+c+pz65x0f5UaNpTOUQsO4ed0WrIYl3zCudUauWQ84n38CiKO0dXylikV7UAx/t/Ir7VEvrKYLjvmrOvhmQIDAQAB-----END PUBLIC KEY-----</textarea>
	<textarea id="eSignMode" style="display: none;">form</textarea>
<script>
    $(document).ready(function(){
        $('#redirectId').hide();
        $('#getOtpId').on('click', function(){
            $('#redirectId').show();
            $('#getOtpId').hide();
            $('#OTPId').removeAttr('disabled');
        });
    });
</script>
</body></html>