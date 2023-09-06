
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
	integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
	crossorigin="anonymous"></script>


<div class="home-container">
	<div id="carousel-example-generic" class="carousel slide  carousel-fade" data-ride="carousel">
		<ol class="carousel-indicators">
			<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
			<li data-target="#carousel-example-generic" data-slide-to="1"></li>
			<li data-target="#carousel-example-generic" data-slide-to="2"></li>
			<li data-target="#carousel-example-generic" data-slide-to="3"></li>
			<li data-target="#carousel-example-generic" data-slide-to="4"></li>
		</ol>

		<div class="carousel-inner" role="listbox">
			<div class="item active">
				<a href="#your-link-1">
                    <img src="https://s26.postimg.cc/7ayxq3q5l/cg5.jpg" width="100%">
                </a>
				<div class="carousel-caption">
					<h3>First Slide</h3>
					<p>Caption goes here<br></p>
				</div>
			</div>
			<div class="item">
				<a href="#your-link-2">
					<img src="https://s26.postimg.cc/zccz3svft/cg6.jpg" width="100%">
                </a>
				<div class="carousel-caption">
					<h3>Second slide</h3>
					<p>Caption goes here</p>
				</div>
			</div>
			<div class="item">
				<a href="#your-link-3">
					<img src="https://s26.postimg.cc/hyxmrttt5/cg1.jpg" width="100%">
                </a>
				<div class="carousel-caption">
					<h3>Third slide</h3>
					<p>Caption goes here</p>
				</div>
			</div>
			<div class="item">
				<a href="#your-link-4">
					<img src="https://s26.postimg.cc/7g2ozrxgp/cg4.jpg" width="100%">
                </a>
				<div class="carousel-caption">
					<h3>Fourth slide</h3>
					<p>Caption goes here</p>
				</div>
			</div>
			<div class="item">
				<a href="#your-link-5">
					<img src="https://s26.postimg.cc/l7244vg2x/cg2.jpg" width="100%">
                </a>
				<div class="carousel-caption">
					<h3>Fifth slide</h3>
					<p>Caption goes here</p>
				</div>
			</div>
		</div>

		<!-- Controls -->
		<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
			<span class="fa fa-chevron-left" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
			<span class="fa fa-chevron-right" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
	</div>
	<div class="headline-container">
		<h1 class="headline-title"><?=lang_safe('home_headline_title')?></h1>
		<p class="headline-text">
		<?=lang_safe('home_headline_text')?>
		</p>
	</div>
	<div class="w3-center" style="margin-bottom:25px;">
		<h1>
			<class="w3-black w3-display-center><b>In Kürze verfügbar </b>
		</h1>
		<img src="./jpg/Node_Devices.jpg" style="width:50%" onClick="Javascript:window.location.href = 'login.php'">
	</div>
	<div class="arrivals-container" style="background-color: #707070; padding: 20px;">
        <h1 class="title" style="margin-bottom: 20px;"><?=lang_safe('home_arrivals_title')?></h1>
        <p class="text" style="word-wrap: break-word;"><?=lang_safe('home_arrivals_text')?></p>
        <div class="row">
            <div class=" col-lg-3 col-md-6 col-sm-6 fade-in-panel">
                <div class="panel panel-default">
					<div class="panel-heading">
						<a href="#">
							<img src="<?php echo base_url('attachments/shop_images/arrival.jpg'); ?>" class="img-responsive" alt="Card Image">
						</a>
					</div>
                    <div class="panel-body">
						<p class="panel-text">
							<?=lang_safe('home_panel_text')?>
						</p>						
						<a href="#" class="panel-button">
							<span class="button-text"><?=lang_safe('home_panel_button')?></span>
							<svg class="svg-custom-style" fill="currentColor" viewBox="0 0 600 1100" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
								<path d="m592.44452,558.58248q0,13 -10,23l-466,466q-10,10 -23,10t-23,-10l-50,-50q-10,-10 -10,-23t10,-23l393,-393l-393,-393q-10,-10 -10,-23t10,-23l50,-50q10,-10 23,-10t23,10l466,466q10,10 10,23z"></path>
							</svg>
                    	</a>
                    </div>
                </div>
            </div>
            <div class=" col-lg-3 col-md-6 col-sm-6 fade-in-panel">
                <div class="panel panel-default">
					<div class="panel-heading">
						<a href="#">
							<img src="<?php echo base_url('attachments/shop_images/arrival.jpg'); ?>" class="img-responsive" alt="Card Image">
						</a>
					</div>
                    <div class="panel-body">
						<p class="panel-text">
							<?=lang_safe('home_panel_text')?>
						</p>
						<a href="#" class="panel-button">
							<span class="button-text"><?=lang_safe('home_panel_button')?></span>
							<svg class="svg-custom-style" fill="currentColor" viewBox="0 0 600 1100" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
								<path d="m592.44452,558.58248q0,13 -10,23l-466,466q-10,10 -23,10t-23,-10l-50,-50q-10,-10 -10,-23t10,-23l393,-393l-393,-393q-10,-10 -10,-23t10,-23l50,-50q10,-10 23,-10t23,10l466,466q10,10 10,23z"></path>
							</svg>
                    	</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 fade-in-panel">
                <div class="panel panel-default">
					<div class="panel-heading">
						<a href="#">
							<img src="<?php echo base_url('attachments/shop_images/arrival.jpg'); ?>" class="img-responsive" alt="Card Image">
						</a>
					</div>
                    <div class="panel-body">
						<p class="panel-text">
							<?=lang_safe('home_panel_text')?>
						</p>
						<a href="#" class="panel-button">
							<span class="button-text"><?=lang_safe('home_panel_button')?></span>
							<svg class="svg-custom-style" fill="currentColor" viewBox="0 0 600 1100" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
								<path d="m592.44452,558.58248q0,13 -10,23l-466,466q-10,10 -23,10t-23,-10l-50,-50q-10,-10 -10,-23t10,-23l393,-393l-393,-393q-10,-10 -10,-23t10,-23l50,-50q10,-10 23,-10t23,10l466,466q10,10 10,23z"></path>
							</svg>
                    	</a>
				    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 fade-in-panel">
                <div class="panel panel-default">
					<div class="panel-heading">
						<a href="#">
							<img src="<?php echo base_url('attachments/shop_images/arrival.jpg'); ?>" class="img-responsive" alt="Card Image">
						</a>
					</div>
                    <div class="panel-body">
					<p class="panel-text">
							<?=lang_safe('home_panel_text')?>
						</p>							
						<a href="#" class="panel-button">
							<span class="button-text"><?=lang_safe('home_panel_button')?></span>
							<svg class="svg-custom-style" fill="currentColor" viewBox="0 0 600 1100" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
								<path d="m592.44452,558.58248q0,13 -10,23l-466,466q-10,10 -23,10t-23,-10l-50,-50q-10,-10 -10,-23t10,-23l393,-393l-393,-393q-10,-10 -10,-23t10,-23l50,-50q10,-10 23,-10t23,10l466,466q10,10 10,23z"></path>
							</svg>
                    	</a>
					</div>
                </div>
            </div>
        </div>
    </div>
	<div class="row quote-partner">
		<div class="row row-flex quote col-lg-6 col-md-6">
			<div class="col-xs-10 col-sm-9">
				<h3 class="quote-title"><?=lang_safe('home_quote_title')?></h3>
				<p class="quote-head"><strong><?=lang_safe('home_quote_head')?></strong></p>
				<p class="quote-text"><?=lang_safe('home_quote_text')?></p>
			</div>
			<div class="col-xs-2 col-sm-3">
				<div class="d-flex justify-content-center align-items-end h-100">
					<a href="#">
						<svg class="svg-custom-style" fill="currentColor" viewBox="0 0 600 1100" width="600" height="1100"
							xmlns="http://www.w3.org/2000/svg">
							<path
								d="m592.44452,558.58248q0,13 -10,23l-466,466q-10,10 -23,10t-23,-10l-50,-50q-10,-10 -10,-23t10,-23l393,-393l-393,-393q-10,-10 -10,-23t10,-23l50,-50q10,-10 23,-10t23,10l466,466q10,10 10,23z">
							</path>
						</svg>
					</a>
				</div>
			</div>
		</div>
		<div class="col-lg-1 col-md-5 "></div>
		<div class="partner col-lg-5 col-md-5 ">
			<h3 style="text-align:left">
				<?= lang_safe('partner_title') ?>
			</h3>
			<div class="content">
				<div class="description">
					<p>
						<?= lang_safe('partner_description') ?>
					</p>
				</div>
				<div class="icon">
					<i class="fa fa-map-marker"></i>
				</div>
			</div>
			<div class="form-container">
				<form action="javascript:void(0);" onsubmit="redirectToGoogleMaps()">
					<input class="partner-input" type="text" id="plz" name="plz_or_stadt" placeholder="PLZ oder Stadt"
						required>
					<button type="submit" class="submit-button">Suchen
						<span style="vertical-align: middle;font-size:35px;">
							<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"
								xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
								<g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									<g id="Desktop-HD" transform="translate(0.000000, -384.000000)">
										<g id="arrow_right" transform="translate(1.000000, 384.000000)" fill="#000000"
											fill-rule="nonzero">
											<path
												d="M10.1231887,0.710064646 L21.2720179,12.0187229 L10.1231887,23.3273812 L8.69894628,21.9232681 L17.4660248,13.0290646 L0.752024771,13.03 L0.752024771,11.03 L17.4860248,11.0290646 L8.69894628,2.11417775 L10.1231887,0.710064646 Z"
												id="Combined-Shape"></path>
										</g>
										<g id="slices"></g>
									</g>
								</g>
							</svg>
						</span>
					</button>
				</form>
			</div>
		</div>
	</div>
</div>