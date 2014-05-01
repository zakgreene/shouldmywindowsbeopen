<!doctype html>

<!--[if lt IE 7 ]> <html class="ie ie6 ie-lt10 ie-lt9 ie-lt8 ie-lt7 no-js" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 ie-lt10 ie-lt9 ie-lt8 no-js" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 ie-lt10 ie-lt9 no-js" lang="en"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 ie-lt10 no-js" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" lang="en"><!--<![endif]-->
<!-- the "no-js" class is for Modernizr. --> 

<head id="www-sitename-com" data-template-set="html5-reset">

	<meta charset="utf-8">
	
	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<title>Should My Windows Be Open?</title>
	
	<meta name="title" content="Should My Windows Be Open?" />
	<meta name="author" content="Zak Greene" />
	
	<meta name="google-site-verification" content="" />
	<!-- Speaking of Google, don't forget to set your site up: http://google.com/webmasters -->
	
	<meta name="Copyright" content="Zak Greene, 2014" />
	
	<!--  Mobile Viewport Fix
	http://j.mp/mobileviewport & http://davidbcalhoun.com/2010/viewport-metatag
	device-width : Occupy full width of the screen in its current orientation
	initial-scale = 1.0 retains dimensions instead of zooming out if page height > device height
	maximum-scale = 1.0 retains dimensions instead of zooming in if page width < device width
	-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<!-- Iconifier might be helpful for generating favicons and touch icons: http://iconifier.net -->
	<link rel="shortcut icon" href="_/img/favicon.ico" />
	<!-- This is the traditional favicon.
		 - size: 16x16 or 32x32
		 - transparency is OK -->
		 
	<link rel="apple-touch-icon" href="_/img/apple-touch-icon.png" />
	<!-- The is the icon for iOS's Web Clip and other things.
		 - size: 57x57 for older iPhones, 72x72 for iPads, 114x114 for retina display (IMHO, just go ahead and use the biggest one)
		 - To prevent iOS from applying its styles to the icon name it thusly: apple-touch-icon-precomposed.png
		 - Transparency is not recommended (iOS will put a black BG behind the icon) -->
	
	<!-- concatenate and minify for production -->
	<link rel="stylesheet" href="reset.css" />
	<link rel="stylesheet" href="style.css" />
	
	<!-- This is an un-minified, complete version of Modernizr. 
		 Before you move to production, you should generate a custom build that only has the detects you need.
	<script src="_/js/modernizr-2.6.2.dev.js"></script> -->
	
	<!-- Application-specific meta tags -->
	<!-- Windows 8 -->
	<meta name="application-name" content="" /> 
	<meta name="msapplication-TileColor" content="" /> 
	<meta name="msapplication-TileImage" content="" />
	<!-- Twitter -->
	<meta name="twitter:card" content="">
	<meta name="twitter:site" content="">
	<meta name="twitter:title" content="">
	<meta name="twitter:description" content="">
	<meta name="twitter:url" content="">
	<!-- Facebook -->
	<meta property="og:title" content="" />
	<meta property="og:description" content="" />
	<meta property="og:url" content="" />
	<meta property="og:image" content="" />

</head>

