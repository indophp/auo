<?php

namespace Indophp\Auo;

class Uuid
{
    private $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private $base = 62;

    private static $counter = 0; 
    private $machineId;

    public function __construct(int $machineId = 0)
    {
        if ($machineId < 0 || $machineId >= 3844) {
            throw new \Exception("machineId harus antara 0-3843");
        }
        $this->machineId = $machineId;
    }
    public function generate(): string
    {
        $year  = (int) date('y');
        $week  = (int) date('W');
        $day   = (int) date('d');
        $hour  = (int) date('H');
        $timeValue = $year * 1000000 + $week * 10000 + $day * 100 + $hour;
        self::$counter = (self::$counter + 1) % 3844;

        $randomValue = random_int(0, pow($this->base, 5) - 1);

        $number = bcadd(
            bcmul($timeValue, bcpow(62, 9)),
            bcmul($this->machineId, bcpow(62, 7))
        );
        $number = bcadd($number, bcmul(self::$counter, bcpow(62, 5)));
        $number = bcadd($number, $randomValue);

        return $this->encodeBase62($number);
    }

    public function decode(string $id): array
    {
        $number = $this->decodeBase62($id);

        $randomValue = bcmod($number, bcpow(62, 5));
        $number = bcdiv($number, bcpow(62, 5));

        $counter = bcmod($number, 3844);
        $number = bcdiv($number, 3844);

        $machineId = bcmod($number, 3844);
        $number = bcdiv($number, 3844);

        $timeValue = $number;
        $hour = $timeValue % 100;
        $day  = bcdiv($timeValue % 10000, 100);
        $week = bcdiv($timeValue % 1000000, 10000);
        $year = bcdiv($timeValue, 1000000);

        return [
            'year'      => (int)$year,
            'week'      => (int)$week,
            'day'       => (int)$day,
            'hour'      => (int)$hour,
            'machineId' => (int)$machineId,
            'counter'   => (int)$counter,
            'random'    => (int)$randomValue,
        ];
    }

    private function encodeBase62($number): string
    {
        $str = '';
        do {
            $mod = bcmod($number, 62);
            $str = $this->characters[$mod] . $str;
            $number = bcdiv($number, 62, 0);
        } while ($number > 0);

        return str_pad($str, 17, '0', STR_PAD_LEFT);
    }

    private function decodeBase62(string $str)
    {
        $number = '0';
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++) {
            $number = bcadd(bcmul($number, 62), strpos($this->characters, $str[$i]));
        }
        return $number;
    }
}
