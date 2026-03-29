<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_number','recipient_name','recipient_phone',
        'recipient_address','payment_method','shipping_method',
        'subtotal','shipping_cost','total','status','notes'
    ];
    protected $casts = ['subtotal'=>'decimal:2','shipping_cost'=>'decimal:2','total'=>'decimal:2'];

    public function items() { return $this->hasMany(OrderItem::class); }
    public function getStatusLabelAttribute() {
        return match($this->status) {
            'pending'    => ['label'=>'Menunggu',   'color'=>'warning'],
            'processing' => ['label'=>'Diproses',   'color'=>'info'],
            'shipped'    => ['label'=>'Dikirim',    'color'=>'primary'],
            'delivered'  => ['label'=>'Selesai',    'color'=>'success'],
            'cancelled'  => ['label'=>'Dibatalkan', 'color'=>'danger'],
            default      => ['label'=>$this->status,'color'=>'secondary'],
        };
    }
    public function getPaymentLabelAttribute() { return $this->payment_method==='cash'?'💵 Cash':'📱 Cashless'; }
    public function getShippingLabelAttribute() { return $this->shipping_method==='cod'?'🏠 COD':'📦 JNE Reguler'; }
    public function getTotalFormattedAttribute() { return 'Rp '.number_format($this->total,0,',','.'); }
}