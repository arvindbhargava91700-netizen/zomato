<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add likes/dislikes counters to restaurant_blogs table if not present
        Schema::table('restaurant_blogs', function (Blueprint $table) {
            if (!Schema::hasColumn('restaurant_blogs', 'likes_count')) {
                $table->unsignedInteger('likes_count')->default(0)->after('views_count');
            }
            if (!Schema::hasColumn('restaurant_blogs', 'dislikes_count')) {
                $table->unsignedInteger('dislikes_count')->default(0)->after('likes_count');
            }
        });

        // 2. Blog reactions table (like/dislike on blog)
        Schema::create('restaurant_blog_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_blog_id')->constrained('restaurant_blogs')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->string('session_id', 100)->nullable();
            $table->enum('reaction_type', ['like', 'dislike'])->default('like');
            $table->timestamps();

            $table->index(['restaurant_blog_id', 'user_id'], 'rb_react_user_idx');
            $table->index(['restaurant_blog_id', 'session_id'], 'rb_react_sess_idx');
        });

        // 3. Blog comments table (comments and replies)
        Schema::create('restaurant_blog_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_blog_id')->constrained('restaurant_blogs')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('restaurant_blog_comments')->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('email', 150);
            $table->text('comment');
            $table->unsignedInteger('likes_count')->default(0);
            $table->unsignedInteger('dislikes_count')->default(0);
            $table->enum('status', ['approved', 'pending', 'spam'])->default('approved');
            $table->string('session_id', 100)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['restaurant_blog_id', 'parent_id', 'status'], 'rb_comm_filter_idx');
        });

        // 4. Comment reactions table (like/dislike on comment)
        Schema::create('restaurant_blog_comment_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained('restaurant_blog_comments')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->string('session_id', 100)->nullable();
            $table->enum('reaction_type', ['like', 'dislike'])->default('like');
            $table->timestamps();

            $table->index(['comment_id', 'user_id'], 'rbc_react_user_idx');
            $table->index(['comment_id', 'session_id'], 'rbc_react_sess_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_blog_comment_reactions');
        Schema::dropIfExists('restaurant_blog_comments');
        Schema::dropIfExists('restaurant_blog_reactions');

        Schema::table('restaurant_blogs', function (Blueprint $table) {
            if (Schema::hasColumn('restaurant_blogs', 'dislikes_count')) {
                $table->dropColumn('dislikes_count');
            }
            if (Schema::hasColumn('restaurant_blogs', 'likes_count')) {
                $table->dropColumn('likes_count');
            }
        });
    }
};
