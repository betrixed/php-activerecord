<?php

/**
 * @package ActiveRecord
 */

namespace ActiveRecord {

    /**
     * This implementation of the singleton pattern does not conform to the strong definition
     * given by the "Gang of Four." The __construct() method has not be privatized so that
     * a singleton pattern is capable of being achieved; however, multiple instantiations are also
     * possible. This allows the user more freedom with this pattern.
     *
     * @package ActiveRecord
     */
    abstract class Singleton {

        /**
         * Static method for instantiating a singleton object.
         *
         * @return object
         */
        final public static function instance() {
            global $Singletons;
            $class_name = get_called_class();

            $result = $Singletons[$class_name] ?? null;

            if ($result === null) {
                $result = new $class_name;
                $Singletons[$class_name] = $result;
            }
            return $result;
        }

        /**
         * Singleton objects should not be cloned.
         *
         * @return void
         */
        final private function __clone() {
            
        }

        /**
         * Similar to a get_called_class() for a child class to invoke.
         *
         * @return string
         */
        final protected function get_called_class() {
            $backtrace = debug_backtrace();
            return get_class($backtrace[2]['object']);
        }

    }

} // End namespace Active Record

namespace {
    // global namespace, where we can have "globals"
    global $Singletons;
    /**
     * Public global array of cached singleton objects.
     */
    $Singletons = [];
}
