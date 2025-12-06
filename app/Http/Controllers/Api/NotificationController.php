<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RealTimeNotification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Get notifikasi hari ini untuk dashboard
     */
    public function getTodayNotifications(Request $request)
    {
        try {
            $limit = $request->get('limit', 50);
            $type = $request->get('type', null);
            
            $query = RealTimeNotification::today()->latest();
            
            if ($type) {
                $query->byType($type);
            }
            
            $notifications = $query->limit($limit)->get();
            
            // Transform data untuk frontend
            $data = $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'icon' => $notification->icon,
                    'color' => $notification->color,
                    'data' => $notification->data,
                    'time_format' => $notification->time_format,
                    'time_ago' => $notification->time_ago,
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at->toDateTimeString()
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'total' => $notifications->count(),
                'last_updated' => now()->toDateTimeString()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching notifications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistik notifikasi hari ini
     */
    public function getTodayStats()
    {
        try {
            $stats = NotificationService::getTodayStats();
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching stats: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        try {
            $notification = RealTimeNotification::find($id);
            
            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found'
                ], 404);
            }
            
            $notification->update(['is_read' => true]);
            
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error marking notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        try {
            RealTimeNotification::today()
                ->where('is_read', false)
                ->update(['is_read' => true]);
            
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error marking notifications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get notifikasi by type
     */
    public function getByType($type)
    {
        try {
            $notifications = RealTimeNotification::today()
                ->byType($type)
                ->latest()
                ->limit(20)
                ->get();
                
            $data = $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'icon' => $notification->icon,
                    'color' => $notification->color,
                    'data' => $notification->data,
                    'time_format' => $notification->time_format,
                    'time_ago' => $notification->time_ago,
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at->toDateTimeString()
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'total' => $notifications->count()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching notifications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test endpoint untuk membuat notifikasi dummy
     */
    public function createTestNotification()
    {
        try {
            $notification = NotificationService::customNotification(
                'Test Notification',
                'This is a test notification created at ' . now()->format('H:i:s'),
                'system',
                [
                    'icon' => 'ti ti-test-pipe',
                    'color' => 'info',
                    'data' => ['test' => true, 'timestamp' => now()]
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Test notification created',
                'data' => $notification
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating test notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get notification count (total and unread)
     */
    public function getCount()
    {
        try {
            $todayNotifications = RealTimeNotification::today();
            $total = $todayNotifications->count();
            $unread = $todayNotifications->unread()->count();
            
            return response()->json([
                'success' => true,
                'total_count' => $total,
                'unread_count' => $unread,
                'read_count' => $total - $unread
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting notification count: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clean up old notifications (untuk cron job)
     */
    public function cleanupOldNotifications()
    {
        try {
            $deleted = NotificationService::cleanupOldNotifications();
            
            return response()->json([
                'success' => true,
                'message' => "Cleaned up {$deleted} old notifications"
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error cleaning up notifications: ' . $e->getMessage()
            ], 500);
        }
    }
}
