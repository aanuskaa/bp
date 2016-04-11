<?php

namespace flow;

use PDO;

/**
 * PDO class wrapper
 *
 * @package    
 * @author     Anna Demeterova
 * @link       http://workflow.com/
 * @version    1.0.0
 */
class PdoConnection extends PDO{

    /**
     * Spojenie sa vytvori cez newConnection()
     */
    public function __construct() { }
    
    /**
     * Vytvorí database connection
     * @param string $dsn
     * @param string $username
     * @param string $password
     */
    public function newConnection($dsn, $username, $password) {
        parent::__construct($dsn, $username, $password);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }
    
    /**
     * Metóda vytvorí na základe parametra tabuľka a pole dát dotaz pre vloženie 
     * parametrov a vykoná ho
     * 
     * @param String $table Názov tabuľky
     * @param array $data Pole hodnôt vo forme stĺpec => hodnota
     * @param array $default Pole stĺpcov ktorá majú v DB prednastavenú DEFAULT hodnotu, použije sa ak je NULL
     * @return bool
     */
    public function insert( $table, $data, $defaults = array() ) {
        $fields = '';
        $vals = '';
        
        foreach ( $data as $f => $v) {
            $fields .= "`{$f}`,";
            
            if( $v === NULL && in_array( $f, $defaults )) {
                $vals .= "DEFAULT,";
            }
            elseif( $v === NULL  ){
                $vals .= "NULL,";
            }
            elseif ( is_numeric( $v ) && ( intval( $v ) == $v )
                    && ( substr( $v, 0, 1) != '0' && substr( $v, 0, 1) != '+' ) ) {
                $vals .= "{$v},";
            }
            else{
                $vals .= "'{$v}',";
            }
        }
        $fields = trim($fields, ",");
        $vals = trim($vals, ",");
        
        $query = "INSERT INTO `{$table}` ({$fields}) VALUES ({$vals})";
        
        //var_dump($query);
        $this->query($query);
        
        return true;
    }
    
    /**
     * Metóda aktualizuje záznamy v databáze
     * 
     * @param String $table Názov tabuľky
     * @param array $changes Pole zmenie stĺpec => hodnota
     * @param String $condition Podmienka
     * @param array $defaults Pole stĺpcov ktorá majú v DB prednastavenú DEFAULT hodnotu, použije sa ak je NULL
     * @return bool
     */
    public function update( $table, $changes, $condition = '', $defaults = array() ) {
        
        $query = "UPDATE `{$table}` SET ";
        foreach ( $changes as $field => $value ){
            
            if( $value === NULL && in_array( $field, $defaults )) {
                $query .= "`{$field}` = DEFAULT,";
            }
            elseif( $value === NULL  ){
                $query .= "`{$field}` = NULL,";
            }
            elseif ( is_numeric( $value ) && ( intval( $value ) == $value ) && substr( $value, 0, 1) != '+' ) {
                $query .= "`{$field}` = {$value},";
            }
            else {
                $query .= "`{$field}` = '{$value}',";
            }
        }
        $query = trim($query,",");
        if( $condition != '' ) {
            $query .= " WHERE " . $condition;
        }
        $this->query($query);
        return true;
    }
    
    /**
     * Metóda vytvorí na základe parametrov názvu tabuľky, podmienky a limitu
     * dotaz na odstránenie záznamu a vykoná ho
     * 
     * @param String $table Tabuľka z ktorej sa záznam odstráni
     * @param String $condition Podmienka pre odstránenie
     * @param int $limit Počet riadkov, ktoré sa majú odstrániť
     * @return Boolean
     */
    public function delete( $table, $condition, $limit = '1' ) {
        $limit = ( $limit == '' ) ? '' : ' LIMIT ' . $limit;

        $query = "DELETE FROM {$table} WHERE {$condition} {$limit}";
        $this->query($query);

        return true;
    }
    
    
}
