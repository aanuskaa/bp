<?php

namespace flow;

/**
 * Autoloader
 * PSR-4 standard
 * @package    flow
 * @license    License here
 * @version    1.0.0
 */
class Autoloader {
    
    /**
     * Pripojí súbor podľa namespace
     * An associative array where the key is a namespace prefix and the value
     * is an array of base directories for classes in that namespace.
     *
     * @var array
     */
    protected $prefixes = array();

    /**
     * Register loader with SPL autoloader stack.
     * 
     * @return void
     */
    public function __construct()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }
    
    /**
     * Adds a base directory for a namespace prefix.
     *
     * @param string $prefix The namespace prefix.
     * @param string $base_dir A base directory for class files in the
     * namespace.
     * @param bool $prepend If true, prepend the base directory to the stack
     * instead of appending it; this causes it to be searched first rather
     * than last.
     * @return void
     */
    public function addNamespace($prefix, $base_dir, $prepend = false)
    {
        // normalize namespace prefix and the base directory
        $prefix   = trim($prefix, '\\') . '\\';
        $base_dir = rtrim($base_dir, DIRECTORY_SEPARATOR) . '/';

        if (isset($this->prefixes[$prefix]) === false) {
            $this->prefixes[$prefix] = array();
        }

        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $base_dir);
        } else {
            array_push($this->prefixes[$prefix], $base_dir);
        }
    }
    
    /**
     * Similar to addNamespace(), but handles the input as an array of multiple namespaces. 
     * Adds an array of base directories for each namespace prefix.
     * 
     * Array format
     * [
     *      prefix1 => base_dir1,
     *      prefix2 => [base_dir2, base_dir3],
     *      ...
     * ]
     * 
     * @param array $namespaces  
     * @param bool $prepend If true, prepend the base directory to the stack
     * instead of appending it; this causes it to be searched first rather
     * than last.
     */
    public function addNamespacesArray($namespaces, $prepend = false){
        
        foreach ( $namespaces as $prefix => $directories ){
            
            foreach ( (array) $directories as $base_dir ){
                $this->addNamespace($prefix, $base_dir, $prepend);
            }
        }
    }


    /**
     * Loads the class file for a given class name.
     *
     * @param string $class The fully-qualified class name.
     * @return mixed The mapped file name on success, or boolean false on
     * failure.
     */
    public function loadClass($class)
    {
        $prefix = $class;

        // work backwards through the namespace names of the fully-qualified
        // class name to find a mapped file name
        while (false !== $pos = strrpos($prefix, '\\')) {

            // retain the trailing namespace separator in the prefix
            $prefix = substr($class, 0, $pos + 1);
            
            // the rest is the relative class name
            $relative_class = substr($class, $pos + 1);
            
            // try to load a mapped file for the prefix and relative class
            $mapped_file = $this->loadMappedFile($prefix, $relative_class);
            
            
            if ($mapped_file) {
                return $mapped_file;
            }
            
            // remove the trailing namespace separator for the next iteration
            $prefix = rtrim($prefix, '\\');   
        }

        // never found a mapped file
        return false;
    }
    
    /**
     * Load the mapped file for a namespace prefix and relative class.
     * 
     * @param string $prefix The namespace prefix.
     * @param string $relative_class The relative class name.
     * @return mixed Boolean false if no mapped file can be loaded, or the
     * name of the mapped file that was loaded.
     */
    protected function loadMappedFile($prefix, $relative_class)
    {
        // no base directories registred
        if (isset($this->prefixes[$prefix]) === false) {
            return false;
        }

        // look through base directories for this namespace prefix
        foreach ($this->prefixes[$prefix] as $base_dir) {

            $file_path = $base_dir
                        . str_replace('\\', '/', $relative_class)
                        . '.php';

            if (file_exists($file_path)) {
                require $file_path;
                return $file_path;
            }
        }

        // found nothing
        return false;
    }
}
