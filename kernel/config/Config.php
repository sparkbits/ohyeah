<?php
    namespace ohyeah\config;

use RuntimeException;

    class Config extends Configbase implements Iserialize
    {
        protected Iserializecontract $contract;

        public function __construct()
        {
            parent::__construct();
            $this->contract = new Jsoncontract();
        }
        public function __destruct(){}
        protected function __clone(){}

        protected function checkDataIntegrity(mixed $data) : bool
        {
            if (get_class($data) != "stdClass") {
                return FALSE;
            }
            foreach($data as $property => $value) {
                if ($this->checkKeyIntegrity($property) == FALSE) {
                    return FALSE;
                }
                //check it is a STDCLASS
                if (get_class($value) != "stdClass") {
                    return FALSE;
                }
                foreach($value as $keyproperty => $keyvalue) {
                    if ($this->checkKeyIntegrity($keyproperty) == FALSE) {
                        return FALSE;
                    }
                }
            }
            return TRUE;
        }
        //Convert raw data (stdclass) into format defined by contract.
        public function serialize()
        {
            return $this->contract->export($this->data);
        }
        //Convert formated data defined by contract into raw data (stdclass)
        public function unserialize(mixed $data = null)
        {
            $obj = $this->contract->import($data);
            if ($this->checkDataIntegrity($obj) == TRUE) {
                $this->data = $obj;
            } else {
                throw new RuntimeException("Invalid Data.", 100);
            }
        }
        public function setContract(Iserializecontract $contract)
        {
            $this->contract = $contract;
        }
        public function getData()
        {
            return $this->data;
        }
    }
?>