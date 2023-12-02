<?php
namespace src\handlers;

use \src\models\User;


class LoginHandler  {
     
      
   
    public  static function checkLogin() {

       if(!empty($_SESSION['token'])) {
           $token = $_SESSION['token'];

           $data = User::select()->where('token', $token)->one();

           if(!empty($data) > 0) {

             $loggedUser = new User();
             $loggedUser->Id = $data['id'];
             $loggedUser->Email = $data['email'];
             $loggedUser->Name = $data['name'];

               return $loggedUser;
             }

        }
        
            return false;
        }
        // Verifica Login e faz Update.
    public static function verifyLogin($email, $password){
        $user = User::select()->where('email', $email)->one();

        if(password_verify($password, $user['password'])) {
          $token = md5(time().rand(0,9999).time());

          User::update()
          ->set('token',$token)
          ->where('email', $email)
        ->execute();

          return $token;

     }

      return false;

    }  

    public static function emailExists($email) {

      $user = User::select()->where('email', $email)->one();
       return $user ? true : false;

    }
     // Adiciona usuário e Data Real.
    public  function addUser( $name, $email, $password, $birthdate) {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $token = md5(time().rand(0,9999).time());



      User::insert([
        'email' => $email,
        'password' => $hash,
        'name'=> $name,
        'birthdate'=> $birthdate,
        'token'=> $token
      ])->excute();
      return $token;


    }
            
}




