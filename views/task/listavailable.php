<?php

/**
 * @todo write description
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
    <option name="firm" value="all">Všetky firmy</option>
    <?php foreach($data->firms as $firm){
        echo '<option name="firm" value="' . $firm->firm_id . '">' . $firm->firm_name . '</option>';
    }?>
</select>

<table class="mt">
    <thead>
        <tr>
            <th>Referencia</th>
            <th>Firma</th>
            <th>Case</th>
            <th>Task</th>
            <th>Rola</th>
            <th>Vezmi</th>
        </tr>
    </thead>
    <tbody id="tasks">
        <?php 
        $all_firms = '';
        foreach ($data->firm_cases as $id_firm => $firm){
            foreach ($firm['cases'] as $id_case => $case){
                foreach ($case['tasks'] as $id_task => $task){
                    if($task['reference'] != NULL){
                        $all_firms .= '<tr><td id="reference_check"><i class="icon icon-warning"></i></td>';
                    }
                    else{
                        $all_firms .= '<tr><td>&nbsp;</td>';
                    }
                    $all_firms .= '<td>' . $firm['name'] . '</td><td>' . $case['name'] . '</td><td>' . $task['name'] . '</td>';
                    if(isset($task['role_name'])){
                        $all_firms .= '<td>' . $task['role_name'] . '</td>';
                    }
                    else{
                        $all_firms .=  '<td>Bez naväzujúcej role</td>';
                    }
                    $all_firms .=  '<td>'
                    . '<form action="' . ENTRY_SCRIPT_URL. 'task/take" method="POST">'
                    . '<button type="submit" class="button-blue button-blue-full">Vezmi task</button>'
                    . '<input type="hidden" name="task" value="'  .$id_case . ',' . $id_task . '"/>'
                    . '</form>'
                    . '</td></tr>';
                }
            }
        }
        echo $all_firms;
        ?>
    </tbody>
</table>

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
                        role = '<td>Bez naväzujúcej role</td>';
                    }
                    var form = '<td><form action="<?php echo ENTRY_SCRIPT_URL?>task/take" method="POST">\n\
                        <button type="submit" class="button-blue button-blue-full">Vezmi task</button>\n\
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
</script>
