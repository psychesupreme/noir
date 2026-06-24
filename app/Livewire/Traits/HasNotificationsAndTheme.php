<?php

namespace App\Livewire\Traits;

use App\Models\Notification;

trait HasNotificationsAndTheme
{
    public array $notificationsList = [];
    public int $unreadNotificationsCount = 0;

    public function bootHasNotificationsAndTheme(): void
    {
        $this->loadNotifications();
    }

    public function loadNotifications(): void
    {
        $user = auth()->user();
        if ($user) {
            $notifs = Notification::where('user_id', $user->id)
                ->orWhereNull('user_id')
                ->latest()
                ->get();
                
            $this->notificationsList = $notifs->toArray();
            $this->unreadNotificationsCount = $notifs->where('is_read', false)->count();
        } else {
            $this->notificationsList = [];
            $this->unreadNotificationsCount = 0;
        }
    }

    public function markNotificationAsRead(int $id): void
    {
        $notification = Notification::find($id);
        if ($notification && (is_null($notification->user_id) || $notification->user_id === auth()->id())) {
            $notification->update(['is_read' => true]);
            
            // Log to system logs
            \App\Models\SystemLog::write('info', 'system', "User marked notification ID {$id} as read.", [
                'notification_id' => $id,
                'user_id' => auth()->id()
            ]);

            $this->loadNotifications();
        }
    }

    public function deleteNotification(int $id): void
    {
        $notification = Notification::find($id);
        if ($notification && (is_null($notification->user_id) || $notification->user_id === auth()->id())) {
            $notification->delete();
            
            // Log to system logs
            \App\Models\SystemLog::write('info', 'system', "User deleted notification ID {$id}.", [
                'notification_id' => $id,
                'user_id' => auth()->id()
            ]);

            $this->loadNotifications();
        }
    }

    public function markAllAsSeen(): void
    {
        $user = auth()->user();
        if ($user) {
            Notification::where('user_id', $user->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
            
            // Log to system logs
            \App\Models\SystemLog::write('info', 'system', "User marked all notifications as read.", [
                'user_id' => $user->id
            ]);

            $this->loadNotifications();
        }
    }

    public function updatePreferredTheme(string|array $theme): void
    {
        $user = auth()->user();
        if ($user) {
            if (is_array($theme)) {
                $theme = isset($theme[0]) ? (string)$theme[0] : (isset($theme['theme']) ? (string)$theme['theme'] : 'light');
            }
            $theme = ($theme === 'dark' || $theme === 'onyx') ? 'dark' : 'light';

            $settings = $user->settings ?? [];
            $settings['preferred_theme'] = $theme;
            $user->update(['settings' => $settings]);
            
            // Log to system logs
            \App\Models\SystemLog::write('info', 'system', "User updated preferred theme to {$theme} site-wide.", [
                'theme' => $theme,
                'user_id' => $user->id
            ]);
        }
    }
}
