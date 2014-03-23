<?php

/**
 * Decoded bencode list, consisting of mutiple values.
 *
 * @package PHPTracker
 * @subpackage Bencode
 */
class PHPTrackerBencodeList extends PHPTrackerBencodeContainer
{
    /**
     * Adds an item to the list.
     *
     * @param PHPTrackerBencodeAbstract $sub_value
     * @param PHPTrackerBencodeString $key Not used here.
     */
    public function contain(PHPTrackerBencodeAbstract $sub_value, PHPTrackerBencodeString $key = null){
        $this->value[]=$sub_value;
    }

    /**
     * Convert the object back to a bencoded string when used as string.
     */
    public function __toString(){
        $string_represent="l";
        foreach($this->value as $sub_value){
            $string_represent.=$sub_value;
        }
        return $string_represent."e";
    }
}
