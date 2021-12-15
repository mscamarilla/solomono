<?php


namespace Core;


/**
 * Interface DatabaseInterface
 * @package Core
 */
interface DatabaseInterface
{
    /**
     * @return mixed
     */
    public function makeConnection();

}