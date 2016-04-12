<?php

namespace app\controllers;

use flow\AbstractController;
use flow\Flow;
use PDO;

/**
 * @todo write description
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */
class PetrinetController extends AbstractController{
    
    public function create(){
        $this->render('petrinet');
    }
    
    /**
    * Vyfiltrovanie vsetkych procesov v zavislosti na firme a pouzivatelovi, z ktorych pouzivatel moze vytvorit case
    */     
    public function filter(){
        $query = 'SELECT t.firm_id, t.firm_name, PN_X_FIRM.pn_id, petri_net.`name` FROM 
                    (SELECT FIRM.firm_id, FIRM.firm_name 
                        FROM FIRM 
                        LEFT JOIN USERS_X_FIRM 
                        ON FIRM.firm_id = USERS_X_FIRM.firm_id 
                        WHERE USERS_X_FIRM.user_id = 1) AS t
                    LEFT JOIN PN_X_FIRM on t.firm_id = PN_X_FIRM.firm_id
                    LEFT JOIN petri_net on PN_X_FIRM.pn_id = petri_net.id
                    WHERE PN_X_FIRM.pn_id IS NOT NULL;';
        $result = Flow::app()->pdo->query($query)->fetchAll(PDO::FETCH_OBJ);
        $arr = [];
        foreach ($result as $r){
            if(!isset($arr[$r->firm_id])){
                $arr[$r->firm_id] = [];
                $arr[$r->firm_id]['nets'] = [];
                $arr[$r->firm_id]['name'] = $r->firm_name;
            }
            $arr[$r->firm_id]['nets'][$r->pn_id] = $r->name; 
        }
        $this->render('listall', ['nets' => $arr]);
    }
    
}
