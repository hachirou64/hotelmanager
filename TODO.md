# TODO: Implement Reservation Functionality for Clients and Admins

## Database & Models
- [x] Create migration to add 'user_id' to clients table
- [x] Update Client model to add belongsTo User relationship
- [x] Update User model to add hasOne Client relationship
- [x] Update AuthController register method to create Client record linked to User

## Client-Side API
- [x] Add client-specific routes in api.php (/api/client/reservations) with auth middleware
- [x] Create ClientReservationController with index, store, update, destroy methods
- [x] Implement availability check in store method (no overlapping reservations)
- [x] Implement modify reservation logic (only if 'en cours')

## Admin-Side Enhancements
- [x] Update ReservationController to include 'payée' in status validation
- [x] Add filtering methods in ReservationController (by date, status)
- [ ] Update reservations.blade.php to include action buttons (confirm, cancel, mark paid, change room)

## Frontend Updates
- [ ] Update ReservationForm.jsx to check availability before submission
- [x] Update ClientReservations.jsx to enable modify functionality (redirect to form with pre-filled data)
- [ ] Ensure public-rooms.blade.php shows only available rooms or integrates date selection

## Additional Features
- [x] Add route for client reservations view (/client-reservations) in web.php
- [ ] Implement modify reservation: pre-fill form with existing data

## Testing
- [ ] Test client reservation creation, viewing, modifying, canceling
- [ ] Test admin reservation management (view all, filter, actions)
- [ ] Test availability checks
- [ ] Ensure authentication and authorization work correctly
