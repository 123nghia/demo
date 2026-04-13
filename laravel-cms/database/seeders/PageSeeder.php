<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = [
            [
                'name' => 'Trang chủ',
                'slug' => 'home',
                'legacy_file' => 'home.html',
                'page_key' => 'home',
                'seo_title' => 'HOVI Việt Nam | Thiết Kế & Thi Công Cảnh Quan, Sân Vườn Cao Cấp',
                'seo_description' => 'HOVI Việt Nam chuyên thiết kế, thi công cảnh quan và sân vườn cao cấp cho biệt thự, penthouse và khu đô thị.',
                'sort_order' => 1,
                'is_published' => true,
            ],
            [
                'name' => 'Giới thiệu',
                'slug' => 'about-us',
                'legacy_file' => 'about-us.html',
                'page_key' => 'about',
                'seo_title' => 'Giới Thiệu HOVI Việt Nam | Tầm Nhìn, Sứ Mệnh & Năng Lực',
                'seo_description' => 'Khám phá HOVI Việt Nam, đơn vị thiết kế thi công cảnh quan và sân vườn cao cấp với quy trình rõ ràng.',
                'sort_order' => 2,
                'is_published' => true,
            ],
            [
                'name' => 'Liên hệ',
                'slug' => 'lien-he',
                'legacy_file' => 'lien-he.html',
                'page_key' => 'contact',
                'seo_title' => 'Liên Hệ HOVI Việt Nam | Đặt Lịch Tư Vấn Thiết Kế Cảnh Quan',
                'seo_description' => 'Liên hệ HOVI Việt Nam để đặt lịch tư vấn thiết kế cảnh quan, sân vườn, biệt thự và penthouse.',
                'sort_order' => 3,
                'is_published' => true,
            ],
            [
                'name' => 'Thiết kế biệt thự Vinhomes Ocean Park',
                'slug' => 'thiet-ke-biet-thu-vinhomes-ocean-park',
                'legacy_file' => 'ocean-park.html',
                'page_key' => 'oceanpark',
                'seo_title' => 'Thiết Kế Biệt Thự Vinhomes Ocean Park | Dự Án HOVI Việt Nam',
                'seo_description' => 'Tổng hợp các dự án thiết kế biệt thự Vinhomes Ocean Park do HOVI Việt Nam thực hiện.',
                'sort_order' => 4,
                'is_published' => true,
            ],
            [
                'name' => 'Biệt thự đơn lập M07-L14 Dương Nội',
                'slug' => 'biet-thu-don-lap-m07-l14-dtm-duong-noi',
                'legacy_file' => 'duong-noi.html',
                'page_key' => 'project',
                'seo_title' => 'Biệt Thự Đơn Lập M07-L14 ĐTM Dương Nội | Dự Án HOVI Việt Nam',
                'seo_description' => 'Chi tiết dự án biệt thự đơn lập M07-L14 ĐTM Dương Nội của HOVI Việt Nam với hình ảnh phối cảnh.',
                'sort_order' => 5,
                'is_published' => true,
            ],
        ];

        foreach ($pages as $page) {
            Page::query()->updateOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }
    }
}
