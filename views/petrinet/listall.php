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

<form method="POST" action="<?php echo ENTRY_SCRIPT_URL . 'case/create' ?>"><?php 
?>
    <label>Názov pre case: <input type="text" name="name" class="input-full"></label>
    <br />
    <select id="firms" name="firm" class="mt">
        <option>Vyberte firmu</option>
        <?php foreach($data->nets as $id => $net){
            echo '<option name="firm" value="' . $id . '">' . $net['name'] . '</option>';
        }?>
    </select>
    <br/>
    <select id="nets"  name="pn" class="mt">
         <option>Vyberte proces</option>
    </select>
    <br />
    <button type="submit"  class="button-blue button-blue-full">Vytvoriť case</button>
</form>
<script>
    var firms = JSON.parse('<?php echo json_encode($data->nets)?>');
    $(document).on("change", "#firms", function(){
        var nets = firms[$(this).val()].nets;
        var html="<option>Vyberte proces</option>";
        for(var key in nets) {
            html += "<option value=\"" + key  + "\">" + nets[key] + "</option>";
        }
        $("#nets").html(html);
    });
</script>

