<?php
    namespace ohyeah\config;

    interface Iserializecontract
    {
        //convert external data accepted by contract (e.g. json) into raw data (stdclass)
        public function import(mixed $data = null);
        //export rawdata according the contract
        public function export($data);
    }
?>