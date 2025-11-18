import React, { useState, useEffect, useCallback, useMemo } from 'react';
import { initializeApp } from 'firebase/app';
import {
    getAuth,
    signInAnonymously,
    signInWithCustomToken,
    onAuthStateChanged,
} from 'firebase/auth';
import {
    getFirestore,
    collection,
    query,
    onSnapshot,
    doc,
    setDoc,
    deleteDoc,
    serverTimestamp,
    setLogLevel
} from 'firebase/firestore';

// Définition des variables globales pour Firebase (fournies par l'environnement Canvas)
const firebaseConfig = typeof __firebase_config !== 'undefined' ? JSON.parse(__firebase_config) : {};
const initialAuthToken = typeof __initial_auth_token !== 'undefined' ? __initial_auth_token : null;
const appId = typeof __app_id !== 'undefined' ? __app_id : 'default-app-id';

// Configuration Firebase et initialisation
const app = initializeApp(firebaseConfig);
const db = getFirestore(app);
const auth = getAuth(app);
// setLogLevel('debug'); // Décommenter pour le débogage

// --- Utilitaires de Date et Prix ---

// Calcule la différence en jours entre deux dates (inclusif)
const calculateDurationInDays = (startDateStr, endDateStr) => {
    if (!startDateStr || !endDateStr) return 0;
    const start = new Date(startDateStr);
    const end = new Date(endDateStr);
    // Assurer que le calcul se fait en jours complets
    const timeDiff = end.getTime() - start.getTime();
    if (timeDiff < 0) return 0; // Date de fin avant date de début
    // Ajouter 1 jour pour inclure la première nuit dans le décompte (si c'est une réservation de nuitée)
    // Pour une réservation de nuits, il est souvent (Date Fin - Date Début) / 1 jour
    // Si nous considérons les nuits (e.g., Lundi au Mercredi = 2 nuits), c'est:
    const duration = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
    return duration > 0 ? duration : 1; // Minimum 1 nuit/jour
};

// Calcule le coût total
const calculateTotalCost = (startDateStr, endDateStr, pricePerNight) => {
    const duration = calculateDurationInDays(startDateStr, endDateStr);
    return duration * pricePerNight;
};

// --- Modèles de Données ---

const initialReservationState = {
    id: null,
    guestName: '',
    room: '',
    startDate: '',
    endDate: '',
    status: 'Confirmé', // Statuts: 'Confirmé', 'Enregistré', 'Annulé'
    pricePerNight: 0, // Nouveau champ: Prix par nuit
    totalCost: 0, // Nouveau champ: Coût total calculé
};

// --- Composants Modals ---

const StatusBadge = ({ status }) => {
    const colorMap = {
        'Confirmé': 'bg-green-100 text-green-800',
        'Enregistré': 'bg-blue-100 text-blue-800',
        'Annulé': 'bg-red-100 text-red-800',
    };
    const color = colorMap[status] || 'bg-gray-100 text-gray-800';
    return (
        <span className={`inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium ${color}`}>
            {status}
        </span>
    );
};

const ModalWrapper = ({ isOpen, onClose, title, children }) => {
    if (!isOpen) return null;
    return (
        <div className="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 z-50 transition-opacity duration-300">
            <div className="bg-white rounded-xl shadow-2xl w-full max-w-lg transition-transform duration-300 transform scale-100">
                <div className="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h2 className="text-2xl font-semibold text-gray-800">{title}</h2>
                    <button
                        onClick={onClose}
                        className="text-gray-400 hover:text-gray-600 transition"
                        aria-label="Fermer la fenêtre modale"
                    >
                        <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div className="p-6">
                    {children}
                </div>
            </div>
        </div>
    );
};

