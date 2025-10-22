<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titre',
        'description',
        'categorie',
        'priorite',
        'statut',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
