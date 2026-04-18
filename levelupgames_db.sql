-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mariadb
-- Generation Time: Apr 18, 2026 at 07:54 PM
-- Server version: 10.11.16-MariaDB-ubu2204
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `levelupgames_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `name`, `email`, `password_hash`) VALUES
(1, 'Test', 'Test@example.com', '$2y$10$4imuSriDk4s00h.q1anmXufNSDHgHW37.PQ7wHE4hngsjmNz0HnDe'),
(2, 'John', 'John@example.com', '$2y$10$Q80xT681hcpYgudf1hqel.eGdgs4Jpq6.RwTwXpe7m76p0mq88qFG'),
(12, 'Example', 'Example@example.com', '$2y$10$dfcQyuichRtn144Q1v7vUeJCIHeLid77I9HOtRZgp1BguScy0ocyG');

-- --------------------------------------------------------

--
-- Table structure for table `basket`
--

CREATE TABLE `basket` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(11) NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(3) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `filters`
--

CREATE TABLE `filters` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `filters`
--

INSERT INTO `filters` (`id`, `name`) VALUES
(5, 'DLC'),
(4, 'NINTENDO'),
(6, 'OTHER'),
(1, 'PC'),
(2, 'PS'),
(3, 'XBOX');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_filter_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `rrp_price` decimal(10,2) NOT NULL,
  `discount` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `image` varchar(255) NOT NULL DEFAULT 'assets/items/placeholder.png',
  `release_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_filter_id`, `title`, `description`, `rrp_price`, `discount`, `image`, `release_date`) VALUES
(1, 4, 'Kirby Air Riders', 'Race through colorful worlds on customizable Air Ride machines, mastering boosts, shortcuts, and special powers. Kirby Air Riders blends speed, charm, and competitive chaos into an addictive racing experience.', 58.99, 13, 'assets/items/Kirby Air Riders.png', '2025-11-25'),
(2, 4, 'Nintendo Switch + Mario Kart 8 Deluxe (+DLC) + Nintendo Switch Online 365 Days', 'Whether you’re racing your family on the big screen in your living room, playing in the park, or visiting a friend’s place, Nintendo Switch lets you play Mario Kart any way you like!\r\nRace on 48 remastered courses from across the Mario Kart series with the Mario Kart 8 Deluxe – Booster Course Pass! Six waves will be released, each including eight courses that can be played locally and online with 365 days of Nintendo Switch Online included in this purchase!', 329.99, 5, 'assets/items/Nintendo Switch + Mario Kart 8 Deluxe (+DLC) + Nintendo Switch Online 365 Days.png', '2017-04-28'),
(3, 6, ' 3 Months Xbox Game Pass Ultimate', ' 3 Months Xbox Game Pass Ultimate \r\n\r\nPlay hundreds of games\r\n\r\nAccess a growing library of console and PC games with new games added each month.\r\nPC, TV and console icon\r\nPlay on your devices\r\n\r\nPlay on Xbox console, PC and more devices with cloud gaming.\r\nAward ribbon icon\r\nEnjoy benefits & rewards\r\n\r\nGet in-game benefits, online console multiplayer, exclusive discounts and more rewards.\r\nDiscover your next favourite game\r\n\r\nExplore hundreds of games from every genre.\r\nWith new games added all the time, there\'s always something new to play.\r\n', 68.99, 25, 'assets/items/3 Months Xbox Game Pass Ultimate.png', '2019-06-09'),
(4, 4, 'Pokemon Pokopia', '\r\nCraft cosy towns with Pokémon!\r\n\r\nSavour the slow life in Pokémon Pokopia – a relaxing life simulation game devoted to crafting, creating, and building – only for Nintendo Switch 2.\r\nCultivate a new world\r\nPokémon and people once lived happily together, but the world has withered and the humans are gone. The only remaining resident appears to be a lone Tangrowth.\r\nAfter waking from a long slumber, a peculiar Ditto decides to restore the desolate land using its transformation skills and its surprising new crafting abilities.\r\nBuild a Pokémon paradise\r\n\r\nPlay as a Ditto and build a charming world alongside your Pokémon friends. After a satisfying day of crafting, building, and gardening, relax in your very own paradise or invite Pokémon and your friends to visit.\r\nAs you restore your world and cultivate its natural beauty, you’ll encounter more Pokémon who will teach you useful moves!\r\n\r\nYour Pokémon neighbours are excited to play together, as well as help you build houses to welcome even more friends! Pokémon might even ask for your help – work with them to build a cosy utopia.\r\nGet creative!\r\n\r\n\r\nCraft items and furniture, then decorate your space however you want with convenient Joy-Con 2 mouse controls.\r\n\r\nFrom updating your hairstyle and outfit to visiting friends and taking photos of your creations together, there are so many ways to enjoy your world!\r\nUnique Pokémon pals\r\n\r\nLet’s take a look at some of the extraordinary Pokémon you might encounter as you work to make your world a better place', 58.99, 33, 'assets/items/Pokemon Pokopia.png', '2026-03-05'),
(5, 3, 'Battlefield 6', 'BATTLEFIELD 6: BEST FPS OF 2025\r\n\r\nReinforce yourself for the best-selling shooter of 2025 with the Battlefield™ 6 Phantom Edition. Includes a Battlefield Pro Token, the Phantom Pack, and more.\r\n\r\nFrom California and New York City, now to the mountainsides of Germany; the war with Pax Armata reaches a global scale. Extreme Measures introduces a new psychoactive smoke mechanic, a new map, weapons, and the return of the legendary AH-6 Little Bird.', 60.00, 20, 'assets/items/battlefield 6.png', '2025-10-10'),
(6, 2, 'Clair Obscur Expedition 33 - GOTY', 'GAME OF THE YEAR 2025\r\n\"With only one year left to live, join Gustave, Maelle, and their fellow Expeditioners as they embark upon a desperate quest to break the Paintress’ cycle of death. Follow the trail of previous expeditions and discover their fate. Get to know the members of Expedition 33 as they learn to work together against impossible odds.\"\r\nPlay one of the most engaging RPG\'s of 2025.', 50.00, 35, 'assets/items/Expedition 33.png', '2025-04-24'),
(7, 6, 'FIFA 26 FC Points 5900', '5900 FC Points for FIFA 26', 45.99, 40, 'assets/items/FIFA 26 FC Points 5900.png', '2025-09-25'),
(8, 4, 'Donkey Kong Bananza', 'Smash! Explore! Bananas!\r\n\r\nJoin the unlikely duo of Donkey Kong and Pauline as they smash their way through a vast underground world in Donkey Kong Bananza – only on Nintendo Switch 2.\r\nDonkey Kong!\r\nWith his incredible strength, DK can smash and bash his way through almost anything that stands between him and his beloved bananas.\r\nPauline\r\nA young girl with a powerful singing voice. She teams up with DK in the hopes of finding a way back to the surface.\r\nBreak it down!\r\nBash through just about anything with the raw power of Donkey Kong! Crash through walls, carve tunnels with your fists, punch straight down into the ground, and even tear off chunks of terrain to swing around and throw – the more you smash, the more secrets you\'ll open up to explore.\r\nPeel back the layers!\r\nDelve deeper underground to discover a subterranean world that\'s more than it seems on the surface. From lush lagoons, enormous canyons, and even frozen tundra – each layer is full of surprises. These layers are also home to inhabitants who need help dealing with Void Company\'s shenanigans.\r\n', 58.99, 0, 'assets/items/Donkey Kong Bananza.png', '2025-07-17'),
(9, 2, 'Ghost-of-Yotei', 'At the Northern edge of Japan, a new warrior rises\r\n\r\nSet 300 years after the critically acclaimed Ghost of Tsushima, Ghost of Yōtei is a standalone experience set in 1600s rural Japan. The story follows a haunted, lone mercenary named Atsu. Thirsty for revenge, she travels through the beautiful, rugged landscapes of northern Japan, hunting those who killed her family many years earlier.\r\n\r\nSixteen years after her family’s death, Atsu\'s quest across Ezo brings her to unexplored lands in search of a gang of six outlaws, but she finds much more than vengeance. \r\n\r\nThroughout her journey, Atsu will discover unlikely allies, and greater bonds than she could have imagined.', 60.00, 15, 'assets/items/ghost-of-yotei.png', '2025-10-02'),
(10, 5, 'The Sims Adventure Awaits Expansion Pack', 'The Sims Adventure Awaits Expansion Pack is a New experiences are out there for you to discover – or create! Explore the possibilities of Custom Venues and Getaways. Getaways can last a day or more and be anything from restful to challenging. Schedule activities that’ll help your Sims recharge, and maybe meet new friends. You can even apply rules to your Getaway. For example, Elimination Rules – like skill or relationship gains – will have Sims competing to outlast each other. This’ll be extra fun for Sims with the new Competitive Trait. Build and decorate your unique Custom Venue however you want, decide what kinds of activities are available there, and share it to the Gallery for others to enjoy.\r\nKids play at a large outdoor playground, climbing, sliding, doing crafts, archery, and games together in The Sims 4 Expansion Pack.\r\n\r\nFrom camp Getaways to imaginative play: the fun Sims have as kids will set the stage for the rest of their lives. Child Sims can develop new traits by going through Formative Moments, and forge evolving Childhood Sentiments towards Sims they grow up knowing. Customize Modular Playgrounds with slides and activities for a ton of Childhood fun. Kids can challenge each other with games like Hide and Seek or Rock, Paper, Scissors, and even have Imaginary Friends! Choose from four different Imaginary Friend dolls and four different personalities: Goofball, Competitive, Creative, and Evil. These personalities will affect the activities they suggest, so keep those smoke detectors handy!\r\nSims will have new skills to engage with: Papercraft, Entomology, Diving, and Archery. Make classic crafts like paper planes, catch and raise butterflies, make a splash with new diving boards, and hit the bullseye!', 34.99, 75, 'assets/items/The Sims Adventure Awaits Expansion Pack.png', '2025-10-02'),
(11, 4, 'Animal Crossing New Horizons - Nintendo Switch 2 Edition', '\r\nWelcome to the Deserted Island Getaway Package!\r\n\r\nA carefree new life is just beyond the horizon! Step off the plane and onto your very own deserted island where new friends, discoveries and opportunities are waiting for you. Paradise is what you make it in Animal Crossing: New Horizons for Nintendo Switch!\r\n\r\nYour island, clearer than ever\r\n\r\nWith the enhanced 4K resolution of the Nintendo Switch 2 Edition, you’ll be able to see your island and its residents even clearer than before!\r\n', 54.99, 10, 'assets/items/Animal Crossing New Horizons - Nintendo Switch 2 Edition.png', '2026-01-15'),
(12, 4, 'Metroid Prime 4 Beyond', '\r\nA world unknown. A mind unleashed.\r\n\r\nSamus Aran, the galaxy’s greatest bounty hunter, must explore the mysterious planet of Viewros, wielding new psychic abilities to find a way home in Metroid Prime 4: Beyond – Nintendo Switch 2 Edition.\r\nBattle alien threats and formidable bosses, navigate your way through labyrinthine environments, and discover new abilities and upgrades to power your pursuit of Viewros’ secrets.\r\nBehold, the planet Viewros\r\nAfter an unexpected accident during a clash with the dangerous bounty hunter Sylux, Samus finds herself transported to a strange new planet. She’ll need to thoroughly explore this hostile alien world if she’s to make it off the planet alive...\r\n', 58.99, 23, 'assets\\items\\Metroid Prime 4 Beyond.png', '2025-12-04'),
(13, 4, 'Super Mario Bros. Wonder – Nintendo Switch 2 Edition + Meetup in Bellabel Park', 'Join Mario and friends on a new 2D side-scrolling adventure full of wonder!\r\n\r\nEvery course contains its very own Wonder Flower – collect one, and anything can happen. From moving pipes to tilting terrain, singing Piranha Plants to stampeding Bulrushes, skydiving to space walks, you never know what’ll happen next. You might even transform into something totally new!\r\n\r\nPick to play as Mario, Luigi, Peach and more! They all play in exactly the same way, so you can freely use your favourite character without having to worry.\r\n\r\nThe world of Super Mario Bros. Wonder is even better when enjoyed with others! Play together locally or online and help save the Flower Kingdom from Bowser as a team, or enjoy enhancements to solo play by connecting online.\r\nLook out, it’s the Koopalings!\r\n\r\nThe Koopalings are here and ready to cause trouble! They’ve stolen the prized Bellabel Flowers, and spread out across the Flower Kingdom. Now it’s up to Mario and friends to chase them down and recover the treasures!\r\n\r\nAs you play the main adventure, be on the lookout for Toad Brigade tents dotted around the worlds. Each one leads to a Wonder-filled course where one of the Koopalings awaits, so prepare for exciting and surprising battles against them all!\r\n', 66.99, 69, 'assets/items/Super Mario Bros. Wonder - Nintendo Switch 2 Edition.png', '2026-03-26'),
(14, 2, 'Resident Evil Requiem', 'Requiem for the dead. Nightmare for the living.\r\n\r\nA new era of survival horror arrives with Resident Evil Requiem, the latest and most immersive entry yet in the iconic Resident Evil series. Experience terrifying survival horror with FBI analyst Grace Ashcroft, and dive into pulse-pounding action with legendary agent Leon S. Kennedy. Both of their journeys and unique gameplay styles intertwine into a heart-stopping, emotional experience that will chill you to your core.\r\nRaccoon City\r\n\r\nReturn once again to the city of disaster and despair.\r\nA midwestern city in the United States and the headquarters for the former global pharmaceutical company, Umbrella.\r\nIn the face of the zombie outbreak in 1998, the government approved a sterilization operation, a missile strike on the city in an attempt to quickly bring the situation under control—but this was swiftly covered up.\r\nGrace Ashcroft\r\n\r\nAn intelligence analyst for the FBI who demonstrates intense focus and insight in deductive reasoning and analysis. Her mother\'s death shook her to the soul, making her an introvert who immerses herself in work. So she heads to the abandoned hotel alone to investigate this mysterious death.\r\nLeon S. Kennedy\r\n\r\nOne of the survivors of the Raccoon City Incident. With a strong sense of justice and physical capabilities to match, he has responded to numerous outbreaks since that fateful day. Now, as a seasoned DSO agent combatting bioterrorism, he has returned to investigate the latest string of deaths in the Midwest.\r\nGameplay\r\n\r\nExperience the series\' classic survival horror through combat, investigations, puzzles, and resource management. Gameplay allows you to freely switch between first and third-person views to face the horrors in a way that suits your playstyle.', 59.99, 9, 'assets/items/Resident Evil 9 Requiem.png', '2026-02-27'),
(15, 1, 'DEATH STRANDING 2 ON THE BEACH', 'From legendary game creator Hideo Kojima comes an emotionally charged evolution of this genre-defying experience. In bringing this title to PC, KOJIMA PRODUCTIONS worked with Nixxes Software to deliver the definitive version of the critically acclaimed console release.\r\nUnique and realistic world\r\n\r\nIn the expansive open world of DEATH STRANDING 2: ON THE BEACH, countless areas with vastly different terrains and environments are seamlessly connected, and the changing time, weather and nature itself provide a variety of landscapes and challenges.\r\n\r\nEvolved and more flexible action\r\n\r\nThere are various obstacles that will prevent Sam from completing his mission. Face them head-on, sneak around, or completely avoid dangerous areas. Choose your approach to any situation.\r\n\r\nA deep and captivating story\r\n\r\nEleven months after the events of the original DEATH STRANDING, rejoin Sam and his companions on a new journey to save humanity from extinction and discover the answer to this haunting question: Should we have connected?\r\n\r\nGo beyond the UCA\r\n\r\nJoin Sam for the next chapter of his journey as he attempts to connect Australia to the Chiral Network. Navigate a living environment through day and night cycles, natural disasters – including earthquakes, sandstorms and forest fires – and Timefall, a rain that instantly ages everything it touches.\r\n\r\nTo traverse this dangerous landscape, you’ll need to build new types of infrastructure, master specialized equipment and confront (or evade) the otherworldly creatures that roam the fractured world.\r\n\r\nFight for the future\r\n\r\nFace enigmatic Mech soldiers, as-yet unseen BTs and new boss types that will push Sam to his limits. As you prepare for each journey, you’ll be able to fabricate weapons like machine guns, shotguns and grenade launchers to deal with threats. Or, choose to avoid confrontation altogether with stealth – using decoy holograms, silent takedowns and long-ranged gear.\r\n\r\nThrough APAS enhancements, Sam can learn new skills that will improve his odds of survival, like enhanced weapon fire-rates or reduced movement noise to help him stay hidden.\r\n\r\nHands across Australia\r\n\r\nThe iconic Social Strand System of DEATH STRANDING returns to encourage asynchronous connection across the globe. Your in-game actions affect how other players interact with the world and vice-versa – so connect, collaborate and help your fellow Porters on their journeys.\r\n\r\nLeave a “Like” on a useful bridge, donate resources to community projects, store field ware in shared lockers and finish incomplete deliveries as you stumble upon dropped cargo from other Porters. As you support other players on their journeys, your own reputation will grow, giving you access to valuable resources and new gear.', 70.00, 25, 'assets/items/Death Stranding 2.png', '2026-03-19'),
(16, 1, ' City Skylines 2 - Ultimate Edition.png', 'Cities: Skylines II: Ultimate Edition gives you access to the base game, the Expansion Pass: Waterfronts, and an additional 3 Creator Packs and 3 Radio Stations.\r\n\r\nIncluded in the Waterfronts Expansion Pass is the San Francisco Set, the Creator Packs Modern Architecture and Urban Promenades, the Bridges & Ports Expansion and the radio packs Deluxe Relax Station, Soft Rock Radio and Cold Wave Channel.\r\n\r\nIf you can dream it, you can build it.\r\nRaise a city from the ground up and transform it into the thriving metropolis only you can imagine. You\'ve never experienced building on this scale. With deep simulation and a living economy, Cities: Skylines II delivers world-building without limits.\r\n\r\nLay the foundations for your city to begin. Create the roads, infrastructure, and systems that make life possible day to day. It\'s up to you – all of it.\r\n\r\nHow your city grows is your call too, but plan strategically. Every decision has an impact. Can you energize local industries while also using trade to boost the economy? What will make residential districts flourish without killing the buzz downtown? How will you meet the needs and desires of citizens while balancing the city\'s budget?\r\n\r\nYour city never rests. Like any living, breathing world, it changes over time. Some changes will be slow and gradual, while others will be sudden and unexpected. So while seasons turn and night follows day, be ready to act when life doesn\'t go to plan.\r\n\r\nAn ever-expanding community of Builders means more opportunities to build a truly groundbreaking city with mods. They\'re now more easily available in Cities: Skylines II.\r\n\r\nThe most realistic and detailed city builder ever, Cities: Skylines II pushes your creativity and problem-solving to another level. With beautifully rendered high-resolution graphics, it also inspires you to build the city of your dreams.\r\nItems included in this package\r\nCities: Skylines II\r\nCities: Skylines II - Atmospheric Piano Channel\r\nCities: Skylines II - Bridges & Ports\r\nCities: Skylines II - Cold Wave Channel\r\n', 74.99, 25, 'assets/items/City Skylines 2 - Ultimate Edition.png', '2023-10-24'),
(17, 1, 'Ready or Not', '- The LSPD reports a massive upsurge in violent crime across the greater Los Sueños area. Special Weapons and Tactics (SWAT) teams have been dispatched to respond to various scenes involving high-risk hostage situations, active bomb threats, barricaded suspects, and other criminal activities. Citizens are being advised to practice caution when traveling the city or to stay at home.\r\n\r\nIt has been noted that while Los Sueños is still seen as a city where riches can be found, for many more the finer things in life are becoming less and less obtainable. “The city is sprawling with cramped high-rise apartments and decaying affordable housing, which has been exploited by the criminal underground like a malevolent parasite,” states Chief Galo Álvarez. “In a city where people are just trying to survive, lawful action from the LSPD and the LSPD SWAT team remains an integral force preventing the stretched thin social fabric in this city from snapping under this chaotic strain.”\r\n\r\nIn response to the burgeoning violent crime wave inundating Los Sueños, Chief Álvarez of the LSPD has enlisted the stalwart support of David ‘Judge’ Beaumont as the Commander of the LSPD SWAT team. Shortly following this announcement, the LSPD has also confirmed active recruitment for additional talent to join this specialized tactical police unit with the mission of bringing peace back to the city.\r\n\r\n“This assignment is not for the faint of heart,” comments Commander Beaumont, “Extremists, crooked politicians, countless weapons, human trafficking, and illicit drugs and pornography… the world of policing in Los Sueños is fraught with real and harsh realities, realities that the everyday person isn’t necessarily confronted with. These are realities that you will need to navigate with your team within the proper bounds of the law or face the consequences and make matters worse.”\r\n\r\nThe Los Sueños Policed Department has officially posted new details on Commander “Judge” Beaumont’s updated assignment with the LSPD SWAT team:\r\n\r\nNew Expectations:\r\n\r\nWhether this is your first time in Los Sueños or not, the city has changed and so too have the tools and methods with which we conduct our work. The haunts you might have seen or heard about are re-imagined, and so have the angles with which we approach each call. Furthermore, we’ve received reports that identify at least 4 new high-risk police districts which we suspect may require future tactical intervention, plus at least 4 existing high-risk districts that underwent massive upheavals.\r\n\r\nTake Command:\r\n\r\nThe role of the Commander of the LSPD SWAT is to compose their team from a large roster with unique talents, give tactical orders, meticulously plan, and carry out missions. Commanders are obligated to conduct each mission with integrity and look after their team’s mental and physical health. Officers with unaddressed deteriorating mental status may be unable to properly perform their duties or even feel compelled to quit the force. Incapacitated officers may be temporarily unavailable for missions, with the tragic prospect of death leading to a permanent loss. For SWAT Commanders in unusual circumstances based on individual choices they opt for, deadly mistakes may lead to their own permanent fatality.\r\n\r\nSWAT Team Quality Enhancement:\r\n\r\nMuch anticipated improved SWAT training procedures, tactics, and experience in the LSPD has led to an overall increase in the quality and quantity of officers in our roster. Expanded tactical versatility allows our officers to take on any challenge with renewed confidence, blending coordination and independence seamlessly. There is also additional basic training now available to SWAT members to keep foundational knowledge and muscle memory in top shape.\r\n\r\nEquipment and customization:\r\n\r\nWe have access to the best weapons and equipment that the LSPD can offer to fulfill our exceptionally dangerous role, including many new acquisitions. However, customization isn’t limited to the equipment we use. Through close-knit comradery while performing remarkable actions alongside the team, you’ll earn the clothes you wear, the patches on your sleeves, the artwork that may adorn your skin, and potentially even the timepiece on your wrist. Lastly, we have overhauled our headquarters\' training facilities to better test our loadouts before heading out on call.\r\nMature Content Description\r\n\r\nThe developers describe the content like this:\r\n\r\nGiven the graphic nature of the game and the gritty realism it portrays, the game is for mature audiences. We will also strongly recommend that people who have experienced personal traumatic events from criminal violence, hostage situations or terrorism refrain from playing. At its core, the game honors the work of dedicated law enforcement officers across the world and in no way intends on glorifying cowardly criminal acts.\r\n', 58.99, 30, 'assets/items/Ready or Not.png', '2023-12-13'),
(18, 1, 'Slay The Spire II', 'THE SPIRE AWAKENS\r\n\r\nThe ultimate roguelike deckbuilder returns!\r\n\r\nFor 1,000 years, the Spire lay dormant, its secrets buried and its horrors forgotten. Now, it has reopened, hungrier and more dangerous than ever, devouring all who dare to ascend.\r\n\r\nNew perils demand sharper strategies, relentless cunning, and unwavering resolve. Outwit the Spire’s brutal trials and uncover the truths hidden at its peak.\r\n\r\nWill you attempt the climb alone or enlist the aid of your fellow adventurers?\r\n\r\nAN EVER-CHANGING SPIRE\r\n\r\nStep into the newly evolved Spire, a twisted labyrinth teeming with strange and deadly foes. Adapt to an arsenal of brand new cards, relics, and potions, each offering game-changing possibilities—or dangerous consequences.\r\n\r\nClimb the Spire with a new and returning cast of characters, each with their own cards, motives, and secrets.\r\n\r\nNo two climbs are ever the same. Unpredictable challenges and evolving strategies await those brave—or foolish—enough to ascend.\r\n\r\nCLIMB ALONE OR WITH ALLIES\r\n\r\nBrave the ascent on your own, or play with up to 4 players and face the Spire together in the all new co-op mode. Discover multiplayer-specific cards, powerful team synergies, and carry your friends (or get carried) to victory!\r\n\r\nA NEW AND RETURNING CAST OF CHARACTERS\r\n\r\nEmbark on your quest to Slay the Spire as one of five different characters, some of whom will be familiar to seasoned Slayers and two of whom who are brand new. Discover each character’s unique playstyle, full potential, and secret backstory as you conquer all who stand in their way.\r\nUNCOVER THE SPIRE’S LORE\r\n\r\nThe history of the Spire and its inhabitants, be they friend or foe, can now be discovered in entirely new ways. Unlock fragments of its mysterious timeline and encounter the most ancient of its residents through your myriad attempts.', 20.00, 10, 'assets/items/Slay The Spire II.png', '2026-03-05');

-- --------------------------------------------------------

--
-- Table structure for table `support_requests`
--

CREATE TABLE `support_requests` (
  `id` int(11) NOT NULL,
  `idx_account` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `request_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `basket`
--
ALTER TABLE `basket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_basket_account` (`account_id`),
  ADD KEY `idx_basket_product` (`product_id`);

--
-- Indexes for table `filters`
--
ALTER TABLE `filters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product_filter` (`product_filter_id`);

--
-- Indexes for table `support_requests`
--
ALTER TABLE `support_requests`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `basket`
--
ALTER TABLE `basket`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `filters`
--
ALTER TABLE `filters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `support_requests`
--
ALTER TABLE `support_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `basket`
--
ALTER TABLE `basket`
  ADD CONSTRAINT `fk_basket_account` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_basket_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_item_type` FOREIGN KEY (`product_filter_id`) REFERENCES `filters` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
