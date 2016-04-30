var offset;
var placesArray = [];
var transitonsArray = [];
var arcs = [];
var size = 20;
var IDOfElementCount = 0;

var transitionIsMoving = 0;
var movedTransition;
var numOfMoveTransitionClicks = 0;

var placeIsMoving = 0;
var movedPlace;
var numOfMovePlaceClicks = 0;

var arcIsDrawing = 0;
var sourceOfArc;
var nowDrawedArc;
var numOfDrawingArcClick = 0;

var lengthOfArrowHead = 10;
var fontSize = 12;
var arcRectOffset = 1;

var yLabelOffset = 12 + size;

var arcIsMoving = 0;
var numOfMovingArcClicks = 0;
var movedArc;
var findPoint = 0;
var indexOfPointToChange;
//polomer gulicky na inh. hrane
var inhibitorArcEndR = 5;
var inhibitorArcOffset = 1;

var tokenSize = 4;
var tokenShift = 9;

var description = "";
/*Popup wind texts*/
var labelText = "Please enter label";
var loadFromDBText = "Choose your net";
var saveToDBText = "Please enter net name";
var arcWeightText = "Please enter positive integer arc weight";
var markingText = "Please enter nonnegative place marking";
var saveXMLText = "Please enter XML file name";
var saveSVGText = "Please enter SVG file name";
var clearNetText = "Are you sure to clear? Any unsaved changes will be lost!";
var nescribeNetText = "Please Describe your net";

/*
 * 
 * pri hrane mi na touch zasa reaguje pozadu aaa len na minDistance od ciary
 */

$(document).ready(function() {
    //pri nacitani sa posunie len na editor html je asi kvoli FF
    $('html, body').animate({scrollTop: $('.controlAreaUpped').offset().top}, 1000);
    //ak sa nieco v kresliacej ploche nachadza a zrusi sa karta tak sa este spyta
    $(window).on('beforeunload', function() {
           if($('#netDrawArea').children().length > 0)
               return "Are you sure to leave? Any unsaved changes will be lost!";
    });
    //ak sa klikne mimo ako na popup okno tak sa okno schova
    $('.container-fluid').on('click', function(){
       if($('.container-fluid').hasClass('blur')){
           $('.popup').find('.cancel').trigger('click');
           //informacne okno
           $('.popupInfo').find('.close').trigger('click');
       } 
       
    });
    //disable right click context menu on svg draw area
    $('body').on('contextmenu', 'svg', function(){
        return false; 
    });
    //esc pre zrusenie popup okna bez ulozenia, enter pre ulozenie
    $(document).keyup(function(e) {
        if (e.keyCode === 13) $('.save').click();     // enter
        if (e.keyCode === 27) $('.close').click();   // esc
    });
   
    $('#netDrawArea').on('click',function(e) {
        
        //offset pre posun kreslenia objektov
        var offset = $(this).offset();
        var x = e.pageX - offset.left;
        var y = e.pageY - offset.top;
        
        
        if(placeIsMoving === 1){
            $('html,body').css('cursor','move');
            movedPlace.move(x, y);
            numOfMovePlaceClicks++;
        }
        if(numOfMovePlaceClicks === 2 && placeIsMoving === 1){
            alignAllArc();
            $('html,body').css('cursor','default');
            numOfMovePlaceClicks = 0;
            placeIsMoving = 0;
        }
        if ($("#placeRadio").is(":checked")){ 
            placesArray.push(new Place(x, y));
        }
        
        if ($("#transitionRadio").is(":checked")) 
            transitonsArray.push(new Transition(x, y));
        
        if(transitionIsMoving === 1){
            $('html,body').css('cursor','move');
            movedTransition.move(x, y);
            numOfMoveTransitionClicks++;
        }
        
        if(numOfMoveTransitionClicks === 2 && transitionIsMoving === 1){
            alignAllArc();
            $('html,body').css('cursor','default');
            numOfMoveTransitionClicks = 0;
            transitionIsMoving = 0;
        }
        if(arcIsDrawing === 1)
            numOfDrawingArcClick++;
        if(arcIsDrawing === 1 && numOfDrawingArcClick === 2){
            
            numOfDrawingArcClick = 0;
            arcIsDrawing = 0;
            nowDrawedArc.line.remove();
            if($('#inhibitorArcRadio').is(':checked'))
                nowDrawedArc.circle.remove();
            else
                nowDrawedArc.arrowHead.remove();
        }
        
        
        if(arcIsMoving === 1){
            $('html,body').css('cursor','move');
            movedArc.move(x, y);
            numOfMovingArcClicks++;
        }
        
        if(arcIsMoving === 1 && numOfMovingArcClicks === 2){
            movedArc.alignArcIfCan();
            $('html,body').css('cursor','default');
            numOfMovingArcClicks = 0;
            arcIsMoving = 0;
            findPoint = 0;
        }
        
    });
    
    $('#netDrawArea').on('mousemove', function(e){
        
        var offset = $(this).offset();
        var x = e.pageX - offset.left;
        var y = e.pageY - offset.top;
        var shift;
        
        if(transitionIsMoving === 1 && $('#moveRadio').is(':checked'))
            movedTransition.move(x, y);

        if(placeIsMoving === 1 && $('#moveRadio').is(':checked'))
            movedPlace.move(x, y);
        
        if(arcIsMoving === 1){
            movedArc.move(x, y);
        }
            
        if(arcIsDrawing === 1){
            if(sourceOfArc.type === "place")
                shift = size * 2;
            else if(sourceOfArc.type === "transition"){
                shift = size * 2;
            }
            
            var vect = new Vector(sourceOfArc.xPos - x, sourceOfArc.yPos - y);
            
            if(vect.length() > shift){
                if(sourceOfArc.xPos > x)
                    x = x * 1.015;
                else
                    x = x * 0.99;
                if(sourceOfArc.yPos > y)
                    y = y * 1.015;
                else
                    y = y * 0.99;
                
                var end = new Point(x, y);
                var start = calculateStartOfArc(sourceOfArc, end);
                var vect = new Vector(end.xPos - sourceOfArc.xPos, end.yPos - sourceOfArc.yPos);
                var sX, sY, shortenLen, ratio;
                if($('#resetArcRadio').is(':checked')){
                    shortenLen = vect.length() - lengthOfArrowHead*2;
                    ratio = shortenLen / vect.length();
                    sX = sourceOfArc.xPos + vect.xPos * ratio;
                    sY = sourceOfArc.yPos + vect.yPos * ratio;
                    
                }else if($('#arcRadio').is(':checked')){
                    shortenLen = vect.length() - lengthOfArrowHead;
                    ratio = shortenLen / vect.length();
                    sX = sourceOfArc.xPos + vect.xPos * ratio;
                    sY = sourceOfArc.yPos + vect.yPos * ratio;
                    
                }else if($('#inhibitorArcRadio').is(':checked')){
                    shortenLen = vect.length() - inhibitorArcEndR*2;
                    ratio = shortenLen / vect.length();
                    sX = sourceOfArc.xPos + vect.xPos * ratio;
                    sY = sourceOfArc.yPos + vect.yPos * ratio;
                }
                
                var points = start.xPos + "," + start.yPos + " " + sX + "," + sY;
                nowDrawedArc.line.attr({
                    'stroke' : 'blue',
                    'points' : points 
                });
                if($('#inhibitorArcRadio').is(':checked')){
                    shortenLen = vect.length() - inhibitorArcEndR;
                    ratio = shortenLen / vect.length();
                    sX = sourceOfArc.xPos + vect.xPos * ratio;
                    sY = sourceOfArc.yPos + vect.yPos * ratio;
                    
                    nowDrawedArc.circle.attr({
                        'cx': sX,
                        'cy': sY
                    });
                }else{
                    if($('#arcRadio').is(':checked'))
                        points = pointsOfArrowHead(start.xPos, start.yPos, end.xPos, end.yPos);
                    else if($('#resetArcRadio').is(':checked'))
                        points = pointsOfResetArrowHead(start.xPos, start.yPos, end.xPos, end.yPos);
                    
                    nowDrawedArc.arrowHead.attr({
                        'stroke' : 'blue',
                        'points' : points
                    });
                }

            }
        }
       
    });
    
    $("input[name='netControls']").change(function(){
        //zmena farby prechodov ak je aktivovany fire mod
        if($('#fireRadio').is(':checked')){
            setFireModeTransitionColors();
        }else if(!($('#fireRadio').is(':checked'))){
            resetTransitionColors();
        }
    });
    
});

function setFireModeTransitionColors(){
    for(var i = 0; i < transitonsArray.length; i++){
        if(transitonsArray[i].canFire()){
            transitonsArray[i].svgObjects.svgTransition.attr({
                'stroke': 'black',
                'fill' : '#80ffaa'
            });
        }else{
            transitonsArray[i].svgObjects.svgTransition.attr({
                'stroke': 'black',
                'fill' : '#ffb3b3'
            });
        }
    }
}

function resetTransitionColors(){
    for(var i = 0; i < transitonsArray.length; i++){
        transitonsArray[i].svgObjects.svgTransition.attr({
                'stroke': 'black',
                'fill' : 'white'
            });
    }
}
//funkcia vráti parameter z url
function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
    return -1;
};

function alignAllArc(){
    for(var i = 0; i < arcs.length; i++){
        arcs[i].alignArcIfCan();
    }
}

function toDegrees (angle) {
  return angle * (180 / Math.PI);
}

function toRadians (angle) {
  return angle * (Math.PI / 180);
}

function SVG(tag) {
    return document.createElementNS('http://www.w3.org/2000/svg', tag);
}

function Point(x, y){
    this.xPos = x;
    this.yPos = y;
}

function Vector(x , y){
    this.xPos = x;
    this.yPos = y;
    
    this.length = function (){
        return Math.sqrt(this.xPos*this.xPos + this.yPos*this.yPos);
    };
    
    this.perpendicular = function(){
        return new Vector(-this.yPos, this.xPos);
    };
}

function topElemsLabels(){
    for(var i = 0; i < transitonsArray.length; i++){
        $('#netDrawArea').append(transitonsArray[i].svgObjects.svgLabelRect)
                        .append(transitonsArray[i].svgObjects.svgName);
    }
    for(var i = 0; i < placesArray.length; i++){
        $('#netDrawArea').append(placesArray[i].svgObjects.svgLabelRect)
                        .append(placesArray[i].svgObjects.svgName);
      
    }
    
    
}

