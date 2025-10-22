<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\ClubMessage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ClubMessageController extends Controller
{
    public function index(Club $club): JsonResponse
    {
        $messages = ClubMessage::with(['user:id,name,email'])
            ->where('club_id', $club->id)
            ->orderBy('created_at', 'asc')
            ->take(200)
            ->get()
            ->map(function ($m) {
                return [
                    'id' => $m->id,
                    'user' => $m->user?->name ?? $m->user?->email,
                    'message' => $m->message,
                    'created_at' => $m->created_at->toDateTimeString(),
                ];
            });

        return response()->json(['data' => $messages]);
    }

    public function store(Request $request, Club $club): JsonResponse
    {
        $request->validate([
            'message' => ['required', 'string', 'max:500'],
        ]);

        $message = ClubMessage::create([
            'club_id' => $club->id,
            'user_id' => Auth::id(),
            'message' => $request->string('message'),
        ]);

        $message->load('user:id,name,email');

        return response()->json([
            'data' => [
                'id' => $message->id,
                'user' => $message->user?->name ?? $message->user?->email,
                'message' => $message->message,
                'created_at' => $message->created_at->toDateTimeString(),
            ]
        ], 201);
    }
}
