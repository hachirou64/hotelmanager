@extends('layouts.marketing')

@section('title', 'Hôtel | Accueil')

@section('content')

<!-- Styles additionnels pour affiner l'ambiance -->
<style>
    .font-serif {
        font-family: 'Playfair Display', serif;
    }
    .hero-pattern {
        background: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0.6));
    }
    .transition-smooth {
        transition: all 0.3s ease-in-out;
    }
</style>

<link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- ==================== HERO AVEC FORMULAIRE RAPIDE ==================== -->
<section class="relative h-screen min-h-[700px] flex items-center justify-center bg-cover bg-center bg-fixed"
         style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');">
    
    <div class="absolute inset-0 bg-black/30"></div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <span class="text-sm tracking-widest uppercase bg-white/20 backdrop-blur-sm px-4 py-1.5 rounded-full inline-block mb-6">Bienvenue au</span>
        <h1 class="font-serif text-5xl md:text-7xl lg:text-8xl font-bold mb-6 drop-shadow-2xl">Hôtel Plaza</h1>
        <p class="text-xl md:text-2xl text-gray-100 max-w-2xl mx-auto mb-10 font-light">L'élégance parisienne, un art de vivre intemporel.</p>
        
        <!-- Formulaire de recherche rapide -->
        <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-2xl p-6 max-w-4xl mx-auto">
            <form action="{{ route('public.rooms') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-gray-800 text-sm font-semibold mb-1 text-left">Arrivée</label>
                    <input type="date" name="checkin" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-amber-600 bg-white/90">
                </div>
                <div>
                    <label class="block text-gray-800 text-sm font-semibold mb-1 text-left">Départ</label>
                    <input type="date" name="checkout" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-amber-600 bg-white/90">
                </div>
                <div>
                    <label class="block text-gray-800 text-sm font-semibold mb-1 text-left">Voyageurs</label>
                    <select name="guests" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-amber-600 bg-white/90">
                        <option>1 adulte</option>
                        <option>2 adultes</option>
                        <option>3 adultes</option>
                        <option>4 adultes</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-amber-700 hover:bg-amber-800 text-white font-semibold py-3 px-6 rounded-xl transition duration-300 shadow-lg">Vérifier disponibilité</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Curseur de défilement -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
    </div>
</section>

<!-- ==================== PRESENTATION HOTEL ==================== -->
<section class="py-20 bg-white dark:bg-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-amber-700 text-sm font-semibold tracking-wider uppercase">Notre histoire</span>
                <h2 class="font-serif text-4xl md:text-5xl text-gray-900 dark:text-white mt-2 mb-6">Un havre de paix au cœur de la ville</h2>
                <div class="w-20 h-1 bg-amber-600 mb-6"></div>
                <p class="text-gray-600 dark:text-gray-300 text-lg leading-relaxed mb-6">Depuis 1924, l'Hôtel Plaza incarne l'alliance parfaite entre le charme classique et le confort moderne. Chaque chambre raconte une histoire, chaque salon invite à la rêverie.</p>
                <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-8">Rénové avec soin, notre établissement vous offre un écrin de sérénité à deux pas des Champs-Élysées. Laissez-vous séduire par une atmosphère unique, où chaque détail est pensé pour votre bien-être.</p>
                <a href="#about" class="inline-flex items-center text-amber-700 font-semibold hover:text-amber-800 transition">
                    En savoir plus
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="rounded-2xl shadow-xl h-64 w-full object-cover" alt="Lobby">
                <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="rounded-2xl shadow-xl h-64 w-full object-cover mt-8" alt="Chambre Deluxe">
                <img src="https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="rounded-2xl shadow-xl h-48 w-full object-cover" alt="Restaurant">
                <img src="https://images.unsplash.com/photo-1560200353-ce0a76b1d6fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="rounded-2xl shadow-xl h-48 w-full object-cover mt-4" alt="Spa">
            </div>
        </div>
    </div>
</section>

