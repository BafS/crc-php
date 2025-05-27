<?php

declare(strict_types=1);

namespace BafS\Crc;

/** @final */
class Encoder implements EncoderInterface
{
    /** @var array<int, int>|null */
    private ?array $lookupTable = null;
    private readonly int $mask;
    private readonly int $highBit;

    public function __construct(protected readonly Parameters $parameters)
    {
        $highBit = 1 << ($this->parameters->width - 1);
        $this->mask = (($highBit - 1) << 1) | 1;
        $this->highBit = $highBit;
    }

    /** @param array<int, int> $lookupTable */
    public function setTable(array $lookupTable): void
    {
        $this->lookupTable = $lookupTable;
    }

    /**
     * @psalm-mutation-free
     */
    public function compute(string $buffer): int
    {
        if ($this->lookupTable === null) {
            return $this->computeWithoutTable($buffer);
        }

        return $this->computeWithTable($buffer, $this->lookupTable);
    }

    /**
     * Based on {@see https://github.com/darkstar/gus/blob/master/UnpackShell/Shared/CRC.cs}
     *
     * @psalm-mutation-free
     * @param array<int, int> $lookupTable
     */
    private function computeWithTable(string $buffer, array $lookupTable): int
    {
        $crc = $this->parameters->init;
        $bufferLength = strlen($buffer);

        if ($this->parameters->reflectIn) {
            $crc = $this->binaryReverse($crc, $this->parameters->width);

            for ($i = 0; $i < $bufferLength; ++$i) {
                $byte = ord($buffer[$i]);
                $crc = ($crc >> 8) ^ $lookupTable[($crc & 0xff) ^ $byte];
            }
        } else {
            for ($i = 0; $i < $bufferLength; ++$i) {
                $byte = ord($buffer[$i]);
                $crc = ($crc << 8) ^ $lookupTable[(($crc >> ($this->parameters->width - 8)) & 0xff) ^ $byte];
            }
        }

        if ($this->parameters->reflectIn xor $this->parameters->reflectOut) {
            $crc = $this->binaryReverse($crc, $this->parameters->width);
        }

        return ($crc ^ $this->parameters->xorOutput) & $this->mask;
    }

    /**
     * @psalm-mutation-free
     */
    private function computeWithoutTable(string $buffer): int
    {
        $bufferLength = strlen($buffer);

        $crc = $this->parameters->init;

        for ($i = 0; $i < $bufferLength; ++$i) {
            $byte = ord($buffer[$i]);
            if ($this->parameters->reflectIn) {
                $byte = $this->binaryReverse($byte, 8);
            }

            for ($j = 0x80; $j; $j >>= 1) {
                $bit = $crc & $this->highBit;
                $crc <<= 1;

                if (($byte & $j) !== 0) {
                    $bit ^= $this->highBit;
                }

                if ($bit !== 0) {
                    $crc ^= $this->parameters->poly;
                }
            }
        }

        if ($this->parameters->reflectOut) {
            $crc = $this->binaryReverse($crc, $this->parameters->width);
        }

        return ($crc ^ $this->parameters->xorOutput) & $this->mask;
    }

    /**
     * @psalm-mutation-free
     * @return int[]
     */
    public function generateTable(): array
    {
        if ($this->parameters->width < 8) {
            throw new \LogicException('Table cannot be generated for CRC with a width smaller than 8.');
        }

        $crcTable = [];
        for ($i = 0; $i < 256; ++$i) {
            $crc = $i;
            if ($this->parameters->reflectIn) {
                $crc = $this->binaryReverse($crc, 8);
            }

            $crc <<= $this->parameters->width - 8;

            for ($j = 0; $j < 8; ++$j) {
                $bit = $crc & $this->highBit;
                $crc <<= 1;
                if ($bit !== 0) {
                    $crc ^= $this->parameters->poly;
                }
            }

            if ($this->parameters->reflectIn) {
                $crc = $this->binaryReverse($crc, $this->parameters->width);
            }
            $crcTable[] = $crc & $this->mask;
        }

        return $crcTable;
    }

    /**
     * @psalm-mutation-free
     */
    protected function binaryReverse(int $bin, int $width): int
    {
        $clonedBin = $bin;
        $bin = 0;
        for ($i = 0; $i < $width; ++$i) {
            $bin <<= 1;
            $bin |= ($clonedBin & 0x1);
            $clonedBin >>= 1;
        }

        return $bin;
    }
}
