<?php
    namespace ohyeah\restapi;
    
    interface Irestapiauth
    {
        const APIKEY_NAME = "rapi-index-name";

        public function grant(array $data):bool;
    }
?>