function ExtendNetDrawAreaIfCan(x, y){
    var dx = $('#netDrawArea').width() - x;
    var dy = $('#netDrawArea').height() - y;
    /**
     * ak je vo vzdialenosti 40 od okraja alebo ak sa nacita stiet a bude velka
     * tak dx, dy budu zaporne
     */
    if(dx <= 40 || dx < 0){
        $('#netDrawArea').width(x + 80);
        $(".svgDiv").animate({scrollLeft: $('.svgDiv').scrollLeft() + 80}, 500);
    }
    if(dy <= 40 || dy < 0){
         $('#netDrawArea').height(y + 80);
         $(".svgDiv").animate({scrollTop: $('.svgDiv').scrollTop() + 80}, 500);
     }
    
}

function Transition(x, y) {
    this.id = IDOfElementCount++;
    this.xPos = x;
    this.yPos = y;
    this.type = "transition";
    this.label = "";
    this.svgObjects = newSvgTransition(this, x, y);
    
    this.canFire = function(){
        var ret = true;
        for(var i = 0; i < arcs.length; i++){
            
            if(arcs[i].target === this){
                if((arcs[i].type === "regular") && (arcs[i].source.marking < arcs[i].weight))
                    ret = false;
                else if((arcs[i].type === "inhibitor") && (arcs[i].source.marking >= arcs[i].weight))
                    ret = false;
            }
        }
        
        return ret;
    };
    
    this.consume = function(){
        for(var i = 0; i < arcs.length; i++){
            if(arcs[i].target === this){
                if(arcs[i].type === "regular")
                    arcs[i].source.marking -= arcs[i].weight;
                else if(arcs[i].type === "reset")
                    arcs[i].source.marking = 0;
                
                arcs[i].source.updateTokens();
            }
        }
    };
    
    this.produce = function(){
        for(var i = 0; i < arcs.length; i++){
            if(arcs[i].source === this){
                arcs[i].target.marking += arcs[i].weight;
                arcs[i].target.updateTokens();
            }
        }
    };
    
    this.move = function(x, y){
        this.xPos = x;
        this.yPos = y;

        var textWidth = this.svgObjects.svgName.get(0).getBBox().width;
        this.svgObjects.svgName.attr({
            'x': this.xPos - textWidth / 2,
            'y': this.yPos + yLabelOffset
        });
        
        ExtendNetDrawAreaIfCan(x, y);
        
        this.svgObjects.svgTransition.attr({
            'x': this.xPos - size,
            'y': this.yPos - size
        });
        
        this.svgObjects.svgLabelRect.attr({
            'x': this.xPos - textWidth / 2 - 1,
            'y': this.yPos + size + 1
        });
        
        for(var i = 0; i < arcs.length; i++){
            if((arcs[i].source === this) || (arcs[i].target === this )){
                arcs[i].updateArc();
            }
        }
    };
    //koniec sipky pod objekt
    this.topElem = function(){
        this.svgObjects.svgTransition.appendTo($("#netDrawArea"));
    };
}

function newSvgTransition(elem, x, y) {
    ExtendNetDrawAreaIfCan(x,y);
    
    var $transition = $(SVG('rect'));
    $transition.attr({
        'id' : elem.id,
        'x': x - size,
        'y': y - size,
        'width': size * 2,
        'height': size * 2,
        'fill': 'white',
        'class': 'transition',
        'stroke': 'black',
        'stroke-width': 2
    });
    $transition.appendTo($("#netDrawArea"));
    
    var $svgLabelRect = $(SVG('rect'));
    $svgLabelRect.attr({
        'x' : elem.xPos,
        'y' : elem.yPos + yLabelOffset,
        'stroke-width' : 1,
        'fill-opacity' : 0.6,
        'fill' : 'white'
    });
    $svgLabelRect.appendTo($("#netDrawArea"));
    
    var $svgName = $(SVG('text'));
    $svgName.attr({
        'id' : 'label_' + elem.id,
        'y' : elem.yPos + yLabelOffset,
        'x' : elem.xPos,
        'font-family' : 'verdana',
        'font-weight': 'bold',
        'font-size' : fontSize
    });
    
    var labelNode = document.createTextNode(elem.label);
    $svgName.append(labelNode);
    $svgName.appendTo($('#netDrawArea'));
    
    $transition.hover(function(){
        if(!($('#fireRadio').is(':checked')))//aby mi to nemenilo farbu pri fireovani
            $transition.attr('stroke', 'blue');
        
        //zmena kurzora ak je prechod aktivovany a moze vypalit
        if($transition.attr('fill') === "#80ffaa")
            $('html,body').css('cursor','pointer');
        else if($transition.attr('fill') === "#ffb3b3")
            $('html,body').css('cursor','not-allowed');
            
    });
    $transition.mouseout(function(){
        if(!($('#fireRadio').is(':checked')))
            $transition.attr('stroke', 'black');
        //kurzor spat do normalu
        $('html,body').css('cursor','default');
    });

    $transition.click(function(e) {    
        
        if($('#labelRadio').is(':checked')){
           // var task = prompt("Label", elem.label);
            popUpTask(labelText);

            var mypop = $('.popup');
            mypop.find('.popup-input').val(elem.label);
            mypop.find('.save').click(function(){
                var task = mypop.find('.popup-input').val().trim();

                if(task === '' || task === undefined)
                    return;

                mypop.find('.cancel').trigger('click');

                if(task !== null || task !== undefined){

                    elem.label = task;
                    labelNode.nodeValue = elem.label;

                    var textWidth = $svgName.get(0).getBBox().width;
                    var textHeight = $svgName.get(0).getBBox().height;
                    $svgName.attr('x', elem.xPos - textWidth / 2);
                    
                    $svgLabelRect.attr({
                        'x': elem.xPos - textWidth / 2 - 1,
                        'y': elem.yPos + size + 1,
                        'width' : textWidth + 2,
                        'height' : textHeight - 3
                    });
                }
            });
            
        }
        
        if ($("#moveRadio").is(":checked")) {
            
            if(transitionIsMoving === 0){
                transitionIsMoving = 1;
                movedTransition = elem;
            }

        }else if($("#removeRadio").is(":checked")){
            var index = transitonsArray.indexOf(elem);
            transitonsArray.splice(index, 1);
            $transition.remove();
            $svgName.remove();
            $svgLabelRect.remove();
            for(var i = 0; i < arcs.length; i++){
                
                if(arcs[i].source === elem || arcs[i].target === elem){
                    arcs[i].svgArcObjects.lineOfArc.remove();
                    arcs[i].svgArcObjects.whiteLineOfArc.remove();
                    if(arcs[i].type === "inhibitor")
                        arcs[i].svgArcObjects.circle.remove();
                    else
                        arcs[i].svgArcObjects.arrow.remove();
                    arcs[i].svgArcObjects.arcWeight.remove();
                    arcs[i].svgArcObjects.arcWeightRect.remove();
  
                    arcs.splice(i, 1);
                    i--;
                }
                
            }
            
        }
        
        if($("#arcRadio").is(":checked")){
            if(arcIsDrawing === 0){
                sourceOfArc = elem;
                arcIsDrawing = 1;
                nowDrawedArc = SvgTmpArc(elem);
            }else{
                if(elem.type !== sourceOfArc.type){
                    arcs.push(new Arc(sourceOfArc, elem, "regular"));
                    topElemsLabels();
                }
            }
            
        }
        
        if($('#inhibitorArcRadio').is(':checked') || $("#resetArcRadio").is(":checked")){
            if(arcIsDrawing === 1){
                if(elem.type !== sourceOfArc.type){
                    if($("#resetArcRadio").is(":checked"))
                        arcs.push(new Arc(sourceOfArc, elem, "reset"));
                    else if($('#inhibitorArcRadio').is(':checked'))
                        arcs.push(new Arc(sourceOfArc, elem, "inhibitor"));
                    
                    topElemsLabels();
                }
            }
        }
        
        if($('#fireRadio').is(':checked')){
            
            if(elem.canFire()){
                elem.consume();
                elem.produce();
                
                for(var i = 0; i < transitonsArray.length; i++){
                    if(transitonsArray[i].canFire()){
                        transitonsArray[i].svgObjects.svgTransition.attr({
                            'stroke': 'black',
                            'fill' : '#80ffaa'
                    });
                    }else{
                        transitonsArray[i].svgObjects.svgTransition.attr({
                            'stroke': 'black',
                            'fill' : '#ffb3b3'
                    });
                    }
                }
            }
            //ak sa klikne na prechod a uz nemoze fireovat
            if($transition.attr('fill') === "#ffb3b3")
                $('html,body').css('cursor','not-allowed');
            
        }

    });
    return new TransitionObjects($transition, $svgName, $svgLabelRect, labelNode);
}

function TransitionObjects(svgTransition, svgName, svgLabelRect, labelNode){
    this.svgTransition = svgTransition;
    this.svgName = svgName;
    this.svgLabelRect = svgLabelRect;
    this.labelNode = labelNode;
}

