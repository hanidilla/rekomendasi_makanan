<?php

namespace Database\Seeders;

use App\Models\FaktorAktivitas;
use App\Models\FaktorStress;
use App\Models\KoreksiUmur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaktorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $faktor_stress = [
            [
                'nama' => 'DM Murni',
                'presentase' => 0.1,
            ],
            [
                'nama' => 'CHF, bedah minor. CVA (kasus neuro)',
                'presentase' => random_int(1, 2) / 10,
            ],
            [
                'nama' => 'Febris, Kenaikan suhu 1 derajat',
                'presentase' => 0.13,
            ],
            [
                'nama' => 'Infeksi',
                'presentase' => rand(2, 4) / 10,
            ],
            [
                'nama' => 'CH, Ca',
                'presentase' => 0.5,
            ],
            [
                'nama' => 'Sepsis',
                'presentase' => rand(5, 8) / 10,
            ], [
                'nama' => 'Post operasi ektif',
                'presentase' => rand(1, 5) / 10,
            ], [
                'nama' => 'Luka bakar 10%',
                'presentase' => rand(1, 25) / 10,
            ], [
                'nama' => 'Luka bakar 25%',
                'presentase' => rand(2, 5) / 10,
            ], [
                'nama' => 'Luka bakar 50%',
                'presentase' => rand(5, 10) / 10,
            ]
        ];

        $faktor_aktifitas = [
            [
                'nama' => 'total bed rest',
                'presentase' => 0.05,
            ], [
                'nama' => 'mobilitas di tempat tidur',
                'presentase' => 0.1
            ], [
                'nama' => 'jalan disekitar kamar',
                'presentase' => 0.2
            ], [
                'nama' => 'aktifitas ringan',
                'presentase' => 0.3
            ], [
                'nama' => 'aktifitas sedang',
                'presentase' => 0.4
            ], [
                'nama' => 'aktifitas berat',
                'presentase' => 0.5
            ]
        ];

        $koreksi_umur = [
            [
                'nama' => 'umur 40 - 49 tahun',
                'presentase' => 0.05,
            ], [
                'nama' => 'umur 50-59 tahun',
                'presentase' => 0.1
            ], [
                'nama' => 'umur 60-69 tahun',
                'presentase' => 0.15
            ], [
                'nama' => 'umur >70 tahun',
                'presentase' => 0.2
            ],
        ];

        foreach ($faktor_aktifitas as $key => $value) {
            FaktorAktivitas::create($value);
        }

        foreach ($faktor_stress as $key => $value) {
            FaktorStress::create($value);
        }

        foreach ($koreksi_umur as $key => $value) {
            KoreksiUmur::create($value);
        }
    }
}
