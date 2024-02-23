<?php
    namespace ohyeah\db;

    interface Ipdoparams 
    {
        public function get():array;
        public function set(string $key, mixed $values):void;
        public function clear():void;
    }
?>