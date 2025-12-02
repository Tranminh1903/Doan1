<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // XOÁ dữ liệu cũ
        DB::table('customers')->delete();

        // Lấy user_id từ bảng users (chỉ lấy role = customers)
        $users = DB::table('users')
            ->where('role', 'customers')
            ->pluck('id')
            ->all();

        // Nếu ít hơn 5 user thì tự tạo thêm
        $count = count($users);

        if ($count < 5) {
            for ($i = $count; $i < 5; $i++) {

                $id = DB::table('users')->insertGetId([
                    'username'   => 'customer' . ($i + 1),
                    'email'      => 'customer' . ($i + 1) . '@example.com',
                    'password'   => bcrypt('123456'),
                    'role'       => 'customers',
                    'status'     => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $users[] = $id;
            }
        }

        // Tạo 5 customers tương ứng
        $customers = [];
        $names     = ['Minh', 'Khoa', 'Huy', 'Tuấn', 'Phong'];

        foreach (array_slice($users, 0, 5) as $i => $userId) {
            $customers[] = [
                'user_id'                => $userId,
                'customer_name'          => $names[$i] . ' Auto',
                'total_order_amount'     => 0,
                'total_promotions_unused'=> 0,
                'created_at'             => Carbon::now()->subDays(rand(1, 30)),
                'updated_at'             => now(),
            ];
        }

        DB::table('customers')->insert($customers);
    }
}
