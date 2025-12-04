<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize()
    {
        // Allow for now; controller or middleware will gate access if needed
        return true;
    }

    public function rules()
    {
        return [
            // normalize to DB column names: id_chambre, date_debut, date_fin
            'id_chambre' => 'required|exists:rooms,id_chambre',
            'id_client' => 'nullable|exists:clients,id_client',
            // client details are optional for anonymous reservations
            'client_email' => 'nullable|email|max:255',
            'client_nom' => 'nullable|string|max:255',
            'client_prenom' => 'nullable|string|max:255',
            'client_telephone' => 'nullable|string|max:30',
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after:date_debut',
            'guests' => 'nullable|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'id_chambre.required' => 'La chambre est requise.',
            'date_debut.required' => 'La date d\'arrivée est requise.',
            'date_fin.required' => 'La date de départ est requise.',
            'client_email.required_without' => 'Veuillez fournir une adresse email ou vous connecter pour réserver.',
            'client_email.email' => 'L\'adresse email fournie est invalide.',
            'client_nom.required_with' => 'Veuillez renseigner votre nom lorsque vous fournissez une adresse email.',
            'client_prenom.required_with' => 'Veuillez renseigner votre prénom lorsque vous fournissez une adresse email.',
            'client_telephone.required_with' => 'Veuillez renseigner votre numéro de téléphone lorsque vous fournissez une adresse email.',
        ];
    }

    protected function prepareForValidation()
    {
        // accept both API/form naming and map to DB names
        $map = [];

        if ($this->has('room_id')) {
            $map['id_chambre'] = $this->input('room_id');
        }

        if ($this->has('id_chambre')) {
            $map['id_chambre'] = $this->input('id_chambre');
        }

        if ($this->has('checkin_date')) {
            $map['date_debut'] = $this->input('checkin_date');
        }

        if ($this->has('date_debut')) {
            $map['date_debut'] = $this->input('date_debut');
        }

        if ($this->has('checkout_date')) {
            $map['date_fin'] = $this->input('checkout_date');
        }

        if ($this->has('date_fin')) {
            $map['date_fin'] = $this->input('date_fin');
        }

        if ($this->has('client_id')) {
            $map['id_client'] = $this->input('client_id');
        }

        if ($this->has('id_client')) {
            $map['id_client'] = $this->input('id_client');
        }

        $this->merge($map);
    }
}
