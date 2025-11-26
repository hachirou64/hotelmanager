import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';

const ReservationForm = ({ roomId }) => {
    const [room, setRoom] = useState(null);
    const [formData, setFormData] = useState({
        date_debut: window.initialDates?.date_debut || '',
        date_fin: window.initialDates?.date_fin || '',
        demandes_speciales: ''
    });

    // Update min for date_fin when date_debut changes
    useEffect(() => {
        if (formData.date_debut) {
            const dateFinInput = document.getElementById('date_fin');
            if (dateFinInput) {
                dateFinInput.min = formData.date_debut;
            }
        }
    }, [formData.date_debut]);
    const [loading, setLoading] = useState(true);
    const [submitting, setSubmitting] = useState(false);
    const [error, setError] = useState(null);
    const navigate = useNavigate();

    useEffect(() => {
        if (roomId) {
            fetchRoom();
        }
    }, [roomId]);

    const fetchRoom = async () => {
        try {
            const response = await axios.get(`/api/public/rooms/${roomId}`);
            setRoom(response.data);
        } catch (err) {
            setError('Erreur lors du chargement de la chambre');
            console.error(err);
        } finally {
            setLoading(false);
        }
    };

    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({
            ...prev,
            [name]: value
        }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setSubmitting(true);
        setError(null);

        try {
            const reservationData = {
                id_chambre: roomId,
                ...formData
            };

            await axios.post('/api/client/reservations', reservationData);
            navigate('/client-reservations');
        } catch (err) {
            if (err.response && err.response.data) {
                if (err.response.data.error) {
                    setError(err.response.data.error);
                } else if (err.response.data.errors) {
                    // Handle validation errors
                    const errorMessages = Object.values(err.response.data.errors).flat();
                    setError(errorMessages.join(' '));
                } else {
                    setError('Erreur lors de la création de la réservation');
                }
            } else {
                setError('Erreur lors de la création de la réservation');
            }
            console.error(err);
        } finally {
            setSubmitting(false);
        }
    };

    if (loading) return <div className="flex justify-center items-center h-64"><div className="animate-spin rounded-full h-32 w-32 border-b-2 border-blue-500"></div></div>;
    if (error && !room) return <div className="text-red-500 text-center">{error}</div>;

    return (
        <div className="container mx-auto px-4 py-8 max-w-2xl">
            <h1 className="text-3xl font-bold mb-8">Réserver une Chambre</h1>

            {room && (
                <div className="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 className="text-xl font-semibold mb-4">Chambre {room.numero_chambre}</h2>
                    <p className="text-gray-600 mb-2">{room.roomType?.nom_type}</p>
                    <p className="text-gray-600">Capacité: {room.capacite_max} personnes</p>
                </div>
            )}

            <form onSubmit={handleSubmit} className="bg-white rounded-lg shadow-md p-6">
                <div className="mb-4">
                    <label htmlFor="date_debut" className="block text-sm font-medium text-gray-700 mb-2">
                        Date d'arrivée
                    </label>
                    <input
                        type="date"
                        id="date_debut"
                        name="date_debut"
                        value={formData.date_debut}
                        onChange={handleInputChange}
                        min={new Date().toISOString().split('T')[0]}
                        className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    />
                </div>

                <div className="mb-4">
                    <label htmlFor="date_fin" className="block text-sm font-medium text-gray-700 mb-2">
                        Date de départ
                    </label>
                    <input
                        type="date"
                        id="date_fin"
                        name="date_fin"
                        value={formData.date_fin}
                        onChange={handleInputChange}
                        min={formData.date_debut || new Date().toISOString().split('T')[0]}
                        className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    />
                </div>

                <div className="mb-6">
                    <label htmlFor="demandes_speciales" className="block text-sm font-medium text-gray-700 mb-2">
                        Demandes spéciales (optionnel)
                    </label>
                    <textarea
                        id="demandes_speciales"
                        name="demandes_speciales"
                        value={formData.demandes_speciales}
                        onChange={handleInputChange}
                        rows="4"
                        className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Ex: Lit supplémentaire, vue sur mer, etc."
                    />
                </div>

                {error && <div className="text-red-500 mb-4">{error}</div>}

                <div className="flex gap-4">
                    <button
                        type="button"
                        onClick={() => navigate('/public-rooms')}
                        className="flex-1 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Annuler
                    </button>
                    <button
                        type="submit"
                        disabled={submitting}
                        className="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                    >
                        {submitting ? 'Réservation en cours...' : 'Confirmer la Réservation'}
                    </button>
                </div>
            </form>
        </div>
    );
};

export default ReservationForm;