<!-- ==================== CHAMBRES EMBLÉMATIQUES ==================== -->
<section class="py-20 bg-gray-50 dark:bg-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="text-amber-700 text-sm font-semibold tracking-wider uppercase">Séjours sur mesure</span>
            <h2 class="font-serif text-4xl md:text-5xl text-gray-900 dark:text-white mt-2">Nos chambres & suites</h2>
            <div class="w-20 h-1 bg-amber-600 mx-auto mt-4"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Chambre Classique -->
            <div class="bg-white dark:bg-slate-700 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-smooth">
                <img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="h-64 w-full object-cover" alt="Chambre Classique">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-2xl font-serif font-bold text-gray-900 dark:text-white">Classique</h3>
                        <span class="text-amber-700 font-bold">À partir de 180€</span>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">Lit queen-size, salle de bain italienne, vue cour intérieure. Confort et charme parisien.</p>
                    <a href="{{ route('public.rooms') }}" class="inline-block bg-amber-700 hover:bg-amber-800 text-white font-semibold px-5 py-2 rounded-full transition w-full text-center">Découvrir</a>
                </div>
            </div>
            <!-- Chambre Deluxe -->
            <div class="bg-white dark:bg-slate-700 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-smooth">
                <img src="https://images.unsplash.com/photo-1591088398332-8a7791972844?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="h-64 w-full object-cover" alt="Chambre Deluxe">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-2xl font-serif font-bold text-gray-900 dark:text-white">Deluxe</h3>
                        <span class="text-amber-700 font-bold">À partir de 280€</span>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">King-size, balcon, vue sur la ville, salon d'angle, produits de luxe.</p>
                    <a href="{{ route('public.rooms') }}" class="inline-block bg-amber-700 hover:bg-amber-800 text-white font-semibold px-5 py-2 rounded-full transition w-full text-center">Découvrir</a>
                </div>
            </div>
            <!-- Suite Présidentielle -->
            <div class="bg-white dark:bg-slate-700 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-smooth">
                <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="h-64 w-full object-cover" alt="Suite">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-2xl font-serif font-bold text-gray-900 dark:text-white">Suite Présidentielle</h3>
                        <span class="text-amber-700 font-bold">À partir de 450€</span>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">Terrasse privée, jacuzzi, home cinéma, service majordome inclus.</p>
                    <a href="{{ route('public.rooms') }}" class="inline-block bg-amber-700 hover:bg-amber-800 text-white font-semibold px-5 py-2 rounded-full transition w-full text-center">Découvrir</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== SERVICES & EXPÉRIENCES ==================== -->
<section class="py-20 bg-white dark:bg-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="text-amber-700 text-sm font-semibold tracking-wider uppercase">Services d'exception</span>
            <h2 class="font-serif text-4xl md:text-5xl text-gray-900 dark:text-white">Votre confort, notre passion</h2>
            <div class="w-20 h-1 bg-amber-600 mx-auto mt-4"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-6 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                <div class="w-16 h-16 bg-amber-100 text-amber-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6l4 2"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path></svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Spa & bien-être</h3>
                <p class="text-gray-600 dark:text-gray-400">Espace détente, sauna, massages sur réservation pour une relaxation absolue.</p>
            </div>
            <div class="text-center p-6 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                <div class="w-16 h-16 bg-amber-100 text-amber-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 21h6M12 15v6"></path></svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Restaurant gastronomique</h3>
                <p class="text-gray-600 dark:text-gray-400">Cuisine étoilée, produits frais, terrasse ombragée. Dîner aux chandelles.</p>
            </div>
            <div class="text-center p-6 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                <div class="w-16 h-16 bg-amber-100 text-amber-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Conciergerie 24/7</h3>
                <p class="text-gray-600 dark:text-gray-400">Service personnalisé : réservations théâtre, excursions, transferts aéroport.</p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== GALERIE PHOTOS (grille) ==================== -->
<section class="py-16 bg-gray-100 dark:bg-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="font-serif text-3xl md:text-4xl text-center text-gray-900 dark:text-white mb-8">Instants précieux</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <img src="https://images.unsplash.com/photo-1578683010236-d716f9a3f461?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="rounded-xl h-48 w-full object-cover hover:scale-105 transition duration-500">
            <img src="https://images.unsplash.com/photo-1584132967334-10e028bd69f7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="rounded-xl h-48 w-full object-cover hover:scale-105 transition duration-500">
            <img src="https://images.unsplash.com/photo-1602002418082-dd7c0b2d9f32?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="rounded-xl h-48 w-full object-cover hover:scale-105 transition duration-500">
            <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="rounded-xl h-48 w-full object-cover hover:scale-105 transition duration-500">
        </div>
    </div>
