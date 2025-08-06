<?php

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Language::truncate();
        Language::insert([
            [
                'iso'=>'ar',
            ],
            [
                'iso'=>'en',
            ],
            [
                'iso'=>'de',
            ],
            [
                'iso'=>'es',
            ],
            [
                'iso'=>'et',
            ],
            [
                'iso'=>'fa',
            ],
            [
                'iso'=>'fr',
            ],
            [
                'iso'=>'id',
            ],
            [
                'iso'=>'it',
            ],
            [
                'iso'=>'nl',
            ],
            [
                'iso'=>'de',
            ],
            [
                'iso'=>'pl',
            ],
            [
                'iso'=>'pt',
            ],
            [
                'iso'=>'ro',
            ],
            [
                'iso'=>'ru',
            ],
            [
                'iso'=>'th',
            ],
            [
                'iso'=>'tr',
            ],
            [
                'iso'=>'pt-br',
            ],
            [
                'iso'=>'zh-cn',
            ],
            [
                'iso'=>'zh-tw',
            ],
        ]);
    }
}
