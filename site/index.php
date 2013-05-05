
<!DOCTYPE html>
<html lang="en">
<head>
    <title></title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/css/reset.css" type="text/css" media="screen">
	<link rel="stylesheet" href="/css/style.css" type="text/css" media="screen">
	<link rel="stylesheet" href="/css/grid.css" type="text/css" media="screen">
	<script src="/js/jquery-1.6.3.min.js" type="text/javascript"></script>
    <script src="/js/FF-cash.js" type="text/javascript"></script>
    <script src="/js/jquery.featureCarousel.js" type="text/javascript"></script>
	<script type="text/javascript">
      $(document).ready(function() {
        $("#carousel").featureCarousel({
			autoPlay:7000,
			trackerIndividual:false,
			trackerSummation:false,
			topPadding:50,
			smallFeatureWidth:.9,
			smallFeatureHeight:.9,
			sidePadding:0,
			smallFeatureOffset:0
		});
      });
    </script>
	<!--[if lt IE 7]>
    <div style=' clear: both; text-align:center; position: relative;'>
        <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode">
        	<img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." />
        </a>
    </div>
	<![endif]-->
    <!--[if lt IE 9]>
   		<script type="text/javascript" src="js/html5.js"></script>
        <link rel="stylesheet" href="css/ie.css" type="text/css" media="screen">
	<![endif]-->
</head>
<body id="page1">

	<?php include ("template/header.php"); ?>
	 <div class="row-bot">
		<div class="center-shadow">
           	<div class="carousel-container">
				<div id="carousel">
					<div class="carousel-feature">
                          <a href="#"><img class="carousel-image" alt="" src="/images/gallery-img1.png"></a>                          
                    </div>
                    <div class="carousel-feature">
						<a href="#"><img class="carousel-image" alt="" src="/images/gallery-img3.png"></a>
                    </div>
                    <div class="carousel-feature">
						<a href="#"><img class="carousel-image" alt="" src="/images/gallery-img2.png"></a>
                    </div>
                </div>
			</div>
         </div>
    </div>
	<section id="content"><div class="ic"></div>
		<div class="main"> 
			<div class="container_12">
				<div class="wrapper">
					<article class="grid_8">
						<h3>Welcom to Bidforfix</h3>
                        <em class="text-1 margin-bot">Quick info about the site <a class="link" target="_blank" href="#">A link</a> and more info</em>
                    </article>
                </div>
            </div>
        </div>
		<div class="block"></div>
    </section>
  
	
	<?php include ("template/footer.php"); ?>
	
</body>
</html>