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

        $aboutParents = [
            [
                'key' => 'overview',
                'label' => 'Tổng quan',
                'url' => '#tong-quan',
                'page_key' => 'about',
                'sort_order' => 1,
            ],
            [
                'key' => 'core-values',
                'label' => 'Giá trị & cam kết',
                'url' => '#gia-tri',
                'page_key' => 'about',
                'sort_order' => 2,
            ],
            [
                'key' => 'team-profile',
                'label' => 'Đội ngũ & hồ sơ',
                'url' => '#ceo',
                'page_key' => 'about',
                'sort_order' => 3,
            ],
        ];

        $createdParents = [];
        foreach ($aboutParents as $parent) {
            $record = MenuItem::query()->updateOrCreate(
                [
                    'label' => $parent['label'],
                    'url' => $parent['url'],
                    'menu_zone' => MenuItem::ZONE_ABOUT_US,
                    'parent_id' => null,
                ],
                [
                    'label' => $parent['label'],
                    'url' => $parent['url'],
                    'menu_zone' => MenuItem::ZONE_ABOUT_US,
                    'parent_id' => null,
                    'page_key' => $parent['page_key'],
                    'sort_order' => $parent['sort_order'],
                    'is_active' => true,
                    'open_in_new_tab' => false,
                    'is_home_icon' => false,
                ]
            );

            $createdParents[$parent['key']] = $record;
        }

        $aboutChildren = [
            [
                'parent' => 'overview',
                'label' => 'Sứ mệnh & Tầm nhìn',
                'url' => '#su-menh',
                'sort_order' => 1,
            ],
            [
                'parent' => 'core-values',
                'label' => 'Lợi thế cạnh tranh',
                'url' => '#loi-the',
                'sort_order' => 1,
            ],
            [
                'parent' => 'team-profile',
                'label' => 'Hồ sơ năng lực',
                'url' => '#ho-so',
                'sort_order' => 1,
            ],
        ];

        foreach ($aboutChildren as $child) {
            $parent = $createdParents[$child['parent']] ?? null;
            if (!$parent instanceof MenuItem) {
                continue;
            }

            MenuItem::query()->updateOrCreate(
                [
                    'label' => $child['label'],
                    'url' => $child['url'],
                    'menu_zone' => MenuItem::ZONE_ABOUT_US,
                    'parent_id' => $parent->id,
                ],
                [
                    'label' => $child['label'],
                    'url' => $child['url'],
                    'menu_zone' => MenuItem::ZONE_ABOUT_US,
                    'parent_id' => $parent->id,
                    'page_key' => 'about',
                    'sort_order' => $child['sort_order'],
                    'is_active' => true,
                    'open_in_new_tab' => false,
                    'is_home_icon' => false,
                ]
            );
        }
    }
}
