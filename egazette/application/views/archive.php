<?php include_once 'page_initialization.php'; ?>
<!DOCTYPE html>
<html   class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Archive  | eGazette | Government of (StateName)</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <?php include_once 'website/include/header-scripts-style.php'; ?>
    </head>
    <body class="home-body">
        <?php include_once 'website/include/header-menu.php'; ?>
        <section class="main-container">
            <div class="container">
                <div class="row">

                    <!--Right Sidebar-1 Start-->
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 fadeInBottom">
                        <div class="infographic-section media-section">
                            <h3 class="head-section pink">Archive </h3>
                            <div class="content-part">
                                <div class="row">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg">
                                            <div class="search-gazette">
                                                <div class="form-group">
                                                    <label class="margin-bottom">Gazette Type</label>
                                                    <select class="form-control" id="sel1">
                                                        <option>All</option>
                                                        <option>Weekly Gazette</option>
                                                        <option>Extraordinary Gazette</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg">
                                            <div class="search-gazette">
                                                <form>
                                                    <div class="form-group">
                                                        <label class="margin-bottom" for="usr">Year</label>
                                                        <input type="text" class="form-control">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg">
                                            <div class="search-gazette">
                                                <form>
                                                    <div class="form-group">
                                                        <label class="margin-bottom" for="usr">Department</label>
                                                        <input type="text" class="form-control">
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg">
                                            <div class="search-gazette">
                                                <form>
                                                    <div class="form-group">
                                                        <label class="margin-bottom" for="usr">No of Gazettes</label>
                                                        <input type="text" class="form-control">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg">
                                            <div class="search-gazette">
                                                <form>
                                                    <div class="form-group">
                                                        <label class="margin-bottom" for="usr">Subject of Gazette</label>
                                                        <input type="text" class="form-control">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg">
                                            <div class="search-gazette">
                                                <form>
                                                    <div class="form-group">
                                                        <label class="margin-bottom" for="usr">Gazette No</label>
                                                        <input type="text" class="form-control">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg">
                                            <div class="search-gazette">
                                                <form>
                                                    <div class="form-group">
                                                        <label class="margin-bottom" for="usr">Gazette Date</label>
                                                        <input type="text" class="form-control">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Right Sidebar-1 End-->
                    <!--Left Sidebar-1 Start-->
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 left-side-bar">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 media-section fadeInScale">
                                <div class="infographic-section e-margin links">
                                    <h3 class="head-section green">Important Links</h3>
                                    <ul id="announcements-vertical-news">
                                        <li class="external"><a href="https://govtpress.(StateName).gov.in/" target="_blank">Directorate of Printing, Stationery & Publication</a></li>
                                        <li class="external"><a href="http://ct.(StateName).gov.in/" target="_blank">Department of Commerce & Transport</a></li>
                                        <li class="external"><a href="https://www.(StateName).gov.in/" target="_blank">State Portal of (StateName)</a></li>
                                        <!--                                        <li><a href="javascript:void(0)" target="_blank">Link 4</a></li>-->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Left Sidebar-1 End-->
                </div>
            </div>
        </section>
        <?php include_once 'website/include/footer.php'; ?>
        <?php include_once 'website/include/script.php'; ?>
    </body>
</html>