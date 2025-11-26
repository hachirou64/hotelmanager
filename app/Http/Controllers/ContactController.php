<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    /**
     * Handle contact form submission.
     */
    public function submit(Request $request)
    {
        // Validate input data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // Save message to database
        ContactMessage::create($data);

        // Optionally send notification email to admin
        try {
            Mail::raw("Message de : {$data['name']} ({$data['email']})\n\nMessage:\n{$data['message']}", function ($message) use ($data) {
                $message->to('contact@hotelmanager.com')
                        ->subject('Nouveau message via formulaire de contact');
            });
        } catch (\Exception $e) {
            // Log error or ignore for now
        }

        // Redirect back with success message
        return redirect()->back()->with('success', 'Merci pour votre message. Nous vous répondrons rapidement.');
    }
}
