<?php

/**
 * Class creating bencoded values out of PHP values (arrays, scalars).
 *
 * @package PHPTracker
 * @subpackage Bencode
 */
class PHPTrackerBencodeBuilder{
    /**
     * Given an input value, converts it to a bencode value class.
     *
     * @param mixed $input Any PHP scalar or array containing arrays of scalars.
     * @return PHPTracker_Bencode_Value_Abstract
     */
    static public function build($input){
        if(is_int($input))
            return new PHPTrackerBencodeInteger($input);
        
        if(is_string($input))
            return new PHPTrackerBencodeString($input);

        if(is_array($input)){
            // Creating sub-elements to construct list/dictionary.
            $constructor_input = array();
            foreach($input as $key => $value){
                $constructor_input[$key]=self::build($value);
            }

            if(self::isDictionary($input))
                return new PHPTrackerBencodeDictionary($constructor_input);
            else
                return new PHPTrackerBencodeList($constructor_input);
        }

        throw new BencodeBuildException( "Invalid input type when building: " . gettype( $input ) );
    }

    /**
     * Tries to tell if an array is associative or an indexed list.
     *
     * @param array $array
     * @return boolean True if the array looks like associative.
     */
    static public function isDictionary( array $array ){
        // Checking if the keys are ordered numbers starting from 0.
        return array_keys($array) !== range(0,(count($array)-1));
    }
}
