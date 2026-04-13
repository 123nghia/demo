<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Page;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pages' => Page::query()->count(),
            'published_pages' => Page::query()->where('is_published', true)->count(),
            'messages' => ContactMessage::query()->count(),
            'unread_messages' => ContactMessage::query()->where('is_read', false)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
