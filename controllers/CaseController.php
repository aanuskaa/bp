<?php

namespace app\controllers;

use app\models\Case_MarkingModel;
use app\models\CaseModel;
use app\models\Petri_NetModel;
use app\models\PlaceModel;
use flow\AbstractController;
use flow\Flow;
use PDO;
use SimpleXMLElement;


/**
 * Controller pre pracu s caseom
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */
class CaseController extends AbstractController{
    
    /**
     * @inheritdoc 
     */
    protected function accessRules(){
        return [
            'logged_in' => ['create', 'viewActive', 'viewFinished', 'visualizeOne'],
        ];
    }
    
    /**
     * Funkcia na vytvorenie noveho case, ulozenie do DB a zaznacenie pociatocneho znackovania do DB
     */
    protected function create(){
        
        $case = new CaseModel();
        $case->name = $_POST['name'];
        $case->id_pn = $_POST['pn'];
        $case->firm = $_POST['firm'];
        
        if((Petri_NetModel::model()->findOne('id = ' . $case->id_pn)) == NULL){
            echo 'error';
            return;
        }
        
        $case->timestamp_start = date("Y-m-d H:i:s");
        $case->started_by = Flow::app()->auth->getUserId();
        var_dump($case->save(TRUE));
        var_dump($case->getValidationErrors());

        /*Zaznaci pociatocne znackovanie pre case*/
        $caseMarking = new Case_MarkingModel();
        $allPlacesMarking = PlaceModel::model()->findAll('id_pn = ' . $case->id_pn, 'id, initial_marking');
        foreach ($allPlacesMarking as $p){
            $cm = clone($caseMarking);
            $cm->id_case = $case->id;
            $cm->id_place = $p->id;
            $cm->marking = $p->initial_marking;
            $cm->save(TRUE);
        }
        header('Location:' . ENTRY_SCRIPT_URL . 'petrinet/filter', TRUE, 301);
    }

    /**
     * Najde a vylistuje vsetky case-y, ktore uz boli ukoncene, tzn. su neaktivne
     */
    public function viewFinished(){
        $query = 'SELECT 
                    `case`.id,
                    `case`.name,
                    `case`.id_pn,
                    `case`.timestamp_start,
                    `case`.timestamp_stop,
                    `case`.`started_by`,
                    `case`.firm,
                    FIRM.firm_name,
                    USERS.first_name,
                    USERS.last_name
                FROM
                    `case`
                        LEFT JOIN
                    FIRM ON FIRM.firm_id = `case`.firm
                                LEFT JOIN
                        USERS ON `case`.started_by = USERS.id
                WHERE
                    `case`.firm IN (SELECT 
                            firm_id
                        FROM
                            USERS_X_FIRM
                        WHERE
                            USERS_X_FIRM.user_id = 1)
                        AND timestamp_stop IS NOT NULL;';
        $result = Flow::app()->pdo->query($query)->fetchAll(PDO::FETCH_OBJ);

        $this->render('listallfinished', ['cases' => $result]);
    }
    
    /**
     * Funkcia vyfiltruje z DB vsetky case-y firiem, v ktorych je pouzivatel, ktore este nie su ukoncene
     */
    public function viewActive(){
        $query = 'SELECT 
                    `case`.id,
                    `case`.name,
                    `case`.id_pn,
                    `case`.timestamp_start,
                    `case`.`started_by`,
                    `case`.firm,
                    FIRM.firm_name,
                    USERS.first_name,
                    USERS.last_name
                FROM
                    `case`
                        LEFT JOIN
                    FIRM ON FIRM.firm_id = `case`.firm
                                LEFT JOIN
                        USERS ON `case`.started_by = USERS.id
                WHERE
                    `case`.firm IN (SELECT 
                            firm_id
                        FROM
                            USERS_X_FIRM
                        WHERE
                            USERS_X_FIRM.user_id = 1)
                        AND timestamp_stop IS NULL;';

        $result = Flow::app()->pdo->query($query)->fetchAll(PDO::FETCH_OBJ);
        
        $this->render('visualizeActive', ['cases' => $result]);
    }
    
