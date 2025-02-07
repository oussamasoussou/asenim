<?php

namespace App\Http\Controllers;

use App\Models\ChatGroupe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatGroupeController extends Controller
{
    public function index()
    {
        $userConnected = Auth::user();
        $connectedUsers = User::where('user_connected', 1)->get(['id', 'first_name', 'last_name']);

        $userId = auth()->id(); // ID de l'utilisateur connecté

        // Récupérer tous les messages avec les informations sur l'utilisateur
        $messages = ChatGroupe::with('user')->get();

        // Retourner la vue avec les utilisateurs connectés
        return view('pages.chat.chat', compact('connectedUsers', 'userId', 'messages','userConnected'));
    }
    
    public function sendMessage(Request $request)
    {
        // Récupérer les utilisateurs connectés
        $connectedUsers = User::where('user_connected', 1)->get(['id', 'first_name', 'last_name']);
    
        // Récupérer l'ID de l'utilisateur connecté
        $userId = auth()->id();
    
        // Récupérer tous les messages avec l'utilisateur associé
        $messages = ChatGroupe::with('user')->get();
    
        // Validation des données
        $validated = $request->validate([
            'content' => 'nullable|string', 
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx,txt|max:2048', 
        ]);
    
        // Préparer les données du message
        $chatData = [
            'user_id' => $userId, 
            'content' => $validated['content'] ?? null, 
        ];
    
        // Gestion du fichier s'il existe
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('messages', $fileName, 'public');
            $mimeType = $file->getClientMimeType();
    
            // Ajouter les informations sur le fichier
            $chatData['file_path'] = $filePath;
            $chatData['file_name'] = $fileName;
            $chatData['file_type'] = str_starts_with($mimeType, 'image/') ? 'image' : 'file';
        }
    
        // Enregistrement du message
        $chat = ChatGroupe::create($chatData);
    
        // Diffuser l'événement pour la mise à jour en temps réel
    
        return response()->json([
            'message' => $chat,
            'userId' => $userId,
            'connectedUsers' => $connectedUsers,
            'messages' => $messages
        ]);
    }

    public function fetchMessages()
    {
        return ChatGroupe::with('user')->get();
    }
}
