@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Messages de contact</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Vue administrative — gérez et répondez rapidement aux demandes clients.</p>
        </div>

        <div class="flex items-center gap-3">
            <form method="GET" action="" class="flex items-center">
                <input type="search" name="q" placeholder="Rechercher par nom ou email" value="{{ request('q') }}" class="px-3 py-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-r-md">Rechercher</button>
            </form>
            <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">Retour</a>
        </div>
    </div>

    @if($messages->count())
        <div class="bg-white dark:bg-slate-800 shadow overflow-hidden sm:rounded-md">
            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($messages as $message)
                    <li class="px-4 py-4 sm:px-6 hover:bg-gray-50 dark:hover:bg-slate-700">
                        <div class="flex items-center justify-between space-x-3">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-100 font-semibold">{{ strtoupper(substr($message->name,0,1)) }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $message->name }}</p>
                                    <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-300 truncate">{{ $message->email }} • <span class="text-xs text-gray-400">{{ $message->created_at->diffForHumans() }}</span></p>
                                    <p class="mt-2 text-sm text-gray-700 dark:text-gray-200 truncate">{{ Str::limit($message->message, 120) }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.contact_messages.show', $message->id) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Voir</a>
                                <button type="button" onclick="toggleReply({{ $message->id }})" class="inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-sm leading-4 font-medium rounded-md hover:bg-gray-50">Répondre</button>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

    {{-- Inline reply forms (hidden by default) --}}
    @foreach($messages as $message)
        <div id="reply-box-{{ $message->id }}" class="reply-box-container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-2 hidden">
            <form method="POST" action="{{ route('admin.contact_messages.reply', $message->id) }}" class="bg-white dark:bg-slate-800 p-4 rounded-md shadow-sm">
                @csrf
                <label for="reply-{{ $message->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Réponse rapide à {{ $message->name }}</label>
                <textarea id="reply-{{ $message->id }}" name="reply" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-gray-600 dark:text-white"></textarea>
                <div class="mt-2 flex justify-end gap-2">
                    <button type="button" onclick="toggleReply({{ $message->id }})" class="px-3 py-2 bg-gray-100 rounded">Annuler</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Envoyer</button>
                </div>
            </form>
        </div>
    @endforeach

        <div class="mt-6 flex justify-end">
            {{ $messages->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-slate-800 p-8 rounded-lg shadow">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Aucun message reçu</h3>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Les visiteurs n'ont encore envoyé aucun message via le formulaire de contact.</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function toggleReply(id) {
        const el = document.getElementById('reply-box-' + id);
        if (!el) return;
        if (el.classList.contains('hidden')) {
            el.classList.remove('hidden');
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            el.classList.add('hidden');
        }
    }
</script>
@endpush
