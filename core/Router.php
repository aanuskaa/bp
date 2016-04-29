<?php

namespace flow;

/**
 * Smeruje požiadavku používateľa na konkrétny ovládač
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.market/
 * @version    1.0.0
 */
class Router {

    public function resolve(){
        
        $request = $this->parseUrl();
        //Default search Controller->action();
        $controllerClass = '\app\controllers\\' . ucfirst($request[0]) . 'Controller';

        if(!empty($request[1]) && method_exists($controllerClass, $action = $request[1])){
            
            $this->forward($controllerClass, $action, array_slice($request, 2));
            return TRUE;
        }
        elseif(empty($request[1]) && method_exists($controllerClass, 'index')) {
            
            $this->forward($controllerClass, 'index');
            return TRUE;
        }
        else{
            $this->forward('\app\controllers\TaskController', 'listAll');
        }
    }
    
    /**
     * Parsuje URL cez lomitko
     * @param string $urldata Pri internom presmerovani moze byt vstupom string,
     * inak sa pouzije $_SERVER['REQUEST_URI]
     * @return array
     */
    protected function parseUrl($urldata = NULL){
        
        if(empty($urldata)){
            $urldata = $_SERVER['REQUEST_URI'];
        }

        //** Ak je celá aplikácia v podzložke, z cesty sa odstráni podzložka
        if( strpos($urldata, basename(ROOT_URL)) !== FALSE ){ 
            $urldata = substr($urldata, strpos($urldata, basename(ROOT_URL)) + strlen(basename(ROOT_URL)));
        }
        
        //split cez otaznik
        if(strpos($urldata, '?') !== FALSE){
            $urldata = substr($urldata, 0, strpos($urldata, '?'));
        }
        //split cez lomitko
        $splited = preg_split('/[\/]/', $urldata);
        $parsed  = array_values((array_filter($splited,'self::array_trim'))); //vycisti prazdne hodnoty
        
        //odstrani vstupny php script ak je
        if(isset($parsed[0]) && strpos( $parsed[0], '.php') !== FALSE ){
            array_shift($parsed);
        }
        
        return $parsed;
    }
    
    /**
     * Callback pre array_filter, odstranenie prazdnych hodnot z pola ak je v URL "//"
     * @param string 
     * @return boolean
     */
    protected function array_trim($value) {
        
        return !empty($value) || $value === '0';
    }
    
    /**
     * Vytvori ovladac a zavola metodu
     * @param String $controllerClass Fully qualified domain name
     * @param String $action Presný názov akcie
     * @param mixed $id
     * @param array $params
     */
    protected function forward($controllerClass, $action, $params = []) {
        
        $controller = new $controllerClass();
        $params = array_values($params);
        $controller->$action(...$params); //as of PHP >= 5.6.0
    }
}
