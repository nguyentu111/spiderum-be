<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoryTagSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::create([
            'name' => 'Quan điểm-tranh luận',
            'slug' => Str::slug('Quan điểm-tranh luận', '-'),
        ]);

        $category->tags()->saveMany([
            new Tag([
                'name' => 'Góc nhìn thời sự',
                'slug' => Str::slug('Góc nhìn thời sự', '-'),
            ]),
            new Tag([
                'name' => 'Thinking Out Loud',
                'slug' => Str::slug('Thinking Out Loud', '-'),
            ]),
            new Tag([
                'name' => 'Lịch sử',
                'slug' => Str::slug('Lịch sử', '-'),
            ]),
            new Tag([
                'name' => 'Khoa học công nghệ',
                'slug' => Str::slug('Khoa học công nghệ', '-'),
            ]),
            new Tag([
                'name' => 'Yêu',
                'slug' => Str::slug('Yêu', '-'),
            ]),
            new Tag([
                'name' => 'Phát triển bản thân',
                'slug' => Str::slug('Phát triển bản thân', '-'),
            ]),
            new Tag([
                'name' => 'Người trong muôn nghề',
                'slug' => Str::slug('Người trong muôn nghề', '-'),
            ]),
            new Tag([
                'name' => 'Life style',
                'slug' => Str::slug('Life style', '-'),
            ]),
            new Tag([
                'name' => 'Tâm lý học',
                'slug' => Str::slug('Tâm lý học', '-'),
            ]),
            new Tag([
                'name' => 'Giáo dục',
                'slug' => Str::slug('Giáo dục', '-'),
            ]),
            new Tag([
                'name' => 'Game',
                'slug' => Str::slug('Game', '-'),
            ]),
            new Tag([
                'name' => 'Movie',
                'slug' => Str::slug('Movie', '-'),
            ]),
            new Tag([
                'name' => 'Sách',
                'slug' => Str::slug('Sách', '-'),
            ]),
            new Tag([
                'name' => 'Chuyện thầm kín',
                'slug' => Str::slug('Chuyện thầm kín', '-'),
            ]),
            new Tag([
                'name' => 'Nấu ăn - Ẩm thực',
                'slug' => Str::slug('Nấu ăn - Ẩm thực', '-'),
            ]),
            new Tag([
                'name' => 'Du lịch',
                'slug' => Str::slug('Du lịch', '-'),
            ]),
            new Tag([
                'name' => 'Xe máy',
                'slug' => Str::slug('Xe máy', '-'),
            ]),
            new Tag([
                'name' => 'Sport',
                'slug' => Str::slug('Sport', '-'),
            ]),
            new Tag([
                'name' => 'Fitness',
                'slug' => Str::slug('Fitness', '-'),
            ]),
            new Tag([
                'name' => 'Music',
                'slug' => Str::slug('Music', '-'),
            ]),
            new Tag([
                'name' => 'Fashion',
                'slug' => Str::slug('Fashion', '-'),
            ]),
            new Tag([
                'name' => 'Nhiếp ảnh',
                'slug' => Str::slug('Nhiếp ảnh', '-'),
            ]),
            new Tag([
                'name' => 'Ô tô',
                'slug' => Str::slug('Ô tô', '-'),
            ]),
            new Tag([
                'name' => 'Sáng tạo',
                'slug' => Str::slug('Sáng tạo', '-'),
            ]),
        ]);

        $category = Category::create([
            'name' => 'Khoa học - Công nghệ',
            'slug' => Str::slug('Khoa học - Công nghệ', '-'),
        ]);

        $category->tags()->saveMany([
            new Tag([
                'name' => 'Thiết bị di dộng',
                'slug' => Str::slug('Thiết bị di dộng', '-'),
            ]),
            new Tag([
                'name' => 'Thiết bị thông minh',
                'slug' => Str::slug('Thiết bị thông minh', '-'),
            ]),
            new Tag([
                'name' => 'Công nghệ mới',
                'slug' => Str::slug('Công nghệ mới', '-'),
            ]),
            new Tag([
                'name' => 'Cách mạng 4.0',
                'slug' => Str::slug('Cách mạng 4.0', '-'),
            ]),
            new Tag([
                'name' => 'Blockchain',
                'slug' => Str::slug('Blockchain', '-'),
            ]),
            new Tag([
                'name' => 'Biển đổi khí hậu',
                'slug' => Str::slug('Biển đổi khí hậu', '-'),
            ]),
            new Tag([
                'name' => 'How thinks work',
                'slug' => Str::slug('How thinks work', '-'),
            ]),
            new Tag([
                'name' => 'Vũ trụ',
                'slug' => Str::slug('Vũ trụ', '-'),
            ]),
            new Tag([
                'name' => 'What if?',
                'slug' => Str::slug('What if?', '-'),
            ]),
            new Tag([
                'name' => 'Sinh học',
                'slug' => Str::slug('Sinh học', '-'),
            ]),
            new Tag([
                'name' => 'Vật lý',
                'slug' => Str::slug('Vật lý', '-'),
            ]),
            new Tag([
                'name' => 'Địa lý',
                'slug' => Str::slug('Địa lý', '-'),
            ]),
            new Tag([
                'name' => 'Hóa học',
                'slug' => Str::slug('Hóa học', '-'),
            ]),
            new Tag([
                'name' => 'Y học',
                'slug' => Str::slug('Y học', '-'),
            ]),
            new Tag([
                'name' => 'Toán học',
                'slug' => Str::slug('Toán học', '-'),
            ]),
            new Tag([
                'name' => 'Động vật',
                'slug' => Str::slug('Động vật', '-'),
            ]),
            new Tag([
                'name' => 'Giả thuyết',
                'slug' => Str::slug('Giả thuyết', '-'),
            ]),
            new Tag([
                'name' => 'Computer Science',
                'slug' => Str::slug('Computer Science', '-'),
            ]),
            new Tag([
                'name' => 'Phần mềm',
                'slug' => Str::slug('Phần mềm', '-'),
            ]),
        ]);

        Category::create([
            'name' => 'Tài chính',
            'slug' => Str::slug('Tài chính', '-'),
        ]);

        $category = Category::create([
            'name' => 'Thể thao',
            'slug' => Str::slug('Thể thao', '-'),
        ]);

        $category->tags()->saveMany([
            new Tag([
                'name' => 'Bóng rổ',
                'slug' => Str::slug('Bóng rổ', '-'),
            ]),
            new Tag([
                'name' => 'Võ thuật',
                'slug' => Str::slug('Võ thuật', '-'),
            ]),
            new Tag([
                'name' => 'Chạy',
                'slug' => Str::slug('Chạy', '-'),
            ]),
            new Tag([
                'name' => 'Bóng đá',
                'slug' => Str::slug('Bóng đá', '-'),
            ]),
            new Tag([
                'name' => 'Cờ tướng',
                'slug' => Str::slug('Cờ tướng', '-'),
            ]),
            new Tag([
                'name' => 'Cờ vua',
                'slug' => Str::slug('Cờ vua', '-'),
            ]),
            new Tag([
                'name' => 'Môn thể thao khác',
                'slug' => Str::slug('Môn thể thao khác', '-'),
            ]),
            new Tag([
                'name' => 'Phụ kiện tập luyện',
                'slug' => Str::slug('Phụ kiện tập luyện', '-'),
            ]),
            new Tag([
                'name' => 'Newbie sport',
                'slug' => Str::slug('Newbie sport', '-'),
            ]),
        ]);

        $category = Category::create([
            'name' => 'Game',
            'slug' => Str::slug('Game', '-'),
        ]);

        $category->tags()->saveMany([
            new Tag([
                'name' => 'Sandbox',
                'slug' => Str::slug('Sandbox', '-'),
            ]),
            new Tag([
                'name' => 'Real-time strategy (RTS)',
                'slug' => Str::slug('Real-time strategy (RTS)', '-'),
            ]),
            new Tag([
                'name' => 'Shooter (FPS and TPS)',
                'slug' => Str::slug('Shooter (FPS and TPS)', '-'),
            ]),
            new Tag([
                'name' => 'Multiplayer online battle arena (MOBA)',
                'slug' => Str::slug('Multiplayer online battle arena (MOBA)', '-'),
            ]),
            new Tag([
                'name' => 'Role-playing (RPG',
                'slug' => Str::slug('Role-playing (RPG', '-'),
            ]),
            new Tag([
                'name' => 'Simulation and sports',
                'slug' => Str::slug('Simulation and sports', '-'),
            ]),
            new Tag([
                'name' => 'Puzzlers and party games',
                'slug' => Str::slug('Puzzlers and party games', '-'),
            ]),
            new Tag([
                'name' => 'Action adventure',
                'slug' => Str::slug('Action adventure  ', '-'),
            ]),
            new Tag([
                'name' => 'Survival and horror',
                'slug' => Str::slug('Survival and horror', '-'),
            ]),
            new Tag([
                'name' => 'Review Game',
                'slug' => Str::slug('Review Game', '-'),
            ]),
            new Tag([
                'name' => 'Hướng dẫn game',
                'slug' => Str::slug('Hướng dẫn game', '-'),
            ]),
            new Tag([
                'name' => 'Giới thiệu game',
                'slug' => Str::slug('Giới thiệu game', '-'),
            ]),
        ]);

        Category::create([
            'name' => 'Sự kiện Spiderum',
            'slug' => Str::slug('Sự kiện Spiderum', '-'),
        ]);

        $category = Category::create([
            'name' => 'Sáng tác',
            'slug' => Str::slug('Sáng tác', '-'),
        ]);

        $category->tags()->saveMany([
            new Tag([
                'name' => 'Thơ',
                'slug' => Str::slug('Thơ', '-'),
            ]),
            new Tag([
                'name' => 'Truyện ngắn',
                'slug' => Str::slug('Truyện ngắn', '-'),
            ]),
            new Tag([
                'name' => 'Truyện dài',
                'slug' => Str::slug('Truyện dài', '-'),
            ]),
            new Tag([
                'name' => 'Tản văn',
                'slug' => Str::slug('Tản văn', '-'),
            ]),
        ]);

        $category = Category::create([
            'name' => 'Sách',
            'slug' => Str::slug('Sách', '-'),
        ]);

        $category->tags()->saveMany([
            new Tag([
                'name' => 'Fiction',
                'slug' => Str::slug('Fiction', '-'),
            ]),
            new Tag([
                'name' => 'Non-fiction',
                'slug' => Str::slug('Non-fiction', '-'),
            ]),
            new Tag([
                'name' => 'Văn học Mỹ',
                'slug' => Str::slug('Văn học Mỹ', '-'),
            ]),
            new Tag([
                'name' => 'Văn học Châu Âu',
                'slug' => Str::slug('Văn học Châu Âu', '-'),
            ]),
            new Tag([
                'name' => 'Văn học Châu Á',
                'slug' => Str::slug('Văn học Châu Á', '-'),
            ]),
            new Tag([
                'name' => 'Triết học',
                'slug' => Str::slug('Triết học', '-'),
            ]),
            new Tag([
                'name' => 'Tâm linh',
                'slug' => Str::slug('Tâm linh', '-'),
            ]),
            new Tag([
                'name' => 'Manga',
                'slug' => Str::slug('Manga', '-'),
            ]),
            new Tag([
                'name' => 'Sách kĩ năng',
                'slug' => Str::slug('Sách kĩ năng', '-'),
            ]),
            new Tag([
                'name' => 'Sách kinh doanh',
                'slug' => Str::slug('Sách kinh doanh', '-'),
            ]),
            new Tag([
                'name' => 'Review sách',
                'slug' => Str::slug('Review sách', '-'),
            ]),
            new Tag([
                'name' => 'Trích dẫn sách',
                'slug' => Str::slug('Trích dẫn sách', '-'),
            ]),
            new Tag([
                'name' => 'Sách thiếu nhi',
                'slug' => Str::slug('Sách thiếu nhi', '-'),
            ]),
            new Tag([
                'name' => 'Tác giả',
                'slug' => Str::slug('Tác giả', '-'),
            ]),
            new Tag([
                'name' => 'Comics',
                'slug' => Str::slug('Comics', '-'),
            ]),
            new Tag([
                'name' => 'Bàn về sách',
                'slug' => Str::slug('Bàn về sách', '-'),
            ]),
            new Tag([
                'name' => 'Văn học Mỹ-Latin',
                'slug' => Str::slug('Văn học Mỹ-Latin', '-'),
            ]),
        ]);

        $category = Category::create([
            'name' => 'Âm nhạc',
            'slug' => Str::slug('Âm nhạc', '-'),
        ]);

        $category->tags()->saveMany([
            new Tag([
                'name' => 'Pop',
                'slug' => Str::slug('Pop', '-'),
            ]),
            new Tag([
                'name' => 'Jazz',
                'slug' => Str::slug('Jazz', '-'),
            ]),
            new Tag([
                'name' => 'Classical',
                'slug' => Str::slug('Classical', '-'),
            ]),
            new Tag([
                'name' => 'R&B',
                'slug' => Str::slug('R&B', '-'),
            ]),
            new Tag([
                'name' => 'EDM',
                'slug' => Str::slug('EDM', '-'),
            ]),
            new Tag([
                'name' => 'Ballad',
                'slug' => Str::slug('Ballad', '-'),
            ]),
            new Tag([
                'name' => 'Nghe gì hôm nay',
                'slug' => Str::slug('Nghe gì hôm nay', '-'),
            ]),
            new Tag([
                'name' => 'Piano',
                'slug' => Str::slug('Piano', '-'),
            ]),
            new Tag([
                'name' => 'Guitar',
                'slug' => Str::slug('Guitar', '-'),
            ]),
            new Tag([
                'name' => 'Violin',
                'slug' => Str::slug('Violin', '-'),
            ]),
            new Tag([
                'name' => 'Saxophone',
                'slug' => Str::slug('Saxophone', '-'),
            ]),
            new Tag([
                'name' => 'Trống',
                'slug' => Str::slug('Trống', '-'),
            ]),
            new Tag([
                'name' => 'Tin tức âm nhạc',
                'slug' => Str::slug('Tin tức âm nhạc', '-'),
            ]),
            new Tag([
                'name' => 'Nghệ sĩ',
                'slug' => Str::slug('Nghệ sĩ', '-'),
            ]),
            new Tag([
                'name' => 'Sự kiện âm nhạc',
                'slug' => Str::slug('Sự kiện âm nhạc', '-'),
            ]),
            new Tag([
                'name' => 'Newbie âm nhạc',
                'slug' => Str::slug('Newbie âm nhạc', '-'),
            ]),
            new Tag([
                'name' => 'Thanh nhạc',
                'slug' => Str::slug('Thanh nhạc', '-'),
            ]),
            new Tag([
                'name' => 'Rap',
                'slug' => Str::slug('Rap', '-'),
            ]),
            new Tag([
                'name' => 'Sáng tác âm nhạc',
                'slug' => Str::slug('Sáng tác âm nhạc', '-'),
            ]),
            new Tag([
                'name' => 'Nhạc đồng quê',
                'slug' => Str::slug('Nhạc đồng quê', '-'),
            ]),
            new Tag([
                'name' => 'Nhạc Indie',
                'slug' => Str::slug('Nhạc Indie', '-'),
            ]),
            new Tag([
                'name' => 'Rock',
                'slug' => Str::slug('Rock', '-'),
            ]),
        ]);

        $category = Category::create([
            'name' => 'Góc nhìn thời sự',
            'slug' => Str::slug('Góc nhìn thời sự', '-'),
        ]);

        $category->tags()->saveMany([
            new Tag([
                'name' => 'Thời sự Việt Nam',
                'slug' => Str::slug('Thời sự Việt Nam', '-'),
            ]),
            new Tag([
                'name' => 'Thời sự quốc tế',
                'slug' => Str::slug('Thời sự quốc tế', '-'),
            ]),
            new Tag([
                'name' => 'Kinh tế xã hội',
                'slug' => Str::slug('Kinh tế xã hội', '-'),
            ]),
            new Tag([
                'name' => 'Địa chính trị',
                'slug' => Str::slug('Địa chính trị', '-'),
            ]),
        ]);

        $category = Category::create([
            'name' => 'Think Out Loud',
            'slug' => Str::slug('Think Out Loud', '-'),
        ]);

        $category->tags()->saveMany([
            new Tag([
                'name' => 'Tài chính cá nhân',
                'slug' => Str::slug('Tài chính cá nhân', '-'),
            ]),
            new Tag([
                'name' => 'Tâm sự',
                'slug' => Str::slug('Tâm sự', '-'),
            ]),
            new Tag([
                'name' => 'LGBT',
                'slug' => Str::slug('LGBT', '-'),
            ]),
            new Tag([
                'name' => 'Astro',
                'slug' => Str::slug('Astro', '-'),
            ]),
            new Tag([
                'name' => 'Zen',
                'slug' => Str::slug('Zen', '-'),
            ]),
            new Tag([
                'name' => 'Tôn giáo',
                'slug' => Str::slug('Tôn giáo', '-'),
            ]),
            new Tag([
                'name' => 'Truyền cảm hứng',
                'slug' => Str::slug('Truyền cảm hứng', '-'),
            ]),
            new Tag([
                'name' => 'Thời sự',
                'slug' => Str::slug('Thời sự', '-'),
            ]),
            new Tag([
                'name' => 'Hôm nay tôi nghĩ',
                'slug' => Str::slug('Hôm nay tôi nghĩ', '-'),
            ]),
            new Tag([
                'name' => 'Tình yêu',
                'slug' => Str::slug('Tình yêu', '-'),
            ]),
            new Tag([
                'name' => 'Nổi buồn',
                'slug' => Str::slug('Nổi buồn', '-'),
            ]),
            new Tag([
                'name' => 'Hạnh phúc',
                'slug' => Str::slug('Hạnh phúc', '-'),
            ]),
            new Tag([
                'name' => 'Cuộc sống',
                'slug' => Str::slug('Cuộc sống', '-'),
            ]),
        ]);
    }
}
