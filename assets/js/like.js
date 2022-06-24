$(function(){
	$(document).on('click','.like-btn', function(){
 		var tweet_id  = $(this).data('tweet');
		var user_id   = $(this).data('user');
		var counter   = $(this).find('.likes-count');
		var count     = counter.text();
		var button    = $(this);


         $.ajax({
            type: "POST",
            url: "core/ajax/like.php",
            data: {like:tweet_id, user_id:user_id},
            cache: false,
            success: function(data){

              
               counter.text(data);
               button.addClass('unlike-btn');
               button.removeClass('like-btn');
               
               button.find('.fa-heart').addClass('fas');
               button.find('.fa-heart').removeClass('far');
               button.find('.fa-heart').removeClass('mt-icon-reaction');

              
            }
          })
        
       

          
	});

	$(document).on('click','.unlike-btn', function(){
        var tweet_id  = $(this).data('tweet');
		var user_id   = $(this).data('user');
		var counter   = $(this).find('.likes-count');
		var count     = counter.text();
		var button    = $(this);
 
        $.ajax({
            type: "POST",
            url: "core/ajax/like.php",
            data: {unlike:tweet_id, user_id:user_id},
            cache: false,
            success: function(data){

               if (data == 0)
               counter.text('');
              else  counter.text(data);

               button.addClass('like-btn');
		       button.removeClass('unlike-btn');
               
                button.find('.fa-heart').addClass('far');
                button.find('.fa-heart').addClass('mt-icon-reaction');
                button.find('.fa-heart').removeClass('fas');

              
            }
          })     

	});
});