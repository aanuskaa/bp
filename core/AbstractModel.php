<?php

namespace flow;

use PDO;

/**
 * @todo write description
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */
abstract class AbstractModel {
    /**
     * @var array Pole atribútov (stĺpcov tabuľky)
     */
    public $attributes = array();
    
    /**
     * @var array Pole atribútov (stĺpcov tabuľky), počas behu scriptu nedôjde k ich zmene
     * využiju sa na kontrolu pri ukladaní objektu do DB
     */
    protected $originalAttributes = array();
    
    /**
     * @var String Názov databázovej tabuľky
     */
    protected $tableName;
    
    /**
     * @var array pole stĺpcov ktoré majú v DB nejakú default hodnotu
     */
    protected $columnsWithDefValue = array();
    
    /**
     * @var boolean Definuje či sa jedná o nový záznam = nezápísaný v DB alebo existujúci
     */
    protected $isNewRecord = TRUE;

    /**
     * @var String reťazec pre formulár s upozorneniami pri povinných položkách
     * alebo nesprávnom formáte
     */
    protected $validationErrors = array();
    /**
     * @var String Názov stĺpca s primárnym kľúčom
     */
    protected $primaryKey;
    
    /**
     * @var array Uchováva modely ktoré zdedili funckionalitu
     */
    private static $models = array();
    
    /**
     * Vymaže tagy a placeholdery
     * @var array
     */
    protected $tags;
    
     /**
     * Konštruktor modelu, zistí atribúty databázovej tabuľky a priradí ich do poľa attributes
     */
    public function __construct() {
 
        $this->tableName = $this->table();
        if(empty($this->tableName)){
            return;
        }
        $table_fields = Flow::app()->pdo->query('DESCRIBE `' . $this->tableName . '`')->fetchAll(PDO::FETCH_OBJ);

        foreach ($table_fields as $row) {
            if ($row->Key == 'PRI') {
                $this->primaryKey = $row->Field;
            }
            if ($row->Default != NULL) {
                $this->columnsWithDefValue[] = $row->Field;
            }

            $this->originalAttributes[$row->Field] = $this->attributes[$row->Field] = NULL;
        }
    }
    
    /**
     * Metóda vráti model požadovanej triedy (potomka), 
     * Umoznuje pouzitie funkcii triedy bez vytvorenia objektu
     * @return object 
     */
    public static function model($className = __CLASS__) {

        if (isset(self::$models[$className])) {
            return self::$models[$className];
        } else {
            $model = self::$models[$className] = new $className(null);
            return $model;
        }
    }
    
    /**
     * Vrati nazov tabulky
     * @return string
     */
    abstract public function table();
    
