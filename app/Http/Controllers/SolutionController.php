<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use App\Models\Solution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SolutionController extends Controller
{
    /**
     * Show the form for creating a new solution.
     */
    public function create(Reclamation $reclamation)
    {
        return view('solutions.create', compact('reclamation'));
    }

    /**
     * Generate a solution using Gemini API
     */
    public function generateSolution(Request $request, Reclamation $reclamation)
    {
        $client = new Client();
        $apiKey = env('GEMINI_API_KEY');
        
        try {
            $response = $client->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key={$apiKey}", [
                'json' => [
                    'contents' => [
                        'parts' => [
                            ['text' => "Génère une solution professionnelle pour la réclamation suivante :\n\n" . 
                                     "Titre : " . $reclamation->titre . "\n" .
                                     "Description : " . $reclamation->description . "\n\n" .
                                     "La solution doit être claire, concise et professionnelle."]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 500,
                    ]
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return response()->json([
                    'success' => true,
                    'solution' => $data['candidates'][0]['content']['parts'][0]['text']
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Impossible de générer une solution pour le moment.'
            ], 400);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération de la solution : ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la génération de la solution.'
            ], 500);
        }
    }

    /**
     * Store a newly created solution in storage.
     */
    public function store(Request $request, Reclamation $reclamation)
    {
        // Activer le suivi des requêtes SQL
        \DB::enableQueryLog();
        
        // Log de débogage
        \Log::info('Début de la création d\'une solution', [
            'reclamation_id' => $reclamation->id,
            'current_status' => $reclamation->statut,
            'user_id' => Auth::id()
        ]);

        $request->validate([
            'contenu' => 'required|string',
        ]);

        try {
            // Commencer une transaction de base de données
            \DB::beginTransaction();

            // 1. D'abord, vérifier la valeur actuelle dans la base de données
            $currentStatus = \DB::table('reclamations')
                ->where('id', $reclamation->id)
                ->value('statut');
                
            \Log::info('Statut avant mise à jour', [
                'reclamation_id' => $reclamation->id,
                'current_status' => $currentStatus
            ]);
            
            // 2. Mettre à jour directement dans la base de données
            \Log::info('Tentative de mise à jour du statut', [
                'reclamation_id' => $reclamation->id,
                'current_status' => $reclamation->statut,
                'new_status' => 'resolue'
            ]);
            
            try {
                $updated = \DB::table('reclamations')
                    ->where('id', $reclamation->id)
                    ->update([
                        'statut' => 'resolue',
                        'updated_at' => now()
                    ]);
                
                // 3. Vérifier que la mise à jour a réussi
                $newStatus = \DB::table('reclamations')
                    ->where('id', $reclamation->id)
                    ->value('statut');
                
                \Log::info('Résultat de la mise à jour', [
                    'reclamation_id' => $reclamation->id,
                    'update_result' => $updated,
                    'new_status' => $newStatus,
                    'query' => \DB::getQueryLog()
                ]);
                
                if ($newStatus !== 'resolue') {
                    throw new \Exception("Échec de la mise à jour du statut à 'resolue'. Statut actuel: " . $newStatus);
                }
            } catch (\Exception $e) {
                \Log::error('Erreur lors de la mise à jour du statut', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
            
            // 4. Mettre à jour l'instance du modèle
            $reclamation->statut = 'resolue';
            $reclamation->updated_at = now();
            
            // 5. Créer la solution
            $solution = $reclamation->solution()->create([
                'admin_id' => Auth::id(),
                'contenu' => $request->contenu,
            ]);

            // Valider les modifications
            \DB::commit();

            // Forcer le rechargement de la réclamation
            $reclamation = $reclamation->fresh();

            \Log::info('Solution créée et statut mis à jour avec succès', [
                'solution_id' => $solution->id,
                'reclamation_id' => $reclamation->id,
                'new_status' => $reclamation->statut
            ]);

            return redirect()->route('reclamations.show', $reclamation)
                ->with('success', 'Solution ajoutée avec succès et statut de la réclamation marqué comme résolue');
                
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Erreur lors de la création de la solution', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Une erreur est survenue lors de l\'ajout de la solution.');
        }
    }

    /**
     * Show the form for editing the specified solution.
     */
    public function edit(Reclamation $reclamation, Solution $solution)
    {
        return view('solutions.edit', compact('reclamation', 'solution'));
    }

    /**
     * Update the specified solution in storage.
     */
    public function update(Request $request, Reclamation $reclamation, Solution $solution)
    {
        $request->validate([
            'contenu' => 'required|string',
        ]);

        $solution->update([
            'contenu' => $request->contenu,
        ]);

        return redirect()->route('reclamations.show', $reclamation)
            ->with('success', 'Solution mise à jour avec succès');
    }

    /**
     * Remove the specified solution from storage.
     */
    public function destroy(Reclamation $reclamation, Solution $solution)
    {
        $solution->delete();
        return redirect()->route('reclamations.show', $reclamation)
            ->with('success', 'Solution supprimée avec succès');
    }
}
