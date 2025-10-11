<x-layouts.base>
    @push('styles')
        <style>
            /* Event Details Page Styling */
            .event-details-section {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                padding: 60px 0;
                position: relative;
                overflow: hidden;
            }
            
            .event-details-section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            }
            
            .event-details-container {
                position: relative;
                z-index: 1;
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 20px;
            }
            
            .event-details-card {
                background: white;
                border-radius: 20px;
                overflow: hidden;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            }
            
            .event-details-header {
                position: relative;
                height: 400px;
                overflow: hidden;
            }
            
            .event-details-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            
            .event-details-date-badge {
                position: absolute;
                top: 24px;
                left: 24px;
                background: rgba(255, 255, 255, 0.95);
                border-radius: 12px;
                padding: 16px 20px;
                text-align: center;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
                backdrop-filter: blur(10px);
            }
            
            .event-details-day {
                display: block;
                font-size: 2.5rem;
                font-weight: 800;
                color: #4f46e5;
                line-height: 1;
            }
            
            .event-details-month {
                display: block;
                font-size: 0.875rem;
                font-weight: 700;
                color: #6b7280;
                text-transform: uppercase;
                margin-top: 4px;
                letter-spacing: 0.5px;
            }
            
            .event-details-content {
                padding: 48px;
            }
            
            .event-details-title {
                font-size: 2.5rem;
                font-weight: 800;
                color: #1f2937;
                margin-bottom: 24px;
                line-height: 1.2;
            }
            
            .event-details-meta {
                display: flex;
                flex-wrap: wrap;
                gap: 24px;
                margin-bottom: 32px;
                padding-bottom: 32px;
                border-bottom: 2px solid #f3f4f6;
            }
            
            .event-meta-item {
                display: flex;
                align-items: center;
                gap: 12px;
            }
            
            .event-meta-icon {
                width: 48px;
                height: 48px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.25rem;
            }
            
            .event-meta-text {
                flex: 1;
            }
            
            .event-meta-label {
                display: block;
                font-size: 0.75rem;
                font-weight: 600;
                color: #9ca3af;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 4px;
            }
            
            .event-meta-value {
                display: block;
                font-size: 1rem;
                font-weight: 600;
                color: #1f2937;
            }
            
            .event-details-description {
                font-size: 1.125rem;
                line-height: 1.8;
                color: #4b5563;
                margin-bottom: 40px;
            }
            
            .event-details-actions {
                display: flex;
                gap: 16px;
                flex-wrap: wrap;
            }
            
            .event-action-button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 14px 32px;
                border-radius: 12px;
                font-weight: 700;
                font-size: 1rem;
                transition: all 0.3s ease;
                text-decoration: none;
            }
            
            .event-action-primary {
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
                color: white;
                box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
            }
            
            .event-action-primary:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
                color: white;
            }
            
            .event-action-secondary {
                background: white;
                color: #4f46e5;
                border: 2px solid #e5e7eb;
            }
            
            .event-action-secondary:hover {
                background: #f9fafb;
                border-color: #4f46e5;
                transform: translateY(-2px);
            }
            
            /* Responsive Design */
            @media (max-width: 768px) {
                .event-details-header {
                    height: 300px;
                }
                
                .event-details-content {
                    padding: 32px 24px;
                }
                
                .event-details-title {
                    font-size: 2rem;
                }
                
                .event-details-meta {
                    flex-direction: column;
                    gap: 16px;
                }
                
                .event-details-actions {
                    flex-direction: column;
                }
                
                .event-action-button {
                    width: 100%;
                }
            }
        </style>
    @endpush

    <div class="event-details-section">
        <div class="event-details-container">
            <!-- Back Navigation -->
            <div class="mb-6">
                <a href="{{ route('home') }}#events" class="inline-flex items-center text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span class="font-semibold">Back to Events</span>
                </a>
            </div>

            <!-- Event Details Card -->
            <div class="event-details-card">
                <!-- Event Header with Image -->
                <div class="event-details-header">
                    @if($event->thumbnail)
                        <img src="{{ asset('storage/' . $event->thumbnail) }}" 
                             alt="{{ $event->title }}" 
                             class="event-details-image"
                             onerror="this.src='{{ asset('assets/img/curved-images/curved14.jpg') }}'">
                    @else
                        <img src="{{ asset('assets/img/curved-images/curved14.jpg') }}" 
                             alt="{{ $event->title }}" 
                             class="event-details-image">
                    @endif
                    
                    <!-- Date Badge -->
                    <div class="event-details-date-badge">
                        @php
                            $eventDate = $event->start_date ? \Carbon\Carbon::parse($event->start_date) : now();
                            $day = $eventDate->format('d');
                            $month = $eventDate->format('M');
                        @endphp
                        <span class="event-details-day">{{ $day }}</span>
                        <span class="event-details-month">{{ $month }}</span>
                    </div>
                </div>

                <!-- Event Content -->
                <div class="event-details-content">
                    <!-- Title -->
                    <h1 class="event-details-title">{{ $event->title }}</h1>

                    <!-- Meta Information -->
                    <div class="event-details-meta">
                        <!-- Date & Time -->
                        <div class="event-meta-item">
                            <div class="event-meta-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="event-meta-text">
                                <span class="event-meta-label">Date & Time</span>
                                <span class="event-meta-value">
                                    @if($event->start_date)
                                        {{ \Carbon\Carbon::parse($event->start_date)->format('l, F j, Y') }}
                                        <br>
                                        {{ \Carbon\Carbon::parse($event->start_date)->format('g:i A') }}
                                        @if($event->end_date)
                                            - {{ \Carbon\Carbon::parse($event->end_date)->format('g:i A') }}
                                        @endif
                                    @else
                                        Date TBA
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="event-meta-item">
                            <div class="event-meta-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="event-meta-text">
                                <span class="event-meta-label">Location</span>
                                <span class="event-meta-value">{{ $event->location ?? 'TBD' }}</span>
                            </div>
                        </div>

                        <!-- Organizer -->
                        <div class="event-meta-item">
                            <div class="event-meta-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="event-meta-text">
                                <span class="event-meta-label">Organizer</span>
                                <span class="event-meta-value">{{ $organizer ? $organizer->name : 'Unknown' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="event-details-description">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">About This Event</h2>
                        <p>{{ $event->description }}</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="event-details-actions">
                        @can('update', $event)
                            <a href="{{ route('events.edit', $event->id) }}" class="event-action-button event-action-primary">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Event
                            </a>
                        @endcan
                        
                        <a href="{{ route('home') }}#events" class="event-action-button event-action-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Events
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.base>