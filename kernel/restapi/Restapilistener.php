<?php
namespace ohyeah\restapi;

use ohyeah\config\Iconfig;

class Restapilistener implements Irestapilisterner
{
    public function __construct(?Iconfig $config = null)
    {

    }
    public function listen():string
    {
        //Detection of type of METHOD
        //$_SERVER['REQUEST_METHOD']
        return "";
    }
}
?>