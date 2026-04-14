<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddZoneAndParentToMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->string('menu_zone')->default('main')->after('url');
            $table->foreignId('parent_id')
                ->nullable()
                ->after('menu_zone')
                ->constrained('menu_items')
                ->nullOnDelete();

            $table->index(['menu_zone', 'sort_order']);
            $table->index(['parent_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropIndex(['menu_zone', 'sort_order']);
            $table->dropIndex(['parent_id', 'sort_order']);
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['menu_zone', 'parent_id']);
        });
    }
}
