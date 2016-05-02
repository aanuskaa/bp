<?php

namespace app\controllers;

use flow\AbstractController;
use flow\Flow;
use PDO;

/**
 * Controller pre pracu s petrinet
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.market/
 * @version    1.0.0
 */
class PetrinetController extends AbstractController{
    
    /**
     * @inheritdoc 
     */
    protected function accessRules(){
        return [
            'logged_in' => ['filter'],
        ];
    }
    
    /**
    * Vyfiltrovanie vsetkych procesov v zavislosti na firme a pouzivatelovi, z ktorych pouzivatel moze vytvorit case
    */     
    public function filter(){
        $query = 'SELECT
                    USERS_X_FIRM.firm_id,
                    USERS_X_FIRM.user_id,
                    USERS_X_ROLE.role_id,
                    FIRM.firm_name,
                    petri_net.`name`,
                    ROLES.role_name,
                        ROLES_START_CASES.pn_id
                FROM 
                        USERS_X_FIRM 
                LEFT JOIN 
                        USERS_X_ROLE ON USERS_X_FIRM.user_id = USERS_X_ROLE.user_id AND USERS_X_FIRM.firm_id = USERS_X_ROLE.firm_id
                LEFT JOIN
                        ROLES_START_CASES ON USERS_X_ROLE.role_id = ROLES_START_CASES.role_id
                LEFT JOIN
                        FIRM ON USERS_X_FIRM.firm_id = FIRM.firm_id
                LEFT JOIN
                        petri_net ON ROLES_START_CASES.pn_id = petri_net.id
                LEFT JOIN
                        ROLES ON ROLES.role_id = USERS_X_ROLE.role_id
                WHERE 
                        USERS_X_FIRM.user_id = ' . Flow::app()->auth->getUserId() . '
                        AND EXISTS (
                                SELECT id FROM PN_X_FIRM WHERE PN_X_FIRM.firm_id = USERS_X_FIRM.firm_id AND PN_X_FIRM.pn_id = ROLES_START_CASES.pn_id
                    );';
        $result = Flow::app()->pdo->query($query)->fetchAll(PDO::FETCH_OBJ);
        $arr = [];
        foreach ($result as $r){
            if(!isset($arr[$r->firm_id])){
                $arr[$r->firm_id] = [];
                $arr[$r->firm_id]['nets'] = [];
                $arr[$r->firm_id]['name'] = $r->firm_name;
            }
            $arr[$r->firm_id]['nets'][$r->pn_id]['name'] = $r->name; 
            $arr[$r->firm_id]['nets'][$r->pn_id]['role'] = $r->role_name;
        }
        $this->render('listall', ['nets' => $arr]);
    }
    
}
