<?php

/**
 * Zobrazi vsetky ulohy, ktore si pouzivatel moze vziat. Doraz na prechody, ktore su referencovane na neho
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */

$arr = (array) $data->firm_cases;
$arr = json_encode($arr);
?>
<select id="firms" name="firm" class="mt">
    <option name="firm" value="all">All firms</option>
    <?php foreach($data->firms as $firm){
        echo '<option name="firm" value="' . $firm->firm_id . '">' . $firm->firm_name . '</option>';
    }?>
</select>
<div id="ajax-target">
    <?php include 'listavailable.table.php';?>
</div>

<script>
    var data = JSON.parse(<?php echo json_encode($arr)?>);
    $(document).on("change", "#firms", function(){
        var html = '<tr>';
        var firm_id = this.value;
        if(firm_id !== 'all'){
            var firm_name = '<td>' + data[this.value]['name'] + '</td>';
            $.each(data[this.value]['cases'], function(c_key, c){
                var id_pn = c['id_pn'];
                var case_name = '<td>' + c['name'] + '</td>';
                var case_id = c_key;
                $.each(c['tasks'], function(t_key, t){
                    var ref = '<td>&nbsp;</td>';
                    var role = '';
                    if(t['reference'] !== null){
                        ref = '<td id="reference_check"><i class="icon icon-tick"></i></td>';
                    }
                    if(t['role_name'] !== null){
                        role = '<td>' + t['role_name'] + '</td>';
                    }
                    else{
                        role = '<td>No role assigned</td>';
                    }
                    var form = '<td><form action="<?php echo ENTRY_SCRIPT_URL?>task/take" method="POST">\n\
                        <button type="submit" class="button-blue button-blue-full">Take this task</button>\n\
                        <input type="hidden" name="task" value="'  + case_id + ',' + t_key +  '"/></form></td></tr>';
                        html += ref + firm_name + case_name + '<td>' + t['name'] + '</td>' + role + form;
                });                
            });
        }
        else{
            html = '<?php echo $all_firms;?>';
        }
        $("#tasks").html(html);
    });
    $(document).on("ready", function(){
        setInterval(ajaxify, 30000);
    });
    function ajaxify(){
        $.ajax({
            method: "GET",
            url: window.location.href,
            data: {ajaxify: 1},
            success: function(data){
                $("#ajax-target").html(data);
            }
        });
    }
</script>
