<?php

namespace Database\Seeders;

use App\Models\ClassType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = ['Yoga', 'Dance Fitness','Boxing', 'Jiu Jitsu'];

        foreach($classes as $class) {
            ClassType::create([
                'name'          => $class,
                'description'   => fake()->text(),
                'minutes'       => random_int(10,18) * 5,
            ]);
        }
    }
}
