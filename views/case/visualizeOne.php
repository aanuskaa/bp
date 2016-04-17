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
  
<script>
    function addProgress(idTransition){
        var x = $("#" + (idTransition-1)).attr("x");
        var y = $("#" + (idTransition-1)).attr("y");
        var width = $("#" + (idTransition-1)).attr("width") -10;
        var height = $("#" + (idTransition-1)).attr("height") -10;
        var svg = document.getElementsByTagName('svg')[0];
        
        var icon = document.createElementNS("http://www.w3.org/2000/svg", 'image');
        
        icon.setAttributeNS('http://www.w3.org/2000/svg','height',height+11);
        icon.setAttributeNS('http://www.w3.org/2000/svg','width',width+10);
        icon.setAttributeNS('http://www.w3.org/2000/svg','x',x);
        icon.setAttributeNS('http://www.w3.org/2000/svg','y',y);
        icon.setAttributeNS('http://www.w3.org/2000/svg','visibility', 'visible')+ 5;
        icon.setAttributeNS('http://www.w3.org/1999/xlink','href','<?php echo ROOT_URL?>style/tool.png');
        icon.setAttributeNS('http://www.w3.org/2000/svg','class','transition-icon');
        console.log($("#" + (idTransition-1)).parent());
        
        svg.appendChild(icon);
        $(svg).html($(svg).html());
    }
    
</script>
    
<?php
echo $data->svg;

var_dump($data->progress);

foreach ($data->progress as $p){
    echo '<script>addProgress(' . $p->id_in_xml . ');</script>';
}


    
    