function Place(x, y) {
    this.id = IDOfElementCount++;
    this.xPos = x;
    this.yPos = y;
    this.label = "";
    this.type = "place";
    this.marking = 0;
    this.markingLabel = "";
    this.svgTokens = [];
    
    this.initToken = function(xTokenShift, yTokenShift){
        var $token = $(SVG('circle'));
        $token.attr({
            'cx' : this.xPos + xTokenShift,
            'cy' : this.yPos - yTokenShift,
            'r' : tokenSize,
            'fill' : 'white'
        });
        this.svgTokens.push($token);
        $token.appendTo($('#netDrawArea'));
    };
    
    this.initSvgTokens = function(){
        
        this.initToken(-tokenShift, -tokenShift);
        this.initToken(0, -tokenShift);
        this.initToken(tokenShift, -tokenShift);
        this.initToken(-tokenShift, 0);
        this.initToken(0, 0);
        this.initToken(tokenShift, 0);
        this.initToken(-tokenShift, tokenShift);
        this.initToken(0, tokenShift);
        this.initToken(tokenShift, tokenShift);
        
    };
    
    this.svgObjects = newSvgPlace(this, x, y); // init až sem kvoli funkciam na graficke tokeny
    
    this.updateTokenPosition = function(index, xTokenShift, yTokenShift){
        this.svgTokens[index].attr({
            'cx' : this.xPos + xTokenShift,
            'cy' : this.yPos + yTokenShift
        });
    };
    
    this.updateSvgTokensPosition = function(){
        
        this.updateTokenPosition(0, -tokenShift, -tokenShift);
        this.updateTokenPosition(1, 0, -tokenShift);
        this.updateTokenPosition(2, tokenShift, -tokenShift);
        this.updateTokenPosition(3, -tokenShift, 0);
        this.updateTokenPosition(4, 0, 0);
        this.updateTokenPosition(5, tokenShift, 0);
        this.updateTokenPosition(6, -tokenShift, tokenShift);
        this.updateTokenPosition(7, 0, tokenShift);
        this.updateTokenPosition(8, tokenShift, tokenShift);
        
    };
    
    this.setSvgTokens = function(arr){
        for(var i = 0; i < this.svgTokens.length; i++){
            
            if(arr[i] === 0)
                this.svgTokens[i].attr('fill', 'white');
            else{
                if(placeIsMoving === 1)
                    this.svgTokens[i].attr('fill', 'blue');
                else
                    this.svgTokens[i].attr('fill', 'black');
            }
        } 
    };
    
    this.updateSvgTokens = function(num){
        if(num === 1){
            this.setSvgTokens([0, 0, 0, 0, 1, 0, 0, 0, 0]);
        }else if(num === 2){
            this.setSvgTokens([0, 0, 1, 0, 0, 0, 1, 0, 0]);
        }else if(num === 3){
            this.setSvgTokens([0, 0, 1, 0, 1, 0, 1, 0, 0]);
        }else if(num === 4){
            this.setSvgTokens([1, 0, 1, 0, 0, 0, 1, 0, 1]);
        }else if(num === 5){
            this.setSvgTokens([1, 0, 1, 0, 1, 0, 1, 0, 1]);
        }else if(num === 6){
            this.setSvgTokens([1, 0, 1, 1, 0, 1, 1, 0, 1]);
        }else if(num === 7){
            this.setSvgTokens([1, 0, 1, 1, 1, 1, 1, 0, 1]);
        }else if(num === 8){
            this.setSvgTokens([1, 1, 1, 1, 0, 1, 1, 1, 1]);
        }else if(num === 9){
            this.setSvgTokens([1, 1, 1, 1, 1, 1, 1, 1, 1]);
        }else if(num === 0 || num > 9){
            this.setSvgTokens([0, 0, 0, 0, 0, 0, 0, 0, 0]);
        }
    };
    
    this.updateTokens = function(){
        var tokens = this.marking;
        if(tokens < 10){
            this.markinglabel = "";
            this.svgObjects.markingNode.nodeValue = "";
        }
        else{
            this.markinglabel = "" + tokens;
            this.svgObjects.markingNode.nodeValue = this.markinglabel;
            var textWidth = this.svgObjects.svgMarking.get(0).getBBox().width;
            var textHeight = this.svgObjects.svgMarking.get(0).getBBox().height;
            this.svgObjects.svgMarking.attr({
                'x': this.xPos - textWidth / 2,
                'y': this.yPos +  textHeight / 4 
            });
        }
        
        this.updateSvgTokens(tokens);
    };
    
    this.addTokens = function(){
        var elem = this;
        popUpTask(markingText);
        
        var mypop = $('.popup');
        mypop.find('.popup-input').val(elem.marking);
        
        mypop.find('.save').click(function(){
            var task = mypop.find('.popup-input').val().trim();

            if(task === '' || task === undefined)
                return;

            mypop.find('.cancel').trigger('click');

            if(task !== null || task !== undefined){

                var tokensInt = parseInt(task);
                if(tokensInt >= 0){
                    elem.marking = tokensInt;
                    elem.updateTokens();
                    elem.updateSvgTokensPosition();
                }else{
                    //chyba hlaaska
                }
            }
        });
    };
    
    this.move = function(x, y){
        this.xPos = x;
        this.yPos = y;
        ExtendNetDrawAreaIfCan(x, y);
        var textWidth = this.svgObjects.svgName.get(0).getBBox().width;
        this.svgObjects.svgName.attr({
            'x': this.xPos - textWidth / 2,
            'y': this.yPos + yLabelOffset
        });
        
        this.svgObjects.svgPlace.attr({
            'cx': this.xPos,
            'cy': this.yPos
        });
        
        this.svgObjects.svgLabelRect.attr({
            'x': this.xPos - textWidth / 2 - 1,
            'y': this.yPos + size + 1
        });

        for(var i = 0; i < arcs.length; i++){
            if((arcs[i].source === this) || (arcs[i].target === this )){
                arcs[i].updateArc();
            }
        }
        this.updateSvgTokensPosition();
        this.updateTokens();    
    };
    
    this.topElem = function(){
        this.svgObjects.svgPlace.appendTo($("#netDrawArea"));
        
        for(var i = 0; i < this.svgTokens.length; i++)
            this.svgTokens[i].appendTo($("#netDrawArea"));
        this.svgObjects.svgMarking.appendTo($("#netDrawArea"));
    };
    
}

function newSvgPlace(elem, x, y) {
    //ak sa vztvori miesto na kraji kresliacej plochy, tak sa polocha zvacsi
    ExtendNetDrawAreaIfCan(x,y);
    
    var $place = $(SVG('circle'));
    $place.attr({
        'id' : elem.id,
        'cx': x,
        'cy': y,
        'r': size,
        'fill': 'white',
        'stroke': 'black',
        'stroke-width': 2,
        'class': 'place'
    });
    $place.appendTo($("#netDrawArea"));
    
    //inicializuje kruzky v mieste
    elem.initSvgTokens();
    
    var $markingTextSVG = $(SVG('text'));
    $markingTextSVG.attr({
        'x' : x,
        'y' : y,
        'font-family' : 'verdana',
        'font-weight': 'bold',
        'font-size' : fontSize
    });
    var markingNode = document.createTextNode(elem.markingLabel);
    $markingTextSVG.append(markingNode);
    $markingTextSVG.appendTo('#netDrawArea');
    
    var textWidth = $markingTextSVG.get(0).getBBox().width;
    var textHeight = $markingTextSVG.get(0).getBBox().height;
    
    var $svgLabelRect = $(SVG('rect'));
    $svgLabelRect.attr({
        'x' : elem.xPos,
        'y' : elem.yPos + yLabelOffset,
        'stroke-width' : 1,
        'fill-opacity' : 0.6,
        'fill' : 'white'
    });
    $svgLabelRect.appendTo($("#netDrawArea"));
    
    $markingTextSVG.attr({
        'x': x - textWidth / 2,
        'y': y +  textHeight / 4 
    });
    var $svgName = $(SVG('text'));
    $svgName.attr({
        'y' : elem.yPos + yLabelOffset,
        'x' : elem.xPos,
        'font-family' : 'verdana',
        'font-weight': 'bold',
        'font-size' : fontSize
    });
    
    
    var labelNode = document.createTextNode(elem.label);
    $svgName.append(labelNode);
    $svgName.appendTo($('#netDrawArea'));
    /**
     * v kazdom objekte miesta je zmena kurzora pri aktivovani stavu pridaj/odober token
     * 
     */
    $place.hover(function(){
        placeChangeColor('blue', $place, $markingTextSVG, elem.svgTokens);
        if($('#addTakeTokenRadio').is(':checked'))
            $('html,body').css('cursor','pointer');
    });
    $place.mouseout(function(){
        placeChangeColor('black', $place, $markingTextSVG, elem.svgTokens);
        if($('#addTakeTokenRadio').is(':checked'))
            $('html,body').css('cursor','default');
    });
    $markingTextSVG.hover(function(){
        placeChangeColor('blue', $place, $markingTextSVG, elem.svgTokens);
        if($('#addTakeTokenRadio').is(':checked'))
            $('html,body').css('cursor','pointer');
    });
    $markingTextSVG.mouseout(function(){
        placeChangeColor('black', $place, $markingTextSVG, elem.svgTokens);
        if($('#addTakeTokenRadio').is(':checked'))
            $('html,body').css('cursor','default');
    });
    for(var i = 0; i < elem.svgTokens.length; i++){
        elem.svgTokens[i].mouseup(function(event){
            placeClick(elem, event, $place, $markingTextSVG, markingNode, $svgLabelRect, $svgName, labelNode);
        });
        elem.svgTokens[i].hover(function(){
            placeChangeColor('blue', $place, $markingTextSVG, elem.svgTokens);
            if($('#addTakeTokenRadio').is(':checked'))
                $('html,body').css('cursor','pointer');
        });
        elem.svgTokens[i].mouseout(function(){
            placeChangeColor('black', $place, $markingTextSVG, elem.svgTokens);
            if($('#addTakeTokenRadio').is(':checked'))
                $('html,body').css('cursor','default');
        });
    }
    $place.mouseup(function(event){
        placeClick(elem, event, $place, $markingTextSVG, markingNode, $svgLabelRect, $svgName, labelNode);
    });
    $markingTextSVG.mouseup(function(event){
        placeClick(elem, event, $place, $markingTextSVG, markingNode, $svgLabelRect, $svgName, labelNode);
    });

    return new PlaceObjects($place, $markingTextSVG, markingNode, $svgLabelRect,  $svgName, labelNode);
}

function placeChangeColor(color, place, markingTextSVG, svgTokensArr){
    place.attr('stroke', color);
    markingTextSVG.attr('fill', color);
    for(var i = 0; i < svgTokensArr.length; i++){
        if(svgTokensArr[i].attr('fill') !== 'white')
            svgTokensArr[i].attr('fill',color);
    }
}