const ReservationModal = ({ isOpen, onClose, initialData, db, auth, userId }) => {
    const [reservation, setReservation] = useState(initialData);
    const [isLoading, setIsLoading] = useState(false);
    const [error, setError] = useState(null);

    useEffect(() => {
        setReservation({
            ...initialData,
            pricePerNight: initialData.pricePerNight || 0,
        });
    }, [initialData]);

    const handleChange = useCallback((e) => {
        const { name, value } = e.target;
        setReservation(prev => ({
            ...prev,
            [name]: name === 'pricePerNight' ? parseFloat(value) || 0 : value,
        }));
    }, []);

    const totalCost = useMemo(() => calculateTotalCost(
        reservation.startDate,
        reservation.endDate,
        reservation.pricePerNight
    ), [reservation.startDate, reservation.endDate, reservation.pricePerNight]);

    const durationInDays = useMemo(() => calculateDurationInDays(
        reservation.startDate,
        reservation.endDate
    ), [reservation.startDate, reservation.endDate]);

    const handleSubmit = useCallback(async (e) => {
        e.preventDefault();
        if (!userId || !db) {
            setError("Erreur d'authentification ou de base de données.");
            return;
        }

        const data = {
            ...reservation,
            totalCost, // Enregistrer le coût total calculé (valeur calculée par useMemo)
            timestamp: serverTimestamp(),
            // Conserver uniquement les champs nécessaires pour la persistence
            guestName: reservation.guestName.trim(),
            room: reservation.room.trim(),
            startDate: reservation.startDate,
            endDate: reservation.endDate,
            status: reservation.status,
            pricePerNight: reservation.pricePerNight,
            // ANCIEN CODE AVEC DUPLICATION : totalCost: totalCost, 
            userId: userId, // Sécurité: l'UID de l'utilisateur qui a créé/modifié
        };

        if (data.guestName === '' || data.room === '' || data.startDate === '' || data.endDate === '') {
            setError("Veuillez remplir tous les champs requis.");
            return;
        }

        if (totalCost < 0) {
            setError("La date de fin doit être postérieure à la date de début.");
            return;
        }

        setIsLoading(true);
        setError(null);

        try {
            const docRef = data.id
                ? doc(db, `/artifacts/${appId}/public/data/reservations`, data.id)
                : doc(collection(db, `/artifacts/${appId}/public/data/reservations`));

            await setDoc(docRef, data, { merge: true });

            onClose();
        } catch (err) {
            console.error("Erreur lors de la sauvegarde: ", err);
            setError("Échec de la sauvegarde de la réservation.");
        } finally {
            setIsLoading(false);
        }
    }, [reservation, totalCost, userId, db, onClose]);

    return (
        <ModalWrapper isOpen={isOpen} onClose={onClose} title={reservation.id ? "Modifier la Réservation" : "Nouvelle Réservation"}>
            <form onSubmit={handleSubmit} className="space-y-4">
                {error && <p className="text-red-600 bg-red-100 p-3 rounded-md">{error}</p>}
                
                {/* Ligne 1: Nom et Chambre */}
                <div className="flex flex-col sm:flex-row gap-4">
                    <InputField
                        label="Nom du Client"
                        name="guestName"
                        value={reservation.guestName}
                        onChange={handleChange}
                        required
                    />
                    <InputField
                        label="Chambre / Service"
                        name="room"
                        value={reservation.room}
                        onChange={handleChange}
                        required
                    />
                </div>

                {/* Ligne 2: Dates */}
                <div className="flex flex-col sm:flex-row gap-4">
                    <InputField
                        label="Date de Début"
                        name="startDate"
                        type="date"
                        value={reservation.startDate}
                        onChange={handleChange}
                        required
                    />
                    <InputField
                        label="Date de Fin"
                        name="endDate"
                        type="date"
                        value={reservation.endDate}
                        onChange={handleChange}
                        required
                    />
                </div>

                {/* Ligne 3: Statut et Prix par Nuit (Nouveau pour Facturation) */}
                <div className="flex flex-col sm:flex-row gap-4">
                    <SelectField
                        label="Statut"
                        name="status"
                        value={reservation.status}
                        onChange={handleChange}
                        options={['Confirmé', 'Enregistré', 'Annulé']}
                    />
                    <InputField
                        label="Prix Unitaire (€/nuit)"
                        name="pricePerNight"
                        type="number"
                        value={reservation.pricePerNight}
                        onChange={handleChange}
                        min="0"
                        step="0.01"
                        required
                    />
                </div>

                {/* Affichage du Coût Total (Calculé) */}
                <div className="p-4 bg-blue-50 border-l-4 border-blue-400 text-blue-800 rounded-md">
                    <p className="font-medium">Durée : <span className="font-bold">{durationInDays} jours/nuits</span></p>
                    <p className="text-lg font-semibold mt-1">
                        Coût Total Estimé : <span className="text-blue-600">{totalCost.toFixed(2)} €</span>
                    </p>
                    {totalCost < 0 && <p className="text-sm text-red-500 mt-1">La date de fin doit être après la date de début.</p>}
                </div>

                <div className="pt-4 flex justify-end space-x-3">
                    <button
                        type="button"
                        onClick={onClose}
                        className="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition"
                        disabled={isLoading}
                    >
                        Annuler
                    </button>
                    <button
                        type="submit"
                        className="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition disabled:opacity-50"
                        disabled={isLoading || totalCost < 0}
                    >
                        {isLoading ? "Sauvegarde..." : "Sauvegarder la Réservation"}
                    </button>
                </div>
            </form>
        </ModalWrapper>
    );
};

