<?php
    namespace ohyeah\db;

    trait Featpdo
    {
        protected $pdow = NULL;

        public function FPDO_get()
        {
            return $this->pdow;
        }
        public function FPDO_set(Ipdowrapper $pdo)
        {
            $this->pdow = $pdo;        
        }
    }
?>