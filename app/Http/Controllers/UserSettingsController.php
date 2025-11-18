<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSettingsController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'theme' => 'nullable|in:light,dark,auto',
            'language' => 'nullable|in:fr,en,es',
            'email_notifications' => 'nullable|boolean',
            'push_notifications' => 'nullable|boolean',
        ]);

        $user = Auth::user();

        // Update user preferences (you might want to create a user_preferences table or use JSON column)
        $preferences = $user->preferences ?? [];

        $preferences['theme'] = $request->theme ?? 'auto';
        $preferences['language'] = $request->language ?? 'fr';
        $preferences['email_notifications'] = $request->boolean('email_notifications', true);
        $preferences['push_notifications'] = $request->boolean('push_notifications', true);

        $user->preferences = $preferences;
        $user->save();

        return back()->with('success', 'Paramètres sauvegardés avec succès.');
    }
}
