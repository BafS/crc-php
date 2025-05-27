<?php

declare(strict_types=1);

namespace BafS\Crc;

/**
 * Constant names/values are based on https://crccalc.com/.
 * @psalm-immutable
 * @psalm-pure
 * @final
 */
class CrcFactory
{
    /** @deprecated Use CrcType */
    public const CRC5_EPC = CrcType::CRC5_EPC;
    /** @deprecated Use CrcType */
    public const CRC5_ITU = CrcType::CRC5_ITU;

    /** @deprecated Use CrcType */
    public const CRC8 = CrcType::CRC8;
    /** @deprecated Use CrcType */
    public const CRC8_CDMA2000 = CrcType::CRC8_CDMA2000;
    /** @deprecated Use CrcType */
    public const CRC8_DARC = CrcType::CRC8_DARC;
    /** @deprecated Use CrcType */
    public const CRC8_DVB_S2 = CrcType::CRC8_DVB_S2;
    /** @deprecated Use CrcType */
    public const CRC8_EBU = CrcType::CRC8_EBU;
    /** @deprecated Use CrcType */
    public const CRC8_I_CODE = CrcType::CRC8_I_CODE;
    /** @deprecated Use CrcType */
    public const CRC8_ITU = CrcType::CRC8_ITU;
    /** @deprecated Use CrcType */
    public const CRC8_MAXIM = CrcType::CRC8_MAXIM;
    /** @deprecated Use CrcType */
    public const CRC8_ROHC = CrcType::CRC8_ROHC;
    /** @deprecated Use CrcType */
    public const CRC8_WCDMA = CrcType::CRC8_WCDMA;

    /** @deprecated Use CrcType */
    public const CRC16_CCITT_FALSE = CrcType::CRC16_CCITT_FALSE;
    /** @deprecated Use CrcType */
    public const CRC16_ARC = CrcType::CRC16_ARC;
    /** @deprecated Use CrcType */
    public const CRC16_AUG_CCITT = CrcType::CRC16_AUG_CCITT;
    /** @deprecated Use CrcType */
    public const CRC16_BUYPASS = CrcType::CRC16_BUYPASS;
    /** @deprecated Use CrcType */
    public const CRC16_CDMA2000 = CrcType::CRC16_CDMA2000;
    /** @deprecated Use CrcType */
    public const CRC16_DDS_110 = CrcType::CRC16_DDS_110;
    /** @deprecated Use CrcType */
    public const CRC16_DECT_R = CrcType::CRC16_DECT_R;
    /** @deprecated Use CrcType */
    public const CRC16_DECT_X = CrcType::CRC16_DECT_X;
    /** @deprecated Use CrcType */
    public const CRC16_DNP = CrcType::CRC16_DNP;
    /** @deprecated Use CrcType */
    public const CRC16_EN_13757 = CrcType::CRC16_EN_13757;
    /** @deprecated Use CrcType */
    public const CRC16_GENIBUS = CrcType::CRC16_GENIBUS;
    /** @deprecated Use CrcType */
    public const CRC16_MAXIM = CrcType::CRC16_MAXIM;
    /** @deprecated Use CrcType */
    public const CRC16_MCRF4XX = CrcType::CRC16_MCRF4XX;
    /** @deprecated Use CrcType */
    public const CRC16_RIELLO = CrcType::CRC16_RIELLO;
    /** @deprecated Use CrcType */
    public const CRC16_T10_DIF = CrcType::CRC16_T10_DIF;
    /** @deprecated Use CrcType */
    public const CRC16_TELEDISK = CrcType::CRC16_TELEDISK;
    /** @deprecated Use CrcType */
    public const CRC16_TMS37157 = CrcType::CRC16_TMS37157;
    /** @deprecated Use CrcType */
    public const CRC16_USB = CrcType::CRC16_USB;
    /** @deprecated Use CrcType */
    public const CRCA = CrcType::CRCA;
    /** @deprecated Use CrcType */
    public const CRC16_KERMIT = CrcType::CRC16_KERMIT;
    /** @deprecated Use CrcType */
    public const CRC16_MODBUS = CrcType::CRC16_MODBUS;
    /** @deprecated Use CrcType */
    public const CRC16_X_25 = CrcType::CRC16_X_25;
    /** @deprecated Use CrcType */
    public const CRC16_XMODEM = CrcType::CRC16_XMODEM;