const BillingModal = ({ isOpen, onClose, reservation }) => {
    if (!isOpen || !reservation) return null;

    const durationInDays = calculateDurationInDays(reservation.startDate, reservation.endDate);
    const taxRate = 0.10; // Exemple: 10% de TVA
    const subtotal = reservation.totalCost / (1 + taxRate);
    const taxes = reservation.totalCost - subtotal;

    return (
        <ModalWrapper isOpen={isOpen} onClose={onClose} title="Facture Détaillée de Réservation">
            <div className="space-y-6 text-gray-700">
                
                {/* Entête Facture */}
                <div className="border-b pb-4">
                    <h3 className="text-xl font-bold text-indigo-600">{reservation.guestName}</h3>
                    <p className="text-sm">Facture n°: {reservation.id.substring(0, 8).toUpperCase()}</p>
                </div>

                {/* Détails de la Réservation */}
                <div className="grid grid-cols-2 gap-y-2">
                    <p className="font-medium">Service:</p>
                    <p className="text-right">{reservation.room}</p>
                    
                    <p className="font-medium">Période:</p>
                    <p className="text-right">Du {reservation.startDate} au {reservation.endDate}</p>
                    
                    <p className="font-medium">Durée:</p>
                    <p className="text-right">{durationInDays} jours/nuits</p>
                    
                    <p className="font-medium">Prix Unitaire:</p>
                    <p className="text-right">{reservation.pricePerNight.toFixed(2)} €</p>
                </div>

                {/* Tableau de Coûts */}
                <div className="mt-4 pt-4 border-t border-dashed">
                    <div className="flex justify-between py-1">
                        <span>Sous-Total:</span>
                        <span className="font-semibold">{subtotal.toFixed(2)} €</span>
                    </div>
                    <div className="flex justify-between py-1">
                        <span>Taxes ({(taxRate * 100).toFixed(0)}%):</span>
                        <span className="font-semibold">{taxes.toFixed(2)} €</span>
                    </div>
                </div>

                {/* Total Final */}
                <div className="pt-4 border-t-2 border-indigo-600 flex justify-between items-center">
                    <span className="text-xl font-bold text-gray-800">TOTAL À PAYER:</span>
                    <span className="text-3xl font-extrabold text-indigo-600">{reservation.totalCost.toFixed(2)} €</span>
                </div>

                <div className="text-center pt-4">
                    <button
                        onClick={onClose}
                        className="w-full sm:w-auto px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
                    >
                        Fermer la Facture
                    </button>
                </div>
            </div>
        </ModalWrapper>
    );
};

