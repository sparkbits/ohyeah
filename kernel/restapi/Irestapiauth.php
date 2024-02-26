<?php
    interface Irestapiauth
    {
        public function grant(array $data):bool;
    }
?>