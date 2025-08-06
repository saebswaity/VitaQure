<?php

use Illuminate\Database\Seeder;
use App\Models\CatogeryTest;

class CatogeryTestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CatogeryTest::truncate();

        CatogeryTest::insert([
            [
                'catogery' => 'Hematology',
                'description' => 'Blood cell analysis and related tests',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'catogery' => 'Biochemistry',
                'description' => 'Chemical analysis of body fluids',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'catogery' => 'Microbiology',
                'description' => 'Study of microorganisms and their effects',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'catogery' => 'Immunology',
                'description' => 'Study of immune system and its responses',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
} 