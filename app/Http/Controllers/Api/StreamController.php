<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Server;
use Illuminate\Http\Request;

class StreamController extends Controller
{
    // API untuk mendapatkan daftar server aktif
    public function getServers(Request $request)
    {
        $type = $request->query('type'); // 'movie' atau 'tv'

        $query = Server::where('is_active', true);

        if ($type) {
            $query->whereIn('type', [$type, 'both']);
        }

        $servers = $query->orderBy('sort_order', 'asc')->get([
            'id',
            'name',
            'key',
            'type'
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $servers
        ]);
    }

    // API untuk meracik Stream URL
    public function getStreamUrl(Request $request)
    {
        $validated = $request->validate([
            'media_type' => 'required|in:movie,tv',
            'tmdb_id' => 'required|integer',
            'server_key' => 'nullable|string',
            'season' => 'nullable|integer',
            'episode' => 'nullable|integer',
            'start_position' => 'nullable|integer',
        ]);

        $mediaType = $validated['media_type'];
        $tmdbId = $validated['tmdb_id'];
        $serverKey = $validated['server_key'] ?? 'vidstuck';
        $season = $validated['season'] ?? 1;
        $episode = $validated['episode'] ?? 1;
        $startPosition = $validated['start_position'] ?? 0;

        $server = Server::where('key', $serverKey)
            ->where('is_active', true)
            ->first();

        // Fallback jika server_key tidak ditemukan / nonaktif
        if (!$server) {
            $server = Server::where('is_active', true)->first();
        }

        if (!$server) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada server yang tersedia.'
            ], 404);
        }

        $pattern = ($mediaType === 'movie') ? $server->movie_url_pattern : $server->tv_url_pattern;

        // Replace placeholder dengan data konkret
        $playerUrl = str_replace(
            ['{tmdb_id}', '{season}', '{episode}', '{start}'],
            [$tmdbId, $season, $episode, $startPosition],
            $pattern
        );

        return response()->json([
            'status' => 'success',
            'data' => [
                'server_name' => $server->name,
                'server_key' => $server->key,
                'stream_url' => $playerUrl
            ]
        ]);
    }
}
