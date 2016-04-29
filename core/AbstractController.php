<?php

namespace flow;

use Exception;
use const APP_PATH;

/**
 * @todo write description
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.market/
 * @version    1.0.0
 */
abstract class AbstractController {
    
    /**
     * @var array Premenné odoslané do náhľadu
     */
    protected $data = array();
    
    /**
     * @var String Podzložka v ktorej sú uložené náhľady asociované s týmto ovládačom
     */
    protected $viewfolder;
    
    /**
     * @var String názov súboru bez prípony, ktorý predstavuje náhľad
     */
    protected $viewfile = '';
    
    /**
     * @var String Súbor, ktorý sa použíje ako vonkajší kontajner pre zobrazený náhľad 
     */
    protected $template = 'main.template';
    
    /**
     * Konštruktor triedy priradí do premennej $viewfolder názov podzložky nahľadu
     * podľa názvu triedy
     */
    public function __construct() {
        $folder_exp = preg_split( '/(?=[A-Z])/', get_class($this) );
        array_shift($folder_exp);
        $this->viewfolder = strtolower( array_shift($folder_exp) );
    }
    
    /**
     * Vykreslí obsah stránky aj s vonkajšou časťou - main.template.php
     * @param type $viewfile
     * @param type $variables
     * @param type $subfolder
     * @throws Exception
     */
     public function render($viewfile, $variables = [], $subfolder = '') {
        
        ob_start();
        $this->renderPartial($viewfile, $variables, $subfolder);
        $content = ob_get_clean();

        //Pripraví vonkajšiu časť
        if(file_exists(APP_PATH . '/views/' . $this->template . '.php')){
            include APP_PATH . '/views/' . $this->template . '.php';
        }
    }
    
    /**
     * Vykreslí obsah stránky bez vonkajšej časti - main.template.php
     * @param type $viewfile
     * @param type $variables
     * @param type $subfolder
     * @throws Exception
     */
    public function renderPartial($viewfile, $variables = [], $subfolder = ''){
        
         if(empty($subfolder)) {
            $subfolder = $this->viewfolder;
        }
        
        $this->viewfile = APP_PATH . 'views/' . trim($subfolder,'/') . '/' . $viewfile . '.php';
        
        if(!file_exists($this->viewfile)){
            throw new Exception( "View file {$viewfile} was not found." );
        }
        
        //Uloží prijaté dáta do premennej a použije ich v náhľade
        $this->data = $data = (object) $variables;

        include($this->viewfile);
    }
    
    /**
     * Metóda skontroluje prístupové práva a ak používateľ spĺňa úpžadovanú úroveň práv
     * spustí privatnú funkciu, volanú zvonku
     * 
     * @param String $method Názov volanej metódy
     * @param mixed $arguments Argumenty
     */
    public function __call( $method, $arguments ) {
        
        $acRules = $this->accessRules();
        $permission = false;
        foreach ( $acRules as $role => $actions ){
            
            if( $role == Flow::app()->auth->role
                    && array_search( $method, $actions ) !== FALSE ) {
                $permission = TRUE;
            }
            else if( $role == 'logged_in' && Flow::app()->auth->isLoggedIn()
                    && array_search( $method, $actions ) !== FALSE ){

                $permission = TRUE;
            }
        }
        if( $permission ){
            call_user_func_array( array( $this, $method ), $arguments );
        }
        else{
            time_nanosleep( 2, 0 );
            /** @todo Temporary solution because of infinite script execute after following call*/
            //Flow::app()->getObject('urlresolver')->replaceByOther('admin/index/1');
            header('Location: ' . ENTRY_SCRIPT_URL . 'user/login/');
        }
    }
}
