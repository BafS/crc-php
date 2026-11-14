<?php

declare(strict_types=1);

namespace BafS\Crc;

/**
 * @psalm-immutable
 * @psalm-readonly
 * @final
 */
class Parameters
{
    /** Largest width that fits in a (signed 64-bit) PHP integer. */
    public const MAX_WIDTH = PHP_INT_SIZE * 8 - 1;

    private const KEY_ALIASES = [
        'width' => 'width',
        'len' => 'width',
        'length' => 'width',
        'size' => 'width',
        'poly' => 'poly',
        'polynomial' => 'poly',
        'init' => 'init',
        'initial' => 'init',
        'initialize' => 'init',
        'refin' => 'refin',
        'refinput' => 'refin',
        'revin' => 'refin',
        'refout' => 'refout',
        'refoutput' => 'refout',
        'revout' => 'refout',
        'xorout' => 'xorout',
        'xoroutput' => 'xorout',
    ];

    /**
     * @throws \InvalidArgumentException
     */
    public function __construct(
        public readonly int $width,
        public readonly int $poly,
        public readonly int $init,
        public readonly bool $reflectIn = false,
        public readonly bool $reflectOut = false,
        public readonly int $xorOutput = 0x0,
    ) {
        if ($width < 1 || $width > self::MAX_WIDTH) {
            throw new \InvalidArgumentException(
                sprintf('Width must be between 1 and %d, got %d.', self::MAX_WIDTH, $width),
            );
        }

        $mask = PHP_INT_MAX >> (self::MAX_WIDTH - $width);
        foreach (['poly' => $poly, 'init' => $init, 'xorOutput' => $xorOutput] as $name => $value) {
            if ($value < 0 || $value > $mask) {
                throw new \InvalidArgumentException(
                    sprintf('%s (0x%x) does not fit in %d bits.', $name, $value, $width),
                );
            }
        }
    }

    /**
     * Parse a parameter string such as the ones from the CRC RevEng catalogue,
     * e.g. "width=16 poly=0x1021 init=0xffff refin=false refout=false xorout=0x0000".
     *
     * @psalm-pure
     * @throws \InvalidArgumentException
     */
    public static function createFromString(string $info): self
    {
        $info = str_replace([':', '=', '&'], ' ', strtolower($info));
        $tokens = preg_split('/\s+/', $info, -1, PREG_SPLIT_NO_EMPTY);
        if ($tokens === false) {
            throw new \InvalidArgumentException('Unable to parse the parameter string.');
        }

        /** @var array<string, string> $raw */
        $raw = [];
        $key = null;
        foreach ($tokens as $token) {
            if ($key !== null) {
                $raw[$key] = $token;
                $key = null;
                continue;
            }

            $key = self::KEY_ALIASES[$token] ?? null;
        }

        if (!isset($raw['width'], $raw['poly'], $raw['init'])) {
            throw new \InvalidArgumentException('Missing parameters (width, poly and init are required).');
        }

        return new self(
            self::parseInt('width', $raw['width']),
            self::parseInt('poly', $raw['poly']),
            self::parseInt('init', $raw['init']),
            isset($raw['refin']) ? self::parseBool('refin', $raw['refin']) : false,
            isset($raw['refout']) ? self::parseBool('refout', $raw['refout']) : false,
            isset($raw['xorout']) ? self::parseInt('xorout', $raw['xorout']) : 0,
        );
    }

    /** @psalm-pure */
    private static function parseInt(string $key, string $value): int
    {
        if (preg_match('/^0x([0-9a-f]+)$/', $value, $m) === 1) {
            $int = hexdec($m[1]);
        } elseif (ctype_digit($value)) {
            $int = (int) $value;
            if ((string) $int !== ltrim($value, '0') && $int !== 0) {
                $int = (float) $value; // overflow
            }
        } else {
            throw new \InvalidArgumentException(
                sprintf('Invalid value "%s" for "%s": expected a decimal or 0x-prefixed hexadecimal integer.', $value, $key),
            );
        }

        if (!is_int($int)) {
            throw new \InvalidArgumentException(sprintf('Value "%s" for "%s" is too large.', $value, $key));
        }

        return $int;
    }

    /** @psalm-pure */
    private static function parseBool(string $key, string $value): bool
    {
        return match ($value) {
            'true', '1' => true,
            'false', '0' => false,
            default => throw new \InvalidArgumentException(
                sprintf('Invalid value "%s" for "%s": expected true, false, 1 or 0.', $value, $key),
            ),
        };
    }
}
