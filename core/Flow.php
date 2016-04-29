<?php

namespace flow;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;

/**
 * Singleton 
 *
 * @package    flow
 * @author     Anna Demeterova
 * @link       http://workflow.market/
 * @version    1.0.0
 * 
 * @property PdoConnection $pdo 
 */
class Flow {
    
    private static $instance;
    
    private static $objects;
            
    private $config = [];
    
    /**
     * Zamedzuje priamemu vytvoreniu objektu tejto triedy
     */
    private function __construct() {}
    
    /**
     * Zamedzuje klonovaniu
     */
    private function __clone() {}
    
    /**
     * Vrati objekt z registra
     * @param string $name
     * @return mixed Objekt z registra ak existuje, NULL ak neexistuje
     */
    public function __get($name) {
        
        if(array_key_exists($name, self::$objects)){
            return self::$objects[$name];
        }
        else{
            trigger_error("Flow doesn't store any object under the key {$name}.", E_USER_ERROR);
            return NULL;
        }
    }
    /**
     * Singleton metoda pre pristup k instancii tejto triedy
     * @return Flow
     */
    public static function app(){
        if(empty(self::$instance)){
            self::$instance = new static;
        }
        return self::$instance;
    }
    
    /**
     * Start celej aplikacie 
     */
    public function start($config){
        
        session_start();
        
        $this->config = $config;

        $this->autoloader(); //vytvori autoloader
        
        $this->storeObject('\flow\PdoConnection', 'pdo'); //pripojenie s databazou
        $this->pdo->newConnection($config['db']['dns'],$config['db']['user'],$config['db']['pass']);

        self::$objects['alertmanager'] = new AlertManager();
        
        self::$objects['auth'] = new Authentication();
        self::$objects['auth']->checkForAuthentication();

        self::$objects['router'] = new Router(); // smeruje požiadavku
        self::$objects['router']->resolve(); 
        
        //** Operacie registrovane za tymto riadkom sa neprejavia !!
    }
    
    /**
     * Vlozi novovytvoreny objekt do registra
     * @param string $object
     * @param string $key
     */
    public function storeObject($object, $key){
         self::$objects[$key] = new $object();
    }
    
    /**
     * Vrati obsah z $config podla zadaneho kluca
     * Klucom moze byt string, ale aj pole, v tom pripade sa vyhladava rekurzivne
     * @param array|string $key
     * @return mixed
     */
    public function getConfig($key){
                
        $found = $this->config;

        if(is_array($key)){
            $iterator  = new RecursiveArrayIterator($key);
            $recursive = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);

            foreach ($recursive as $key => $value){
                $found = array_key_exists($key, $found)? $found[$key] : FALSE;
            }
            return array_key_exists($value, $found)? $found[$value] : FALSE;
        }
        elseif(array_key_exists($key, $found)){
            return $found[$key];
        }
            
        return FALSE;
    }
    
     
    /**
     * Vrati Autoloader object
     * @return Autoloader
     */
    public function autoloader() {
        
        if(!isset(self::$objects['autoloader'])){ 
            require 'Autoloader.php';
            require 'namespaces.php';
            self::$objects['autoloader'] = new Autoloader();
            
            //Prida default namespaces
            self::$objects['autoloader']->addNamespacesArray($namespaces);
        }

        return self::$objects['autoloader'];
    }
    
    /**
     */
    /**
     * Vrati prelozeny string prelozeny do aktualneho jazyka aplikacie
     * @todo Dokoncit
     * 
     * @param string $str
     * @param array $params [optional] Nahradi premenne vo vseobecnom preklade za konkretne hodnoty
     * napr. translate('Pay me {NUMBER} dollars', ['{NUMBER}' => 10])
     * @return string
     */
    public static function translate($str, $params = []) {
        
        return str_replace(array_keys($params), array_values($params), $str);
    }
}
