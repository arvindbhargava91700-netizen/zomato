<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndiaUpLocationSeeder extends Seeder
{
    /**
     * Seeds only:
     * - Country: India
     * - State: Uttar Pradesh
     * - Cities: all 75 districts of Uttar Pradesh
     */
    public function run(): void
    {
        DB::transaction(function () {

            // 1. Country: India
            $countryId = DB::table('countries')->insertGetId([
                'name'       => 'India',
                'status'     => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. State: Uttar Pradesh
            $stateId = DB::table('states')->insertGetId([
                'country_id' => $countryId,
                'name'       => 'Uttar Pradesh',
                'status'     => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. Cities: All 75 districts of Uttar Pradesh
            $cities = [
                'Agra', 'Aligarh', 'Ambedkar Nagar', 'Amethi', 'Amroha',
                'Auraiya', 'Ayodhya', 'Azamgarh', 'Baghpat', 'Bahraich',
                'Ballia', 'Balrampur', 'Banda', 'Barabanki', 'Bareilly',
                'Basti', 'Bhadohi', 'Bijnor', 'Budaun', 'Bulandshahr',
                'Chandauli', 'Chitrakoot', 'Deoria', 'Etah', 'Etawah',
                'Farrukhabad', 'Fatehpur', 'Firozabad', 'Gautam Buddha Nagar', 'Ghaziabad',
                'Ghazipur', 'Gonda', 'Gorakhpur', 'Hamirpur', 'Hapur',
                'Hardoi', 'Hathras', 'Jalaun', 'Jaunpur', 'Jhansi',
                'Kannauj', 'Kanpur Dehat', 'Kanpur Nagar', 'Kasganj', 'Kaushambi',
                'Kushinagar', 'Lakhimpur Kheri', 'Lalitpur', 'Lucknow', 'Maharajganj',
                'Mahoba', 'Mainpuri', 'Mathura', 'Mau', 'Meerut',
                'Mirzapur', 'Moradabad', 'Muzaffarnagar', 'Pilibhit', 'Pratapgarh',
                'Prayagraj', 'Rae Bareli', 'Rampur', 'Saharanpur', 'Sambhal',
                'Sant Kabir Nagar', 'Shahjahanpur', 'Shamli', 'Shravasti', 'Siddharthnagar',
                'Sitapur', 'Sonbhadra', 'Sultanpur', 'Unnao', 'Varanasi',
            ];

            $now = now();
            $rows = array_map(function ($cityName) use ($stateId, $now) {
                return [
                    'state_id'   => $stateId,
                    'name'       => $cityName,
                    'status'     => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }, $cities);

            // Insert in chunks to keep queries safe for large lists
            foreach (array_chunk($rows, 100) as $chunk) {
                DB::table('cities')->insert($chunk);
            }
        });

        $this->command->info('India -> Uttar Pradesh -> 75 districts seeded successfully.');
    }
}
