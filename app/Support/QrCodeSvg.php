<?php

namespace App\Support;

/**
 * Dependency-free ISO/IEC 18004 QR Code SVG Generator (Byte mode, ECC Level L, Versions 1-3).
 */
class QrCodeSvg
{
    private const VERSIONS = [
        1 => ['size' => 21, 'dataCodewords' => 19, 'ecCodewords' => 7,  'align' => []],
        2 => ['size' => 25, 'dataCodewords' => 34, 'ecCodewords' => 10, 'align' => [6, 18]],
        3 => ['size' => 29, 'dataCodewords' => 55, 'ecCodewords' => 15, 'align' => [6, 22]],
    ];

    public static function generate(string $text, int $pixelSize = 120): string
    {
        $bytes = array_values(unpack('C*', $text));
        $len = count($bytes);

        $version = 1;
        if ($len > 17 && $len <= 32) {
            $version = 2;
        } elseif ($len > 32 && $len <= 53) {
            $version = 3;
        } elseif ($len > 53) {
            $bytes = array_slice($bytes, 0, 53);
            $len = 53;
            $version = 3;
        }

        $spec = self::VERSIONS[$version];
        $size = $spec['size'];

        $codewords = self::encodeData($bytes, $spec['dataCodewords']);
        $ec = self::reedSolomon($codewords, $spec['ecCodewords']);
        $allBytes = array_merge($codewords, $ec);

        $bits = [];
        foreach ($allBytes as $b) {
            for ($i = 7; $i >= 0; $i--) {
                $bits[] = ($b >> $i) & 1;
            }
        }

        $bestMask = 0;
        $bestScore = PHP_INT_MAX;
        $bestMatrix = [];

        for ($mask = 0; $mask < 8; $mask++) {
            [$matrix, $isFunction] = self::buildBaseMatrix($size, $spec['align']);
            self::placeDataBits($matrix, $isFunction, $size, $bits);
            self::applyMask($matrix, $isFunction, $size, $mask);
            self::drawFormatBits($matrix, $size, $mask);

            $score = self::penaltyScore($matrix, $size);
            if ($score < $bestScore) {
                $bestScore = $score;
                $bestMask = $mask;
                $bestMatrix = $matrix;
            }
        }

        return self::renderSvg($bestMatrix, $size, $pixelSize, $text);
    }

    private static function encodeData(array $bytes, int $capacity): array
    {
        $bits = [];
        // Byte mode: 0100
        array_push($bits, 0, 1, 0, 0);

        // Character count (8 bits for versions 1-9)
        $count = count($bytes);
        for ($i = 7; $i >= 0; $i--) {
            $bits[] = ($count >> $i) & 1;
        }

        foreach ($bytes as $b) {
            for ($i = 7; $i >= 0; $i--) {
                $bits[] = ($b >> $i) & 1;
            }
        }

        $maxBits = $capacity * 8;
        $terminator = min(4, $maxBits - count($bits));
        for ($i = 0; $i < $terminator; $i++) {
            $bits[] = 0;
        }
        while (count($bits) % 8 !== 0) {
            $bits[] = 0;
        }

        $codewords = [];
        for ($i = 0; $i < count($bits); $i += 8) {
            $val = 0;
            for ($j = 0; $j < 8; $j++) {
                $val = ($val << 1) | $bits[$i + $j];
            }
            $codewords[] = $val;
        }

        $padBytes = [0xEC, 0x11];
        $padIdx = 0;
        while (count($codewords) < $capacity) {
            $codewords[] = $padBytes[$padIdx % 2];
            $padIdx++;
        }

        return $codewords;
    }

    private static function reedSolomon(array $data, int $ecCount): array
    {
        $gen = [1];
        for ($i = 0; $i < $ecCount; $i++) {
            $gen = self::polyMultiply($gen, [1, self::gfPow(2, $i)]);
        }

        $res = array_fill(0, $ecCount, 0);
        foreach ($data as $b) {
            $factor = $b ^ array_shift($res);
            $res[] = 0;
            for ($j = 0; $j < $ecCount; $j++) {
                $res[$j] ^= self::gfMultiply($gen[$j + 1], $factor);
            }
        }

        return $res;
    }

    private static function polyMultiply(array $p, array $q): array
    {
        $result = array_fill(0, count($p) + count($q) - 1, 0);
        for ($i = 0; $i < count($p); $i++) {
            for ($j = 0; $j < count($q); $j++) {
                $result[$i + $j] ^= self::gfMultiply($p[$i], $q[$j]);
            }
        }
        return $result;
    }

