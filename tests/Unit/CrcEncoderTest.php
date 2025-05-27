<?php

namespace BafS\Tests\Unit;

use BafS\Crc\CrcFactory;
use BafS\Crc\CrcType;
use BafS\Crc\Encoder;
use BafS\Crc\Parameters;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CrcEncoderTest extends TestCase
{
    private static function createEncoderBase(mixed ...$args): Encoder
    {
        return new Encoder(...$args);
    }

    private static function createEncoderWithTable(mixed ...$args): Encoder
    {
        $encoder = new Encoder(...$args);
        $table = $encoder->generateTable();
        $encoder->setTable($table);

        return $encoder;
    }

    #[DataProvider('crcDataProvider')]
    public function testCrc(\Closure $encoderFactory, Parameters $params, int $expected, string $actual): void
    {
        $encoder = $encoderFactory($params);

        $result = $encoder->compute($actual);

        Assert::assertSame(
            $expected,
            $result,
            'It should be 0x' . dechex($expected) . ', got 0x' . dechex($result) . ' instead',
        );
    }

    public static function crcDataProvider(): iterable
    {
        yield from self::dataProviderGenerator([
            'createEncoderBase' => \Closure::fromCallable([self::class, 'createEncoderBase']),
        ], self::getCrcData());

        yield from self::dataProviderGenerator([
            'createEncoderBase' => \Closure::fromCallable([self::class, 'createEncoderBase']),
            'createEncoderWithTable' => \Closure::fromCallable([self::class, 'createEncoderWithTable']),
        ], self::getCrcDataBothEncoders());
    }

    private static function dataProviderGenerator(array $encoders, iterable $data): iterable
    {
        foreach ($encoders as $encoderName => $encoder) {
            foreach ($data as $crcName => $testDatas) {
                $param = CrcFactory::createParameters($crcName);

                foreach ($testDatas as $testData) {
                    $name = json_encode($testData);
                    if (strlen($name) >= 140) {
                        $name = substr($name, 0, 139) . '…';
                    }
                    yield "$crcName ($encoderName) with $name" => [$encoder, $param, ...$testData];
                }
            }
        }
    }

    private static function getCrcData(): array
    {
        return [
            CrcType::CRC5_EPC->value => [
                [0x12, '12345'],
                [0x04, '12345_()'],
                [0x0d, '12345h)O2=x!]'],
            ],
            CrcType::CRC5_ITU->value => [
                [0x4, '12345'], // 0x04
                [0x1e, '12345_()'],
                [0x10, '12345h)O2=x!]'],
            ],
//            CrcType::CRC5_USB->value => [
////                [0x5, '12345'],
//                [0x1d, '12345_()'], // 1d
////                [0x6, '12345h)O2=x!]'],
//            ],
        ];
    }

    private static function getCrcDataBothEncoders(): array
    {
        $longBuffer = str_repeat("a\t\0\n_h)O2=x!]", 1024);

        return [
            CrcType::CRC8->value => [
                [0x0, ''],
                [0xf4, '123456789'],
                [0x63, $longBuffer],
                [0x7, hex2bin('110093ff8a8b6c71')],
                [0x19, "test\t"],
                [0x7f, "longtest\t\0\n_h)O2=x!]\t\0\n_h)O2=x!]\t\0\n_h)O2=x!]\t\0\n_h)O2=x!]\t\0\n_h)O2=x!]\t\0\n_h)O2=x!]\t"],
            ],
            CrcType::CRC8_CDMA2000->value => [
                [0x79, "test\t"],
                [0xbf, hex2bin('110093ff8a8b6c71')],
            ],
            CrcType::CRC8_DARC->value => [
                [0x50, "test\t"],
                [0x8b, hex2bin('110093ff8a8b6c71')],
            ],
            CrcType::CRC8_DVB_S2->value => [
                [0xce, "test\t"],
                [0x57, hex2bin('110093ff8a8b6c71')],
            ],
            CrcType::CRC8_EBU->value => [
                [0x6f, "test\t"],
                [0x2b, hex2bin('110093ff8a8b6c71')],
            ],
            CrcType::CRC8_WCDMA->value => [
                [0x9b, "test\t"],
                [0xe5, hex2bin('110093ff8a8b6c71')],
            ],
            CrcType::CRC8_ITU->value => [
                [0xc2, '1'],
                [0x95, '123'],
                [0xa1, '123456789'],
                [0xd6, '123456()__@'],
                [0x52, hex2bin('110093ff8a8b6c71')],
            ],

            CrcType::CRC16_ARC->value => [
                [0x0, ''],
                [0xBB3D, '123456789'],
                [0xb33, hex2bin('00938a8b6c71')],
                [0x1ab8, "test\t"],
                [0x5aa2, "longtest\t\0\n_h)O2=x!]\t\0\n_h)O2=x!]\t\0\n_h)O2=x!]\t\0\n_h)O2=x!]\t\0\n_h)O2=x!]\t\0\n_h)O2=x!]\t"],
            ],
            CrcType::CRC16_AUG_CCITT->value => [
                [0x1d0f, ''],
                [0xe5cc, '123456789'],
                [0xa7af, hex2bin('00938a8b6c71')],
                [0xc499, "test\t\0\n_h)O2=x!]"],
            ],
            CrcType::CRC16_BUYPASS->value => [
                [0x0, ''],
                [0xfee8, '123456789'],
                [0xa2a, "test\t\0\n_h)O2=x!]"],
                [0xdb52, hex2bin('110093ff8a8b6c71')],
            ],
            CrcType::CRC16_CDMA2000->value => [
                [0xffff, ''],
                [0x4c06, '123456789'],
                [0x5962, hex2bin('110093ff8a8b6c71')],
            ],
            CrcType::CRC16_DDS_110->value => [
                [0x800d, ''],
                [0xd5b2, hex2bin('110093ff8a8b6c71')],
            ],
            CrcType::CRC16_DECT_R->value => [
                [0x1, ''],
                [0xf18, hex2bin('110093ff8a8b6c71')],
            ],
            CrcType::CRC16_DECT_X->value => [
                [0x0, ''],
                [0xf19, hex2bin('110093ff8a8b6c71')],
            ],
            CrcType::CRC16_DNP->value => [
                [0xffff, ''],
                [0x1d91, hex2bin('110093ff8a8b6c71')],
            ],
            CrcType::CRC16_EN_13757->value => [
                [0xffff, ''],
                [0x6d6c, hex2bin('110093ff8a8b6c71')],
            ],

            CrcType::CRC24_BLE->value => [
                [0xaaaaaa, ''],
                [0xc25a56, '123456789'],
                [0x26c33f, "test\t\0\n"],
                [0x1015e9, hex2bin('110093ff8a8b6c71')],
            ],
            CrcType::CRC24_INTERLAKEN->value => [
                [0x0, ''],
                [0xb4f3e6, '123456789'],
                [0x69ec0f, hex2bin('110093ff8a8b6c71')],
            ],
            CrcType::CRC24_OPENPGP->value => [
                [0xb704ce, ''],
                [0x21cf02, '123456789'],
                [0xec6cd2, "test\t\0\n"],
                [0x67dfd9, hex2bin('110093ff8a8b6c71')],
            ],

            CrcType::CRC32->value => [
                [0x0, ''],
                [0xe464dd64, $longBuffer],
                [0x56b8a82, hex2bin('110093ff8a8b6c71')],
            ],
            CrcType::CRC32_BZIP2->value => [
                [0x0, ''],
                [0x268399a6, hex2bin('110093ff8a8b6c71')],
            ],
            CrcType::CRC32C->value => [
                [0x0, ''],
                [0x9b98b449, hex2bin('110093ff8a8b6c71')],
            ],
            CrcType::CRC32_MPEG2->value => [
                [0xffffffff, ''],
                [0xd97c6659, hex2bin('110093ff8a8b6c71')],
            ],
        ];
    }
}
