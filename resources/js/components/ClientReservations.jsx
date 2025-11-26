import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { useLocation } from 'react-router-dom';

const ClientReservations = () => {
    const [reservations, setReservations] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [editingReservation, setEditingReservation] = useState(null);
    const [editForm, setEditForm] = useState({
        date_debut: '',
        date_fin: '',
        demandes_speciales: ''
    });
    const location = useLocation();
    const successMessage = location.state?.message;

    useEffect(() => {
        fetchReservations();
    }, []);

    const fetchReservations = async () => {
        try {
            const response = await axios.get('/api/client/reservations');
            setReservations(response.data);
        } catch (err) {
            setError('Erreur lors du chargement des réservations');
            console.error(err);
        } finally {
            setLoading(false);
        }
    };

    const handleEdit = (reservation) => {
        setEditingReservation(reservation.id_reservation);
        setEditForm({
            date_debut: reservation.date_debut,
            date_fin: reservation.date_fin,
            demandes_speciales: reservation.demandes_speciales || ''
        });
    };

    const handleCancelEdit = () => {
        setEditingReservation(null);
        setEditForm({
            date_debut: '',
            date_fin: '',
            demandes_speciales: ''
        });
    };

    const handleUpdate = async (e) => {
        e.preventDefault();
        try {
            await axios.put(`/api/client/reservations/${editingReservation}`, editForm);
            fetchReservations();
            handleCancelEdit();
        } catch (err) {
            setError('Erreur lors de la mise à jour');
            console.error(err);
        }
    };

    const handleCancel = async (id) => {
        if (window.confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')) {
            try {
                await axios.put(`/api/client/reservations/${id}`, { statut: 'annulée' });
                fetchReservations();
            } catch (err) {
                setError('Erreur lors de l\'annulation');
                console.error(err);
            }
        }
    };

    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setEditForm(prev => ({
            ...prev,
            [name]: value
        }));
    };

    if (loading) return <div className="flex justify-center items-center h-64"><div className="animate-spin rounded-full h-32 w-32 border-b-2 border-blue-500"></div></div>;
    if (error) return <div className="text-red-500 text-center">{error}</div>;

    return (
        <div className="container mx-auto px-4 py-8">
            <h1 className="text-3xl font-bold mb-8">Mes Réservations</h1>

            {successMessage && (
                <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {successMessage}
                </div>
            )}

            <div className="bg-white shadow-md rounded-lg overflow-hidden">
                <table className="min-w-full divide-y divide-gray-200">
                    <thead className="bg-gray-50">
                        <tr>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chambre</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody className="bg-white divide-y divide-gray-200">
                        {reservations.map((reservation) => (
                            <tr key={reservation.id_reservation}>
                                <td className="px-6 py-4 whitespace-nowrap">
                                    <div className="text-sm font-medium text-gray-900">Chambre {reservation.room?.numero_chambre}</div>
                                    <div className="text-sm text-gray-500">{reservation.room?.roomType?.nom_type}</div>
                                </td>
                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {editingReservation === reservation.id_reservation ? (
                                        <div className="space-y-2">
                                            <input
                                                type="date"
                                                name="date_debut"
                                                value={editForm.date_debut}
                                                onChange={handleInputChange}
                                                className="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                                            />
                                            <input
                                                type="date"
                                                name="date_fin"
                                                value={editForm.date_fin}
                                                onChange={handleInputChange}
                                                className="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                                            />
                                        </div>
                                    ) : (
                                        `${new Date(reservation.date_debut).toLocaleDateString()} - ${new Date(reservation.date_fin).toLocaleDateString()}`
                                    )}
                                </td>
                                <td className="px-6 py-4 whitespace-nowrap">
                                    <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                        reservation.statut === 'confirmée' ? 'bg-green-100 text-green-800' :
                                        reservation.statut === 'en cours' ? 'bg-yellow-100 text-yellow-800' :
                                        'bg-red-100 text-red-800'
                                    }`}>
                                        {reservation.statut}
                                    </span>
                                </td>
                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    {editingReservation === reservation.id_reservation ? (
                                        <div className="space-y-2">
                                            <textarea
                                                name="demandes_speciales"
                                                value={editForm.demandes_speciales}
                                                onChange={handleInputChange}
                                                rows="2"
                                                className="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                                                placeholder="Demandes spéciales"
                                            />
                                            <div className="flex gap-2">
                                                <button
                                                    onClick={handleUpdate}
                                                    className="bg-green-500 hover:bg-green-700 text-white px-2 py-1 rounded text-xs"
                                                >
                                                    Sauvegarder
                                                </button>
                                                <button
                                                    onClick={handleCancelEdit}
                                                    className="bg-gray-500 hover:bg-gray-700 text-white px-2 py-1 rounded text-xs"
                                                >
                                                    Annuler
                                                </button>
                                            </div>
                                        </div>
                                    ) : (
                                        <div className="flex gap-2">
                                            {reservation.statut !== 'annulée' && reservation.statut !== 'confirmée' && (
                                                <button
                                                    onClick={() => handleEdit(reservation)}
                                                    className="text-blue-600 hover:text-blue-900"
                                                >
                                                    Modifier
                                                </button>
                                            )}
                                            {reservation.statut !== 'annulée' && (
                                                <button
                                                    onClick={() => handleCancel(reservation.id_reservation)}
                                                    className="text-red-600 hover:text-red-900"
                                                >
                                                    Annuler
                                                </button>
                                            )}
                                        </div>
                                    )}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>

            {reservations.length === 0 && (
                <div className="text-center py-8">
                    <p className="text-gray-500">Vous n'avez pas encore de réservations.</p>
                </div>
            )}
        </div>
    );
};

export default ClientReservations;
