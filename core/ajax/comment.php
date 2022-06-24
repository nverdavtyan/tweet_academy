<?php 
	include '../init.php';
	$user_id = $_SESSION['user_id'];
	// Comment place
	if(isset($_POST['qoute']) && !empty($_POST['qoute'])){
		$tweet_id  = $_POST['qoute'];
		$get_id    = $_POST['user_id'];
		// $flag = $_POST['isQoute'];
		// $qoq = $_POST['qoq'];
		$comment   = User::checkInput($_POST['comment']);
    
		$retweet = Tweet::getRetweet($tweet_id);
		

        //  if(!$flag_retweeted) {
			

			$data = [
				'user_id' => $_SESSION['user_id'] , 
                'tweet_id' => $tweet_id , 
                'comment' => $comment , 
				'date_comment' => date("Y-m-d H:i:s") ,
			];
		    if ($comment != '') {
				$for_user = Tweet::getData($tweet_id)->user_id;
		
					if($for_user != $user_id) {
						$data_notify = [
						'exped_id' => $for_user ,
						'dest_id' => $user_id ,
						'target' => $tweet_id , 
						'type' => 'comment' ,
						'time' => date("Y-m-d H:i:s") ,
						'count' => '0' , 
						'status' => '0'
						];
				
						Tweet::create('notification' , $data_notify);
						
					} 

		     User::create('comment' , $data);


			}
	}

	if(isset($_POST['reply']) && !empty($_POST['reply'])){
		$tweet_id  = $_POST['reply'];
		$get_id    = $_POST['user_id'];
	
		$comment   = User::checkInput($_POST['comment']);

			date_default_timezone_set("Europe/Paris");
          
		
			$data = [
				'user_id' => $_SESSION['user_id'] , 
                'tweet_id' => $tweet_id , 
                'content' => $comment , 
				'time' => date("Y-m-d H:i:s") ,
			];
		    if ($comment != '') { 
				// notification
				$for_user = Tweet::getComment($tweet_id)->user_id;
				$target = Tweet::getComment($tweet_id)->tweet_id;
		
				if($for_user != $user_id) {
					$data_notify = [
					'exped_id' => $for_user ,
					'dest_id' => $user_id ,
					'target' => $target , 
					'type' => 'reply' ,
					'time' => date("Y-m-d H:i:s") ,
					'count' => '0' , 
					'status' => '0'
					];
			
					Tweet::create('notification' , $data_notify);
					
				} 
                //  end
				
		     User::create('reply' , $data);
			}
	}
        // Comment on Post popup
	if(isset($_POST['showPopup']) && !empty($_POST['showPopup'])){
		$tweet_id   = $_POST['showPopup'];
		$user       = User::getData($user_id);
		$retweet_comment = false;
		$qoq = false;
		if (Tweet::isRetweet($tweet_id)) {
		$retweet =Tweet::getRetweet($tweet_id);
		if ($retweet->tweet_id == null) {

				// when the retweetd tweet is normal tweet
				
			if ($retweet->message!= null) {
				
				// when qoute 

                $user_tweet = User::getData($retweet->user_id) ;
				 $timeAgo = Tweet::getTimeAgo($retweet->reply_date) ; 
				 $qoute = $retweet->message;
                 $retweet_comment = true;
           

              $tweet_inner = Tweet::getTweet($retweet->tweet_id);
              $user_inner_tweet = User::getData($tweet_inner->user_id) ;
              $timeAgo_inner = Tweet::getTimeAgo($tweet_inner->reply_date); 


			} else {
				// when normal retweet

				$tweet      = Tweet::getTweet($retweet->tweet_id);
		    	$user_tweet = User::getData($tweet->user_id);
		    	$timeAgo = Tweet::getTimeAgo($tweet->reply_date) ; 
			}
		} else {
			// if tweet_id = null and retweeted_id not null then it's retweet od qoute
			// so we have to get the retweeted tweet first

			// here condtion of retweeted a qouted tweet
		
			if ($retweet->message == null) {
				
				$retweeted_tweet = Tweet::getRetweet($retweet->tweet_id);

				if($retweeted_tweet->tweet_id != null) {
						$user_tweet = User::getData($retweeted_tweet->user_id) ;
						$timeAgo = Tweet::getTimeAgo($retweeted_tweet->reply_date) ; 

						$retweet_inner = Tweet::getRetweet($retweet->tweet_id);

						$qoute = $retweet_inner->message;
						$retweet_comment = true;
				

					
					$tweet_inner = Tweet::getTweet($retweet_inner->tweet_id);
					$user_inner_tweet = User::getData($tweet_inner->user_id) ;
					$timeAgo_inner = Tweet::getTimeAgo($tweet_inner->reply_date); 

				} else {
					// hereeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee

					     $user_tweet = User::getData($retweeted_tweet->user_id) ;
						$timeAgo = Tweet::getTimeAgo($retweeted_tweet->reply_date) ; 

						$retweet_inner = Tweet::getRetweet($retweet->tweet_id);

						$qoute = $retweet_inner->retweet_msg;
						$retweet_comment = true;
				        $qoq = true;

					
					$tweet_inner = Tweet::getRetweet($retweeted_tweet->tweet_id);
					// $tweet_inner = Tweet::getRetweet($tweet_inner->retweet_id);
					$user_inner_tweet = User::getData($tweet_inner->user_id) ;
					$timeAgo_inner = Tweet::getTimeAgo($tweet_inner->reply_date); 
                    $inner_qoute = $tweet_inner->message;

				}
			} else {

				// here must handle the qoute of qoute display

				$user_tweet = User::getData($retweet->user_id) ;
				$timeAgo = Tweet::getTimeAgo($retweet->reply_date) ; 
				// $likes_count = Tweet::countLikes($tweet->id) ;
				// $user_like_it = Tweet::userLikeIt($user_id ,$tweet->id);
				// $retweets_count = Tweet::countRetweets($tweet->id) ;
				// $user_retweeted_it = Tweet::userRetweeetedIt($user_id ,$tweet->id);
				$qoute = $retweet->message;
				$qoq = true; // stand for qoute of qoute
				
				$tweet_inner = Tweet::getRetweet($retweet->tweet_id);
				$user_inner_tweet = User::getData($tweet_inner->user_id) ;
				$timeAgo_inner = Tweet::getTimeAgo($tweet_inner->reply_date);
				$inner_qoute = $tweet_inner->message;
			}
			
		}	

	} else {

		 // when normal tweet

		$tweet      = Tweet::getTweet($tweet_id);
		$user_tweet = User::getData($tweet->user_id);
		$timeAgo = Tweet::getTimeAgo($tweet->reply_date) ;
		

	}
	
?>
<div class="retweet-popup">
<div class="wrap5">
	<div class="retweet-popup-body-wrap">
		<div class="retweet-popup-heading">
			<h3>Reply Tweet</h3>
			<span><button class="close-retweet-popup"><i class="fa fa-times" aria-hidden="true"></i></button></span>
		</div>
		<div class="retweet-popup-input">
			<div class="retweet-popup-input-inner">
				<input  class="retweet-msg" type="text" placeholder="Add Comment.."/>
			</div>
		</div>
		
				
		<div class="grid-tweet py-2">
              <div>
                <img
                  src="assets/images/users/<?php echo $user_tweet->avatar; ?>"
                  alt=""
                  class="img-user-tweet"
                />
              </div>
  
              <div>
                <p>
                  <strong> <?php echo $user_tweet->name ?> </strong>
                  <span class="username-twitter">@<?php echo $user_tweet->username ?> </span>
                  <span class="username-twitter"><?php echo $timeAgo ?></span>
                </p>
                <p>
				<?php
                  // check if it's qoute or normal tweet
                  if ($retweet_comment || $qoq)
                  echo  Tweet::getTweetLinks($qoute);
                  else echo  Tweet::getTweetLinks($tweet->content); ?>
				</p>
				
				<?php if ($retweet_comment == false && $qoq == false) { ?>
                <?php if ($tweet->media != null) { ?>
                <p class="mt-post-tweet">
                  <img
                    src="assets/images/tweets/<?php echo $tweet->media; ?>"
                    alt=""
                    class="img-post-retweet"
                  />
                </p>
			   <?php } ?>
			   <?php }  else { ?>

				<div  class="mt-post-tweet comment-post">

				<div class="grid-tweet py-3  ">
				<div>
				<img
				src="assets/images/users/<?php echo $user_inner_tweet->avatar; ?>"
				alt=""
				class="img-user-tweet"
				/>
				</div>

				<div>
				<p>
				<strong> <?php echo $user_inner_tweet->name ?> </strong>
				<span class="username-twitter">@<?php echo $user_inner_tweet->username ?> </span>
				<span class="username-twitter"><?php echo $timeAgo_inner ?></span>
				</p>
				<p>
				<?php 
				    if ($qoq)
                    echo $inner_qoute;
                    else  echo  Tweet::getTweetLinks($tweet_inner->content); ?>
				</p>
				<?php
				if($qoq == false) {
				if ($tweet_inner->media != null) { ?>
				<p class="mt-post-tweet">
				<img
				src="assets/images/tweets/<?php echo $tweet_inner->media; ?>"
				alt=""
				class="img-post-retweet"
				/>
				</p>
         <?php } } ?>

</div>
</div>
	   

</div>

<?php } ?>
			   

	</div>
</div>


		<div class="retweet-popup-footer"> 
			<div class="retweet-popup-footer-right">
				<button class="comment-it" 
				data-tweet="<?php echo $tweet_id;?>"
				data-user="<?php echo $user_id;?>"
				data-tmp="<?php echo $retweet_comment; ?>" 
				data-qoq="<?php echo $qoq; ?>" 
			 type="submit"><i class="fas fa-pencil-alt" aria-hidden="true"></i>Reply</button>
			</div>
		</div> 
		

</div>

<!-- Post Comment PopUp ends-->

<?php }  

