<?php
    namespace ohyeah\restapi;

    use ohyeah\config\Iconfig;
    use ohyeah\config\Featconfig;
    use ohyeah\db\Featpdo;
    use ohyeah\db\Ipdowrapper;
    use ohyeah\db\Pdoparams;
    use RuntimeException;

    class Restapiauth implements Irestapiauth
    {
        use Featpdo;
        use Featconfig;

        public function __construct(Iconfig $config, Ipdowrapper $pdo)
        {
            $this->FC_set($config, "AUTH");
            $this->FPDO_set($pdo);
        }
        public function __destruct()
        {
            
        }
        public function grant(array $data):bool
        {
            $config = $this->FC_get("AUTH");
            $pdo = $this->FPDO_get();
            $params = new Pdoparams();
            //Checking index
            if (array_key_exists($config->get(Irestapiauth::APIKEY_NAME), $data) == FALSE) {
                throw new RuntimeException("Invalid key.");
            } else {
                $params->set($config->get(Irestapiauth::APIKEY_NAME),$data[$config->get(Irestapiauth::APIKEY_NAME)]);
            }
            $strquery = "select `apikey` from `apikeys` where `apikey`=?";
            $row = $pdo->query($strquery, $params);
            return (count($row) == 1) ? TRUE:FALSE;            
        }
    }
?>