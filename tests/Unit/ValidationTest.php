<?php

declare(strict_types=1);

namespace BafS\Crc\Tests\Unit;

use BafS\Crc\CrcFactory;
use BafS\Crc\CrcType;
use BafS\Crc\Encoder;
use BafS\Crc\Parameters;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ValidationTest extends TestCase
{
    #[DataProvider('invalidParametersProvider')]
    public function testInvalidParameters(int $width, int $poly, int $init, int $xorOut): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Parameters($width, $poly, $init, false, false, $xorOut);
    }

    /** @return iterable<string, array{int, int, int, int}> */
    public static function invalidParametersProvider(): iterable
    {
        yield 'width 0' => [0, 0x1, 0x0, 0x0];
        yield 'negative width' => [-8, 0x1, 0x0, 0x0];
        yield 'width 64 (does not fit in a PHP int)' => [64, 0x1b, 0x0, 0x0];
        yield 'poly too large' => [8, 0x107, 0x0, 0x0];
        yield 'init too large' => [16, 0x1021, 0x10000, 0x0];
        yield 'xorout too large' => [5, 0x09, 0x0, 0x20];
        yield 'negative poly' => [32, -1, 0x0, 0x0];
    }

    public function testMaxWidthIsAccepted(): void
    {
        $params = new Parameters(Parameters::MAX_WIDTH, PHP_INT_MAX, PHP_INT_MAX, xorOutput: PHP_INT_MAX);

        self::assertSame(Parameters::MAX_WIDTH, $params->width);
    }

    #[DataProvider('invalidTableProvider')]
    public function testInvalidTable(CrcType $type, array $table): void
    {
        $this->expectException(\InvalidArgumentException::class);

        CrcFactory::create($type, $table);
    }

    /** @return iterable<string, array{CrcType, array}> */
    public static function invalidTableProvider(): iterable
    {
        $valid = (new Encoder(CrcFactory::createParameters(CrcType::CRC8)))->generateTable();

        yield 'width < 8' => [CrcType::CRC5_EPC, array_fill(0, 256, 0)];
        yield 'too short' => [CrcType::CRC8, [1, 2, 3]];
        yield 'too long' => [CrcType::CRC8, [...$valid, 0]];
        yield 'not a list' => [CrcType::CRC8, array_combine(range(1, 256), $valid)];
        yield 'value wider than CRC' => [CrcType::CRC8, [0x100, ...array_slice($valid, 1)]];
        yield 'negative value' => [CrcType::CRC8, [-1, ...array_slice($valid, 1)]];
        yield 'non-int value' => [CrcType::CRC8, ['0', ...array_slice($valid, 1)]];
    }

    public function testUnknownCrcName(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        CrcFactory::createParameters('CRC-99/NOPE');
    }

    public function testCreateParametersAcceptsNameAndEnum(): void
    {
        self::assertEquals(CrcFactory::createParameters(CrcType::CRC32), CrcFactory::createParameters('CRC-32'));
    }
}
