<?php

/**
 * Zobrazi svg aktualneho stavu case-u
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */
 
 include APP_PATH . 'editor/tomas/visualize.php';

?>
<div id="visiblesvg">
</div>
  
<script>
    function addProgress(idTransition, username){
        var x = $("#" + (idTransition)).attr("x");
        var y = $("#" + (idTransition)).attr("y");
        var width = $("#" + (idTransition)).attr("width") -10;
        var height = $("#" + (idTransition)).attr("height") -10;
        var svg = $("#visiblesvg").find('svg')[0];
        var link = 'http://www.w3.org/2000/svg';
        
        var text_id = 'transition-name' + (idTransition);
        console.log(text_id);
        
        var icon = document.createElementNS(link, 'image');
        var set = document.createElementNS(link, 'set');
        var title = document.createElementNS(link, 'title');
        
        icon.setAttributeNS(link,'height',height+11);
        icon.setAttributeNS(link,'width',width+10);
        icon.setAttributeNS(link,'x',x);
        icon.setAttributeNS(link,'y',y);
        icon.setAttributeNS(link,'visibility', 'visible')+ 5;
        icon.setAttributeNS('http://www.w3.org/1999/xlink','href','<?php echo ROOT_URL?>style/tool.gif');
        icon.setAttributeNS(link,'id','transition-icon' + (idTransition-1));
        
        set.setAttributeNS(link,'attributeName','fill-opacity');
        set.setAttributeNS(link,'to','0.9');
        set.setAttributeNS(link,'begin',(idTransition-1) + ".mouseover");
        set.setAttributeNS(link,'end', (idTransition-1) + ".mouseout");
        
        title.textContent = 'Vykonáva: ' + username;
        
        svg.appendChild(icon);
        
        icon.appendChild(title);
        
        $("#visiblesvg").find('svg').html($("#visiblesvg").find('svg').html());
    }
    
</script>
    
<?php

echo '<script>$("#visiblesvg").html(svg);</script>';

foreach ($data->progress as $p){
    $user_name = $p->last_name . ' ' . $p->first_name;
    echo '<script>addProgress(' . $p->id_in_xml . ', "' . $user_name . '");</script>';
}


    
    