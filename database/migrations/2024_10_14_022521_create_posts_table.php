<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wordpress_id')->unique()->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('mobiledoc');
            $table->string('feature_image')->nullable();
            $table->string('feature_image_alt')->nullable();
            $table->text('feature_image_caption')->nullable();
            $table->boolean('featured')->default(false);
            $table->string('type');
            $table->string('status');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
