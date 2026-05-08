import React, { useMemo, useState } from 'react';

import ReservationPage from './components/ReservationPage';
import ReservationForm from './components/ReservationForm';
import ClientReservations from './components/ClientReservations';
import ClientReservationsPage from './components/ClientReservationsPage';
import WelcomePage from './components/WelcomePage';

import FacturationApp from './components/facturation .jsx';
import WelcomeApp from './welcome.jsx';

const App = () => {
    // Simple switcher to avoid rendering multiple apps at once.
    const pages = useMemo(
        () => [
            { key: 'reservation', label: 'ReservationPage', element: <ReservationPage /> },
            { key: 'reservation-form', label: 'ReservationForm', element: <ReservationForm /> },
            { key: 'client-reservations', label: 'ClientReservations', element: <ClientReservations /> },
            { key: 'client-reservations-page', label: 'ClientReservationsPage', element: <ClientReservationsPage /> },
            { key: 'welcome-page', label: 'WelcomePage', element: <WelcomePage /> },
            { key: 'facturation', label: 'Facturation', element: <FacturationApp /> },
            { key: 'welcome', label: 'welcome.jsx', element: <WelcomeApp /> },
        ],
        []
    );

    const [activeKey, setActiveKey] = useState(pages[0]?.key);
    const active = pages.find((p) => p.key === activeKey) || pages[0];

    return (
        <div>
            <div style={{ padding: 10, borderBottom: '1px solid #eee', display: 'flex', gap: 8, flexWrap: 'wrap' }}>
                {pages.map((p) => (
                    <button
                        key={p.key}
                        onClick={() => setActiveKey(p.key)}
                        style={{
                            padding: '6px 10px',
                            borderRadius: 8,
                            border: '1px solid #ddd',
                            background: p.key === activeKey ? '#2563eb' : '#fff',
                            color: p.key === activeKey ? '#fff' : '#111',
                            cursor: 'pointer',
                        }}
                        type="button"
                    >
                        {p.label}
                    </button>
                ))}
            </div>

            {active?.element}
        </div>
    );
};

export default App;

