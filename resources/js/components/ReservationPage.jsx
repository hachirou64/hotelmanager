import React, { useState, useEffect, useMemo } from 'react';
import { initializeApp } from 'firebase/app';
import { getAuth, signInAnonymously, signInWithCustomToken, onAuthStateChanged } from 'firebase/auth';
import { getFirestore, doc, collection, query, onSnapshot, addDoc, updateDoc, deleteDoc } from 'firebase/firestore';
import { Calendar as CalendarIcon, Hotel, Plus, X, ChevronLeft, ChevronRight, Loader2, LogOut, CheckCheck } from 'lucide-react';

// --- Date and Logic Utilities (Utilitaires de Date et Logique) ---
// Function to format a date to YYYY-MM-DD
const formatDate = (date) => date.toISOString().split('T')[0];

// Function to add days to a date
const addDays = (date, days) => {
    const newDate = new Date(date);
    newDate.setDate(date.getDate() + days);
    return newDate;
};

// Function to check if a reservation covers a specific date
const isDateWithinReservation = (dateStr, checkInStr, checkOutStr) => {
    // A reservation starts on checkInStr and ends *before* checkOutStr
    return dateStr >= checkInStr && dateStr < checkOutStr;
};

// Function to calculate the duration of a reservation in days
const getReservationLength = (checkInStr, checkOutStr) => {
    const checkIn = new Date(checkInStr);
    const checkOut = new Date(checkOutStr);
    const diffTime = Math.abs(checkOut - checkIn);
    return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
};

// Default room list (Liste des chambres par défaut)
const ROOM_DATA = [
    { id: 'A101', name: 'Chambre Standard (101)', capacity: 2 },
    { id: 'A102', name: 'Chambre Standard (102)', capacity: 2 },
    { id: 'B201', name: 'Suite Junior (201)', capacity: 3 },
    { id: 'B202', name: 'Suite Junior (202)', capacity: 3 },
    { id: 'C301', name: 'Suite Présidentielle (301)', capacity: 4 },
];

const STATUS_COLORS = {
    'Confirmed': 'bg-blue-500',
    'CheckedIn': 'bg-green-500',
    'CheckedOut': 'bg-gray-400',
    'Cancelled': 'bg-red-500 line-through'
};

