<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Project;
use Carbon\Carbon;
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
        $projectIdsBySlug = Project::query()
            ->pluck('id', 'slug')
            ->toArray();

        $blogs = [
            [
                'project_slug' => null,
                'title' => 'Tổng hợp thiết kế nội thất tại Green Villas: Những lát cắt Modern Luxury',
                'slug' => 'tong-hop-thiet-ke-noi-that-tai-green-villas-nhung-lat-cat-modern-luxury',
                'display_zone' => 'all',
                'excerpt' => 'Mỗi công trình tại Vinhomes Green Villas là một lát cắt khác nhau của Modern Luxury: hiện đại, sang trọng nhưng luôn phù hợp thói quen sống của gia chủ.',
                'content' => <<<HTML
<p>Mỗi công trình tại Vinhomes Green Villas là một minh chứng cho cách định nghĩa thiết kế nội thất hiện đại: tinh gọn, chuẩn mực và giàu cảm xúc sống. Dưới đây là những lát cắt tiêu biểu của bộ sưu tập dự án được triển khai thực tế.</p>
<h3>GV11-02 – Tinh thần hiện đại thể hiện qua vật liệu và bố cục</h3>
<p>Không gian khách – bếp mở rộng liền mạch, lấy trục chính là vách đá Quartz nguyên khối xuyên thông tầng. Bảng màu trung tính ấm được nhấn bằng gỗ, kim loại ánh đồng và hệ đèn điểm mềm.</p>
<p><img src="https://rhinelux.com/wp-content/uploads/2025/06/GV11-02.jpg" alt="Vinhomes Green Villas GV11-02"></p>
<h3>GV7-06 – Nâu gỗ cổ điển, sang trọng</h3>
<p>Bảng màu trầm ấm với gỗ tự nhiên, ánh sáng vàng và các chi tiết pha lê tạo nên bầu không khí quý phái nhưng không nặng nề. Bố cục mở giúp tổng thể vẫn thông thoáng và giàu nhịp điệu.</p>
<p><img src="https://rhinelux.com/wp-content/uploads/2025/06/GV7-06-2.jpg" alt="Vinhomes Green Villas GV7-06"></p>
<h3>GV12-09 – Sự chuyển tiếp tinh tế giữa ánh sáng, vật liệu và không gian</h3>
<p>Đá xám, gỗ nâu và ánh sáng vàng dịu được phối theo mảng lớn để mang lại cảm giác thư giãn. Khu khách – bếp đồng bộ về ngôn ngữ thiết kế, tối ưu tiện nghi cho sinh hoạt hàng ngày.</p>
<p><img src="https://rhinelux.com/wp-content/uploads/2025/06/GV12-09-3.jpg" alt="Vinhomes Green Villas GV12-09"></p>
HTML,
                'thumbnail_image' => 'https://rhinelux.com/wp-content/uploads/2025/06/GV11-02.jpg',
                'seo_title' => 'Tổng hợp thiết kế nội thất tại Green Villas | Modern Luxury',
                'seo_description' => 'Tổng hợp các dự án nội thất Green Villas với nhiều lát cắt Modern Luxury: bố cục, vật liệu và giải pháp tối ưu trải nghiệm sống.',
                'published_at' => Carbon::create(2025, 6, 20, 10, 30, 0, 'Asia/Ho_Chi_Minh'),
                'sort_order' => 1,
                'is_published' => true,
            ],
            [
                'project_slug' => null,
                'title' => 'Biệt thự đơn lập phong cách Modern Luxury tại Vinhomes Green Villas',
                'slug' => 'biet-thu-don-lap-phong-cach-modern-luxury-tai-vinhomes-green-villas',
                'display_zone' => 'blog',
                'excerpt' => 'Khối nhà 3 tầng + mái áp được xử lý tinh gọn, mở tối đa ánh sáng tự nhiên và kết nối mạnh với sân vườn để tạo trải nghiệm sống nghỉ dưỡng.',
                'content' => <<<HTML
<p>Ẩn mình trong khuôn viên xanh của Green Villas, căn biệt thự GV11-02 là một ví dụ điển hình cho Modern Luxury: khối hình rõ ràng, vật liệu chọn lọc và công năng tối ưu theo nhịp sống gia chủ.</p>
<p><img src="https://rhinelux.com/wp-content/uploads/2025/06/DSC09037-copy.jpg" alt="Thiết kế sân vườn Green Villas"></p>
<h3>Tầng 1 – Khách | Bếp | Sân vườn</h3>
<p>Mặt bằng liên thông giúp phòng khách và bếp ăn kết nối mạch lạc. Vách đá vân tự nhiên, tủ bếp âm tường và đảo bếp nhỏ gọn tạo cảm giác sang trọng nhưng không phô trương.</p>
<p><img src="https://rhinelux.com/wp-content/uploads/2025/06/DSC09077-copy.jpg" alt="Phòng khách Modern Luxury"></p>
<h3>Tầng 3 – Master Suite &amp; Phòng ngủ phụ</h3>
<p>Khu master được thiết kế theo tinh thần “retreat” với bảng màu trung tính, ánh sáng gián tiếp và hệ tủ tối giản. Không gian mang lại sự riêng tư, thư giãn và linh hoạt sử dụng.</p>
<p><img src="https://rhinelux.com/wp-content/uploads/2025/06/4_Interactive-LightMix-2.jpg" alt="Phòng ngủ master"></p>
HTML,
                'thumbnail_image' => 'https://rhinelux.com/wp-content/uploads/2025/06/DSC09077-copy.jpg',
                'seo_title' => 'Biệt thự đơn lập phong cách Modern Luxury tại Green Villas',
                'seo_description' => 'Khám phá thiết kế biệt thự Modern Luxury tại Green Villas với bố cục mở, ánh sáng tự nhiên và vật liệu cao cấp.',
                'published_at' => Carbon::create(2025, 6, 11, 9, 0, 0, 'Asia/Ho_Chi_Minh'),
                'sort_order' => 2,
                'is_published' => true,
            ],
            [
                'project_slug' => null,
                'title' => 'Hiệu chỉnh thiết kế 3D biệt thự Louis City Hoàng Mai: Khi bản thiết kế trở nên “có hồn”',
                'slug' => 'hieu-chinh-thiet-ke-3d-biet-thu-louis-city-hoang-mai-khi-ban-thiet-ke-tro-nen-co-hon',
                'display_zone' => 'blog',
                'excerpt' => 'Một bản 3D đẹp chưa phải đích đến cuối cùng. Giá trị thực nằm ở các vòng hiệu chỉnh công năng, vật liệu, kỹ thuật và trải nghiệm sống trước khi thi công.',
                'content' => <<<HTML
<p>Hiệu chỉnh 3D là bước “chốt hạ” quan trọng giúp bản thiết kế đi từ đẹp về hình ảnh sang khả thi khi triển khai. Dự án Louis City Hoàng Mai cho thấy rõ vai trò của bước này trong thực tế.</p>
<h3>Kiến trúc mặt ngoài: Tỉ lệ, kết nối và giới hạn cải tạo</h3>
<p>Cao độ tầng 1 lớn tạo cảm giác bề thế nhưng làm đứt kết nối với sân vườn. Phương án chỉnh sửa tập trung vào xử lý tỷ lệ, vật liệu bề mặt và tổ chức cảnh quan an toàn quanh nhà.</p>
<p><img src="https://rhinelux.com/wp-content/uploads/2025/07/ARCHITECTURE-9.jpg" alt="Thiết kế kiến trúc biệt thự Louis City"></p>
<h3>Sảnh đón khách &amp; khu khách – bếp</h3>
<p>Điểm nhìn, tỷ lệ khối tủ, trần và ánh sáng được rà soát lại để không gian mạch lạc, dễ dùng. Mỗi chi tiết đều được cân đối lại để giữ đúng tinh thần thiết kế ban đầu.</p>
<p><img src="https://rhinelux.com/wp-content/uploads/2025/07/Sanh-ngoai.jpg" alt="Sảnh đón khách"></p>
<h3>Góc nhìn cuối cùng</h3>
<p>Hiệu chỉnh 3D giúp tháo gỡ vướng mắc kỹ thuật trước khi thi công, giảm rủi ro phát sinh và đảm bảo công trình bàn giao đúng công năng – đúng cảm xúc.</p>
HTML,
                'thumbnail_image' => 'https://rhinelux.com/wp-content/uploads/2025/07/ARCHITECTURE-9.jpg',
                'seo_title' => 'Hiệu chỉnh thiết kế 3D biệt thự Louis City Hoàng Mai',
                'seo_description' => 'Toàn cảnh quá trình hiệu chỉnh thiết kế 3D biệt thự Louis City Hoàng Mai để tối ưu công năng, kỹ thuật và trải nghiệm sống.',
                'published_at' => Carbon::create(2025, 7, 21, 10, 15, 0, 'Asia/Ho_Chi_Minh'),
                'sort_order' => 3,
                'is_published' => true,
            ],
            [
                'project_slug' => null,
                'title' => 'Tổng hợp các mẫu thiết kế nội thất biệt thự tại Vinhomes Cổ Loa: Layout mẫu',
                'slug' => 'tong-hop-cac-mau-thiet-ke-noi-that-biet-thu-tai-vinhomes-co-loa-layout-mau',
                'display_zone' => 'blog',
                'excerpt' => 'Bộ layout thực tế cho dòng biệt thự song lập, liền kề tại Vinhomes Cổ Loa, kết hợp kinh nghiệm thiết kế và thi công nội thất đa khu đô thị.',
                'content' => <<<HTML
<p>Vinhomes Cổ Loa là bài toán thiết kế – thi công đòi hỏi hiểu sâu kết cấu, thông số và thói quen sử dụng thực tế. Bộ layout mẫu dưới đây tổng hợp các phương án đang triển khai thực tế.</p>
<p><img src="https://rhinelux.com/wp-content/uploads/2026/03/Layout-BT-song-lap-DH.png" alt="Layout biệt thự song lập ĐH"></p>
<h3>5 hướng giải pháp tiêu biểu</h3>
<ul>
  <li>Xử lý thông tầng và mảng đá khổ lớn đồng bộ trần – tường.</li>
  <li>Tối ưu mặt bằng liền kề theo trục giao thông và điểm nhìn.</li>
  <li>Đưa năng lực sản xuất thủ công vào các chi tiết có độ khó cao.</li>
  <li>Thi công sàn gỗ xương cá và nội thất bo góc theo tiêu chuẩn bàn giao cao.</li>
  <li>Tích hợp giải pháp thang máy kính và khung chịu lực trong không gian ở.</li>
</ul>
<p><img src="https://rhinelux.com/wp-content/uploads/2026/03/CAH_TANG-1-3.jpg" alt="Thiết kế nội thất biệt thự song lập Vin Cổ Loa"></p>
HTML,
                'thumbnail_image' => 'https://rhinelux.com/wp-content/uploads/2026/03/CAH_TANG-1-3.jpg',
                'seo_title' => 'Layout mẫu thiết kế nội thất biệt thự Vinhomes Cổ Loa',
                'seo_description' => 'Tổng hợp layout mẫu nội thất biệt thự tại Vinhomes Cổ Loa với giải pháp thi công thực tế và tối ưu công năng.',
                'published_at' => Carbon::create(2026, 3, 16, 8, 45, 0, 'Asia/Ho_Chi_Minh'),
                'sort_order' => 4,
                'is_published' => true,
            ],
            [
                'project_slug' => 'thiet-ke-biet-thu-vinhomes-ocean-park',
                'title' => 'Biệt thự đơn lập DD2-02, Vinhomes Ocean Park',
                'slug' => 'bt-don-lap-dd2-02-vinhomes-ocean-park',
                'display_zone' => 'project',
                'excerpt' => 'Bộ hồ sơ dự án DD2-02 nổi bật với bố cục ánh sáng rõ ràng, mảng vật liệu tự nhiên và giải pháp tổ chức không gian theo nhịp sống gia đình.',
                'content' => <<<HTML
<p>Dự án DD2-02 tập trung vào trải nghiệm sống tiện nghi và bền vững: hệ giao thông trong nhà rõ ràng, phòng sinh hoạt mở và các mảng vật liệu có chiều sâu thị giác.</p>
<p><img src="https://rhinelux.com/wp-content/uploads/2024/12/03-KT.jpg" alt="Biệt thự DD2-02"></p>
<p>Bộ ảnh thiết kế cho thấy sự liên tục giữa kiến trúc – nội thất – sân vườn, đảm bảo công năng sử dụng hàng ngày và cảm xúc thẩm mỹ trong từng khu vực.</p>
HTML,
                'thumbnail_image' => 'https://rhinelux.com/wp-content/uploads/2024/12/03-KT.jpg',
                'seo_title' => 'Biệt thự đơn lập DD2-02 Vinhomes Ocean Park',
                'seo_description' => 'Thông tin dự án biệt thự đơn lập DD2-02 tại Vinhomes Ocean Park với định hướng thiết kế – thi công nội thất hiện đại.',
                'published_at' => Carbon::create(2024, 12, 14, 9, 0, 0, 'Asia/Ho_Chi_Minh'),
                'sort_order' => 5,
                'is_published' => true,
            ],
            [
                'project_slug' => 'thiet-ke-san-vuon-biet-thu-starlake-mockup',
                'title' => 'Biệt thự đơn lập K5, KĐT Starlake',
                'slug' => 'bt-don-lap-k5-kdt-starlake',
                'display_zone' => 'project',
                'excerpt' => 'Case study K5 Starlake nhấn mạnh sự cân bằng giữa tổ chức mặt bằng, ánh sáng và vật liệu để tối ưu chất lượng sống.',
                'content' => <<<HTML
<p>Thiết kế K5 Starlake theo đuổi ngôn ngữ hiện đại tối giản, ưu tiên mặt bằng rõ nhịp, tỷ lệ nội thất chuẩn và tối đa ánh sáng tự nhiên trong các không gian sinh hoạt chính.</p>
<p><img src="https://rhinelux.com/wp-content/uploads/2024/10/29.jpg" alt="Biệt thự K5 Starlake"></p>
<p>Các mảng vật liệu lớn được xử lý đồng bộ, giảm chi tiết thừa, giúp tổng thể gọn và sang nhưng vẫn ấm áp cho nhịp sống gia đình.</p>
HTML,
                'thumbnail_image' => 'https://rhinelux.com/wp-content/uploads/2024/10/29.jpg',
                'seo_title' => 'Biệt thự đơn lập K5 KĐT Starlake',
                'seo_description' => 'Dự án biệt thự K5 Starlake với giải pháp tổ chức không gian, ánh sáng và vật liệu theo phong cách hiện đại.',
                'published_at' => Carbon::create(2024, 10, 22, 9, 0, 0, 'Asia/Ho_Chi_Minh'),
                'sort_order' => 6,
                'is_published' => true,
            ],
            [
                'project_slug' => null,
                'title' => 'Tổng hợp thiết kế phòng ngủ master biệt thự tại Vinhomes: Không gian riêng tư đẳng cấp',
                'slug' => 'tong-hop-thiet-ke-phong-ngu-master-biet-thu-tai-vinhomes',
                'display_zone' => 'blog',
                'excerpt' => 'Từ tỷ lệ giường – tủ – ánh sáng đến tổ chức khu thay đồ, bài viết tổng hợp các nguyên tắc thiết kế master suite sang trọng và tối ưu công năng.',
                'content' => <<<HTML
<p>Phòng ngủ master không chỉ là nơi nghỉ ngơi mà còn là không gian tái tạo năng lượng. Một thiết kế tốt cần đồng thời đạt ba tiêu chí: riêng tư, thư giãn và linh hoạt sử dụng.</p>
<h3>Ba nguyên tắc cốt lõi</h3>
<ul>
  <li>Giữ trục giao thông gọn, tránh xung đột giữa giường ngủ và khu thay đồ.</li>
  <li>Tổ chức ánh sáng nhiều lớp: tổng thể, chức năng và nhấn điểm.</li>
  <li>Ưu tiên vật liệu chạm êm, tông màu trung tính để tối ưu cảm giác nghỉ ngơi.</li>
</ul>
<p>Thiết kế master suite chuẩn giúp căn phòng sang trọng bền vững theo thời gian, không phụ thuộc quá nhiều vào xu hướng ngắn hạn.</p>
HTML,
                'thumbnail_image' => 'https://rhinelux.com/wp-content/uploads/2025/06/4_Interactive-LightMix-2.jpg',
                'seo_title' => 'Thiết kế phòng ngủ master biệt thự tại Vinhomes',
                'seo_description' => 'Tổng hợp giải pháp thiết kế phòng ngủ master biệt thự tại Vinhomes theo hướng sang trọng, tối ưu riêng tư và công năng.',
                'published_at' => Carbon::create(2025, 9, 8, 9, 20, 0, 'Asia/Ho_Chi_Minh'),
                'sort_order' => 7,
                'is_published' => true,
            ],
            [
                'project_slug' => null,
                'title' => 'Top mẫu thiết kế biệt thự Vinhomes Riverside sang trọng bậc nhất',
                'slug' => 'top-mau-thiet-ke-biet-thu-vinhomes-riverside-sang-trong-bac-nhat',
                'display_zone' => 'blog',
                'excerpt' => 'Tuyển chọn các concept nổi bật tại Vinhomes Riverside với phong cách Modern Luxury, chú trọng chiều sâu vật liệu và trải nghiệm sống.',
                'content' => <<<HTML
<p>Vinhomes Riverside là khu đô thị có mật độ biệt thự cao với nhiều phong cách sống khác nhau. Các mẫu thiết kế nổi bật đều có điểm chung: bố cục mạch lạc, vật liệu có chiều sâu và công năng rõ ràng.</p>
<h3>Những tiêu chí tạo nên biệt thự “đẳng cấp nhưng thực dụng”</h3>
<ul>
  <li>Không gian khách – bếp mở, kết nối sân vườn bằng hệ kính lớn.</li>
  <li>Vật liệu tự nhiên được dùng có điểm nhấn, tránh nặng nề thị giác.</li>
  <li>Thiết kế theo nhu cầu sinh hoạt thật thay vì chạy theo hình ảnh.</li>
</ul>
HTML,
                'thumbnail_image' => 'https://rhinelux.com/wp-content/uploads/2025/07/Khach.jpg',
                'seo_title' => 'Top mẫu thiết kế biệt thự Vinhomes Riverside sang trọng',
                'seo_description' => 'Tổng hợp các mẫu thiết kế biệt thự Vinhomes Riverside theo phong cách hiện đại, sang trọng và giàu trải nghiệm.',
                'published_at' => Carbon::create(2025, 10, 22, 8, 30, 0, 'Asia/Ho_Chi_Minh'),
                'sort_order' => 8,
                'is_published' => true,
            ],
            [
                'project_slug' => 'thiet-ke-biet-thu-vinhomes-ocean-park',
                'title' => 'Biệt thự đơn lập Vinhomes Green Villas GV12-09: Sự chuyển tiếp tinh tế giữa ánh sáng và vật liệu',
                'slug' => 'bt-don-lap-gv12-09-vinhomes-green-villas-2',
                'display_zone' => 'project',
                'excerpt' => 'GV12-09 tạo ấn tượng bằng nhịp chuyển mềm giữa đá xám, gỗ nâu và ánh sáng vàng dịu, mang lại trải nghiệm sống thư giãn và hiện đại.',
                'content' => <<<HTML
<p>GV12-09 là phương án thiết kế đề cao cảm giác thư giãn trong không gian sống: các mảng nội thất lớn gọn ghẽ, ánh sáng được phân lớp và vật liệu trung tính có chiều sâu.</p>
<p><img src="https://rhinelux.com/wp-content/uploads/2025/06/GV12-09-3.jpg" alt="Green Villas GV12-09"></p>
<p>Toàn bộ khu khách – bếp được xử lý đồng bộ về màu sắc và chi tiết hoàn thiện, giúp không gian thoáng, tinh tế và thuận tiện cho sinh hoạt gia đình.</p>
HTML,
                'thumbnail_image' => 'https://rhinelux.com/wp-content/uploads/2025/06/GV12-09-3.jpg',
                'seo_title' => 'Biệt thự đơn lập Green Villas GV12-09',
                'seo_description' => 'Thông tin dự án GV12-09 tại Green Villas với phong cách hiện đại, vật liệu trung tính và ánh sáng tinh tế.',
                'published_at' => Carbon::create(2025, 6, 12, 9, 40, 0, 'Asia/Ho_Chi_Minh'),
                'sort_order' => 9,
                'is_published' => true,
            ],
            [
                'project_slug' => 'thiet-ke-biet-thu-vinhomes-ocean-park',
                'title' => 'Biệt thự đơn lập GV2-08A Vinhomes Green Villas: Á Đông hiện đại trong không gian sống mới',
                'slug' => 'bt-don-lap-gv2-08a-vinhomes-green-villas',
                'display_zone' => 'project',
                'excerpt' => 'GV2-08A kết hợp tinh thần Á Đông qua vật liệu gỗ, bố cục đăng đối và lớp nền sáng hiện đại để tạo nên bản sắc sống riêng.',
                'content' => <<<HTML
<p>GV2-08A là dự án thể hiện rõ cách kết hợp tinh thần Á Đông với ngôn ngữ thiết kế đương đại: giữ chất nền truyền thống nhưng xử lý nhẹ, sáng và tinh gọn.</p>
<h3>Điểm nhấn thiết kế</h3>
<p>Bàn ghế gỗ chân cao, bố cục đăng đối và tranh tường khổ lớn được đặt trên lớp nền tường sáng – rèm voan để tổng thể không nặng, vẫn giàu chiều sâu văn hóa.</p>
<p><img src="https://rhinelux.com/wp-content/uploads/2025/06/GV2-08.jpg" alt="Green Villas GV2-08A"></p>
HTML,
                'thumbnail_image' => 'https://rhinelux.com/wp-content/uploads/2025/06/GV2-08.jpg',
                'seo_title' => 'Biệt thự đơn lập GV2-08A Vinhomes Green Villas',
                'seo_description' => 'Dự án GV2-08A tại Green Villas với phong cách Á Đông hiện đại, tối ưu công năng và thẩm mỹ sống.',
                'published_at' => Carbon::create(2025, 6, 13, 9, 45, 0, 'Asia/Ho_Chi_Minh'),
                'sort_order' => 10,
                'is_published' => true,
            ],
        ];

        foreach ($blogs as $blog) {
            $projectSlug = $blog['project_slug'];
            unset($blog['project_slug']);

            $blog['project_id'] = is_string($projectSlug) && isset($projectIdsBySlug[$projectSlug])
                ? (int) $projectIdsBySlug[$projectSlug]
                : null;

            Blog::query()->updateOrCreate(
                ['slug' => $blog['slug']],
                $blog
            );
        }
    }
}