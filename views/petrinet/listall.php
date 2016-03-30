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
    <label>Názov pre case: <input type="text" name="name" class="mt"></label>
    <br />
    <button type="submit">Vytvor case</button>
</form>
<script>
    var firms = JSON.parse('<?php echo json_encode($data->nets)?>');
    $(document).on("change", "#firms", function(){
        var nets = firms[$(this).val()].nets;
        var html="<option>Vyberte firmu</option>";
        for(var key in nets) {
            html += "<option value=\"" + key  + "\">" + nets[key] + "</option>";
        }
        $("#nets").html(html);
    });
</script>

