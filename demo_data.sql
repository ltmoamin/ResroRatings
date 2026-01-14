-- ============================================================================
-- RestoRatings Demo Data
-- Données de démonstration pour présentation
-- Version: 1.0
-- Date: 2026-01-14
-- ============================================================================

-- ============================================================================
-- 1. USERS (Utilisateurs)
-- Mot de passe pour tous: "password123" (hashé avec bcrypt)
-- ============================================================================

INSERT INTO user (iduser, username, email, role, password, firstname, lastname, tel, address, reset_token, is_blocked, is_approved, etat, status) VALUES
-- Admin
(1, 'admin', 'admin@restoratings.tn', '["ROLE_ADMIN"]', '$2y$13$hK1aM8rEjVfT4w6gH3nL7OE.8zDqX5kL9YbPmNcR2sA4wU6xZ0eKi', 'Mohamed', 'Ben Ali', '+216 71 234 567', '123 Avenue Habib Bourguiba, Tunis 1000', NULL, 0, 1, 'Actif', 'Actif'),

-- Utilisateurs
(2, 'chef_karim', 'karim.benali@gmail.com', '["ROLE_USER"]', '$2y$13$hK1aM8rEjVfT4w6gH3nL7OE.8zDqX5kL9YbPmNcR2sA4wU6xZ0eKi', 'Karim', 'Ben Ali', '+216 22 333 444', '45 Rue de la République, La Marsa', NULL, 0, 1, 'Actif', 'Actif'),
(3, 'fatma_gourmet', 'fatma.trabelsi@outlook.com', '["ROLE_USER"]', '$2y$13$hK1aM8rEjVfT4w6gH3nL7OE.8zDqX5kL9YbPmNcR2sA4wU6xZ0eKi', 'Fatma', 'Trabelsi', '+216 25 888 999', '78 Avenue de Carthage, Sidi Bou Said', NULL, 0, 1, 'Actif', 'Actif'),
(4, 'omar_cuisine', 'omar.mansour@yahoo.fr', '["ROLE_USER"]', '$2y$13$hK1aM8rEjVfT4w6gH3nL7OE.8zDqX5kL9YbPmNcR2sA4wU6xZ0eKi', 'Omar', 'Mansour', '+216 50 111 222', '12 Rue Ibn Khaldoun, Hammamet', NULL, 0, 1, 'Actif', 'Actif'),

-- Autres Utilisateurs
(5, 'sarah_foodie', 'sarah.mejri@gmail.com', '["ROLE_USER"]', '$2y$13$hK1aM8rEjVfT4w6gH3nL7OE.8zDqX5kL9YbPmNcR2sA4wU6xZ0eKi', 'Sarah', 'Mejri', '+216 55 444 555', '34 Rue de Marseille, Tunis', NULL, 0, 1, 'Actif', 'Actif'),
(6, 'youssef_gourmet', 'youssef.hamdi@hotmail.com', '["ROLE_USER"]', '$2y$13$hK1aM8rEjVfT4w6gH3nL7OE.8zDqX5kL9YbPmNcR2sA4wU6xZ0eKi', 'Youssef', 'Hamdi', '+216 98 765 432', '56 Avenue Mohamed V, Sousse', NULL, 0, 1, 'Actif', 'Actif'),
(7, 'ines_taste', 'ines.bouazizi@gmail.com', '["ROLE_USER"]', '$2y$13$hK1aM8rEjVfT4w6gH3nL7OE.8zDqX5kL9YbPmNcR2sA4wU6xZ0eKi', 'Ines', 'Bouazizi', '+216 29 876 543', '23 Rue de Paris, Sfax', NULL, 0, 1, 'Actif', 'Actif'),
(8, 'ahmed_critic', 'ahmed.jebali@yahoo.com', '["ROLE_USER"]', '$2y$13$hK1aM8rEjVfT4w6gH3nL7OE.8zDqX5kL9YbPmNcR2sA4wU6xZ0eKi', 'Ahmed', 'Jebali', '+216 99 123 456', '89 Boulevard du 7 Novembre, Monastir', NULL, 0, 1, 'Actif', 'Actif'),
(9, 'leila_explorer', 'leila.khedher@gmail.com', '["ROLE_USER"]', '$2y$13$hK1aM8rEjVfT4w6gH3nL7OE.8zDqX5kL9YbPmNcR2sA4wU6xZ0eKi', 'Leila', 'Khedher', '+216 52 789 012', '67 Avenue de la Liberté, Bizerte', NULL, 0, 1, 'Actif', 'Actif'),
(10, 'mehdi_foodlover', 'mehdi.gharbi@outlook.com', '["ROLE_USER"]', '$2y$13$hK1aM8rEjVfT4w6gH3nL7OE.8zDqX5kL9YbPmNcR2sA4wU6xZ0eKi', 'Mehdi', 'Gharbi', '+216 23 456 789', '101 Rue de Rome, Tozeur', NULL, 0, 1, 'Actif', 'Actif');


