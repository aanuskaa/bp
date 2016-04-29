<?php

namespace flow;

/**
 * @todo write description
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.market/
 * @version    1.0.0
 */
class AlertManager {
    
    /**
     *
     * @var String Nazov kluca v $_SESSION, ktore nesie pole zo spravami
     */
    private $flashKey = 'mAlert';
    
    /**
     * Metóda nastaví novú správu
     * 
     * @param String $key Kľúč pod ktorým sa správa uloží do poľa
     * @param String $flashType Typ správy
     * @param String $message Text správy
     */
    public function setAlert($key, $flashType, $message){
        
        if(!isset($_SESSION[$this->flashKey])){
            $_SESSION[$this->flashKey] = array();
        }
        $_SESSION[$this->flashKey][$key] = (object) array('type' => $flashType, 'text' => $message);
    }
    
    /**
     * Metóda zistí či používateľ má priradenú správu s oznámením
     * 
     * @return boolean
     */
    public function hasAlert($key){
        
        if(isset($_SESSION[$this->flashKey]) && array_key_exists($key, $_SESSION[$this->flashKey])){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }
    
    /**
     * Metóda vráti oznámenie z poľa podľa zadaného kľúča
     * 
     * @return object
     */
    public function getAlert($key){
        
        if(isset($_SESSION[$this->flashKey]) && array_key_exists($key, $_SESSION[$this->flashKey])){
            
            $flash = $_SESSION[$this->flashKey][$key];
            unset($_SESSION[$this->flashKey][$key]);
            return $flash;
        }
        else{
            return NULL;
        }
    }
    
    /**
     * Metóda vráti celé pole oznámení
     * 
     * @return array
     */
    public function getAlerts(){
        
        if(isset($_SESSION[$this->flashKey])){
            $flashes = $_SESSION[$this->flashKey];
            unset($_SESSION[$this->flashKey]);
            return $flashes;
        }
        else{
            return array();
        }
    }
    
    /**
     * Metóda vypíše oznámenia z fronty. 
     * Štandardne sa používa na výpis oznámení o úspešnosti operácií 
     * @return String HTML snippet
     */
    public function displayAlerts(){
        
        $snippet = '';
        
        if(isset($_SESSION[$this->flashKey])){
            
            foreach ($_SESSION[$this->flashKey] as $flash){
                $snippet .= '<div class="alert '.$flash->type.'">';
                $snippet .= $flash->text;
                $snippet .= '</div>' . PHP_EOL;
            }  
            unset($_SESSION[$this->flashKey]);
        }
                 
        return $snippet;
    }
}