function placeClick(elem, event, place, markingSVG, markingNode, svgLabelRect, svgName, labelNode){
    
    
    
    if($('#labelRadio').is(':checked')){

        popUpTask(labelText);

        var mypop = $('.popup');
        mypop.find('.popup-input').val(elem.label);
        mypop.find('.save').click(function(){
            var task = mypop.find('.popup-input').val().trim();

            if(task === '' || task === undefined)
                return;

            mypop.find('.cancel').trigger('click');

            if(task !== null || task !== undefined){

                elem.label = task;
                labelNode.nodeValue = elem.label;

                var textWidth = svgName.get(0).getBBox().width;
                var textHeight = svgName.get(0).getBBox().height;
                svgName.attr('x', elem.xPos - textWidth / 2);
                
                svgLabelRect.attr({
                        'x': elem.xPos - textWidth / 2 - 1,
                        'y': elem.yPos + size + 1,
                        'width' : textWidth + 2,
                        'height' : textHeight - 3
                });
            }
        });
        
    }
    
    if($('#moveRadio').is(':checked')){
            if(placeIsMoving === 0){
                placeIsMoving = 1;
                movedPlace = elem;
            }
        }
        
    if($("#removeRadio").is(":checked")){
        var index = placesArray.indexOf(elem);
        placesArray.splice(index, 1);
        place.remove();
        markingSVG.remove();
        svgName.remove();
        svgLabelRect.remove();
        for(var i = 0; i < elem.svgTokens.length; i++)
            elem.svgTokens[i].remove();

        for(var i = 0; i < arcs.length; i++){
            if(arcs[i].source === elem || arcs[i].target === elem){
                arcs[i].svgArcObjects.lineOfArc.remove();
                arcs[i].svgArcObjects.whiteLineOfArc.remove();
                if(arcs[i].type === "inhibitor")
                    arcs[i].svgArcObjects.circle.remove();
                else
                    arcs[i].svgArcObjects.arrow.remove();
                arcs[i].svgArcObjects.arcWeight.remove();
                arcs[i].svgArcObjects.arcWeightRect.remove();

                arcs.splice(i, 1);
                i--;
            }

        }
    }
    
    if($("#arcRadio").is(":checked") || $("#resetArcRadio").is(":checked")){
        if(arcIsDrawing === 0){
            sourceOfArc = elem;
            arcIsDrawing = 1;
            nowDrawedArc = SvgTmpArc(elem);
        }else{
            if(elem.type !== sourceOfArc.type){
                if($('#arcRadio').is(':checked')){
                    arcs.push(new Arc(sourceOfArc, elem, "regular"));
                }else if($("#resetArcRadio").is(":checked")){
                    arcs.push(new Arc(sourceOfArc, elem, "reset"));
                }
                topElemsLabels();
           }
        }
    }

    if($('#inhibitorArcRadio').is(':checked')){
        if(arcIsDrawing === 0){
            sourceOfArc = elem;
            arcIsDrawing = 1;
            nowDrawedArc = SvgTmpArc(elem);
        }else{
            if(elem.type !== sourceOfArc.type){
                arcs.push(new Arc(sourceOfArc, elem, "inhibitor"));
            }
            topElemsLabels();
        }
    }

    if($('#markRadio').is(':checked')){
        elem.addTokens();
    }
    
    if($('#addTakeTokenRadio').is(':checked')){
        if(event.which === 1){
            //left
            elem.marking++;
            elem.updateTokens();
        }else if(event.which === 3){
            //right
            if(elem.marking > 0){
                elem.marking--;
                elem.updateTokens();
            }else{
                //error message
            }
        }
    }

}

function PlaceObjects(svgPlace, svgMarking, markingNode, svgLabelRect, svgName, labelNode){
    this.svgPlace = svgPlace;
    this.svgMarking = svgMarking;
    this.markingNode = markingNode;
    this.svgLabelRect = svgLabelRect;
    this.svgName = svgName;
    this.nameNode = labelNode;
}

function Arc(source, target, type){
    this.id = IDOfElementCount++;
    this.type = type;
    this.source = source;
    this.target = target;
    this.weightLabel = "";
    this.weight = 1;
    this.arcPoints = getStartPoints(source, target);
    this.svgArcObjects = newSvgArc(this);
    
    this.updateArc = function(){
        //zisti prostrednu cast hrany
        var numOfPoints = this.arcPoints.length;
        var middle = Math.round((numOfPoints - 2) / 2);
        var cX, cY;
        
        this.arcPoints[0] = calculateStartOfArc(this.source, this.arcPoints[1]);
        this.arcPoints[numOfPoints - 1] = calculateEndOfArc( this.arcPoints[numOfPoints - 2], this.target);
        
        var points = "";
        //o jeden menej lebo posledny sa skracuje kvoli sipke
        for(var i = 0; i < numOfPoints - 1; i++)
            points += this.arcPoints[i].xPos + "," + this.arcPoints[i].yPos + " ";
        
        var shortenEnd = this.shortenArc();
        points += " " + shortenEnd.xPos + "," + shortenEnd.yPos;
        

        var arrowHeadPoints;
        if(this.type === "regular"){
            arrowHeadPoints = pointsOfArrowHead(this.arcPoints[numOfPoints - 2].xPos, this.arcPoints[numOfPoints - 2].yPos, 
                                                this.arcPoints[numOfPoints - 1].xPos, this.arcPoints[numOfPoints - 1].yPos);
        }else if(this.type === "reset"){
            arrowHeadPoints = pointsOfResetArrowHead(this.arcPoints[numOfPoints - 2].xPos, this.arcPoints[numOfPoints - 2].yPos, 
                                                this.arcPoints[numOfPoints - 1].xPos, this.arcPoints[numOfPoints - 1].yPos);
        }else if(this.type === "inhibitor"){
            var vect = new Vector(this.arcPoints[numOfPoints - 1].xPos - this.arcPoints[numOfPoints - 2].xPos,
                                  this.arcPoints[numOfPoints - 1].yPos - this.arcPoints[numOfPoints - 2].yPos);
            var shortenLen = vect.length() - inhibitorArcEndR - inhibitorArcOffset;
            var ratio = shortenLen / vect.length();
            cX = this.arcPoints[numOfPoints - 2].xPos + vect.xPos * ratio;
            cY = this.arcPoints[numOfPoints - 2].yPos + vect.yPos * ratio;
        }
            

        this.svgArcObjects.lineOfArc.attr('points', points);
        this.svgArcObjects.whiteLineOfArc.attr('points', points);
            
        if(this.type === "inhibitor"){
            this.svgArcObjects.circle.attr({
                'cx' : cX,
                'cy' : cY
            });
        }else{
           this.svgArcObjects.arrow.attr('points', arrowHeadPoints); 
        }
            
        var textHeight = this.svgArcObjects.arcWeight.get(0).getBBox().height;
        var textWidth = this.svgArcObjects.arcWeight.get(0).getBBox().width;
        //body hrany
        var weightPoint = getWeightPoint(this.arcPoints[middle], this.arcPoints[middle + 1]);
        this.svgArcObjects.arcWeight.attr({
            'x' : weightPoint.xPos - textWidth / 2,
            'y' : weightPoint.yPos - 4
        });

        this.svgArcObjects.arcWeightRect.attr({
            'x' : weightPoint.xPos - textWidth / 2 - 1,
            'y' : weightPoint.yPos - textHeight - 1
        });
    };
   
    this.move = function(x, y){
        var newPoint = new Point(x, y);
        // var textWidth = svgName.get(0).getBBox().width;
        var maxDistance = 5;
        var distance;
        var dotProd;
        var squareLen;
        var crossProd;

        //ak som na okraji kresliacej plochy tak sa zvacsi
        ExtendNetDrawAreaIfCan(x, y);
        if(findPoint === 0){
            
            //ak klikneme blizko breakPointu tak presuva ten point a nevytvara novy
            var indexOfPoint = this.moveCreatedBreakPoint(newPoint);
            
            if(indexOfPoint !== -1){
                //ak je blizko zlomu tak sa nastavy hodnota a pri dalsom update sa už posuva bod
                findPoint = 1;
                indexOfPointToChange = indexOfPoint;
                return;
            }
            //ak je daleko od breakPointu tak vytvori novy
            for(var i = 0; i < this.arcPoints.length - 1; i++){
                var x1 = this.arcPoints[i].xPos;
                var y1 = this.arcPoints[i].yPos;
                var x2 = this.arcPoints[i + 1].xPos;
                var y2 = this.arcPoints[i + 1].yPos;

                var dxL = x2 - x1, dyL = y2 - y1;  // line: vector from (x1,y1) to (x2,y2)
                var dxP = newPoint.xPos - x1, dyP = newPoint.yPos - y1;  // point: vector from (x1,y1) to (xp,yp)

                squareLen = dxL * dxL + dyL * dyL;  // squared length of line
                dotProd   = dxP * dxL + dyP * dyL;  // squared distance of point from (x1,y1) along line, skalárny súčin
                crossProd = dyP * dxL - dxP * dyL;  // area of parallelogram defined by line and point, vektorovy sucin

                // perpendicular distance of point from line
                distance = Math.abs(crossProd) / Math.sqrt(squareLen);
                if(distance <= maxDistance && dotProd >= 0 && dotProd <= squareLen){
                    findPoint = 1;
                    this.arcPoints.splice(i + 1, 0, newPoint);//insert new point to correct place
                    indexOfPointToChange = i + 1;
                    break;
                }
            }
        }else{
            this.arcPoints[indexOfPointToChange].xPos = Math.round(newPoint.xPos);
            this.arcPoints[indexOfPointToChange].yPos = Math.round(newPoint.yPos);
        }
        this.updateArc();
    };
    
    this.moveCreatedBreakPoint = function(newPoint){
        for(var i = 0; i < this.arcPoints.length; i++){
            var dx = this.arcPoints[i].xPos - newPoint.xPos;
            var dy = this.arcPoints[i].yPos - newPoint.yPos;
            var vect = new Vector(dx, dy);
            var maxDistance = 15;
            if(vect.length() <= maxDistance)
                return i;
        }
        return -1;
    };
    
    this.shortenArc = function(){
        var num = this.arcPoints.length - 2;
        var arcVect = new Vector(this.arcPoints[num + 1].xPos - this.arcPoints[num].xPos,
                                this.arcPoints[num + 1].yPos - this.arcPoints[num].yPos);
        var c = arcVect.length();
        var shorterC;
        
        if(this.type === "regular")
            shorterC = c - lengthOfArrowHead;
        else if(this.type === "reset")
            shorterC = c - lengthOfArrowHead*2;
        else if(this.type === "inhibitor")
            shorterC = c - inhibitorArcEndR*2;
        var ratio = shorterC / c;

        return new Point(this.arcPoints[num].xPos + arcVect.xPos * ratio,
                        this.arcPoints[num].yPos + arcVect.yPos * ratio);
    };
    
    this.removePointIfCan = function(mouseClickPoint){
        for(var i = 1; i < this.arcPoints.length - 1; i++){
            var dx = this.arcPoints[i].xPos - mouseClickPoint.xPos;
            var dy = this.arcPoints[i].yPos - mouseClickPoint.yPos;
            var vect = new Vector(dx, dy);
            var maxDistance = 10;
            
            if(vect.length() <= maxDistance){
                this.arcPoints.splice(i, 1);
                return true;
            }
        }
        return false;
    };
    
    this.alignArcIfCan = function(){
        for(var i = 1; i < this.arcPoints.length - 1; i++){
            //vzdialenost bodov (i - 1) a (i + 1)
            var distantPointsVector = new Vector(this.arcPoints[i + 1].xPos - this.arcPoints[i - 1].xPos,
                                    this.arcPoints[i + 1].yPos - this.arcPoints[i - 1].yPos);
            //vzdialenost bodov (i - 1) a i 
            var neighbourVector1 = new Vector(this.arcPoints[i].xPos - this.arcPoints[i - 1].xPos,
                                    this.arcPoints[i].yPos - this.arcPoints[i - 1].yPos);
            //vzdialenost bodov i a (i + 1) 
            var neighbourVector2 = new Vector(this.arcPoints[i + 1].xPos - this.arcPoints[i].xPos,
                                    this.arcPoints[i + 1].yPos - this.arcPoints[i].yPos);
            var reserve = 1.5;
            //je bod zarovno(s rezervou) s jeho susedmi tak sa odstáni a hrana sa medzi susedmi vyrovná
            if(Math.abs(distantPointsVector.length() - neighbourVector1.length() - neighbourVector2.length()) <= reserve){
                this.arcPoints.splice(i, 1);
                //pre test kazdeho bodu
                i--;
            }
        }
        this.updateArc();
    };
    
}

