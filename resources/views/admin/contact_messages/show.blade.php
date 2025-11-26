@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white dark:bg-slate-800 shadow rounded-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Détails du message</h2>
                <p class="text-sm text-gray-500 dark:text-gray-300">Message reçu le {{ $message->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.contact_messages.index') }}" class="px-3 py-2 bg-gray-100 dark:bg-slate-700 text-sm rounded-md hover:bg-gray-200">Retour</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6">
            <div class="md:col-span-1">
                <div class="bg-gray-50 dark:bg-slate-900 p-4 rounded">
                    <div class="flex items-center gap-3">
                        <div class="h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-700 dark:text-indigo-100 font-semibold">{{ strtoupper(substr($message->name,0,1)) }}</div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $message->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-300">{{ $message->email }}</p>
                        </div>
                    </div>

                    <dl class="mt-4 text-sm text-gray-600 dark:text-gray-300">
                        <dt class="font-medium">IP</dt>
                        <dd class="truncate">{{ $message->ip_address ?? '—' }}</dd>
                        <dt class="mt-2 font-medium">Sujet</dt>
                        <dd class="truncate">{{ $message->subject ?? 'Général' }}</dd>
                    </dl>
                </div>
            </div>

            <div class="md:col-span-2">
                <div class="prose dark:prose-invert max-w-none"> 
                    <h3 class="text-lg font-medium">Message</h3>
                    <div class="mt-2 p-4 bg-white dark:bg-slate-800 border border-gray-100 dark:border-gray-700 rounded">
                        <p class="whitespace-pre-line text-gray-800 dark:text-gray-200">{{ $message->message }}</p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mt-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('admin.contact_messages.reply', $message->id) }}" class="mt-6">
                    @csrf
                    <label for="reply" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Répondre au client</label>
                    <textarea id="reply" name="reply" rows="5" required class="mt-1 block w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-gray-600 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    @error('reply')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <div class="mt-4 flex items-center gap-3">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Envoyer la réponse</button>
                        <a href="mailto:{{ $message->email }}" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-slate-700 rounded-md">Ouvrir dans mail</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
