<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="google-site-verification" content="NHE1Ng2MUxbGr7SSwc2p4ynZqUj7Z779LE8UXG5o6uE" />
	<title>10 BLOCK</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= base_url('sites/homepage/css/owl.carousel.css') ?>">
    <link rel="stylesheet" href="<?= base_url('sites/homepage/css/owl.theme.default.css') ?>">
    <link href="<?= base_url('sites/homepage/css/app.css') ?>" rel="stylesheet">
	<link rel="icon" type="image/x-icon" href="<?= base_url('sites/homepage/images/favicon.ico') ?>">
	<link rel="shortcut icon" href="<?= base_url('sites/homepage/images/favicon.ico') ?>">

	  <script type="text/javascript">
		  (function(b,r,a,n,c,h,_,s,d,k){if(!b[n]||!b[n]._q){for(;s<_.length;)c(h,_[s++]);d=r.createElement(a);d.async=1;d.src="https://cdn.branch.io/branch-latest.min.js";k=r.getElementsByTagName(a)[0];k.parentNode.insertBefore(d,k);b[n]=h}})(window,document,"script","branch",function(b,r){b[r]=function(){b._q.push([r,arguments])}},{_q:[],_v:1},"addListener applyCode banner closeBanner creditHistory credits data deepview deepviewCta first getCode init link logout redeem referrals removeListener sendSMS setBranchViewData setIdentity track validateCode".split(" "), 0);

		  branch.init('key_live_meLeL0xihPduXBeYFSnNWlfiDvaprV9L');
		  function sendSMS(form) {
			  var phone = form.phone.value;
			  var linkData = {
				  tags: [],
				  channel: 'Website',
				  feature: 'TextMeTheApp',
				  data: {
					  'foo': 'bar'
				  }
			  };
			  var options = {};
			  var callback = function(err, result) {
				  if (err) {
					  alert("Sorry, something went wrong.");
				  } else {
					  form.phone.value = "";
					  alert("Download link sent! Please check your phone.");
				  }
			  };
			  branch.sendSMS(phone, linkData, options, callback);
		  }
	  </script>
	  <!-- Google Analytics -->
	  <script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-136312934-1', 'auto');
		  ga('send', 'pageview');
	  </script>
	  <!-- End Google Analytics -->
  </head>
  <body>
    <div class="app">
      <div class="container">
        <div class="home-page">
          <div class="col-left">
            <a href="" class="logo">
              <img src="<?= base_url('sites/homepage/images/logo.png') ?>" alt="10-block">
            </a>
            <div class="caption">
              <h1>A Better Way To Stream Movies & TV On Mobile</h1>
              <p>Watch in 10-minute blocks when, where, and how you want.</p>
              <form onsubmit="sendSMS(this); return false;">
                <div class="form-group">
                  <input type="tel" name="phone" class="form-control" placeholder="Enter your phone number to get the app." REQUIRED>
                </div>
                <button type="submit" name="submit" class="button">Text Me</button>
              </form>
            </div>
          </div>
          <div class="col-right">
            <img src="<?= base_url('sites/homepage/images/phone-1.png') ?>" alt="10-block">
          </div>
        </div>
      </div>
      <section class="multiple">
        <div class="small-container">
          <div class="heading text-center">
            <h2>Discover What <br class="br-mobile">To Binge Next</h2>
            <p>See what friends, creators and influencers are watching. <br> Tell friends what to watch next.</p>
          </div>
          <div class="multiple-boxes">
            <div class="box first"><img src="<?= base_url('sites/homepage/images/phone-3.png') ?>" alt="10-block"></div>
            <div class="box"><img src="<?= base_url('sites/homepage/images/phone-2.png') ?>" alt="10-block"></div>
            <div class="box last"><img src="<?= base_url('sites/homepage/images/phone-4.png') ?>" alt="10-block"></div>
          </div>
        </div>
      </section>
      <section class="cards-section">
        <div class="fluid-container">
          <div class="heading text-center">
            <h2>Our Curators Select Fascinating Titles</h2>
            <p>Watch for free or subscribe.</p>
          </div>
          <div class="cards transformed">
            <div class="card one"><img src="<?= base_url('sites/homepage/images/card-1.png') ?>" alt="10-block"></div>
            <div class="card two"><img src="<?= base_url('sites/homepage/images/card-2.png') ?>" alt="10-block"></div>
            <div class="card three"><img src="<?= base_url('sites/homepage/images/card-3.png') ?>" alt="10-block"></div>
            <div class="card four"><img src="<?= base_url('sites/homepage/images/card-4.png') ?>" alt="10-block"></div>
            <div class="card five"><img src="<?= base_url('sites/homepage/images/card-5.png') ?>" alt="10-block"></div>
          </div>
          <div class="cards owl-carousel">
            <div class="card"><img src="<?= base_url('sites/homepage/images/card-1.png') ?>" alt="10-block"></div>
            <div class="card"><img src="<?= base_url('sites/homepage/images/card-2.png') ?>" alt="10-block"></div>
            <div class="card"><img src="<?= base_url('sites/homepage/images/card-3.png') ?>" alt="10-block"></div>
            <div class="card"><img src="<?= base_url('sites/homepage/images/card-4.png') ?>" alt="10-block"></div>
            <div class="card"><img src="<?= base_url('sites/homepage/images/card-5.png') ?>" alt="10-block"></div>
          </div>
        </div>
      </section>
      <section class="about-us">
        <div class="heading text-center">
          <h2>Talking About 10 Block</h2>
        </div>
        <div class="boxes">
          <div class="box text-center">
            <p>A totally original approach to snackable content. <br> - Loren, 34, Los Angeles</p>
          </div>
          <div class="box text-center">
            <p>I’d watch commuting on the train, morning and night. <br> - Andrew, 44, New York</p>
          </div>
        </div>
        <div class="boxes is-center">
          <div class="box text-center">
            <p class="featured">I like seeing what my friends and people say in reviews not critics. <br> - Hannah, 36, San Francisco</p>
          </div>
        </div>
        <div class="boxes">
          <div class="box text-center">
            <p>Imagine watching bite size episodes of a film on your phone…and while you’re watching, you could be interacting with fellow viewers. <br> - Jenny, 45, London</p>
          </div>
          <div class="box text-center">
            <p>Almost everything I watch, I watch on my phone. So this is my dream app. <br> - Magda, 29, Boston</p>
          </div>
        </div>
        <div class="boxes evenly">
          <div class="box text-center">
            <p>A cooler feel than Netflix. <br> - Alex, 20, NY</p>
          </div>
          <div class="box text-center">
            <p>Takes what I love about streaming but customizes for mobile watching. <br> - Stephanie, 30, LA</p>
          </div>
        </div>
      </section>
      <section class="clients-section">
        <div class="clients">
          <div class="client text-center">
            <img src="<?= base_url('sites/homepage/images/SXSW-Release.png') ?>" alt="10-block">
            <h3>TOP 10 FINALIST <span>CUTTING-EDGE NEW PRODUCTS 2019</span></h3>
          </div>
          <div class="client text-center">
            <img src="<?= base_url('sites/homepage/images/founder-university-logo.png') ?>" alt="10-block">
            <h3>JASON CALACANIS’ <span>FOUNDER UNIVERSITY 2018</span></h3>
          </div>
          <div class="client text-center">
            <img src="<?= base_url('sites/homepage/images/women-who-tech-logo.png') ?>" alt="10-block">
            <h3>TOP 10 FINALIST <span>STARTUP CHALLENGE 2018</span></h3>
          </div>
        </div>
      </section>
      <footer class="footer text-center">
        <div class="container">
          <a href="" class="logo">
            <img src="<?= base_url('sites/homepage/images/logo.png') ?>" alt="10-block">
          </a>
          <div class="block">
            <h2>Get On The <br class="br-mobile"> Email List For <br> Exclusives And Updates</h2>
            <form action="register" method="POST">
              <div class="form-group"><input type="text" name="full_name" class="form-control" placeholder="Name" required></div>
              <div class="form-group"><input type="email" name="email" class="form-control" placeholder="Email address" required></div>
              <div class="form-group">
                <button type="submit" name="sign-up" class="button">Sign Up</button>
              </div>
            </form>
            <ul class="social-media">
              <li><a href="https://twitter.com/get10block/"><i class="fa fa-twitter"></i></a></li>
              <li><a href="https://www.instagram.com/get10block/"><i class="fa fa-instagram"></i></a></li>
            </ul>
            <ul class="links">
              <li><a href="support">Contact Us</a></li>
              <li><a href="privacypolicy">Privacy Policy</a></li>
            </ul>
            <div class="copyright">
              <p>&copy; 2019 10 BLOCK. All rights reserved.</p>
            </div>
          </div>
        </div>
      </footer>
    </div>
    <script src="<?= base_url('sites/homepage/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('sites/homepage/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('sites/homepage/js/owl.carousel.min.js') ?>"></script>
    <script src="<?= base_url('sites/homepage/js/app.js') ?>"></script>
    <script>
      $(document).ready(function() {
        $('.owl-carousel').owlCarousel({
          items: 2,
          loop: true,
          margin: 10
        });
      });
    </script>
  </body>
</html>