function newSvgArc(elem){
    var $whiteLineOfArc = $(SVG("polyline"));
    var $lineOfArc = $(SVG("polyline"));
    var startPoint = elem.arcPoints[0];
    var endPoint = elem.arcPoints[1];
   //skrati ciaru o dlzku sipky
    var vect = new Vector(endPoint.xPos - startPoint.xPos, endPoint.yPos - startPoint.yPos);
    var shortenLen = vect.length() - lengthOfArrowHead;
    var ratio = shortenLen / vect.length();
    var sX = startPoint.xPos + vect.xPos * ratio;
    var sY = startPoint.yPos + vect.yPos * ratio;
                  
    var points = startPoint.xPos + "," + startPoint.yPos + " " + sX + "," + sY;
    
    $lineOfArc.attr({
        "points" : points,
        "fill" : "none",
        "stroke-width" : 2,
        "stroke" : "black"
    });
    
    $whiteLineOfArc.attr({
        "points" : points,
        "fill" : "none",
        "stroke-width" : 4,
        "stroke" : "white"
    });
   
    $whiteLineOfArc.appendTo($("#netDrawArea"));
    $lineOfArc.appendTo($("#netDrawArea"));
    
    var $arrow = null;
    var $circle = null;
    if(elem.type === "inhibitor"){
        //presne nastavi polohu kruhu na konci hrany
        $circle = $(SVG('circle'));
        var vect = new Vector(endPoint.xPos - startPoint.xPos, endPoint.yPos - startPoint.yPos);
        var shortenLen = vect.length() - inhibitorArcEndR - inhibitorArcOffset;
        var ratio = shortenLen / vect.length();
        $circle.attr({
            'cx': startPoint.xPos + vect.xPos * ratio,
            'cy': startPoint.yPos + vect.yPos * ratio,
            'r' : inhibitorArcEndR,
            'fill' : 'white',
            'stroke' : 'black',
            'stroke-width' : 2
        });
        $circle.appendTo($('#netDrawArea'));
    }else{

        $arrow = $(SVG('polygon'));
        var points;
        if(elem.type === "regular")
            points = pointsOfArrowHead(startPoint.xPos, startPoint.yPos, endPoint.xPos, endPoint.yPos);
        else if(elem.type === "reset")
            points = pointsOfResetArrowHead(startPoint.xPos, startPoint.yPos, endPoint.xPos, endPoint.yPos);

        $arrow.attr({
            'points' : points ,
            'stroke' : 'black',
            'fill' : 'black'
        });

        $arrow.appendTo($("#netDrawArea"));
    }
    var weightPoint = getWeightPoint(startPoint, endPoint);
    var $arcWeightRect = $(SVG('rect'));
    $arcWeightRect.attr({
        'x' : weightPoint.xPos,
        'y' : weightPoint.yPos,
        //'stroke' : 'black',
        'opacity': 0.6,
        'stroke-width' : 1,
        'fill' : 'white'
    });
    $arcWeightRect.appendTo($("#netDrawArea"));
    
    var $arcWeightSVG = $(SVG('text'));
    $arcWeightSVG.attr({
        'font-family' : 'verdana',
        'font-weight': 'bold',
        'font-size' : fontSize
    });
                                                                                              
    var arcWeightNode = document.createTextNode(elem.weightLabel);
    $arcWeightSVG.append(arcWeightNode);
    $arcWeightSVG.appendTo($("#netDrawArea"));
    
    var textWidth = $arcWeightSVG.get(0).getBBox().width;
    var textHeight = $arcWeightSVG.get(0).getBBox().height;
    
    $arcWeightRect.attr({
        'x': weightPoint.xPos - textWidth / 2 - 1,
        'y': weightPoint.yPos - textHeight + 3,
        'width' : textWidth + 2,
        'height': textHeight
    });
    
    // aby bol zacatok sipky pod objektom (vzhlad)
    elem.source.topElem();
    
    //ked je hrana inh. tak sa meni farba kluzku a sipka nieje
    if(elem.type === "inhibitor"){
        $whiteLineOfArc.hover(function(){
            $lineOfArc.attr('stroke', 'blue');
            $circle.attr('stroke', 'blue');
        });
        $whiteLineOfArc.mouseout(function(){
            $lineOfArc.attr('stroke', 'black');
            $circle.attr('stroke', 'black');
        });
        $lineOfArc.hover(function(){
            $lineOfArc.attr('stroke', 'blue');
            $circle.attr('stroke', 'blue');
        });
        $lineOfArc.mouseout(function(){
            $lineOfArc.attr('stroke', 'black');
            $circle.attr('stroke', 'black');
        });
    }else{
        $whiteLineOfArc.hover(function(){
            $lineOfArc.attr('stroke', 'blue');
            $arrow.attr({'stroke' : 'blue', 'fill' : 'blue'});
        });
        $whiteLineOfArc.mouseout(function(){
            $lineOfArc.attr('stroke', 'black');
            $arrow.attr({'stroke' : 'black', 'fill' : 'black'});
        });
        $lineOfArc.hover(function(){
            $lineOfArc.attr('stroke', 'blue');
            $arrow.attr({'stroke' : 'blue', 'fill' : 'blue'});
        });
        $lineOfArc.mouseout(function(){
            $lineOfArc.attr('stroke', 'black');
            $arrow.attr({'stroke' : 'black', 'fill' : 'black'});
        });
    }

    if(elem.type === "inhibitor"){
        $circle.hover(function(){
            $lineOfArc.attr('stroke', 'blue');
            $circle.attr('stroke', 'blue');
        });
        $circle.mouseout(function(){
           $lineOfArc.attr('stroke', 'black');
           $circle.attr('stroke', 'black');
       });
       
    }else{
        $arrow.hover(function(){
            $lineOfArc.attr('stroke', 'blue');
            $arrow.attr({
                'stroke' : 'blue',
                'fill' : 'blue'            
            });
        });
        $arrow.mouseout(function(){
            $lineOfArc.attr('stroke', 'black');
            $arrow.attr({
                'stroke' : 'black',
                'fill' : 'black'            
            });
        });
    }
    $whiteLineOfArc.click(function(e){
        arcClick(e, elem, $whiteLineOfArc, $lineOfArc , $arrow, $circle, $arcWeightSVG, arcWeightNode, $arcWeightRect);
    });
    $lineOfArc.click(function(e){
        arcClick(e, elem, $whiteLineOfArc, $lineOfArc , $arrow, $circle, $arcWeightSVG, arcWeightNode, $arcWeightRect);
    });
    if(elem.type === "inhibitor"){
        $circle.click(function(e){
            arcClick(e, elem, $whiteLineOfArc, $lineOfArc , $arrow, $circle, $arcWeightSVG, arcWeightNode, $arcWeightRect);
        });
    }else{
        $arrow.click(function(e){
           arcClick(e, elem, $whiteLineOfArc, $lineOfArc , $arrow, $circle, $arcWeightSVG, arcWeightNode, $arcWeightRect);
        });
    }
    $arcWeightRect.click(function(e){
        arcClick(e, elem, $whiteLineOfArc, $lineOfArc , $arrow, $circle, $arcWeightSVG, arcWeightNode, $arcWeightRect);
    });
    $arcWeightSVG.click(function(e){
        arcClick(e, elem, $whiteLineOfArc, $lineOfArc , $arrow, $circle, $arcWeightSVG, arcWeightNode, $arcWeightRect);
    });
    
    return new svgArcObjects($whiteLineOfArc, $lineOfArc, $arrow, $circle, $arcWeightSVG, arcWeightNode, $arcWeightRect);
}

