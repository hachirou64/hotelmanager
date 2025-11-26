# Cahier des charges — HotelManager

Date : 26 novembre 2025
Version : 1.0
Auteur : Équipe HotelManager

## 1. Contexte et objectifs

HotelManager est une application web destinée à la gestion opérationnelle d'un hôtel : gestion des chambres, réservations, clients, facturation, personnel et paramètres hôteliers. L'objectif principal est de fournir aux administrateurs et au personnel un outil simple, sécurisé et évolutif pour gérer les opérations quotidiennes et améliorer l'expérience client.

Objectifs principaux :
- Gérer l'inventaire des chambres et types de chambres.
- Traiter les réservations et les disponibilités en temps réel.
- Gérer les clients, la facturation et les paiements.
- Offrir une interface d'administration avec permissions par rôle.
- Fournir une API REST pour intégration (frontend public, application mobile, etc.).

## 2. Périmètre fonctionnel

Fonctionnalités clés :
- Authentification et gestion des utilisateurs (roles : Admin, Manager, Staff, Client).
- Gestion des types de chambre (prix de base, capacité, description, équipements).
- Gestion des chambres (numéro, type, statut, prix ajusté, images).
- Gestion des disponibilités et calendrier des réservations.
- Création, modification, annulation de réservations.
- Gestion des clients (profil, historique de réservations, documents).
- Facturation : génération de factures, suivi des paiements, historique.
- Gestion des promotions et remises.
- Centre de contact / messages client.
- Paramètres hôteliers (coordonnées, options, règles de tarification).
- API publique pour consultation de chambres et réservation (auth pour actions sensibles).

Exclusions (hors scope initial) :
- Intégration de channel managers externes (Booking, Expedia) — prévu en phase 2.
- Moteur de tarification dynamique complexe (prévu en extension).

## 3. Utilisateurs et parcours

Profils utilisateurs :
- Administrateur (Admin) : gestion complète du système, utilisateurs, paramètres, rapports.
- Manager : gestion opérationnelle : chambres, réservations, personnel, facturation.
- Personnel (Staff) : consultation et gestion quotidienne (check-in/check-out, nettoyage, notes clients).
- Client : accès limité pour consulter et gérer ses réservations via interface publique ou client.

Parcours type :
- Réceptionniste crée une réservation pour un client, génère une facture et enregistre le paiement.
- Client réserve une chambre via l'interface publique et reçoit une confirmation par e-mail.
- Manager consulte le planning des réservations et édite les tarifs ou bloque une chambre.

## 4. Modèle de données (haut niveau)

Entités principales et attributs résumés :
- User { id, name, email, password, role_id, ... }
- Role { id, name }
- RoomType { id, name, description, capacity, base_price, amenities }
- Room { id, number, room_type_id, status, floor, images, price_override }
- Client { id, name, email, phone, address, document_id }
- Reservation { id, client_id, room_id, checkin_date, checkout_date, status, total_price }
- Invoice { id, reservation_id, amount, tax, status, issued_at }
- Payment { id, invoice_id, amount, method, status, paid_at }
- Promotion { id, code, discount_type, value, start_at, end_at }
- HotelParameter { key, value }
- ContactMessage { id, name, email, message, handled_by, status }

Relations principales :
- Room belongsTo RoomType
- Reservation belongsTo Client, belongsTo Room
- Invoice belongsTo Reservation
- Payment belongsTo Invoice

## 5. API / Endpoints (exemples)

API REST (JSON) proposée :
- Auth
  - POST /api/login
  - POST /api/logout
  - POST /api/register (si applicable)
- Rooms
  - GET /api/rooms - liste (avec filtres disponibilité, type, prix)
  - GET /api/rooms/{id} - détails
- Reservations
  - GET /api/reservations
  - POST /api/reservations - création
  - GET /api/reservations/{id}
  - PUT /api/reservations/{id}
  - DELETE /api/reservations/{id}
- Clients
  - GET /api/clients
  - POST /api/clients
- Invoices / Payments
  - POST /api/invoices
  - POST /api/payments

Sécurité API : utiliser token-based (Sanctum / JWT), rôles et permissions pour restreindre les actions.

## 6. Non-fonctionnel

- Performance : la UI doit rester réactive pour 1000 requêtes simultanées en lecture avec cache (Redis) pour données non sensibles.
- Disponibilité : architecture déployable sur cluster (Docker/Kubernetes) pour tolérance aux pannes.
- Scalabilité : séparation des services (API, queue workers) pour montée en charge.
- Internationalisation : support i18n (fr/en) pour l'interface.
- Accessibilité : respecter les bases WCAG (contrastes, navigation clavier, labels formulaires).

## 7. Sécurité

- Authentification forte (hash bcrypt/argon2 pour mots de passe).
- HTTPS obligatoire en production.
- Protection CSRF sur formulaires web et CORS configuré pour API.
- Validation stricte côté serveur + sanitization des entrées.
- Permissions basées sur rôles pour toutes les routes sensibles.
- Journalisation et alerting pour évènements importants (échecs de login, modifications de réservations).

## 8. Déploiement et stack technique

Stack recommandé :
- Backend : PHP 8.1+ (Laravel 10+)
- Base de données : MySQL / MariaDB
- Cache / Queue : Redis
- Serveur web : Nginx
- Orchestration : Docker Compose pour dev, Kubernetes pour production (optionnel)
- CI/CD : GitHub Actions (tests, lint, build, déploiement)

Procédure de déploiement (synthétique) :
1. Builder l'image Docker.
2. Exécuter migrations et seeders.
3. Lancer services: app, db, redis.
4. Exposer via Nginx / load balancer.

## 9. Tests

- Tests unitaires : modèles, helpers, validations.
- Tests d'intégration : endpoints API critiques (réservations, paiements).
- Tests end-to-end (optionnel) : flux réservation complet via Cypress / Playwright.
- Coverage minimal attendu : 60%+ sur la logique métier critique.

## 10. Planification (proposition)

Phase 1 (4 semaines) - MVP :
- Auth, gestion utilisateurs/roles.
- CRUD RoomType / Room.
- Réservations : création, annulation, calendrier de base.
- Clients, facturation basique.
- API publique pour consultation de chambres.

Phase 2 (4 semaines) - Améliorations :
- Paiements et facturation complète.
- Promotions et remises.
- UI/UX améliorations, notifications e-mail.

Phase 3 (4 semaines) - Évolution et intégrations :
- Intégrations channels (optionnel), reporting avancé, tests e2e.

## 11. Critères d'acceptation

- L'utilisateur Admin peut créer/modifier/supprimer types de chambre et chambres.
- Les réservations peuvent être créées et associées à un client et à une chambre disponible.
- Les factures générées correspondent aux réservations et acceptent paiements simulés.
- L'API respecte les contrats définis et protège les routes sensibles par authentification.
- L'application passe les tests unitaires et d'intégration définis.

## 12. Livrables

- Code source hébergé sur le repo Git (branches features + PRs).
- Documentation technique et cahier des charges (ce fichier).
- Scripts d'installation et d'exécution (Docker Compose, instructions README).
- Tests automatisés et pipeline CI.

---

Remarques / prochaines étapes :
- Valider ce cahier des charges avec le client (priorités, contraintes, formats de livraison).
- Après validation, créer les issues et milestones dans le tracker (GitHub Issues).

