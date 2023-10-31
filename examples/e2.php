<?php


    require_once "../kernel/config/Iconfig.php";
    require_once "../kernel/config/Iserialize.php";
    require_once "../kernel/config/Iserializecontract.php";
    require_once "../kernel/config/Configbase.php";
    require_once "../kernel/config/Config.php";
    require_once "../kernel/config/Jsoncontract.php";


    use ohyeah\config\Config as Config;

    try {
        $c = new Config();
        $index = str_repeat("a",31);
        $c->setNamespace("master");
        $c->set("value-one1", "one1");
        $c->set("value-one2", "one2");
        $c->set("value-one3", "one3");
        $c->set("value-one1", "two1", "slave");
        $c->set("value-one2", "two2", "slave");
        $c->set("value-one3", "two3", "slave");
        print($c->get("value-one1") . PHP_EOL);
        print($c->get("value-one1", "slave") . PHP_EOL);
        $c->setNamespace("slave");
        print($c->get("value-one2") . PHP_EOL);
        print($c->get("value-one3") . PHP_EOL);
        $c->set($index, "two3", "slave");
        $d = $c->getData();
        foreach($d as $property=>$value) {
            echo $property;
            if (get_class($value) != "stdClass") {
                echo "CACA";
            }
            foreach($value as $key=>$keyvalue) {
                echo $key . PHP_EOL;
            }
        }
    } catch(RuntimeException $e) {
        echo $e->getMessage();
    }
?>