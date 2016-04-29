<?php

/**
 * Vylistuje vsetky procesy, z ktorych moze pouzivatel vytvorit case
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */

?>

<form method="POST" action="<?php echo ENTRY_SCRIPT_URL . 'case/create' ?>"><?php 
?>
    <div class="row">
        <div class="col-lg-4 col-lg-offset-2">
            <select id="firms" name="firm" class="mt">
                <option value="">Select firm</option>
                <?php foreach($data->nets as $id => $net){
                    echo '<option name="firm" value="' . $id . '">' . $net['name'] . '</option>';
                }?>
            </select>
        </div>
        <div class="col-lg-4">
            <select id="nets"  name="pn" class="mt">
                 <option value="">Select a process</option>
            </select>
        </div>
        <div class="col-lg-8 col-lg-offset-2">
            <label>Name of the case: <input type="text" name="name" class="input-full"></label>
        </div>
        <div class="col-lg-8 col-lg-offset-2">
            <button type="submit"  class="button-blue button-blue-full">Create case</button>
        </div>
    </div>
</form>
<script>
    var firms = JSON.parse('<?php echo json_encode($data->nets)?>');
    $(document).on("change", "#firms", function(){
        var nets = firms[$(this).val()].nets;
        var html="<option value=\"\">Select a process</option>";
        for(var key in nets) {
            html += "<option value=\"" + key  + "\">" + nets[key] + "</option>";
        }
        $("#nets").html(html);
    });
</script>

