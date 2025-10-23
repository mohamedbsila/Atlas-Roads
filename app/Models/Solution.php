<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reclamation;
use App\Models\User;

class Solution extends Model
{
    use HasFactory;

    protected $fillable = [
        'reclamation_id',
        'admin_id',
        'contenu'
    ];

    public function reclamation()
    {
        return $this->belongsTo(Reclamation::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
