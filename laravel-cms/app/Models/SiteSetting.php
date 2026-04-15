<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    public const ABOUT_CONTENT_KEY = 'about_content';
    public const HOME_CONTENT_KEY = 'home_content';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
    ];

    public static function defaults(): array
    {
        return [
            'site_name' => 'HOVI Việt Nam',
            'site_tagline' => 'Thiết kế và thi công cảnh quan, sân vườn cao cấp cho biệt thự và penthouse.',
            'header_logo' => '/theme/logoMenuRight1.png',
            'footer_logo' => '/theme/logofooter.png',
            'favicon' => '/theme/logohome.png',

            'seo_default_title' => 'HOVI Việt Nam | Thiết Kế & Thi Công Cảnh Quan, Sân Vườn Cao Cấp',
            'seo_default_description' => 'HOVI Việt Nam chuyên thiết kế, thi công cảnh quan và sân vườn cao cấp cho biệt thự, penthouse và khu đô thị.',
            'seo_keywords' => 'thiết kế cảnh quan, thi công sân vườn, biệt thự, penthouse, HOVI Việt Nam',
            'seo_robots' => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
            'seo_canonical_base' => rtrim((string) config('app.url'), '/'),
            'seo_og_image' => '/theme/logohome.png',

            'footer_company_name' => 'CÔNG TY TNHH HOVI VIỆT NAM',
            'footer_tax_code' => '2301198445',
            'footer_address' => 'BT6 KĐT Việt Hưng, Long Biên, Hà Nội',
            'footer_website' => 'https://www.hovi.com.vn',
            'footer_email' => 'hovivietnam99@gmail.com',
            'footer_phone' => '0988991635',
            'footer_copyright' => '© HOVI Việt Nam',

            'social_facebook' => 'https://www.hovi.com.vn',
            'social_tiktok' => 'https://www.hovi.com.vn',
            'social_youtube' => 'https://www.hovi.com.vn',
            'social_messenger' => 'https://www.hovi.com.vn/',
            'social_zalo' => 'https://zalo.me/0988991635',
        ];
    }

    public static function aboutContentDefaults(): array
    {
        return [
            'hero' => [
                'enabled' => true,
                'eyebrow' => 'About Us',
                'title' => 'HOVI VIỆT NAM - THIẾT KẾ & THI CÔNG CẢNH QUAN, SÂN VƯỜN CAO CẤP',
                'description' => 'Thành lập từ năm 2021, HOVI Việt Nam mang sứ mệnh đưa thiên nhiên vào không gian sống một cách nghệ thuật, tinh tế và bền vững. Chúng tôi tập trung vào các giải pháp cảnh quan cá nhân hóa cho biệt thự, penthouse và không gian dịch vụ.',
                'image' => '/theme/assets/hovi/about-hero.png',
                'image_alt' => 'Cảnh quan sân vườn HOVI Việt Nam',
            ],
            'mission' => [
                'enabled' => true,
                'title' => 'Sứ mệnh',
                'description' => 'Kiến tạo những không gian xanh “độc bản”, kết hợp thẩm mỹ và công năng để nâng cao chất lượng sống, sức khỏe tinh thần và giá trị lâu dài cho từng công trình.',
                'image' => '/theme/assets/hovi/about-mission.png',
                'image_alt' => 'Không gian xanh biệt thự - HOVI Việt Nam',
            ],
            'vision' => [
                'enabled' => true,
                'title' => 'Tầm nhìn',
                'description' => 'Trở thành đơn vị dẫn đầu trong lĩnh vực thiết kế cảnh quan thông minh (Smart Landscape) tại Việt Nam, kết hợp hài hòa giữa kiến trúc hiện đại và hệ sinh thái tự nhiên.',
                'image' => '/theme/assets/hovi/about-vision.png',
                'image_alt' => 'Thiết kế sân vườn phong cách hiện đại',
            ],
            'inspiration' => [
                'enabled' => true,
                'title' => 'Cảm hứng thương hiệu',
                'subtitle' => 'HOVI - Garden Flowers',
                'description' => 'HOVI theo đuổi triết lý “Tinh tế - Chu đáo, Sáng tạo - Đam mê”, mang tinh thần thủ công chỉn chu vào từng chi tiết cảnh quan để mỗi không gian đều có bản sắc riêng và giá trị sử dụng bền vững.',
                'image' => '/theme/assets/hovi/about-inspiration.png',
                'image_alt' => 'Cảm hứng cảnh quan bonsai nghệ thuật',
            ],
            'definition' => [
                'enabled' => true,
                'title' => 'HOVI: Không gian xanh cho phong cách sống hiện đại',
                'description' => 'Chúng tôi tin rằng sân vườn không chỉ là phần “trang trí”, mà là lá phổi xanh của ngôi nhà và là nơi tái tạo năng lượng mỗi ngày. HOVI ứng dụng thiết kế 3D trực quan, thi công đúng chuẩn và bảo hành dài hạn để đảm bảo chất lượng.',
            ],
            'core' => [
                'enabled' => true,
                'heading' => 'Giá trị cốt lõi',
                'items' => [
                    [
                        'title' => 'Tinh tế',
                        'description' => 'Mỗi chi tiết cảnh quan được cân chỉnh hài hòa theo kiến trúc, công năng và trải nghiệm sử dụng thực tế.',
                        'image' => '/theme/assets/hovi/about-core-1.jpg',
                        'image_alt' => 'Tinh tế',
                    ],
                    [
                        'title' => 'Chu đáo',
                        'description' => 'Lắng nghe kỹ nhu cầu khách hàng, đồng hành xuyên suốt từ tư vấn, thiết kế đến chăm sóc sau bàn giao.',
                        'image' => '/theme/assets/hovi/about-core-2.jpg',
                        'image_alt' => 'Chu đáo',
                    ],
                    [
                        'title' => 'Sáng tạo',
                        'description' => 'Liên tục cập nhật xu hướng và công nghệ để mang đến giải pháp thiết kế độc bản cho từng công trình.',
                        'image' => '/theme/assets/hovi/about-core-3.jpg',
                        'image_alt' => 'Sáng tạo',
                    ],
                    [
                        'title' => 'Đam mê',
                        'description' => 'Đội ngũ HOVI đặt trọn tâm huyết vào từng dự án để mỗi khu vườn đều có chiều sâu cảm xúc riêng.',
                        'image' => '/theme/assets/hovi/about-core-4.jpg',
                        'image_alt' => 'Đam mê',
                    ],
                    [
                        'title' => 'Chuyên nghiệp',
                        'description' => 'Quy trình làm việc rõ ràng, vật tư minh bạch và thi công theo tiêu chuẩn kỹ thuật đã cam kết.',
                        'image' => '/theme/assets/hovi/about-core-5.jpg',
                        'image_alt' => 'Chuyên nghiệp',
                    ],
                    [
                        'title' => 'Bền vững',
                        'description' => 'Ưu tiên giải pháp xanh, tối ưu bảo trì và bảo hành dài hạn để cảnh quan giữ được vẻ đẹp lâu dài.',
                        'image' => '/theme/assets/hovi/about-core-6.jpg',
                        'image_alt' => 'Bền vững',
                    ],
                ],
            ],
            'manifesto' => [
                'enabled' => true,
                'heading' => 'Cam kết thương hiệu',
                'items' => [
                    [
                        'quote' => '“Mỗi bản vẽ là một câu chuyện riêng, phù hợp phong cách và phong thủy của gia chủ.”',
                        'image' => '/theme/assets/hovi/about-manifesto-1.jpg',
                        'image_alt' => 'Cam kết 1',
                    ],
                    [
                        'quote' => '“Thiết kế trực quan bằng 3D để khách hàng nhìn thấy rõ không gian trước khi thi công.”',
                        'image' => '/theme/assets/hovi/about-manifesto-2.jpg',
                        'image_alt' => 'Cam kết 2',
                    ],
                    [
                        'quote' => '“Thi công đúng tiến độ, đúng chủng loại vật liệu và đồng hành bảo hành dài hạn.”',
                        'image' => '/theme/assets/hovi/about-manifesto-3.jpg',
                        'image_alt' => 'Cam kết 3',
                    ],
                ],
            ],
            'advantages' => [
                'enabled' => true,
                'title' => 'Lợi thế cạnh tranh',
                'image' => '/theme/assets/hovi/about-advantages.png',
                'image_alt' => 'Lợi thế cạnh tranh HOVI Việt Nam',
                'items' => [
                    [
                        'title' => 'Cá nhân hóa thiết kế',
                        'description' => 'mỗi phương án được xây dựng theo gu sống, ngân sách và hiện trạng thực tế.',
                    ],
                    [
                        'title' => 'Ứng dụng công nghệ',
                        'description' => 'mô phỏng 3D trực quan giúp chốt phương án nhanh và giảm sai lệch thi công.',
                    ],
                    [
                        'title' => 'Thi công chuẩn kỹ thuật',
                        'description' => 'quy trình chặt chẽ, kiểm soát vật tư và chất lượng theo từng hạng mục.',
                    ],
                    [
                        'title' => 'Đúng tiến độ',
                        'description' => 'phối hợp đồng bộ giữa thiết kế, cung ứng và thi công để bàn giao đúng cam kết.',
                    ],
                    [
                        'title' => 'Hậu mãi rõ ràng',
                        'description' => 'bảo dưỡng định kỳ và bảo hành dài hạn để cảnh quan luôn đẹp theo thời gian.',
                    ],
                ],
            ],
            'ceo' => [
                'enabled' => true,
                'eyebrow' => 'Thông điệp từ HOVI Việt Nam',
                'title' => 'Đội ngũ sáng lập',
                'description_1' => '“Trong nhịp sống hiện đại, sân vườn không chỉ là khoảng xanh quanh nhà mà còn là nơi tái tạo năng lượng và thể hiện phong cách sống của gia chủ. Đó là lý do HOVI theo đuổi các giải pháp cảnh quan vừa đẹp, vừa bền vững.”',
                'description_2' => 'Chúng tôi trân trọng mọi cơ hội hợp tác và tin rằng sự đồng hành giữa HOVI với đối tác, khách hàng sẽ tạo nên những công trình giàu cảm hứng, nâng cao giá trị thương hiệu và chất lượng sống.',
                'image' => '/theme/assets/hovi/about-ceo.png',
                'image_alt' => 'Đội ngũ sáng lập HOVI Việt Nam',
            ],
            'capacity' => [
                'enabled' => true,
                'heading' => 'Hồ sơ năng lực',
                'lead' => 'HOVI cung cấp dịch vụ thiết kế - thi công sân vườn biệt thự, tiểu cảnh hồ cá koi, trang trí ban công/penthouse và các gói decor sự kiện, set up hoa theo yêu cầu cho không gian cao cấp.',
                'stats' => [
                    ['value' => 'Since 2021', 'label' => 'Khởi tạo thương hiệu'],
                    ['value' => '05', 'label' => 'Bước quy trình chuẩn'],
                    ['value' => '04', 'label' => 'Nhóm sản phẩm mẫu'],
                    ['value' => 'Dài hạn', 'label' => 'Bảo hành & chăm sóc'],
                ],
                'action_1_label' => 'NHẬN HỒ SƠ NĂNG LỰC',
                'action_1_url' => 'mailto:hovivietnam99@gmail.com',
                'action_2_label' => 'ĐẶT LỊCH TƯ VẤN',
                'action_2_url' => '/lien-he',
            ],
        ];
    }

    public static function homeContentDefaults(): array
    {
        return [
            'hero' => [
                'background_image' => '/theme/assets/hero.jpg',
                'scroll_target' => '#projects-1',
            ],
            'profile' => [
                'background_image' => '/theme/assets/hovi/gallery/hovi-060.jpg',
                'eyebrow' => 'Hồ sơ năng lực',
                'title' => 'Không gian được thiết kế như một tuyên ngôn sống',
                'description_1' => 'Thấu hiểu rằng mỗi công trình là hiện thân của những ước mơ và cá tính riêng của khách hàng, HOVI VIỆT NAM luôn đặt sự tận tâm với nghề và nỗ lực sáng tạo lên hàng đầu để mang tới những không gian kết hợp hài hòa giữa tầm nhìn nghệ thuật của kiến trúc sư và nhu cầu sử dụng thực tế.',
                'description_2' => 'Với HOVI VIỆT NAM, hạnh phúc lớn nhất là được đồng hành cùng khách hàng trên hành trình kiến tạo tổ ấm. Chính sự tin tưởng đó là động lực để đội ngũ không ngừng hoàn thiện, phát triển và nâng chuẩn dịch vụ.',
                'button_label' => 'Catalogue HOVI VIỆT NAM',
                'button_url' => '#footer',
                'slider_images' => [
                    '/theme/assets/hovi/gallery/hovi-014.jpg',
                    '/theme/assets/hovi/gallery/hovi-015.jpg',
                    '/theme/assets/hovi/gallery/hovi-016.jpg',
                    '/theme/assets/hovi/gallery/hovi-017.jpg',
                    '/theme/assets/hovi/gallery/hovi-018.jpg',
                    '/theme/assets/hovi/gallery/hovi-019.jpg',
                ],
            ],
            'about' => [
                'title' => 'Về HOVI VIỆT NAM',
                'description' => 'Kể từ khi thành lập vào năm 2015, chúng tôi không ngừng khẳng định vị thế là một trong những đơn vị mang đến cho khách hàng giải pháp nâng tầm không gian toàn diện hàng đầu, từ khâu thiết kế và thi công kiến trúc đến sản xuất và lắp đặt nội thất. Trong một thị trường biệt thự đầy cạnh tranh, HOVI VIỆT NAM được tin - yêu không chỉ bởi mức độ xuất sắc của sản phẩm mà còn bởi dịch vụ tư vấn và đồng hành cùng khách hàng sau khi tiếp nhận yêu cầu.',
                'stats' => [
                    ['value' => '10+', 'label' => 'Năm kinh nghiệm'],
                    ['value' => '80', 'label' => 'Nhân sự'],
                    ['value' => '100', 'label' => 'Khách hàng'],
                    ['value' => '100+', 'label' => 'Dự án thi công'],
                ],
                'cta_label' => 'XEM THÊM',
                'cta_url' => '/about-us',
                'team_image' => '/theme/assets/hovi/gallery/hovi-060.jpg',
            ],
            'footer_cta' => [
                'consult' => [
                    'title' => 'ĐẶT LỊCH TƯ VẤN',
                    'button_label' => 'Đặt lịch',
                    'button_url' => '/dang-ky-dich-vu',
                    'background_image' => '/theme/assets/hovi/gallery/hovi-001.jpg',
                ],
                'partner' => [
                    'title' => 'TRỞ THÀNH ĐỐI TÁC HOVI VIỆT NAM',
                    'button_label' => 'Tham gia',
                    'button_url' => '/lien-he',
                    'background_image' => '/theme/assets/hovi/gallery/hovi-055.jpg',
                ],
            ],
            'project_highlights' => [
                'mode' => 'auto',
                'items' => [],
                'auto_excluded_detail_page_ids' => [],
            ],
        ];
    }

    public static function allAsArray(): array
    {
        try {
            $stored = static::query()->pluck('value', 'key')->toArray();
        } catch (\Throwable $exception) {
            $stored = [];
        }

        return array_merge(static::defaults(), $stored);
    }

    public static function aboutContent(): array
    {
        $defaults = static::aboutContentDefaults();

        try {
            $raw = static::query()
                ->where('key', static::ABOUT_CONTENT_KEY)
                ->value('value');
        } catch (\Throwable $exception) {
            $raw = null;
        }

        if (empty($raw) || !is_string($raw)) {
            return $defaults;
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            return $defaults;
        }

        return static::mergeWithoutNull($defaults, $decoded);
    }

    public static function setAboutContent(array $content): void
    {
        static::query()->updateOrCreate(
            ['key' => static::ABOUT_CONTENT_KEY],
            [
                'value' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        );
    }

    public static function homeContent(): array
    {
        $defaults = static::homeContentDefaults();

        try {
            $raw = static::query()
                ->where('key', static::HOME_CONTENT_KEY)
                ->value('value');
        } catch (\Throwable $exception) {
            $raw = null;
        }

        if (empty($raw) || !is_string($raw)) {
            return $defaults;
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            return $defaults;
        }

        $merged = static::mergeWithoutNull($defaults, $decoded);

        $sliderImages = data_get($decoded, 'profile.slider_images');
        if (is_array($sliderImages)) {
            $merged['profile']['slider_images'] = array_values(array_filter($sliderImages, function ($value) {
                return is_string($value) && trim($value) !== '';
            }));
        }

        $aboutStats = data_get($decoded, 'about.stats');
        if (is_array($aboutStats)) {
            $merged['about']['stats'] = array_values(array_filter($aboutStats, function ($value) {
                return is_array($value);
            }));
        }

        $projectHighlightsMode = data_get($decoded, 'project_highlights.mode');
        if (in_array($projectHighlightsMode, ['auto', 'manual'], true)) {
            $merged['project_highlights']['mode'] = $projectHighlightsMode;
        }

        $projectHighlightItems = data_get($decoded, 'project_highlights.items');
        if (is_array($projectHighlightItems)) {
            $merged['project_highlights']['items'] = array_values(array_filter($projectHighlightItems, function ($value) {
                return is_array($value);
            }));
        }

        $autoExcludedDetailPageIds = data_get($decoded, 'project_highlights.auto_excluded_detail_page_ids');
        if (is_array($autoExcludedDetailPageIds)) {
            $merged['project_highlights']['auto_excluded_detail_page_ids'] = array_values(array_unique(array_filter(array_map(function ($value) {
                if (is_string($value)) {
                    $value = trim($value);
                }

                return is_numeric($value) ? (int) $value : null;
            }, $autoExcludedDetailPageIds), function ($value) {
                return is_int($value) && $value > 0;
            })));
        }

        return $merged;
    }

    public static function setHomeContent(array $content): void
    {
        static::query()->updateOrCreate(
            ['key' => static::HOME_CONTENT_KEY],
            [
                'value' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        );
    }

    private static function mergeWithoutNull(array $defaults, array $overrides): array
    {
        $result = $defaults;

        foreach ($overrides as $key => $overrideValue) {
            if (!array_key_exists($key, $defaults)) {
                if (!is_null($overrideValue)) {
                    $result[$key] = $overrideValue;
                }

                continue;
            }

            $defaultValue = $defaults[$key];

            if (is_array($defaultValue) && is_array($overrideValue)) {
                $result[$key] = static::mergeWithoutNull($defaultValue, $overrideValue);
                continue;
            }

            if (!is_null($overrideValue)) {
                $result[$key] = $overrideValue;
            }
        }

        return $result;
    }

    public static function setMany(array $settings): void
    {
        foreach ($settings as $key => $value) {
            static::query()->updateOrCreate(
                ['key' => (string) $key],
                ['value' => is_null($value) ? null : (string) $value]
            );
        }
    }
}
