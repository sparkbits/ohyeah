<?php
    namespace ohyeah\config;

    interface Iconfig
    {
        const CONFIG_KEY_LENGTH = 32;

        public function setNamespace(string $namespace);
        public function get(string $key, ?string $namespace);
        public function set(string $key, mixed $value, ?string $namespace);
    }
?>