<?php

	// debug
	// ini_set('display_errors', 'On');
	// error_reporting(E_ALL);

	// phpinfo();

	if ($_SERVER['REMOTE_ADDR'] == "::1")
		$ip = "69.201.157.117";
	else
		$ip = $_SERVER['REMOTE_ADDR'];

	function detect_city($ip) {
	        
	    // $default = 'lat=20&lon=-90';  // somewhere reeeeeal hot
	    $lat 	= '11.825138'; //NYC: 40.8291
	    $lon 	= '42.590275';
	    $city 	= "Djibouti";

		// Get location by IP
		$ip_json 	= file_get_contents("http://freegeoip.net/json/".$ip);
		$json_loc 	= json_decode($ip_json,true);

		if ($json_loc["latitude"])
			return array($json_loc["latitude"], $json_loc["longitude"], $json_loc["city"]);
		else
			return array($lat, $lon, $city);
	}

	list($lat, $lon, $city) = detect_city($ip);

	// sf
	// $lat 	= 37.774929;
	// $lon 	= -122.419416;
	// $city 	= "San Francisco";

	// berlin
	// $lat 	= 52.520007;
	// $lon 	= 13.404954;
	// $city 	= "Berlin";

	// phoenix
	// $lat 	= '33.448377';
 //    $lon 	= '-112.074037';
 //    $city 	= "Phoenix";


	$string = file_get_contents("http://api.openweathermap.org/data/2.5/weather?lat=" . $lat . "&lon=" . $lon . "&mode=json");
	$json_w	= json_decode($string,true);

	// temperature in fahrenheit
	$temp 	= round($json_w["main"]["temp"] * 1.8 - 459.67);

	// raining?
	if ($json_w["weather"][0])
		$weather_id = $json_w["weather"][0]["id"];
	else
		$weather_id = 0;

	if ($weather_id > 200 && $weather_id < 600) {
		$rain = true;
	}
	else if ($weather_id >= 600 && $weather_id < 700) {
		$snow = true;
	}
	else {
		$rain = false;
		$snow = false;
	}

	// weather ids
	// ------------
	// thunderstorm 200 - 232
	// drizzle		300 - 321
	// rain 		500 - 537
	// snow 		600 - 622

	// should it be open?
	if ($temp >= 85 && !($rain)) {
		$should_it 	= "No";
		$message 	= "{$temp}&deg;&thinsp;is just too darn hot for that.";
	}
	else if ($temp < 85 && $temp >= 65 && !($rain)) {
		$should_it 	= "Yes";
		$message 	= "It&rsquo;s {$temp}&deg;&thinsp;out!";
	}
	else if ($temp >= 65 && $rain) {
		$should_it 	= "No";
		$message 	= "It&rsquo;s raining! {$temp}&deg;&thinsp;out, though.";
	}
	else if ($temp < 65 && $temp >= 55 && !($rain)) {
		$should_it 	= "Maybe";
		$message 	= "It&rsquo;s {$temp}&deg;&thinsp;out. Not too shabby.";
	}
	else if ($temp < 65 && $temp >= 55 && $rain) {
		$should_it	= "No";
		$message 	= "It&rsquo;s raining. :/";
	}
	else if ($temp < 55 && $rain) {
		$should_it 	= "No";
		$message 	= "It&rsquo;s {$temp}&deg;&thinsp;out, <em>and</em> it's raining. Keep those suckers closed.";	
	}
	else {
		$should_it 	= "No";
		$message 	= "It&rsquo;s {$temp}&deg;&thinsp;out. Brr.";
	}

	echo "<p id='loc'>" . $city . "</p>";

	function htmldump($variable, $height="20em") {
		echo "<pre style=\"border: 1px solid #000; height: {$height}; overflow: auto; margin: 0.5em; max-width: 800px; text-align: left; color: #333; background: #fff; z-index: 50; position: relative;\">";
		var_dump($variable);
		echo "</pre>\n";
	}

	// call the Flickr API
	// "hey flickr api, heeeyyyy"

	$params = array(
		'api_key'	=> 'd02cc0e280dc14f64e3b208dd16346e1',
		'method'	=> 'flickr.photos.search',
		'lat'		=> $lat,
		'lon'		=> $lon,
		'accuracy'	=> 6, // city level = 11
		'per_page'	=> 20,
		'media'		=> 'photos',
		'sort'		=> 'relevance',
					// =>	'interestingness-desc',
		'text'		=>	"window",
		// 'geo_context'=> 2, // outdoors
		'format'	=> 'php_serial',
	);

	$encoded_params = array();

	foreach ($params as $k => $v){

		$encoded_params[] = urlencode($k).'='.urlencode($v);
	}


	#
	# call the API and decode the response
	#

	$url = "https://api.flickr.com/services/rest/?".implode('&', $encoded_params);

	$rsp 			= file_get_contents($url);
	$rsp_obj 		= unserialize($rsp);

	$rand 	= rand(0, 19);
	$photo	= $rsp_obj['photos']['photo'][$rand];


	# if the call to flickr works, load an img into a variable

	if ($rsp_obj['stat'] == 'ok'){

		$flickr_call = true;

		$photo_url	= "http://farm{$photo['farm']}.staticflickr.com/{$photo['server'
		]}/{$photo['id']}_{$photo['secret']}.jpg";

		// echo htmldump($rsp_obj);

		// echo '<img id="photo" src="' . $photo_url . '" />';
	}else{

		echo "Call failed!";
		$flickr_call = false;
	}

	if ($flickr_call && extension_loaded('imagick')) {

		if ($should_it == "No" && $temp >= 85) {	
			$top 	= "#da8c10"; // dark
			$bottom = "#fcf1de"; // light
		}
		// set colors
		else if ($should_it == "No") {
			$top 	= "#004761"; // dark
			$bottom = "#3693b6"; // light
		}
		else { // if yes or maybe
			$top 	= "#1f97a9"; // dark
			$bottom = "#24cae3"; // light
		}

		$flickr_image = file_get_contents($photo_url);
		$name = tempnam("/tmp", "flickr");
		file_put_contents($name, $flickr_image);

	    $im = new Imagick($name);
	    $im->modulateImage(100, 0, 100);
	    // $im->colorizeImage('#005a80', 1);

	    $clut = new Imagick();
	    $clut->newPseudoImage(100, 100, "gradient:".$top."-".$bottom);
		$im->clutImage($clut);

	}

	?>

<body style="background-image: <?php if ($flickr_call): ?>url(data:image/jpg;base64,<?php echo base64_encode($im); endif;?>)" class="<?php echo strtolower($should_it); if($rain) echo " rain"; if($temp >= 85) echo " hot" ?>">

<header>
	<h1><?php echo $should_it ?>.</h1>

	<h2><?php echo $message ?></h2>
</header>

<!-- Prompt IE 6 users to install Chrome Frame. Remove this if you support IE 6.
		 http://chromium.org/developers/how-tos/chrome-frame-getting-started -->
<!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->

<!-- Grab Google CDN's jQuery. fall back to local if necessary -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write("<script src='jquery-1.9.1.min.js'>\x3C/script>")</script>

<script src="jquery.fittext.js"></script>
<script src="functions.js"></script>
<!-- Asynchronous google analytics; this is the official snippet.
	 Replace UA-XXXXXX-XX with your site's ID and uncomment to enable.
	 
<script>

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-XXXXXX-XX']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
-->
  
</body>
</html>
