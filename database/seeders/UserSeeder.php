<?php

namespace Database\Seeders;

use App\Models\ScheduledClass;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(10)->create();
        User::factory()
            ->has(
                ScheduledClass::factory()
                    ->count(2)
                    ->sequence(fn (Sequence $sequence) => [
                        'date_time' => Carbon::now()->addHours(24+ (++$sequence->index))->minutes(0)->seconds(0)
                    ])
            )
            ->count(10)->create(['role' => 'instructor']);

        User::factory()
            ->has(
                ScheduledClass::factory()
                    ->sequence(fn (Sequence $sequence) => [
                        'date_time' => Carbon::now()->addHours(45+ (++$sequence->index))->minutes(0)->seconds(0)
                        ])
                    ->count(2)
            )
            ->create([
                'name'      => 'brandon',
                'email'     => 'brandon@email.com',
                'role'      => 'instructor',
            ]);

        User::factory()->create([
            'name'      => 'chanse',
            'email'     => 'chanse@email.com',
        ]);
        User::factory()->create([
            'name'      => 'jorden',
            'email'     => 'jordin@email.com',
        ]);

        User::factory()->create([
            'name'      => 'jordin marshall',
            'email'     => 'jordin@admin.com',
            'password'  => 'admin',
            'role'      => 'admin',
        ]);
    }
}
