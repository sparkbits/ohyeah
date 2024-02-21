<?php
    namespace ohyeah\config;

    use RuntimeException;
    use ohyeah\config\Iserializecontract as Iserializecontract;

    class Jsoncontract implements Iserializecontract
    {
        protected $data;

        public function __construct(){}
        public function __destruct(){}
        protected function __clone(){}

        public function import(mixed $data = null)
        {
            $obj = json_decode($data, FALSE);
            if (is_object($obj) == FALSE) {
                throw new RuntimeException("invalid source data", 100);
            }
            //verify all key are valid
            return $obj;
        }
        public function export(mixed $data)
        {
            if (get_class($data) != "stdClass") {
                throw new RuntimeException("invalid source data", 100);
            } else {
                $result = json_encode($data,JSON_PRETTY_PRINT);
                if ($result === FALSE) {
                    throw new RuntimeException("invalid source data", 100);
                } else {
                    return $result;
                }
            }
        }
    }
?>