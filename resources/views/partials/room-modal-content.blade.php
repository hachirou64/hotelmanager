<div class="p-6 max-w-lg">
    <div class="flex justify-between items-start mb-4">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">Chambre {{ $room->numero_chambre ?? $room->numero ?? '—' }}</h2>
            <p class="text-sm text-gray-600">{{ optional($room->roomType)->nom_type ?? 'Type inconnu' }}</p>
        </div>
        <div class="text-right">
            <span class="text-sm text-gray-500">Statut: <strong>{{ $room->statut ?? '—' }}</strong></span>
        </div>
    </div>

    <div class="mb-4">
        <p class="text-gray-700">Capacité: {{ $room->capacite_max ?? '—' }}</p>
        <p class="text-gray-700">Prix: {{ optional($room->roomType)->prix_base ? number_format(optional($room->roomType)->prix_base, 2) . ' €' : '—' }}</p>
    </div>

    <div class="mb-4 text-sm text-gray-700">
        <p>{{ optional($room->roomType)->description ?? '' }}</p>
    </div>

    <div class="flex items-center justify-end space-x-2 mt-4">
        <a href="{{ route('rooms.show', [$room->id_chambre ?? $room->id ?? 0]) }}" class="inline-block px-4 py-2 text-sm bg-white border rounded text-primary hover:bg-gray-50">Voir la page complète</a>
        <a href="{{ route('book.room', [$room->id_chambre ?? $room->id ?? 0]) }}" class="inline-block px-4 py-2 text-sm bg-primary text-white rounded">Réserver</a>
    </div>
</div>
