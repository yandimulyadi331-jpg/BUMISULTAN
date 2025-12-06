<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Informasi;
use App\Models\InformasiRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InformasiApiController extends Controller
{
    /**
     * Get unread informasi for current user
     */
    public function getUnreadInformasi(Request $request)
    {
        $userId = Auth::id();
        
        $informasi = Informasi::active()
            ->unreadByUser($userId)
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'konten' => $item->konten,
                    'tipe' => $item->tipe,
                    'banner_url' => $item->banner_path ? getBannerInformasi($item->banner_path) : null,
                    'link_url' => $item->link_url,
                    'priority' => $item->priority,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $informasi
        ]);
    }

    /**
     * Mark informasi as read
     */
    public function markAsRead(Request $request, $id)
    {
        $userId = Auth::id();
        
        $informasi = Informasi::findOrFail($id);

        // Check if already read
        $alreadyRead = InformasiRead::where('informasi_id', $id)
            ->where('user_id', $userId)
            ->exists();

        if (!$alreadyRead) {
            InformasiRead::create([
                'informasi_id' => $id,
                'user_id' => $userId,
                'read_at' => now()
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Informasi ditandai sebagai sudah dibaca'
        ]);
    }

    /**
     * Get all informasi for current user (always show)
     */
    public function getAllInformasi(Request $request)
    {
        $informasi = Informasi::active()
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'konten' => $item->konten,
                    'tipe' => $item->tipe,
                    'banner_url' => $item->banner_path ? getBannerInformasi($item->banner_path) : null,
                    'link_url' => $item->link_url,
                    'priority' => $item->priority
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $informasi
        ]);
    }
}
