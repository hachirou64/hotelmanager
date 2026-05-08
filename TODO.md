# TODO - Redesign page d’accueil (welcome)

- [ ] 1) Créer `resources/views/layouts/marketing.blade.php` (layout public, Tailwind + dark mode, sans sidebar admin)
- [ ] 2) Créer `resources/css/welcome.css` (styles spécifiques: primary, fond, tweaks UI)
- [ ] 3) Refaire `resources/views/welcome.blade.php` : `@extends('layouts.marketing')`, structure plus propre (Hero/Services/Pricing/Contact), réduire code inline
- [ ] 4) Vérifier les liens de navigation et ancres : `href="#about"`, `id` cohérents + CTA
- [ ] 5) Ajouter un bloc “Services” distinct (car tu as demandé “puis services”) si on ne l’a pas déjà (ou fusionner avec features)
- [ ] 6) Vérifier responsive (mobile menu, dark mode)
- [ ] 7) Lancer `npm run dev` et tester la page d’accueil dans le navigateur

