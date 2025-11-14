<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style nonce="8f0882ce3be14f201cadd0eff5726cbd" rel="stylesheet">
    .redirect_modal {
        z-index: 999999999;
    }
    .cm_message {
        font-family: 'Muli', sans-serif;line-height: 20px;
    }
    .right-align-div {
        float: right;
        /* color: darkblue; */
        background: #e06c05;
    }
    .main_nav ul li .submenu{
        background:#ffffff;
    }
    .main_nav ul li .submenu li a:hover{
        color:#ffffff;
    }
    .submenu a:hover{
         color:#ffffff;
    }
    /* .dropdown{
        position: relative;
        display: inline-block;
    }
    .dropdown:hover .dropdown-menu {
        display: block;
    } */
    .dropdown-menu{
        display: none;
        position: absolute;
        background-color: #ffffff;
        margin:5px !important;
    }
    .dropdown-menu li{
        color: #870204;
        /* padding: 10px; */
        text-decoration: none;
        display: block;
    }
    .dropdown ul li a {
        /* border-bottom: 1px solid #ddd; */
        padding: 4px 10px !important;
        font-size: 13px;
    }
    .dropdown-menu li:hover {
    background-color: #ececec;
    color: #262626;
    }
    bottom:focus, bottom:focus-visible{
        outline:none !important;
        box-shadow:none !important;
    }
    .dropdown-menu li a:hover {
        color: #262626 !important;
    }
    .dropdown-menu a{
        color: #870204 !important;
    }
    .dropdown-menu a:hover{
        color: #ffffff !important;
    }
    .btn-default:hover{
        background-color: #e06c05 !important;
        
    }
    .open>.dropdown-toggle.btn-default{
        background-color: #e06c05 !important;
        color:#ffffff!important;
    }
    /* .top-menu li a{
        margin:5px;
    } */
    .custom-menu{
        width: 160px !important;
        padding: 10px;
        border-radius: 0;
        margin: 0 !important;
    }
    .custom-menu li{
       padding: 0;
    }
    .custom-menu li a{
       padding: 7px !important;
    }
</style>
<!-- Popup modal for external link -->
<div id="external" class="modal fade redirect_modal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Alert</h4>
            </div>
            <div class="modal-body">
                <p>You are being redirected to the external website. Are you sure?</p>
                <a  href="#" target="_blank" class="btn btn-primary" >Yes, Proceed</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="cm_message" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Hon'ble CM's Message</h4>
      </div>
      <div class="modal-body">
            <p class="cm_message">
                I am happy that today Govt. Printing Press under Commerce and Transport Dept. is introducing e-Gazette system which would change the conventional method of publication of (StateName) Gazette to eco-friendly, paperless and through electronic mode, making the process more convenient and transparent to both citizens as well as indenting Departments. 
            </p><br/>

            <p class="cm_message">
                I am happy that such efforts are being taken by the State Government to ensure citizens get Govt. services without having to visit a Govt. office. This is the essence of our 5T mission. The citizen is the owner of the Govt. and he or she must remain at the focal point of all our programmes.
            </p><br/>

            <p class="cm_message">
                I congratulate Commerce & Transport Dept. and specifically the Govt. Printing Press on this occasion. Let us continue our concerted efforts for building a citizen centric Government.
            </p>
            <span><img src="<?php echo base_url(); ?>/assets/frontend/images/cm_sign.jpg"></span>
      </div>
    </div>
  </div>
</div>

