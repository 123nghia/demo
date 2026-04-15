<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class UpdateProjectVideosTableForGlobalVideoManagement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('project_videos', 'slug')) {
            Schema::table('project_videos', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('title');
            });
        }

        if (!Schema::hasColumn('project_videos', 'content')) {
            Schema::table('project_videos', function (Blueprint $table) {
                $table->longText('content')->nullable()->after('description');
            });
        }

        if (!Schema::hasColumn('project_videos', 'display_zone')) {
            Schema::table('project_videos', function (Blueprint $table) {
                $table->string('display_zone', 20)->default('all')->after('content');
            });
        }

        if (!Schema::hasColumn('project_videos', 'seo_title')) {
            Schema::table('project_videos', function (Blueprint $table) {
                $table->string('seo_title')->nullable()->after('display_zone');
            });
        }

        if (!Schema::hasColumn('project_videos', 'seo_description')) {
            Schema::table('project_videos', function (Blueprint $table) {
                $table->text('seo_description')->nullable()->after('seo_title');
            });
        }

        if (!Schema::hasColumn('project_videos', 'published_at')) {
            Schema::table('project_videos', function (Blueprint $table) {
                $table->timestamp('published_at')->nullable()->after('seo_description');
            });
        }

        $this->backfillSlugAndPublishedAt();
        $this->ensureSlugUniqueIndex();
        $this->ensureDisplayZoneIndex();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropIndexSafely('project_videos', ['slug']);
        $this->dropIndexSafely('project_videos', ['display_zone']);

        $dropColumns = [];

        foreach (['slug', 'content', 'display_zone', 'seo_title', 'seo_description', 'published_at'] as $column) {
            if (Schema::hasColumn('project_videos', $column)) {
                $dropColumns[] = $column;
            }
        }

        if (!empty($dropColumns)) {
            Schema::table('project_videos', function (Blueprint $table) use ($dropColumns) {
                $table->dropColumn($dropColumns);
            });
        }
    }

    private function backfillSlugAndPublishedAt(): void
    {
        $videos = DB::table('project_videos')
            ->select(['id', 'title', 'slug', 'created_at', 'published_at'])
            ->orderBy('id')
            ->get();

        $usedSlugs = [];

        foreach ($videos as $video) {
            $existingSlug = trim((string) ($video->slug ?? ''));
            if ($existingSlug !== '') {
                $usedSlugs[$existingSlug] = true;
            }
        }

        foreach ($videos as $video) {
            $updates = [];
            $currentSlug = trim((string) ($video->slug ?? ''));

            if ($currentSlug === '') {
                $base = Str::slug((string) ($video->title ?? ''));
                $base = $base !== '' ? $base : 'video';

                $candidate = $base;
                $counter = 2;

                while (isset($usedSlugs[$candidate])) {
                    $candidate = $base . '-' . $counter;
                    $counter++;

                    if ($counter > 9999) {
                        $candidate = $base . '-' . Str::lower(Str::random(6));
                        break;
                    }
                }

                $usedSlugs[$candidate] = true;
                $updates['slug'] = $candidate;
            }

            if (is_null($video->published_at) && !empty($video->created_at)) {
                $updates['published_at'] = $video->created_at;
            }

            if (!empty($updates)) {
                DB::table('project_videos')
                    ->where('id', $video->id)
                    ->update($updates);
            }
        }
    }

    private function ensureSlugUniqueIndex(): void
    {
        try {
            Schema::table('project_videos', function (Blueprint $table) {
                $table->unique('slug');
            });
        } catch (\Throwable $exception) {
            // Ignore if index already exists.
        }
    }

    private function ensureDisplayZoneIndex(): void
    {
        try {
            Schema::table('project_videos', function (Blueprint $table) {
                $table->index('display_zone');
            });
        } catch (\Throwable $exception) {
            // Ignore if index already exists.
        }
    }

    private function dropIndexSafely(string $table, array $columns): void
    {
        try {
            Schema::table($table, function (Blueprint $blueprint) use ($columns) {
                $blueprint->dropUnique($columns);
            });

            return;
        } catch (\Throwable $exception) {
            // Try regular index if unique index was not found.
        }

        try {
            Schema::table($table, function (Blueprint $blueprint) use ($columns) {
                $blueprint->dropIndex($columns);
            });
        } catch (\Throwable $exception) {
            // Ignore missing index.
        }
    }
}
