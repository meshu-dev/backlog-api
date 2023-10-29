<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends BaseModel
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = ['user_id', 'category_id', 'name', 'image_url'];
}
