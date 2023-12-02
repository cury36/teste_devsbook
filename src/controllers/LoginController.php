<?php
namespace src\controllers;

use \core\Controller;
//use \core\Controller;
use \src\handlers\LoginHandler;

class LoginController extends Controller {
  public function signin() {

  $flash = '';
  if(!empty($_SESSION['flash'])) {
     $flash = $_SESSION['flash'];
     $_SESSION['flash'] = '';
  }
 $this->render('signin', [
     'flash' => $flash
 ]);

}

public function signinAction() {

 $email = filter_input(INPUT_POST, 'email',FILTER_VALIDATE_EMAIL);
 $password = filter_input(INPUT_POST,'passowrd');

 if($email && $password) {

     $token = LoginHandler::verifyLogin($email ,$password);
     if($token) {
           $_SESSION['token'] = $token;
           $this->redirect('/');

   
      }else{
         //$_SESSION['flash'] = 'E-mail e/ou senha inválidos';
          $this->redirect('/login');
          
        }
     }else {
     $_SESSION['flash'] = 'E-mail e/ou senha inválidos';
     $this->redirect('/login');
   }

}
    public function signup() {

      $flash = '';
  if(!empty($_SESSION['flash'])) {
     $flash = $_SESSION['flash'];
     $_SESSION['flash'] = '';
  }
 $this->render('signup', [
     'flash' => $flash
 ]);


}

    public function signupAction()  {
      
      $name = filter_input(INPUT_POST, 'name');
      $email = filter_input(INPUT_POST, 'email',FILTER_VALIDATE_EMAIL);
      $password = filter_input(INPUT_POST,'passowrd');
      $birthdate = filter_input(INPUT_POST, 'birthdate');

        // Verifica se foi preenchido os tres campo de data
      if($name && $email && $password && $birthdate) {
        $birthdate = explode('/', $birthdate); 
            if(count($birthdate) != 3){
                $_SESSION['flash'] = 'Data de nascimento inválida';
                $this->redirect('/cadastro');

             }
             // verificação se a data é real;
              $birthdate = $birthdate[2]._.$birthdate[1]._.$birthdate[0];

              if(strtotime($birthdate) === false) {
                  $_SESSION['flash'] = 'Data de nascimento inválida';
                  $this->redirect('/cadastro');
            }
              // Verifica se E-mail existe, se não existir Cadastra usuário.
            if(LoginHandler::emailExists($email) === false) {
                 $token = LoginHandler::addUser($name, $email, $password, $birthdate);
                 $_SESSION['token'] = $token;
                 $this->redirect('/');

           }else {
              $_SESSION['flash'] = 'E-mail já cadastrado!';
              $this->redirect('/cadastro');
            } 

          }else{

           $this->redirect('/cadastro');
          }

        } 

     }