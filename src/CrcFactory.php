<?php

declare(strict_types=1);

namespace BafS\Crc;

/**
 * Constant names/values are based on https://crccalc.com/.
 * @psalm-immutable
 * @psalm-pure
 */
class CrcFactory
{
    public const CRC5_EPC = 'CRC-5/EPC-C1G2';
    public const CRC5_ITU = 'CRC-5/G-704';

    public const CRC8 = 'CRC-8';
    public const CRC8_CDMA2000 = 'CRC-8/CDMA2000';
    public const CRC8_DARC = 'CRC-8/DARC';
    public const CRC8_DVB_S2 = 'CRC-8/DVB-S2';
    public const CRC8_EBU = 'CRC-8/EBU';
    public const CRC8_I_CODE = 'CRC-8/I-CODE';
    public const CRC8_ITU = 'CRC-8/I-432-1';
    public const CRC8_MAXIM = 'CRC-8/MAXIM';
    public const CRC8_ROHC = 'CRC-8/ROHC';
    public const CRC8_WCDMA = 'CRC-8/WCDMA';

    public const CRC16_CCITT_FALSE = 'CRC-16/CCITT-FALSE';
    public const CRC16_ARC = 'CRC-16/ARC';
    public const CRC16_AUG_CCITT = 'CRC-16/AUG-CCITT';
    public const CRC16_BUYPASS = 'CRC-16/BUYPASS';
    public const CRC16_CDMA2000 = 'CRC-16/CDMA2000';
    public const CRC16_DDS_110 = 'CRC-16/DDS-110';
    public const CRC16_DECT_R = 'CRC-16/DECT-R';
    public const CRC16_DECT_X = 'CRC-16/DECT-X';
    public const CRC16_DNP = 'CRC-16/DNP';
    public const CRC16_EN_13757 = 'CRC-16/EN-13757';
    public const CRC16_GENIBUS = 'CRC-16/GENIBUS';
    public const CRC16_MAXIM = 'CRC-16/MAXIM';
    public const CRC16_MCRF4XX = 'CRC-16/MCRF4XX';
    public const CRC16_RIELLO = 'CRC-16/RIELLO';
    public const CRC16_T10_DIF = 'CRC-16/T10-DIF';
    public const CRC16_TELEDISK = 'CRC-16/TELEDISK';
    public const CRC16_TMS37157 = 'CRC-16/TMS37157';
    public const CRC16_USB = 'CRC-16/USB';
    public const CRCA = 'CRC-A';
    public const CRC16_KERMIT = 'CRC-16/KERMIT';
    public const CRC16_MODBUS = 'CRC-16/MODBUS';
    public const CRC16_X_25 = 'CRC-16/X-25';
    public const CRC16_XMODEM = 'CRC-16/XMODEM';

    public const CRC24_BLE = 'CRC-24/BLE';
    public const CRC24_INTERLAKEN = 'CRC-24/INTERLAKEN';
    public const CRC24_OPENPGP = 'CRC-24/OPENPGP';

    public const CRC32 = 'CRC-32';
    public const CRC32_BZIP2 = 'CRC-32/BZIP2';
    public const CRC32C = 'CRC-32C';
    public const CRC32D = 'CRC-32D';
    public const CRC32_JAMCRC = 'CRC-32/JAMCRC';
    public const CRC32_MPEG2 = 'CRC-32/MPEG-2';
    public const CRC32_POSIX = 'CRC-32/POSIX';
    public const CRC32Q = 'CRC-32Q';
    public const CRC32_XFER = 'CRC-32/XFER';

    /**
     * @psalm-param self::CRC*|Parameters $nameOrParams
     * @psalm-suppress ArgumentTypeCoercion
     */
    public static function create(string|Parameters $nameOrParams, ?array $table = null): Encoder
    {
        if (is_string($nameOrParams)) {
            $nameOrParams = self::createParameters($nameOrParams);
        }

        $encoder = new Encoder($nameOrParams);

        if ($table) {
            $encoder->setTable($table);
        }

        return $encoder;
    }

