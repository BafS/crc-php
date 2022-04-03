<?php

use BafS\Crc\CrcFactory;
use BafS\Crc\Encoder;

require __DIR__ . '/../vendor/autoload.php';

$params = CrcFactory::createParameters(CrcFactory::CRC24_OPENPGP);
$table = (new Encoder($params))->generateTable();

$acc = '';
foreach ($table as $n => $item) {
    $acc .= '0x' . str_pad(dechex($item), $params->width / 4, '0', STR_PAD_LEFT) . ', ';
    if (($n + 1) % 8 === 0) {
        $acc .= "\n";
    }
}

echo '[' . trim($acc, "\n ,") . ']';
