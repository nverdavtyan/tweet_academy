<?php 

class Tweet extends User {
    
    protected static $pdo;
      
      public static function tweets($user_id) {
        $stmt = self::connect()->prepare("SELECT * from `tweet`
        WHERE user_id = :user_id OR user_id IN (SELECT follow_id from `follow` WHERE follower_id = :user_id)
        ORDER BY tweet_date DESC");
        $stmt->bindParam(":user_id" , $user_id , PDO::PARAM_STR);
        $stmt->execute();
       return $stmt->fetchAll(PDO::FETCH_OBJ);
      }
      public static function tweetsUser($user_id) {
        $stmt = self::connect()->prepare("SELECT * from `tweet`
        WHERE user_id = :user_id
        ORDER BY tweet_date DESC");
        $stmt->bindParam(":user_id" , $user_id , PDO::PARAM_STR);
        $stmt->execute();
       return $stmt->fetchAll(PDO::FETCH_OBJ);
      }
      public static function likedTweets($user_id) {
        $stmt = self::connect()->prepare("SELECT * from `tweet`
        WHERE id IN (SELECT tweet_id from `like` WHERE user_id = :user_id)
        ORDER BY tweet_date DESC");
        $stmt->bindParam(":user_id" , $user_id , PDO::PARAM_STR);
        $stmt->execute();
       return $stmt->fetchAll(PDO::FETCH_OBJ);
      }
      public static function mediaTweets($user_id) {
        $stmt = self::connect()->prepare("SELECT * from `tweet`
        WHERE id IN (SELECT id from `tweet` WHERE user_id = :user_id AND media is not null)
        ORDER BY tweet_date DESC");
        $stmt->bindParam(":user_id" , $user_id , PDO::PARAM_STR);
        $stmt->execute();
       return $stmt->fetchAll(PDO::FETCH_OBJ);
      }
      public static function comments($tweet_id) {
        $stmt = self::connect()->prepare("SELECT * from `comment`
        WHERE comment.id = :tweet_id
        ORDER BY date_comment");
        $stmt->bindParam(":tweet_id" , $tweet_id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
      }

      public static function replies($comment_id) {
        $stmt = self::connect()->prepare("SELECT * from `reply`
        WHERE comment_id = :comment_id
        ORDER BY time");
        $stmt->bindParam(":comment_id" , $comment_id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
      }

      public static function isTweet($tweet_id){
            
        $stmt = self::connect()->prepare("SELECT * FROM `tweet` 
        WHERE `id` = :tweet_id");
        $stmt->bindParam(":tweet_id", $tweet_id, PDO::PARAM_INT);
        $stmt->execute(); 

        if ($stmt->rowCount() > 0) {
            return true;
        } else return false;
    }
    public static function isRetweet($tweet_id){
            
        $stmt = self::connect()->prepare("SELECT * FROM `retweet` 
        WHERE `tweet_id` = :tweet_id");
        $stmt->bindParam(":tweet_id", $tweet_id, PDO::PARAM_INT);
        $stmt->execute(); 

        if ($stmt->rowCount() > 0) {
            return true;
        } else return false;

    }

        public static function getTimeAgo($timestamp){
            date_default_timezone_set("Europe/Paris");
               
            $time_ago        = strtotime($timestamp);
            $current_time = strtotime(date("Y-m-d H:i:s")); 
            $time_difference = $current_time - $time_ago;
            $seconds         = $time_difference;
            
            $minutes = round($seconds / 60); // value 60 is seconds  
            $hours   = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec  
            $days    = round($seconds / 86400); //86400 = 24 * 60 * 60;  
            $weeks   = round($seconds / 604800); // 7*24*60*60;  
            $months  = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60  
            $years   = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60
                   
            if ($seconds <= 60){
        
            return "just now";
        
            } else if ($minutes <= 60){
        
            if ($minutes == 1){
        
                return "one minute ago";
        
            } else {
        
                return "$minutes minutes ago";
        
            }
        
            } else if ($hours <= 24){
        
            if ($hours == 1){
        
                return "an hour ago";
        
            } else {
        
                return "$hours hrs ago";
        
            }
        
            } else if ($days <= 7){
        
            if ($days == 1){
        
                return "yesterday";
        
            } else {
        
                return "$days days ago";
        
            }
        
            } else if ($weeks <= 4.3){
        
            if ($weeks == 1){
        
                return "a week ago";
        
            } else {
        
                return "$weeks weeks ago";
        
            }
        
            } else if ($months <= 12){
        
            if ($months == 1){
        
                return "a month ago";
        
            } else {
        
                return "$months months ago";
        
            }
        
            } else {
            
            if ($years == 1){
        
                return "one year ago";
        
            } else {
        
                return "$years years ago";
        
            }
            }
        }
        


        public static function getTrendByHash($hashtag){
            $stmt = self::connect()->prepare("SELECT * FROM `hashtag` 
            WHERE `hashtag` LIKE :hashtag LIMIT 5");
            $stmt->bindValue(":hashtag", $hashtag.'%');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
    
        public static function getMention($mension){
            $stmt = self::connect()->prepare("SELECT `id`,`username`,`name`,`avatar` FROM `user` 
            WHERE `username` LIKE :mention OR `name` LIKE :mention LIMIT 5");
            $stmt->bindValue("mention", $mension.'%');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
    
        }
        public static function HashtagExist($hash){
            
            $stmt = self::connect()->prepare("SELECT * FROM `hashtag` 
            WHERE `hashtag` = '$hash' ");
           //  $stmt->bindParam(":hashtag", $hash, PDO::PARAM_INT);
            $stmt->execute(); 
    
            if ($stmt->rowCount() > 0) {
                return true;
            } else return false;
    
        }

        public static function addTrend($hashtag){
            preg_match_all("/#+([a-zA-Z0-9_]+)/i", $hashtag, $matches);
            if($matches){
                $result = array_values($matches[1]);
            }
            $sql = "INSERT INTO `hashtag` (`hashtag`, `date_hashtag`) VALUES (:hashtag, CURRENT_TIMESTAMP)";
            foreach ($result as $trend) { 
                 if (!Tweet::HashtagExist($trend)) {
                     
                     if($stmt = self::connect()->prepare($sql)){
                         $stmt->execute(array(':hashtag' => $trend));
                     }
                 }
            }
        } 

       
        
        public static function getTweetLinks($tweet){
            $tweet = preg_replace("/(https?:\/\/)([\w]+.)([\w\.]+)/", "<a href='$0' target='_blink'>$0</a>", $tweet);
            $tweet = preg_replace("/#([\w]+)/", "<a class='hash-tweet' href='#'>$0</a>", $tweet);		
            $tweet = preg_replace("/@([\w]+)/", "<a class='hash-tweet' href=' ".BASE_URL."$1'>$0</a>", $tweet);
            return $tweet;		
        }
        public static function hashtagAndMentionTweet($tweet){
            $tweet = preg_replace("/(https?:\/\/)([\w]+.)([\w\.]+)/", "<a href='$0' target='_blink'>$0</a>", $tweet);
            $tweet = preg_replace("/#([\w]+)/", "<a class='hash-tweet' href='#'>$0</a>", $tweet);		
            $tweet = preg_replace("/@([\w]+)/", "<a class='hash-tweet' href='#'>$0</a>", $tweet);
            return $tweet;		
        }
        
        public static function countLikes($post_id) {
            $stmt = self::connect()->prepare("SELECT COUNT(tweet_id) as count FROM `like`
            WHERE tweet_id = :tweet_id");
            $stmt->bindParam(":tweet_id" , $post_id , PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->fetch(PDO::FETCH_OBJ);
            return $count->count;
        }
        public static function countTweets($user_id) {
            $stmt = self::connect()->prepare("SELECT COUNT(user_id) as count FROM `tweet`
            WHERE user_id = :user_id");
            $stmt->bindParam(":user_id" , $user_id , PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->fetch(PDO::FETCH_OBJ);
            return $count->count;
        }

        public static function countComments($post_id) {
            $stmt = self::connect()->prepare("SELECT COUNT(tweet_id) as count FROM `comment`
            WHERE tweet_id = :tweet_id");
            $stmt->bindParam(":tweet_id" , $post_id , PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->fetch(PDO::FETCH_OBJ);
            return $count->count;
        }
        public static function countReplies($comment_id) {
            $stmt = self::connect()->prepare("SELECT COUNT(comment_id) as count FROM `reply`
            WHERE comment_id = :comment_id");
            $stmt->bindParam(":comment_id" , $comment_id , PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->fetch(PDO::FETCH_OBJ);
            return $count->count;
        }

        public static function countRetweets($tweet_id) {
            $stmt = self::connect()->prepare("SELECT COUNT(*) as count FROM `retweet`
            WHERE `tweet_id` = :tweet_id  and message is null ");
            $stmt->bindParam(":tweet_id" , $tweet_id , PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $count = $stmt->fetch(PDO::FETCH_OBJ);
                return $count->count;
            } else return false;
            
        }

        public static function unLike($user_id, $tweet_id){
            
            $stmt = self::connect()->prepare("DELETE FROM `like` 
            WHERE `user_id` = :user_id and `tweet_id` = :tweet_id");
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $stmt->bindParam(":tweet_id", $tweet_id, PDO::PARAM_INT);
            $stmt->execute(); 

            if ($stmt->rowCount() > 0) {
                return true;
            } else return false;

        }

        public static function userLikeIt( $user_id ,$tweet_id){
            
            $stmt = self::connect()->prepare("SELECT `tweet_id` , `user_id` FROM `like` 
            WHERE `user_id` = :user_id and `tweet_id` = :tweet_id");
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $stmt->bindParam(":tweet_id", $tweet_id, PDO::PARAM_INT);
            $stmt->execute(); 

            if ($stmt->rowCount() > 0) {
                return true;
            } else return false;

        }
        public static function usersLiked($tweet_id){
            
            $stmt = self::connect()->prepare("SELECT `tweet_id` , `user_id` FROM `like` 
            WHERE  `tweet_id` = :tweet_id");
            $stmt->bindParam(":tweet_id", $tweet_id, PDO::PARAM_INT);
            $stmt->execute(); 

            return $stmt->fetchAll(PDO::FETCH_OBJ);

        }

        public static function userRetweeetedIt($user_id ,$tweet_id){
            
            $stmt = self::connect()->prepare("SELECT `id` , `user_id` FROM `retweet`

            WHERE `user_id` = :user_id and (`tweet_id` = :tweet_id )  and message is NULL");
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $stmt->bindParam(":tweet_id", $tweet_id, PDO::PARAM_INT);
            $stmt->execute(); 

            if ($stmt->rowCount() > 0) {
                return true;
            } else return false;

        } 
        public static function usersRetweeeted($tweet_id){
            
            $stmt = self::connect()->prepare("SELECT `id` , `user_id` FROM `retweet` 
            WHERE `tweet_id` = :tweet_id  and message is NULL");
            $stmt->bindParam(":tweet_id", $tweet_id, PDO::PARAM_INT);
            $stmt->execute(); 

            return $stmt->fetchAll(PDO::FETCH_OBJ);

        }
        public static function checkRetweet($user_id ,$tweet_id){
            
            $stmt = self::connect()->prepare("SELECT `id` , `user_id` FROM `retweet`
            WHERE `user_id` = :user_id and `tweet_id` = :tweet_id  and message is NULL");
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $stmt->bindParam(":tweet_id", $tweet_id, PDO::PARAM_INT);
            $stmt->execute(); 

            if ($stmt->rowCount() > 0) {
                return true;
            } else return false;

        }


        

        public static function undoRetweet($user_id , $tweet_id) {
            
            $stmt = self::connect()->prepare("DELETE FROM `retweet` 
            WHERE `user_id` = :user_id and `tweet_id` = :tweet_id
            ");
            // and id not in (SELECT post_id from `retweets` WHERE retweet_msg is not null)
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $stmt->bindParam(":tweet_id", $tweet_id, PDO::PARAM_INT);
            $stmt->execute(); 

            if ($stmt->rowCount() > 0) {
                return true;
            } else return false;
        }

        public static function retweetRealId($tweet_id , $user_id) {
            $stmt = self::connect()->prepare("SELECT tweet_id FROM retweet 
            WHERE (tweet_id = :tweet_id) and `user_id` = :user_id");
            $stmt->bindParam(":tweet_id" , $tweet_id , PDO::PARAM_STR);
            $stmt->bindParam(":user_id" , $user_id , PDO::PARAM_STR);
            $stmt->execute();
            $id = $stmt->fetch(PDO::FETCH_OBJ);
            return $id->tweet_id;
        }

       
        
        
        public static function likedTweetRealId($tweet_id) {
            $stmt = self::connect()->prepare("SELECT tweet_id FROM retweet
            WHERE tweet_id = :tweet_id");
            $stmt->bindParam(":tweet_id" , $tweet_id , PDO::PARAM_STR);
            $stmt->execute();
            $id = $stmt->fetch(PDO::FETCH_OBJ);
            return $id->tweet_id;
        }


        public static function getTweet($tweet_id){
            $stmt = self::connect()->prepare("SELECT * FROM `tweet` 
            WHERE `id` = :tweet_id");
            $stmt->bindParam(":tweet_id", $tweet_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
        
        public static function getComment($tweet_id){
            $stmt = self::connect()->prepare("SELECT * FROM `comment` 
            WHERE `id` = :tweet_id");
            $stmt->bindParam(":tweet_id", $tweet_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
        public static function getRetweet($tweet_id){
            $stmt = self::connect()->prepare("SELECT * FROM `retweet` ");
            $stmt->bindParam(":tweet_id", $tweet_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } 

        public static function getData($id) {
            $stmt = self::connect()->prepare("SELECT * from `tweet` WHERE `id` = :id ");
            $stmt->bindParam(":id" , $id , PDO::PARAM_STR);
            $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
     }  

     public static function includeHeader($title) {
        global $tweets;
        $tweets = $title;
        include 'includes/tweets.php';
    }


        
}