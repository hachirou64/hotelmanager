@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Payer la réservation #{{ $reservation->id_reservation }}</h1>

    <p class="mb-4">Montant à payer: <strong>{{ number_format($amount, 2) }} €</strong> </p>

    <form method="POST" action="{{ route('reservations.pay', $reservation) }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Numéro de téléphone (MOMO)</label>
            <input type="text" name="telephone" value="@old('telephone')" class="mt-1 block w-full border-gray-300 rounded-md" required />
            @error('telephone') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Fournisseur MOMO</label>
            <select name="provider" class="mt-1 block w-full border-gray-300 rounded-md" required>
                <option value="mtn" {{ old('provider', 'mtn') == 'mtn' ? 'selected' : '' }}>MTN</option>
                <option value="moov" {{ old('provider', 'moov') == 'moov' ? 'selected' : '' }}>Moov</option>
                <option value="celtis" {{ old('provider', 'celtis') == 'celtis' ? 'selected' : '' }}>Celtis</option>
            </select>
            @error('provider') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Payer avec MOMO</button>
    </form>
</div>
@endsection