<!-- Popup modal for external link -->
<header class="stricky">
    <!-- <marquee behavior="" direction="" height="50"><h1 style="font-size:25px;font-weight:bold;padding 20px;">Test</h1></marquee> -->
    
    <div class="header-setting">
            
        <a href="#skip-to-main" class="skiper">Skip To Main Content</a>

        <a href="javascript:void(0);" class="tooltip-button">
            <i class="glyphicon glyphicon-cog icon-spin"></i>
        </a>
        <div class="logiin-button">
            <div class="loginn"><a class="odia notranslate" href="<?php echo base_url(); ?>user/login" target="_blank">Login</a></div>
        </div>
    </div>
    <div class="top-header">
        <div class="topbar">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 res-none">
                        <ul class="top-menu left">
                            <li id="date"></li>
                            <li id="clock"></li>
                        </ul>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 setting-responsive margin-bottom-media">
                        <div class="top-right">
                            <ul class="top-menu">
                                <li><a id="lang_chng_btn" style="cursor: pointer;margin-top:0px;">Switch to Odia</a></li>
                                <li><a href="#skip-to-main" class="skiper" data-en="Skip To Main Content" data-or="ମୁଖ୍ୟ ବିଷୟବସ୍ତୁକୁ ଅତିକ୍ରମ କରନ୍ତୁ">Skip To Main Content</a></li>
                                <li><a href="<?php echo base_url(); ?>screen_reader" class="skiper2" data-en="Screen Reader Access" data-or="ସ୍କ୍ରିନ୍ ପାଠକ ପ୍ରବେଶ">Screen Reader Access</a></li>
                                <li>
                                    <div class="topbar-font skiper3">
                                        <div class="f-btn"> 
                                            <a href="javascript:void(0)" id="btn-decrease">A-</a> 
                                            <a href="javascript:void(0)" id="btn-orig">&nbsp;A</a> 
                                            <a href="javascript:void(0)" id="btn-increase"> &nbsp; A+</a>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="a-btn skiper4">
                                        <a href="javascript:void(0)" id="c-active">T</a>
                                        <a href="javascript:void(0)" id="c-inactive">T</a>
                                    </div>
                                </li>
                                <!--                                <li class="drop translation-links">
                                                                    <span><a class="odia notranslate" href="javascript:void(0)" data-lang="odia">ଓଡ଼ିଆ</a></span>
                                                                </li>-->
                                <li class="home-login">
                                    <!-- <div class="loginn"><a class="odia notranslate" href="<?php echo base_url(); ?>user/login" target="_blank">Login</a></div> -->
                                    <!-- <div class="loginn"><a class="odia notranslate" href="javascript:void(0);" target="_blank">Login</a></div>
                                        <ul class="submenu">
                                        <li><a href="<?php echo base_url(); ?>name_surname">Name/Surname</a></li>
                                        <li><a href="<?php echo base_url(); ?>partnership">Partnership</a></li>
                                        </ul>
                                    </li> -->
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" data-en="Login" data-or="ଲଗଇନ୍">
                                        <!-- <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1">  -->
                                            Login
                                            <span class="caret"></span>
                                            <!-- <span class="toggle-arrow"></span> -->
                                        </button>
                                        <ul class="dropdown-menu custom-menu" aria-labelledby="dropdownMenu1">
                                            <li><a href="<?php echo base_url(); ?>user/login" data-en="Nodal Officer" data-or="ନୋଡାଲ୍ ଅଧିକାରୀ">Nodal Officer</a></li>
                                            <li><a href="<?php echo base_url(); ?>commerce_transport_department/login_ct" data-en="C&T Dept" data-or="ସି&ଟି ବିଭାଗ">C&T Dept.</a></li>
                                            <li><a href="<?php echo base_url(); ?>igr_user/login" data-en="IGR" data-or="IGR">IGR</a></li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="logo-menu">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="logo">
                                    <a href="<?php echo base_url(); ?>"><img src="<?= base_url(); ?>assets/frontend/images/uplogo.png" class="img-responsive"></a>
                                </div>
                            </div>
                        </div>
                        

                        
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                        <div class="current-cm">
                            <span 
                                id="hoverable_text"
                                class="hover-audio"
                                data-en="<span>Shri Yogi Adityanath<br><i>Hon'ble Chief Minister</i></span>" 
                                data-or="<span>योगी आदित्यनाथ</i></span>">
                                Shri Yogi Adityanath<br>
                                <i>Hon'ble Chief Minister</i>
                            </span>

                            <div class="cm-pic-small pic1"><img style="background-size: cover;border-radius: 50%;width:200px" src="<?= base_url(); ?>assets/frontend/images/cm-up.jpg" class="img-responsive"></div>
                        </div>
                    </div>
                    <!--<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <div class="map-e fadeInScale">
                        </div>
                    </div>-->
                </div>
            </div>
        </div>
    </div>
