<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Criterion;
use App\Models\ScoringParameter;
use App\Models\Period;
use App\Models\Customer;

class SmartSeeder extends Seeder
{
    public function run()
    {
        // Hati-hati saat seeding di DB yang sudah ada
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ScoringParameter::truncate();
        Criterion::truncate();
        Period::truncate();
        Customer::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 4 criteria fixed
        $c1 = Criterion::create([
            'code' => 'C1',
            'name' => 'Riwayat Pengembalian',
            'type' => 'cost',
            'weight' => 0.35
        ]);
        $c2 = Criterion::create([
            'code' => 'C2',
            'name' => 'Penghasilan',
            'type' => 'benefit',
            'weight' => 0.20
        ]);
        $c3 = Criterion::create([
            'code' => 'C3',
            'name' => 'Jumlah Pinjaman',
            'type' => 'cost',
            'weight' => 0.25
        ]);
        $c4 = Criterion::create([
            'code' => 'C4',
            'name' => 'Lama Keanggotaan',
            'type' => 'benefit',
            'weight' => 0.20
        ]);

        // Parameters: C1 (keterlambatan - cost)
        ScoringParameter::insert([
            ['criterion_id' => $c1->id, 'min_value' => 0, 'max_value' => 0, 'score' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['criterion_id' => $c1->id, 'min_value' => 1, 'max_value' => 2, 'score' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['criterion_id' => $c1->id, 'min_value' => 3, 'max_value' => 5, 'score' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['criterion_id' => $c1->id, 'min_value' => 6, 'max_value' => 10, 'score' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['criterion_id' => $c1->id, 'min_value' => 11, 'max_value' => 999999999, 'score' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // C2 Penghasilan (benefit) - rupiah
        ScoringParameter::insert([
            ['criterion_id' => $c2->id, 'min_value' => 7000001, 'max_value' => 9999999999, 'score' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['criterion_id' => $c2->id, 'min_value' => 5000001, 'max_value' => 7000000, 'score' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['criterion_id' => $c2->id, 'min_value' => 3000001, 'max_value' => 5000000, 'score' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['criterion_id' => $c2->id, 'min_value' => 1000000, 'max_value' => 3000000, 'score' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['criterion_id' => $c2->id, 'min_value' => 0, 'max_value' => 999999, 'score' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // C3 Jumlah Pinjaman (cost) - rupiah
        ScoringParameter::insert([
            ['criterion_id' => $c3->id, 'min_value' => 0, 'max_value' => 1999999, 'score' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['criterion_id' => $c3->id, 'min_value' => 2000000, 'max_value' => 5000000, 'score' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['criterion_id' => $c3->id, 'min_value' => 5000001, 'max_value' => 7000000, 'score' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['criterion_id' => $c3->id, 'min_value' => 7000001, 'max_value' => 10000000, 'score' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['criterion_id' => $c3->id, 'min_value' => 10000001, 'max_value' => 9999999999, 'score' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // C4 Lama Keanggotaan (benefit) - tahun
        ScoringParameter::insert([
            ['criterion_id' => $c4->id, 'min_value' => 11, 'max_value' => 999, 'score' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['criterion_id' => $c4->id, 'min_value' => 7, 'max_value' => 10, 'score' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['criterion_id' => $c4->id, 'min_value' => 4, 'max_value' => 6, 'score' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['criterion_id' => $c4->id, 'min_value' => 2, 'max_value' => 3, 'score' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['criterion_id' => $c4->id, 'min_value' => 0, 'max_value' => 1, 'score' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Sample period (bulan sekarang) & sample customers
        $now = now();
        Period::create(['month' => $now->month, 'year' => $now->year, 'label' => $now->translatedFormat('F Y')]);

        Customer::insert([
            ['name' => 'Andi', 'identifier' => 'KTP001', 'phone' => '0811000001', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Budi', 'identifier' => 'KTP002', 'phone' => '0811000002', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Citra', 'identifier' => 'KTP003', 'phone' => '0811000003', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