</section>

<!-- ==================== TÉMOIGNAGES ==================== -->
<section class="py-20 bg-white dark:bg-slate-900">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="text-amber-700 text-sm font-semibold tracking-wider uppercase">Ils nous ont fait confiance</span>
        <h2 class="font-serif text-4xl md:text-5xl text-gray-900 dark:text-white mt-2">Avis clients</h2>
        <div class="w-20 h-1 bg-amber-600 mx-auto mt-4 mb-12"></div>
        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-gray-50 dark:bg-slate-800 p-6 rounded-2xl shadow">
                <div class="flex text-amber-500 mb-3">★★★★★</div>
                <p class="italic text-gray-700 dark:text-gray-300">"Un séjour absolument parfait. Le personnel est aux petits soins, la chambre sublime, le petit-déjeuner digne d'un palace. Je reviendrai!"</p>
                <p class="font-semibold mt-4">— Sophie M.</p>
            </div>
            <div class="bg-gray-50 dark:bg-slate-800 p-6 rounded-2xl shadow">
                <div class="flex text-amber-500 mb-3">★★★★★</div>
                <p class="italic text-gray-700 dark:text-gray-300">"L'hôtel Plaza est une pépite. La décoration raffinée, le calme, la literie exceptionnelle. Mention spéciale pour le spa."</p>
                <p class="font-semibold mt-4">— Thomas L.</p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== APPEL À RÉSERVER ==================== -->
<section class="py-24 bg-cover bg-center bg-fixed" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');">
    <div class="max-w-4xl mx-auto text-center text-white px-4">
        <h2 class="font-serif text-4xl md:text-6xl mb-6">Réservez votre séjour</h2>
        <p class="text-xl mb-8">Offrez-vous l’expérience d’un séjour d’exception, entre luxe discrétion et raffinement.</p>
        <a href="{{ route('public.rooms') }}" class="inline-block bg-white text-amber-800 hover:bg-amber-800 hover:text-white font-bold py-3 px-8 rounded-full text-lg transition duration-300 shadow-xl">Voir les disponibilités</a>
    </div>
</section>

<!-- ==================== FOOTER ==================== -->
<footer class="bg-gray-900 text-gray-300 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
        <div>
            <h3 class="font-serif text-2xl text-white mb-4">Hôtel Plaza</h3>
            <p class="text-sm">123 avenue des Hôtels<br>75008 Paris, France</p>
            <p class="mt-2 text-sm">📞 +33 1 23 45 67 89<br>✉️ contact@hotelplaza.com</p>
        </div>
        <div>
            <h4 class="font-semibold text-white mb-4">Navigation</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="#" class="hover:text-amber-400">Accueil</a></li>
                <li><a href="#rooms" class="hover:text-amber-400">Chambres</a></li>
                <li><a href="#services" class="hover:text-amber-400">Services</a></li>
                <li><a href="#contact" class="hover:text-amber-400">Contact</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-semibold text-white mb-4">Légal</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="#" class="hover:text-amber-400">Mentions légales</a></li>
                <li><a href="#" class="hover:text-amber-400">Politique de confidentialité</a></li>
                <li><a href="#" class="hover:text-amber-400">CGV</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-semibold text-white mb-4">Newsletter</h4>
            <form class="flex flex-col gap-2">
                <input type="email" placeholder="Votre email" class="px-4 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white">
                <button class="bg-amber-700 hover:bg-amber-600 text-white px-4 py-2 rounded-lg transition">S'abonner</button>
            </form>
            <div class="flex space-x-4 mt-6">
                <a href="#" class="text-gray-400 hover:text-white">FB</a>
                <a href="#" class="text-gray-400 hover:text-white">IG</a>
                <a href="#" class="text-gray-400 hover:text-white">X</a>
            </div>
        </div>
    </div>
    <div class="text-center text-sm text-gray-500 mt-12 pt-8 border-t border-gray-800">© 2025 Hôtel Plaza - Tous droits réservés.</div>
</footer>

@endsection