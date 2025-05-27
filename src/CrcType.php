<?php

namespace BafS\Crc;

enum CrcType: string
{
    case CRC5_EPC = 'CRC-5/EPC-C1G2';
    case CRC5_ITU = 'CRC-5/G-704';

    case CRC8 = 'CRC-8';
    case CRC8_CDMA2000 = 'CRC-8/CDMA2000';
    case CRC8_DARC = 'CRC-8/DARC';
    case CRC8_DVB_S2 = 'CRC-8/DVB-S2';
    case CRC8_EBU = 'CRC-8/EBU';
    case CRC8_I_CODE = 'CRC-8/I-CODE';
    case CRC8_ITU = 'CRC-8/I-432-1';
    case CRC8_MAXIM = 'CRC-8/MAXIM';
    case CRC8_ROHC = 'CRC-8/ROHC';
    case CRC8_WCDMA = 'CRC-8/WCDMA';

    case CRC16_CCITT_FALSE = 'CRC-16/CCITT-FALSE';
    case CRC16_ARC = 'CRC-16/ARC';
    case CRC16_AUG_CCITT = 'CRC-16/AUG-CCITT';
    case CRC16_BUYPASS = 'CRC-16/BUYPASS';
    case CRC16_CDMA2000 = 'CRC-16/CDMA2000';
    case CRC16_DDS_110 = 'CRC-16/DDS-110';
    case CRC16_DECT_R = 'CRC-16/DECT-R';
    case CRC16_DECT_X = 'CRC-16/DECT-X';
    case CRC16_DNP = 'CRC-16/DNP';
    case CRC16_EN_13757 = 'CRC-16/EN-13757';
    case CRC16_GENIBUS = 'CRC-16/GENIBUS';
    case CRC16_MAXIM = 'CRC-16/MAXIM';
    case CRC16_MCRF4XX = 'CRC-16/MCRF4XX';
    case CRC16_RIELLO = 'CRC-16/RIELLO';
    case CRC16_T10_DIF = 'CRC-16/T10-DIF';
    case CRC16_TELEDISK = 'CRC-16/TELEDISK';
    case CRC16_TMS37157 = 'CRC-16/TMS37157';
    case CRC16_USB = 'CRC-16/USB';
    case CRCA = 'CRC-A';
    case CRC16_KERMIT = 'CRC-16/KERMIT';
    case CRC16_MODBUS = 'CRC-16/MODBUS';
    case CRC16_X_25 = 'CRC-16/X-25';
    case CRC16_XMODEM = 'CRC-16/XMODEM';

    case CRC24_BLE = 'CRC-24/BLE';
    case CRC24_INTERLAKEN = 'CRC-24/INTERLAKEN';
    case CRC24_OPENPGP = 'CRC-24/OPENPGP';

    case CRC32 = 'CRC-32';
    case CRC32_BZIP2 = 'CRC-32/BZIP2';
    case CRC32C = 'CRC-32C';
    case CRC32D = 'CRC-32D';
    case CRC32_JAMCRC = 'CRC-32/JAMCRC';
    case CRC32_MPEG2 = 'CRC-32/MPEG-2';
    case CRC32_POSIX = 'CRC-32/POSIX';
    case CRC32Q = 'CRC-32Q';
    case CRC32_XFER = 'CRC-32/XFER';
}
