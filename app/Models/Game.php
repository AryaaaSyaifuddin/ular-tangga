<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = ['materi_id', 'jumlah_pemain', 'status', 'current_question_id'];

    protected $casts = [
        'status' => 'string',
    ];

    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }
}