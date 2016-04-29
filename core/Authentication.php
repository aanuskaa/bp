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
class Authentication {
     
    /**
     * @var int
     */
    protected $userId = 0;
    
    /**
     * @var boolean
     */
    protected $loggedIn = false;
    
    /**
     * @var String
     */
    public $role = '';
        
    /**
     * @var String
     */
    protected $username;
            
    /**
     * Session expiration in seconds, defaults to 1hour = 3600 seconds
     * @var type 
     */
    protected $session_expiration = 3600;
    
    /**
     * Session regeneration interval in seconds, to avoid session fixation
     * defaults to 5 minutes = 300 seconds
     * @var type 
     */
    protected $session_regenerate = 300;

    /**
     * @var String
     */
    protected $loginFailureReason;
    
    /**
     * Constants that define login failure reasons
     */
    const INVALID_CREDENTIALS = 1;
    
    const BANNED = 2;
    
    const INACTIVE = 3;
    
    const NOUSER = 4;
    
    const SESSION_EXPIRED = 5;
                
    /**
     * Constructor
     */
    public function __construct() { }
    
    /**
     * Metóda zaisťuje autentifikáciu používateľa
     */
    public function checkForAuthentication() {
        
        if(isset($_SESSION['flow_session']['last_activity']) && isset($_SESSION['flow_session']['session_uid'])){   //ci je nastavene potrebne session
            
            if($_SESSION['flow_session']['last_activity'] + $this->session_expiration < time()){                    //ci neexpirovala posledna aktivita
                $this->loginFailureReason = self::SESSION_EXPIRED;
            }
            elseif(intval($_SESSION['flow_session']['session_uid']) > 0) {                                          //validne session uid
                $this->sessionAuthenticate(intval($_SESSION['flow_session']['session_uid']));
            }
        } 
        else{
            $this->loginFailureReason = self::NOUSER;
        }
    }
    
    /**
     * Updates/Creates user session
     * @param mixed $uid User identificator to store in session
     */
    protected function updateSession($uid) {
                
        if(isset($_SESSION['flow_session']['last_activity'])                                                //pregenerovanie session ID
                && $_SESSION['flow_session']['last_activity'] + $this->session_regenerate < time()){
            session_regenerate_id(true); 
        }

        $this->sessionPushState('last_activity', time());
        $this->sessionPushState('session_uid', $uid);

        $this->loggedIn = true;
        $this->userId   = $uid;        
    }


    /**
     * Verifies login from $_SESSION id
     * @param int $uid
     */
    protected function sessionAuthenticate($uid) {
        
        if($uid == 1){
            $this->loggedIn = true;
            $this->userId   = 1;
            $this->role     = 'admin';
            $this->username = 'admin';
            $this->updateSession(1);
        }
        elseif ($uid == 2) {
            $this->loggedIn = true;
            $this->userId   = 2;
            $this->role     = 'user';
            $this->username = 'user';
            $this->updateSession(2);
        }
        else{
            $this->loggedIn = false;
            $this->loginFailureReason = self::NOUSER;
        }
    }
    
    /**
     * Login user with username and password
     * 
     * For a simple test login with (username => password):
     * admin => admin
     * user => user
     * 
     * @param string $u username
     * @param string $p password
     */
    public function postAuthenticate($u, $p) {
        
        if($u == 'admin' && $p == 'admin'){
            $this->loggedIn = true;
            $this->userId   = 1;
            $this->role     = 'admin';
            $this->username = 'admin';
            $this->updateSession(1);
        }
        elseif ($u == 'user' && $p == 'user') {
            $this->loggedIn = true;
            $this->userId   = 2;
            $this->role     = 'user';
            $this->username = 'user';
            $this->updateSession(2);
        }
        elseif ($u == 'user2' && $p == 'user2') {
            $this->loggedIn = true;
            $this->userId   = 2;
            $this->role     = 'user';
            $this->username = 'user2';
            $this->updateSession(3);
        }
        else{
            $this->loggedIn = false;
            $this->loginFailureReason = self::INVALID_CREDENTIALS;
        }
    }
        
    /**
     * Destroys a user session
     */
    public function logout() {
        session_destroy();
    }
                
    /**
     * Returns a description of failed loggin
     * @return string 
     */
    public function getTextLoginFailureReason() {
        
        $err = [
            self::INVALID_CREDENTIALS => Flow::t('Invalid credentials.'),
            self::BANNED => Flow::t('Your account is banned.'),
            self::INACTIVE => Flow::t('Your account is no longer active'),
            self::NOUSER => Flow::t('Sorry, you are not logged in.'),
            self::SESSION_EXPIRED => Flow::t('Sorry, your session has expired')
        ];       
        
        if( array_key_exists( $this->loginFailureReason, $err ) ){
            return $err[$this->loginFailureReason];
        }
            
        return $this->loginFailureReason;
    }

    /**
     * LoggedIn state getter
     * @return boolean
     */
    public function isLoggedIn() {
        return $this->loggedIn;
    }
    
    /**
     * UserId getter
     * @return int
     */
    public function getUserID() {
        return $this->userId;
    }

    /**
     * Username getter
     * @return String
     */
    public function getUsername() {
        return $this->username;
    }
        
    /**
     * Creates a password hash with salt
     * 
     * @param string 
     * @return string 
     */
    public function getHash($password) {
        return md5("flowapp" . $password);
    }
    
    /**
     * Saves the state or data into user session
     * @param string $key 
     * @param mixed $data
     */
    public function sessionPushState($key, $data){
        
        if(!isset($_SESSION['flow_session'])){
            $_SESSION['flow_session'] = [];
        }
        $_SESSION['flow_session'][$key] = $data;
    }
    
    /**
     * Returns the state or data from user session
     * @param string $key Key to search for
     * @return mixed
     */
    public function sessionGetState($key){
        
        return isset($_SESSION['flow_session'][$key])? $_SESSION['flow_session'][$key] : NULL;
    }
    
    /**
     * Similar to sessionGetState(), returns the state or data from user session
     * and deletes them subsequently.
     * @param string $key Key to search for
     * @return mixed
     */
    public function sessionPopState( $key ){
        
        $_s = NULL;
        
        if(isset($_SESSION['flow_session'][$key])){
            $_s = $_SESSION['flow_session'][$key];
            unset($_SESSION['flow_session'][$key]);
        }
        return $_s;
    }
}