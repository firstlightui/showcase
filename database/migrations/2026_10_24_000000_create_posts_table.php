<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('excerpt');
            $table->text('body');
            $table->timestamps();
        });

        // Sample data for the recipe screens: enough rows for two pages.
        $now = now();

        DB::table('posts')->insert(collect(range(1, 30))->map(fn (int $i) => [
            'title' => "Sample post {$i}",
            'excerpt' => "Excerpt for sample post {$i}.",
            'body' => "Body text for sample post {$i}. Recipe screens read this from SQLite.",
            'created_at' => $now->copy()->subMinutes($i),
            'updated_at' => $now->copy()->subMinutes($i),
        ])->all());
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
