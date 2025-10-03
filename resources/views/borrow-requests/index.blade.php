<x-layouts.app>
<div class="container-fluid px-4 py-5">
    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Dashboard Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="dashboard-header">
                <h1 class="dashboard-title">
                    <i class="fas fa-exchange-alt me-3"></i>
                    Gestion des Emprunts
                </h1>
                <p class="dashboard-subtitle">
                    Gérez vos demandes d'emprunt et les demandes reçues pour vos livres
                </p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-5">
        <div class="col-md-6 mb-3">
            <div class="stats-card stats-card-primary">
                <div class="stats-icon">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $myRequests->count() }}</h3>
                    <p>Demandes Envoyées</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="stats-card stats-card-warning">
                <div class="stats-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <div class="stats-info">
                    <h3>{{ $receivedRequests->count() }}</h3>
                    <p>Demandes Reçues</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="modern-tabs-container mb-4">
        <ul class="nav nav-pills modern-nav-pills" id="borrowTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="sent-tab" data-bs-toggle="pill" data-bs-target="#sent-requests" type="button" role="tab">
                    <i class="fas fa-paper-plane me-2"></i>
                    Mes Demandes
                    <span class="badge">{{ $myRequests->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="received-tab" data-bs-toggle="pill" data-bs-target="#received-requests" type="button" role="tab">
                    <i class="fas fa-inbox me-2"></i>
                    Demandes Reçues
                    <span class="badge">{{ $receivedRequests->count() }}</span>
                </button>
            </li>
        </ul>
    </div>

    <!-- Tab Content -->
    <div class="tab-content" id="borrowTabsContent">
        
        <!-- Mes Demandes Envoyées -->
        <div class="tab-pane fade show active" id="sent-requests" role="tabpanel">
            @if($myRequests->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <h4>Aucune demande envoyée</h4>
                    <p>Vous n'avez encore envoyé aucune demande d'emprunt.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-book me-2"></i>
                        Parcourir les livres
                    </a>
                </div>
            @else
                <div class="requests-grid">
                    @foreach($myRequests as $request)
                        <div class="request-card {{ $request->isPending() ? 'pending' : ($request->status === App\Enums\RequestStatus::APPROVED ? 'approved' : ($request->status === App\Enums\RequestStatus::REJECTED ? 'rejected' : 'returned')) }}">
                            <!-- Card Header -->
                            <div class="request-card-header">
                                <div class="book-info">
                                    <div class="book-avatar">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <div class="book-details">
                                        <h5>{{ $request->book->title }}</h5>
                                        <p>par {{ $request->book->author }}</p>
                                    </div>
                                </div>
                                <div class="status-badge status-{{ $request->status->value }}">
                                    {{ $request->status->label() }}
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="request-card-body">
                                <div class="request-details">
                                    <div class="detail-item">
                                        <i class="fas fa-user text-muted me-2"></i>
                                        <span class="detail-label">Propriétaire:</span>
                                        <span class="detail-value">{{ $request->book->owner->name }}</span>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <i class="fas fa-calendar-start text-success me-2"></i>
                                        <span class="detail-label">Du:</span>
                                        <span class="detail-value">{{ $request->start_date->format('d/m/Y') }}</span>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <i class="fas fa-calendar-check text-danger me-2"></i>
                                        <span class="detail-label">Au:</span>
                                        <span class="detail-value">{{ $request->end_date->format('d/m/Y') }}</span>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <i class="fas fa-clock text-info me-2"></i>
                                        <span class="detail-label">Durée:</span>
                                        <span class="detail-value">{{ $request->getDurationInDays() }} jour(s)</span>
                                    </div>

                                    @if($request->status === App\Enums\RequestStatus::APPROVED && $request->isOverdue())
                                        <div class="alert alert-danger alert-sm mt-2">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <strong>Livre en retard!</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Actions -->
                            <div class="request-card-actions">
                                @if($request->isPending())
                                    <form action="{{ route('borrow-requests.cancel', $request) }}" method="POST" class="d-inline" 
                                          onsubmit="return confirm('Voulez-vous annuler cette demande ?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-times me-1"></i>
                                            Annuler
                                        </button>
                                    </form>
                                @elseif($request->status === App\Enums\RequestStatus::APPROVED)
                                    <form action="{{ route('borrow-requests.return', $request) }}" method="POST" class="d-inline" 
                                          onsubmit="return confirm('Confirmez-vous avoir rendu ce livre ?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-check me-1"></i>
                                            Marquer comme Rendu
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Demandes Reçues -->
        <div class="tab-pane fade" id="received-requests" role="tabpanel">
            @if($receivedRequests->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h4>Aucune demande reçue</h4>
                    <p>Vous n'avez reçu aucune demande d'emprunt pour vos livres.</p>
                </div>
            @else
                <div class="requests-grid">
                    @foreach($receivedRequests as $request)
                        <div class="request-card {{ $request->isPending() ? 'pending' : ($request->status === App\Enums\RequestStatus::APPROVED ? 'approved' : ($request->status === App\Enums\RequestStatus::REJECTED ? 'rejected' : 'returned')) }}">
                            <!-- Card Header -->
                            <div class="request-card-header">
                                <div class="book-info">
                                    <div class="book-avatar owner">
                                        <i class="fas fa-crown"></i>
                                    </div>
                                    <div class="book-details">
                                        <h5>{{ $request->book->title }}</h5>
                                        <p>par {{ $request->book->author }}</p>
                                        <small class="text-success"><i class="fas fa-check-circle me-1"></i>Votre livre</small>
                                    </div>
                                </div>
                                <div class="status-badge status-{{ $request->status->value }}">
                                    {{ $request->status->label() }}
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="request-card-body">
                                <div class="request-details">
                                    <div class="detail-item">
                                        <i class="fas fa-user-circle text-primary me-2"></i>
                                        <span class="detail-label">Demandeur:</span>
                                        <span class="detail-value">{{ $request->borrower->name }}</span>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <i class="fas fa-envelope text-muted me-2"></i>
                                        <span class="detail-label">Email:</span>
                                        <span class="detail-value">{{ $request->borrower->email }}</span>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <i class="fas fa-calendar-start text-success me-2"></i>
                                        <span class="detail-label">Du:</span>
                                        <span class="detail-value">{{ $request->start_date->format('d/m/Y') }}</span>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <i class="fas fa-calendar-check text-danger me-2"></i>
                                        <span class="detail-label">Au:</span>
                                        <span class="detail-value">{{ $request->end_date->format('d/m/Y') }}</span>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <i class="fas fa-clock text-info me-2"></i>
                                        <span class="detail-label">Durée:</span>
                                        <span class="detail-value">{{ $request->getDurationInDays() }} jour(s)</span>
                                    </div>

                                    <div class="detail-item">
                                        <i class="fas fa-calendar-plus text-warning me-2"></i>
                                        <span class="detail-label">Demandé:</span>
                                        <span class="detail-value">{{ $request->created_at->diffForHumans() }}</span>
                                    </div>

                                    @if($request->status === App\Enums\RequestStatus::APPROVED && $request->isOverdue())
                                        <div class="alert alert-warning alert-sm mt-2">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <strong>Livre en retard chez l'emprunteur!</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Actions -->
                            <div class="request-card-actions">
                                @if($request->isPending())
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <form action="{{ route('borrow-requests.approve', $request) }}" method="POST" class="w-100" 
                                                  onsubmit="return confirm('✅ Approuver cette demande d\'emprunt ?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success w-100 fw-bold">
                                                    <i class="fas fa-check-circle me-2"></i>
                                                    ✅ APPROUVER
                                                </button>
                                            </form>
                                        </div>
                                        <div class="col-6">
                                            <form action="{{ route('borrow-requests.reject', $request) }}" method="POST" class="w-100" 
                                                  onsubmit="return confirm('❌ Refuser cette demande d\'emprunt ?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-danger w-100 fw-bold">
                                                    <i class="fas fa-times-circle me-2"></i>
                                                    ❌ REFUSER
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-center">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Cette demande nécessite votre réponse
                                        </small>
                                    </div>
                                @elseif($request->status === App\Enums\RequestStatus::APPROVED)
                                    <div class="alert alert-info alert-sm mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Livre actuellement emprunté
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Dashboard Styles */
.dashboard-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 15px;
    margin-bottom: 2rem;
}

.dashboard-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.dashboard-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin: 0;
}

/* Statistics Cards */
.stats-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border: none;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.stats-card-primary {
    border-left: 5px solid #667eea;
}

.stats-card-warning {
    border-left: 5px solid #f093fb;
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stats-card-primary .stats-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stats-card-warning .stats-icon {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.stats-info h3 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    color: #2d3748;
}

.stats-info p {
    margin: 0;
    color: #718096;
    font-weight: 500;
}

/* Modern Navigation Pills */
.modern-tabs-container {
    background: white;
    border-radius: 15px;
    padding: 1rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.modern-nav-pills {
    gap: 0.5rem;
}

.modern-nav-pills .nav-link {
    background: transparent;
    border: 2px solid #e2e8f0;
    color: #64748b;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.modern-nav-pills .nav-link:hover {
    background: #f8fafc;
    border-color: #cbd5e0;
    transform: translateY(-2px);
}

.modern-nav-pills .nav-link.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.modern-nav-pills .nav-link .badge {
    background: rgba(255,255,255,0.2);
    color: inherit;
    border-radius: 20px;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.modern-nav-pills .nav-link.active .badge {
    background: rgba(255,255,255,0.3);
    color: white;
}

/* Request Cards Grid */
.requests-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
}

.request-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: 2px solid transparent;
    overflow: hidden;
}

.request-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.request-card.pending {
    border-color: #f59e0b;
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.2);
}

.request-card.approved {
    border-color: #10b981;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
}

.request-card.rejected {
    border-color: #ef4444;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.2);
}

.request-card.returned {
    border-color: #6366f1;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.2);
}

/* Card Header */
.request-card-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.book-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.book-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.book-avatar.owner {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.book-details h5 {
    margin: 0 0 0.25rem 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e293b;
}

.book-details p {
    margin: 0;
    color: #64748b;
    font-size: 0.9rem;
}

.book-details small {
    font-size: 0.8rem;
}

/* Status Badge */
.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-approved {
    background: #d1fae5;
    color: #065f46;
}

.status-rejected {
    background: #fee2e2;
    color: #991b1b;
}

.status-returned {
    background: #e0e7ff;
    color: #3730a3;
}

/* Card Body */
.request-card-body {
    padding: 1.5rem;
}

.request-details {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.detail-label {
    font-weight: 600;
    color: #374151;
    min-width: 80px;
}

.detail-value {
    color: #6b7280;
}

/* Card Actions */
.request-card-actions {
    padding: 1rem 1.5rem;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.empty-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e0 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2rem;
    color: #64748b;
}

.empty-state h4 {
    color: #1e293b;
    margin-bottom: 1rem;
}

.empty-state p {
    color: #64748b;
    margin-bottom: 2rem;
}

/* Alert Modifications */
.alert-sm {
    padding: 0.5rem 0.75rem;
    font-size: 0.85rem;
}

/* Button Improvements */
.btn {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

/* Action Buttons Special Styling */
.btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border: none;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.btn-success:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

.btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    border: none;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.btn-danger:hover {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
}

/* Pending request highlight */
.request-card.pending .request-card-actions {
    background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
    border-top: 2px solid #f59e0b;
}

.request-card.pending {
    animation: pulse-border 2s infinite;
}

@keyframes pulse-border {
    0% { border-color: #f59e0b; }
    50% { border-color: #f97316; }
    100% { border-color: #f59e0b; }
}

/* Responsive */
@media (max-width: 768px) {
    .requests-grid {
        grid-template-columns: 1fr;
    }
    
    .dashboard-title {
        font-size: 2rem;
    }
    
    .stats-card {
        margin-bottom: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Bootstrap Pills functionality
    const triggerTabList = [].slice.call(document.querySelectorAll('#borrowTabs button'))
    triggerTabList.forEach(function (triggerEl) {
        const tabTrigger = new bootstrap.Tab(triggerEl)
        
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault()
            tabTrigger.show()
        })
    })

    // Auto refresh every 60 seconds
    setTimeout(() => {
        location.reload()
    }, 60000)

    // Add loading states to form submissions
    const forms = document.querySelectorAll('form')
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]')
            if (submitBtn) {
                submitBtn.disabled = true
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Traitement...'
            }
        })
    })
})
</script>
</x-layouts.app>