function arcClick(event, elem, whiteLineOfArc, lineOfAcr , arrow, circle,  arcWeightSVG, arcWeightNode, arcWeightRect){

    var startPoint = elem.arcPoints[0];
    var endPoint = elem.arcPoints[1];
    var weightPoint = getWeightPoint(startPoint, endPoint);
    
    var offset = $("#netDrawArea").offset();
    var x = event.pageX - offset.left;
    var y = event.pageY - offset.top;
    
    if($('#moveRadio').is(':checked') && arcIsMoving === 0){
        arcIsMoving = 1;
        movedArc = elem;
    }
        
    if($('#removeRadio').is(':checked')){
        //ak je klik blizko vymaze sa bod a vrati true inak false
        if(elem.removePointIfCan(new Point(x, y))){
            //updatne archu aby sa nactalo pole po vymazani bodu a prekreslila sa 
            elem.updateArc();
        }else{
            lineOfAcr.remove();
            whiteLineOfArc.remove();
            if(elem.type === "inhibitor")
                circle.remove();
            else
                arrow.remove();
            arcWeightSVG.remove();
            arcWeightRect.remove();
            var index = arcs.indexOf(elem);
            arcs.splice(index, 1);
        }
    }

    if($('#arcWeightRadio').is(':checked') && elem.type !== "reset"){
        popUpTask(arcWeightText);

        var mypop = $('.popup');
        mypop.find('.popup-input').val(elem.weight);
        mypop.find('.save').click(function(){
            var task = mypop.find('.popup-input').val().trim();
            var weightInt = parseInt(task);
            
            mypop.find('.cancel').trigger('click');

            if(task !== '' &&  weightInt > 0){

                if(weightInt === 1)
                    elem.weightLabel = "";
                else
                    elem.weightLabel = weightInt;
    
                elem.weight = weightInt;
                arcWeightNode.nodeValue = elem.weightLabel;
    
                var textWidth = arcWeightSVG.get(0).getBBox().width;
                var textHeight = arcWeightSVG.get(0).getBBox().height;
                if(textHeight === 0) textHeight = 2;
                
                arcWeightRect.attr({
                    'x': weightPoint.xPos - textWidth / 2 - 1,
                    'y': weightPoint.yPos - textHeight ,
                    'width' : textWidth + 2,
                    'height' : textHeight - 2
                });
                
                arcWeightSVG.attr({
                    'x' : weightPoint.xPos - textWidth / 2,
                    'y' : weightPoint.yPos - 4
                });
            }
        }); 
    }
}

function svgArcObjects(whiteLineOfArc, lineOfArc, arrow, circle, arcWeight, arcWeightNode, arcWeightRect){
    this.whiteLineOfArc = whiteLineOfArc;
    this.lineOfArc = lineOfArc;
    this.arrow = arrow;
    this.circle = circle;
    this.arcWeight = arcWeight;
    this.arcWeightNode = arcWeightNode;
    this.arcWeightRect = arcWeightRect;
}

function getWeightPoint(start, end){
    var x = (start.xPos + end.xPos) / 2;
    var y = (start.yPos + end.yPos) / 2;
    
    return new Point(x, y);
}

function getStartPoints(source, target){
    
    var arcPoints = [];
    arcPoints.push(calculateStartOfArc(source, target));
    arcPoints.push(calculateEndOfArc(source, target));

    return arcPoints;
}

function calculateStartOfArc(startElem, endElem){
    
    var startX = startElem.xPos;
    var startY = startElem.yPos;
    var endX = endElem.xPos;
    var endY = endElem.yPos;
    var xLenght = endX - startX;
    var yLenght = endY - startY;
 
    var c = Math.sqrt(xLenght*xLenght + yLenght*yLenght);
    var alpha = toDegrees(Math.asin(yLenght / c));
    
    if(startElem.type === "place"){
        var phi = Math.atan2(yLenght, xLenght);
        return new Point(startX + size * Math.cos(phi), startY + size * Math.sin(phi));
    }
    
    if(startElem.type === "transition"){
        
        if(startX > endX){
            
            if(Math.abs(alpha) <= 45){
                
                var x = startX - size;
                var y = startY + Math.tan(toRadians(alpha)) * size;
                
                return new Point(x, y);
            }else{
               
                if(startY >= endY){
                    console.log(alpha);
                    var x = startX + size / Math.tan(toRadians(alpha));
                    var y = startY - size;
                }else{
                    var x = startX - size / Math.tan(toRadians(alpha));
                    var y = startY + size;
                }
                return new Point(x, y);
            }
        }else{
            if(Math.abs(alpha) >= 45){
                 
                if(startY > endY){
                    var x = startX - size / Math.tan(toRadians(alpha));
                    var y = startY - size;
                }else{
                    var x = startX + size / Math.tan(toRadians(alpha));
                    var y = startY + size;
                }
                return new Point(x, y);
            }else{
                var x = startX + size;
                var y = startY + size * Math.tan(toRadians(alpha));
                
                return new Point(x, y);
            }
        }
    }
    
}

function calculateEndOfArc(startElem, endElem){
    var startX = startElem.xPos;
    var startY = startElem.yPos;
    var endX = endElem.xPos;
    var endY = endElem.yPos;
    var xLenght = endX - startX;
    var yLenght = endY - startY;
    
    var c = Math.sqrt(xLenght*xLenght + yLenght*yLenght);
    var alpha = toDegrees(Math.asin(yLenght / c));

    if(endElem.type === "place"){
        var phi = Math.atan2(yLenght, xLenght);
        return new Point(endX - size * Math.cos(phi), endY - size * Math.sin(phi));
    }
    if(endElem.type === "transition"){
        if(startX > endX){
            if(Math.abs(alpha) <= 45){
                var x = endX + size;
                var y = endY - Math.tan(toRadians(alpha)) * size;
                
                return new Point(x, y);
            }else{
                if(startY >= endY){
                    var x = endX - size / Math.tan(toRadians(alpha));
                    var y = endY + size;
                }else{
                    var x = endX + size / Math.tan(toRadians(alpha));
                    var y = endY - size;
                }
                return new Point(x, y);
            }
        }else{
            if(Math.abs(alpha) >= 45){
                if(startY > endY){
                    var x = endX + size / Math.tan(toRadians(alpha));
                    var y = endY + size;
                }else{
                    var x = endX - size / Math.tan(toRadians(alpha));
                    var y = endY - size;
                }
                return new Point(x, y);
            }else{
                var x = endX - size;
                var y = endY - size * Math.tan(toRadians(alpha));
                
                return new Point(x, y);
            }
        }
    }
}

function SvgTmpArc(elem){
    var $arrow = null;
    var $circle = null;
    var $lineOfArc = $(SVG("polyline"));
    var points = elem.xPos + "," + elem.yPos + " " + elem.xPos + "," + elem.yPos;
    $lineOfArc.attr({
        "points" : points,
        "fill" : "none",
        "stroke-width" : 2,
        "stroke" : "white"
    });
    
    
    $lineOfArc.appendTo($("#netDrawArea"));
        
    if($('#inhibitorArcRadio').is(':checked')){
        $circle = $(SVG('circle'));
        $circle.attr({
            'cx' : elem.xPos +5,
            'cy' : elem.yPos + 5,
            'r' :  inhibitorArcEndR,
            'fill' : 'white',
            'stroke': 'blue',
            'stroke-width': 2
        });
        $circle.appendTo($('#netDrawArea'));
    }else{
        $arrow = $(SVG('polygon'));
        var arrowPoints;
        if($('#arcRadio').is(':checked'))
            arrowPoints = pointsOfArrowHead(elem.xPos, elem.yPos, elem.xPos + 5, elem.yPos + lengthOfArrowHead);
        else if($('#resetArcRadio').is(':checked'))
            arrowPoints = pointsOfResetArrowHead(elem.xPos, elem.yPos, elem.xPos, elem.yPos + lengthOfArrowHead * 2);
        $arrow.attr({
            'points' : arrowPoints,
            'stroke' : 'blue',
            'fill' : 'blue'
        });

        $arrow.appendTo($("#netDrawArea"));
    }

    
    return new ObjectsOfTmpArc($lineOfArc, $arrow, $circle);
}

function ObjectsOfTmpArc(line, arrowHead, circle){
    this.line = line;
    this.arrowHead = arrowHead;
    this.circle = circle;
}

function pointsOfArrowHead(xStart, yStart, xEnd, yEnd){
    var arrowHeadLen = lengthOfArrowHead;
    var halfWind = lengthOfArrowHead * 0.5;
    //http://stackoverflow.com/questions/10316180/how-to-calculate-the-coordinates-of-a-arrowhead-based-on-the-arrow
    var direction = new Vector(xEnd - xStart, yEnd - yStart);
    var unitDir = new Vector(direction.xPos / direction.length(), direction.yPos / direction.length());// nesie info len o smere
    var normalVec = unitDir.perpendicular();
    
    var v1 = new Vector(xEnd - arrowHeadLen * unitDir.xPos + halfWind * normalVec.xPos,
                        yEnd - arrowHeadLen * unitDir.yPos + halfWind * normalVec.yPos);
                        
    var v2 = new Vector(xEnd - arrowHeadLen * unitDir.xPos - halfWind * normalVec.xPos,
                        yEnd - arrowHeadLen * unitDir.yPos - halfWind * normalVec.yPos);
                        
                      
    return xEnd + "," + yEnd + " " + v1.xPos + "," + v1.yPos + " " + v2.xPos + "," + v2.yPos;

}

function pointsOfResetArrowHead(xStart, yStart, xEnd, yEnd){
    var arrowHeadLen = lengthOfArrowHead;
    var halfWind = lengthOfArrowHead * 0.5;
    /*
     * skrati sa ciara o velkost hlavicky sEnd...
     * a pocitaju sa ako dve odlisne hlavicky
     */
    var vect = new Vector(xEnd - xStart, yEnd - yStart);
    var shortenLen = vect.length() - lengthOfArrowHead;
    var ratio = shortenLen / vect.length();
    var sEndX = xStart + vect.xPos * ratio;
    var sEndY = yStart + vect.yPos * ratio;

    var direction = new Vector(sEndX - xStart, sEndY - yStart);

    var unitDir = new Vector(direction.xPos / direction.length(), direction.yPos / direction.length());
    var normalVec = unitDir.perpendicular();

    var v3 = new Vector(sEndX - arrowHeadLen * unitDir.xPos + halfWind * normalVec.xPos,
                        sEndY - arrowHeadLen * unitDir.yPos + halfWind * normalVec.yPos);

    var v4 = new Vector(sEndX - arrowHeadLen * unitDir.xPos - halfWind * normalVec.xPos,
                        sEndY - arrowHeadLen * unitDir.yPos - halfWind * normalVec.yPos);
 
    
    direction = new Vector(xEnd - xStart, yEnd - yStart);
    unitDir = new Vector(direction.xPos / direction.length(), direction.yPos / direction.length());
    normalVec = unitDir.perpendicular();
    
    var v1 = new Vector(xEnd - arrowHeadLen * unitDir.xPos + halfWind * normalVec.xPos,
                        yEnd - arrowHeadLen * unitDir.yPos + halfWind * normalVec.yPos);
                        
    var v2 = new Vector(xEnd - arrowHeadLen * unitDir.xPos - halfWind * normalVec.xPos,
                        yEnd - arrowHeadLen * unitDir.yPos - halfWind * normalVec.yPos);
                        
    
    return  v4.xPos + "," + v4.yPos + " " + v3.xPos + "," + v3.yPos + " " + sEndX + "," + sEndY + " " +
            v1.xPos + "," + v1.yPos + " " +xEnd + "," + yEnd + " " + v2.xPos + "," + v2.yPos + " " + sEndX + "," + sEndY + " " +
            v4.xPos + "," + v4.yPos;
}

