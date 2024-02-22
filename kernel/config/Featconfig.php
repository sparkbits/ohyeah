<?php
    namespace ohyeah\config;
    
    use RuntimeException;
    use stdClass;

    trait Featconfig
    {
        protected $config_container;

        protected  function FC_initialize($context = "__SELF__"):Iconfig
        {
            if (empty($context) == TRUE || is_string($context) == FALSE) {
                throw new RuntimeException("Invalid Context.",1000);
            }
            if (is_null($this->config_container) == TRUE) {
                $this->config_container = new stdClass();
            }
            if (property_exists($this->config_container,$context) == FALSE) {
                $this->config_container->$context = new Config();
                $this->config_container->$context->setNamespace();
            }
            return $this->config_container->$context;
        }
        public function FC_get($context = "__SELF__")
        {
            $this->FC_initialize($context);
            if (property_exists($this->config_container,$context) == FALSE) {
                throw new RuntimeException("Unable to retrieve configuration from $context", 1050);
            } else {
                return $this->config_container->$context;
            }
        }
        public function FC_set(Iconfig $config, $context = "__SELF__"):void
        {
            $this->FC_initialize($context);
            $this->config_container->$context = $config;
        }
        public function FC_contexts():array
        {
            return (is_null($this->config_container) == FALSE) ? array_keys((array) $this->config_container):array();
        }
    }
?>