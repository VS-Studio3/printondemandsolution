<?php
// Отключить все сообщения об ошибках
error_reporting(0);
/**
 * @version    $Id: index.php 20196 2011-01-09 02:40:25Z ian $
 * @package    Joomla.Site
 * @copyright  Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/* The following line gets the application object for things like displaying the site name */
$app = JFactory::getApplication();
$config = & JFactory::getConfig();
$menu = $app->getMenu();
$lang = JFactory::getLanguage();
$option = $_GET['option'];
$view = $_GET['view'];
$task = $_GET['task'];
  $itemid = JRequest::getVar('Itemid');
  $pmenu = $app->getMenu()->getActive();
  $pageclass = '';
 
  if (is_object($pmenu)) {
  	$pageclass = $pmenu->params->get('pageclass_sfx');
  }
?>
<!DOCTYPE html >
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>

<jdoc:include type="head" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/general.css" type="text/css" />
<!-- Bootstrap Core CSS -->
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/custom.css" rel="stylesheet">
        
		<!-- Customizable CSS -->
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/main.css" rel="stylesheet" data-skrollr-stylesheet>
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/green.css" rel="stylesheet" title="Color">
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/owl.carousel.css" rel="stylesheet">
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/owl.transitions.css" rel="stylesheet">
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/animate.min.css" rel="stylesheet">
		
		<!-- Fonts -->
		<link href="http://fonts.googleapis.com/css?family=Lato:400,900,300,700" rel="stylesheet">
		<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700,400italic,700italic" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700,400italic,700italic&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic&subset=latin,cyrillic-ext,cyrillic' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,500,400italic,700,700italic,500italic,300italic,100italic,100&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
		<!-- Icons/Glyphs -->
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/fonts/fontello.css" rel="stylesheet">
		
		<!-- Favicon -->
		<link rel="shortcut icon" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/favicon.ico">
		
		<!-- HTML5 elements and media queries Support for IE8 : HTML5 shim and Respond.js -->
		<!--[if lt IE 9]>
			<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/html5shiv.js"></script>
			<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/respond.min.js"></script>
		<![endif]-->
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/jquery.fancybox.css" type="text/css" />
		
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/jquery.fancybox.pack.js"></script>
		<script type="text/javascript">
			var $j = jQuery.noConflict();
			$j(document).ready(function(){
				$j('.fancybox').fancybox();
			});
		</script>
