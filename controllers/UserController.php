<?php

namespace app\controllers;

use flow\Flow;

/**
 * Controller pre pracu s userom
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.market/
 * @version    1.0.0
 */
class UserController extends \flow\AbstractController{
    
    public function accessRules(){
        return [
            'logged_in' => ['test'],
            'admin' => ['test', 'delete'],
        ];
    }

    /**
     * Spracovanie prihlasovacieho formulára a presmerovanie na pôvodnú stránku
     */
    public function login() {

        if (isset($_POST['UserModel']['password'])) {
            Flow::app()->auth->postAuthenticate($_POST['UserModel']['email'], $_POST['UserModel']['password']);
        }
        if (Flow::app()->auth->isLoggedIn()) { //prihlaseny
            header('Location:' . ENTRY_SCRIPT_URL . 'petrinet/filter', TRUE, 301);
        }
        else {
            //zobrazit prihlacovaci formular znova
            $this->render('login');
        }
    }
}
