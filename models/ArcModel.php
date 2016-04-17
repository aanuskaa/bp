<?php

namespace app\models;

use flow\AbstractModel;
use flow\Flow;

/**
 * Vseobecny model hrany
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */
class ArcModel extends AbstractModel{
    
    public function rules() {
        
    }

    public function table() {
        return '';
    }
    
    /**
     * @inheritdoc
     * @return ArcModel
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    /**
     * Funkcia vyselectuje hrany, ktore vchadzaju do prechodu
     * @param type $condition
     * @param type $columns
     * @param type $joins
     * @param type $index
     * @return type
     */
    public function findAll($condition = '', $columns = '*', $joins = '', $index = '') {
        
        $query = 
                "SELECT {$columns} FROM 
                (
                    SELECT id as id_arc, `from`, `to`, weight, 'PT' as `type` FROM arc_PT
                    UNION ALL SELECT id as id_arc, `from`, `to`, weight, 'inhibitor' as `type` FROM arc_inhibitor
                    UNION ALL SELECT id as id_arc, `from`, `to`, 1 as weight, 'reset' as `type` FROM arc_reset
                ) arcs 
                {$joins}
                WHERE {$condition}";
                
        return Flow::app()->pdo->query($query)->fetchAll();
    }
}
