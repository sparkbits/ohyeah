<?php
    namespace ohyeah\factory;

    use ohyeah\config\Featconfig;
    use ohyeah\config\Iconfig;
    use ohyeah\config\Configbase;
use stdClass;

    class Factory implements Ifactory
    {
        use Featconfig;

        public function __construct(?Iconfig $config = null)
        {                                     
            //Configuration relative to object behavior
            [$object_config, $factory_config] = $this->init();
            if (is_null($config) == FALSE) { 
                $object_config = Configbase::merge($config,$object_config, Iconfig::MERGE_ALL);
            }
            $this->FC_set($object_config, "__OBJECT__");
            $this->FC_set($factory_config, "__FACTORY__");
        }
        public function __destruct()
        {
            
        }
        //Where you initialize the object by default.
        protected function init()
        {
            $config = $this->FC_initialize("__OBJECT__");
            $fconfig = $this->FC_initialize("__FACTORY__");
            return [$config, $fconfig];
        }
        public function setConfigValue(string $key, string $value):void
        {
            $fconfig = $this->FC_get("__FACTORY__");
            $fconfig->set($key,$value);
            $this->FC_set($fconfig,"__FACTORY__");
            return;
        }
        //When you customize the object from init settings.
        public function get():object
        {
            return new stdClass();
        }
    }
?>