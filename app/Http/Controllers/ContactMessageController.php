<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Support\Facades\Auth;

/**
 * Messages reçus via le formulaire « Contact » du site public.
 */
class ContactMessageController extends Controller
{
    public function index()
    {
        $userConnected = Auth::user();
        $filter = request('filter') === 'unread' ? 'unread' : 'all';
        $search = trim((string) request('search'));
        $perPage = max(1, min(100, (int) request('perPage', 5)));
        $page = max(1, (int) request('page', 1));

        $query = ContactMessage::query()
            ->when($filter === 'unread', fn ($q) => $q->unread())
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('subject', 'LIKE', "%{$search}%")
                        ->orWhere('message', 'LIKE', "%{$search}%");
                });
            });

        $total = $query->count();
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $totalPages);
        $messages = $query->latest()->skip(($page - 1) * $perPage)->take($perPage)->get();
        $unreadCount = ContactMessage::unread()->count();

        return view('pages.messages.index', compact('messages', 'userConnected', 'filter', 'page', 'totalPages', 'perPage', 'total', 'unreadCount'));
    }

    public function show(int $id)
    {
        $userConnected = Auth::user();
        $message = ContactMessage::findOrFail($id);

        if (!$message->isRead()) {
            $message->read_at = now();
            $message->save();
        }

        return view('pages.messages.show', compact('message', 'userConnected'));
    }

    public function markUnread(int $id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->read_at = null;
        $message->save();

        return redirect()->route('messages.index')->with('success', 'Message marqué comme non lu.');
    }

    public function destroy(int $id)
    {
        ContactMessage::findOrFail($id)->delete();

        return redirect()->route('messages.index')->with('success', 'Message supprimé.');
    }
}
