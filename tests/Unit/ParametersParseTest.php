<?php

namespace BafS\Tests\Unit;

use BafS\Crc\CrcFactory;
use BafS\Crc\Parameters;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class ParametersParseTest extends TestCase
{
    /**
     * @dataProvider parametersDataProvider
     */
    public function testParseString(string $toParse, Parameters $params): void
    {
        Assert::assertEquals(
            (array) $params,
            (array) Parameters::createFromString($toParse),
        );
    }

    public function parametersDataProvider(): iterable
    {
        yield [
            'width=24 poly=0x00065b init=0x555555 refin=true refout=true xorout=0x000000 check=0xc25a56 residue=0x000000 name="CRC-24/BLE"',
            CrcFactory::createParameters(CrcFactory::CRC24_BLE),
        ];

        yield [
            'len:24 init=0x555555  poly=0x00065b refin=true refout=true xorout=0x000000 check=0xc25a56 residue=0x000000 foo=bar name="CRC-24/BLE"',
            CrcFactory::createParameters(CrcFactory::CRC24_BLE),
        ];

        yield [
            'size=24 init=0x555555 poly=0x00065b refin=true refout=true',
            CrcFactory::createParameters(CrcFactory::CRC24_BLE),
        ];

        yield [
            'size=24 init=0x555555 poly=0x00065b refin=1 refout=1',
            CrcFactory::createParameters(CrcFactory::CRC24_BLE),
        ];
    }

    /**
     * @dataProvider wrongParametersDataProvider
     */
    public function testFailParseString(string $toParse): void
    {
        $this->expectException(\Exception::class);

        Parameters::createFromString($toParse);
    }

    public function wrongParametersDataProvider(): iterable
    {
        yield ['width=24 poly=00065b init=0x555555 refin=true refout=true xorout=0x000000 check=0xc25a56 residue=0x000000 name="CRC-24/BLE"'];
        yield ['init=0x555555  poly=0x00065b refin=true refout=true xorout=0x000000 check=0xc25a56 residue=0x000000 foo=bar name="CRC-24/BLE"'];
        yield ['size=true init=0x555555 poly=0x00065b'];
        yield ['size=24 init=0x555555'];
    }
}