</header>
<div class="menu-overlay"></div>
<nav class="main_nav h-over">
    <div class="container">
        <div class="responsive-menu">
            <span class="menu-text">Menu</span>
            <span class="hambergur-menu">
                <i></i>
                <i></i>
                <i></i>
            </span>
        </div>
        <ul class="main-ul">
            <span class="menu_close_btn"></span>
            <li><a class="link-3" href="<?php echo base_url(); ?>" id="hoverable_text" data-en="Home" data-or="ହୋମ୍">Home</a></li>
            <li id="menu-iteam-1 "><a href="<?php echo base_url(); ?>about_us/" id="hoverable_text" data-en="About Us" data-or="ଆମ ବିଷୟରେ">About Us</a> <span class="toggle-arrow"></span>
            </li>                    
            <li><a href="<?php echo base_url(); ?>about_gazette/" id="hoverable_text" data-en="About Gazette" data-or="ଗେଜେଟ୍ ବିଷୟରେ">About Gazette</a></li>
            <!-- <li id="menu-iteam-1"><a href="<?php echo base_url(); ?>search_gazette/" id="hoverable_text" data-en="Search Gazette" data-or="ଗେଜେଟ୍ ଖୋଜନ୍ତୁ">Search Gazette</a> <span class="toggle-arrow"></span></li> -->
			
			<li><a href="javascript:void(0);" id="hoverable_text" data-en="Archival" data-or="ଆର୍କାଇଭାଲ୍">Archival</a><span class="toggle-arrow"></span>
                <ul class="submenu">
                   <li><a href="<?php echo base_url(); ?>extraordinary_archival" id="hoverable_text" data-en="Extraordinary" data-or="ଅବାଜ୍ଞାତ">Extraordinary</a></li>
                   <li><a href="<?php echo base_url(); ?>weekly_archival" id="hoverable_text" data-en="Weekly" data-or="ସାପ୍ତାହିକ">Weekly</a></li>
                </ul>
            </li>
			
			<!--
            <li id="menu-iteam-1"><a href="<?php echo base_url(); ?>archive">Archive</a> <span class="toggle-arrow"></span></li>-->
            <li class="external"><a href="https://rtionline.gov.in/" target="_blank" id="hoverable_text" data-en="RTI" data-or="ଆରଟିଆଇ">RTI</a></li>
            <!--<li><a href="<?php echo base_url(); ?>applicants_login">Applicant Login</a></li>-->
            <!-- <li><a href="<?php echo base_url(); ?>contact_us/">Contact Us</a></li> -->
			<li><a href="<?php echo base_url(); ?>manual/public/manual.pdf" target="_blank" id="hoverable_text" class="pdf-link" data-en="User Manual" data-or="ବ୍ୟବହାରକାରୀ ମୋଡେଲ">User Manual</a></li>
            <li><a href="javascript:void(0);" id="hoverable_text" data-en="Online Services" data-or="ଅନ୍ଲାଇନ୍ ସେବାଗୁଡ଼ିକ">Online Services</a><span class="toggle-arrow"></span>
				<ul class="submenu">
				   <li><a href="<?php echo base_url(); ?>name_surname" id="hoverable_text" data-en="Name/Surname" data-or="ନାମ/ପରିଚୟ">Name/Surname</a></li>
				   <li><a href="<?php echo base_url(); ?>partnership" id="hoverable_text" data-en="Partnership" data-or="ସହଯୋଗ">Partnership</a></li>
				</ul>
			</li>
            <li>
                <!-- Add routes in href -->
                <!-- <a href="#">Grievance</a> -->
            </li>
        </ul>
    </div>
</nav>