-- ============================================================================
-- 2. RESTAURANTS
-- ============================================================================

INSERT INTO restaurant (id_restau, nom, location) VALUES
(1, 'Le Méditerranéen', 'La Marsa, Tunis'),
(2, 'Dar El Jeld', 'Médina de Tunis'),
(3, 'La Closerie', 'Lac 2, Tunis'),
(4, 'El Ali', 'Sidi Bou Said'),
(5, 'Le Baroque', 'Gammarth, Tunis'),
(6, 'Cap Carthage', 'Carthage'),
(7, 'Chez Nous', 'Hammamet'),
(8, 'La Spiga', 'Sousse'),
(9, 'Le Pirate', 'Port de Sfax'),
(10, 'Dar Zarrouk', 'Sidi Bou Said');

-- ============================================================================
-- 3. PLATS (avec images d'internet haute qualité)
-- ============================================================================

INSERT INTO plat (idplat, nom, description, image, prix, categorie) VALUES
-- Entrées
(1, 'Brik à l''Oeuf', 'Croustillant traditionnel tunisien garni d''un oeuf, persil et câpres', 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=800', 8.50, 'Entrée'),
(2, 'Salade Mechouia', 'Salade de poivrons grillés, tomates et ail, assaisonnée à l''huile d''olive', 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=800', 7.00, 'Entrée'),
(3, 'Chorba Tunisienne', 'Soupe traditionnelle aux légumes et pâtes fines à la coriandre', 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=800', 6.50, 'Entrée'),
(4, 'Ojja aux Crevettes', 'Oeufs pochés dans une sauce tomate épicée aux crevettes', 'https://images.unsplash.com/photo-1482049016gy0-d8c2c0cdf3c9?w=800', 14.00, 'Entrée'),
(5, 'Tartare de Thon', 'Thon frais de Méditerranée mariné aux agrumes et herbes fraîches', 'https://images.unsplash.com/photo-1579631542720-3a87824fff86?w=800', 16.00, 'Entrée'),

-- Plats Principaux
(6, 'Couscous Royal', 'Couscous traditionnel aux légumes, merguez, poulet et agneau', 'https://images.unsplash.com/photo-1541518763669-27fef04b14ea?w=800', 25.00, 'Plat Principal'),
(7, 'Tajine Tunisien', 'Gratin aux oeufs, pommes de terre, viande hachée et fromage', 'https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=800', 18.00, 'Plat Principal'),
(8, 'Poisson Grillé', 'Loup de mer grillé au charbon de bois, servi avec légumes de saison', 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=800', 32.00, 'Plat Principal'),
(9, 'Kamounia', 'Ragoût de foie de veau au cumin, spécialité tunisoise', 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=800', 22.00, 'Plat Principal'),
(10, 'Pâtes aux Fruits de Mer', 'Linguine aux crevettes, moules et calamars dans une sauce marinara', 'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?w=800', 28.00, 'Plat Principal'),
(11, 'Méchoui d''Agneau', 'Épaule d''agneau rôtie aux herbes méditerranéennes', 'https://images.unsplash.com/photo-1544025162-d76694265947?w=800', 35.00, 'Plat Principal'),
(12, 'Brick Malsouka au Poulet', 'Feuilleté croustillant farci au poulet et champignons', 'https://images.unsplash.com/photo-1432139555190-58524dae6a55?w=800', 20.00, 'Plat Principal'),

-- Desserts
(13, 'Makroudh', 'Gâteau de semoule farci aux dattes et arrosé de miel', 'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?w=800', 5.00, 'Dessert'),
(14, 'Baklava Tunisien', 'Feuilleté aux amandes et pistaches, parfumé à l''eau de fleur d''oranger', 'https://images.unsplash.com/photo-1519676867240-f03562e64548?w=800', 6.50, 'Dessert'),
(15, 'Assidat Zgougou', 'Crème de pignons de pin d''Alep, dessert du Mouled', 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=800', 7.00, 'Dessert'),
(16, 'Crème Brûlée à l''Orange', 'Crème onctueuse parfumée à l''orange de Nabeul', 'https://images.unsplash.com/photo-1470324161839-ce2bb6fa6bc3?w=800', 8.00, 'Dessert'),
(17, 'Fruits de Saison', 'Sélection de fruits frais tunisiens', 'https://images.unsplash.com/photo-1619566636858-adf3ef46400b?w=800', 9.00, 'Dessert'),

-- Boissons
(18, 'Thé à la Menthe', 'Thé vert traditionnel aux pignons et menthe fraîche', 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?w=800', 3.50, 'Boisson'),
(19, 'Citronnade Maison', 'Citrons frais pressés avec menthe et fleur d''oranger', 'https://images.unsplash.com/photo-1621263764928-df1444c5e859?w=800', 5.00, 'Boisson'),
(20, 'Café Turc', 'Café traditionnel servi dans une tasse en cuivre', 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=800', 3.00, 'Boisson');

-- ============================================================================
-- 4. EVENNEMENTS (avec images haute qualité)
-- ============================================================================

INSERT INTO evennement (idevent, titre, description, date, img, lieu, adresse) VALUES
(1, 'Festival Gastronomique de Tunis', 'Célébration de la cuisine tunisienne avec les meilleurs chefs du pays. Dégustations, ateliers culinaires et démonstrations.', '2026-02-15', 'https://images.unsplash.com/photo-1555244162-803834f70033?w=800', 'Cité de la Culture', 'Avenue Mohamed V, Tunis'),
(2, 'Soirée Jazz & Tapas', 'Une soirée musicale avec jazz live et tapas méditerranéennes dans un cadre intimiste.', '2026-02-20', 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=800', 'Le Baroque', 'Gammarth, Tunis'),
(3, 'Atelier Couscous Traditionnel', 'Apprenez les secrets du couscous tunisien authentique avec Chef Fatma.', '2026-02-25', 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=800', 'Dar El Jeld', 'Médina de Tunis'),
(4, 'Wine Tasting: Vins de Tunisie', 'Découverte des meilleurs crus tunisiens de Mornag et du Cap Bon.', '2026-03-05', 'https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?w=800', 'Cap Carthage', 'Carthage'),
(5, 'Brunch Méditerranéen', 'Brunch dominical avec vue sur la mer, buffet et animation musicale.', '2026-03-08', 'https://images.unsplash.com/photo-1504754524776-8f4f37790ca0?w=800', 'Le Méditerranéen', 'La Marsa'),
(6, 'Nuit des Chefs', 'Événement exclusif avec 5 chefs étoilés qui préparent un menu dégustation.', '2026-03-15', 'https://images.unsplash.com/photo-1577219491135-ce391730fb2c?w=800', 'La Closerie', 'Lac 2, Tunis'),
(7, 'Sunset BBQ Party', 'Barbecue en bord de mer avec DJ et ambiance festive.', '2026-03-22', 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=800', 'Chez Nous', 'Hammamet'),
(8, 'Masterclass Pâtisserie', 'Initiation aux secrets de la pâtisserie tunisienne et française.', '2026-04-01', 'https://images.unsplash.com/photo-1586985289688-ca3cf47d3e6e?w=800', 'El Ali', 'Sidi Bou Said');

-- ============================================================================
-- 5. AVIS (Reviews)
-- ============================================================================

INSERT INTO avis (id, pubavis, titreavis, dateavis, iduser, id_restau, nbvue) VALUES
(1, 'Une expérience culinaire exceptionnelle ! Le couscous royal était parfaitement préparé, les légumes tendres et la viande fondante. Service impeccable et cadre magnifique avec vue sur la mer. Je recommande vivement !', 'Excellent couscous royal', '2026-01-10', 5, 1, 156),
(2, 'Dar El Jeld reste une institution de la cuisine tunisienne. L''ambiance dans cette maison traditionnelle de la Médina est unique. Les plats sont authentiques et savoureux. Un peu cher mais ça vaut le détour.', 'Institution tunisienne', '2026-01-08', 6, 2, 234),
(3, 'Première visite à La Closerie et nous avons été conquis. Le poisson était d''une fraîcheur remarquable, la sauce délicate. Seul bémol : l''attente un peu longue mais le résultat en valait la peine.', 'Poisson frais et délicieux', '2026-01-05', 7, 3, 89),
(4, 'Vue imprenable sur le golfe de Tunis depuis la terrasse. La salade mechouia était authentique et le tajine bien épicé. Personnel accueillant et attentionné. Une adresse à retenir !', 'Vue magnifique, cuisine savoureuse', '2026-01-03', 8, 4, 178),
(5, 'Le Baroque propose une cuisine fusion intéressante. Le décor est somptueux et l''ambiance chic. Les portions sont généreuses et les saveurs bien équilibrées. Parfait pour une soirée spéciale.', 'Cuisine fusion réussie', '2026-01-02', 9, 5, 210),
(6, 'Cap Carthage offre une expérience gastronomique haut de gamme. Le menu dégustation était un voyage culinaire mémorable. Prix élevés mais qualité au rendez-vous.', 'Gastronomie haut de gamme', '2025-12-28', 10, 6, 145),
(7, 'Chez Nous à Hammamet, c''est comme manger chez sa grand-mère ! Cuisine familiale, portions généreuses et prix très raisonnables. Ambiance chaleureuse et propriétaires adorables.', 'Comme à la maison', '2025-12-25', 5, 7, 267),
(8, 'La Spiga propose les meilleures pâtes de Sousse. Les fruits de mer sont ultra frais et la cuisson parfaite. Le tiramisu maison est à tomber. On reviendra !', 'Meilleures pâtes de la région', '2025-12-20', 6, 8, 198);

-- ============================================================================
-- 6. BADGES
-- ============================================================================

INSERT INTO badge (id, commantaire, datebadge, typebadge, iduser, id_restau, likes, dislikes) VALUES
(1, 'Excellence culinaire reconnue par notre communauté', '2026-01-12', 'Diamant', 2, 1, 45, 2),
(2, 'Restaurant authentique et traditionnel', '2026-01-10', 'VIP', 3, 2, 38, 1),
(3, 'Service et qualité exceptionnels', '2026-01-08', 'Silver', 4, 3, 25, 3),
(4, 'Vue panoramique unique', '2026-01-05', 'VIP', 2, 4, 32, 2),
(5, 'Ambiance et cuisine fusion remarquables', '2026-01-03', 'Diamant', 3, 5, 41, 1),
(6, 'Gastronomie de prestige', '2025-12-30', 'Diamant', 4, 6, 48, 0),
(7, 'Cuisine familiale authentique', '2025-12-28', 'Silver', 2, 7, 22, 4),
(8, 'Spécialités italiennes savoureuses', '2025-12-25', 'VIP', 3, 8, 35, 2);

-- ============================================================================
-- 7. ACHATS
-- ============================================================================

INSERT INTO achat (idachat, montanttotal, quantite, date, type, iduser, idplat) VALUES
(1, 50.00, 2, '2026-01-14', 'sur place', 5, 6),
(2, 32.00, 1, '2026-01-13', 'livraison', 6, 8),
(3, 36.00, 2, '2026-01-12', 'sur place', 7, 7),
(4, 56.00, 2, '2026-01-11', 'livraison', 8, 10),
(5, 70.00, 2, '2026-01-10', 'sur place', 9, 11),
(6, 17.00, 2, '2026-01-09', 'sur place', 10, 1),
(7, 75.00, 3, '2026-01-08', 'livraison', 5, 6),
(8, 44.00, 2, '2026-01-07', 'sur place', 6, 9);

-- ============================================================================
-- 8. RECLAMATIONS
-- ============================================================================

INSERT INTO reclamation (idrec, date, description, typerec, etatrec, iduser) VALUES
(1, '2026-01-12', 'La livraison a pris plus d''une heure alors que le restaurant est à 15 minutes. La nourriture était froide à l''arrivée.', 'Livraison', 'En cours', 5),
(2, '2026-01-10', 'J''ai trouvé un cheveu dans mon plat. C''est inacceptable pour un restaurant de ce standing. Je demande un remboursement.', 'Qualité', 'Résolu', 6),
(3, '2026-01-08', 'Le serveur était impoli et nous a fait attendre 45 minutes pour prendre notre commande durant l''heure du déjeuner.', 'Service', 'En cours', 7),
(4, '2026-01-05', 'La facture ne correspondait pas aux prix affichés sur le menu. On nous a compté 5 dinars de plus par plat.', 'Facturation', 'Nouveau', 8),
(5, '2026-01-03', 'Réservation non honorée malgré confirmation téléphonique. Nous avons dû attendre 30 minutes debout.', 'Reservation', 'Résolu', 9);

-- ============================================================================
-- 9. RESERVATIONS
-- ============================================================================

INSERT INTO reservation (id_res, datereser, timereser, id_user, id_restau) VALUES
(1, '2026-01-20', '19:30:00', 5, 1),
(2, '2026-01-21', '20:00:00', 6, 2),
(3, '2026-01-22', '12:30:00', 7, 3),
(4, '2026-01-23', '21:00:00', 8, 5),
(5, '2026-01-25', '19:00:00', 9, 4),
(6, '2026-01-26', '20:30:00', 10, 6),
(7, '2026-01-28', '13:00:00', 5, 8),
(8, '2026-01-30', '19:30:00', 6, 7);

-- ============================================================================
-- 10. PARTICIPANTS (Inscriptions aux événements)
-- ============================================================================

INSERT INTO participant (idparticipant, datepar, numero, idevent, iduser) VALUES
(1, '2026-01-14', 71234567, 1, 5),
(2, '2026-01-13', 98765432, 1, 6),
(3, '2026-01-12', 55444555, 2, 7),
(4, '2026-01-11', 29876543, 3, 8),
(5, '2026-01-10', 52789012, 4, 9),
(6, '2026-01-09', 23456789, 5, 10),
(7, '2026-01-08', 71234567, 6, 5),
(8, '2026-01-07', 98765432, 7, 6);

-- ============================================================================
-- Fin du fichier SQL de démonstration
-- ============================================================================
