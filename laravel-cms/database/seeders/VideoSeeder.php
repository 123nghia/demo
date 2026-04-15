<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectVideo;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projectIdsBySlug = Project::query()
            ->pluck('id', 'slug')
            ->toArray();

        $fallbackProjectId = (int) (reset($projectIdsBySlug) ?: 0);
        if ($fallbackProjectId <= 0) {
            return;
        }

        ProjectVideo::query()
            ->whereIn('title', [
                'Review thực tế công trình Ocean Park - Tập 1',
                'Quy trình bảo vệ phương án 3D với chủ nhà',
                'Mockup video: nhật ký thi công ngày 1',
            ])
            ->delete();

        $videos = [
            [
                'project_slug' => 'thiet-ke-biet-thu-vinhomes-ocean-park',
                'title' => 'Tour nội thất biệt thự Green Villas GV11-02 | Modern Luxury',
                'slug' => 'tour-noi-that-biet-thu-green-villas-gv11-02-modern-luxury',
                'display_zone' => 'all',
                'video_url' => 'https://www.youtube.com/watch?v=M7lc1UVf-VE',
                'thumbnail_image' => 'https://rhinelux.com/wp-content/uploads/2025/06/GV11-02.jpg',
                'description' => 'Toàn cảnh không gian khách - bếp - thông tầng của dự án GV11-02 theo tinh thần Modern Luxury.',
                'content' => <<<HTML
<p>Video ghi lại các lớp không gian chính của biệt thự GV11-02: phòng khách, khu bếp và trục thông tầng.</p>
<p>Tập trung vào nhịp chuyển vật liệu đá - gỗ - kim loại cùng cách xử lý ánh sáng tạo cảm giác sang trọng nhưng vẫn ấm áp.</p>
HTML,
                'seo_title' => 'Tour nội thất biệt thự Green Villas GV11-02',
                'seo_description' => 'Video tour nội thất biệt thự GV11-02 tại Green Villas theo phong cách Modern Luxury.',
                'published_at' => Carbon::create(2025, 6, 20, 11, 0, 0, 'Asia/Ho_Chi_Minh'),
                'sort_order' => 1,
                'is_published' => true,
            ],
            [
                'project_slug' => 'thiet-ke-biet-thu-vinhomes-ocean-park',
                'title' => 'Video thực tế GV7-06: không gian nâu gỗ cổ điển sang trọng',
                'slug' => 'video-thuc-te-gv7-06-khong-gian-nau-go-co-dien-sang-trong',
                'display_zone' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'thumbnail_image' => 'https://rhinelux.com/wp-content/uploads/2025/06/GV7-06-2.jpg',
                'description' => 'Hành trình hoàn thiện dự án GV7-06 với chất liệu gỗ trầm, ánh sáng vàng và chi tiết nhấn tinh tế.',
                'content' => <<<HTML
<p>Trong video này, đội ngũ chia sẻ quá trình hoàn thiện vật liệu và kiểm soát tỷ lệ nội thất cho không gian nâu gỗ.</p>
<p>Các điểm nhấn về đèn và phụ kiện được tiết chế để giữ tổng thể thanh lịch, không nặng nề.</p>
HTML,
                'seo_title' => 'Video thực tế dự án GV7-06 Green Villas',
                'seo_description' => 'Cập nhật video công trình GV7-06 với phong cách nâu gỗ cổ điển sang trọng.',
                'published_at' => Carbon::create(2025, 6, 18, 9, 30, 0, 'Asia/Ho_Chi_Minh'),
                'sort_order' => 2,
                'is_published' => true,
            ],
            [
                'project_slug' => 'thiet-ke-biet-thu-vinhomes-ocean-park',
                'title' => 'Behind the scenes: hiệu chỉnh 3D Louis City Hoàng Mai',
                'slug' => 'behind-the-scenes-hieu-chinh-3d-louis-city-hoang-mai',
                'display_zone' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=oHg5SJYRHA0',
                'thumbnail_image' => 'https://rhinelux.com/wp-content/uploads/2025/07/ARCHITECTURE-9.jpg',
                'description' => 'Quy trình rà soát công năng, vật liệu và điểm nhìn trước khi chốt phương án thi công.',
                'content' => <<<HTML
<p>Video tập trung vào giai đoạn hiệu chỉnh 3D: cân lại tỷ lệ, vật liệu và chi tiết kỹ thuật trước thi công.</p>
<p>Đây là bước giúp giảm rủi ro phát sinh và đảm bảo trải nghiệm sử dụng thực tế của gia chủ.</p>
HTML,
                'seo_title' => 'Behind the scenes hiệu chỉnh 3D biệt thự Louis City',
                'seo_description' => 'Khám phá quy trình hiệu chỉnh 3D cho biệt thự Louis City trước khi triển khai thi công.',
                'published_at' => Carbon::create(2025, 7, 22, 10, 0, 0, 'Asia/Ho_Chi_Minh'),
                'sort_order' => 3,
                'is_published' => true,
            ],
            [
                'project_slug' => 'thiet-ke-san-vuon-biet-thu-starlake-mockup',
                'title' => 'Nhật ký công trình K5 Starlake: từ bản vẽ tới không gian thật',
                'slug' => 'nhat-ky-cong-trinh-k5-starlake-tu-ban-ve-toi-khong-gian-that',
                'display_zone' => 'project',
                'video_url' => 'https://www.youtube.com/watch?v=FTQbiNvZqaY',
                'thumbnail_image' => 'https://rhinelux.com/wp-content/uploads/2024/10/29.jpg',
                'description' => 'Theo dõi tiến độ hoàn thiện công trình K5 Starlake với các mốc thi công quan trọng.',
                'content' => <<<HTML
<p>Tư liệu thực địa ghi lại tiến độ triển khai theo từng hạng mục chính: thô, hoàn thiện và bàn giao.</p>
<p>Video nhấn vào việc kiểm soát chi tiết để đảm bảo đúng bản thiết kế ban đầu.</p>
HTML,
                'seo_title' => 'Nhật ký công trình K5 Starlake',
                'seo_description' => 'Video hậu trường thi công K5 Starlake từ bản vẽ đến không gian sống thực tế.',
                'published_at' => Carbon::create(2024, 10, 23, 9, 0, 0, 'Asia/Ho_Chi_Minh'),
                'sort_order' => 4,
                'is_published' => true,
            ],
            [
                'project_slug' => 'thiet-ke-biet-thu-vinhomes-ocean-park',
                'title' => 'Walkthrough DD2-02 Ocean Park: ánh sáng và vật liệu',
                'slug' => 'walkthrough-dd2-02-ocean-park-anh-sang-va-vat-lieu',
                'display_zone' => 'project',
                'video_url' => 'https://www.youtube.com/watch?v=9bZkp7q19f0',
                'thumbnail_image' => 'https://rhinelux.com/wp-content/uploads/2024/12/03-KT.jpg',
                'description' => 'Review công trình DD2-02 với góc nhìn về tổ chức mặt bằng và xử lý ánh sáng nội thất.',
                'content' => <<<HTML
<p>Video cho thấy cách bố cục không gian và lựa chọn vật liệu giúp công trình vừa sang trọng vừa tiện dụng.</p>
<p>Các mảng chính được xử lý đồng bộ nhằm tạo cảm giác liền mạch giữa kiến trúc và nội thất.</p>
HTML,
                'seo_title' => 'Walkthrough DD2-02 Vinhomes Ocean Park',
                'seo_description' => 'Video review dự án DD2-02 tại Ocean Park với điểm nhấn về ánh sáng và vật liệu.',
                'published_at' => Carbon::create(2024, 12, 15, 8, 45, 0, 'Asia/Ho_Chi_Minh'),
                'sort_order' => 5,
                'is_published' => true,
            ],
            [
                'project_slug' => 'thiet-ke-biet-thu-vinhomes-ocean-park',
                'title' => 'GV12-09 Green Villas: nhịp chuyển mềm giữa đá và gỗ',
                'slug' => 'gv12-09-green-villas-nhip-chuyen-mem-giua-da-va-go',
                'display_zone' => 'all',
                'video_url' => 'https://www.youtube.com/watch?v=kXYiU_JCYtU',
                'thumbnail_image' => 'https://rhinelux.com/wp-content/uploads/2025/06/GV12-09-3.jpg',
                'description' => 'Video cận cảnh các lớp vật liệu và ánh sáng của dự án GV12-09.',
                'content' => <<<HTML
<p>Nội dung tập trung vào cách phối vật liệu trung tính để giữ sự nhẹ nhàng và chiều sâu thị giác.</p>
<p>Giải pháp chiếu sáng được chia lớp, tăng trải nghiệm thư giãn cho khu khách - bếp.</p>
HTML,
                'seo_title' => 'Video GV12-09 Green Villas',
                'seo_description' => 'Khám phá video dự án GV12-09 với nhịp chuyển vật liệu tinh tế.',
                'published_at' => Carbon::create(2025, 6, 13, 9, 20, 0, 'Asia/Ho_Chi_Minh'),
                'sort_order' => 6,
                'is_published' => true,
            ],
            [
                'project_slug' => 'thiet-ke-biet-thu-vinhomes-ocean-park',
                'title' => 'GV2-08A Green Villas: tinh thần Á Đông hiện đại',
                'slug' => 'gv2-08a-green-villas-tinh-than-a-dong-hien-dai',
                'display_zone' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=3JZ_D3ELwOQ',
                'thumbnail_image' => 'https://rhinelux.com/wp-content/uploads/2025/06/GV2-08.jpg',
                'description' => 'Giới thiệu ngôn ngữ thiết kế Á Đông hiện đại trong không gian biệt thự GV2-08A.',
                'content' => <<<HTML
<p>Video thể hiện cách kết hợp chi tiết gỗ và bố cục đăng đối với nền sáng hiện đại.</p>
<p>Tổng thể mang đến cảm giác trang nhã, gần gũi và phù hợp sinh hoạt gia đình.</p>
HTML,
                'seo_title' => 'Video GV2-08A Green Villas',
                'seo_description' => 'Video dự án GV2-08A với phong cách Á Đông hiện đại và bố cục tinh gọn.',
                'published_at' => Carbon::create(2025, 6, 14, 9, 10, 0, 'Asia/Ho_Chi_Minh'),
                'sort_order' => 7,
                'is_published' => true,
            ],
            [
                'project_slug' => 'thiet-ke-san-vuon-biet-thu-starlake-mockup',
                'title' => 'Mockup Starlake: kiểm thử luồng quản trị video trong CMS',
                'slug' => 'mockup-starlake-kiem-thu-luong-quan-tri-video-trong-cms',
                'display_zone' => 'project',
                'video_url' => 'https://www.youtube.com/watch?v=L_jWHffIx5E',
                'thumbnail_image' => '/theme/assets/hovi/gallery/hovi-030.jpg',
                'description' => 'Video mẫu phục vụ đào tạo thao tác quản trị nội dung video trong hệ thống.',
                'content' => <<<HTML
<p>Video mockup giúp đội vận hành kiểm tra toàn bộ luồng nhập liệu: tạo mới, chỉnh sửa, xuất bản và hiển thị frontend.</p>
<p>Nội dung có thể thay thế linh hoạt theo nhu cầu đào tạo nội bộ.</p>
HTML,
                'seo_title' => 'Mockup Starlake: kiểm thử quản trị video',
                'seo_description' => 'Video mẫu phục vụ kiểm thử và đào tạo quy trình quản trị video trong CMS.',
                'published_at' => Carbon::create(2026, 1, 10, 8, 30, 0, 'Asia/Ho_Chi_Minh'),
                'sort_order' => 8,
                'is_published' => true,
            ],
            [
                'project_slug' => 'thiet-ke-biet-thu-vinhomes-ocean-park',
                'title' => 'Thi công hoàn thiện phòng khách biệt thự: checklist hiện trường',
                'slug' => 'thi-cong-hoan-thien-phong-khach-biet-thu-checklist-hien-truong',
                'display_zone' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=fLexgOxsZu0',
                'thumbnail_image' => 'https://rhinelux.com/wp-content/uploads/2025/06/DSC09077-copy.jpg',
                'description' => 'Checklist thực tế để kiểm soát chất lượng thi công khu khách theo đúng hồ sơ thiết kế.',
                'content' => <<<HTML
<p>Video chia sẻ các điểm cần kiểm tra khi hoàn thiện phòng khách: cao độ, khe mạch, ánh sáng và bề mặt vật liệu.</p>
<p>Áp dụng checklist giúp giảm lỗi phát sinh trước giai đoạn bàn giao.</p>
HTML,
                'seo_title' => 'Checklist thi công hoàn thiện phòng khách biệt thự',
                'seo_description' => 'Video checklist hiện trường cho hạng mục phòng khách biệt thự.',
                'published_at' => Carbon::create(2025, 6, 16, 10, 10, 0, 'Asia/Ho_Chi_Minh'),
                'sort_order' => 9,
                'is_published' => true,
            ],
            [
                'project_slug' => 'thiet-ke-biet-thu-vinhomes-ocean-park',
                'title' => 'Layout Vinhomes Cổ Loa: giải thích tư duy bố trí không gian',
                'slug' => 'layout-vinhomes-co-loa-giai-thich-tu-duy-bo-tri-khong-gian',
                'display_zone' => 'all',
                'video_url' => 'https://www.youtube.com/watch?v=e-ORhEE9VVg',
                'thumbnail_image' => 'https://rhinelux.com/wp-content/uploads/2026/03/CAH_TANG-1-3.jpg',
                'description' => 'Video chia sẻ tư duy tổ chức layout cho biệt thự song lập và liền kề tại Vinhomes Cổ Loa.',
                'content' => <<<HTML
<p>Nội dung tập trung vào cách tổ chức giao thông nội bộ, điểm nhìn và phân lớp công năng theo nhu cầu sử dụng thực tế.</p>
<p>Đây là tài liệu tham khảo hữu ích cho chủ nhà ở giai đoạn chuẩn bị thiết kế nội thất.</p>
HTML,
                'seo_title' => 'Layout Vinhomes Cổ Loa: tư duy bố trí không gian',
                'seo_description' => 'Video phân tích tư duy bố trí không gian cho các mẫu biệt thự tại Vinhomes Cổ Loa.',
                'published_at' => Carbon::create(2026, 3, 16, 9, 5, 0, 'Asia/Ho_Chi_Minh'),
                'sort_order' => 10,
                'is_published' => true,
            ],
        ];

        foreach ($videos as $video) {
            $projectSlug = $video['project_slug'];
            unset($video['project_slug']);

            $video['project_id'] = is_string($projectSlug) && isset($projectIdsBySlug[$projectSlug])
                ? (int) $projectIdsBySlug[$projectSlug]
                : $fallbackProjectId;

            ProjectVideo::query()->updateOrCreate(
                ['slug' => $video['slug']],
                $video
            );
        }
    }
}
