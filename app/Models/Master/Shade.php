<?php

namespace App\Models\Master;

use App\Models\Master\Article;
use Illuminate\Database\Eloquent\Model;

class Shade extends Model
{
    protected $fillable = [
        'name',
        'article_id'
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
