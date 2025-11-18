<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Réservations - Hôtel Manager</title>

    @viteReactRefresh
    @vite(['resources/js/app.jsx', 'resources/css/app.css'])
</head>
<body>
    <!-- L’ID doit être "root" car dans app.jsx on monte React sur "root" -->
    <div id="root"></div>

    <script>
        // Mobile sidebar toggle for React app
        window.mobileSidebarToggle = function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-sidebar-overlay');
            if (sidebar && overlay) {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            }
        };

        window.closeMobileSidebar = function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-sidebar-overlay');
            if (sidebar && overlay) {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        };
    </script>
</body>
</html>
