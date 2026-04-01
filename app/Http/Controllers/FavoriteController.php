<?php

namespace App\Http\Controllers;

use App\Models\Verb;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class FavoriteController extends Controller
{
    public function toggle(Request $request, Verb $verb): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        if (! Schema::hasTable('favorites')) {
            return response()->json(['error' => 'Favorites table missing. Run migrations.'], 409);
        }

        $isFavorited = $user->favorites()->where('verbs.id', $verb->id)->exists();

        if ($isFavorited) {
            $user->favorites()->detach($verb->id);

            return response()->json(['favorited' => false]);
        }

        $user->favorites()->attach($verb->id);

        return response()->json(['favorited' => true]);
    }
}
