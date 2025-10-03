<x-layouts.app>
<div class="row mt-4 mx-4">
    <div class="col-12">
        
        <!-- Messages de succès/erreur -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Nav Tabs -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Gestion des Demandes d'Emprunt</h5>
                <ul class="nav nav-tabs card-header-tabs mt-3" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="sent-tab" data-bs-toggle="tab" data-bs-target="#sent" type="button" role="tab">
                            Mes Demandes Envoyées <span class="badge bg-primary">{{ $myRequests->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="received-tab" data-bs-toggle="tab" data-bs-target="#received" type="button" role="tab">
                            Demandes Reçues <span class="badge bg-warning">{{ $receivedRequests->count() }}</span>
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content" id="myTabContent">
                    
                    <!-- Mes Demandes Envoyées -->
                    <div class="tab-pane fade show active" id="sent" role="tabpanel">
                        <h6 class="mb-3">Mes Demandes d'Emprunt</h6>
                        
                        @if($myRequests->isEmpty())
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Vous n'avez encore aucune demande d'emprunt.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>📚 Livre Demandé</th>
                                            <th>👤 Propriétaire</th>
                                            <th>📅 Période d'Emprunt</th>
                                            <th>📊 Statut</th>
                                            <th class="text-center">⚡ Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($myRequests as $index => $request)
                                            <tr class="{{ $request->isPending() ? 'table-warning' : ($request->status === App\Enums\RequestStatus::APPROVED ? 'table-success' : ($request->status === App\Enums\RequestStatus::REJECTED ? 'table-danger' : 'table-info')) }}">
                                                <td class="text-center fw-bold">
                                                    {{ $index + 1 }}
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                                <i class="fas fa-book"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 fw-bold">{{ $request->book->title }}</h6>
                                                            <small class="text-muted"><i class="fas fa-user-edit"></i> {{ $request->book->author }}</small><br>
                                                            <small class="text-info"><i class="fas fa-calendar-plus"></i> Demandé le {{ $request->created_at->format('d/m/Y à H:i') }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-2">
                                                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                                                <i class="fas fa-user"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <span class="fw-semibold">{{ $request->book->owner->name }}</span><br>
                                                            <small class="text-muted">{{ $request->book->owner->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <div class="badge bg-light text-dark border mb-1">
                                                            <i class="fas fa-play text-success"></i> {{ $request->start_date->format('d/m/Y') }}
                                                        </div><br>
                                                        <div class="badge bg-light text-dark border">
                                                            <i class="fas fa-stop text-danger"></i> {{ $request->end_date->format('d/m/Y') }}
                                                        </div><br>
                                                        <small class="text-info">
                                                            <i class="fas fa-clock"></i> {{ $request->getDurationInDays() }} jour(s)
                                                        </small>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-{{ $request->status->color() }} fs-6 px-3 py-2">
                                                        {{ $request->status->label() }}
                                                    </span>
                                                    @if($request->status === App\Enums\RequestStatus::APPROVED && $request->isOverdue())
                                                        <br><span class="badge bg-danger mt-1">
                                                            <i class="fas fa-exclamation-triangle"></i> En retard
                                                        </span>
                                                    @endif
                                                    @if($request->status === App\Enums\RequestStatus::APPROVED)
                                                        <br><small class="text-success mt-1">
                                                            <i class="fas fa-check-circle"></i> Livre emprunté
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($request->isPending())
                                                        <form action="{{ route('borrow-requests.cancel', $request) }}" method="POST" class="d-inline" 
                                                              onsubmit="return confirm('Voulez-vous annuler cette demande ?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="fas fa-times"></i> Annuler
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    @if($request->status === App\Enums\RequestStatus::APPROVED)
                                                        <form action="{{ route('borrow-requests.mark-returned', $request) }}" method="POST" class="d-inline" 
                                                              onsubmit="return confirm('Confirmez-vous avoir rendu ce livre ?')">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-success">
                                                                <i class="fas fa-check"></i> Marquer comme Rendu
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <!-- Demandes Reçues -->
                    <div class="tab-pane fade" id="received" role="tabpanel">
                        <h6 class="mb-3">Demandes Reçues pour Mes Livres</h6>
                        
                        @if($receivedRequests->isEmpty())
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Aucune demande reçue pour vos livres.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead class="table-primary">
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>📖 Mon Livre Demandé</th>
                                            <th>🙋 Demandeur</th>
                                            <th>📅 Période Souhaitée</th>
                                            <th>📊 Statut</th>
                                            <th class="text-center">⚡ Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($receivedRequests as $index => $request)
                                            <tr class="{{ $request->isPending() ? 'table-warning' : ($request->status === App\Enums\RequestStatus::APPROVED ? 'table-success' : ($request->status === App\Enums\RequestStatus::REJECTED ? 'table-danger' : 'table-info')) }}">
                                                <td class="text-center fw-bold">
                                                    {{ $index + 1 }}
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                                <i class="fas fa-book-open"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 fw-bold text-success">{{ $request->book->title }}</h6>
                                                            <small class="text-muted"><i class="fas fa-user-edit"></i> {{ $request->book->author }}</small><br>
                                                            <span class="badge bg-info text-white">
                                                                <i class="fas fa-crown"></i> Votre livre
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-2">
                                                            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                                <i class="fas fa-user-circle"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <span class="fw-semibold">{{ $request->user->name }}</span><br>
                                                            <small class="text-muted">
                                                                <i class="fas fa-envelope"></i> {{ $request->user->email }}
                                                            </small><br>
                                                            <small class="text-info">
                                                                <i class="fas fa-clock"></i> {{ $request->created_at->diffForHumans() }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <div class="badge bg-light text-dark border mb-1">
                                                            <i class="fas fa-play text-success"></i> {{ $request->start_date->format('d/m/Y') }}
                                                        </div><br>
                                                        <div class="badge bg-light text-dark border">
                                                            <i class="fas fa-stop text-danger"></i> {{ $request->end_date->format('d/m/Y') }}
                                                        </div><br>
                                                        <small class="text-info">
                                                            <i class="fas fa-hourglass-half"></i> {{ $request->getDurationInDays() }} jour(s)
                                                        </small>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-{{ $request->status->color() }} fs-6 px-3 py-2">
                                                        {{ $request->status->label() }}
                                                    </span>
                                                    @if($request->status === App\Enums\RequestStatus::APPROVED && $request->isOverdue())
                                                        <br><span class="badge bg-danger mt-1">
                                                            <i class="fas fa-exclamation-triangle"></i> En retard!
                                                        </span>
                                                    @endif
                                                    @if($request->isPending())
                                                        <br><span class="badge bg-warning text-dark mt-1">
                                                            <i class="fas fa-hourglass-start"></i> En attente de réponse
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($request->isPending())
                                                        <div class="btn-group" role="group">
                                                            <form action="{{ route('borrow-requests.approve', $request) }}" method="POST" class="d-inline" 
                                                                  onsubmit="return confirm('Approuver cette demande ?')">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-sm btn-success">
                                                                    <i class="fas fa-check"></i> Approuver
                                                                </button>
                                                            </form>
                                                            
                                                            <form action="{{ route('borrow-requests.reject', $request) }}" method="POST" class="d-inline" 
                                                                  onsubmit="return confirm('Refuser cette demande ?')">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <i class="fas fa-times"></i> Refuser
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                    
                                                    @if($request->status === App\Enums\RequestStatus::APPROVED)
                                                        <span class="badge bg-info">En cours d'emprunt</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts pour les tabs Bootstrap -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Activer les tabs Bootstrap s'ils existent
    if (typeof bootstrap !== 'undefined' && bootstrap.Tab) {
        const triggerTabList = document.querySelectorAll('#myTab button')
        triggerTabList.forEach(triggerEl => {
            const tabTrigger = new bootstrap.Tab(triggerEl)
            
            triggerEl.addEventListener('click', event => {
                event.preventDefault()
                tabTrigger.show()
            })
        })
    }
    
    // Fallback simple pour les tabs si Bootstrap JS n'est pas disponible
    else {
        document.querySelectorAll('#myTab button').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault()
                
                // Retirer la classe active de tous les boutons
                document.querySelectorAll('#myTab button').forEach(btn => btn.classList.remove('active'))
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.remove('show', 'active')
                })
                
                // Activer le bouton cliqué
                this.classList.add('active')
                const target = document.querySelector(this.getAttribute('data-bs-target'))
                if (target) {
                    target.classList.add('show', 'active')
                }
            })
        })
    }
})
</script>

