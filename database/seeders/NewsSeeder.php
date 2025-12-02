<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Sử dụng DB facade để chèn dữ liệu trực tiếp vào bảng 'news'
        DB::table('news')->insert([
            [
                'title' => '“Truy tìm Long Diên Hương” gây bất ngờ với doanh thu 155 tỷ đồng',
                'description' => 'Sau chỉ hơn một tuần công chiếu, “Truy tìm Long Diên Hương” đã vươn lên trở thành cái tên được nhắc đến nhiều nhất trên thị trường phim Việt. Sau thời gian dài trầm lắng của phim Việt, sự xuất hiện của tác phẩm đầu tay từ đạo diễn Dương Minh Chiến - người đứng sau nhiều dự án viral của nhóm ActionC - đã tạo nên một “cú hích” mạnh mẽ.

                Theo thống kê của trang Box Office Việt Nam, doanh thu vé rạp của bộ phim hành động - hài “Truy tìm Long Diên Hương” tính đến 10 giờ sáng 28/11 là 160,1 tỷ đồng. Trong dịp cuối tuần qua, phim thu về hơn 40 tỷ đồng, khoảng 430.000 vé bán ra trên 11.500 suất chiếu. “Truy tìm Long Diên Hương” đã trở thành một trong những tác phẩm nội địa có tốc độ tăng trưởng ấn tượng nhất trong năm 2025.',
                'image' => 'https://cdn.nhandan.vn/images/c70c314deea0caeec5472b6b51c3b7063ca178dcd4645d848abe33b68e1281d357aea4d694ef629ac22cd535d9f9566e/truytim3.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Zootopia 2 vừa mở màn đã phá loạt kỷ lục phòng vé Trung Quốc',
                'description' => 'Tại Trung Quốc, Zootopia 2 được gọi là Crazy Animal City 2. Chỉ trong ngày công chiếu, phim thu về 240 triệu nhân dân tệ, trở thành phim hoạt hình nhập khẩu có doanh thu một ngày cao nhất trong lịch sử phòng vé Trung Quốc.

                Nền tảng bán vé Maoyan cho biết đây cũng là phim hoạt hình thứ tư đạt hơn 200 triệu nhân dân tệ chỉ trong một ngày, sau 3 bom tấn nội địa: Na Tra 2, Giáng thần kỷ và Chú Gấu Boonie: Nghịch chuyển thời không.

                Zootopia 2 cũng thể hiện sức mạnh từ giai đoạn bán vé trước với 333 triệu nhân dân tệ, lập kỷ lục mới cho phim hoạt hình có doanh thu đặt vé trước cao nhất mọi thời đại tại Trung Quốc, vượt qua con số 309 triệu của Na Tra 2 trong mùa cao điểm Tết Nguyên đán.

                Phim dự kiến sẽ khởi chiếu vào 28/11/2025 tại Việt Nam',
                'image' => 'https://kenh14cdn.com/203336854389633024/2025/11/30/ngang641619c9-7838-4f16-9882-a4d7b492ec5f-17644832259911852191435-1764489119109-1764489127910898827003.jpg',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'title' => 'Hoàng Tử Quỷ – Anh Tú Atus bất ngờ vào vai hoàng tử Thân Đức',
                'description' => 'Hoàng Tử Quỷ là bộ phim mới nhất của nhà sản xuất Hoàng Quân và đạo diễn Trần Hữu Tấn, đã tạo nên sự chú ý đặc biệt. Không chỉ bởi bối cảnh đậm chất huyền sử, mà còn bởi cái tên đảm nhận vai chính Anh Tú Atus. Vốn quen thuộc với những vai diễn trẻ trung, hiện đại, Anh Tú bất ngờ xuất hiện trong một hình tượng hoàn toàn khác. Lần này, anh hóa thân thành Thân Đức – hoàng tử nửa người nửa quỷ, nhân vật trung tâm của cả câu chuyện.
                
                Điểm bất ngờ nhất của dự án nằm ở việc Anh Tú Atus được chọn vào vai Thân Đức. Từ trước đến nay, khán giả vốn quen với hình ảnh một Anh Tú gần gũi, đôi khi hài hước, đôi khi lãng tử trong những bộ phim hiện đại. Với Hoàng Tử Quỷ, anh phải hóa thân thành một nhân vật nặng về nội tâm, thiên hướng phản diện và đặc biệt là nằm trong thể loại kinh dị cổ trang – một lãnh địa hoàn toàn mới đối với anh.
                
                Nhân vật Thân Đức không phải một ác nhân đơn thuần mà là một hoàng tử khao khát được công nhận nhưng bị chính dòng máu lai nửa người nửa quỷ đẩy vào bi kịch. Đó là một dạng vai khó, đòi hỏi diễn viên không chỉ diễn bằng lời thoại mà còn phải truyền tải qua ánh mắt, thần thái, sự tiết chế trong từng cử chỉ.',
                'image' => 'https://image.sggp.org.vn/w680/Uploaded/2025/chuabhu/2025_09_30/hoang-tu-quy-1-4739-1674.jpg.webp',
                'created_at' => now()->subWeeks(3),
                'updated_at' => now()->subWeeks(3),
            ],
            [
                'title' => 'Bẫy tiền: Phim Việt khai thác nạn lừa đảo trực tuyến, lừa đảo qua điện thoại',
                'description' => 'Phim ra mắt trong bối cảnh nạn lừa đảo trực tuyến, lừa đảo qua điện thoại tại Việt Nam diễn ra ngày một nhiều. Các chiêu thức lừa đảo cũng trở nên tinh vi và phức tạp hơn, để lại hậu quả lớn cho nạn nhân, trong đó có nhiều người trẻ tuổi.
                
                Đoàn phim cho biết: “Thay vì chỉ tập trung vào góc độ phá án, bộ phim đặt trọng tâm vào tâm lý người bị lừa, sự hoảng loạn khi bị cuốn vào bẫy tài chính và những góc khuất phía sau,” đồng thời hứa hẹn phong cách kể chuyện mang đậm tính hiện thực xã hội.
                
                Đạo diễn Oscar Dương chia sẻ để truyền tải thông điệp hiệu quả, phim hướng đến phản ảnh đúng tình hình xã hội hiện tại.

                “Tôi và êkíp của mình đã chủ động tiếp cận, phỏng vấn và khai thác rất nhiều từ những chuyên gia trong lĩnh vực kế toán, tài chính, ngân hàng và thông qua luật sư để tìm hiểu thêm về luật kinh tế hiện hành; đặc biệt là tâm lý của chính những bạn trẻ hiện nay các quan điểm về tài chính, ước mơ và động lực kiếm tiền," nhà làm phim này chia sẻ. 
                
                Thủ vai chính trong phim này là diễn viên Liên Bỉnh Phát. Anh vừa nhận một đề cử diễn viên nam chính xuất sắc tại giải Kim Chung (Đài Loan, Trung Quốc), trong phim “Bác sỹ tha hương” cũng thuộc đề tài tâm lý, hiện thực xã hội.
                
                Theo chia sẻ của đoàn phim, nam diễn viên Liên Bỉnh Phát hội tụ đủ các yếu tố mà vai diễn cần, ví dụ khả năng nhìn trực diện vào máy quay để diễn tả tâm lý nhân vật đang đứng giữa ranh giới của đúng và sai.

                Theo chia sẻ của đoàn phim, nam diễn viên Liên Bỉnh Phát hội tụ đủ các yếu tố mà vai diễn cần, ví dụ khả năng nhìn trực diện vào máy quay để diễn tả tâm lý nhân vật đang đứng giữa ranh giới của đúng và sai.',
                'image' => 'https://media.vietnamplus.vn/images/4d193e6d5c459369e17bc49b0761541f3e534d30cce2cebb7f9d815a92de7b6005620d902565f91b1e6190e8656fda85b07e326dc02f3cdf52cff885ddad02b636be7d1871dd6a8121cb6270552a37c6/bay-tien-1st-look-poster-kthuoc-social-1.jpg',
                'created_at' => now()->subWeeks(2),
                'updated_at' => now()->subWeeks(2),
            ],
            [
                'title' => 'HHTO - Sau 18 năm ra mắt, cuối cùng người hâm mộ điện ảnh Việt Nam cũng có cơ hội thưởng thức kiệt tác "5 Centimet Trên Giây" của "phù thuỷ nỗi buồn" Makoto Shinkai trên màn ảnh rộng.',
                'description' => 'Theo thông tin chính thức từ các hệ thống rạp chiếu, phim điện ảnh 5 Centimet Trên Giây (5 Centimeters per Second) dự kiến ra mắt khán giả Việt Nam với phiên bản đặc biệt từ ngày 5/12/2025. Đây là lần đầu tiên bộ anime bi kịch lãng mạn này được phát hành rộng rãi tại các cụm rạp thương mại lớn trên toàn quốc. Trước đây, tác phẩm chủ yếu chỉ được trình chiếu giới hạn trong khuôn khổ các sự kiện văn hóa hoặc học thuật.
                
                5 Centimet Trên Giây kể về cuộc sống và mối liên kết sâu sắc, đầy nuối tiếc của nhân vật chính Takaki Tono qua ba giai đoạn trưởng thành, tất cả đều xoay quanh cô bạn thanh mai trúc mã Akari Shinohara. Bộ phim được chia thành ba chương gồm Truyện Hoa Anh Đào, Phi Hành Gia, và Năm Centimet Trên Giây. Mỗi chương thể hiện một cung bậc cảm xúc về sự xa cách và cô đơn.
                
                5 Centimet Trên Giây cũng là tác phẩm làm nên tên tuổi đạo diễn Makoto Shinkai khi sở hữu chất lượng hình ảnh phi thường vào thời điểm đó, đặc biệt là các chi tiết về ánh sáng, bầu trời và khung cảnh thiên nhiên, thường được ví như "mỗi khung hình đều là một tác phẩm nghệ thuật".',
                'image' => 'https://www.cgv.vn/media/catalog/product/cache/1/image/1800x/71252117777b696995f01934522c402d/t/h/thumbnail_5cm_logo_localize_mkt_material_digital_1920x1080.jpg',
                'created_at' => now()->subWeeks(6),
                'updated_at' => now()->subWeeks(6),
            ],
            [
                'title' => 'Bộ ba “bom tấn” xuất hiện trong Tuần phim Việt Nam tại Lào',
                'description' => 'VTV.vn - Trong 6 bộ phim được trình chiếu tại Tuần phim Việt Nam tại Lào, có bộ ba “bom tấn” được khán giả yêu thích, gồm: Mưa đỏ, Tử chiến trên không và Địa đạo.
                
                Thông tin từ Cục Điện ảnh (Bộ VHTTDL), từ ngày 2 đến 6/12/2025, Tuần phim Việt Nam tại Lào sẽ được tổ chức nhằm giới thiệu những tác phẩm đặc sắc của điện ảnh Việt Nam tới khán giả Lào. Sự kiện cũng nhằm thắt chặt hơn nữa mối quan hệ hữu nghị truyền thống giữa hai nước.
                
                Lễ khai mạc Tuần phim diễn ra ngày 2/12/2025 tại Cung Văn hóa Quốc gia Lào, mở màn với phim tài liệu Chủ tịch Cay - xỏn Phôm - vi - hản với Việt Nam.
                
                Không chỉ có sức thu hút với khán giả trong nước, bộ ba "bom tấn" phim Việt sau khi gặt hái loạt thành công, gần nhất là những giải thưởng danh giá tại LHP Việt Nam lần thứ XXIV, tiếp tục hành trình "xuất ngoại" và ra mắt khán giả yêu điện ảnh trên nước bạn.
                
                Phim truyện Mưa đỏ (124 phút) của Điện ảnh Quân đội Nhân dân, đạo diễn Đặng Thái Huyền, chuyển thể từ tiểu thuyết của nhà văn Chu Lai và lấy bối cảnh sự kiện 81 ngày đêm bảo vệ Thành cổ Quảng Trị năm 1972. Phim mong muốn chuyển tải thông điệp tới khán giả Lào về bản anh hùng ca về lòng yêu nước, tinh thần đoàn kết và sự hy sinh cao cả của thế hệ trẻ Việt Nam.
                
                Phim Tử chiến trên không (118 phút), sản xuất bởi Điện ảnh Công an Nhân dân và Công ty Cổ phần phim Thiên Ngân, do Hàm Trần đạo diễn, tái hiện vụ không tặc chiếm máy bay HVN-137 năm 1977.
                
                Phim Địa đạo – Mặt trời trong bóng tối (128 phút) của Công ty Cổ phần Sản xuất phim Hoan Khuê, đạo diễn Bùi Thạc Chuyên, kể về đội du kích tại căn cứ Bình An Đông năm 1967 trong chiến dịch Cedar Falls.
                
                Phim Hai người mẹ (96 phút) của Xưởng phim Truyện Việt Nam, đạo diễn Nguyễn Khắc Lợi, kể về tình hữu nghị, sự hy sinh của hai tộc Việt – Lào trong Chiến tranh Đông Dương.
                
                Cuối cùng, là sự trở lại của Tôi thấy hoa vàng trên cỏ xanh (102 phút), bộ phim của đạo diễn Victor Vũ, do Bộ VHTTDL đặt hàng Công ty Cổ phần phim Thiên Ngân sản xuất. Phim là câu chuyện đầy cảm xúc về quê hương, về gia đình, về thời niên thiếu của mỗi người.
                
                Cùng với hoạt động chiếu phim, tại Tuần phim sẽ diễn ra chương trình giao lưu sau buổi chiếu của bộ ba phim "bom tấn": phim truyện Mưa đỏ (ngày 2/12), Địa đạo – Mặt trời trong bóng tối (ngày 3/12), Tử chiến trên không (ngày 5/12).
                
                Tuần phim Việt Nam tại Lào 2025 được kỳ vọng mang đến cho khán giả trên nước bạn những tác phẩm giàu giá trị nghệ thuật và cảm xúc, thể hiện chiều sâu lịch sử, văn hoá và con người Việt Nam, qua đó tiếp tục góp phần vun đắp tình đoàn kết và sự gắn bó lâu bền giữa hai dân tộc.',
                'image' => 'https://cdn-images.vtv.vn/66349b6076cb4dee98746cf1/2025/11/29/-ia--ao-87249594618747471653023.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => '"Nam thần xứ Đài" Lâm Bách Hoành trở lại đường đua điện ảnh, "bom tấn".',
                'description' => 'SVO - Quy tụ toàn những tên tuổi "ngôi sao" thu hút phòng vé, những bộ phim ra rạp Việt gần đây đều có mở màn ấn tượng, hứa hẹn mùa phim cuối năm 2025 rộn ràng.
                
                Siêu phẩm Đài Loan quy tụ "dàn sao" thực lực.
                
                Mới đây, 96 Phúc sinh tử đã tung trailer mở màn bằng vụ đe dọa đánh bom tinh vi trên chuyến tàu sắt cao tốc từ Đài Bắc đến Cao Hùng. Đồng thời, phim hé lộ những ám ảnh quá khứ của cựu chuyên gia gỡ bom Tống Khang Nhân (Lâm Bách Hoành).

                Dưới bàn tay dàn dựng của đạo diễn Hồng Tử Huyên, tác phẩm hứa hẹn mở ra một hướng đi táo bạo cho điện ảnh Đài Loan khi trở thành bộ phim hành động – thảm họa đầu tiên lấy bối cảnh đường sắt cao tốc. Tác phẩm thu về 5 đề cử tại giải Kim Mã danh giá và chiến thắng tại hạng mục Hiệu ứng hình ảnh xuất sắc nhất.

                Ba năm sau ngày thảm kịch nổ bom tại trung tâm mua sắm xảy ra, khi cùng mẹ lên chuyến tàu cao tốc, Tống Khang Nhân và người vợ sắp cưới Huỳnh Hân (Tống Vân Hoa) lại rơi vào một tình huống nguy hiểm khác: Một quả bom hẹn giờ đã được cài sẵn trên tàu. Dù cố giữ kín thông tin, sự hoảng loạn nhanh chóng lan rộng, khi mọi người nhận ra họ đang đối mặt với hiểm nguy cận kề.
                
                Vụ việc lần này dường như còn phức tạp hơn, khi kẻ khủng bố tính toán vô cùng tinh vi. Chẳng mấy chốc, tàu sẽ cập bến ga cuối, vợ chồng Khang Nhân và đội trưởng Lý Kiệt (Lý Lý Nhân) cần phải hợp tác với cảnh sát để ngăn chặn thảm kịch xảy ra thêm một lần nữa, đồng thời đối mặt với những ám ảnh kinh hoàng từ vụ nổ năm xưa.
                
                Thời gian dần trôi, những sự thật từng được chôn vùi 3 năm trước đã dần hé lộ. Những tên đánh bom đã yêu cầu phải vạch trần một vụ việc mà giới cảnh sát đã luôn che giấu bấy lâu nay. Trước tình hình tính mạng của gia đình mình và hàng trăm người dân khác đang bị đe dọa, Tống Khang Nhân lại phải đối mặt với chính những nỗi ân hận sâu sắc nhất trong cuộc đời mình.
                
                Phim được cầm trịch bởi đạo diễn Hồng Tử Huyên. Vị trí giám đốc âm nhạc của tác phẩm sẽ do Hầu Chí Kiên đảm nhận. Đặc biệt "ngôi sao" Chuyện tôi và ma quỷ thành người một nhà Lâm Bách Hoành cũng sẽ góp mặt trong phim. Ngoài ra, tác phẩm còn quy tụ "dàn sao" nổi bật của nền điện ảnh xứ Đài: Tống Vân Hoa, Vương Bá Kiệt, Lý Lý Nhân, Lý Minh, cùng nhiều gương mặt thực lực khác.',
                'image' => 'https://cdn.tienphong.vn/images/de887584ef3b042cd28672ea48a7dc4ad1f3d2d1fa110558d40f16772faef0ff714ef85a15986e12ec866c53661a093d/q3.jpg',
                'created_at' => now()->subWeeks(4),
                'updated_at' => now()->subWeeks(4),
            ],
            [
                'title' => 'hoạt hình dịp lễ Giáng sinh cập bến Việt Nam',
                'description' => 'Phim hoạt hình với dàn diễn viên lồng tiếng "siêu sao"
                
                The King of Kings - Vua của các Vua ra mắt tại Bắc Mỹ phá kỷ lục Bắc Mỹ với doanh thu 60,3 triệu đôla trong 17 ngày. Bộ phim nhanh chóng trở thành một “hiện tượng văn hóa”, với độ “tươi” 98% từ khán giả trên Rotten tomatoes và điểm A+ từ CinemaScore.
                
                Bộ phim được ghi danh vào lịch sử như thể loại hoạt hình dựa trên Kinh Thánh có doanh thu mở màn cao nhất mọi thời đại.
                
                Sức lan tỏa của bộ phim đến từ sự kết hợp giữa công nghệ hoạt hình tinh xảo, phần hòa âm da diết đi vào lòng người và đặc biệt là sự góp giọng của dàn diễn viên lồng tiếng "siêu sao": Kenneth Branagh, Uma Thurman, Mark Hamill, Pierce Brosnan, Roman Griffin Davis, Forest Whitaker, Ben Kingsley và Oscar Isaac.
                
                Tất cả mang đến chiều sâu cảm xúc và sức nặng diễn xuất khiến từng lời thoại của các nhân vật đều trở nên sống động và đầy tính nhân văn.
                
                Bộ phim mang đến không khí Giáng sinh đích thực với ánh sáng, hy vọng, tình yêu và sự chữa lành. Đây là lựa chọn lý tưởng để khán giả cùng nhau trở về với câu chuyện nguyên thủy đã truyền cảm hứng cho nhân loại suốt hơn hai thiên niên kỷ.',
                'image' => 'https://thebendwi.org/wp-content/uploads/2025/04/King-of-Kings-at-The-Bend-Theater.jpg',
                'created_at' => now()->subWeeks(4),
                'updated_at' => now()->subWeeks(4),
            ],
            [
                'title' => '"Sherk 5" lùi lịch chiếu, "Minions 3" ra rạp sớm hơn dự định',
                'description' => '(TGĐA) - Shrek 5 bị đẩy lùi lịch chiếu đến tháng 12/2026, trong khi Minions 3 dự kiến ra rạp sớm hơn dự định, phát hành vào tháng 7/2026.

                Universal Pictures mới đây đã công bố rằng các bộ phim hoạt hình rất được mong đợi sẽ hoán đổi ngày phát hành tại rạp, với Shrek 5 của DreamWorks Animation sẽ lùi lịch phát hành từ ngày 1/7/2026 sang ngày 23/12, trong khi Minions 3 của Illumination sẽ ra rạp sớm hơn dự định, phát hành vào mùa hè năm 2026 thay cho lịch trước đó là 30/6/2027.
                
                Universal cũng đã chuyển ngày phát hành của bộ phim chưa có tên do Illumination sản xuất từ ngày 19/3/2027 sang tháng 6 cùng năm.
                
                Mặc dù tháng 12/2026 nghe có vẻ rất xa, nhưng Shrek 5 vẫn ra mắt kịp cho lễ kỷ niệm 25 năm của loạt phim và đánh dấu sự trở lại hoành tráng của các ngôi sao trong phần phim gốc là Mike Myers, Eddie Murphy và Cameron Diaz. Bộ ba sẽ lồng tiếng cho các nhân vật Shrek, Donkey và Fiona.
                
                Xuất hiện lần đầu ngoài rạp vào năm 2001, bộ phim Sherk đầu tiên giành giải Oscar cho phim hoạt hình hay nhất. Bốn phần phim Sherk đã thu về hơn 2.9 tỷ USD trên toàn thế giới, cùng với đó là một chương trình lưu diễn trên toàn cầu và một vở nhạc kịch Broadway từng đoạt giải thưởng, nhận được tám đề cử Tony và 12 đề cử Drama Desk. Cùng với đó, phim cũng góp phần tạo ra một điểm đến du lịch hấp dẫn ở London và các điểm tham quan trên khắp Công viên Universal Studios.
                
                Shrek 5 sẽ được đạo diễn bởi những cái tên kỳ cựu gắn liền với thành công của loạt phim Walt Dohrn và Conrad Vernon, cùng với đó là đồng đạo diễn Brad Ableson. Trong khi đó, Gina Shay và người được đề cử giải Oscar Chris Meledandri sẽ đảm nhận vai trò sản xuất.
                
                Trong khi đó, Minions 3, bộ phim tiếp nối thành công của bom tấn Despicable Me 4, sẽ ra rạp vào 30/6/2027 Hơn 10 năm kể từ khi những sinh vật màu vàng nổi bật và tên trùm siêu phản diện Gru ra mắt khán giả, các bộ phim Despicable Me và Minions đã thu về gần 5 tỷ USD tại phòng vé toàn cầu.
                
                Minions 3 được viết bởi Brian Lynch và sẽ được đạo diễn bởi Pierre Coffin, người được đề cử giải thưởng Oscar. Coffin cũng đã lồng tiếng cho Minions kể từ khi bộ phim đầu tay của họ ra mắt vào năm 2010. Bộ phim sẽ được sản xuất bởi Meledandri và Bill Ryan.',
                'image' => 'https://thegioidienanh.vn/stores/news_dataimages/2025/012025/11/13/screenshot-10120250111133812.png?rt=20250111133828',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Top phim chiếu rạp đáng xem nhất hôm nay: Top 1 gây bất ngờ',
                'description' => 'Top phim chiếu rạp đáng xem nhất hôm nay có nhiều lựa chọn phù hợp từng nhu cầu giải trí.
                
                Thống kê mới nhất từ Box Office Việt Nam cho thấy sự cạnh tranh rõ rệt ở nhóm dẫn đầu, dưới đây là top phim chiếu rạp đáng xem nhất hôm nay 27/11.
                
                1. Zootopia: Phi Vụ Động Trời 2 - Dẫn đầu gần 14.000 vé

                Với 13.967 vé bán ra, Zootopia: Phi Vụ Động Trời 2 tiếp tục giữ vững phong độ ấn tượng. Bộ phim đưa khán giả trở lại thế giới động vật náo nhiệt cùng bộ đôi Judy Hopps và Nick Wilde trong hành trình điều tra sinh vật bò sát bí ẩn gây hỗn loạn toàn thành phố. Sở hữu màu sắc tươi sáng, nhịp phim nhanh và nội dung dễ thương, dễ xem, đây là lựa chọn lý tưởng cho khán giả ở mọi độ tuổi.
                
                2. Truy Tìm Long Diên Hương - Sức nóng từ phim Việt chưa hạ nhiệt
                
                Xếp thứ hai top phim chiếu rạp đáng xem nhất hôm nay với 7.404 vé, Truy Tìm Long Diên Hương tiếp tục là đại diện nổi bật của phim Việt tại rạp. Khai thác câu chuyện báu vật Long Diên Hương bị đánh cắp, tác phẩm mang đến loạt phân cảnh võ thuật mãn nhãn, đan xen yếu tố hài duyên dáng cùng tinh thần nhân văn của người dân làng biển. Cách kể chuyện gần gũi, giàu cảm xúc giúp bộ phim giữ chân khán giả nhiều ngày liên tiếp.

                3. Phi Vụ Thế Kỷ: Thoắt Ẩn Thoắt Hiện - Cuộc chơi ảo thuật nâng cấp
                
                Với 1.236 vé bán ra, phần mới của loạt phim ảo thuật đình đám tiếp tục có mặt trong top 3. Nhóm Tứ kỵ sĩ trở lại với phi vụ táo bạo liên quan đến kim cương, buộc họ phải vận dụng mọi thủ thuật dàn dựng tinh vi để vượt qua loạt cạm bẫy. Tiết tấu nhanh, nhiều cú twist hấp dẫn và phong cách trình diễn mãn nhãn khiến tác phẩm trở thành lựa chọn đáng chú ý cho khán giả yêu thích thể loại bất ngờ, kịch tính.
                
                Ở nhóm cuối bảng, Gió Vẫn Thổi ghi nhận 5 vé nhưng lại mang đến câu chuyện đầy cảm hứng về Jiro Horikoshi - kỹ sư hàng không Nhật Bản dành cả đời theo đuổi giấc mơ bay lượn, đồng thời để lại dấu ấn lịch sử với thiết kế chiến đấu cơ A-6M Zero. Theo sau là Tee Yod 3: Quỷ Ăn Tạng với 4 vé, tiếp tục gieo rắc nỗi ám ảnh khi Yak và gia đình phải tiến vào khu rừng ma ám để giải cứu Yee trước khi linh hồn tà ác thức giấc. Cùng số vé tương tự, Mộ Đom Đóm, kiệt tác bi thương của điện ảnh Nhật, tái hiện hành trình sinh tồn của hai anh em mồ côi Seita và Setsuko trong bối cảnh Thế chiến II, để lại dư âm ám ảnh và nghẹn ngào cho người xem.
                
                Với nhiều thể loại từ hoạt hình, hành động, kinh dị đến những tác phẩm kinh điển nặng chiều sâu, phòng vé hôm nay mang đến nhiều lựa chọn phù hợp cho từng nhu cầu giải trí. Dù muốn tận hưởng tiếng cười nhẹ nhàng, theo dõi phi vụ gay cấn hay đắm chìm trong cảm xúc, khán giả đều có thể dễ dàng tìm được bộ phim lý tưởng cho buổi hẹn xem rạp trong ngày. ',
                'image' => 'https://ss-images.saostar.vn/wwebp700/2025/11/27/pc/1764212759941/ea2vjfygaa1-siwjmv4ckr2-cic1haklic3.jpg',
                'created_at' => now()->subWeeks(1),
                'updated_at' => now()->subWeeks(1),
            ],
        ]);
    }
}