<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectMockupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mockups = [
            [
                'project' => [
                    'name' => 'Thiết kế biệt thự Vinhomes Ocean Park',
                    'slug' => 'thiet-ke-biet-thu-vinhomes-ocean-park',
                    'short_description' => 'Tổng hợp các dự án biệt thự do HOVI triển khai tại Ocean Park với hình ảnh thực tế, video và bài viết tư vấn.',
                    'intro' => 'Dự án tập trung vào giải pháp cảnh quan cao cấp, tối ưu không gian xanh và trải nghiệm sống cho từng gia đình.',
                    'cover_image' => '/theme/assets/hovi/gallery/hovi-024.jpg',
                    'seo_title' => 'Thiết kế biệt thự Vinhomes Ocean Park | Dự án HOVI Việt Nam',
                    'seo_description' => 'Dự án thực tế tại Vinhomes Ocean Park: trang chi tiết, blog chuyên sâu và video thực chiến từ HOVI Việt Nam.',
                    'sort_order' => 1,
                    'is_published' => true,
                ],
                'details' => [
                    [
                        'title' => 'Biệt thự đơn lập M07-L14 ĐTM Dương Nội',
                        'slug' => 'biet-thu-don-lap-m07-l14-dtm-duong-noi',
                        'summary' => 'Hồ sơ thiết kế - thi công cảnh quan cho biệt thự đơn lập, tối ưu công năng và thẩm mỹ.',
                        'content' => 'Thiết kế chú trọng bố cục xanh đa lớp, lối dạo kết nối mềm và khu vực thư giãn ngoài trời cho gia đình đa thế hệ.',
                        'thumbnail_image' => '/theme/assets/hovi/gallery/hovi-035.jpg',
                        'gallery_images' => [
                            '/theme/assets/hovi/gallery/hovi-035.jpg',
                            '/theme/assets/hovi/gallery/hovi-036.jpg',
                            '/theme/assets/hovi/gallery/hovi-037.jpg',
                            '/theme/assets/hovi/gallery/hovi-038.jpg',
                            '/theme/assets/hovi/gallery/hovi-039.jpg',
                            '/theme/assets/hovi/gallery/hovi-040.jpg',
                        ],
                        'sort_order' => 1,
                        'is_published' => true,
                    ],
                    [
                        'title' => 'Biệt thự đơn lập NT17-10 Vinhomes Ocean Park',
                        'slug' => 'biet-thu-don-lap-nt17-10-vinhomes-ocean-park',
                        'summary' => 'Phương án cảnh quan sân vườn tích hợp hệ đèn và mảng xanh theo phong cách resort.',
                        'content' => 'Hệ thống cây tầng cao - trung - thấp được phối theo nhịp sinh trưởng để cảnh quan bền đẹp quanh năm.',
                        'thumbnail_image' => '/theme/assets/hovi/gallery/hovi-041.jpg',
                        'gallery_images' => [
                            '/theme/assets/hovi/gallery/hovi-041.jpg',
                            '/theme/assets/hovi/gallery/hovi-042.jpg',
                            '/theme/assets/hovi/gallery/hovi-043.jpg',
                            '/theme/assets/hovi/gallery/hovi-044.jpg',
                        ],
                        'sort_order' => 2,
                        'is_published' => true,
                    ],
                ],
                'blogs' => [
                    [
                        'title' => 'Top 10 mẫu sân vườn biệt thự tại Ocean Park',
                        'slug' => 'top-10-mau-san-vuon-biet-thu-ocean-park',
                        'excerpt' => 'Gợi ý các mẫu sân vườn đẹp theo diện tích và phong cách kiến trúc tại Vinhomes Ocean Park.',
                        'content' => 'Bài viết phân tích bố cục sân vườn, nhóm cây phù hợp và kinh nghiệm triển khai thực tế.',
                        'thumbnail_image' => '/theme/assets/hovi/gallery/hovi-036.jpg',
                        'target_url' => '/biet-thu-don-lap-m07-l14-dtm-duong-noi',
                        'published_at' => now()->subDays(7),
                        'sort_order' => 1,
                        'is_published' => true,
                    ],
                    [
                        'title' => 'Checklist thi công cảnh quan đúng kỹ thuật',
                        'slug' => 'checklist-thi-cong-canh-quan-dung-ky-thuat',
                        'excerpt' => 'Những điểm cần kiểm soát khi thi công để đảm bảo tiến độ và chất lượng bàn giao.',
                        'content' => 'Từ xử lý nền, chống thấm, tưới tự động đến kế hoạch bảo trì sau bàn giao đều cần tiêu chuẩn rõ ràng.',
                        'thumbnail_image' => '/theme/assets/hovi/gallery/hovi-037.jpg',
                        'target_url' => '/thiet-ke-biet-thu-vinhomes-ocean-park',
                        'published_at' => now()->subDays(3),
                        'sort_order' => 2,
                        'is_published' => true,
                    ],
                ],
                'videos' => [
                    [
                        'title' => 'Review thực tế công trình Ocean Park - Tập 1',
                        'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                        'thumbnail_image' => '/theme/assets/hovi/gallery/hovi-033.jpg',
                        'description' => 'Walkthrough hiện trường và giải pháp bố trí cây - đèn cho không gian sân vườn.',
                        'sort_order' => 1,
                        'is_published' => true,
                    ],
                    [
                        'title' => 'Quy trình bảo vệ phương án 3D với chủ nhà',
                        'video_url' => 'https://www.youtube.com/watch?v=oHg5SJYRHA0',
                        'thumbnail_image' => '/theme/assets/hovi/gallery/hovi-034.jpg',
                        'description' => 'Mô phỏng cách đội ngũ HOVI chốt phương án và kiểm soát sai lệch trước thi công.',
                        'sort_order' => 2,
                        'is_published' => true,
                    ],
                ],
            ],
            [
                'project' => [
                    'name' => 'Thiết kế sân vườn biệt thự Starlake (Mockup)',
                    'slug' => 'thiet-ke-san-vuon-biet-thu-starlake-mockup',
                    'short_description' => 'Dự án mẫu để đội content luyện thao tác quản trị: thêm trang chi tiết, blog và video.',
                    'intro' => 'Dùng cho mục đích demo giao diện quản trị và quy trình cập nhật nội dung theo chuẩn SEO.',
                    'cover_image' => '/theme/assets/hovi/gallery/hovi-025.jpg',
                    'seo_title' => 'Thiết kế sân vườn biệt thự Starlake (Mockup) | HOVI Việt Nam',
                    'seo_description' => 'Dự án mockup phục vụ huấn luyện thao tác quản trị nội dung dự án trong CMS.',
                    'sort_order' => 2,
                    'is_published' => true,
                ],
                'details' => [
                    [
                        'title' => 'Biệt thự song lập khu H Starlake',
                        'slug' => 'biet-thu-song-lap-khu-h-starlake-mockup',
                        'summary' => 'Mẫu trang chi tiết với gallery ảnh để kiểm thử flow nhập liệu.',
                        'content' => 'Dữ liệu mockup: bạn có thể chỉnh sửa tự do để làm mẫu đào tạo nội bộ.',
                        'thumbnail_image' => '/theme/assets/hovi/gallery/hovi-026.jpg',
                        'gallery_images' => [
                            '/theme/assets/hovi/gallery/hovi-026.jpg',
                            '/theme/assets/hovi/gallery/hovi-027.jpg',
                            '/theme/assets/hovi/gallery/hovi-028.jpg',
                        ],
                        'sort_order' => 1,
                        'is_published' => true,
                    ],
                ],
                'blogs' => [
                    [
                        'title' => 'Mockup blog: 5 lỗi thường gặp khi làm sân vườn',
                        'slug' => 'mockup-blog-5-loi-thuong-gap-khi-lam-san-vuon',
                        'excerpt' => 'Bài mẫu để test các trường tiêu đề, mô tả và link đích.',
                        'content' => 'Nội dung mẫu có thể thay bằng bài thật bất kỳ lúc nào.',
                        'thumbnail_image' => '/theme/assets/hovi/gallery/hovi-029.jpg',
                        'target_url' => '/thiet-ke-san-vuon-biet-thu-starlake-mockup',
                        'published_at' => now()->subDay(),
                        'sort_order' => 1,
                        'is_published' => true,
                    ],
                ],
                'videos' => [
                    [
                        'title' => 'Mockup video: nhật ký thi công ngày 1',
                        'video_url' => 'https://www.youtube.com/watch?v=FTQbiNvZqaY',
                        'thumbnail_image' => '/theme/assets/hovi/gallery/hovi-030.jpg',
                        'description' => 'Video mẫu để kiểm tra hiển thị khối video thực tế.',
                        'sort_order' => 1,
                        'is_published' => true,
                    ],
                ],
            ],
        ];

        foreach ($mockups as $item) {
            $project = Project::query()->updateOrCreate(
                ['slug' => $item['project']['slug']],
                $item['project']
            );

            foreach ($item['details'] as $detail) {
                $project->detailPages()->updateOrCreate(
                    ['slug' => $detail['slug']],
                    $detail
                );
            }

            foreach ($item['blogs'] as $blog) {
                $project->blogs()->updateOrCreate(
                    ['slug' => $blog['slug']],
                    $blog
                );
            }

            foreach ($item['videos'] as $video) {
                $project->videos()->updateOrCreate(
                    ['title' => $video['title']],
                    $video
                );
            }
        }
    }
}
