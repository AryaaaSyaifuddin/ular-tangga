<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SnakeLadder;

class SnakeLadderSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Ular (turun)
            ['start' => 16, 'end' => 6, 'type' => 'snake'],
            ['start' => 47, 'end' => 26, 'type' => 'snake'],
            ['start' => 49, 'end' => 11, 'type' => 'snake'],
            ['start' => 56, 'end' => 53, 'type' => 'snake'],
            ['start' => 62, 'end' => 19, 'type' => 'snake'],
            ['start' => 64, 'end' => 60, 'type' => 'snake'],
            ['start' => 87, 'end' => 24, 'type' => 'snake'],
            ['start' => 93, 'end' => 73, 'type' => 'snake'],
            ['start' => 95, 'end' => 75, 'type' => 'snake'],
            ['start' => 98, 'end' => 78, 'type' => 'snake'],
            // Tangga (naik)
            ['start' => 2, 'end' => 38, 'type' => 'ladder'],
            ['start' => 7, 'end' => 14, 'type' => 'ladder'],
            ['start' => 8, 'end' => 31, 'type' => 'ladder'],
            ['start' => 15, 'end' => 26, 'type' => 'ladder'],
            ['start' => 21, 'end' => 42, 'type' => 'ladder'],
            ['start' => 28, 'end' => 84, 'type' => 'ladder'],
            ['start' => 36, 'end' => 44, 'type' => 'ladder'],
            ['start' => 51, 'end' => 67, 'type' => 'ladder'],
            ['start' => 71, 'end' => 91, 'type' => 'ladder'],
            ['start' => 78, 'end' => 98, 'type' => 'ladder'],
            ['start' => 87, 'end' => 94, 'type' => 'ladder'],
        ];

        foreach ($data as $item) {
            SnakeLadder::create($item);
        }
    }
}