</head>
<body  id="<?php echo $pageclass ? htmlspecialchars($pageclass) : 'default'; ?>" class="<?php echo $option.' loyaut-'.$task. ' loyaut-'. $view ; ?>">
		
		<!-- ============================================================= HEADER ============================================================= -->
		
		<header>
			<div class="">
				
				<div class="navbar-header">
					<div class="container">
						
						<ul class="info pull-right">
                            <li class="telephone_head"> 8-800-555-38-16 </li>
							<li><a href="mailto:info@printondemandsolution.ru">info@printondemandsolution.ru</a></li>
						</ul><!-- /.info -->
						
					
                    </div>
						<!-- ============================================================= LOGO MOBILE ============================================================= -->
                    <div class="container">
						<a class="navbar-brand visible-xs" href="<?php echo $this->baseurl ?>"><img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/logo_mob_ver.png" class="logo visible-xs" alt=""></a>
						
						<!-- ============================================================= LOGO MOBILE : END ============================================================= -->

                        <a class="btn responsive-menu pull-right collapsed" data-toggle="collapse"
                           data-target=".navbar-collapse"><i class='icon-menu-1'></i></a>
						
					</div><!-- /.container -->
				</div><!-- /.navbar-header -->
				
				<div class="yamm">
					<div class="navbar-collapse collapse">
						<div class="container menus" style="max-height: 110px;">
							
							<!-- ============================================================= LOGO ============================================================= -->
							
							<a class="navbar-brand hidden-xs no_visible_xs" href="<?php echo $this->baseurl ?>"><img
                                    src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/logotype.png"
                                                                            class="logo hidden-xs" alt=""></a>
							
							<!-- ============================================================= LOGO : END ============================================================= -->
							
							
							<!-- ============================================================= MAIN NAVIGATION ============================================================= -->
							
							<jdoc:include type="modules" name="top-menu" />

                            <a href="#demo-form" class="order-demo btn btn-large fancybox hidden-xs">Заказать демо</a>

                                
							
							<!-- ============================================================= MAIN NAVIGATION : END ============================================================= -->
							
						</div><!-- /.container -->
					</div><!-- /.navbar-collapse -->
				</div><!-- /.yamm -->
			</div><!-- /.navbar -->
		</header>
		
		<!-- ============================================================= HEADER : END ============================================================= -->
		
		
		<!-- ============================================================= MAIN ============================================================= -->
		
		<main>
			
			<!-- ============================================================= SECTION – HERO ============================================================= -->
			
			
			<section>
			<jdoc:include type="modules" name="top-content" style="xhtml"/>
					<jdoc:include type="message" />
					<jdoc:include type="component" />
					<jdoc:include type="modules" name="bottom-component" style="xhtml"/>
			</section>
			


       
            
			
			<!-- ============================================================= SECTION – GET IN TOUCH : END ============================================================= -->

            <!-- ============================================================= SECTION – PRODUCT ============================================================= -->
            <section>
                    <jdoc:include type="modules" name="phone" style="xhtml"/>
            </section>
            <section>
                <div class="container">
                    <jdoc:include type="modules" name="fox-contact" style="xhtml"/>
                </div>

            </section>
            <!-- ============================================================= SECTION – PRODUCT : END ============================================================= -->
		</main>
		
		<!-- ============================================================= MAIN : END ============================================================= -->
		
		
		<!-- ============================================================= FOOTER ============================================================= -->
		
		<footer class="dark-bg">
			<div class="container inner">
				<div class="row">
					

					
					<div class="col-md-2 col-sm-2 hidden-xs inner">
                        <a href="<?php echo $this->baseurl ?>"><img class="logo img-intext" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/logo_footer.png"
                                                  alt=""></a>
					</div><!-- /.col -->


                    <div class="col-md-6 col-sm-6 col-xs-12 inner " style="padding-left: 80px;">
                        <div class="visible-xs logo_footers">
                            <a href="index.html" ><img class="logo img-intext"
                                                                         src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/logo_footer.png"
                                                                         alt=""></a>
                        </div>


                        <h4>Кто мы?</h4>
                        <!--	<a
                                href="index.html"><img class="logo img-intext" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/logo-white.svg" alt=""></a>-->
                        <h5 class="hidden-xs">PrintOnDemandSolution</h5>
                        <h5 class="visible-xs">Print On Demand Solution</h5>
                        <p>Компания PrintOnDemandSolution (ООО "Айфото сервис") специализируется на разработке и продаже облачного решения для подготовки и оформления онлайн заказов на различные виды полиграфической и сувенирной фотопродукции.</p>
                        <a href="about.html" class="know_more">Узнать больше</a>
                    </div><!-- /.col -->
					
					<div class="col-md-4 col-sm-4 col-xs-12 inner over_contact">
						<!--<h4>Get In Touch</h4>
						<p>Doloreiur quia commolu ptatemp dolupta oreprerum tibusam eumenis et consent accullignis dentibea autem inisita.</p>-->
						<ul class="contacts">
							<li class="number"> 8-800-555-38-16</li>
                            <li> <a href="#" class="btn btn-large">Заказать звонок</a></li>
                            <li class="meil"><a href="mailto:info@printondemandsolution.ru">info@printondemandsolution
                                .ru</a></li>
							<li class="skype">Skype: vitaly.klementiev</li>

						</ul><!-- /.contacts -->
					</div><!-- /.col -->

                    <!--<div class="col-md-3 col-sm-6 inner">
                        <h4>Free updates</h4>
                        <p>Conecus iure posae volor remped modis aut lor volor accabora incim resto explabo.</p>
                        <form id="newsletter" class="form-inline newsletter" role="form">
                            <label class="sr-only" for="exampleInputEmail">Email address</label>
                            <input type="email" class="form-control" id="exampleInputEmail" placeholder="Enter your email address">
                            <button type="submit" class="btn btn-default btn-submit">Subscribe</button>
                        </form>
                    </div><!-- /.col -->
					
				</div><!-- /.row --> 
			</div><!-- .container -->
		  
			<div class="footer-bottom hidden-xs">
				<div class="container inner">
					<p class="pull-left">© 2014 REEN. All rights reserved.</p>
					<ul class="footer-menu pull-right">
						<li><a href="index.html">о компании</a></li>
						<li><a href="portfolio.html">продукты</a></li>
						<li><a href="blog.html">отзывы</a></li>
						<li><a href="about.html">БЛОГ</a></li>
						<li><a href="services.html">контакты</a></li>
						<!--<li><a href="contact.html">Contact</a></li> -->
					</ul><!-- .footer-menu -->
				</div><!-- .container -->
			</div><!-- .footer-bottom -->
		</footer>
		
		<!-- ============================================================= FOOTER : END ============================================================= -->
		
		<!-- JavaScripts placed at the end of the document so the pages load faster -->
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/jquery.min.js"></script>
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/jquery.easing.1.3.min.js"></script>
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/bootstrap.min.js"></script>
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/bootstrap-hover-dropdown.min.js"></script>
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/skrollr.min.js"></script>
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/skrollr.stylesheets.min.js"></script>
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/waypoints.min.js"></script>
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/waypoints-sticky.min.js"></script>
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/owl.carousel.min.js"></script>
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/jquery.isotope.min.js"></script>
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/jquery.easytabs.min.js"></script>
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/jquery.slickforms.js"></script>
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/google.maps.api.v3.js"></script>
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/viewport-units-buggyfill.js"></script>
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/scripts.js"></script>
		
		<!-- For demo purposes – can be removed on production -->
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/green.css" rel="alternate stylesheet" title="Green color">
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/blue.css" rel="alternate stylesheet" title="Blue color">
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/red.css" rel="alternate stylesheet" title="Red color">
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/pink.css" rel="alternate stylesheet" title="Pink color">
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/purple.css" rel="alternate stylesheet" title="Purple color">
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/orange.css" rel="alternate stylesheet" title="Orange color">
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/navy.css" rel="alternate stylesheet" title="Navy color">
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/gray.css" rel="alternate stylesheet" title="Gray color">
		
		
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/switchstylesheet.js"></script>
		
		<script>
			$(document).ready(function(){ 
				$(".changecolor").switchstylesheet( { seperator:"color"} );
			});
		</script>
		<!-- For demo purposes – can be removed on production : End -->
	</body>


<div style="display:none;">
    <div id="demo-form">
    	
		<jdoc:include type="modules" name="demo-form" style="xhtml"/>
	</div>
</div>

</body>
</html>
