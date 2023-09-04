<!DOCTYPE html>
<html lang="<?= MY_LANGUAGE_ABBR ?>">

	<head>
		<!-- <script>
		var gaProperty = 'G-X9N04MYG16';
		var disableStr = 'ga-disable-' + gaProperty;
		if (document.cookie.indexOf(disableStr + '=true') > -1) {
			window[disableStr] = true;
		}

		function gaOptout() {
			document.cookie = disableStr + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';
			window[disableStr] = true;
		}
		</script> -->


		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-X9N04MYG16">
		</script>

		<script>
			window.dataLayer = window.dataLayer || [];

			function gtag() {
				dataLayer.push(arguments);
			}
			gtag('js', new Date());
			gtag('config', 'G-X9N04MYG16');
			gtag('config', 'AW-428847483');
		</script>


		<!-- <meta http-equiv="cache-control" content="no-cache" /> -->
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="description" content="<?= $description ?>" />
		<meta name="keywords" content="<?= $keywords ?>" />
		<meta property="og:title" content="<?= $title ?>" />
		<meta property="og:description" content="<?= $description ?>" />
		<meta property="og:url" content="<?= LANG_URL ?>" />
		<meta property="og:type" content="website" />
		<meta property="og:image"
			content="<?= isset($image) && !is_null($image) ? $image : base_url('assets/img/site-overview.png') ?>" />
		<title>
			<?= $title ?>
		</title>
		<link rel="icon" type="image/vnd.microsoft.icon" href="<?= base_url('ico/favicon.ico') ?>">
		<link rel="stylesheet" href="<?= base_url('assets/css/w3.css') ?>" />

		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<!-- <link rel="stylesheet" href="<?= base_url('assets/css/font-awesome.min.css') ?>" /> -->

		<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>" />
		<link rel="stylesheet" href="<?= base_url('assets/bootstrap-select-1.12.1/bootstrap-select.min.css') ?>" />

		<link href="<?= base_url('assets/css/bootstrap-datepicker.min.css') ?>" rel="stylesheet" />
		<link href="<?= base_url('assets/templatecss/custom.css') ?>" rel="stylesheet" />
		<link href="<?= base_url('cssloader/theme') ?>" rel="stylesheet" />
		<script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
		<script src="<?= base_url('loadlanguage/all') ?>"></script>

		<!-- cookie 2 -->
		<link href="/dist/cookieconsent.css" rel="stylesheet" />
		<script src="/dist/cookieconsent.js"></script>
		<script var cookieconsent=initCookieConsent(); cookieconsent.run({ onAccept: function(cookies){
			if(cc.allowedCategory('analytics_cookies')){ cc.loadScript('https://www.google-analytics.com/analytics.js',
			function(){ ga('create', 'UA-XXXXXXXX-Y' , 'auto' ); //replace UA-XXXXXXXX-Y with your tracking code
			ga('send', 'pageview' ); }); } }, });></script>

		<?php if ($cookieLaw != false) { ?>
			<script type="text/javascript">
				window.cookieconsent_options = {
					"message": "<?= $cookieLaw['message'] ?>",
					"dismiss": "<?= $cookieLaw['button_text'] ?>",
					"learnMore": "<?= $cookieLaw['learn_more'] ?>",
					"link": "<?= base_url($cookieLaw['link']) ?>",
					"theme": "<?= $cookieLaw['theme'] ?>"
				};
			</script>
			<script type="text/javascript" src="<?= base_url('assets/js/cookieconsent.min.js') ?>">
			</script>

			<script type="text/javascript">
				window.addEventListener("resize", () => {
					document.body.classList.add("resize-animation-stopper");
				});
			</script>
			<script>
			if (window.sessionStorage.getItem("stopanimate") == null) { 
				
				window.sessionStorage.setItem("stopanimate" ,1);
			} 
			else{
				document.getElementById("float_phone").classList.add("animate_once");
			}
			</script>

		<?php } ?>
		<!--[if lt IE 9]>
				<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
				<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
				<![endif]-->
	</head>

	<?php if (isset($_GET['ID'])) 
	{
		$ID = $_GET['ID'];
		if($ID == '2021000100011')
		{
			header('Location: ' . base_url('ND_GC_1S_9'));
			die();
		} 
	if($ID == '0')
		{
			header('Location: ' . base_url('/admin'));
			die();
		} 
	}

	// header('Location: ' . base_url('/shop'));
	// die();
	?>

	<body>
		<a id="float_wa" class="float_wa " href="https://wa.me/+4917641736450"><img class="wa_widget_img" alt="Chat on WhatsApp" src="<?= base_url('png/wa_widget.png') ?>" /> </a>
		<a id="float_phone" class="float_phone" href="tel:+4917641736450"><img class="phone_widget_img" alt="Chat on WhatsApp" src="<?= base_url('png/phone_widget.png') ?>" /> </a>
		<a id="float_mail" class="float_mail resize-animation-stopper" href="mailto:kontakt@nodedevices.de"><img class="mail_widget_img" alt="Chat on WhatsApp" src="<?= base_url('png/mail_widget.png') ?>" /> </a>


		

		<div id="wrapper">
			<div id="content">
			<!-- <div class="user-panel">
                <?php if (isset($_SESSION['logged_user'])) { ?>
                    <a href="<?= LANG_URL . '/myaccount' ?>" class="my-acc">
                        <?= lang_safe('my_acc') ?>
                    </a>
                <?php } else { ?>
                    <a href="<?= LANG_URL . '/login' ?>" class="my-acc-login">
                        <?= lang_safe('login') ?>
                    </a>
                    <a href="<?= LANG_URL . '/register' ?>" class="my-acc-register">
                        <?= lang_safe('register') ?>
                    </a>
                <?php } ?>
                <div class="clearfix"></div>
            </div> -->
			
                <div class="top-part " id="top-part">
				
				<div id="languages-bar">
						<?php
						$num_langs = count($allLanguages);
						if ($num_langs > 0) {
							?>
							<ul class="header_lang_pull-right">
								<?php
								$i = 1;
								$lang_last = '';
								foreach ($allLanguages as $key_lang => $lang) {
									?>
									<li <?= $i == $num_langs ? 'class="last-item"' : '' ?>>
										<img src="<?= base_url('attachments/lang_flags/' . $lang['flag']) ?>"
											alt="Language-<?= MY_LANGUAGE_ABBR ?>"><a href="<?= base_url($key_lang) ?>/shop"><?= $lang['name'] ?></a>
									</li>
									<?php
									$i++;
								}
								?>
							</ul>
						<?php } ?>
					</div>

					<nav class="navbar navbar-custom" role="navigation">
						<div class="container-fluid">
							<!-- Brand and toggle get grouped for better mobile display -->
							<div class="navbar-header">
								<button type="button" class="navbar-toggle " data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
									<span class="sr-only">Toggle navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<a class="navbar-brand" href="#">
									<img class="nav_logo " alt="Brand" src="<?= base_url('png/NODEMATIC_BRAND_TRANSPARENT.png') ?>">
								</a>
								
								<a class="shopping_cart_div" href="<?= base_url() ?>shopping-cart">
									<img class="shopping_cart_img " src="<?= base_url('png/cart_white128.png') ?>">
									<span class="shopping_cart_counter sumOfItems">
										<?= is_numeric($cartItems) && (int) $cartItems == 0 ? 0 : $sumOfItems ?>
							</span>
							</img>
								<!-- <div class="burger_div">
								<a href="javascript:void(0);" class="top_burger" onclick="myFunction()">
									<i class="fa fa-bars burger"></i>
								</a> 
								</div>-->
								</a>
							</div>
						
						
						</div><!-- /.container-fluid -->

						<!-- Collect the nav links, forms, and other content for toggling -->
						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
							<ul class="nav navbar-nav">
								<li><a href="<?= LANG_URL ?>"><?= lang_safe('nav_startseite') ?></a></li>
								<li><a href="<?= LANG_URL . '/shop' ?>"><?= lang_safe('nav_shop') ?></a></li>
								<?php
								if (!empty($nonDynPages)) {
									foreach ($nonDynPages as $addonPage) { ?>
										<li><a href="<?= LANG_URL . $addonPage ?>"><?= mb_ucfirst(lang_safe($addonPage)) ?></a></li>
										<?php
									}
								}
								?>

								<li><a href="<?= LANG_URL . '/contacts' ?>"><?= lang_safe('nav_kontakt') ?></a></li>							
							</ul>
						</div><!-- /.navbar-collapse -->
					</nav>
				</div>

