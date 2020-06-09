<?php

use Illuminate\Database\Seeder;
use App\Question;

/**
 * Class DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Question::truncate();
        factory(Question::class, 10)->create();
    }
}