    private static function gfMultiply(int $x, int $y): int
    {
        $z = 0;
        for ($i = 7; $i >= 0; $i--) {
            $z = ($z << 1) ^ (($z >> 7) * 0x11D);
            $z ^= (($y >> $i) & 1) * $x;
        }
        return $z;
    }

    private static function gfPow(int $base, int $exp): int
    {
        $res = 1;
        for ($i = 0; $i < $exp; $i++) {
            $res = self::gfMultiply($res, $base);
        }
        return $res;
    }

    private static function buildBaseMatrix(int $size, array $alignCoords): array
    {
        $matrix = array_fill(0, $size, array_fill(0, $size, 0));
        $isFunction = array_fill(0, $size, array_fill(0, $size, false));

        // Finder patterns + separators
        self::drawFinder($matrix, $isFunction, $size, 0, 0);
        self::drawFinder($matrix, $isFunction, $size, 0, $size - 7);
        self::drawFinder($matrix, $isFunction, $size, $size - 7, 0);

        // Alignment patterns
        if (count($alignCoords) >= 2) {
            foreach ($alignCoords as $r) {
                foreach ($alignCoords as $c) {
                    if ($isFunction[$r][$c]) {
                        continue;
                    }
                    for ($dr = -2; $dr <= 2; $dr++) {
                        for ($dc = -2; $dc <= 2; $dc++) {
                            $dist = max(abs($dr), abs($dc));
                            $matrix[$r + $dr][$c + $dc] = ($dist === 2 || $dist === 0) ? 1 : 0;
                            $isFunction[$r + $dr][$c + $dc] = true;
                        }
                    }
                }
            }
        }

        // Timing patterns
        for ($i = 8; $i < $size - 8; $i++) {
            $bit = ($i % 2 === 0) ? 1 : 0;
            $matrix[6][$i] = $bit;
            $isFunction[6][$i] = true;
            $matrix[$i][6] = $bit;
            $isFunction[$i][6] = true;
        }

        // Dark module
        $matrix[$size - 8][8] = 1;
        $isFunction[$size - 8][8] = true;

        // Reserve format info areas
        for ($i = 0; $i <= 8; $i++) {
            if ($i !== 6) {
                $isFunction[8][$i] = true;
                $isFunction[$i][8] = true;
            }
        }
        for ($i = $size - 8; $i < $size; $i++) {
            $isFunction[8][$i] = true;
            if ($i !== $size - 8) {
                $isFunction[$i][8] = true;
            }
        }

        return [$matrix, $isFunction];
    }

    private static function drawFinder(array &$matrix, array &$isFunction, int $size, int $top, int $left): void
    {
        for ($dr = -1; $dr <= 7; $dr++) {
            for ($dc = -1; $dc <= 7; $dc++) {
                $r = $top + $dr;
                $c = $left + $dc;
                if ($r < 0 || $r >= $size || $c < 0 || $c >= $size) {
                    continue;
                }
                $isDark = ($dr >= 0 && $dr <= 6 && $dc >= 0 && $dc <= 6) &&
                    ($dr === 0 || $dr === 6 || $dc === 0 || $dc === 6 || ($dr >= 2 && $dr <= 4 && $dc >= 2 && $dc <= 4));
                $matrix[$r][$c] = $isDark ? 1 : 0;
                $isFunction[$r][$c] = true;
            }
        }
    }

    private static function placeDataBits(array &$matrix, array $isFunction, int $size, array $bits): void
    {
        $bitIdx = 0;
        $totalBits = count($bits);
        $upward = true;

        for ($right = $size - 1; $right >= 1; $right -= 2) {
            if ($right === 6) {
                $right = 5;
            }
            for ($vert = 0; $vert < $size; $vert++) {
                $r = $upward ? ($size - 1 - $vert) : $vert;
                for ($j = 0; $j < 2; $j++) {
                    $c = $right - $j;
                    if (!$isFunction[$r][$c]) {
                        $matrix[$r][$c] = ($bitIdx < $totalBits) ? $bits[$bitIdx++] : 0;
                    }
                }
            }
            $upward = !$upward;
        }
    }

