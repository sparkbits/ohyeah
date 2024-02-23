<?php

    namespace ohyeah\db;

    use RuntimeException;
    use Exception;
    use ohyeah\config\Configbase;
    use ohyeah\config\Featconfig;
    use PDO;
    use PDOException;
    use ohyeah\config\Iconfig;

    class Pdowrapper implements Ipdowrapper
    {
        use Featconfig;

        protected $pdo = null;
        protected $metadata = array();
        

        public function __construct(Iconfig $config)
        {
            $lconfig = $this->FC_initialize();
            $lconfig = Configbase::merge($config, $lconfig);
            $this->FC_set($lconfig);
        }
        public function __destruct() { }

        
        //PROTECTED 
        protected function setCharset():void
        {
            $config = $this->FC_get();
            $this->exec("SET CHARACTER SET " . $config->get(Ipdowrapper::CHARSET));
            return;
        }
        protected function initMetadata():void
        {
            $this->metadata = array();
            $this->metadata[Ipdowrapper::METADATA_RETURNEDCOLS] = NULL;
            $this->metadata[Ipdowrapper::METADATA_RETURNEDROWS] = NULL;
            return;
        }
        protected function dsnStatement(array $credentials):string
        {            
            $config = $this->FC_get();
            $dsn = $config->get(Ipdowrapper::DSNSTRING);
            $keys = array_keys($credentials);
            foreach($keys as $key) {
                $dsn = str_replace("@@" . $key . "@@",$credentials[$key],$dsn);
            }
            return $dsn;
        }
        protected function getDsn(array $credentials)
        {
            $config = $this->FC_get();
            $data = $config->get(Ipdowrapper::DSNFIELDS);
            foreach($data as $key) {
                if (array_key_exists($key,$credentials) == TRUE) {
                    $cred[$key] = $credentials[$key];
                } else  {
                    throw new RuntimeException("Missing parameter $key in credentials", 1000);
                }
            }
            return $this->dsnStatement($cred);
        }        
        //Sugar side: Only called when no try catch is used in pdo->connect. This avoid to reveal user and password from zend engine.
        public static function exception_handler($exception)
        {
            throw new RuntimeException("[PDO error] cannot connect to Database",1000);
        }        
        public function connect(array $credentials):void
        {
            $config = $this->FC_get();
            set_exception_handler(array(__CLASS__, 'exception_handler'));
            $this->pdo = new PDO(
                $this->getDsn($credentials),
                $credentials[Ipdowrapper::USER],
                $credentials[Ipdowrapper::PWD],
                $config->get(Ipdowrapper::OPTIONS_INIT)
            );
            if (is_null($this->pdo) == TRUE) {
                throw new RuntimeException("Unable to connect to DB.",1000);
            }
            //Configure PDO to show errors and exceptions
            $options = $config->get(Ipdowrapper::OPTIONS_INIT);
            $keys = array_keys($options);
            foreach($keys as $key) {
                $this->pdo->setAttribute($key, $options[$key]);
            }
            $this->setCharset();
            restore_exception_handler();
        }
        public function beginTran():void
        {
            if(is_null($this->pdo) == TRUE) {
                throw new RuntimeException("Not connected to DB. Transaction not possible.", 1000);
            }
            if ($this->pdo->inTransaction() == TRUE || $this->pdo->beginTransaction() == FALSE) {
                //for debug purposes
                $error = $this->pdo->errorInfo();
                throw new RuntimeException("No active transacion: error.", 1000);
            }
            return;
        }
        public function commit():void
        {
            if(is_null($this->pdo) == TRUE) {
                throw new RuntimeException("Not connected to DB. Transaction not possible.", 1000);
            }
            if ($this->pdo->inTransaction() == FALSE || $this->pdo->commit() == FALSE) {

                //for debug purposes
                $error = $this->pdo->errorInfo();
                throw new RuntimeException("No active transacion.", 1000);
            }
            return;
        }
        public function rollBack():void
        {
            if(is_null($this->pdo) == TRUE) {
                throw new RuntimeException("Not connected to DB. Transaction not possible.", 1000);
            }
            if (($this->pdo->inTransaction() == FALSE) || ($this->pdo->rollBack() == FALSE)) {
                //for debug purposes
                $error = $this->pdo->errorInfo();
                throw new RuntimeException("No active transacion: error.", 1000);
             }
            return;
        }
        public function query(string $strquery, Ipdoparams $params, bool $fetchData = TRUE):array
        {
            if(is_null($this->pdo) == TRUE) {
                throw new RuntimeException("Not connected to DB. Query not possible.", 1000);
            }
            try {
                $config = $this->FC_get();
                $this->initMetadata();
                $fetch = $config->get(Ipdowrapper::FETCHMODE);
                $sth = $this->pdo->prepare($strquery,$config->get(Ipdowrapper::OPTIONS_QUERY));
                $sth->execute($params->get());
                if ($fetchData == TRUE) {
                    $rows = $sth->fetchAll($fetch);
                    $this->setMetadata(Ipdowrapper::METADATA_RETURNEDCOLS,(count($rows) == 0) ? 0:count($rows[0]));
                } else {
                    $rows = array();
                }
                $this->setMetadata(Ipdowrapper::METADATA_RETURNEDROWS,$sth->rowCount());
                return $rows;
            }catch(RuntimeException $e) {
                //for debug purposes
                $error = $sth->errorInfo();
                throw new RuntimeException("Error executing query: " . $e->getMessage(),1000,$e);
            }catch(PDOException $e) {
                //for debug purposes
                $error = $sth->errorInfo();
                throw new RuntimeException("Error executing query: " . $e->getMessage(),1000,$e);
            }catch(Exception $e) {
                throw new RuntimeException("unexpeted error executing query:" . $e->getmessage(),1000,$e);
            }

        }
        public function exec(string $cmd):string
        {
            if(is_null($this->pdo) == TRUE) {
                throw new RuntimeException("Not connected to DB. Query not possible.", 1000);
            }
            try {
                return $this->pdo->exec($cmd);
            }catch (RuntimeException $e) {
                throw new RuntimeException("Error executing query: " . $e->getMessage(),1000,$e);
            }catch(Exception $e) {
                throw new RuntimeException("Unexpected error executing query: " . $e->getMessage(),1000,$e);
            }catch(PDOException $e) {
                //for debug purposes
                throw new RuntimeException("Error executing query: " . $e->getMessage(),1000,$e);
            }
        }
        public function getMetadata(string $key):array
        {
            if (array_key_exists($key,(array) $this->metadata) == TRUE) {
                return $this->metadata[$key];
            } else {
                throw new RuntimeException("Uanble to find $key in metadata",1000);
            }
        }
        public function setMetadata(string $key, mixed $value):void
        {
            $this->metadata[$key] = $value;
        }
    }
?>