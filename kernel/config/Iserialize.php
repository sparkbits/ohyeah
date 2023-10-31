<?php
    namespace ohyeah\config;

    interface Iserialize
    {
        //Convert raw data (stdclass) into format defined by contract.
        public function serialize();
        //Convert formated data defined by contract into raw data (stdclass)
        public function unserialize(mixed $data = null);
        public function setContract(Iserializecontract $contact);
    }
?>