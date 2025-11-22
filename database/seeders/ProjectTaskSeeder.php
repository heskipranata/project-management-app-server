<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class ProjectTaskSeeder extends Seeder
{
    public function run(): void
    {
        // create some users
        $users = User::factory(5)->create();

        // create projects with tasks
        Project::factory(10)->create()->each(function (Project $project) use ($users) {
            // create 3-8 tasks per project
            Task::factory(rand(3, 8))->create([
                'project_id' => $project->id,
                'assigned_to' => $users->random()->id,
            ]);
        });
    }
}
