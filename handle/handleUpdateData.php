<?php 
     include '../core/init.php';
     require_once '../core/classes/validation/Validator.php';
     require_once '../core/classes/image.php';

     use validation\Validator;

     if (User::checkLogIn() === false) 
     header('location: index.php');   

     $username =  User::getUserNameById($_SESSION['user_id']);

     $user =User::getData($_SESSION['user_id']);

     $currentImg = $user->avatar;
     $currentCover = $user->cover;



    if (isset($_POST['update'])) {

      $name =  User::checkInput($_POST['name']) ;
      $bio =   User::checkInput($_POST['country']);
      $website =  User::checkInput($_POST['website']);
      $location =  User::checkInput($_POST['location']);
      $cover = $_FILES['cover'];
      $image = $_FILES['image'];


      $v = new Validator;
      $v->rules('name' , $name , ['required' , 'string' , 'max:20']);
      $v->rules('country' , $bio , ['string' , 'max:100']);
      $v->rules('image' , $image , ['image']);
      $v->rules('cover' , $cover , ['image']);

      
      $errors = $v->errors;

       if ($errors == []) {
    
      if ($image['name'] !== "") {
      $img = new Image($image); 
       $userImg = $img->new_name ;
      } else $userImg = $user->avatar;

      if ($cover['name'] !== "") {
      $coverImg = new Image($cover); 
      $userCover = $coverImg->new_name;
      } else $userCover = $user->cover;
      


      $data = [
         'name' => $name ,
         'country' => $bio ,
         'website' => $website ,
         'location' => $location ,
         'cover' => $userCover ,
         'avatar' => $userImg , 
      ];
      
      $sign= User::update('user' , $_SESSION['user_id'], $data); 

  

         if ($sign === true) {
             if ($image['name'] !== "") {
                if ($currentImg !== 'default.jpg')
                        unlink('../assets/images/users/' . $currentImg);
                        
                $img->upload(); 
                
            }
             if ($cover['name'] !== "") {
                if ($currentCover !== 'cover.png')
                  unlink('../assets/images/users/' . $currentCover);

                $coverImg->upload(); 
            }

            header('location: ../' . $username);
        }

    } else {

        $_SESSION['errors'] = $errors;
        header('location: ../' . $username);


    }

    } else header('location: ../' . $username);
    
    




















?>