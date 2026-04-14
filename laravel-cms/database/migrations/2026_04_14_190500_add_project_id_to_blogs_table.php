<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProjectIdToBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('blogs', 'project_id')) {
            return;
        }

        Schema::table('blogs', function (Blueprint $table) {
            $table->foreignId('project_id')
                ->nullable()
                ->after('id')
                ->constrained('projects')
                ->nullOnDelete();

            $table->index(['project_id', 'is_published']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('blogs', 'project_id')) {
            return;
        }

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropIndex(['project_id', 'is_published']);
            $table->dropForeign(['project_id']);
            $table->dropColumn('project_id');
        });
    }
}