const App = () => {
    // --- Firebase and Auth State (État Firebase et Authentification) ---
    const [db, setDb] = useState(null);
    const [auth, setAuth] = useState(null);
    const [userId, setUserId] = useState(null);
    const [isAuthReady, setIsAuthReady] = useState(false);
    const [authError, setAuthError] = useState(null);

    // --- Application State (État de l'Application) ---
    const [reservations, setReservations] = useState([]);
    const [currentDate, setCurrentDate] = useState(new Date());
    const [isLoading, setIsLoading] = useState(true);
    
    // Modal State
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [editingReservation, setEditingReservation] = useState(null); // Reservation for editing/viewing
    const [newReservationData, setNewReservationData] = useState({ 
        roomId: ROOM_DATA[0].id, guestName: '', checkInDate: formatDate(new Date()), checkOutDate: formatDate(addDays(new Date(), 1)), status: 'Confirmed', color: 'bg-blue-500' 
    });

    // --- Firebase Initialization and Auth (Initialisation Firebase et Authentification) ---
    useEffect(() => {
        try {
            // Retrieve mandatory global variables
            const appId = typeof __app_id !== 'undefined' ? __app_id : 'default-app-id';
            const firebaseConfig = typeof __firebase_config !== 'undefined' ? JSON.parse(__firebase_config) : null;
            const initialAuthToken = typeof __initial_auth_token !== 'undefined' ? __initial_auth_token : null;

            if (!firebaseConfig) {
                console.error("Firebase config is missing.");
                setAuthError("Configuration Firebase manquante.");
                return;
            }

            const app = initializeApp(firebaseConfig);
            const firestore = getFirestore(app);
            const authInstance = getAuth(app);
            
            setDb(firestore);
            setAuth(authInstance);

            const unsubscribe = onAuthStateChanged(authInstance, async (user) => {
                if (user) {
                    setUserId(user.uid);
                } else if (initialAuthToken) {
                    // Sign in with custom token if available
                    await signInWithCustomToken(authInstance, initialAuthToken).catch(e => {
                        console.error("Erreur lors de la connexion par token:", e);
                        setAuthError(`Erreur d'authentification: ${e.message}`);
                    });
                } else {
                    // Sign in anonymously as a fallback
                    await signInAnonymously(authInstance).catch(e => {
                        console.error("Erreur lors de la connexion anonyme:", e);
                        setAuthError(`Erreur d'authentification: ${e.message}`);
                    });
                }
                setIsAuthReady(true);
                setIsLoading(false);
            });

            return () => unsubscribe();
        } catch (e) {
            console.error("Échec de l'initialisation de Firebase:", e);
            setAuthError(`Échec de l'initialisation: ${e.message}`);
            setIsLoading(false);
        }
    }, []);

    // --- Reservation Synchronization (Firestore Snapshot) (Synchronisation des Réservations) ---
    useEffect(() => {
        if (!db || !isAuthReady) return; // Guard clause: wait for auth to be ready

        const appId = typeof __app_id !== 'undefined' ? __app_id : 'default-app-id';
        // Use the public collection path for shared data
        const publicCollectionPath = `artifacts/${appId}/public/data/reservations`;
        const reservationsColRef = collection(db, publicCollectionPath);
        
        // Simple query to fetch all reservations
        const q = query(reservationsColRef);

        const unsubscribe = onSnapshot(q, (snapshot) => {
            const reservationsList = snapshot.docs.map(doc => ({
                id: doc.id,
                ...doc.data()
            }));
            setReservations(reservationsList);
            setIsLoading(false);
        }, (error) => {
            console.error("Erreur de snapshot Firestore:", error);
        });

        return () => unsubscribe();
    }, [db, isAuthReady]);

    // --- UI Logic (Logique de l'UI) ---
    
    // Generates the 7 days to display
    const days = useMemo(() => {
        const start = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate());
        const daysArray = [];
        for (let i = 0; i < 7; i++) {
            daysArray.push(addDays(start, i));
        }
        return daysArray;
    }, [currentDate]);

    const navigateDate = (direction) => {
        const newDate = addDays(currentDate, direction === 'prev' ? -7 : 7);
        setCurrentDate(newDate);
    };

    // Handle click on an empty cell to create a new reservation
    const handleCellClick = (roomId, date) => {
        setEditingReservation(null);
        setNewReservationData({
            roomId: roomId,
            guestName: '',
            checkInDate: formatDate(date),
            checkOutDate: formatDate(addDays(date, 1)),
            status: 'Confirmed',
            color: STATUS_COLORS['Confirmed'],
            userId: userId || 'anonymous'
        });
        setIsModalOpen(true);
    };

    // Handle click on an existing reservation block
    const handleReservationClick = (reservation) => {
        setEditingReservation(reservation);
        setNewReservationData({ ...reservation }); // Load data for editing
        setIsModalOpen(true);
    };
    
    // --- Firestore CRUD Logic (Logique CRUD Firestore) ---

    const handleSaveReservation = async () => {
        if (!db || !userId) {
             console.error("Base de données ou utilisateur non prêt.");
             return;
        }

        // Basic validation
        if (!newReservationData.guestName || newReservationData.checkInDate >= newReservationData.checkOutDate) {
            // In a real app, use a custom modal/toast instead of alert
            console.error("Veuillez remplir le nom du client et assurez-vous que la date de départ est après la date d'arrivée.");
            return;
        }

        const reservationToSave = {
            ...newReservationData,
            userId: userId, // Ensure user ID is always present
        };
        
        const appId = typeof __app_id !== 'undefined' ? __app_id : 'default-app-id';
        const publicCollectionPath = `artifacts/${appId}/public/data/reservations`;
        
        try {
            if (editingReservation) {
                // Update existing reservation
                const docRef = doc(db, publicCollectionPath, editingReservation.id);
                await updateDoc(docRef, reservationToSave);
                console.log("Réservation mise à jour avec ID: ", editingReservation.id);
            } else {
                // Create new reservation
                const colRef = collection(db, publicCollectionPath);
                await addDoc(colRef, reservationToSave);
                console.log("Nouvelle réservation ajoutée.");
            }
            setIsModalOpen(false);
            setEditingReservation(null);
        } catch (e) {
            console.error("Erreur lors de l'enregistrement de la réservation: ", e);
        }
    };
    
    const handleDeleteReservation = async () => {
        if (!db || !editingReservation || !userId) return;
        
        const appId = typeof __app_id !== 'undefined' ? __app_id : 'default-app-id';
        const publicCollectionPath = `artifacts/${appId}/public/data/reservations`;

        try {
            const docRef = doc(db, publicCollectionPath, editingReservation.id);
            await deleteDoc(docRef);
            console.log("Réservation supprimée avec ID: ", editingReservation.id);
            setIsModalOpen(false);
            setEditingReservation(null);
        } catch (e) {
             console.error("Erreur lors de la suppression de la réservation: ", e);
        }
    };

    // --- Child Components (Composants Enfants) ---

    const ReservationBlock = ({ reservation, days }) => {
        const checkIn = reservation.checkInDate;
        const checkOut = reservation.checkOutDate;
        const timelineStart = formatDate(days[0]);

        // Check if the reservation is visible in this 7-day window
        if (checkOut <= timelineStart) {
            return null;
        }

        // Calculate starting position (grid column index)
        let startCol = -1;
        for (let i = 0; i < days.length; i++) {
            const dayStr = formatDate(days[i]);
            // Find the first day of the reservation visible in the current view
            if (isDateWithinReservation(dayStr, checkIn, checkOut) && startCol === -1) {
                 startCol = i + 1; // Grid column starts at 1
                 break;
            }
        }
        
        if (startCol === -1) return null;

        // Calculate visible duration
        let visibleDuration = 0;
        for (let i = startCol - 1; i < days.length; i++) {
             const dayStr = formatDate(days[i]);
             if (isDateWithinReservation(dayStr, checkIn, checkOut)) {
                 visibleDuration++;
             }
        }
        
        if (visibleDuration === 0) return null;

        // Determine reservation style
        const style = {
            gridColumnStart: startCol,
            gridColumnEnd: `span ${visibleDuration}`,
        };

        return (
            // The `pointer-events-auto` overrides the parent's `pointer-events-none`
            <div 
                style={style} 
                onClick={() => handleReservationClick(reservation)} 
                className={`absolute inset-0 h-3/4 my-auto cursor-pointer rounded-lg px-2 py-1 text-xs text-white shadow-md transition-all duration-200 overflow-hidden truncate ${STATUS_COLORS[reservation.status] || STATUS_COLORS['Confirmed']} hover:scale-[1.01] hover:shadow-xl pointer-events-auto`}
            >
                <p className="font-semibold">{reservation.guestName}</p>
                <p className="opacity-80 hidden sm:block">({getReservationLength(checkIn, checkOut)} nuits)</p>
            </div>
        );
    };

    const RoomRow = ({ room, days, reservations, onCellClick }) => {
        const roomReservations = reservations.filter(res => res.roomId === room.id);
        
        return (
            <div className="grid grid-cols-8 border-b dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors duration-200 relative">
                
                {/* Room Name (Column 1) */}
                <div className="col-span-1 p-3 flex flex-col justify-center border-r dark:border-slate-700 bg-gray-50 dark:bg-slate-900 sticky left-0 z-10 shadow-sm">
                    <p className="font-semibold text-gray-900 dark:text-white text-sm truncate">{room.name}</p>
                    <p className="text-xs text-gray-500 dark:text-gray-400">Capacité: {room.capacity}</p>
                </div>
                
                {/* Calendar Cells (Columns 2-8) - Clickable areas for new reservation */}
                {days.map((day, index) => {
                    const dayStr = formatDate(day);
                    
                    // Check for conflicts
                    const conflictingRes = roomReservations.find(res => 
                        isDateWithinReservation(dayStr, res.checkInDate, res.checkOutDate)
                    );

                    return (
                        <div 
                            key={index} 
                            // Only allow clicks if no reservation is currently occupying the spot
                            onClick={() => !conflictingRes && onCellClick(room.id, day)}
                            className={`col-span-1 relative h-20 p-1 border-l dark:border-slate-700 transition-colors duration-150 
                                ${conflictingRes ? 'cursor-default bg-red-500/10' : 'cursor-pointer hover:bg-green-500/10'}
                            `}
                        >
                            {/* Empty cell content */}
                        </div>
                    );
                })}

                {/* Reservation Blocks layer (absolute positioning over the cells) */}
                <div className="absolute inset-0 right-0 left-[12.5%] grid grid-cols-7 pointer-events-none">
                    {roomReservations.map((res) => (
                        <ReservationBlock 
                            key={res.id} 
                            reservation={res} 
                            days={days} 
                        />
                    ))}
                </div>
            </div>
        );
    };

    const ReservationModal = ({ isOpen, onClose, data, onDataChange, onSave, onDelete, isEditing }) => {
        if (!isOpen) return null;

        const allStatus = Object.keys(STATUS_COLORS);

        return (
            <div className="fixed inset-0 bg-gray-900 bg-opacity-70 z-100 flex items-center justify-center p-4">
                <div className="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-lg p-6">
                    <div className="flex justify-between items-center mb-6">
                        <h3 className="text-2xl font-bold text-gray-900 dark:text-white">{isEditing ? 'Détails de la Réservation' : 'Nouvelle Réservation'}</h3>
                        <button onClick={onClose} className="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <X className="w-6 h-6" />
                        </button>
                    </div>

                    <div className="space-y-4">
                        {/* Chambre */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300">Chambre</label>
                            <select 
                                value={data.roomId}
                                onChange={(e) => onDataChange('roomId', e.target.value)}
                                className="mt-1 block w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 text-gray-900 dark:text-white p-3 shadow-sm"
                                // Disabled when editing to prevent moving existing reservations easily
                                disabled={isEditing} 
                            >
                                {ROOM_DATA.map(room => (
                                    <option key={room.id} value={room.id}>{room.name}</option>
                                ))}
                            </select>
                        </div>

                        {/* Nom du Client */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom du Client</label>
                            <input
                                type="text"
                                value={data.guestName}
                                onChange={(e) => onDataChange('guestName', e.target.value)}
                                className="mt-1 block w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 text-gray-900 dark:text-white p-3 shadow-sm"
                                placeholder="Nom et Prénom"
                            />
                        </div>

                        <div className="grid grid-cols-2 gap-4">
                            {/* Check-In */}
                            <div>
                                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300">Arrivée (Check-In)</label>
                                <input
                                    type="date"
                                    value={data.checkInDate}
                                    onChange={(e) => onDataChange('checkInDate', e.target.value)}
                                    className="mt-1 block w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 text-gray-900 dark:text-white p-3 shadow-sm"
                                />
                            </div>
                            {/* Check-Out */}
                            <div>
                                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300">Départ (Check-Out)</label>
                                <input
                                    type="date"
                                    value={data.checkOutDate}
                                    onChange={(e) => onDataChange('checkOutDate', e.target.value)}
                                    className="mt-1 block w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 text-gray-900 dark:text-white p-3 shadow-sm"
                                />
                            </div>
                        </div>
                        
                        {/* Statut */}
                         <div>
                            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300">Statut de la Réservation</label>
                            <select 
                                value={data.status}
                                onChange={(e) => {
                                    onDataChange('status', e.target.value);
                                    // Also update the color for immediate visual feedback
                                    onDataChange('color', STATUS_COLORS[e.target.value] || STATUS_COLORS['Confirmed']);
                                }}
                                className="mt-1 block w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-900 text-gray-900 dark:text-white p-3 shadow-sm"
                            >
                                {allStatus.map(status => (
                                    <option key={status} value={status}>{status}</option>
                                ))}
                            </select>
                        </div>

                    </div>
                    
                    {/* Actions */}
                    <div className="mt-6 flex justify-between">
                        <div className="flex space-x-3">
                            {isEditing && (
                                <button 
                                    onClick={onDelete} 
                                    className="px-4 py-2 bg-red-500 text-white font-semibold rounded-lg shadow hover:bg-red-600 transition"
                                >
                                    Supprimer
                                </button>
                            )}
                        </div>
                        <button 
                            onClick={onSave} 
                            className="px-6 py-2 bg-blue-500 text-white font-semibold rounded-lg shadow-lg hover:bg-blue-600 transition transform hover:scale-[1.02]"
                        >
                            {isEditing ? 'Enregistrer les Modifications' : 'Créer la Réservation'}
                        </button>
                    </div>
                    
                    <p className="mt-4 text-xs text-center text-gray-400 dark:text-gray-500">Créé par l'utilisateur: {data.userId || 'N/A'}</p>
                </div>
            </div>
        );
    };


    if (isLoading) {
        return (
            <div className="flex items-center justify-center min-h-screen bg-gray-50 dark:bg-slate-900">
                <Loader2 className="w-10 h-10 text-blue-500 animate-spin" />
                <p className="ml-3 text-lg text-gray-700 dark:text-gray-300">Chargement de l'application...</p>
            </div>
        );
    }

    if (authError) {
         return (
            <div className="flex flex-col items-center justify-center min-h-screen bg-red-50 dark:bg-red-950 p-6 text-red-800 dark:text-red-300">
                <X className="w-10 h-10 mb-4" />
                <h1 className="text-2xl font-bold mb-2">Erreur d'Initialisation</h1>
                <p className="text-center">{authError}</p>
                <p className="mt-4 text-sm">Veuillez vérifier votre configuration Firebase ou réessayer.</p>
            </div>
        );
    }

    // --- Main Render (Rendu Principal) ---

    return (
        <div className="min-h-screen bg-gray-50 dark:bg-slate-900">
            {/* Navigation Bar (Barre de Navigation) */}
            <header className="shadow bg-white dark:bg-slate-800 p-4 sticky top-0 z-20">
                <div className="max-w-7xl mx-auto flex justify-between items-center">
                    <div className="flex items-center space-x-3">
                        <Hotel className="w-6 h-6 text-blue-500" />
                        <h1 className="text-xl font-bold text-gray-900 dark:text-white">Planning des Réservations</h1>
                        <p className="text-xs text-gray-500 dark:text-gray-400 ml-4 hidden sm:block">
                            Utilisateur: <span className="font-mono text-xs">{userId}</span>
                        </p>
                    </div>
                    
                    <div className="flex items-center space-x-3">
                        <button 
                            onClick={() => {
                                setEditingReservation(null);
                                // Reset new reservation data to today's date and default room
                                setNewReservationData({ 
                                    roomId: ROOM_DATA[0].id, guestName: '', checkInDate: formatDate(new Date()), checkOutDate: formatDate(addDays(new Date(), 1)), status: 'Confirmed', color: 'bg-blue-500' 
                                });
                                setIsModalOpen(true);
                            }}
                            className="flex items-center space-x-1 px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-full shadow-md hover:bg-green-600 transition transform hover:scale-[1.02]"
                        >
                            <Plus className="w-5 h-5" />
                            <span className="hidden sm:inline">Ajouter Réservation</span>
                        </button>
                        {/* Logout (Simulated) */}
                        <button 
                            title="Se déconnecter/Changer d'utilisateur"
                            className="p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700 transition-colors"
                            onClick={() => {
                                console.log("Déconnexion simulée."); 
                            }}
                        >
                            <LogOut className="w-6 h-6" />
                        </button>
                    </div>
                </div>
            </header>

            <div className="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
                
                {/* Date Controls (Contrôles de Date) */}
                <div className="flex justify-between items-center mb-6 bg-white dark:bg-slate-800 p-4 rounded-xl shadow">
                    <button 
                        onClick={() => navigateDate('prev')}
                        className="p-2 rounded-full text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition"
                    >
                        <ChevronLeft className="w-6 h-6" />
                    </button>

                    <div className="flex items-center space-x-2 text-xl font-semibold text-gray-800 dark:text-white">
                        <CalendarIcon className="w-6 h-6 text-blue-500" />
                        <span className="hidden sm:inline">Semaine du</span>
                        <span>{days[0].toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' })}</span>
                        <span>au</span>
                        <span>{days[6].toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' })}</span>
                    </div>

                    <button 
                        onClick={() => navigateDate('next')}
                        className="p-2 rounded-full text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition"
                    >
                        <ChevronRight className="w-6 h-6" />
                    </button>
                </div>
                
                {/* Column Header (Tête de Colonne - Journées) */}
                <div className="grid grid-cols-8 sticky top-[72px] bg-white dark:bg-slate-900 border-b-2 border-blue-500 shadow-lg z-10">
                    <div className="col-span-1 p-3 text-center font-bold text-gray-700 dark:text-gray-300 border-r dark:border-slate-700">Chambre</div>
                    {days.map((day, index) => (
                        <div 
                            key={index} 
                            className={`col-span-1 p-3 text-center text-sm font-semibold border-l dark:border-slate-700
                                ${formatDate(day) === formatDate(new Date()) ? 'bg-blue-500/10 text-blue-500 font-bold' : 'text-gray-600 dark:text-gray-300'}
                            `}
                        >
                            <span className="block">{day.toLocaleDateString('fr-FR', { weekday: 'short' })}</span>
                            <span className="block text-xl">{day.getDate()}</span>
                        </div>
                    ))}
                </div>

                {/* Planning Grid (Grille de Planning) */}
                <div className="overflow-x-auto">
                    <div className="min-w-768px lg:min-w-full">
                        {ROOM_DATA.map(room => (
                            <RoomRow 
                                key={room.id} 
                                room={room} 
                                days={days} 
                                reservations={reservations} 
                                onCellClick={handleCellClick}
                            />
                        ))}
                    </div>
                </div>

                <div className="mt-8 p-4 bg-yellow-100 dark:bg-yellow-900/50 rounded-lg text-yellow-800 dark:text-yellow-300">
                    <p className="flex items-center"><CheckCheck className="w-5 h-5 mr-2" /> 
                    Cette grille est en temps réel (via Firestore) et collaborative. Toute modification est immédiatement visible.</p>
                </div>
                
            </div>
            
            {/* Reservation Modal (Modale de Réservation) */}
            <ReservationModal 
                isOpen={isModalOpen}
                onClose={() => {
                    setIsModalOpen(false);
                    setEditingReservation(null); // Clear editing state when closing
                }}
                data={editingReservation || newReservationData}
                onDataChange={(key, value) => {
                    if (editingReservation) {
                        setEditingReservation(prev => ({ ...prev, [key]: value }));
                    } else {
                        setNewReservationData(prev => ({ ...prev, [key]: value }));
                    }
                }}
                onSave={handleSaveReservation}
                onDelete={handleDeleteReservation}
                isEditing={!!editingReservation}
            />
        </div>
    );
};

export default App;