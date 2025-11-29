# TODO: Implement Payment-Required Room Status Logic & User Account-Based Reservations

## Objective
1. Ensure rooms are only marked as "occupied" or "reserved" if the corresponding reservation has been paid (status 'confirmée'). Unpaid reservations ('en cours') should not affect room availability or status.
2. Allow clients to create accounts and log in with email to make reservations, with automatic client profile association.

## Tasks
- [x] Modify ReservationController::store() - Update availability check to only consider 'confirmée' reservations as blocking overlaps
- [x] Modify ReservationController::refreshRoomStatus() - Change logic to only consider 'confirmée' reservations for setting room status
- [x] Modify Client model - Add user_id to fillable fields for better user association
- [x] Modify ReservationController::store() - Prioritize user_id association for logged-in users, link existing clients to user accounts
- [x] Update book-room.blade.php - Add user status indicators and login prompts for better UX
- [ ] Test the changes to ensure unpaid reservations don't mark rooms as occupied/reserved
- [ ] Verify that successful payments properly update room status via webhook
- [ ] Test user account-based reservations and automatic client profile linking

## Files to Edit
- app/Http/Controllers/ReservationController.php
- app/Models/Client.php
- resources/views/book-room.blade.php

## Notes
- Current flow: Reservation created ('en cours') -> marks room 'réservée' -> Payment success -> Reservation 'confirmée' -> Room status refreshed
- New flow: Reservation created ('en cours') -> room remains 'libre' -> Payment success -> Reservation 'confirmée' -> Room marked 'réservée'/'occupée'
- Availability check should allow overlapping unpaid reservations
- Clients can now create accounts and log in with email for seamless reservations
- Logged-in users have their client profiles automatically created/linked
- Existing clients can be linked to user accounts upon login
