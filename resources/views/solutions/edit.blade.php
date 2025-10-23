@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Modifier la solution</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('solutions.update', [$reclamation, $solution]) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="contenu">Contenu de la solution</label>
                            <textarea class="form-control @error('contenu') is-invalid @enderror" 
                                     id="contenu" name="contenu" rows="5" required>{{ old('contenu', $solution->contenu) }}</textarea>
                            @error('contenu')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Mettre à jour</button>
                        <a href="{{ route('reclamations.show', $reclamation) }}" class="btn btn-secondary mt-3">Annuler</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
