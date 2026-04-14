<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Page;
use App\Models\Project;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pages' => Page::query()->count(),
            'published_pages' => Page::query()->where('is_published', true)->count(),
            'projects' => Project::query()->count(),
            'published_projects' => Project::query()->where('is_published', true)->count(),
            'messages' => ContactMessage::query()->count(),
            'unread_messages' => ContactMessage::query()->where('is_read', false)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
