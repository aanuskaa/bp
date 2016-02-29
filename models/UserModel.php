<?php

namespace app\models;

/**
 * @todo write description
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */
class UserModel extends \flow\AbstractModel{
    public function rules() {
        return[
            'required' => 'username, password',
            'unique'   => 'id',
        ];
    }

    public function table() {
        
    }

//put your code here
}
