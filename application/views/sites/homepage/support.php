<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>10 BLOCK</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="<?= base_url('sites/homepage/css/app.css') ?>" rel="stylesheet">
	  <link rel="icon" type="image/x-icon" href="<?= base_url('sites/homepage/images/favicon.ico') ?>">
	  <link rel="shortcut icon" href="<?= base_url('sites/homepage/images/favicon.ico') ?>">
  </head>
  <body>
    <div class="app">
      <section class="contact-us text-center">
        <div class="container">
          <a href="/" class="logo text-center">
            <img src="<?= base_url('sites/homepage/images/logo.png') ?>" alt="10-block">
          </a>
          <div class="block">
            <h2>Contact Us</h2>
            <form action="" method="POST">
              <div class="form-group"><input type="text" name="name" class="form-control" placeholder="Name" required></div>
              <div class="form-group"><input type="email" name="email" class="form-control" placeholder="Email address" required></div>
              <div class="form-group">
                <textarea name="message" class="form-control" placeholder="Message"></textarea>
              </div>
              <div class="form-group">
                <button type="submit" name="submit" class="button">Submit</button>
              </div>
            </form>
          </div>
          <ul class="social-media">
              <li><a href="https://twitter.com/get10block/"><i class="fa fa-twitter"></i></a></li>
              <li><a href="https://www.instagram.com/get10block/"><i class="fa fa-instagram"></i></a></li>
            </ul>
            <ul class="links text-center">
              <li><a href="privacypolicy">Privacy Policy</a></li>
            </ul>
            <div class="copyright text-center">
              <p>&copy; 2019 10 BLOCK. All rights reserved.</p>
            </div>
        </div>
      </section>
    </div>
    <script src="<?= base_url('sites/homepage/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('sites/homepage/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('sites/homepage/js/app.js') ?>"></script>
  </body>
</html>