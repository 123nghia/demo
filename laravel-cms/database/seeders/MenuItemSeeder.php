<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mainMenus = [
            [
                'label' => 'Trang chủ',
                'url' => '/',
                'menu_zone' => MenuItem::ZONE_MAIN,
                'parent_id' => null,
                'page_key' => 'home',
                'sort_order' => 1,
                'is_active' => true,
                'open_in_new_tab' => false,
                'is_home_icon' => true,
            ],
            [
                'label' => 'Vinhomes Ocean Park',
                'url' => '/thiet-ke-biet-thu-vinhomes-ocean-park',
                'menu_zone' => MenuItem::ZONE_MAIN,
                'parent_id' => null,
                'page_key' => 'oceanpark',
                'sort_order' => 2,
                'is_active' => true,
                'open_in_new_tab' => false,
                'is_home_icon' => false,
            ],
            [
                'label' => 'Giới thiệu',
                'url' => '/about-us',
                'menu_zone' => MenuItem::ZONE_MAIN,
                'parent_id' => null,
                'page_key' => 'about',
                'sort_order' => 3,
                'is_active' => true,
                'open_in_new_tab' => false,
                'is_home_icon' => false,
            ],
            [
                'label' => 'Blog',
                'url' => '/blog',
                'menu_zone' => MenuItem::ZONE_MAIN,
                'parent_id' => null,
                'page_key' => 'blog',
                'sort_order' => 4,
                'is_active' => true,
                'open_in_new_tab' => false,
                'is_home_icon' => false,
            ],
            [
                'label' => 'Video',
                'url' => '/video',
                'menu_zone' => MenuItem::ZONE_MAIN,
                'parent_id' => null,
                'page_key' => 'video',
                'sort_order' => 5,
                'is_active' => true,
                'open_in_new_tab' => false,
                'is_home_icon' => false,
            ],
            [
                'label' => 'Liên hệ',
                'url' => '/lien-he',
                'menu_zone' => MenuItem::ZONE_MAIN,
                'parent_id' => null,
                'page_key' => 'contact',
                'sort_order' => 6,
                'is_active' => true,
                'open_in_new_tab' => false,
                'is_home_icon' => false,
            ],
        ];

        foreach ($mainMenus as $menuItem) {
            MenuItem::query()->updateOrCreate(
                [
                    'label' => $menuItem['label'],
                    'url' => $menuItem['url'],
                    'menu_zone' => $menuItem['menu_zone'],
                ],
                $menuItem
            );
        }
    }
}
