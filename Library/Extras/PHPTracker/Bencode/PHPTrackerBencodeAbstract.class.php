<?php

/**
 * One piece of a decoded bencode value. Could be integer, string, dictionary or list.
 *
 * @package 
 * @subpackage Bencode
 */
abstract class PHPTrackerBencodeAbstract{
    /**
     * PHP representation of the value that this object holds.
     *
     * @var mixed
     */
    protected $value;

    /**
     * Intializing the object with its parsed value.
     *
     * @param mixed $value
     */
    abstract public function __construct( $value );

    /**
     * Convert the object back to a bencoded string when used as string.
     */
    abstract public function __toString();

    /**
     * Represent the value of the object as PHP arrays and scalars.
     */
    abstract public function represent();
}
