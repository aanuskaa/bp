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

<form action="<?php echo ENTRY_SCRIPT_URL . 'user/login/'?>" method="POST">
    <input type="text" name="UserModel[email]" class="input"/>
    <input type="password" name="UserModel[password]" class="input" />
    <button type="submit" class="button-blue button-grey-full">Login</button>
</form>