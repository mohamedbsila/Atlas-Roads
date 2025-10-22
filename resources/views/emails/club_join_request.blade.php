<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>New Club Join Request</title>
    <style>
        body { font-family: Arial, sans-serif; color: #1f2937; }
        .container { max-width: 640px; margin: 0 auto; padding: 24px; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; }
        .title { font-size: 20px; font-weight: 700; margin-bottom: 16px; }
        .section { margin-top: 16px; }
        .muted { color: #6b7280; }
        .badge { display: inline-block; padding: 4px 10px; background: #eff6ff; color: #1d4ed8; border-radius: 9999px; font-size: 12px; font-weight: 600; }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">New Club Join Request</div>
        <p>
            A user has requested to join the club <strong>{{ $club->name }}</strong>.
        </p>

        <div class="section">
            <span class="badge">Applicant</span>
            <p><strong>Name:</strong> {{ $user->name ?? $user->email }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
        </div>

        <div class="section">
            <span class="badge">Club</span>
            <p><strong>Club:</strong> {{ $club->name }}</p>
        </div>

        @if(!empty($messageText))
            <div class="section">
                <span class="badge">Message</span>
                <p class="muted">{{ $messageText }}</p>
            </div>
        @endif

        <div class="section muted">
            <p>This is an automated notification from Atlas Roads.</p>
        </div>
    </div>
</body>
</html>