// Champ de saisie réutilisable
const InputField = ({ label, name, type = "text", value, onChange, required = false, min, step }) => (
    <div className="flex-1">
        <label htmlFor={name} className="block text-sm font-medium text-gray-700">
            {label} {required && <span className="text-red-500">*</span>}
        </label>
        <input
            id={name}
            name={name}
            type={type}
            value={value}
            onChange={onChange}
            required={required}
            min={min}
            step={step}
            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border"
        />
    </div>
);

// Champ de sélection réutilisable
const SelectField = ({ label, name, value, onChange, options }) => (
    <div className="flex-1">
        <label htmlFor={name} className="block text-sm font-medium text-gray-700">
            {label}
        </label>
        <select
            id={name}
            name={name}
            value={value}
            onChange={onChange}
            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border bg-white"
        >
            {options.map(opt => (
                <option key={opt} value={opt}>{opt}</option>
            ))}
        </select>
    </div>
);


const ReservationCard = ({ reservation, onEdit, onDelete, onShowBilling }) => {
    const duration = calculateDurationInDays(reservation.startDate, reservation.endDate);

    return (
        <div className="bg-white p-4 shadow-lg rounded-xl border border-gray-100 hover:shadow-xl transition-shadow duration-300 transform hover:scale-[1.02]">
            <div className="flex justify-between items-start mb-3 border-b pb-2">
                <h3 className="text-xl font-bold text-gray-800 truncate">{reservation.guestName}</h3>
                <StatusBadge status={reservation.status} />
            </div>

            <div className="space-y-2 text-sm text-gray-600">
                <p className="flex items-center">
                    <svg className="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <span className="font-semibold">{reservation.room}</span>
                </p>
                <p className="flex items-center">
                    <svg className="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Du {reservation.startDate} au {reservation.endDate} ({duration} j)
                </p>
                {/* Nouveau champ Facturation */}
                <p className="flex items-center text-lg font-bold text-green-700">
                    <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    {reservation.totalCost.toFixed(2)} €
                </p>
            </div>

            <div className="mt-4 flex flex-wrap gap-2">
                <button
                    onClick={() => onShowBilling(reservation)}
                    className="flex-1 py-2 text-sm font-semibold text-white bg-green-500 rounded-lg hover:bg-green-600 transition shadow-md"
                >
                    <svg className="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Facture
                </button>
                <button
                    onClick={() => onEdit(reservation)}
                    className="flex-1 py-2 text-sm font-semibold text-indigo-600 bg-indigo-100 rounded-lg hover:bg-indigo-200 transition"
                >
                    Modifier
                </button>
                <button
                    onClick={() => onDelete(reservation.id)}
                    className="py-2 px-3 text-sm font-semibold text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition"
                >
                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>
        </div>
    );
};

// --- Composant Principal de l'Application ---

