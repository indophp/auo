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

    // Generate fully linear Base62 ID 17 karakter
    public function generate(): string
    {
        // Bagian waktu
        $year  = (int) date('y');   // 2 digit tahun
        $week  = (int) date('W');   // minggu ke
        $day   = (int) date('d');   // tanggal
        $hour  = (int) date('H');   // jam

        $timeValue = $year * 1000000 + $week * 10000 + $day * 100 + $hour; // 8 digit time number

        // Counter
        self::$counter = (self::$counter + 1) % 3844; // 62^2

        // Random 5 digit Base62 number
        $randomValue = random_int(0, pow($this->base, 5) - 1);

        // Combine all into one integer
        // number_total = time*62^9 + machine*62^7 + counter*62^5 + random
        $number = bcadd(
            bcmul($timeValue, bcpow(62, 9)),
            bcmul($this->machineId, bcpow(62, 7))
        );
        $number = bcadd($number, bcmul(self::$counter, bcpow(62, 5)));
        $number = bcadd($number, $randomValue);

        return $this->encodeBase62($number);
    }

    // Decode linear Base62 ID menjadi bagian-bagian
    public function decode(string $id): array
    {
        $number = $this->decodeBase62($id);

        $randomValue = bcmod($number, bcpow(62, 5));
        $number = bcdiv($number, bcpow(62, 5));

        $counter = bcmod($number, 3844); // 62^2
        $number = bcdiv($number, 3844);

        $machineId = bcmod($number, 3844);
        $number = bcdiv($number, 3844);

        $timeValue = $number; // 8 digit time number
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

        // pad ke 17 karakter
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