    /**
     * Funkcia zaznaci do XML aktualne znackovanie case-u, odosle do editoru, ktore odosle sfarebnene svg
     */
    public function visualizeOne(){
        $id_case = $_POST['case_id'];
        $case = CaseModel::model()->findOne('id = ' . $id_case);
        $pn = Petri_NetModel::model()->findOne('id = ' . $case->id_pn);

        $query = 'SELECT 
                    case_marking.id_place, case_marking.marking, place.id_in_xml
                FROM
                    case_marking
                        LEFT JOIN
                    place ON case_marking.id_place = place.id
                WHERE
                    case_marking.id_case = ' . $id_case . ';';
        $case_marking = Flow::app()->pdo->query($query)->fetchAll(PDO::FETCH_OBJ);
        
        foreach ($case_marking as $m){
            $arr[$m->id_in_xml] = $m->marking;
        }
        
        $xmlStr = $pn->xml_file;
        $xml = new SimpleXMLElement($xmlStr);
        
        foreach ($xml->place as $p){
            $p->tokens = $arr[(Integer) $p->id];
        }
        
        $str = $xml->asXML();
        /*XML odoslat do editora - ziskat zafarbene SVG*/
        $str = str_replace('<?xml version="1.0"?>', '<?xml version="1.0" encoding="UTF-8"?>', $str);
        
        $svg = '<?xml version="1.0" standalone="no"?>
<svg width="778.609375" height="278.00000000020555" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" id="netDrawArea" style="width: 99%; height: 99%;"><circle id="21" cx="746" cy="139" r="20" fill="white" stroke="black" stroke-width="2" class="place"/><circle cx="737" cy="130" r="4" fill="white"/><circle cx="746" cy="130" r="4" fill="white"/><circle cx="755" cy="130" r="4" fill="white"/><circle cx="737" cy="139" r="4" fill="white"/><circle cx="746" cy="139" r="4" fill="white"/><circle cx="755" cy="139" r="4" fill="white"/><circle cx="737" cy="148" r="4" fill="white"/><circle cx="746" cy="148" r="4" fill="white"/><circle cx="755" cy="148" r="4" fill="white"/><text x="1044" y="245" font-family="verdana" font-weight="bold" font-size="12" fill="black"></text><polyline points="76,146  99,146" fill="none" stroke-width="4" stroke="white"/><polyline points="76,146  99,146" fill="none" stroke-width="2" stroke="black"/><polygon points="109,146 99,151 99,141" stroke="black" fill="black"/><rect x="91.5" y="145" opacity="0.6" stroke-width="1" fill="white" width="2" height="0"/><text font-family="verdana" font-weight="bold" font-size="12" x="92.5" y="142"></text><circle id="14" cx="56" cy="146" r="20" fill="white" stroke="black" stroke-width="2" class="place"/><circle cx="47" cy="137" r="4" fill="white"/><circle cx="56" cy="137" r="4" fill="white"/><circle cx="65" cy="137" r="4" fill="white"/><circle cx="47" cy="146" r="4" fill="white"/><circle cx="56" cy="146" r="4" fill="white"/><circle cx="65" cy="146" r="4" fill="white"/><circle cx="47" cy="155" r="4" fill="white"/><circle cx="56" cy="155" r="4" fill="white"/><circle cx="65" cy="155" r="4" fill="white"/><text x="60" y="250" font-family="verdana" font-weight="bold" font-size="12" fill="black"></text><polyline points="149,146.27397260273972  172.00281439092507,146.58907964919075" fill="none" stroke-width="4" stroke="white"/><polyline points="149,146.27397260273972  172.00281439092507,146.58907964919075" fill="none" stroke-width="2" stroke="black"/><polygon points="182.0018762606167,146.7260530994605 171.9343276657902,151.58861058403656 172.07130111605994,141.58954871434494" stroke="black" fill="black"/><rect x="164.50093813030836" y="145.5000128511001" opacity="0.6" stroke-width="1" fill="white" width="2" height="0"/><text font-family="verdana" font-weight="bold" font-size="12" x="165.50093813030836" y="142.5000128511001"></text><rect id="0" x="109" y="126" width="40" height="40" fill="#ffb3b3" class="transition" stroke="black" stroke-width="2"/><polyline points="221.99789993174198,146.71017536330794  241.001050034129,146.43476739080964" fill="none" stroke-width="4" stroke="white"/><polyline points="221.99789993174198,146.71017536330794  241.001050034129,146.43476739080964" fill="none" stroke-width="2" stroke="black"/><polygon points="251,146.2898550724637 241.07350619330197,151.43424237374515 240.92859387495605,141.43529240787413" stroke="black" fill="black"/><rect x="235.498949965871" y="145.50001521788582" opacity="0.6" stroke-width="1" fill="white" width="2" height="0"/><text font-family="verdana" font-weight="bold" font-size="12" x="236.498949965871" y="142.50001521788582"></text><circle id="15" cx="202" cy="147" r="20" fill="white" stroke="black" stroke-width="2" class="place"/><circle cx="193" cy="138" r="4" fill="white"/><circle cx="202" cy="138" r="4" fill="white"/><circle cx="211" cy="138" r="4" fill="white"/><circle cx="193" cy="147" r="4" fill="white"/><circle cx="202" cy="147" r="4" fill="white"/><circle cx="211" cy="147" r="4" fill="white"/><circle cx="193" cy="156" r="4" fill="white"/><circle cx="202" cy="156" r="4" fill="white"/><circle cx="211" cy="156" r="4" fill="white"/><text x="310" y="255" font-family="verdana" font-weight="bold" font-size="12" fill="black"></text><polyline points="291,146.8  316.0239712383463,147.80095884953386" fill="none" stroke-width="4" stroke="white"/><polyline points="291,146.8  316.0239712383463,147.80095884953386" fill="none" stroke-width="2" stroke="black"/><polygon points="326.0159808255642,148.
20063923302257 315.82413104660196,152.79696364314282 316.2238114300906,142.8049540559249" stroke="black" fill="black"/><rect x="303.2423654127821" y="131.5003196165113" opacity="0.6" stroke-width="1" fill="white" width="10.53125" height="15"/><text font-family="verdana" font-weight="bold" font-size="12" x="304.2423654127821" y="143.5003196165113">2</text><rect id="8" x="251" y="126" width="40" height="40" fill="#ffb3b3" class="transition" stroke="black" stroke-width="2"/><polyline points="345.76192163193974,129.00141708293654  345.35713442212545,94.99929145853173" fill="none" stroke-width="4" stroke="white"/><polyline points="345.76192163193974,129.00141708293654  345.35713442212545,94.99929145853173" fill="none" stroke-width="2" stroke="black"/><polygon points="345.23809523809535,85 350.3567801513913,94.93977186651665 340.3574886928596,95.05881105054678" stroke="black" fill="black"/><rect x="344.50000843501755" y="106.00070854146827" opacity="0.6" stroke-width="1" fill="white" width="2" height="0"/><text font-family="verdana" font-weight="bold" font-size="12" x="345.50000843501755" y="103.00070854146827"></text><polyline points="346.45964867539533,168.99471737972826  347.3104055473591,206.00264131013589" fill="none" stroke-width="4" stroke="white"/><polyline points="346.45964867539533,168.99471737972826  347.3104055473591,206.00264131013589" fill="none" stroke-width="2" stroke="black"/><polygon points="347.5402298850572,216 342.31172620242705,206.11755347898495 352.30908489229114,205.88772914128683" stroke="black" fill="black"/><rect x="345.9999392802263" y="191.49735868986414" opacity="0.6" stroke-width="1" fill="white" width="2" height="0"/><text font-family="verdana" font-weight="bold" font-size="12" x="346.9999392802263" y="188.49735868986414"></text><circle id="16" cx="346" cy="149" r="20" fill="white" stroke="black" stroke-width="2" class="place"/><circle cx="337" cy="140" r="4" fill="white"/><circle cx="346" cy="140" r="4" fill="white"/><circle cx="355" cy="140" r="4" fill="white"/><circle cx="337" cy="149" r="4" fill="white"/><circle cx="346" cy="149" r="4" fill="white"/><circle cx="355" cy="149" r="4" fill="white"/><circle cx="337" cy="158" r="4" fill="white"/><circle cx="346" cy="158" r="4" fill="white"/><circle cx="355" cy="158" r="4" fill="white"/><text x="502" y="263" font-family="verdana" font-weight="bold" font-size="12" fill="black"></text><polyline points="365,64.99999999994307  396,64.999999999972" fill="none" stroke-width="4" stroke="white"/><polyline points="365,64.99999999994307  396,64.999999999972" fill="none" stroke-width="2" stroke="black"/><polygon points="406,64.99999999998134 395.99999999999534,69.999999999972 396.00000000000466,59.999999999972005" stroke="black" fill="black"/><rect x="384.5" y="63.9999999999622" opacity="0.6" stroke-width="1" fill="white" width="2" height="0"/><text font-family="verdana" font-weight="bold" font-size="12" x="385.5" y="60.9999999999622"></text><rect id="9" x="325" y="45" width="40" height="40" fill="#ffb3b3" class="transition" stroke="black" stroke-width="2"/><polyline points="368,236.00000000020552  395,236.00000000010817" fill="none" stroke-width="4" stroke="white"/><polyline points="368,236.00000000020552  395,236.00000000010817" fill="none" stroke-width="2" stroke="black"/><polygon points="405,236.0000000000721 395.000000000018,241.00000000010817 394.999999999982,231.00000000010817" stroke="black" fill="black"/><rect x="385.5" y="235.0000000001388" opacity="0.6" stroke-width="1" fill="white" width="2" height="0"/><text font-family="verdana" font-weight="bold" font-size="12" x="386.5" y="232.0000000001388"></text><rect id="10" x="328" y="216" width="40" height="40" fill="#ffb3b3" class="transition" stroke="black" stroke-width="2"/><polyline points="446,64.99999999703199  478,64.99999999856387" fill="none" stroke-width="4" stroke="white"/><polyline points="446,64.99999999703199  478,64.99999999856387" fill="none" stroke-width="2" stroke="black"/><polygon points="488,64.99999999904257 477.99999999976063,69.99999999856387 478.00000000023937,59.999999998563865"
 stroke="black" fill="black"/><rect x="466" y="63.99999999803728" opacity="0.6" stroke-width="1" fill="white" width="2" height="0"/><text font-family="verdana" font-weight="bold" font-size="12" x="467" y="60.99999999803728"></text><circle id="17" cx="426" cy="65" r="20" fill="white" stroke="black" stroke-width="2" class="place"/><circle cx="417" cy="56" r="4" fill="white"/><circle cx="426" cy="56" r="4" fill="white"/><circle cx="435" cy="56" r="4" fill="white"/><circle cx="417" cy="65" r="4" fill="white"/><circle cx="426" cy="65" r="4" fill="white"/><circle cx="435" cy="65" r="4" fill="white"/><circle cx="417" cy="74" r="4" fill="white"/><circle cx="426" cy="74" r="4" fill="white"/><circle cx="435" cy="74" r="4" fill="white"/><text x="710" y="139" font-family="verdana" font-weight="bold" font-size="12" fill="black"></text><polyline points="444.99405381265206,235.5123401423403  477.00297309353584,234.73163479845041" fill="none" stroke-width="4" stroke="white"/><polyline points="444.99405381265206,235.5123401423403  477.00297309353584,234.73163479845041" fill="none" stroke-width="2" stroke="black"/><polygon points="487,234.48780487528433 477.1248880551189,239.7301482516825 476.8810581319528,229.73312134521834" stroke="black" fill="black"/><rect x="464.997026906326" y="234.0000725088123" opacity="0.6" stroke-width="1" fill="white" width="2" height="0"/><text font-family="verdana" font-weight="bold" font-size="12" x="465.997026906326" y="231.0000725088123"></text><circle id="18" cx="425" cy="236" r="20" fill="white" stroke="black" stroke-width="2" class="place"/><circle cx="416" cy="227" r="4" fill="white"/><circle cx="425" cy="227" r="4" fill="white"/><circle cx="434" cy="227" r="4" fill="white"/><circle cx="416" cy="236" r="4" fill="white"/><circle cx="425" cy="236" r="4" fill="white"/><circle cx="434" cy="236" r="4" fill="white"/><circle cx="416" cy="245" r="4" fill="white"/><circle cx="425" cy="245" r="4" fill="white"/><circle cx="434" cy="245" r="4" fill="white"/><text x="708" y="355" font-family="verdana" font-weight="bold" font-size="12" fill="black"></text><polyline points="527,233.45946238095235  551.0109509476326,232.8105164617123" fill="none" stroke-width="4" stroke="white"/><polyline points="527,233.45946238095235  551.0109509476326,232.8105164617123" fill="none" stroke-width="2" stroke="black"/><polygon points="561.0073006317551,232.5403443078082 551.1460370245846,237.80869130377351 550.8758648706806,227.81234161965108" stroke="black" fill="black"/><rect x="543.0036503158776" y="231.99990334438027" opacity="0.6" stroke-width="1" fill="white" width="2" height="0"/><text font-family="verdana" font-weight="bold" font-size="12" x="544.0036503158776" y="228.99990334438027"></text><rect id="12" x="487" y="214" width="40" height="40" fill="#ffb3b3" class="transition" stroke="black" stroke-width="2"/><polyline points="528,64.72602740505505  551.0028143909855,64.41092035522014" fill="none" stroke-width="4" stroke="white"/><polyline points="528,64.72602740505505  551.0028143909855,64.41092035522014" fill="none" stroke-width="2" stroke="black"/><polygon points="561.001876260657,64.2739469034801 551.0713011168555,69.41045129005589 550.9343276651155,59.411389420384396" stroke="black" fill="black"/><rect x="543.5009381303284" y="63.49998715426757" opacity="0.6" stroke-width="1" fill="white" width="2" height="0"/><text font-family="verdana" font-weight="bold" font-size="12" x="544.5009381303284" y="60.49998715426757"></text><rect id="11" x="488" y="45" width="40" height="40" fill="#ffb3b3" class="transition" stroke="black" stroke-width="2"/><polyline points="596.1364765933733,77.07237837343537  641.4317716297,116.19106292568902" fill="none" stroke-width="4" stroke="white"/><polyline points="596.1364765933733,77.07237837343537  641.4317716297,116.19106292568902" fill="none" stroke-width="2" stroke="black"/><polygon points="649,122.72726360612926 638.1636712894799,119.97517711083898 644.6998719699202,112.40694874053906" stroke="black" fill="black"/><rect x="621.5682382966867" y="98.89982098978231" opacity="0.6" stroke-width="1" 
fill="white" width="2" height="0"/><text font-family="verdana" font-weight="bold" font-size="12" x="622.5682382966867" y="95.89982098978231"></text><circle id="19" cx="581" cy="64" r="20" fill="white" stroke="black" stroke-width="2" class="place"/><circle cx="572" cy="55" r="4" fill="white"/><circle cx="581" cy="55" r="4" fill="white"/><circle cx="590" cy="55" r="4" fill="white"/><circle cx="572" cy="64" r="4" fill="white"/><circle cx="581" cy="64" r="4" fill="black"/><circle cx="590" cy="64" r="4" fill="white"/><circle cx="572" cy="73" r="4" fill="white"/><circle cx="581" cy="73" r="4" fill="white"/><circle cx="590" cy="73" r="4" fill="white"/><text x="907" y="143" font-family="verdana" font-weight="bold" font-size="12" fill="black"></text><polyline points="594.8244653901139,217.54717478561565  642.9573460788496,167.2264196830688" fill="none" stroke-width="4" stroke="white"/><polyline points="594.8244653901139,217.54717478561565  642.9573460788496,167.2264196830688" fill="none" stroke-width="2" stroke="black"/><polygon points="649.8695713764043,160 646.570555920384,170.68253233184618 639.3441362373152,163.77030703429142" stroke="black" fill="black"/><rect x="621.3470183832592" y="187.77358739280783" opacity="0.6" stroke-width="1" fill="white" width="2" height="0"/><text font-family="verdana" font-weight="bold" font-size="12" x="622.3470183832592" y="184.77358739280783"></text><circle id="20" cx="581" cy="232" r="20" fill="white" stroke="black" stroke-width="2" class="place"/><circle cx="572" cy="223" r="4" fill="white"/><circle cx="581" cy="223" r="4" fill="white"/><circle cx="590" cy="223" r="4" fill="white"/><circle cx="572" cy="232" r="4" fill="white"/><circle cx="581" cy="232" r="4" fill="white"/><circle cx="590" cy="232" r="4" fill="white"/><circle cx="572" cy="241" r="4" fill="white"/><circle cx="581" cy="241" r="4" fill="white"/><circle cx="590" cy="241" r="4" fill="white"/><text x="903" y="345" font-family="verdana" font-weight="bold" font-size="12" fill="black"></text><polyline points="689,139.7368714786259  716.0025065167479,139.3877946908434" fill="none" stroke-width="4" stroke="white"/><polyline points="689,139.7368714786259  716.0025065167479,139.3877946908434" fill="none" stroke-width="2" stroke="black"/><polygon points="726.0016710111653,139.2585297938956 716.0671389652218,144.38737693805209 715.9378740682739,134.38821244363473" stroke="black" fill="black"/><rect x="706.5008355055827" y="138.49770063626073" opacity="0.6" stroke-width="1" fill="white" width="2" height="0"/><text font-family="verdana" font-weight="bold" font-size="12" x="707.5008355055827" y="135.49770063626073"></text><rect id="13" x="649" y="120" width="40" height="40" fill="#ffb3b3" class="transition" stroke="black" stroke-width="2"/><rect x="119.5390625" y="167" stroke-width="1" fill-opacity="0.6" fill="white" width="18.921875" height="12"/><text id="label_0" y="178" x="120.5390625" font-family="verdana" font-weight="bold" font-size="12">p1</text><rect x="261.5390625" y="167" stroke-width="1" fill-opacity="0.6" fill="white" width="18.921875" height="12"/><text id="label_8" y="178" x="262.5390625" font-family="verdana" font-weight="bold" font-size="12">p2</text><rect x="335.5390625" y="86" stroke-width="1" fill-opacity="0.6" fill="white" width="18.921875" height="12"/><text id="label_9" y="97" x="336.5390625" font-family="verdana" font-weight="bold" font-size="12">p3</text><rect x="338.5390625" y="257" stroke-width="1" fill-opacity="0.6" fill="white" width="18.921875" height="12"/><text id="label_10" y="268" x="339.5390625" font-family="verdana" font-weight="bold" font-size="12">p4</text><rect x="498.5390625" y="86" stroke-width="1" fill-opacity="0.6" fill="white" width="18.921875" height="12"/><text id="label_11" y="97" x="499.5390625" font-family="verdana" font-weight="bold" font-size="12">p5</text><rect x="497.5390625" y="255" stroke-width="1" fill-opacity="0.6" fill="white" width="18.921875" height="12"/><text id="label_12" y="266" x="498.5390625" font-family="verdana" font-weight="bold" font-size="12">p6</text><rect x="659.5390625" 
y="161" stroke-width="1" fill-opacity="0.6" fill="white" width="18.921875" height="12"/><text id="label_13" y="172" x="660.5390625" font-family="verdana" font-weight="bold" font-size="12">p7</text><rect x="44.390625" y="167" stroke-width="1" fill-opacity="0.6" fill="white" width="23.21875" height="12"/><text y="178" x="45.390625" font-family="verdana" font-weight="bold" font-size="12">m1</text><rect x="190.390625" y="168" stroke-width="1" fill-opacity="0.6" fill="white" width="23.21875" height="12"/><text y="179" x="191.390625" font-family="verdana" font-weight="bold" font-size="12">m2</text><rect x="334.390625" y="170" stroke-width="1" fill-opacity="0.6" fill="white" width="23.21875" height="12"/><text y="181" x="335.390625" font-family="verdana" font-weight="bold" font-size="12">m3</text><rect x="414.390625" y="86" stroke-width="1" fill-opacity="0.6" fill="white" width="23.21875" height="12"/><text y="97" x="415.390625" font-family="verdana" font-weight="bold" font-size="12">m4</text><rect x="413.390625" y="257" stroke-width="1" fill-opacity="0.6" fill="white" width="23.21875" height="12"/><text y="268" x="414.390625" font-family="verdana" font-weight="bold" font-size="12">m5</text><rect x="569.390625" y="85" stroke-width="1" fill-opacity="0.6" fill="white" width="23.21875" height="12"/><text y="96" x="570.390625" font-family="verdana" font-weight="bold" font-size="12">m6</text><rect x="569.390625" y="253" stroke-width="1" fill-opacity="0.6" fill="white" width="23.21875" height="12"/><text y="264" x="570.390625" font-family="verdana" font-weight="bold" font-size="12">m7</text><rect x="734.390625" y="160" stroke-width="1" fill-opacity="0.6" fill="white" width="23.21875" height="12"/><text y="171" x="735.390625" font-family="verdana" font-weight="bold" font-size="12">m8</text></svg>';
        
        
        $query = 'SELECT 
                    transition.id_in_xml
                FROM
                    case_progress
                        LEFT JOIN
                    transition ON case_progress.id_transition = transition.id
                WHERE
                    id_case = ' . $id_case . ' AND timestamp_stop IS NULL;';
        $case_progress = Flow::app()->pdo->query($query)->fetchAll(PDO::FETCH_OBJ);
        
        var_dump($case_progress);
        
        $this->render('visualizeOne', ['svg' => $svg, 'progress' => $case_progress]);
    }
}
