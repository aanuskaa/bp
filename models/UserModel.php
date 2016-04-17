<?php

namespace app\models;

/**
 * Model usera
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
        return 'USERS';
    }
}
