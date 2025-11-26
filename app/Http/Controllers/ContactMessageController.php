<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminReplyMail;

class ContactMessageController extends Controller
{
    /**
     * Show list of contact messages for admin.
     */
    public function index()
    {
        $messages = ContactMessage::orderByDesc('created_at')->paginate(15);
        return view('admin.contact_messages.index', compact('messages'));
    }

    /**
     * Show a specific contact message.
     */
    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);
        return view('admin.contact_messages.show', compact('message'));
    }
    
    /**
     * Handle admin reply to a contact message.
     */
    public function reply(Request $request, $id)
    {
        $message = ContactMessage::findOrFail($id);
        
        $request->validate([
            'reply' => 'required|string|min:1',
        ]);

        $replyContent = $request->input('reply');

        // Send email to the client
        Mail::to($message->email)->send(new AdminReplyMail($message, $replyContent));
        
        return redirect()->route('admin.contact_messages.show', $id)
            ->with('success', 'Réponse envoyée avec succès au client.');
    }
}