function exportAsXML(){
    //aj tu asi popup okno
  //  var task = prompt("Filne name", "newNet.xml");
    popUpTask(saveXMLText);

    var mypop = $('.popup');
    mypop.find('.popup-input').val("newNet.xml");
    mypop.find('.save').click(function(){
        var task = mypop.find('.popup-input').val().trim();
        
        if(task === '' || task === undefined)
            return;

        mypop.find('.cancel').trigger('click');

        if(task !== null || task !== undefined){

            nazovSuboru = task;
            saveXML(task);
        }
    });

}

function saveXML(fileName){
    var xml = createXML();
    var blob = new Blob([xml], {type: 'text/xml'});

    if(window.navigator.msSaveOrOpenBlob) {
        window.navigator.msSaveBlob(blob, nazovSuboru);
    }
    else{
        var a = window.document.createElement('a');
        a.href = window.URL.createObjectURL(blob);
        a.download = nazovSuboru;        
        document.body.appendChild(a);
        a.click();        
        document.body.removeChild(a);
    }

 //   postJSonData(nazovSuboru);
    
}

function saveAsSVG(){
    popUpTask(saveSVGText);
    
    var svgName;
    var mypop = $('.popup');
    mypop.find('.popup-input').val("svg");
    mypop.find('.save').click(function(){
        var task = mypop.find('.popup-input').val().trim();

        if(task === '' || task === undefined)
            return;

        mypop.find('.cancel').trigger('click');

        if(task !== null || task !== undefined)
            svgName = task;
        else
            svgName = "PNsvg";
        
        var source = createSVGSource();
        //convert svg source to URI data scheme.
        var url = "data:image/svg+xml;charset=utf-8," + encodeURIComponent(source);
        //set url value to a element's href attribute.

        var link = window.document.createElement('a');
        link.href = url;
        link.download = svgName;
        $('body').append(link);
        link.click();
        $('a').remove();
    });
}

function createXML(){
    var xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<document>\n";
    for(var i = 0; i < placesArray.length; i++){
        xml += "    <place>\n";
        xml += "        <id>" + placesArray[i].id + "</id>\n";
        xml += "        <x>" + placesArray[i].xPos + "</x>\n";
        xml += "        <y>" + placesArray[i].yPos + "</y>\n";
        xml += "        <label>"+ placesArray[i].label + "</label>\n";
        xml += "        <tokens>" + placesArray[i].marking + "</tokens>\n";
        xml += "    </place>\n";
    }
    for(var i = 0; i < transitonsArray.length; i++){
        xml += "    <transition>\n";
        xml += "        <id>" + transitonsArray[i].id + "</id>\n";
        xml += "        <x>" + transitonsArray[i].xPos + "</x>\n";
        xml += "        <y>" + transitonsArray[i].yPos + "</y>\n";
        xml += "        <label>"+ transitonsArray[i].label + "</label>\n";
        xml += "    </transition>\n";
    }
    for(var i = 0; i < arcs.length; i++){
        xml += "    <arc>\n";
        xml += "        <id>" + arcs[i].id + "</id>\n";
        xml += "        <type>" + arcs[i].type + "</type>\n";
        xml += "        <sourceId>" + arcs[i].source.id + "</sourceId>\n";
        xml += "        <destinationId>" + arcs[i].target.id + "</destinationId>\n";
        xml += "        <multiplicity>"+ arcs[i].weight + "</multiplicity>\n";
        for(var j = 1; j <= arcs[i].arcPoints.length - 2; j++){
            xml += "        <breakPoint>" + 
                    "<x>" + arcs[i].arcPoints[j].xPos + "</x>" +
                    "<y>" + arcs[i].arcPoints[j].yPos + "</y>" + 
                    "</breakPoint>\n";
        }
        xml += "    </arc>\n";
    }
    xml += "    <description>" + description + "</description>\n";
    xml += "</document>";
    
    return xml;
}

function openFile(event){
    deleteAll();
    var file = event.target.files[0];

    
    var reader = new FileReader();
    reader.onload = function(){
        importNet($.parseXML(reader.result));
    };
    reader.readAsText(file);
}

function deleteAll(){
    $('#inportButton').replaceWith($('#inportButton').clone());//remove file input
    
    IDOfElementCount = 0;
   
    transitonsArray.length = 0;
    placesArray.length = 0;
    arcs.length = 0;
    description = "";
    
    $('#netDrawArea').empty();
    //
    $('#netDrawArea').width('99%');
    $('#netDrawArea').height('99%');
    
}   

function importNet(xml){
    
    var offsetX = 0;
    var offsetY = 0;
 
    $(xml).find('x').each(function(){
        var x = parseInt($(this).text());
        if(x < offsetX)
            offsetX = x;
    });
    
    $(xml).find('y').each(function(){
        var y = parseInt($(this).text());
        if(y < offsetY)
            offsetY = y;
    });
    if(offsetX !== 0)
        offsetX -= 40;
    if(offsetY !== 0)
        offsetY -= 40;
        
    $(xml).find('transition').each(function(){
        var xPos = parseInt($(this).find('x').text()) - offsetX;
        var yPos = parseInt($(this).find('y').text()) - offsetY;
        var newTran = new Transition(xPos, yPos);
        
        newTran.label = $(this).find('label').text();
        newTran.svgObjects.labelNode.nodeValue = newTran.label;
        
        var textWidth = newTran.svgObjects.svgName.get(0).getBBox().width;
        var textHeight = newTran.svgObjects.svgName.get(0).getBBox().height;
        //ked nieje label tak sirka/ vyska bude isto nula :}
        if(textHeight !== 0 || textWidth !== 0){
            newTran.svgObjects.svgName.attr('x', newTran.xPos - textWidth / 2);

            newTran.svgObjects.svgLabelRect.attr({
                'x': newTran.xPos - textWidth / 2 - 1,
                'y': newTran.yPos + size +1,
                'width': textWidth + 2,
                'height': textHeight - 3
            });
        }
        newTran.id = parseInt($(this).find('id').text());
        newTran.svgObjects.svgTransition.attr('id', newTran.id);
        if(IDOfElementCount < newTran.id)
            IDOfElementCount = newTran.id;

        transitonsArray.push(newTran);
    });
    
    $(xml).find('place').each(function(){
        var xPos = parseInt($(this).find('x').text()) - offsetX;
        var yPos = parseInt($(this).find('y').text()) - offsetY;
        var newPlace = new Place(xPos, yPos);
        
        newPlace.label = $(this).find('label').text();
        newPlace.svgObjects.nameNode.nodeValue = newPlace.label;
        
        var textWidth = newPlace.svgObjects.svgName.get(0).getBBox().width;
        var textHeight = newPlace.svgObjects.svgName.get(0).getBBox().height;
        //ked nieje label tak sirka/ vyska bude isto nula :}
        if(textHeight !== 0 || textWidth !== 0){
            newPlace.svgObjects.svgName.attr('x', newPlace.xPos - textWidth / 2);

            newPlace.svgObjects.svgLabelRect.attr({
                'x': newPlace.xPos - textWidth / 2 - 1,
                'y': newPlace.yPos + size +1,
                'width': textWidth + 2,
                'height': textHeight - 3
            });
        }
        var tokens = parseInt($(this).find('tokens').text());
        newPlace.marking = tokens;
        newPlace.updateTokens();
        newPlace.updateSvgTokensPosition();
        
        newPlace.id = parseInt($(this).find('id').text());
        newPlace.svgObjects.svgPlace.attr('id', newPlace.id);
        if(IDOfElementCount < newPlace.id)
            IDOfElementCount = newPlace.id;

        placesArray.push(newPlace);
    });
    
    $(xml).find('arc').each(function(){
        var source;
        var target;
        var sourceId = parseInt($(this).find('sourceId').text());
        var targetId = parseInt($(this).find('destinationId').text());
        
        for(var i = 0; i < transitonsArray.length; i++){
            if(sourceId === transitonsArray[i].id){
                source = transitonsArray[i];
                break;
            }else if(targetId === transitonsArray[i].id){
                target = transitonsArray[i];
                break;
            }
        }
        for(var i = 0; i < placesArray.length; i++){
            if(sourceId === placesArray[i].id){
                source = placesArray[i];
                break;
            }else if(targetId === placesArray[i].id){
                target = placesArray[i];
                break;
            }
        }
        var type = $(this).find('type').text();
        
        if(type === "")
            type = "regular";

        var newArc = new Arc(source, target, type);
        
        $(this).find('breakPoint').each(function(){
            var x = parseInt($(this).find('x').text()) - offsetX;
            var y = parseInt($(this).find('y').text()) - offsetY;
            newArc.arcPoints.splice(newArc.arcPoints.length - 1, 0,new Point(x, y));
        });
        
        var weightInt = parseInt($(this).find('multiplicity').text());
        newArc.weight = weightInt;
        if(weightInt > 1){
      
            newArc.svgArcObjects.arcWeightNode.nodeValue = newArc.weight;

            var textWidth = newArc.svgArcObjects.arcWeight.get(0).getBBox().width;
            var textHeight = newArc.svgArcObjects.arcWeight.get(0).getBBox().height;

            var numOfPoints = newArc.arcPoints.length;
            var middle = Math.round((numOfPoints - 2) / 2);

            var weightPoint = getWeightPoint(newArc.arcPoints[middle], newArc.arcPoints[middle + 1]);

            newArc.svgArcObjects.arcWeightRect.attr({
                'x': weightPoint.xPos - textWidth / 2 + 1,
                'y': weightPoint.yPos - textHeight + 3,
                'width' : textWidth + 2,
                'height' : textHeight
            });


            newArc.svgArcObjects.arcWeight.attr({
                'x' : weightPoint.xPos - textWidth / 2,
                'y' : weightPoint.yPos
            });
        }  
        var id = parseInt($(this).find('id').text());
        
        if(id !== 'NaN'){
            newArc.id = id;

            if(IDOfElementCount < newArc.id)
                IDOfElementCount = newArc.id;
        }else{
            newArc.id = -1;
        }
        arcs.push(newArc);
        
        
    });
    // treba pekne zarovnat :)
    for(var i = 0; i < arcs.length; i++)
        arcs[i].updateArc();
    
    description = $(xml).find('description').text();
    
    topElemsLabels();
    
}

