<?php

/**
 * @todo write description
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */

?><form method="POST" action="<?php echo ENTRY_SCRIPT_URL . 'case/create' ?>"><?php 
    echo 'Názov pre case: <input type="text" name="name" class="mt"><br>';
    foreach ($data->nets as $net){
        echo '<input type="radio" class="mt" name="pn" value="' . $net->id .  '">'. $net->name .'<br>';
    }
    echo '<button type="submit" class="mt">Vytvor case</button>';
?></form>