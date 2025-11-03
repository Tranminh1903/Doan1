<?php

namespace App\Models\UserModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Promotion extends Model
{
    use HasFactory;

    // Vì bạn đặt tên bảng là 'promotion' (số ít)
    protected $table = 'promotion';

    protected $fillable = [
        'code',           // Mã khuyến mãi
        'type',           // Loại: percent / fixed
        'value',          // Giá trị giảm
        'limit_count',    // Giới hạn lượt dùng
        'used_count',     // Đã dùng
        'min_order_value',   // Giá trị đơn hàng tối thiểu
        'min_ticket_quantity', // Số ghế tối thiểu
        'start_date',     // Ngày bắt đầu
        'end_date',       // Ngày kết thúc
        'status',         // active / inactive
        'description',    // Mô tả
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    // Nếu vẫn muốn giữ quan hệ tới bảng khác
    public function customerPromotions()
    {
        return $this->hasMany(CustomerPromotion::class);
    }

    // 🧠 Hàm kiểm tra khuyến mãi còn hiệu lực không
    public function isValid(): bool
    {
        return $this->status === 'active'
            && $this->start_date <= now()
            && $this->end_date >= now()
            && $this->used_count < $this->limit_count;
    }

    // 💰 Hàm tính số tiền giảm
    public function calculateDiscount(float $total): float
    {
        if ($this->type === 'percent') {
            $discount = $total * ($this->value / 100);
        } else {
            $discount = $this->value;
        }

        return min($discount, $total); // không vượt quá tổng tiền
    }
}
