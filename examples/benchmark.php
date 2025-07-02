<?php

declare(strict_types=1);

use BafS\Crc\CrcFactory;
use BafS\Crc\CrcType;

require __DIR__ . '/../vendor/autoload.php';

function outputInfo(array $ruStart, array $ru, int|float $memPeak, int|float $startTime, int|float $endTime): void
{
    $rutime = static fn (array $ru, array $rus, string $index): int|float =>
        ($ru["ru_$index.tv_sec"] * 1000 + (int) ($ru["ru_$index.tv_usec"] / 1000))
            - ($rus["ru_$index.tv_sec"] * 1000 + (int) ($rus["ru_$index.tv_usec"] / 1000));

    echo 'Computation:  ' . $rutime($ru, $ruStart, 'utime') . " ms\n";
    echo 'System calls: ' . $rutime($ru, $ruStart, 'stime') . " ms\n";

    $timeElapsed = (int) (($endTime - $startTime) * 1000);
    $peak = round($memPeak / 1024 / 1024, 2);
    echo "Memory peak:  $peak Mo\n";
    echo "Time elapsed: $timeElapsed ms\n";
}

// Settings
const ROUNDS = 5;
$bytes8 = random_bytes(8);
$bytes1024 = random_bytes(1024);
$bytes10241024 = random_bytes(1024 ** 2);

// ----------------------------------------------
echo "------[ CRC computation WITHOUT memoization ]------\n";
$ruStart = getrusage();
$startTime = microtime(true);

for ($i = ROUNDS; $i--;) {
    CrcFactory::create(CrcType::CRC16_BUYPASS)->compute($bytes8);
    CrcFactory::create(CrcType::CRC32)->compute($bytes8);
    CrcFactory::create(CrcType::CRC32)->compute($bytes10241024);
    CrcFactory::create(CrcType::CRC24_OPENPGP)->compute($bytes1024);
}

$endTime = microtime(true);
$memPeak = memory_get_peak_usage();
$ru = getrusage();
outputInfo($ruStart, $ru, $memPeak, $startTime, $endTime);
echo "\n";
// ----------------------------------------------

// ----------------------------------------------
echo "------[ CRC computation WITH memoization ]------\n";
$crc16 = CrcFactory::create(CrcType::CRC16_BUYPASS);
$crc16->setTable($crc16->generateTable());
$crc24 = CrcFactory::create(CrcType::CRC24_OPENPGP);
$crc24->setTable($crc24->generateTable());
$crc32 = CrcFactory::create(CrcType::CRC32);
$crc32->setTable($crc32->generateTable());

$ruStart = getrusage();
$startTime = microtime(true);

for ($i = ROUNDS; $i--;) {
    $crc16->compute($bytes8);
    $crc32->compute($bytes8);
    $crc32->compute($bytes10241024);
    $crc24->compute($bytes1024);
}

$endTime = microtime(true);
$memPeak = memory_get_peak_usage();
$ru = getrusage();
outputInfo($ruStart, $ru, $memPeak, $startTime, $endTime);
// ----------------------------------------------
