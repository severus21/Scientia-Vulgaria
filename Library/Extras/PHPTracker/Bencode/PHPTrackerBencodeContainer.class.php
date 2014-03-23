<?php

/**
 * One piece of a decoded bencode container. Could be dictionary or list.
 *
 * @package 
 * @subpackage Bencode
 */
abstract class PHPTrackerBencodeContainer extends PHPTrackerBencodeAbstract
{
    /**
     * Intializing the object with its parsed value.
     *
     * Value is iterated and its values (and keys) are getting contained by the object.
     *
     * @param array $value
     */
    public function __construct(array $value=null){
        $this->value = array();
        if(!isset($value))
            return null;

        if(PHPTrackerBencodeBuilder::isDictionary($value)){
            foreach($value as $key => $sub_value){
                $this->contain($sub_value, new PHPTrackerBencodeString($key));
            }
        }else{
            foreach($value as $sub_value){
                $this->contain($sub_value);
            }
        }
    }

    /**
     * Represent the value of the object as PHP arrays and scalars.
     */
    public function represent(){
        $representation = array();
        foreach($this->value as $key => $sub_value){
            $representation[$key] = $sub_value->represent();
        }
        return $representation;
    }

    /**
     * Adds an item to the list/dictionary.
     *
     * @param PHPTrackerBencodeAbstract $sub_value
     * @param PHPTrackerBencodeString $key Only used for dictionaries.
     */
    abstract public function contain(PHPTrackerBencodeAbstract $sub_value, PHPTrackerBencodeString $key = null);
}
