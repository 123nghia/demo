<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDisplayZoneToBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('blogs', 'display_zone')) {
            return;
        }

        Schema::table('blogs', function (Blueprint $table) {
            $table->string('display_zone', 20)
                ->default('all')
                ->after('slug');

            $table->index(['is_published', 'display_zone', 'published_at'], 'blogs_visibility_zone_published_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('blogs', 'display_zone')) {
            return;
        }

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropIndex('blogs_visibility_zone_published_idx');
            $table->dropColumn('display_zone');
        });
    }
}
