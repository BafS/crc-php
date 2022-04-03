<?php

declare(strict_types=1);

namespace BafS\Crc;

interface EncoderInterface
{
    public function compute(string $buffer): int;
}
