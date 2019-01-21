<?php

/**
 * @author Chanh Ong
 * @package NsClassLoader
 * @date 09/26/2015
 * @since 1.0
 */
/* Usage:
    NsClassLoader::$classFolders = array($path.DS."folder1",$path.DS."folder2",);
    $autoloader = new NsClassLoader();
 */
namespace Libs;

class NsClassLoader {

    public static $classFolders;

    public function __construct() {
//        echo 'Register ' . get_class($this) . '\loader via ', __METHOD__, "()\n";
        spl_autoload_register(array($this, 'loader'), true, true);
    }

    public static function loader($className) {

        // if namespace\classname, extract just class name
        if (strpos($className, '\\') !== false) {
            $classNameArray = explode('\\', $className);
            $className = array_pop($classNameArray);
        }
        // check class file in folders
        if (!class_exists($className)) {
            self::using($className, self::$classFolders);
        }
    }

    public static function using($className, $inFolders) {

        if (is_array($inFolders) or ( is_object($inFolders))) {
            // using with folders
            foreach ($inFolders as $iType) {
                $file = strtolower($iType . DS . $className . '.php');
                if (file_exists($file)) {
                    self::usingOne($file);
                    break;
                }
            }
        } else {
            // using with one path for direct using of class instead of autoload
            $file = strtolower($inFolders . DS . $className . '.php');
            if (file_exists($file)) {
                self::usingOne($file);
            }
        }
    }

    public static function usingOne($file) {

        // remember the defined classes, include the $file and detect newly declared classes
        $preDeclared = get_declared_classes();
        require_once($file);
        // get a newly declared class
        $newClassArray = array_unique(array_diff(get_declared_classes(), $preDeclared));
        // reverse to get the latest class to avoid needless looping of previously loaded class and create aliases
        foreach (array_reverse($newClassArray) as $eachNewNamespaceClass) {
            $oneNamespaceClassArray = explode('\\', $eachNewNamespaceClass);
            if (count($oneNamespaceClassArray) > 1) {
                $justClassName = array_pop($oneNamespaceClassArray);
                if (!class_exists(strtolower($justClassName))) {
                    // create class alias point to fully qualified namespace class
                    class_alias($eachNewNamespaceClass, $justClassName);
//                    echo 'Trying to load ', $justClassName, ' via ', __METHOD__, "()\n";
                    break;
                }
            }
        }
    }

}
