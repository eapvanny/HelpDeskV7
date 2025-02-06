<?php

namespace Database\Seeders;

use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        if (Status::count() === 0) {
            $now = Carbon::now();
            Status::insert([
                ['name' => 'Open', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Pending', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Resolved', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Closed', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }
    }
}