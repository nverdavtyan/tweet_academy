<?php 
 
  
    include 'core/init.php' ;
  
    if (isset($_SESSION['user_id'])) {
      header('location: home.php');
    }
   
?>
 <!DOCTYPE html>
<html>
	<head>
		<title>Twitter</title>
		<meta charset="UTF-8" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css"/>

        <link rel="stylesheet" href="assets/css/index.css">
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/all.min.css">

		<link rel="shortcut icon" type="image/png" href="assets/images/twitter.svg"> 
	</head>
<body>
<main class="twt-main">
            <section class="twt-login">
                <?php  include 'includes/login.php';  ?>
                    <div class="slow-login">
                        <img class="login-bird" src="https://image.ibb.co/d0hbJ9/twt_icon.png" alt="bird">
                        <button class="login-small-display signin-btn pri-btn">Log in</button>
                        <span class="front-para">See what’s happening in the world right now</span>
                        <span class="join">Join Twitter today.</span>
                        <button type="button" id="auto" onclick="" class="signup-btn pri-btn" data-toggle="modal" data-target="#exampleModalCenter">
                            Sign Up</button>
                            
                             
                            <!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 style="font-weight: 700;" class="modal-title" id="exampleModalLongTitle">Sign Up</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
                    
         <?php  include 'includes/signup-form.php' ?>
      </div>
      
    </div>
  </div>
</div>
                    </div>
            </section>
            <section class="twt-features">
                <div class="features-div">
                    <img class="twt-icon" src='./assets/images/twitter.svg'>

                </div>
            </section>
            <footer>
                <ul>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Status</a></li>
                    <li><a href="#">Jobs</a></li>
                    <li><a href="#">Terms</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Cookies</a></li>
                    <li><a href="#">Ads info</a></li>
                    <li><a href="#">Brand</a></li>
                    <li><a href="#">Apps</a></li>
                    <li><a href="#">Advertise</a></li>
                    <li><a href="#">Marketing</a></li>
                    <li><a href="#">Businesses</a></li>
                    <li><a href="#">Developers</a></li>
                    <li><a href="#">Directory</a></li>
                    <li><a href="#">Settings</a></li>
                    <li>© 2022 Tweet Academy</li>
                </ul>
            </footer>
        </main>
        
        <script src="assets/js/jquery-3.4.1.slim.min.js"></script>
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
 
</body>
</html>
