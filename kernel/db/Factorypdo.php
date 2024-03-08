<?php
    namespace ohyeah\db;

    use ohyeah\factory\Factory;
    use ohyeah\config\Iconfig;
    use PDO;
    use RuntimeException;
    use ohyeah\config\Config;
    use ohyeah\config\Configbase;

    class Factorypdo extends Factory
    {
        public function __construct(?Iconfig $config = null)
        {
            parent::__construct($config);
        }
        protected function init()
        {
            [$config, $fconfig] = parent::init();
            //Specific initialization by default.
            $config->set(Ipdowrapper::CHARSET, "utf8");
            $config->set(Ipdowrapper::OPTIONS_INIT, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $config->set(Ipdowrapper::OPTIONS_QUERY, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $config->set(Ipdowrapper::OPTIONS_UPDATE, array());
            $config->set(Ipdowrapper::FETCHMODE, PDO::FETCH_ASSOC);
            $config->set(Ipdowrapper::DSNFIELDS, array(Ipdowrapper::HOST,Ipdowrapper::USER,Ipdowrapper::PWD,Ipdowrapper::DB));
            $config->set(Ipdowrapper::DSNSTRING, "mysql:host=@@host@@;dbname=@@db@@");            
            $config->set(Ipdowrapper::DB_DATE_FORMAT, "Y-m-d H:i:s");
            $config->set(Ipdowrapper::DB_TIMEZONE, "UTC");
            $fconfig->set(Ipdowrapper::CONFIG_PATH,dirname(__DIR__) . DIRECTORY_SEPARATOR . 'settings/db.json');
            $fconfig->set(Ipdowrapper::PROFILE, "main");
            $fconfig->set(Ipdowrapper::AUTOCONNECT, FALSE);
            return [$config, $fconfig];
        }
        public function get():object
        {
            $config = $this->FC_get("__OBJECT__");
            $fconfig = $this->FC_get("__FACTORY__");
            //Load File with settings
            $file = $fconfig->get(Ipdowrapper::CONFIG_PATH);
            if (file_exists($file) == FALSE) {
                throw new RuntimeException("File not found.");
            }
            $data = json_decode(file_get_contents($file), TRUE);
            if (is_null($data) == TRUE) {
                throw new RuntimeException("Invalid file format.");
            }
            //Create a new Config File to be merged with the current one
            $profile = $fconfig->get(Ipdowrapper::PROFILE);
            if (array_key_exists($profile, $data) == FALSE) {
                throw new RuntimeException("Missing profile.");
            }
            $keys = array_Keys($data[$profile]);
            $lconfig = new Config();
            foreach($keys as $key) {
                switch($key) {
                    case Ipdowrapper::HOST:
                    case Ipdowrapper::DB:
                    case Ipdowrapper::USER:
                    case Ipdowrapper::PWD:
                        $credentials[$key] = $data[$profile][$key];
                        break;
                    default:
                        $lconfig->set($key, $data[$profile][$key]);
                }
            }            
            $config = Configbase::merge($lconfig, $config, Iconfig::MERGE_ALL);
            $pdo = new Pdowrapper($config);
            $pdo->connect($credentials);            
            return $pdo;
        }
        
        public static function configFile (string $profile, string $filename, string $host, string $db, string $user, string $pwd)
        {
            if (file_exists($filename) == TRUE) {
                $data = json_decode(file_get_contents($filename), TRUE);
            }
            $data[$profile][Ipdowrapper::HOST] = $host;
            $data[$profile][Ipdowrapper::DB] = $db;
            $data[$profile][Ipdowrapper::USER] = $user;
            $data[$profile][Ipdowrapper::PWD] = $pwd;
            file_put_contents(json_encode($data), $filename);
            return;
        }
    }
?>