const App = () => {
    const [userId, setUserId] = useState(null);
    const [isAuthReady, setIsAuthReady] = useState(false);
    const [reservations, setReservations] = useState([]);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [editingReservation, setEditingReservation] = useState(initialReservationState);
    const [isLoadingData, setIsLoadingData] = useState(true);
    const [isBillingModalOpen, setIsBillingModalOpen] = useState(false); // Nouvel état
    const [billingReservation, setBillingReservation] = useState(null); // Nouvelle réservation pour la facture

    // 1. Initialisation et Authentification Firebase
    useEffect(() => {
        const initializeAuth = async () => {
            try {
                if (initialAuthToken) {
                    await signInWithCustomToken(auth, initialAuthToken);
                } else {
                    await signInAnonymously(auth);
                }
            } catch (error) {
                console.error("Erreur lors de l'authentification:", error);
            }
        };

        const unsubscribe = onAuthStateChanged(auth, (user) => {
            if (user) {
                setUserId(user.uid);
            } else {
                setUserId(null);
            }
            setIsAuthReady(true);
        });

        if (!userId) {
            initializeAuth();
        }

        return () => unsubscribe();
    }, []); // eslint-disable-line react-hooks/exhaustive-deps

    // 2. Écouteur Firestore pour les Réservations
    useEffect(() => {
        if (!isAuthReady || !userId) return;

        setIsLoadingData(true);

        // Chemin pour les données publiques/collaboratives
        const reservationsColRef = collection(db, `/artifacts/${appId}/public/data/reservations`);

        // La requête n'a pas besoin de .where('userId', '==', userId) car nous utilisons un espace public
        const q = query(reservationsColRef);

        const unsubscribe = onSnapshot(q, (snapshot) => {
            const reservationsData = snapshot.docs.map(doc => ({
                id: doc.id,
                ...doc.data(),
                // S'assurer que les champs numériques sont bien des nombres
                pricePerNight: parseFloat(doc.data().pricePerNight || 0),
                totalCost: parseFloat(doc.data().totalCost || 0),
            }));
            // Tri par date de début (côté client pour éviter les index Firestore)
            reservationsData.sort((a, b) => new Date(a.startDate) - new Date(b.startDate));
            setReservations(reservationsData);
            setIsLoadingData(false);
        }, (error) => {
            console.error("Erreur onSnapshot: ", error);
            setIsLoadingData(false);
        });

        return () => unsubscribe();
    }, [isAuthReady, userId]); // eslint-disable-line react-hooks/exhaustive-deps


    // --- Fonctions de Manipulation des Réservations ---

    const handleNewReservation = useCallback(() => {
        setEditingReservation(initialReservationState);
        setIsModalOpen(true);
    }, []);

    const handleEditReservation = useCallback((reservation) => {
        setEditingReservation(reservation);
        setIsModalOpen(true);
    }, []);

    const handleDeleteReservation = useCallback(async (id) => {
        if (!userId || !db) return;

        // Remplacer l'alert() par une modale custom pour une confirmation
        if (window.confirm("Êtes-vous sûr de vouloir supprimer cette réservation ?")) {
            try {
                const docRef = doc(db, `/artifacts/${appId}/public/data/reservations`, id);
                await deleteDoc(docRef);
            } catch (error) {
                console.error("Erreur lors de la suppression:", error);
            }
        }
    }, [userId, db]);

    // --- Fonctions de Facturation (Nouveau) ---

    const handleShowBilling = useCallback((reservation) => {
        setBillingReservation(reservation);
        setIsBillingModalOpen(true);
    }, []);

    const handleCloseBillingModal = useCallback(() => {
        setIsBillingModalOpen(false);
        setBillingReservation(null);
    }, []);

    // --- Rendu des Jours/Colonnes (Simulateur de Planning) ---

    const today = new Date();
    const weekDays = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];
    
    // Déterminer le début de la semaine (lundi ou dimanche, ici on commence par Aujourd'hui + 7 jours)
    const getWeekDays = (start) => {
        const days = [];
        for (let i = 0; i < 7; i++) {
            const date = new Date(start);
            date.setDate(start.getDate() + i);
            days.push(date);
        }
        return days;
    };
    
    // Pour simplifier l'affichage du planning, nous affichons 7 jours à partir d'aujourd'hui
    const nextSevenDays = getWeekDays(today);

    // Groupement des réservations par date pour le rendu du planning
    const reservationsByDay = useMemo(() => {
        const groups = {};
        nextSevenDays.forEach(day => {
            const dateString = day.toISOString().split('T')[0];
            groups[dateString] = [];
        });

        reservations.forEach(res => {
            const start = new Date(res.startDate);
            const end = new Date(res.endDate);

            // Vérifier si la réservation chevauche un des jours de la semaine affichée
            nextSevenDays.forEach(day => {
                const dayString = day.toISOString().split('T')[0];
                const reservationDay = new Date(dayString);

                // La réservation est active à cette date si:
                // 1. La date de début est AVANT ou ÉGALE au jour
                // 2. La date de fin est APRÈS le jour (pour les nuits)
                if (reservationDay >= start && reservationDay < end) {
                     // On affiche la carte uniquement le jour de début pour éviter la duplication
                     if (res.startDate === dayString) {
                        if (groups[dayString]) {
                            groups[dayString].push(res);
                        }
                     }
                }
            });
        });
        return groups;
    }, [reservations, nextSevenDays]);

    const formatDayHeader = (date) => {
        const dayOfWeek = weekDays[date.getDay()];
        const dayOfMonth = date.getDate();
        const month = date.toLocaleDateString('fr-FR', { month: 'short' });
        const isToday = date.toDateString() === today.toDateString();
        
        return (
            <div className={`p-3 text-center rounded-t-lg font-bold transition ${isToday ? 'bg-indigo-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700'}`}>
                <p className="text-sm">{dayOfWeek}</p>
                <p className="text-xl">{dayOfMonth}</p>
                <p className="text-xs">{month}</p>
            </div>
        );
    };

    return (
        <div className="min-h-screen bg-gray-50 font-sans p-4 sm:p-8">
            <header className="mb-8">
                <div className="flex justify-between items-center flex-wrap gap-4">
                    <h1 className="text-3xl font-extrabold text-gray-900">
                        <span className="text-indigo-600">Planning</span> & Facturation
                    </h1>
                    <button
                        onClick={handleNewReservation}
                        className="flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition transform hover:scale-105"
                    >
                        <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Nouvelle Réservation
                    </button>
                </div>
                {userId && (
                    <p className="mt-2 text-sm text-gray-500">
                        Connecté en tant que: <span className="font-mono text-xs bg-gray-200 p-1 rounded">{userId}</span>
                    </p>
                )}
            </header>

            {/* Affichage des données (Planning 7 jours) */}
            <div className="overflow-x-auto">
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-7 gap-4 min-w-max">
                    {nextSevenDays.map((date, index) => {
                        const dateString = date.toISOString().split('T')[0];
                        const reservationsForDay = reservationsByDay[dateString] || [];
                        
                        return (
                            <div key={index} className="flex flex-col bg-white rounded-xl shadow-lg border border-gray-200">
                                {formatDayHeader(date)}
                                
                                <div className="p-3 space-y-3 flex-1 min-h-[200px] overflow-y-auto">
                                    {isLoadingData ? (
                                        <div className="text-center text-gray-400 p-4">Chargement...</div>
                                    ) : reservationsForDay.length > 0 ? (
                                        reservationsForDay.map(res => (
                                            <ReservationCard
                                                key={res.id}
                                                reservation={res}
                                                onEdit={handleEditReservation}
                                                onDelete={handleDeleteReservation}
                                                onShowBilling={handleShowBilling} // Nouveau prop
                                            />
                                        ))
                                    ) : (
                                        <div className="text-center text-gray-400 p-4 text-sm">
                                            Aucune réservation pour ce jour.
                                        </div>
                                    )}
                                </div>
                            </div>
                        );
                    })}
                </div>
            </div>

            {/* Modale de Création/Édition */}
            <ReservationModal
                isOpen={isModalOpen}
                onClose={() => setIsModalOpen(false)}
                initialData={editingReservation}
                db={db}
                auth={auth}
                userId={userId}
            />

            {/* Modale de Facturation (Nouveau Module) */}
            <BillingModal
                isOpen={isBillingModalOpen}
                onClose={handleCloseBillingModal}
                reservation={billingReservation}
            />
        </div>
    );
};

export default App;