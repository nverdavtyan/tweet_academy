<?php 
	include '../init.php';
	$user_id = $_SESSION['user_id'];

    date_default_timezone_set("Europe/Paris");
	if(isset($_POST['qoute']) && !empty($_POST['qoute'])){
		$tweet_id  = $_POST['qoute'];
		$get_id    = $_POST['user_id'];
		$flag = $_POST['isQoute'];
		$qoq = $_POST['qoq'];
		$comment   = User::checkInput($_POST['comment']);

		$retweet = Tweet::getRetweet($tweet_id);
		

		// for notification
		if(isset($retweet->user_id))
		$for_user = $retweet->user_id;
		else $for_user = Tweet::getTweet($tweet_id)->user_id;	

		if($for_user != $user_id) {
			$data_notify = [
              'exped_id' => $for_user ,
			  'dest_id' => $user_id ,
			  'type' => 'qoute' ,
			   'time' => date("Y-m-d H:i:s") ,
			   'count' => '0' , 
			   'status' => '0'
			];
				
		} 


		// check if user retweeted it to avoid double retweet from quote btn
		if ($flag == false && $qoq == false && Tweet::isRetweet($tweet_id)) {
			$flag_retweeted = Tweet::userRetweeetedIt($user_id,$retweet->tweet_id);
		} else $flag_retweeted = Tweet::userRetweeetedIt($user_id,$tweet_id);
		

        //  if(!$flag_retweeted) {
			date_default_timezone_set("Europe/Paris");

			// $data = [
			// 	'user_id' => $_SESSION['user_id'] ,
			// 	'date_retweet' => date("Y-m-d H:i:s") ,
			// ];
			// create function can handle with all tables and return last inserted id

			//$tweet_id=   User::create('retweet' , $data);

			// qoq is check if this tweet quote of quote or not
			if ($comment != '') {
	
			// if flag true then the retweeted post is quote tweet and the fk is retweet_id
					if ($flag && !$qoq) {
						if(Tweet::isRetweet($tweet_id)) {

							$data_tweet = [
								'user_id' => $_SESSION['user_id'] ,
								'content' => 'ok', 
								'tweet_id' => $tweet_id ,
								'message' => $comment , 
								'date_retweet' => date("Y-m-d H:i:s") 

							];
							// for notification
								if($for_user != $user_id) 
								$data_notify['target']= $tweet_id;
							
						} else {
                            $data_tweet = [
								'user_id' => $_SESSION['user_id'] ,
								'content' => 'ok', 
								'tweet_id' => $tweet_id ,
								'message' => $comment , 
								'date_retweet' => date("Y-m-d H:i:s") 
					
							];
							// for notification
							if($for_user != $user_id) 
							$$data_notify['target']= $tweet_id;
						}
						
					} else if ($qoq) {

							if ($retweet->message == null ) {
							$data_tweet = [
								'user_id' => $_SESSION['user_id'] ,
								'content' => 'ok', 
								'tweet_id' => $tweet_id,
								'message' => $comment , 
								'date_retweet' => date("Y-m-d H:i:s") 
		
							];
							// for notification
							if($for_user != $user_id) 
							$data_notify['target']= $tweet_id;

						}	else {
							$data_tweet = [
								'user_id' => $_SESSION['user_id'] ,
								'content' => 'ok', 
								'tweet_id' => $tweet_id,
								'message' => $comment , 
								'date_retweet' => date("Y-m-d H:i:s") 
						
							];
							// for notification
							if($for_user != $user_id) 
							$data_notify['target']= $tweet_id;
						}
	
	
					} else {
							if(Tweet::isRetweet($tweet_id)) {
								$data_tweet = [
									'user_id' => $_SESSION['user_id'] ,
									'content' => 'ok', 
									'tweet_id' => $tweet_id,
									'message' => $comment , 
									'tweet_id' => $retweet->tweet_id ,
									'date_retweet' => date("Y-m-d H:i:s") ,
								];
								// for notification
							if($for_user != $user_id) 
							$data_notify['target']= $tweet_id;
							} else {
								$data_tweet = [
									'user_id' => $_SESSION['user_id'] ,
									'content' => 'ok', 
									'tweet_id' => $tweet_id,
									'message' => $comment , 
									'tweet_id' => $tweet_id ,
									'date_retweet' => date("Y-m-d H:i:s") ,
								]; 
								// for notification
							    if($for_user != $user_id) 
								$data_notify['target']= $tweet_id;
							}
					}
		} else if ($comment == '') {
	          
			 $data_notify['type'] = 'retweet';

			if ($flag) {
				if(Tweet::isRetweet($tweet_id)) {
					$data_tweet = [
						'tweet_id' => $tweet_id,
						'message' => null , 
						'date_retweet' => date("Y-m-d H:i:s") 
				
					];
					// for notification
				if($for_user != $user_id) 
				$data_notify['target']=$retweet->tweet_id;
				} else {
					$data_tweet = [
						'tweet_id' => $tweet_id,
						'message' => null , 
						'date_retweet' => date("Y-m-d H:i:s") 
			
					];
					// for notification
					if($for_user != $user_id) 
					$data_notify['target']= $tweet_id;
				}
			} else if ($qoq) {

	            if ($retweet->message == null ) {
				$data_tweet = [
					'user_id' => $_SESSION['user_id'] ,
					'content' => 'ok', 
					'tweet_id' => $tweet_id,
					'message' => null , 
					'date_retweet' => date("Y-m-d H:i:s") 
					
				];
				// for notification
				if($for_user != $user_id) 
				$data_notify['target']=$retweet->tweet_id;

			   } else {
				$data_tweet = [
					'user_id' => $_SESSION['user_id'] ,
					'content' => 'ok', 
					'tweet_id' => $tweet_id,
					'message' => null , 
					'date_retweet' => date("Y-m-d H:i:s") 
			
				];
				// for notification
				if($for_user != $user_id) 
				$data_notify['target']= $tweet_id;

			   } 
	
	
			} else {
	
				$data_tweet = [
					'user_id' => $_SESSION['user_id'] ,
					'content' => 'ok', 
					'tweet_id' => $tweet_id,
					'message' => null , 
					'date_retweet' => date("Y-m-d H:i:s") 
				];
				// for notification
				if($for_user != $user_id) 
				$data_notify['target']= $tweet_id;
			}
	
	
	
	
		}
			User::create('retweet' , $data_tweet);

			  // for notification
		if($for_user != $user_id) 
		Tweet::create('notification' , $data_notify);

		//  }
		
		
		// echo `<div class="tmp d-none">
        //      `+ Tweet::countRetweets($tweet_id) +`            
		// </div>` ;

	}
	if(isset($_POST['retweet']) && !empty($_POST['retweet'])){
		$tweet_id  = $_POST['retweet'];
		$get_id    = $_POST['user_id'];
		$flag = $_POST['isQoute'];
		$qoq = $_POST['qoq'];
		$retweet = Tweet::getRetweet($tweet_id);

		// for notification
		if(isset($retweet->user_id))
		$for_user = $retweet->user_id;
		else $for_user = Tweet::getTweet($tweet_id)->user_id;	

		if($for_user != $user_id) {
			$data_notify = [
              'exped_id' => $for_user ,
			  'dest_id' => $user_id ,
			  'type' => 'retweet' ,
			   'time' => date("Y-m-d H:i:s") ,
			   'count' => '0' , 
			   'status' => '0'
			];
				
		} 

		date_default_timezone_set("Europe/Paris");

        // $data = [
        //     'user_id' => $_SESSION['user_id'] ,

        //     'date_retweet' => date("Y-m-d H:i:s") ,
        // ];
        // create function can handle with all tables and return last inserted id
		//$tweet_id=   User::create('posts' , $data);

		 // if flag true then the retweeted post is quote tweet and the fk is retweet_id
		 if ($flag) {
				if(Tweet::isRetweet($tweet_id)) {
				$data_tweet = [
					'user_id' => $_SESSION['user_id'] ,
					'content' => 'ok', 
					'tweet_id' => $tweet_id,
					'message' => null , 
					'date_retweet' => date("Y-m-d H:i:s") 
					
				];
				// for notification
				if($for_user != $user_id) 
				$data_notify['target']= $retweet->tweet_id;

			} else {
				$data_tweet = [
					'user_id' => $_SESSION['user_id'] ,
					'content' => 'ok', 
					'tweet_id' => $tweet_id,
					'message' => null , 
					'date_retweet' => date("Y-m-d H:i:s") ,
				
				];
					// for notification
					if($for_user != $user_id) 
					$data_notify['target']=  $tweet_id;

			}
		} else if ($qoq) {

			if(Tweet::isRetweet($tweet_id) && $retweet->message == null) {
				$data_tweet = [
					'user_id' => $_SESSION['user_id'] ,
					'content' => 'ok', 
					'tweet_id' => $tweet_id,
					'message' => null , 
					'date_retweet' => date("Y-m-d H:i:s") 
			
				];
					// for notification
					if($for_user != $user_id) 
					$data_notify['target']= $retweet->tweet_id;
			} else {
				$data_tweet = [
					'user_id' => $_SESSION['user_id'] ,
					'content' => 'ok', 
					'tweet_id' => $tweet_id,
					'message' => null , 
					'date_retweet' => date("Y-m-d H:i:s") 
				
				];
				// for notification
				if($for_user != $user_id) 
				$data_notify['target']=  $tweet_id;
			}


		} else {
			
			if (Tweet::isRetweet($tweet_id)) {
				   
				$data_tweet = [
					'user_id' => $_SESSION['user_id'] ,
					'content' => 'ok', 
					'tweet_id' => $tweet_id,
					'message' => null , 
					'tweet_id' => $retweet->tweet_id,
					'date_retweet' => date("Y-m-d H:i:s") 
				];
				// for notification
				if($for_user != $user_id) 
				$data_notify['target']= $retweet->tweet_id;

			} else {

				$data_tweet = [
					'user_id' => $_SESSION['user_id'] ,
					'content' => 'ok', 
					'tweet_id' => $tweet_id,
					'message' => null , 
					'tweet_id' => $tweet_id,
					'date_retweet' => date("Y-m-d H:i:s") 
				];
				// for notification
				if($for_user != $user_id) 
				$data_notify['target']= $tweet_id;
			}
		}

		User::create('retweet' , $data_tweet);
		
	    // for notification
		if($for_user != $user_id) 
		    Tweet::create('notification' , $data_notify);
       
		
		echo `<div class="tmp d-none">
             `+ Tweet::countRetweets($tweet_id) +`            
		</div>` ;


	}
	if(isset($_POST['unretweet']) && !empty($_POST['unretweet'])){

        $tweet_id  = $_POST['unretweet'];
		$user_id    = $_POST['user_id'];
        
		$retweet = Tweet::getRetweet($tweet_id);

		// for notification
		if(isset($retweet->tweet_id)) {
		$for_user = Tweet::getTweet($retweet->tweet_id)->user_id; 
		$target = $retweet->tweet_id;
	   } else {
		   $for_user = Tweet::getRetweet($retweet->tweet_id)->user_id;	
	       $target = $retweet->tweet_id;
	   }
	
	
		if($for_user != $user_id) {
			$data = [
              'exped_id' => $for_user ,
			  'dest_id' => $user_id ,
			  'target' => $target , 
			  'type' => "'retweet'" ,
			];
	        
			// var_dump($data);
			// die();

			Tweet::delete('notification' , $data);
			
		} 
		Tweet::undoRetweet($user_id , $tweet_id );
		
		echo `<div class="tmp d-none">
             `+ Tweet::countRetweets($tweet_id) +`            
		</div>` ;

	}
	if(isset($_POST['option']) && !empty($_POST['option'])){ 
		$tweet_id  = $_POST['option'];
		$get_id    = $_POST['user_id'];
		$user_retweeted_it = $_POST['retweeted'];
		$retweet_sign = $_POST['sign'];
		$retweet_comment = $_POST['tmp'];
		$qoq = $_POST['qoq'];
		if(isset($_POST['message']))
		 $status = $_POST['message'];
		 else $status = false;

		$flaga = false;
		if($retweet_sign && $user_retweeted_it) {
		$retweeted_user = Tweet::getRetweet($tweet_id); 
		    	if ($retweeted_user->user_id != $user_id) {
                       $flaga = true;
		     	} 

			    
			        
	    }

	?>

                    <div class="retweet-div">
							<a href="#" 
							class="<?=$user_retweeted_it ? 'retweeted-i' : 'retweet-i' ?>"
							data-user="<?php echo $user_id; ?>"
							data-tweet="<?php 
							if(($user_retweeted_it && !$retweet_sign) || $flaga) {
								if($flaga == false)
							       echo Tweet::retweetRealId($tweet_id ,$user_id);
						     	else {
									if($retweeted_user->tweet_id != null)
										echo Tweet::retweetRealId($retweeted_user->tweet_id ,$user_id);
									else echo Tweet::retweetRealId($retweeted_user->tweet_id ,$user_id);
								 } 
							} else echo $tweet_id;  ?>"
							 data-qoq="<?php echo $qoq; ?>"
							 data-status="<?php echo $status; ?>"
							 >  
								<li ><i class="fas fa-retweet icon"></i> 
								<span class="option-text"><?php if($user_retweeted_it) echo 'Undo';  ?>
								Retweet</span></li>
							</a>
							<a href="#"
							class="qoute"
							data-user="<?php echo $get_id; ?>"
							data-tweet="<?php 
							$retweet = Tweet::getRetweet($tweet_id);
							// if(Tweet::isRetweet($tweet_id) && $retweet->message != null)
							// echo $retweet->tweet_id;
							// else
							 echo $tweet_id; ?>"> 
								 <li><i class="fas fa-pencil-alt icon"></i> 
								 <span class="option-text"> Quote Tweet</span></li>
							</a>
                    </div>

<?php	} 

	if(isset($_POST['showPopup']) && !empty($_POST['showPopup'])){
		$tweet_id   = $_POST['showPopup'];
		$user       = User::getData($user_id);
		$retweet_comment = false;
		$qoq = false;
		if (Tweet::isRetweet($tweet_id)) {
		$retweet =Tweet::getRetweet($tweet_id);
		if ($retweet->tweet_id == null) {

				// when the retweetd tweet is normal tweet
				
			if ($retweet->message != null) {
				
				// when qoute 

                $user_tweet = User::getData($retweet->user_id) ;
				 $timeAgo = Tweet::getTimeAgo($retweet->date_retweet) ; 
				 $qoute = $retweet->message;
                 $retweet_comment = true;
           

              $tweet_inner = Tweet::getTweet($retweet->tweet_id);
              $user_inner_tweet = User::getData($tweet_inner->user_id) ;
              $timeAgo_inner = Tweet::getTimeAgo($tweet_inner->date_retweet); 


			} else {
				// when normal retweet

				$tweet      = Tweet::getTweet($retweet->tweet_id);
		    	$user_tweet = User::getData($tweet->user_id);
		    	$timeAgo = Tweet::getTimeAgo($tweet->date_retweet) ; 
			}
		} else {
			// if tweet_id = null and retweeted_id not null then it's retweet od quote
			// so we have to get the retweeted tweet first

			// here condtion of retweeted a quoted tweet
		
			if ($retweet->message == null) {
				
				$retweeted_tweet = Tweet::getRetweet($retweet->tweet_id);

				if($retweeted_tweet->tweet_id != null) {
						$user_tweet = User::getData($retweeted_tweet->user_id) ;
						$timeAgo = Tweet::getTimeAgo($retweeted_tweet->date_retweet) ; 

						$retweet_inner = Tweet::getRetweet($retweet->tweet_id);

						$qoute = $retweet_inner->message;
						$retweet_comment = true;
				

					
					$tweet_inner = Tweet::getTweet($retweet_inner->tweet_id);
					$user_inner_tweet = User::getData($tweet_inner->user_id) ;
					$timeAgo_inner = Tweet::getTimeAgo($tweet_inner->tweet_date); 

				} else {
					// hereeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee

					     $user_tweet = User::getData($retweeted_tweet->user_id) ;
						$timeAgo = Tweet::getTimeAgo($retweeted_tweet->date_retweet) ; 

						$retweet_inner = Tweet::getRetweet($retweet->tweet_id);

						$qoute = $retweet_inner->message;
						$retweet_comment = true;
				        $qoq = true;

					
					$tweet_inner = Tweet::getRetweet($retweeted_tweet->tweet_id);

					$user_inner_tweet = User::getData($tweet_inner->user_id) ;
					$timeAgo_inner = Tweet::getTimeAgo($tweet_inner->date_retweet); 
                    $inner_qoute = $tweet_inner->message;

				}
			} else {

				// here must handle the quote of quote display

				$user_tweet = User::getData($retweet->user_id) ;
				$timeAgo = Tweet::getTimeAgo($retweet->date_retweet) ; 
				// $likes_count = Tweet::countLikes($tweet->id) ;
				// $user_like_it = Tweet::userLikeIt($user_id ,$tweet->id);
				// $retweets_count = Tweet::countRetweets($tweet->id) ;
				// $user_retweeted_it = Tweet::userRetweeetedIt($user_id ,$tweet->id);
				$qoute = $retweet->message;
				$qoq = true; // stand for quote of quote
				
				$tweet_inner = Tweet::getRetweet($retweet->tweet_id);
				$user_inner_tweet = User::getData($tweet_inner->user_id) ;
				$timeAgo_inner = Tweet::getTimeAgo($tweet_inner->date_retweet);
				$inner_qoute = $tweet_inner->message;
			}
			
		}	

	} else {

		 // when normal tweet

		$tweet      = Tweet::getTweet($tweet_id);
		$user_tweet = User::getData($tweet->user_id);
		$timeAgo = Tweet::getTimeAgo($tweet->tweet_date) ;
		

	}
	
?>
<div class="retweet-popup">
<div class="wrap5">
	<div class="retweet-popup-body-wrap">
		<div class="retweet-popup-heading">
			<h3>Quote Tweet</h3>
			<span><button class="close-retweet-popup"><i class="fa fa-times" aria-hidden="true"></i></button></span>
		</div>
		<div class="retweet-popup-input">
			<div class="retweet-popup-input-inner">
				<input  class="retweet-msg" type="text" placeholder="Add Quote"/>
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
                  // check if it's quote or normal tweet
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
				<button class="qoute-it" 
				data-tweet="<?php echo $tweet_id;?>"
				data-user="<?php echo $user_id;?>"
				data-tmp="<?php echo $retweet_comment; ?>" 
				data-qoq="<?php echo $qoq; ?>" 
			 type="submit"><i class="fas fa-pencil-alt" aria-hidden="true"></i>Quote</button>
			</div>
		</div> 
		

</div>

<!-- Retweet PopUp ends-->
<?php }?>
