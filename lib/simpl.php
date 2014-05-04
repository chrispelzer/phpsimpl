<?php namespace Simpl;

// Include the Config
include_once(__DIR__ . '/config.php');
include_once(__DIR__ . '/functions.php');

/**
 * Base PHPSimpl Class used to control Simpl at its highest level
 *
 * @author Nick DeNardis <nick.denardis@gmail.com>
 * @link http://code.google.com/p/phpsimpl/
 */
class Simpl {
    /**
     * @var array
     */
    public $settings = array('form' => array(
        'required_indicator' => 'before',
        'label_ending' => ':'
    ));

    /**
     * Class Construåctor
     *
     * Creates a Simpl Class with nothing in it
     *
     * @return \Simpl\Simpl
     */
    public function __construct(){
        // Clear the Cache if needed
        if (isset($_GET['clear']) || CLEAR_CACHE === true)
            $this->ClearCache('clear');
    }

    /**
     * Load a class file when needed.
     *
     * @depricated
     * @param $class A string containing the class name
     * @return bool
     */
    public function Load($class){
        // Depricated but used for backwards compatibility
        if (!class_exists($class)){
            switch($class){
                case 'Feed':
                    include_once(FS_SIMPL . 'feed.php');
                    break;
            }
        }

        return true;
    }

    /**
     * Does various Actions with the Cache
     *
     * @param string $action
     * @return bool
     */
    public function ClearCache($action){
        switch($action){
            case 'clear':
                $files = glob(FS_CACHE . "*.cache.php");
                break;
            case 'clear_query':
                $files = glob(FS_CACHE . "query_*.cache.php");
                break;
            case 'clear_table':
                $files = glob(FS_CACHE . "table_*.cache.php");
                break;
        }

        if (is_array($files))
            foreach($files as $file)
                unlink($file);

        return true;
    }
}

// If using DB Sessions
if ( DB_SESSIONS == true ){
    // Create the DB Session
    $s = new Session((defined('DB_CMS'))?DB_CMS:DB_DEFAULT);

    //Change the save_handler to use the class functions
    session_set_save_handler (
        array(&$s, 'open'),
        array(&$s, 'close'),
        array(&$s, 'read'),
        array(&$s, 'write'),
        array(&$s, 'destroy'),
        array(&$s, 'gc')
    );
}

// Start a session if not already started
if (session_id() == '')
    @session_start();