// Repling to comment popup

if(isset($_POST['showReply']) && !empty($_POST['showReply'])){
	$comment_id   = $_POST['showReply'];
	$user       = User::getData($user_id);
	

	$tweet      = Tweet::getComment($comment_id);
	$user_tweet = User::getData($tweet->user_id);
	$timeAgo = Tweet::getTimeAgo($tweet->reply_date) ; 

?>
<div class="retweet-popup">
<div class="wrap5">
<div class="retweet-popup-body-wrap">
	<div class="retweet-popup-heading">
		<h3>Reply Comment</h3>
		<span><button class="close-retweet-popup"><i class="fa fa-times" aria-hidden="true"></i></button></span>
	</div>
	<div class="retweet-popup-input">
		<div class="retweet-popup-input-inner">
			<input  class="retweet-msg" type="text" placeholder="Add Reply.."/>
		</div>
	</div>
	
			
	<div class="grid-tweet py-2">
		  <div>
			<img
			  src="assets/images/users/<?php echo $user_tweet->avatar; ?>"
			  alt=""
			  class="img-user-tweet"
			/>
		  </div>

		  <div>
			<p>
			  <strong> <?php echo $user_tweet->name ?> </strong>
			  <span class="username-twitter">@<?php echo $user_tweet->username ?> </span>
			  <span class="username-twitter"><?php echo $timeAgo ?></span>
			</p>
			<p>
			<?php
			  // check if it's qoute or normal tweet
			   echo  Tweet::getTweetLinks($tweet->comment); ?>
			</p>

</div>
</div>
   




	<div class="retweet-popup-footer"> 
		<div class="retweet-popup-footer-right">
			<button class="reply-it" 
			data-tweet="<?php echo $comment_id;?>"
			data-user="<?php echo $user_id;?>"
		 type="submit"><i class="fas fa-pencil-alt" aria-hidden="true"></i>Reply</button>
		</div>
	</div> 
	

</div>

<!-- Retweet PopUp ends-->
<?php }?>