    /**
     * Vrati validacne pravidla
     * Zatial podporovane pravidla
     * [
     *      'required' => 'povinne atributy',
     *      'unique'   => 'jedinecne hodnoty v tabulke',
     * ]
     * @return string
     */
    abstract public function rules();
    
    
    /**
     * PHP getter magic method
     * Umožňuje pristupovať sĺpcom databázy ako k atribútom objektu, hoci v ňom nie sú definované
     * @param String $name Názov virtuálneho atribútu
     */
    public function __get($name) {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        } else {
            trigger_error('Objekt ' . get_class($this) . ' neobsahuje atribút ' . $name, E_USER_NOTICE);
        }
    }

    /**
     * PHP setter magic method
     * Umožňuje zmeniť hodnotu sĺpcom databázy ako atribútom objektu, hoci v ňom nie sú definované
     * @param String $name Názov virtuálneho atribútu
     * @param mixed $value Hodnota, ktorá bude priradená
     */
    public function __set($name, $value) {
        if (array_key_exists($name, $this->attributes)) {
            $this->attributes[$name] = $value;
            return true;
        } else {
            trigger_error('Objekt ' . get_class($this) . ' neobsahuje atribút ' . $name, E_USER_NOTICE);
            return false;
        }
    }

    /**
     * PHP isset magic method
     * Metóda zistí či je virtuálny atribút incializovaný alebo nie
     * @param String $name Názov virtuálneho atribútu
     * @return boolean
     */
    public function __isset($name) {

        if (isset($this->attributes[$name])) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * PHP unset magic method
     * Umožňuje nastaviť hodnotu virtuálnemu atribútu na NULL
     * @param String $name Názov virtuálneho atribútu
     */
    public function __unset($name) {

        if (array_key_exists($name, $this->attributes)) {
            unset($this->attributes[$name]);
        }
    }

    /**
     * Metóda vráti hodnotu premennej $isNewRecord, ktora definuje či sa jedna o novy zaznam
     * @return boolean
     */
    public function getIsNewRecord() {
        return $this->isNewRecord;
    }
    
    
    /**
     * Metóda nastaví hodnotu premennej $isNewRecord, ktora definuje či sa jedna o novy zaznam
     */
    public function setIsNewRecord($isNewRecord) {

        $this->isNewRecord = $isNewRecord;
    }

    /**
     * Metóda vráti pole atribútov
     * @return array
     */
    public function getAttributes() {
        return $this->attributes;
    }
    
    /**
     * Metóda nastaví atribúty poľa na hodnoty definované parametrom
     * @param array $vals Asociatívne pole stĺpec => hodnota
     * @return array
     */
    public function setAttributes($vals) {

        foreach ($vals as $key => $val) {
            $this->attributes[$key] = (strlen($val) === 0)? NULL : $val;
        }
    }
    /**
     * Vráti upozornenia na validačné chyby
     * @return array
     */
    public function getValidationErrors() {
        return $this->validationErrors;
    }

    /**
     * Nastaví upozornenie na validačné chyby
     * @param array $validationErrors
     */
    public function setValidationErrors($validationErrors) {
        $this->validationErrors = $validationErrors;
    }
    
    /**
     * Skontroluje či model má validačné chyby
     * @return bool TRUE ak model má chyby, false ak nemá
     */
    public function hasValidationErrors() {
        return !empty($this->validationErrors);
    }   
        
    public function getPrimaryKey() {
        return $this->primaryKey;
    }
    
    /**
     * Metóda vyhľadá jeden záznam spĺňajúci zadanú podmienku
     * 
     * V SQL príkaze je tabuľka pre aktuálny model označená aliasom t
     * @param String condition
     * @param String $columns [optional]
     * @param String $joins [optional] Definícia JOIN
     * @return object | NULL
     */
    public function findOne($condition, $columns = '*', $joins = '') {
        $query = "SELECT {$columns} FROM `{$this->table()}` t {$joins} WHERE {$condition} LIMIT 1";
        $result = Flow::app()->pdo->query($query)->fetchAll(PDO::FETCH_OBJ);

        if (!empty($result)) {
            $model = new $this;
            $model->setIsNewRecord(FALSE);
            $model->setAttributes(array_shift($result));
            return $model;
        } else {
            return NULL;
        }
    }
    
    /**
     * Metóda vyhľadá všetky záznamy spĺňajúce danú podmienku
     * 
     * V SQL príkaze je tabuľka pre aktuálny model označená aliasom t
     * @param String $condition [optional]
     * @param String $columns [optional]
     * @param String $joins [optional] Definícia JOIN
     * @param String $index [optional] Názov stĺpca ktorého hodnota bude predstavovať kľúč,
     *  vo výslednom poli. Odporúča sa používať stĺpce s unikátmi hodnotami 
     * @return array Pole objektov tejto triedy
     */
    public function findAll($condition = '', $columns = '*', $joins = '', $index = '') {

        $query = "SELECT {$columns} FROM `{$this->table()}` {$joins} ";
        if ($condition != '') {
            $query .= "WHERE {$condition}";
        }
        //var_dump($query);
        $result = Flow::app()->pdo->query($query)->fetchAll(PDO::FETCH_OBJ);
        $empty_object = new $this;
        $empty_object->setIsNewRecord(FALSE);
        $result_arr = [];
        
        foreach ($result as $row) {
            $model = clone $empty_object;
            $model->setAttributes($row);
            if (!empty($index) && (($key = $model->getAttribute($index)) != NULL)) {
                $result_arr[$key] = $model;
            }
            else{
                $result_arr[] = $model;
            }
        }
        return $result_arr;
    }
    
    /**
     * Metóda validuje dáta pred uložením do DB
     * @return boolean - v závislosti od úspešnosti validácie
     */
    public function validate() {

        $rules  = $this->rules();
        /**
         * @todo imlementovat labels()
         * $labels = $this->labels();
         */
        $labels = []; // TEMPORARY!

        foreach ($this->attributes as $attr => $value) {
            if (isset($rules['required']) && preg_match('/\b' . $attr . '\b/', $rules['required']) != false) {
                if ($this->primaryKey !== $attr && !in_array($attr, $this->columnsWithDefValue) && strlen($value)==0) {
                    $this->validationErrors[$attr] = Flow::t('{FIELD} is required field', ['{FIELD}' => ucfirst(isset($labels[$attr]) ? $labels[$attr] : $attr)]);
                }
            }
            if (isset($rules['unique']) && preg_match('/\b' . $attr . '\b/', $rules['unique']) != false && !is_null($value)) {
                if ($this->isNewRecord) {
                    $query = "SELECT t.{$attr} as toCheck from {$this->table()} t WHERE t.{$attr} = '{$value}';";
                } else {
                    $query = "SELECT t.{$attr} as toCheck from {$this->table()} t WHERE t.{$attr} = '{$value}' AND t.id <> {$this->id};";
                }
                //var_dump($query);
                $result = Flow::app()->pdo->query($query)->fetchAll(PDO::FETCH_OBJ);
                if (!empty($result)) {
                    $this->validationErrors[$attr] = Flow::t('Value in the field {FIELD} is already in use', ['{FIELD}' => ( isset($labels[$attr]) ? $labels[$attr] : $attr)]) ;
                }
            }
        }
        return empty($this->validationErrors);
    }
    
    /**
     * Metóda uloží záznam do databázy
     * @return boolean Oznámenie o úspešnom prevedení operácie
     */
    public function save($runValidation = false) {

        if (!$runValidation || $this->validate()) {
            return $this->getIsNewRecord() ? $this->insert() : $this->update();
        } else {
            return false;
        }
    }
    
    /**
     * Metóda vloží do DB nový záznam zodpovedajúci objektu tejto triedy
     * return boolean Oznámenie o uspešnosti prevedenia operácie
     * @return boolean Oznámenie o uspešnosti prevedenia operácie
     */
    public function insert() {

        $attributes = $this->attributes;
        foreach ($attributes as $key => $value) { //zahodi hodnoty neprisluchajuce stlpcom tabulky
            if (!array_key_exists($key, $this->originalAttributes)) {
                unset($attributes[$key]);
            }
        }
        if (array_key_exists($this->primaryKey, $this->attributes)) {
            unset($attributes[$this->primaryKey]);
        }
        $check = Flow::app()->pdo->insert($this->tableName, $attributes, $this->columnsWithDefValue);
        $this->attributes[$this->primaryKey] = Flow::app()->pdo->lastInsertId();
        $this->isNewRecord = FALSE;

        return $check;
    }

    /**
     * Metóda upraví existujúci záznam v DB, ktorý zodpovedá objektu tejto triedy
     * @var String $whereClause Podmienka pre insert príkaz. Formát stĺpec = hodnota
     * @return boolean Oznámenie o uspešnosti prevedenia operácie
     * 
     */
    public function update($condition = '') {

        $attributes = $this->attributes;
        foreach ($attributes as $key => $value) { //zahodi hodnoty neprisluchajuce stlpcom tabulky
            if (!array_key_exists($key, $this->originalAttributes)) {
                unset($attributes[$key]);
            }
        }
        if (array_key_exists($this->primaryKey, $this->attributes)) {
            unset($attributes[$this->primaryKey]);
            $condition = "`{$this->primaryKey}` = '{$this->attributes[$this->primaryKey]}'";
        } elseif ($condition == '') {
            throw new Exception("Nebola špecifikovaná podmienka pre UPDATE príkaz.");
        }

        return Flow::app()->pdo->update(
                        $this->tableName, $attributes, $condition, $this->columnsWithDefValue);
    }

    /**
     * Metóda Vymaže existujúci záznam z DB, ktorý zodpovedá objektu tejto triedy
     * @return void
     */
    public function delete ( ) {
        
        $del = Flow::app()->pdo->delete("`{$this->tableName}`", "{$this->primaryKey} = '{$this->attributes[$this->primaryKey]}'" );
        $this->attributes[$this->primaryKey] = NULL;
        $this->isNewRecord = TRUE;
        
        return $del;
    }
}
