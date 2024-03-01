<?php
    namespace ohyeah\db;

    interface Ipdowrapper
    {
        //CONSTANTS
         //Credentials
         const HOST = "host";
         const USER = "user";
         const PWD = "pwd";
         const DB = "db";
 
         //Metadata
         const METADATA_RETURNEDROWS = '_rows';
         const METADATA_RETURNEDCOLS = '_cols';
 
         //Configuration options
         const OPTIONS_INIT = "pdo-options-init";
         const FETCHMODE = "pdo-options-fetchmode";
         const OPTIONS_QUERY = "pdo-options-query";
         const OPTIONS_UPDATE = "pdo-options-update";
         const CHARSET = "pdo-db-charset";
         const DSNFIELDS = "pdo-db-dsnfields";
         const DSNSTRING = "pdo-db-dsnstring";
 
         //Factory settings
         const CONFIG_PATH = "pdo-db-conf";
         const AUTOCONNECT = FALSE;
         //Profile
         const PROFILE = "profile-name";
         
 
         //FORMATS
         const DB_DATE_FORMAT = "pdo-db-date-format";
         const DB_TIMEZONE = "pdo-db-timezone";

        //PUBLIC METHODS
        public function connect(array $credentials):void;                                            //Connect
        public function beginTran():void;                                                            //Commit Transaction  
        public function commit():void;                                                               //Apply commit
        public function rollBack():void;                                                             //Applie rollback
        public function query(string $strquery, Ipdoparams $params, bool $fetchData = TRUE):array;   //select statements
        public function exec(string $cmd):string;                                                 //Commands
        public function getMetadata(string $key):array;                                              //Retrieve Metadata
        public function setMetadata(string $key, mixed $value):void;                                 //set / add Metadatas
    }
?>