    /** @deprecated Use CrcType */
    public const CRC24_BLE = CrcType::CRC24_BLE;
    /** @deprecated Use CrcType */
    public const CRC24_INTERLAKEN = CrcType::CRC24_INTERLAKEN;
    /** @deprecated Use CrcType */
    public const CRC24_OPENPGP = CrcType::CRC24_OPENPGP;

    /** @deprecated Use CrcType */
    public const CRC32 = CrcType::CRC32;
    /** @deprecated Use CrcType */
    public const CRC32_BZIP2 = CrcType::CRC32_BZIP2;
    /** @deprecated Use CrcType */
    public const CRC32C = CrcType::CRC32C;
    /** @deprecated Use CrcType */
    public const CRC32D = CrcType::CRC32D;
    /** @deprecated Use CrcType */
    public const CRC32_JAMCRC = CrcType::CRC32_JAMCRC;
    /** @deprecated Use CrcType */
    public const CRC32_MPEG2 = CrcType::CRC32_MPEG2;
    /** @deprecated Use CrcType */
    public const CRC32_POSIX = CrcType::CRC32_POSIX;
    /** @deprecated Use CrcType */
    public const CRC32Q = CrcType::CRC32Q;
    /** @deprecated Use CrcType */
    public const CRC32_XFER = CrcType::CRC32_XFER;

    /**
     * @psalm-param CrcType|Parameters $nameOrParams
     * @psalm-suppress ArgumentTypeCoercion
     */
    public static function create(CrcType|Parameters $nameOrParams, ?array $table = null): Encoder
    {
        if ($nameOrParams instanceof CrcType) {
            $nameOrParams = self::createParameters($nameOrParams);
        }

        $encoder = new Encoder($nameOrParams);

        if ($table !== null) {
            $encoder->setTable($table);
        }

        return $encoder;
    }

