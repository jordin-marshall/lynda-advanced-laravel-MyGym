<?php

namespace Tests\Feature;

use App\Models\ClassType;
use App\Models\ScheduledClass;
use App\Models\User;
use Database\Seeders\ClassTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstructorTest extends TestCase
{
    use RefreshDatabase;

    public $user;

    public function setup(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => 'instructor',
        ]);

        $this->actingAs($this->user);

        $this->seed(ClassTypeSeeder::class);

    }

    /**
     * A basic feature test example.
     */
    public function test_instructor_is_redirected_to_instructor_dashboard(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirectToRoute('instructor.dashboard');
    }

    public function test_instructor_dashboars_displays_correct_text(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirectToRoute('instructor.dashboard');

        $this->followRedirects($response)->assertSeeText('Hey Instructor');
    }

    public function test_instructor_dashboars_displays_incorrect_text(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirectToRoute('instructor.dashboard');

        $this->followRedirects($response)->assertDontSeeText('Hey Professors');
    }

    public function test_instructor_can_schedule_a_class(): void
    {
        //$this->seed(ClassTypeSeeder::class);

        $response = $this->post('/instructor/schedule', [
            'class_type_id' => ClassType::first()->id,
            'date' => '2024-04-20',
            'time' => '9:00:00',
        ]);

        $this->assertDatabaseHas('scheduled_classes', [
            'class_type_id' => ClassType::first()->id,
            'date_time' => '2024-04-20 9:00:00',
        ]);

        $response->assertRedirectToRoute('schedule.index');

    }

    public function test_instructor_can_cancel_a_scheduled_class(): void
    {
        $scheduledClass = ScheduledClass::create([
            'instructor_id' => $this->user->id,
            'class_type_id' => ClassType::first()->id,
            'date_time' => '2024-04-20 10:00:00',
        ]);

        $response = $this->delete('/instructor/schedule/'.$scheduledClass->id);

        $this->assertDatabaseMissing('scheduled_classes', [
            'id' => $scheduledClass->id,
        ]);
    }

    public function test_instructor_cant_cancel_a_scheduled_class_for_another_instructor(): void
    {
        $user2 = User::factory()->create([
            'role' => 'instructor',
        ]);
        //$this->seed(ClassTypeSeeder::class);

        $scheduledClass = ScheduledClass::create([
            'instructor_id' => $user2->id,
            'class_type_id' => ClassType::first()->id,
            'date_time' => '2024-04-20 10:00:00',
        ]);

        $response = $this->delete('/instructor/schedule/'.$scheduledClass->id);
        $response->assertStatus(403);

        $this->assertDatabaseHas('scheduled_classes', [
            'id' => $scheduledClass->id,
        ]);
    }

    public function test_cannot_cancel_class_less_than_two_hours_before()
    {
        //$this->seed(ClassTypeSeeder::class);

        $scheduledClass = ScheduledClass::create([
            'instructor_id' => $this->user->id,
            'class_type_id' => ClassType::first()->id,
            'date_time' => now()->addHours(1)->minutes(0)->seconds(0),
        ]);

        $response = $this->get('instructor/schedule');

        $response->assertDontSeeText('Cancel');

        $response = $this->delete('/instructor/schedule/'.$scheduledClass->id);

        $this->assertDatabaseHas('scheduled_classes', [
            'id' => $scheduledClass->id,
        ]);
    }
}
