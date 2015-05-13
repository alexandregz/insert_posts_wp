<?php

$str = file_get_contents($argv[1]);

$lines = explode("\n", $str);

$contador = 0;
foreach($lines as $l) {
        if(trim($l) == '') continue;

        $contador++;

        echo $l."\n";;
        if(($contador % 2) == 0) echo "\n";
}