    /**
     * @psalm-param CrcType|non-empty-string $name
     * @throws \InvalidArgumentException
     */
    public static function createParameters(CrcType|string $name): Parameters
    {
        if ($name instanceof CrcType) {
            $name = $name->value;
        }

        return match ($name) {
            CrcType::CRC5_EPC->value => new Parameters(5, 0x09, 0x09, false, false, 0x00),
            CrcType::CRC5_ITU->value => new Parameters(5, 0x15, 0x00, true, true, 0x00),

            CrcType::CRC8->value => new Parameters(8, 0x07, 0x00, false, false, 0x00),
            CrcType::CRC8_CDMA2000->value => new Parameters(8, 0x9B, 0xFF, false, false, 0x00),
            CrcType::CRC8_DARC->value => new Parameters(8, 0x39, 0x00, true, true, 0x00),
            CrcType::CRC8_DVB_S2->value => new Parameters(8, 0xD5, 0x00, false, false, 0x00),
            CrcType::CRC8_EBU->value => new Parameters(8, 0x1D, 0xFF, true, true, 0x00),
            CrcType::CRC8_I_CODE->value => new Parameters(8, 0x1D, 0xFD, false, false, 0x00),
            CrcType::CRC8_ITU->value => new Parameters(8, 0x07, 0x00, false, false, 0x55),
            CrcType::CRC8_MAXIM->value => new Parameters(8, 0x31, 0x00, true, true, 0x00),
            CrcType::CRC8_ROHC->value => new Parameters(8, 0x07, 0xFF, true, true, 0x00),
            CrcType::CRC8_WCDMA->value => new Parameters(8, 0x9B, 0x00, true, true, 0x00),

            CrcType::CRC16_CCITT_FALSE->value => new Parameters(16, 0x1021, 0xFFFF, false, false, 0x0000),
            CrcType::CRC16_ARC->value => new Parameters(16, 0x8005, 0x0000, true, true, 0x0000),
            CrcType::CRC16_AUG_CCITT->value => new Parameters(16, 0x1021, 0x1d0f, false, false, 0x0000),
            CrcType::CRC16_BUYPASS->value => new Parameters(16, 0x8005, 0x0000, false, false, 0x0000),
            CrcType::CRC16_CDMA2000->value => new Parameters(16, 0xC867, 0xFFFF, false, false, 0x0000),
            CrcType::CRC16_DDS_110->value => new Parameters(16, 0x8005, 0x800D, false, false, 0x0000),
            CrcType::CRC16_DECT_R->value => new Parameters(16, 0x0589, 0x0000, false, false, 0x0001),
            CrcType::CRC16_DECT_X->value => new Parameters(16, 0x0589, 0x0000, false, false, 0x0000),
            CrcType::CRC16_DNP->value => new Parameters(16, 0x3D65, 0x0000, true, true, 0xFFFF),
            CrcType::CRC16_EN_13757->value => new Parameters(16, 0x3D65, 0x0000, false, false, 0xFFFF),
            CrcType::CRC16_GENIBUS->value => new Parameters(16, 0x1021, 0xFFFF, false, false, 0xFFFF),
            CrcType::CRC16_MAXIM->value => new Parameters(16, 0x8005, 0x0000, true, true, 0xFFFF),
            CrcType::CRC16_MCRF4XX->value => new Parameters(16, 0x1021, 0xFFFF, true, true, 0x0000),
            CrcType::CRC16_RIELLO->value => new Parameters(16, 0x1021, 0xB2AA, true, true, 0x0000),
            CrcType::CRC16_T10_DIF->value => new Parameters(16, 0x8BB7, 0x0000, false, false, 0x0000),
            CrcType::CRC16_TELEDISK->value => new Parameters(16, 0xA097, 0x0000, false, false, 0x0000),
            CrcType::CRC16_TMS37157->value => new Parameters(16, 0x1021, 0x89EC, true, true, 0x0000),
            CrcType::CRC16_USB->value => new Parameters(16, 0x8005, 0xFFFF, true, true, 0xFFFF),
            CrcType::CRCA->value => new Parameters(16, 0x1021, 0xC6C6, true, true, 0x0000),
            CrcType::CRC16_KERMIT->value => new Parameters(16, 0x1021, 0x0000, true, true, 0x0000),
            CrcType::CRC16_MODBUS->value => new Parameters(16, 0x8005, 0xFFFF, true, true, 0x0000),
            CrcType::CRC16_X_25->value => new Parameters(16, 0x1021, 0xFFFF, true, true, 0xFFFF),
            CrcType::CRC16_XMODEM->value => new Parameters(16, 0x1021, 0x0000, false, false, 0x0000),

            CrcType::CRC24_BLE->value => new Parameters(24, 0x00065b, 0x555555, true, true, 0x000000),
            CrcType::CRC24_INTERLAKEN->value => new Parameters(24, 0x328b63, 0xffffff, false, false, 0xffffff),
            CrcType::CRC24_OPENPGP->value => new Parameters(24, 0x864cfb, 0xb704ce, false, false, 0x000000),

            CrcType::CRC32->value => new Parameters(32, 0x04C11DB7, 0xFFFFFFFF, true, true, 0xFFFFFFFF),
            CrcType::CRC32_BZIP2->value => new Parameters(32, 0x04C11DB7, 0xFFFFFFFF, false, false, 0xFFFFFFFF),
            CrcType::CRC32C->value => new Parameters(32, 0x1EDC6F41, 0xFFFFFFFF, true, true, 0xFFFFFFFF),
            CrcType::CRC32D->value => new Parameters(32, 0xA833982B, 0xFFFFFFFF, true, true, 0xFFFFFFFF),
            CrcType::CRC32_JAMCRC->value => new Parameters(32, 0x04C11DB7, 0xFFFFFFFF, true, true, 0x00000000),
            CrcType::CRC32_MPEG2->value => new Parameters(32, 0x04C11DB7, 0xFFFFFFFF, false, false, 0x00000000),
            CrcType::CRC32_POSIX->value => new Parameters(32, 0x04C11DB7, 0x00000000, false, false, 0xFFFFFFFF),
            CrcType::CRC32Q->value => new Parameters(32, 0x814141AB, 0x00000000, false, false, 0x00000000),
            CrcType::CRC32_XFER->value => new Parameters(32, 0x000000AF, 0x00000000, false, false, 0x00000000),

            default => throw new \InvalidArgumentException('CRC name not found.'),
        };
    }
}
