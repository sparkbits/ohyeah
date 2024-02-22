<?php
    namespace ohyeah\config;

    interface Iconfig
    {
        const CONFIG_KEY_LENGTH = 32;
        const MERGE_ALL = "merge-all";
        const MERGE_ONLYOVERRIDE = "merge-override";
        const MERGE_ONLYNEW = "merge-new";

        public function setNamespace(string $namespace);
        public function get(string $key, ?string $namespace);
        public function set(string $key, mixed $value, ?string $namespace = null);
        public function nameSpaces():array;
        public function keys(string $namespace):array;
        public function existsNameSpace(string $namespace):bool;
        public function existKey(string $namespace, string $key) : bool;
        public static function merge(Iconfig $source, Iconfig $target, string $type = Iconfig::MERGE_ALL):Iconfig;
    }
?>