<?php

declare(strict_types=1);

namespace BafS\Crc\Tests\Unit;

use BafS\Crc\CrcFactory;
use BafS\Crc\CrcType;
use BafS\Crc\Encoder;
use BafS\Crc\Parameters;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Every algorithm must produce its catalogued "check" value for the ASCII input "123456789".
 *
 * @see https://reveng.sourceforge.io/crc-catalogue/all.htm
 */
final class CheckValueTest extends TestCase
{
    private const CHECK_INPUT = '123456789';

    private const CHECK_VALUES = [
        'CRC-5/EPC-C1G2' => 0x00,
        'CRC-5/G-704' => 0x07,
        'CRC-8' => 0xf4,
        'CRC-8/CDMA2000' => 0xda,
        'CRC-8/DARC' => 0x15,
        'CRC-8/DVB-S2' => 0xbc,
        'CRC-8/EBU' => 0x97,
        'CRC-8/I-CODE' => 0x7e,
        'CRC-8/I-432-1' => 0xa1,
        'CRC-8/MAXIM' => 0xa1,
        'CRC-8/ROHC' => 0xd0,
        'CRC-8/WCDMA' => 0x25,
        'CRC-16/CCITT-FALSE' => 0x29b1,
        'CRC-16/ARC' => 0xbb3d,
        'CRC-16/AUG-CCITT' => 0xe5cc,
        'CRC-16/BUYPASS' => 0xfee8,
        'CRC-16/CDMA2000' => 0x4c06,
        'CRC-16/DDS-110' => 0x9ecf,
        'CRC-16/DECT-R' => 0x007e,
        'CRC-16/DECT-X' => 0x007f,
        'CRC-16/DNP' => 0xea82,
        'CRC-16/EN-13757' => 0xc2b7,
        'CRC-16/GENIBUS' => 0xd64e,
        'CRC-16/MAXIM' => 0x44c2,
        'CRC-16/MCRF4XX' => 0x6f91,
        'CRC-16/RIELLO' => 0x63d0,
        'CRC-16/T10-DIF' => 0xd0db,
        'CRC-16/TELEDISK' => 0x0fb3,
        'CRC-16/TMS37157' => 0x26b1,
        'CRC-16/USB' => 0xb4c8,
        'CRC-A' => 0xbf05,
        'CRC-16/KERMIT' => 0x2189,
        'CRC-16/MODBUS' => 0x4b37,
        'CRC-16/X-25' => 0x906e,
        'CRC-16/XMODEM' => 0x31c3,
        'CRC-24/BLE' => 0xc25a56,
        'CRC-24/INTERLAKEN' => 0xb4f3e6,
        'CRC-24/OPENPGP' => 0x21cf02,
        'CRC-32' => 0xcbf43926,
        'CRC-32/BZIP2' => 0xfc891918,
        'CRC-32C' => 0xe3069283,
        'CRC-32D' => 0x87315576,
        'CRC-32/JAMCRC' => 0x340bc6d9,
        'CRC-32/MPEG-2' => 0x0376e6e7,
        'CRC-32/POSIX' => 0x765e7680,
        'CRC-32Q' => 0x3010bf7f,
        'CRC-32/XFER' => 0xbd0be338,
    ];

    public function testEveryCrcTypeHasACheckValue(): void
    {
        $names = array_map(static fn (CrcType $type): string => $type->value, CrcType::cases());

        self::assertEqualsCanonicalizing($names, array_keys(self::CHECK_VALUES));
    }

    #[DataProvider('checkValueProvider')]
    public function testCheckValue(Parameters $params, int $expected): void
    {
        $encoder = new Encoder($params);
        self::assertSame($expected, $encoder->compute(self::CHECK_INPUT), 'bitwise');

        if ($params->width >= 8) {
            $encoder->setTable($encoder->generateTable());
            self::assertSame($expected, $encoder->compute(self::CHECK_INPUT), 'with lookup table');
        }
    }

    /** @return iterable<string, array{Parameters, int}> */
    public static function checkValueProvider(): iterable
    {
        foreach (CrcType::cases() as $type) {
            yield $type->value => [CrcFactory::createParameters($type), self::CHECK_VALUES[$type->value]];
        }

        // Custom parameters covering refin != refout and widths beyond 32 bits.
        yield 'CRC-12/UMTS' => [new Parameters(12, 0x80f, 0x000, false, true, 0x000), 0xdaf];
        yield 'CRC-31/PHILIPS' => [new Parameters(31, 0x04c11db7, 0x7fffffff, false, false, 0x7fffffff), 0x0ce9e46c];
        yield 'CRC-40/GSM' => [new Parameters(40, 0x0004820009, 0x0, false, false, 0xffffffffff), 0xd4164fc646];

        // No catalogued 63-bit CRC: only checks that the table path matches the bitwise one at max width.
        $max = new Parameters(PHP_INT_SIZE * 8 - 1, 0x1b, PHP_INT_MAX, true, true, 0x0);
        yield 'max width consistency' => [$max, (new Encoder($max))->compute(self::CHECK_INPUT)];
    }
}
