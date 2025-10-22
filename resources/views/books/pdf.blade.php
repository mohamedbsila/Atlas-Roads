<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $book->title }} - Book Information</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            padding: 40px;
            background: #fff;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .subtitle {
            font-size: 14px;
            color: #666;
        }
        
        .book-container {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .book-image-section {
            display: table-cell;
            width: 250px;
            vertical-align: top;
            padding-right: 30px;
        }
        
        .book-image {
            width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .book-details-section {
            display: table-cell;
            vertical-align: top;
        }
        
        .book-title {
            font-size: 28px;
            font-weight: bold;
            color: #1a202c;
            margin-bottom: 15px;
            line-height: 1.3;
        }
        
        .info-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        
        .info-table tr {
            border-bottom: 1px solid #e2e8f0;
        }
        
        .info-table td {
            padding: 12px 0;
            font-size: 14px;
        }
        
        .info-label {
            font-weight: bold;
            color: #4a5568;
            width: 140px;
        }
        
        .info-value {
            color: #1a202c;
        }
        
        .availability-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .badge-available {
            background-color: #4facfe;
            color: white;
        }
        
        .badge-unavailable {
            background-color: #cbd5e0;
            color: #4a5568;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            font-size: 12px;
            color: #718096;
        }
        
        .generated-date {
            margin-top: 10px;
            font-style: italic;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <div class="logo">Atlas Roads Library</div>
        <div class="subtitle">Book Information Document</div>
    </div>
    
    {{-- Book Container --}}
    <div class="book-container">
        {{-- Book Image --}}
        <div class="book-image-section">
            @if($book->image)
                <img src="{{ public_path('storage/' . $book->image) }}" 
                     alt="{{ $book->title }}" 
                     class="book-image"
                     onerror="this.src='{{ public_path('assets/img/curved-images/curved14.jpg') }}'">
            @else
                <img src="{{ public_path('assets/img/curved-images/curved14.jpg') }}" 
                     alt="Default book cover" 
                     class="book-image">
            @endif
        </div>
        
        {{-- Book Details --}}
        <div class="book-details-section">
            <h1 class="book-title">{{ $book->title }}</h1>
            
            <table class="info-table">
                <tr>
                    <td class="info-label">Author:</td>
                    <td class="info-value">{{ $book->author }}</td>
                </tr>
                <tr>
                    <td class="info-label">Category:</td>
                    <td class="info-value">
                        @php
                            $categoryName = 'Uncategorized';
                            if ($book->category_id && $book->relationLoaded('category')) {
                                $cat = $book->getRelation('category');
                                if ($cat) {
                                    $categoryName = $cat->category_name;
                                }
                            } elseif ($book->getAttribute('category')) {
                                $categoryName = $book->getAttribute('category');
                            }
                        @endphp
                        {{ $categoryName }}
                    </td>
                </tr>
                <tr>
                    <td class="info-label">Language:</td>
                    <td class="info-value">{{ $book->language }}</td>
                </tr>
                <tr>
                    <td class="info-label">Published Year:</td>
                    <td class="info-value">{{ $book->published_year }}</td>
                </tr>
                @if($book->isbn)
                <tr>
                    <td class="info-label">ISBN:</td>
                    <td class="info-value">{{ $book->isbn }}</td>
                </tr>
                @endif
                <tr>
                    <td class="info-label">Availability:</td>
                    <td class="info-value">
                        <span class="availability-badge {{ $book->is_available ? 'badge-available' : 'badge-unavailable' }}">
                            {{ $book->is_available ? '✓ Available' : 'Unavailable' }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    {{-- Footer --}}
    <div class="footer">
        <div>© {{ date('Y') }} Atlas Roads Library. All rights reserved.</div>
        <div class="generated-date">Generated on {{ now()->format('F d, Y \a\t H:i') }}</div>
    </div>
</body>
</html>

