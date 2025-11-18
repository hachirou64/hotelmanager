<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\RoomType;
use App\Models\Room;
use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create(['nom_role' => 'Admin']);
        $managerRole = Role::create(['nom_role' => 'Manager']);
        $staffRole = Role::create(['nom_role' => 'Staff']);

        // Create an admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@hotel.com',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id_role,
            'preferences' => json_encode(['theme' => 'light']),
        ]);

        // Create room types
        $singleRoom = RoomType::create([
            'nom_type' => 'Chambre Simple',
            'description' => 'Chambre avec un lit simple',
            'prix_base' => 50.00,
        ]);

        $doubleRoom = RoomType::create([
            'nom_type' => 'Chambre Double',
            'description' => 'Chambre avec un lit double',
            'prix_base' => 80.00,
        ]);

        $suiteRoom = RoomType::create([
            'nom_type' => 'Suite',
            'description' => 'Suite luxueuse avec salon',
            'prix_base' => 150.00,
        ]);

        // Create rooms
        Room::create([
            'numero_chambre' => '101',
            'type_chambre' => $singleRoom->id_type,
            'statut' => 'libre',
            'capacite_max' => 1,
        ]);

        Room::create([
            'numero_chambre' => '102',
            'type_chambre' => $doubleRoom->id_type,
            'statut' => 'libre',
            'capacite_max' => 2,
        ]);

        Room::create([
            'numero_chambre' => '201',
            'type_chambre' => $suiteRoom->id_type,
            'statut' => 'libre',
            'capacite_max' => 4,
        ]);

        // Create clients
        Client::create([
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'adresse_email' => 'jean.dupont@example.com',
            'telephone' => '+33123456789',
            'historique_sejours' => json_encode(['2023-01-15', '2023-06-20']),
            'preferences' => json_encode(['vue_mer' => true, 'climatisation' => true]),
        ]);

        Client::create([
            'nom' => 'Martin',
            'prenom' => 'Marie',
            'adresse_email' => 'marie.martin@example.com',
            'telephone' => '+33987654321',
            'historique_sejours' => json_encode(['2023-03-10']),
            'preferences' => json_encode(['animaux_acceptes' => true]),
        ]);

        Client::create([
            'nom' => 'Dubois',
            'prenom' => 'Pierre',
            'adresse_email' => 'pierre.dubois@example.com',
            'telephone' => '+33555666777',
            'historique_sejours' => json_encode([]),
            'preferences' => json_encode(['wifi_gratuit' => true]),
        ]);
    }
}
