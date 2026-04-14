<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $blogs = [
            [
                'title' => '5 nguyên tắc bố trí sân vườn biệt thự luôn đẹp sau 3 năm',
                'slug' => '5-nguyen-tac-bo-tri-san-vuon-biet-thu',
                'excerpt' => 'Từ phân tầng cây xanh, vật liệu lối dạo đến hệ tưới tự động, đây là 5 nguyên tắc giúp cảnh quan bền đẹp theo thời gian.',
                'content' => 'Một khu vườn đẹp không chỉ ở thời điểm bàn giao mà cần giữ được cấu trúc ổn định nhiều năm sau. HOVI khuyến nghị 5 nguyên tắc: (1) phân tầng cao-trung-thấp rõ ràng, (2) tối ưu thoát nước nền, (3) chọn cây theo cường độ nắng thực tế, (4) tích hợp hệ tưới tự động theo khu vực, (5) lập lịch bảo dưỡng định kỳ theo mùa.',
                'thumbnail_image' => '/theme/assets/hovi/gallery/hovi-036.jpg',
                'seo_title' => '5 nguyên tắc bố trí sân vườn biệt thự bền đẹp | Blog HOVI Việt Nam',
                'seo_description' => '5 nguyên tắc thiết kế cảnh quan giúp sân vườn biệt thự đẹp và bền lâu: phân tầng cây, thoát nước, vật liệu và bảo dưỡng đúng chuẩn.',
                'published_at' => now()->subDays(6),
                'sort_order' => 1,
                'is_published' => true,
            ],
            [
                'title' => 'Checklist thi công cảnh quan tránh phát sinh chi phí',
                'slug' => 'checklist-thi-cong-canh-quan-tranh-phat-sinh',
                'excerpt' => 'Danh sách kiểm tra quan trọng trước và trong thi công để hạn chế lỗi kỹ thuật và kiểm soát ngân sách hiệu quả.',
                'content' => 'Trước khi thi công, chủ đầu tư nên khóa rõ phạm vi công việc, thông số vật liệu, biện pháp chống thấm và tiến độ từng hạng mục. Trong quá trình triển khai, cần có nhật ký công trường, nghiệm thu từng lớp nền và kiểm tra chủng loại cây theo đúng hợp đồng.',
                'thumbnail_image' => '/theme/assets/hovi/gallery/hovi-037.jpg',
                'seo_title' => 'Checklist thi công cảnh quan hạn chế phát sinh chi phí | Blog HOVI',
                'seo_description' => 'Các đầu mục bắt buộc trong checklist thi công cảnh quan để kiểm soát chất lượng và hạn chế phát sinh ngân sách.',
                'published_at' => now()->subDays(3),
                'sort_order' => 2,
                'is_published' => true,
            ],
            [
                'title' => 'Xu hướng sân vườn biệt thự 2026: tối giản nhưng giàu trải nghiệm',
                'slug' => 'xu-huong-san-vuon-biet-thu-2026',
                'excerpt' => 'Thiết kế tối giản đang lên ngôi, nhưng điểm nhấn nằm ở trải nghiệm sống: ánh sáng, âm thanh nước và không gian thư giãn.',
                'content' => 'Năm 2026, xu hướng cảnh quan tập trung vào "ít nhưng chất": mảng xanh chọn lọc, vật liệu tự nhiên, và trải nghiệm đa giác quan. Không gian ngồi ngoài trời, ánh sáng đêm và các điểm nhấn nước trở thành thành phần nâng cao chất lượng sống rõ rệt.',
                'thumbnail_image' => '/theme/assets/hovi/gallery/hovi-038.jpg',
                'seo_title' => 'Xu hướng sân vườn biệt thự 2026 | Blog HOVI Việt Nam',
                'seo_description' => 'Khám phá xu hướng thiết kế sân vườn biệt thự 2026: tối giản, tinh tế và tập trung trải nghiệm sống chất lượng cao.',
                'published_at' => now()->subDay(),
                'sort_order' => 3,
                'is_published' => true,
            ],
        ];

        foreach ($blogs as $blog) {
            Blog::query()->updateOrCreate(
                ['slug' => $blog['slug']],
                $blog
            );
        }
    }
}