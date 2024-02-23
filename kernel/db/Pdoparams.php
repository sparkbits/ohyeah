<?php
    namespace ohyeah\db;

    
    class Pdoparams implements Ipdoparams 
    {
        protected $data = array();

        public function __construct()
        {
            
        }
        function __destruct()
        {
            
        }
        public function get():array
        {
            return array_values($this->data);
        }
        public function set(string $key, mixed $values):void
        {
            //TODO: Check key length
            $this->data[$key] = $values;
            return;
        }
        public function clear():void
        {
            $this->data = array();
            return;
        }
    }
?>