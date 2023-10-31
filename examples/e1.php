<?php



    require_once "../kernel/config/Iserializecontract.php";
    require_once "../kernel/config/Jsoncontract.php";

    use ohyeah\config\Jsoncontract as Jsoncontract;

    try {
        $c = new Jsoncontract();
        $b = new stdClass();
            for ($y=1;$y<3;$y++) {
                $namespace = "namespace-$y";
                $b->{$namespace} = new stdClass();
                for ($x=1;$x<10;$x++) {
                    $key = "my-key-$x";
                    $b->{$namespace}->{$key} = $x;
                }
            }
        //convert config into json
        $r = $c->export($b);
        print($r);
        $s = $c->import($r);
        print_r($s);

    } catch(RuntimeException $e) {
        echo $e->getMessage();
    }
?>