<style>
/* Styles personnalisés pour le dashboard */
.table-warning {
    background-color: #fff3cd !important;
}

.table-success {
    background-color: #d1e7dd !important;
}

.table-danger {
    background-color: #f8d7da !important;
}

.table-info {
    background-color: #d1ecf1 !important;
}

.badge {
    font-size: 0.75em;
}

.btn-group .btn {
    border-radius: 0.25rem;
    margin-right: 2px;
}

.nav-tabs .nav-link {
    border: 1px solid transparent;
    border-top-left-radius: 0.25rem;
    border-top-right-radius: 0.25rem;
}

.nav-tabs .nav-link.active {
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
}

.alert {
    border-radius: 0.375rem;
}

.table > :not(caption) > * > * {
    padding: 0.75rem;
    vertical-align: middle;
}

.table-bordered {
    border: 2px solid #dee2e6;
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
}

.table-striped > tbody > tr:nth-of-type(odd) > td {
    background-color: rgba(0, 0, 0, 0.02);
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.075);
}

.tab-pane {
    display: none;
}

.tab-pane.show.active {
    display: block;
}

/* Animations pour les tableaux */
.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    transform: scale(1.01);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Badge animations */
.badge {
    transition: all 0.3s ease;
}

.badge:hover {
    transform: scale(1.05);
}

/* Rounded avatars */
.rounded-circle {
    transition: all 0.3s ease;
}

.rounded-circle:hover {
    transform: scale(1.1);
}

/* Card header improvements */
.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.card-header h5 {
    color: white;
}

/* Table header improvements */
.table-dark th {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    border-color: #34495e;
}

.table-primary th {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    border-color: #2980b9;
}

/* Button improvements */
.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* Status badge improvements */
.fs-6 {
    font-size: 1rem !important;
}

/* Empty state styling */
.alert-info {
    background: linear-gradient(135deg, #e8f4f8 0%, #d1ecf1 100%);
    border-left: 4px solid #17a2b8;
}
</style>
</x-layouts.app>