function clearNetArea(){
    popUpConfirm(clearNetText);

    var mypop = $('.popup');
    mypop.find('.save').click(function(){
        deleteAll();
        mypop.find('.cancel').trigger('click');

    }); 
}

function describeNet(){
    popUpTask(nescribeNetText);
    
    var mypop = $('.popup');
    mypop.find('.popup-input').val(description);
    mypop.find('.save').click(function(){
        var task = mypop.find('.popup-input').val().trim();
        mypop.find('.cancel').trigger('click');
        if(task !== null || task !== undefined){
            description = task;
        }
    });
}

function popUpTask(mesg){
    var popHtml = '<div class="popup">'+
                        '<div class="question">' + mesg + '</div> ' + 
                        '<input type="text" class="popup-input" id="popupInputID">' +
                        '<div class="close"><i class="icon icon-cross"></i></div>' +
                        '<div class="button-group">' +
                            '<div class="cancel">CANCEL</div>' +
                            '<div class="save">SAVE</div>' +
                        '</div>' +
                   '</div>';
           
    popUpWindow(popHtml);
}

function popUpConfirm(mesg){
    var confirmHhml = '<div class="popup">'+
                        '<div class="question">' + mesg + '</div> ' + 
                        '<div class="close"><i class="icon icon-cross"></i></div>' +
                        '<div class="button-group">' +
                            '<div class="save">YES</div>' +
                            '<div class="cancel">NO</div>' +
                        '</div>' +
                   '</div>';
    popUpWindow(confirmHhml);   
}

function popUpDropDown(mesg){
    var dropDownHhml = '<div class="popup">'+
                        '<div class="question">' + mesg + '</div> ' + 
                        '<div class="close"><i class="icon icon-cross"></i></div>' +
                        '<select class="popup-input">' +
                        '</select>' + 
                        '<div class="button-group">' +
                            '<div class="save">OK</div>' +
                            '<div class="cancel">CANCEL</div>' +
                        '</div>' +
                   '</div>';
    popUpWindow(dropDownHhml);
    
}

function popUpWindow(htmlString){
    if($('.popup').length !== 0){
        return;
    }
    
    var mypop = $(htmlString);
    $( "body" ).append(mypop);
    
    mypop.show(200,function(){
        $('.container-fluid').addClass('blur');
        //aby sa dalo hned pisat pri vybehnuti label popup
        $("#popupInputID").focus();
    });
    
    mypop.find('.close').add(mypop.find('.cancel')).on('click',function(ev){
        
        $(ev.target).closest('.popup').hide(200,function(){
            $('.popup').remove();
            $('.container-fluid').removeClass('blur');
        });
    });
}

function getJSon(fileName){
    var obj = {
        places:[],
        transitions: [],
        arcs: [],
        
        xml_name: fileName,
        description: description
    };
    
    for(var i = 0; i < placesArray.length; i++){
        obj.places.push({
            'id_in_xml': placesArray[i].id,
            'name': placesArray[i].label,
            'initial_marking': placesArray[i].marking
        });
    }
    
    for(var i = 0; i < transitonsArray.length; i++){
        obj.transitions.push({
            'id_in_xml': transitonsArray[i].id,
            'name': transitonsArray[i].label
        });
    }
    
    for(var i = 0; i < arcs.length; i++){
        obj.arcs.push({
            'id_in_xml': arcs[i].id,
            'type': arcs[i].type,
            'sourceId': arcs[i].source.id,
            'destinationId': arcs[i].target.id,
            'weight' : arcs[i].weight
        });
    }
    
    
    return JSON.stringify(obj);
}

function postJSonData(nazovSuboru){
    
    var jSon = getJSon(nazovSuboru);
    $.ajax({
       type : 'POST',
       url : 'neviem.php',
       data : {
           tvojePremennaVPoste : jSon
       },
       dataType : 'json',
       success: function(){
           console.log("json send OK");
       },
       error : function(){
           console.log("json send ERROR");
       }
    });
    
}

function createSVGSource(){
    var svg = document.getElementById("netDrawArea");
    var serializer = new XMLSerializer();
    var source = serializer.serializeToString(svg);
    var maxXY = getMaxXY();
    if(!source.match(/^<svg[^>]+xmlns="http\:\/\/www\.w3\.org\/2000\/svg"/)){
        source = source.replace(/^<svg/, '<svg viewBox="0 0 {' + (maxXY.xPos + size + 2) + '}{' + (maxXY.yPos + size  + 2) +'}" xmlns="http://www.w3.org/2000/svg"');
    }
    if(!source.match(/^<svg[^>]+"http\:\/\/www\.w3\.org\/1999\/xlink"/)){
        source = source.replace(/^<svg/, '<svg width="' + (maxXY.xPos + size + 2) + '" height="' + (maxXY.yPos + size  + 2) +'" xmlns:xlink="http://www.w3.org/1999/xlink"');
    }
    /*viewBox='0 0 {X} {Y}'*/
    source = '<?xml version="1.0" standalone="no"?>\r\n' + source;
    
    return source;
}

function getMaxXY(){
    var point = new Point(0, 0);
    for(var i = 0; i < placesArray.length; i++){
        if(placesArray[i].xPos > point.xPos){
            //ak je siroky label
            var textWidth = placesArray[i].svgObjects.svgName.get(0).getBBox().width;
            point.xPos = placesArray[i].xPos + textWidth / 2;
            
        }
        if(placesArray[i].yPos > point.yPos){
            point.yPos = placesArray[i].yPos;
        }
    }
    
    for(var i = 0; i < transitonsArray.length; i++){
        if(transitonsArray[i].xPos > point.xPos){
            var textWidth = transitonsArray[i].svgObjects.svgName.get(0).getBBox().width;
            point.xPos = transitonsArray[i].xPos + textWidth / 2;
        }
        if(transitonsArray[i].yPos > point.yPos)
            point.yPos = transitonsArray[i].yPos;
    }
    
    for(var i = 0; i < arcs.length; i++){
        for(var j = 0; j < arcs[i].arcPoints.length; j++){
            if(arcs[i].arcPoints[j].xPos > point.xPos)
                point.xPos = arcs[i].arcPoints[j].xPos;
            if(arcs[i].arcPoints[j].yPos > point.yPos)
                point.yPos = arcs[i].arcPoints[j].yPos;
        }
        
    }
    // label ak je blízko spodnej casti
    point.yPos += 20;
    return point;
    
}

function postDataToDB(){
    popUpTask(saveToDBText);

    var mypop = $('.popup');

    mypop.find('.save').click(function(){
        var task = mypop.find('#popupInputID').val().trim();

        if(task === '' || task === undefined || description === ""){
            console.log("nic na vstupe post to db");
            return;
        }

        mypop.find('.cancel').trigger('click');

        if(task !== null || task !== undefined){
            var netName = task;
            var netJson = getJSon(netName);
            var xmlStr = createXML();
            var svgStr = createSVGSource();
        
            $.ajax({
                    type:'POST',
                    url:'http://annade.me/bp/index.php/api/petrinetPost',
                    data: {
                        xml: xmlStr,
                        svg: svgStr,
                        json: netJson
                    },
                    dataType: 'text',
                    success: function(data){
                           console.log(data + " test");
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                            console.log('jqXHR:');
                            console.log(jqXHR);
                            console.log('textStatus:');
                            console.log(textStatus);
                            console.log('errorThrown:');
                            console.log(errorThrown);
                    }		
            });
        }
    });
    
}

function getDataFromDB(){
    $.ajax({
        type : 'POST',
        url : 'loadNets.php',
        dataType : 'json',
        success: function(result){
            
           popUpDropDown(loadFromDBText);

            var mypop = $('.popup');
            var dropDownList = $('.popup-input');
            for(var i in result){
                dropDownList.append('<option value="'+ result[i].netID +'">'+ result[i].name +'</option>');
            }
            mypop.find('.save').click(function(){
                var val = mypop.find('.popup-input').val().trim();
                // nacita siet s ID v premennej val
                $.ajax({
                    type: 'POST',
                    url: 'loadNetXML.php',
                    data: {
                      netID: val  
                    },
                    dataType: 'xml',
                    success: function(result){
                        deleteAll();
                        importNet(result);
                    },
                    error: function(xhr){
                        console.log("ERROR " + xhr + ": xml cannot be liaded");
                    }
                });
                mypop.find('.cancel').trigger('click');

            });
        },
        error : function(xhr, ajaxOptions, thrownError){
           
            console.log("nieco nejde " + xhr.status);
            
       }
    });
}

function showInfo(){
    $('.popupInfo').show(200,function(){$('.container-fluid').addClass('blur');});
    $('.popupInfo').find('.close').add($('.popupInfo').find('.confirm')).on('click',function(ev){
        $('.popupInfo').hide(200,function(){$('.container-fluid').removeClass('blur');});
    });
}

//function test(){
//    for(var i = 0; i < transitonsArray.length; i++){
//        var $tran = transitonsArray[i].svgObjects.svgTransition.remove();
//        $tran.appendTo($('#netDrawArea'));
//    }
//}

/*
 data = { idCase : id , xml : xml , svg : svg }
contentType: "text/xml",
    dataType: "text",

    //ak mi nepojde offset() na svg tag
        var drawArea = document.getElementById('netDrawArea');
        var boundC = drawArea.getBoundingClientRect();
        var doc = document.documentElement;
        var leftScroll = (window.pageXOffset || doc.scrollLeft) - (doc.clientLeft || 0);
        var topScroll = (window.pageYOffset || doc.scrollTop)  - (doc.clientTop || 0);
        var x = e.pageX - (boundC.left + leftScroll);
        var y = e.pageY - (boundC.top + topScroll);
 */
