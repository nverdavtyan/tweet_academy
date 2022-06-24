<?php
include 'core/init.php';

$user_id = $_SESSION['user_id'];
$user = User::getData($user_id);
$who_users = Follow::whoToFollow($user_id);

// update notification count
User::updateNotifications($user_id);

$notify_count = User::CountNotification($user_id);
$notification = User::notification($user_id);

if (User::checkLogIn() === false)
  header('location: index.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notifications | TweetAcademy</title>
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/all.min.css">
  <link rel="stylesheet" href="assets/css/profile.css?v=<?php echo time(); ?>">

  <link rel="shortcut icon" type="image/png" href="assets/images/twitter.svg">

</head>

<body>

  <script src="assets/js/jquery-3.5.1.min.js"></script>


  <div id="mine">
  <div class="wrapper-left">
        <div class="sidebar-left">
          <div class="grid-sidebar" style="margin-top: 12px">
            <div class="icon-sidebar-align">
            <ion-icon name="logo-twitter"></ion-icon>
            </div>
          </div>

 
          <div class="grid-sidebar" style="margin-top: 12px">
            <div class="icon-sidebar-align">
            <ion-icon name="home-outline"></ion-icon>
            </div>
            <div class="wrapper-left-elements">
              <a  href="home.php" style="margin-top: 4px;">Home</a>
            </div>
          </div>

  
        
          <div class="grid-sidebar bg-active">
            <div class="icon-sidebar-align position-relative">
                <?php if ($notify_count > 0) { ?>
              <i class="notify-count"><?php echo $notify_count; ?></i> 
              <?php } ?>
              <ion-icon name="notifications-circle-outline"></ion-icon>
            </div>
  
            <div class="wrapper-left-elements">
              <a class="bg-active"  href="notification.php" style="margin-top: 4px">Notifications</a>
            </div>
          </div>
      
        

          <div class="grid-sidebar ">
            <div class="icon-sidebar-align">
            <ion-icon name="person-outline"></ion-icon>
            </div>
  
            <div class="wrapper-left-elements">

              <a  href="<?php echo $user->username; ?>"  style="margin-top: 4px">Profile</a>
            
            </div>
          </div>

        
          <div class="grid-sidebar ">
            <div class="icon-sidebar-align">
            <ion-icon name="cog-outline"></ion-icon>
            </div>
  
            <div class="wrapper-left-elements">
              <a href="<?php echo  "account.php"; ?>" style="margin-top: 4px">Settings</a>
            </div>
           
            
          </div>
   
  
          <div class="grid-sidebar">
            <div class="icon-sidebar-align">
            <ion-icon name="log-in-outline"></ion-icon>
            </div>
  
            <div class="wrapper-left-elements">
              <a href="includes/logout.php" style="margin-top: 4px">Logout</a>
            </div>
          </div>
  
          <button class="button-twittear">
            <strong>Tweet</strong>
          </button>
  
          <div class="box-user">
            <div class="grid-user">
              <div>
                <img
                  src="assets/images/users/<?php echo $user->avatar ?>"
                  alt="user"
                  class="img-user"
                />
              </div>
              <div>
                <p class="name"><strong><?php if($user->name !== null) {
                echo $user->name; } ?></strong></p>
                <p class="username">@<?php echo $user->username; ?></p>
              </div>
           
            </div>
          </div>
        </div>
      </div>
          



    <div class="grid-posts">
      <div class="border-right">
        <div class="grid-toolbar-center">
          <div class="center-input-search">

          </div>

        </div>

        <div class="box-fixed" id="box-fixed"></div>

        <div class="box-home feed">
          <div class="container">
            <div style="border-bottom: 1px solid #F5F8FA;" class="row position-fixed box-name">
              <div class="col-xs-2">
                <a href="javascript: history.go(-1);"> <i style="font-size:20px;" class="fas fa-arrow-left arrow-style"></i> </a>
              </div>
              <div class="col-xs-10">
                <p style="margin-top: 12px;" class="home-name"> Notifications</p>
              </div>
            </div>

          </div>
          <div class="container mt-5">

            <?php foreach ($notification as $notify) {
              $user = User::getData($notify->dest_id);
              $timeAgo = Tweet::getTimeAgo($notify->time);
            ?>
              <?php if ($notify->type == 'like') {
                $icon = "<i style='color: red !important;font-size:30px;' class='fa-heart  fas ml-2'></i>";
                $msg = "Liked Your Tweet";
              } else if ($notify->type == 'retweet') {
                $icon = "<i style ='font-size:30px;color: rgb(22, 207, 22)!important;'  class='fas fa-retweet ml-2'></i>";
                $msg = "Retweeted Your Tweet";
              } else if ($notify->type == 'qoute') {
                $icon = "<i style='font-size:30px;color: rgb(22, 207, 22) !important;'  class='fas fa-retweet ml-2'></i>";
                $msg = "Quoted Your Tweet";
              } else if ($notify->type == 'comment') {
                $icon = "<i style='font-size:30px;' class='far fa-comment ml-2'></i>";
                $msg = "Comment to your Tweet";
              } else if ($notify->type == 'reply') {
                $icon = "<i style='font-size:30px;' class='far fa-comment ml-2'></i>";
                $msg = "Reply to your Comment";
              } else if ($notify->type == 'follow') {
                $icon = "<i style='font-size:30px;' class='fas fa-user ml-2'></i>";
                $msg = "Followed You";
              } else if ($notify->type == 'mention') {
                $icon = "<i style='font-size:30px;' class='fas fa-user ml-2'></i>";
                $msg = "Mention you in Tweet";
              } ?>

              <div style="position: relative; border-bottom:4px solid #F5F8FA;" class="box-tweet py-3 ">
                <a href="
                        <?php if ($notify->type == 'follow') {
                          echo $user->username;
                        } else { ?>
                            status/<?php echo $notify->target; ?>
                        <?php } ?>  ">
                  <span style="position:absolute; width:100%; height:100%; top:0;left: 0; z-index: 1;"></span>
                </a>
                <div class="grid-tweet">
                  <div class="icon mt-2">
                    <?php echo $icon; ?>
                  </div>
                  <div class="notify-user">
                    <p>
                      <a style="position: relative; z-index:1000;" href="<?php echo $user->username;  ?>">
                        <img class="img-user" src="assets/images/users/<?php echo $user->avatar ?>" alt="">
                      </a>

                    </p>
                    <p> <a style="font-weight: 700;
                                    font-size:18px;
                                    position: relative; z-index:1000;" href="<?php echo $user->username; ?>">
                        <?php echo $user->name; ?> </a> <?php echo $msg; ?>
                      <span style="font-weight: 500;" class="ml-3">
                        <?php echo $timeAgo; ?>
                      </span>
                    </p>
                  </div>
                </div>
              </div>
            <?php  } ?>
          </div>



        </div>
      </div>

      <div class="wrapper-right">
        <div style="width: 90%;" class="container">

          <div class="input-group py-2 m-auto pr-5 position-relative">

            <i id="icon-search" class="fas fa-search tryy"></i>
            <input type="text" class="form-control search-input" placeholder="Search Twitter">
            <div class="search-result">


            </div>
          </div>
        </div>



        <div class="box-share">
          <p class="txt-share"><strong>Who to follow</strong></p>
          <?php
          foreach ($who_users as $user) {
            //  $u = User::getData($user->user_id);
            $user_follow = Follow::isUserFollow($user_id, $user->id);
          ?>
            <div class="grid-share">
              <a style="position: relative; z-index:5; color:black" href="<?php echo $user->username;  ?>">
                <img src="assets/images/users/<?php echo $user->avatar; ?>" alt="" class="img-share" />
              </a>
              <div>
                <p>
                  <a style="position: relative; z-index:5; color:black" href="<?php echo $user->username;  ?>">
                    <strong><?php echo $user->name; ?></strong>
                  </a>
                </p>
                <p class="username">@<?php echo $user->username; ?>
                  <?php if (Follow::FollowsYou($user->id, $user_id)) { ?>
                    <span class="ml-1 follows-you">Follows You</span>
                </p>
              <?php } ?></p>
              </p>
              </div>
              <div>
                <button class="follow-btn follow-btn-m 
                      <?= $user_follow ? 'following' : 'follow' ?>" data-follow="<?php echo $user->id; ?>" data-user="<?php echo $user_id; ?>" data-profile="<?php echo $user->id; ?>" style="font-weight: 700;">
                  <?php if ($user_follow) { ?>
                    Following
                  <?php } else {  ?>
                    Follow
                  <?php }  ?>
                </button>
              </div>
            </div>

          <?php } ?>


        </div>


      </div>
    </div>
  </div>

  <script src="assets/js/search.js"></script>
  <script src="assets/js/photo.js"></script>
  <script src="assets/js/follow.js"></script>
  <script src="assets/js/users.js"></script>
  <script type="text/javascript" src="assets/js/hashtag.js"></script>
  <script type="text/javascript" src="assets/js/like.js"></script>
  <script type="text/javascript" src="assets/js/comment.js"></script>
  <script type="text/javascript" src="assets/js/retweet.js"></script>
  <script src="https://kit.fontawesome.com/38e12cc51b.js" crossorigin="anonymous"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script src="assets/js/jquery-3.5.1.min.js"></script>
  <script src="assets/js/popper.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
</body>

<style>
  .container {
    padding-left: 55px;
  }
</style>

</html>