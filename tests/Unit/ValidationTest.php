<?php

declare(strict_types=1);

namespace BafS\Crc\Tests\Unit;

use BafS\Crc\CrcFactory;
use BafS\Crc\CrcType;
use BafS\Crc\Parameters;
use PHPUnit\Framework\TestCase;

final class ValidationTest extends TestCase
{
    public function testMaxWidthIsAccepted(): void
    {
        $params = new Parameters(PHP_INT_SIZE * 8 - 1, PHP_INT_MAX, PHP_INT_MAX, xorOutput: PHP_INT_MAX);

        self::assertSame(PHP_INT_SIZE * 8 - 1, $params->width);
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
