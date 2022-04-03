<?php

declare(strict_types=1);

namespace BafS\Crc;

/**
 * @psalm-immutable
 * @psalm-readonly
 */
class Parameters
{
    public function __construct(
        public int $width,
        public int $poly,
        public int $init,
        public bool $reflectIn = false,
        public bool $reflectOut = false,
        public int $xorOutput = 0x0,
    ) {
    }

    /**
     * @psalm-mutation-free
     */
    public static function createFromString(string $info): self
    {
        // Clean string
        $info = strtolower($info);
        $info = str_replace(["\n", "\t"], ' ', $info);
        $info = str_replace([':', '=', '&'], ' ', $info);
        $info = str_replace(['len', 'length', 'size'], 'width', $info);
        $info = str_replace('polynomial', 'poly', $info);
        $info = str_replace(['initialize', 'initial'], 'init', $info);
        $info = str_replace(['refinput', 'revin'], 'refin', $info);
        $info = str_replace(['refoutput', 'revout'], 'refout', $info);
        $info = str_replace('xoroutout', 'xorout', $info);
        $tokens = explode(' ', $info);

        $params = ['width' => null, 'poly' => null, 'init' => null, 'refin' => null, 'refout' => null, 'xorout' => null];

        $key = null;
        foreach ($tokens as $token) {
            if ($key !== null) {
                if (str_starts_with($token, '0x')) {
                    $token = hexdec(substr($token, 2));
                }

                if ($token === 'true') {
                    $token = true;
                }

                if ($token === 'false') {
                    $token = false;
                }

                $params[$key] = $token;
                $key = null;
                continue;
            }

            if (array_key_exists($token, $params)) {
                $key = $token;
            } else {
                $key = null;
            }
        }

        if (!isset($params['width'], $params['poly'], $params['init'])) {
            throw new \LogicException('Missing parameters (width, poly and init are required).');
        }

        if (!is_numeric($params['poly']) || !is_numeric($params['width']) || !is_numeric($params['init'])) {
            throw new \LogicException('Poly, width and init must be numerical.');
        }

        return new self(
            (int) $params['width'],
            (int) $params['poly'],
            (int) $params['init'],
            (bool) ($params['refin'] ?? false),
            (bool) ($params['refout'] ?? false),
            (int) ($params['xorout'] ?? 0),
        );
    }
}
