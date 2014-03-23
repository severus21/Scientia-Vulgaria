<?php

/**
 * Decoded bencode dictionary, consisting of key-value pairs.
 *
 * @package 
 * @subpackage Bencode
 */
class PHPTrackerBencodeDictionary extends PHPTrackerBencodeContainer
{
    /**
     * Adds an item to the dictionary.
     *
     * @param PHPTrackerBencodeAbstract $sub_value
     * @param PHPTrackerBencodeString $key
     */
    public function contain( PHPTrackerBencodeAbstract $sub_value, PHPTrackerBencodeString $key = null ){
        if(!isset($key)){
            throw new BencodeInvalidTypeException( "Invalid key value for dictionary: $sub_value" );
        }
        if(isset($this->value[$key->value])){
            throw new BencodeInvalidValueException( "Duplicate key in dictionary: $key->value" );
        }
        $this->value[$key->value]=$sub_value;
    }

    /**
     * Convert the object back to a bencoded string when used as string.
     */
    public function __toString(){
        // All keys must be byte strings and must appear in lexicographical order.
        ksort($this->value);

        $stringRepresent = "d";
        foreach($this->value as $key => $sub_value ){
			$key = new PHPTrackerBencodeString( $key );
            $stringRepresent.=$key.$sub_value;
        }
        return $stringRepresent."e";
    }
}
