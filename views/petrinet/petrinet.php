<?php

/**
 * @todo write description
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */
?>

<form><?php 
    foreach ($data->nets as $net){
        echo '<input type="radio" name="pn" value="' . $net->id .  '">'. $net->name .'<br>';
    }
    ?>
</form>