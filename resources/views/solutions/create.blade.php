<x-layouts.base>
    <x-slot name="title">
        Ajouter une solution
    </x-slot>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h4 class="font-weight-bolder">Ajouter une solution</h4>
                        <p class="mb-0">Pour la réclamation #{{ $reclamation->id }} - {{ $reclamation->titre }}</p>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('solutions.store', $reclamation) }}" id="solutionForm">
                            @csrf
                            <div class="form-group">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="contenu" class="form-label mb-0">Contenu de la solution</label>
                                    <button type="button" id="generateSolutionBtn" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-magic me-1"></i> Générer avec Gemini AI
                                    </button>
                                </div>
                                <textarea class="form-control @error('contenu') is-invalid @enderror" 
                                         id="contenu" name="contenu" rows="5" required 
                                         placeholder="Décrivez la solution à apporter à cette réclamation...">{{ old('contenu') }}</textarea>
                                @error('contenu')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('reclamations.show', $reclamation) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Retour
                                </a>
                                <div>
                                    <button type="button" id="generateAndSaveBtn" class="btn btn-success me-2">
                                        <i class="fas fa-robot me-2"></i>Générer et Enregistrer
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i> Enregistrer la solution
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Modal de confirmation -->
                            <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmationModalLabel">Confirmer la solution</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Voulez-vous enregistrer cette solution générée par l'IA ?</p>
                                            <div class="form-group">
                                                <textarea class="form-control" id="generatedSolution" rows="8" readonly></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                            <button type="button" id="confirmSaveBtn" class="btn btn-primary">
                                                <i class="fas fa-check me-2"></i>Confirmer et Enregistrer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
<script>
    $(document).ready(function() {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        let generatedSolution = '';

        // Gestion du clic sur le bouton de génération
        $('#generateSolutionBtn, #generateAndSaveBtn').on('click', function() {
            const isSaveButton = $(this).attr('id') === 'generateAndSaveBtn';
            
            // Afficher un indicateur de chargement
            const button = $(this);
            const originalText = button.html();
            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Génération en cours...');

            // Appel AJAX pour générer la solution
            $.ajax({
                url: '{{ route("reclamations.generate-solution", $reclamation) }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                success: function(response) {
                    if (response.success) {
                        generatedSolution = response.solution;
                        
                        if (isSaveButton) {
                            // Afficher la solution générée dans la zone de texte
                            $('#contenu').val(generatedSolution);
                            // Soumettre le formulaire
                            $('#solutionForm').submit();
                        } else {
                            // Afficher la solution dans la modale de confirmation
                            $('#generatedSolution').val(generatedSolution);
                            $('#confirmationModal').modal('show');
                        }
                    } else {
                        alert('Erreur: ' + (response.message || 'Impossible de générer une solution pour le moment.'));
                    }
                },
                error: function(xhr) {
                    console.error('Erreur:', xhr);
                    alert('Une erreur est survenue lors de la génération de la solution.');
                },
                complete: function() {
                    button.prop('disabled', false).html(originalText);
                }
            });
        });

        // Gestion du clic sur le bouton de confirmation
        $('#confirmSaveBtn').on('click', function() {
            $('#contenu').val(generatedSolution);
            $('#solutionForm').submit();
        });

        // Gestion de la fermeture de la modale
        $('#confirmationModal').on('hidden.bs.modal', function () {
            $('#generatedSolution').val('');
        });
    });
</script>
@endpush

</x-layouts.base>
