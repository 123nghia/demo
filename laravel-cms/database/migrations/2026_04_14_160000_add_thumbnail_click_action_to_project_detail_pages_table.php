<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThumbnailClickActionToProjectDetailPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_detail_pages', function (Blueprint $table) {
            if (!Schema::hasColumn('project_detail_pages', 'thumbnail_click_action')) {
                $table->string('thumbnail_click_action', 20)
                    ->default('link')
                    ->after('thumbnail_image');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_detail_pages', function (Blueprint $table) {
            if (Schema::hasColumn('project_detail_pages', 'thumbnail_click_action')) {
                $table->dropColumn('thumbnail_click_action');
            }
        });
    }
}
