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

<<<<<<< HEAD
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Log pour déboguer les mises à jour
            \Log::info('Mise à jour de la réclamation', [
                'id' => $model->id,
                'original' => $model->getOriginal(),
                'dirty' => $model->getDirty(),
                'statut' => $model->statut,
                'has_solution' => $model->relationLoaded('solution') && $model->solution !== null
            ]);
        });
    }

=======
>>>>>>> origin/complet
    public function user()
    {
        return $this->belongsTo(User::class);
    }
<<<<<<< HEAD

    public function solution()
    {
        return $this->hasOne(Solution::class);
    }
=======
>>>>>>> origin/complet
}
