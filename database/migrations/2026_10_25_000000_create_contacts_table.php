<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('email');
            $table->string('phone');
            $table->timestamps();
        });

        // Sample data for the large-list recipe: enough rows that building
        // them all at once would stall the screen.
        $now = now();

        foreach (collect(range(1, 5000))->chunk(500) as $chunk) {
            DB::table('contacts')->insert($chunk->map(fn (int $i) => [
                'name' => sprintf('Contact %04d', $i),
                'email' => sprintf('contact%04d@example.com', $i),
                'phone' => sprintf('+61 400 %03d %03d', intdiv($i, 1000), $i % 1000),
                'created_at' => $now,
                'updated_at' => $now,
            ])->all());
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