    private static function applyMask(array &$matrix, array $isFunction, int $size, int $mask): void
    {
        for ($r = 0; $r < $size; $r++) {
            for ($c = 0; $c < $size; $c++) {
                if ($isFunction[$r][$c]) {
                    continue;
                }
                $invert = match ($mask) {
                    0 => ($r + $c) % 2 === 0,
                    1 => $r % 2 === 0,
                    2 => $c % 3 === 0,
                    3 => ($r + $c) % 3 === 0,
                    4 => (intdiv($r, 2) + intdiv($c, 3)) % 2 === 0,
                    5 => (($r * $c) % 2) + (($r * $c) % 3) === 0,
                    6 => ((($r * $c) % 2) + (($r * $c) % 3)) % 2 === 0,
                    7 => ((($r + $c) % 2) + (($r * $c) % 3)) % 2 === 0,
                    default => false,
                };
                if ($invert) {
                    $matrix[$r][$c] ^= 1;
                }
            }
        }
    }

    private static function drawFormatBits(array &$matrix, int $size, int $mask): void
    {
        // ECC Level L (01) + 3-bit mask
        $data = (0b01 << 3) | $mask;
        $rem = $data << 10;
        for ($i = 14; $i >= 10; $i--) {
            if (($rem >> $i) & 1) {
                $rem ^= (0x537 << ($i - 10));
            }
        }
        $bits = (($data << 10) | $rem) ^ 0x5412;

        // First copy
        for ($i = 0; $i <= 5; $i++) {
            $matrix[8][$i] = ($bits >> (14 - $i)) & 1;
        }
        $matrix[8][7] = ($bits >> 8) & 1;
        $matrix[8][8] = ($bits >> 7) & 1;
        $matrix[7][8] = ($bits >> 6) & 1;
        for ($i = 0; $i <= 5; $i++) {
            $matrix[5 - $i][8] = ($bits >> (5 - $i)) & 1;
        }

        // Second copy
        for ($i = 0; $i <= 6; $i++) {
            $matrix[$size - 1 - $i][8] = ($bits >> (14 - $i)) & 1;
        }
        for ($i = 0; $i <= 7; $i++) {
            $matrix[8][$size - 8 + $i] = ($bits >> (7 - $i)) & 1;
        }
    }

    private static function penaltyScore(array $matrix, int $size): int
    {
        $score = 0;
        for ($r = 0; $r < $size; $r++) {
            $run = 1;
            for ($c = 1; $c < $size; $c++) {
                if ($matrix[$r][$c] === $matrix[$r][$c - 1]) {
                    $run++;
                } else {
                    if ($run >= 5) {
                        $score += 3 + ($run - 5);
                    }
                    $run = 1;
                }
            }
            if ($run >= 5) {
                $score += 3 + ($run - 5);
            }
        }
        for ($c = 0; $c < $size; $c++) {
            $run = 1;
            for ($r = 1; $r < $size; $r++) {
                if ($matrix[$r][$c] === $matrix[$r - 1][$c]) {
                    $run++;
                } else {
                    if ($run >= 5) {
                        $score += 3 + ($run - 5);
                    }
                    $run = 1;
                }
            }
            if ($run >= 5) {
                $score += 3 + ($run - 5);
            }
        }
        for ($r = 0; $r < $size - 1; $r++) {
            for ($c = 0; $c < $size - 1; $c++) {
                $v = $matrix[$r][$c];
                if ($matrix[$r][$c + 1] === $v && $matrix[$r + 1][$c] === $v && $matrix[$r + 1][$c + 1] === $v) {
                    $score += 3;
                }
            }
        }
        return $score;
    }

    private static function renderSvg(array $matrix, int $size, int $pixelSize, string $alt): string
    {
        $quiet = 2;
        $viewSize = $size + $quiet * 2;
        $paths = '';
        for ($r = 0; $r < $size; $r++) {
            for ($c = 0; $c < $size; $c++) {
                if ($matrix[$r][$c] === 1) {
                    $x = $c + $quiet;
                    $y = $r + $quiet;
                    $paths .= "M{$x} {$y}h1v1h-1z";
                }
            }
        }

        $label = htmlspecialchars($alt, ENT_QUOTES, 'UTF-8');
        return "<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 {$viewSize} {$viewSize}\" width=\"{$pixelSize}\" height=\"{$pixelSize}\" role=\"img\" aria-label=\"QR Tiket {$label}\" shape-rendering=\"crispEdges\">"
            . "<rect width=\"100%\" height=\"100%\" fill=\"#ffffff\"/>"
            . "<path d=\"{$paths}\" fill=\"#0f172a\"/>"
            . "</svg>";
    }
}

