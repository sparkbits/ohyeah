<?php
    namespace ohyeah\config;

    use ohyeah\config\Iconfig as Iconfig;
    use RuntimeException;
    use stdClass;

    class Configbase implements Iconfig
    {
        protected string $namespace;
        protected $data;

        public function __construct()
        {
            $this->data = new stdClass();
            $this->namespace = "main";
        }
        public function __destruct(){}
        protected function __clone(){}

        protected function existsNameSpace(string $namespace)
        {
            return property_exists($this->data,$namespace);
        }
        protected function existKey(string $namespace, string $key) : bool
        {
            if ($this->existsNameSpace($namespace) == TRUE) {
                return property_exists($this->data->{$namespace},$key);
            } else {
                return FALSE;
            }
        }
        protected function checkKeyIntegrity(string $key) : bool
        {
            if ((strlen($key) > Iconfig::CONFIG_KEY_LENGTH) || (strlen($key) < 1)) {
                return FALSE;
            }
            if (preg_match('/^[a-zA-Z][a-zA-Z0-9\-]*$/', $key)) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
        public function setNamespace(string $namespace)
        {
            if (property_exists($this->data,$namespace) == FALSE) {
                $this->data->{$namespace} = new stdClass();
            }
            $this->namespace = $namespace;
        }
        public function get(string $key, ?string $namespace = null) : mixed
        {
            $n = (is_null($namespace) == TRUE) ? $this->namespace:$namespace;
            if ($this->existKey($n, $key) === TRUE) {
                return $this->data->{$n}->{$key};
            } else {
                throw new RuntimeException("key not found.", 100);
            }
        }
        public function set(string $key, mixed $value, ?string $namespace = null)
        {
            $n = (is_null($namespace) == TRUE) ? $this->namespace:$namespace;
            if ($this->existsNameSpace($n) == FALSE) {
                if ($this->checkKeyIntegrity($n) == TRUE) {
                    $this->data->{$n} = new stdClass();
                } else {
                    throw new RuntimeException("The key violate the key policy",300);
                }
            }
            if ($this->checkKeyIntegrity($key) == TRUE) {
                $this->data->{$n}->{$key} = $value;
            } else {
                throw new RuntimeException("The key violate the key policy",300);
            }
            return;
        }
    }
?>