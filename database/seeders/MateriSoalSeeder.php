<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materi;
use App\Models\Soal;

class MateriSoalSeeder extends Seeder
{
    public function run()
    {
        $materi1 = Materi::create(['nama' => 'Bab 1: Bilangan']);
        $materi2 = Materi::create(['nama' => 'Bab 2: Pecahan']);
        $materi3 = Materi::create(['nama' => 'Bab 3: Geometri']);

        Soal::create(['materi_id' => $materi1->id, 'pertanyaan' => 'Hasil dari 25 + 17 = ?', 'jawaban' => '42']);
        Soal::create(['materi_id' => $materi1->id, 'pertanyaan' => 'Berapa hasil 9 x 8 ?', 'jawaban' => '72']);
        Soal::create(['materi_id' => $materi1->id, 'pertanyaan' => 'Hitunglah 100 - 37 = ?', 'jawaban' => '63']);

        Soal::create(['materi_id' => $materi2->id, 'pertanyaan' => '1/2 + 1/4 = ?', 'jawaban' => '3/4']);
        Soal::create(['materi_id' => $materi2->id, 'pertanyaan' => 'Ubahlah 0,75 menjadi pecahan biasa', 'jawaban' => '3/4']);

        Soal::create(['materi_id' => $materi3->id, 'pertanyaan' => 'Berapa jumlah sudut dalam segitiga?', 'jawaban' => '180']);
        Soal::create(['materi_id' => $materi3->id, 'pertanyaan' => 'Sebutkan rumus luas persegi panjang', 'jawaban' => 'p x l']);
    }
}