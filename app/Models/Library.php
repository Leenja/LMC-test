<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function Language(){
        return $this->hasMany(Language::class, 'LanguageId');
    }

    public function Item(){
        return $this->hasMany(Item::class, 'ItemId');
    }
}