    /**
     * @psalm-param self::CRC* $name
     */
    public static function createParameters(string $name): Parameters
    {
        return match ($name) {
            self::CRC5_EPC => new Parameters(5, 0x09, 0x09, false, false, 0x00),
            self::CRC5_ITU => new Parameters(5, 0x15, 0x00, true, true, 0x00),

            self::CRC8 => new Parameters(8, 0x07, 0x00, false, false, 0x00),
            self::CRC8_CDMA2000 => new Parameters(8, 0x9B, 0xFF, false, false, 0x00),
            self::CRC8_DARC => new Parameters(8, 0x39, 0x00, true, true, 0x00),
            self::CRC8_DVB_S2 => new Parameters(8, 0xD5, 0x00, false, false, 0x00),
            self::CRC8_EBU => new Parameters(8, 0x1D, 0xFF, true, true, 0x00),
            self::CRC8_I_CODE => new Parameters(8, 0x1D, 0xFD, false, false, 0x00),
            self::CRC8_ITU => new Parameters(8, 0x07, 0x00, false, false, 0x55),
            self::CRC8_MAXIM => new Parameters(8, 0x31, 0x00, true, true, 0x00),
            self::CRC8_ROHC => new Parameters(8, 0x07, 0xFF, true, true, 0x00),
            self::CRC8_WCDMA => new Parameters(8, 0x9B, 0x00, true, true, 0x00),

            self::CRC16_CCITT_FALSE => new Parameters(16, 0x1021, 0xFFFF, false, false, 0x0000),
            self::CRC16_ARC => new Parameters(16, 0x8005, 0x0000, true, true, 0x0000),
            self::CRC16_AUG_CCITT => new Parameters(16, 0x1021, 0x1d0f, false, false, 0x0000),
            self::CRC16_BUYPASS => new Parameters(16, 0x8005, 0x0000, false, false, 0x0000),
            self::CRC16_CDMA2000 => new Parameters(16, 0xC867, 0xFFFF, false, false, 0x0000),
            self::CRC16_DDS_110 => new Parameters(16, 0x8005, 0x800D, false, false, 0x0000),
            self::CRC16_DECT_R => new Parameters(16, 0x0589, 0x0000, false, false, 0x0001),
            self::CRC16_DECT_X => new Parameters(16, 0x0589, 0x0000, false, false, 0x0000),
            self::CRC16_DNP => new Parameters(16, 0x3D65, 0x0000, true, true, 0xFFFF),
            self::CRC16_EN_13757 => new Parameters(16, 0x3D65, 0x0000, false, false, 0xFFFF),
            self::CRC16_GENIBUS => new Parameters(16, 0x1021, 0xFFFF, false, false, 0xFFFF),
            self::CRC16_MAXIM => new Parameters(16, 0x8005, 0x0000, true, true, 0xFFFF),
            self::CRC16_MCRF4XX => new Parameters(16, 0x1021, 0xFFFF, true, true, 0x0000),
            self::CRC16_RIELLO => new Parameters(16, 0x1021, 0xB2AA, true, true, 0x0000),
            self::CRC16_T10_DIF => new Parameters(16, 0x8BB7, 0x0000, false, false, 0x0000),
            self::CRC16_TELEDISK => new Parameters(16, 0xA097, 0x0000, false, false, 0x0000),
            self::CRC16_TMS37157 => new Parameters(16, 0x1021, 0x89EC, true, true, 0x0000),
            self::CRC16_USB => new Parameters(16, 0x8005, 0xFFFF, true, true, 0xFFFF),
            self::CRCA => new Parameters(16, 0x1021, 0xC6C6, true, true, 0x0000),
            self::CRC16_KERMIT => new Parameters(16, 0x1021, 0x0000, true, true, 0x0000),
            self::CRC16_MODBUS => new Parameters(16, 0x8005, 0xFFFF, true, true, 0x0000),
            self::CRC16_X_25 => new Parameters(16, 0x1021, 0xFFFF, true, true, 0xFFFF),
            self::CRC16_XMODEM => new Parameters(16, 0x1021, 0x0000, false, false, 0x0000),

            self::CRC24_BLE => new Parameters(24, 0x00065b, 0x555555, true, true, 0x000000),
            self::CRC24_INTERLAKEN => new Parameters(24, 0x328b63, 0xffffff, false, false, 0xffffff),
            self::CRC24_OPENPGP => new Parameters(24, 0x864cfb, 0xb704ce, false, false, 0x000000),

            self::CRC32 => new Parameters(32, 0x04C11DB7, 0xFFFFFFFF, true, true, 0xFFFFFFFF),
            self::CRC32_BZIP2 => new Parameters(32, 0x04C11DB7, 0xFFFFFFFF, false, false, 0xFFFFFFFF),
            self::CRC32C => new Parameters(32, 0x1EDC6F41, 0xFFFFFFFF, true, true, 0xFFFFFFFF),
            self::CRC32D => new Parameters(32, 0xA833982B, 0xFFFFFFFF, true, true, 0xFFFFFFFF),
            self::CRC32_JAMCRC => new Parameters(32, 0x04C11DB7, 0xFFFFFFFF, true, true, 0x00000000),
            self::CRC32_MPEG2 => new Parameters(32, 0x04C11DB7, 0xFFFFFFFF, false, false, 0x00000000),
            self::CRC32_POSIX => new Parameters(32, 0x04C11DB7, 0x00000000, false, false, 0xFFFFFFFF),
            self::CRC32Q => new Parameters(32, 0x814141AB, 0x00000000, false, false, 0x00000000),
            self::CRC32_XFER => new Parameters(32, 0x000000AF, 0x00000000, false, false, 0x00000000),

            default => throw new \InvalidArgumentException('CRC name not found.'),
        };
    }
}
