<?php

namespace App\Models;

use App\Models\Promoter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PromoterReview extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'promoter_reviews';

    protected $fillable = [
        'promoter_id',
        'review',
        'author',
        'display',
    ];

    public function promoter()
    {
        return $this->belongsTo(Promoter::class, 'promoter_id');
    }

    public static function getRecentReviewsForPromoter($promoterId)
    {
        return self::where('promoter_id', $promoterId)
            ->whereNull('deleted_at')
            ->where('display', 1)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
    }
}