<?php

namespace Indophp\Auo;

class Uuid
{
    private $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private $base = 62;

    public function generate(int $length = 17): string
    {
        $yearPart  = str_pad($this->toBase62((int)date('Y')), 2, '0', STR_PAD_LEFT);
        $weekPart  = str_pad($this->toBase62((int)date('W')), 2, '0', STR_PAD_LEFT);
        $dayPart   = str_pad($this->toBase62((int)date('d')), 2, '0', STR_PAD_LEFT);
        $hourPart  = str_pad($this->toBase62((int)date('H')), 2, '0', STR_PAD_LEFT);

        $randomPartLength = $length - 8; // 8 karakter sudah terpakai
        $randomPart = $this->generateRandomPart($randomPartLength);

        return strtoupper($yearPart . $weekPart . $dayPart . $hourPart . $randomPart);
    }

    private function generateRandomPart(int $length): string
    {
        $randomPart = '';
        for ($i = 0; $i < $length; $i++) {
            $randomPart .= $this->characters[random_int(0, 61)];
        }
        return $randomPart;
    }

    private function toBase62(int $number): string
    {
        $result = '';
        do {
            $result = $this->characters[$number % $this->base] . $result;
            $number = intdiv($number, $this->base);
        } while ($number > 0);
        return $result;
    }

    public function decode(string $id): array
    {
        return [
            'year'  => $this->fromBase62(substr($id, 0, 2)),
            'week'  => $this->fromBase62(substr($id, 2, 2)),
            'day'   => $this->fromBase62(substr($id, 4, 2)),
            'hour'  => $this->fromBase62(substr($id, 6, 2)),
            'random'=> substr($id, 8)
        ];
    }

    private function fromBase62(string $str): int
    {
        $number = 0;
        for ($i = 0; $i < strlen($str); $i++) {
            $number = $number * $this->base + strpos($this->characters, $str[$i]);
        }
        return $number;
    }
}
