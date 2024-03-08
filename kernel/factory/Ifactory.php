<?php
    namespace ohyeah\factory;

    interface Ifactory
    {
        public function get():object;
        public function setConfigValue(string $key, string $value):void;
    }

?>