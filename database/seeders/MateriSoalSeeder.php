<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materi;
use App\Models\Soal;

class MateriSoalSeeder extends Seeder
{
    public function run()
    {
        // BAB 1
        $materi1 = Materi::create([
            'nama' => 'BAB 1: Sejarah Kelahiran Pancasila'
        ]);

        Soal::create([
            'materi_id' => $materi1->id,
            'pertanyaan' => 'Mengapa sidang BPUPK memiliki peran penting dalam sejarah Indonesia?',
            'jawaban' => 'Karena membahas dasar negara dan persiapan kemerdekaan Indonesia'
        ]);

        Soal::create([
            'materi_id' => $materi1->id,
            'pertanyaan' => 'Apa hubungan pidato Ir. Soekarno tanggal 1 Juni 1945 dengan lahirnya Pancasila?',
            'jawaban' => 'Dalam pidato tersebut Ir. Soekarno memperkenalkan nama dan konsep Pancasila'
        ]);

        Soal::create([
            'materi_id' => $materi1->id,
            'pertanyaan' => 'Mengapa nilai-nilai Pancasila dikatakan berasal dari kepribadian bangsa Indonesia?',
            'jawaban' => 'Karena nilai Pancasila sudah ada dalam budaya dan kehidupan masyarakat Indonesia sejak dahulu'
        ]);

        Soal::create([
            'materi_id' => $materi1->id,
            'pertanyaan' => 'Apa tujuan dibentuknya Panitia Sembilan?',
            'jawaban' => 'Untuk merumuskan dasar negara Indonesia'
        ]);

        Soal::create([
            'materi_id' => $materi1->id,
            'pertanyaan' => 'Apa hasil penting dari kerja Panitia Sembilan?',
            'jawaban' => 'Piagam Jakarta'
        ]);

        Soal::create([
            'materi_id' => $materi1->id,
            'pertanyaan' => 'Mengapa Pancasila tetap digunakan sebagai dasar negara sampai sekarang?',
            'jawaban' => 'Karena Pancasila sesuai dengan cita-cita dan nilai kehidupan bangsa Indonesia'
        ]);

        Soal::create([
            'materi_id' => $materi1->id,
            'pertanyaan' => 'Apa makna Pancasila sebagai pandangan hidup bangsa?',
            'jawaban' => 'Pancasila menjadi pedoman dalam kehidupan masyarakat Indonesia'
        ]);

        Soal::create([
            'materi_id' => $materi1->id,
            'pertanyaan' => 'Mengapa peserta didik perlu memahami sejarah kelahiran Pancasila?',
            'jawaban' => 'Agar memiliki rasa cinta tanah air dan menghargai perjuangan pendiri bangsa'
        ]);

        Soal::create([
            'materi_id' => $materi1->id,
            'pertanyaan' => 'Apa yang akan terjadi jika masyarakat tidak menerapkan nilai-nilai Pancasila?',
            'jawaban' => 'Kehidupan masyarakat menjadi tidak rukun dan mudah terjadi perpecahan'
        ]);

        Soal::create([
            'materi_id' => $materi1->id,
            'pertanyaan' => 'Bagaimana cara generasi muda menjaga nilai-nilai Pancasila di era modern?',
            'jawaban' => 'Dengan menjaga persatuan, menghormati perbedaan, dan bersikap sesuai nilai Pancasila'
        ]);

        // BAB 2
        $materi2 = Materi::create([
            'nama' => 'BAB 2: Penerapan Nilai-Nilai Pancasila'
        ]);

        Soal::create([
            'materi_id' => $materi2->id,
            'pertanyaan' => 'Mengapa nilai-nilai Pancasila harus diterapkan dalam kehidupan sehari-hari?',
            'jawaban' => 'Agar tercipta kehidupan yang rukun, damai, dan sesuai cita-cita bangsa Indonesia'
        ]);

        Soal::create([
            'materi_id' => $materi2->id,
            'pertanyaan' => 'Apa makna sila pertama Pancasila dalam kehidupan masyarakat?',
            'jawaban' => 'Menghormati kebebasan beragama dan percaya kepada Tuhan Yang Maha Esa'
        ]);

        Soal::create([
            'materi_id' => $materi2->id,
            'pertanyaan' => 'Bagaimana cara menerapkan sila kedua di lingkungan sekolah?',
            'jawaban' => 'Dengan menghormati teman dan tidak melakukan bullying'
        ]);

        Soal::create([
            'materi_id' => $materi2->id,
            'pertanyaan' => 'Mengapa persatuan sangat penting dalam kehidupan berbangsa?',
            'jawaban' => 'Agar bangsa Indonesia tidak mudah terpecah belah'
        ]);

        Soal::create([
            'materi_id' => $materi2->id,
            'pertanyaan' => 'Apa hubungan musyawarah dengan sila keempat Pancasila?',
            'jawaban' => 'Musyawarah digunakan untuk mengambil keputusan bersama secara bijaksana'
        ]);

        Soal::create([
            'materi_id' => $materi2->id,
            'pertanyaan' => 'Sebutkan contoh penerapan sila kelima di lingkungan masyarakat!',
            'jawaban' => 'Bersikap adil kepada semua warga dan saling membantu tanpa membeda-bedakan'
        ]);

        Soal::create([
            'materi_id' => $materi2->id,
            'pertanyaan' => 'Apa akibatnya jika masyarakat tidak menerapkan nilai-nilai Pancasila?',
            'jawaban' => 'Akan muncul konflik, perpecahan, dan ketidakadilan'
        ]);

        Soal::create([
            'materi_id' => $materi2->id,
            'pertanyaan' => 'Mengapa peserta didik perlu menghayati semangat para pendiri negara?',
            'jawaban' => 'Agar memiliki sikap cinta tanah air dan menghargai perjuangan bangsa'
        ]);

        Soal::create([
            'materi_id' => $materi2->id,
            'pertanyaan' => 'Bagaimana penerapan nilai Pancasila dalam kehidupan bernegara?',
            'jawaban' => 'Dengan menaati hukum dan menghormati hak serta kewajiban warga negara'
        ]);

        Soal::create([
            'materi_id' => $materi2->id,
            'pertanyaan' => 'Mengapa Pancasila disebut pedoman hidup bangsa Indonesia?',
            'jawaban' => 'Karena Pancasila menjadi dasar dan petunjuk dalam bersikap serta bertindak dalam kehidupan sehari-hari'
        ]);

        // BAB 3
        $materi3 = Materi::create([
            'nama' => 'BAB 3: Patuh Terhadap Norma'
        ]);

        Soal::create([
            'materi_id' => $materi3->id,
            'pertanyaan' => 'Mengapa manusia disebut sebagai makhluk sosial?',
            'jawaban' => 'Karena manusia membutuhkan bantuan dan hidup bersama orang lain'
        ]);

        Soal::create([
            'materi_id' => $materi3->id,
            'pertanyaan' => 'Apa yang dimaksud dengan norma?',
            'jawaban' => 'Aturan atau pedoman yang mengatur perilaku manusia dalam kehidupan'
        ]);

        Soal::create([
            'materi_id' => $materi3->id,
            'pertanyaan' => 'Mengapa norma diperlukan dalam kehidupan masyarakat?',
            'jawaban' => 'Agar tercipta ketertiban, keamanan, dan kehidupan yang harmonis'
        ]);

        Soal::create([
            'materi_id' => $materi3->id,
            'pertanyaan' => 'Apa akibatnya jika seseorang tidak mematuhi norma?',
            'jawaban' => 'Akan mendapatkan sanksi dan dapat merugikan orang lain'
        ]);

        Soal::create([
            'materi_id' => $materi3->id,
            'pertanyaan' => 'Apa perbedaan norma agama dan norma hukum?',
            'jawaban' => 'Norma agama berasal dari Tuhan, sedangkan norma hukum dibuat oleh negara'
        ]);

        Soal::create([
            'materi_id' => $materi3->id,
            'pertanyaan' => 'Mengapa manusia harus mampu membedakan perbuatan terpuji dan tercela?',
            'jawaban' => 'Agar dapat bertindak sesuai nilai kebaikan dan aturan yang berlaku'
        ]);

        Soal::create([
            'materi_id' => $materi3->id,
            'pertanyaan' => 'Sebutkan contoh perilaku yang sesuai dengan norma kesopanan di sekolah!',
            'jawaban' => 'Menghormati guru dan berbicara dengan sopan'
        ]);

        Soal::create([
            'materi_id' => $materi3->id,
            'pertanyaan' => 'Mengapa menaati norma dapat menciptakan kehidupan yang damai?',
            'jawaban' => 'Karena setiap orang saling menghormati hak dan kewajiban'
        ]);

        Soal::create([
            'materi_id' => $materi3->id,
            'pertanyaan' => 'Apa hubungan norma dengan kehidupan bermasyarakat?',
            'jawaban' => 'Norma menjadi pedoman agar masyarakat hidup tertib dan rukun'
        ]);

        Soal::create([
            'materi_id' => $materi3->id,
            'pertanyaan' => 'Bagaimana cara menunjukkan sikap patuh terhadap norma dalam kehidupan sehari-hari?',
            'jawaban' => 'Dengan menaati aturan, bersikap jujur, disiplin, dan menghormati orang lain'
        ]);

        // BAB 4
        $materi4 = Materi::create([
            'nama' => 'BAB 4: Keberagaman dalam Bingkai Bhinneka Tunggal Ika'
        ]);

        Soal::create([
            'materi_id' => $materi4->id,
            'pertanyaan' => 'Apa yang dimaksud dengan keberagaman?',
            'jawaban' => 'Keadaan masyarakat yang memiliki perbedaan suku, agama, budaya, dan golongan'
        ]);

        Soal::create([
            'materi_id' => $materi4->id,
            'pertanyaan' => 'Mengapa Indonesia memiliki keberagaman yang sangat banyak?',
            'jawaban' => 'Karena Indonesia terdiri dari banyak suku, budaya, bahasa, dan daerah'
        ]);

        Soal::create([
            'materi_id' => $materi4->id,
            'pertanyaan' => 'Apa arti semboyan Bhinneka Tunggal Ika?',
            'jawaban' => 'Berbeda-beda tetapi tetap satu'
        ]);

        Soal::create([
            'materi_id' => $materi4->id,
            'pertanyaan' => 'Mengapa masyarakat harus menghargai perbedaan agama dan kepercayaan?',
            'jawaban' => 'Agar tercipta kerukunan dan toleransi dalam kehidupan masyarakat'
        ]);

        Soal::create([
            'materi_id' => $materi4->id,
            'pertanyaan' => 'Apa yang dimaksud dengan perubahan sosial?',
            'jawaban' => 'Perubahan dalam kehidupan masyarakat dari waktu ke waktu'
        ]);

        Soal::create([
            'materi_id' => $materi4->id,
            'pertanyaan' => 'Sebutkan salah satu faktor penyebab perubahan sosial!',
            'jawaban' => 'Kemajuan teknologi / pendidikan / pengaruh budaya luar'
        ]);

        Soal::create([
            'materi_id' => $materi4->id,
            'pertanyaan' => 'Apa tantangan yang dapat muncul akibat keberagaman?',
            'jawaban' => 'Konflik atau perpecahan jika tidak saling menghargai'
        ]);

        Soal::create([
            'materi_id' => $materi4->id,
            'pertanyaan' => 'Bagaimana sikap yang tepat menghadapi keberagaman di lingkungan sekolah?',
            'jawaban' => 'Saling menghormati dan tidak membeda-bedakan teman'
        ]);

        Soal::create([
            'materi_id' => $materi4->id,
            'pertanyaan' => 'Mengapa keberagaman harus dijaga dalam kehidupan berbangsa?',
            'jawaban' => 'Karena keberagaman merupakan kekayaan dan identitas bangsa Indonesia'
        ]);

        Soal::create([
            'materi_id' => $materi4->id,
            'pertanyaan' => 'Bagaimana cara menerapkan Bhinneka Tunggal Ika dalam kehidupan sehari-hari?',
            'jawaban' => 'Dengan hidup rukun, toleransi, dan menghargai perbedaan antar sesama'
        ]);

        // BAB 5
        $materi5 = Materi::create([
            'nama' => 'BAB 5: Wilayah NKRI'
        ]);

        Soal::create([
            'materi_id' => $materi5->id,
            'pertanyaan' => 'Apa tujuan dibentuknya sebuah negara?',
            'jawaban' => 'Untuk mengatur kehidupan masyarakat agar aman dan sejahtera'
        ]);

        Soal::create([
            'materi_id' => $materi5->id,
            'pertanyaan' => 'Mengapa rakyat disebut unsur penting negara?',
            'jawaban' => 'Karena rakyat merupakan penduduk yang menjalankan kehidupan negara'
        ]);

        Soal::create([
            'materi_id' => $materi5->id,
            'pertanyaan' => 'Apa fungsi pemerintah dalam sebuah negara?',
            'jawaban' => 'Mengatur dan menjalankan pemerintahan negara'
        ]);

        Soal::create([
            'materi_id' => $materi5->id,
            'pertanyaan' => 'Indonesia memiliki bentuk negara apa?',
            'jawaban' => 'Negara kesatuan'
        ]);

        Soal::create([
            'materi_id' => $materi5->id,
            'pertanyaan' => 'Apa keuntungan Indonesia menjadi negara kesatuan?',
            'jawaban' => 'Persatuan bangsa lebih kuat dan pemerintahan lebih teratur'
        ]);

        Soal::create([
            'materi_id' => $materi5->id,
            'pertanyaan' => 'Mengapa masyarakat harus menjaga wilayah NKRI?',
            'jawaban' => 'Karena wilayah Indonesia adalah milik bersama seluruh rakyat Indonesia'
        ]);

        Soal::create([
            'materi_id' => $materi5->id,
            'pertanyaan' => 'Apa dampak jika masyarakat tidak menjaga persatuan bangsa?',
            'jawaban' => 'Dapat menimbulkan konflik dan mengancam keutuhan NKRI'
        ]);

        Soal::create([
            'materi_id' => $materi5->id,
            'pertanyaan' => 'Bagaimana peran generasi muda dalam menjaga keutuhan NKRI?',
            'jawaban' => 'Dengan menjaga persatuan, menghormati perbedaan, dan mencintai budaya Indonesia'
        ]);

        Soal::create([
            'materi_id' => $materi5->id,
            'pertanyaan' => 'Mengapa sikap cinta tanah air penting dimiliki setiap warga negara?',
            'jawaban' => 'Agar masyarakat peduli dan mau menjaga bangsa Indonesia'
        ]);

        Soal::create([
            'materi_id' => $materi5->id,
            'pertanyaan' => 'Sebutkan contoh upaya menjaga keutuhan wilayah Indonesia di lingkungan sekolah!',
            'jawaban' => 'Tidak membeda-bedakan teman dan menjaga kerukunan di sekolah'
        ]);
    }
}