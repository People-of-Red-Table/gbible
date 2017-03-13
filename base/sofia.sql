create table users
(
	id int auto_increment primary key,
	nickname varchar(30),
	full_name varchar(50),
	email varchar(100),
	password varchar(32),
	secret_question varchar(200),
	secret_answer varchar(50),
	last_hit datetime,
	timezone varchar(50),
	inserted datetime,
	updated datetime,
	updated_by int,
	deleted datetime,
	deleted_by int,
	fav_bible varchar(20),
	verification_code varchar(36),
	remote_addr varchar(50),
	topics_per_page int,
	posts_per_page int,
	messages_per_page int,

	-- may be someday they will become normal =]
	country varchar(50),
	language varchar(50)
);

create table fav_verses
(
	id int auto_increment primary key,
	user_id int,
	verseID varchar(16),
	b_code varchar(20),
	inserted datetime,
	sort int
);

create table tweeted_verses
(
	id int auto_increment primary key,
	verseID varchar(16),
	times_tweeted int
);

create table shared_verses
(
	id int auto_increment primary key,
	sn_id int,
	verseID varchar(16),
);


create table social_networks
(
	id int auto_increment primary key,
	network_name varchar(50),
	base_url varchar(50)
);

insert into social_networks (network_name, base_url) values ('Facebook', 'https://www.facebook.com/'), ('Google+', 'https://plus.google.com/'), ('Vkontakte', 'https://vk.com/'), ('Twitter', 'https://www.twitter.com/');

create table charity_organization_types
(
    id int primary key auto_increment,
    name varchar(20)
);

create table charity_organizations
(
    id int primary key auto_increment,
    charity_organization_type_id int,
    country_code varchar(5),
    english_name varchar(200),
    native_name varchar(200),
    description varchar(500),
    http_link varchar(200)
);

alter table charity_organizations add constraint fk_charity_types_of_organizations foreign key (charity_organization_type_id) references charity_organization_types(id);
INSERT INTO `charity_organization_types` (`id`, `name`) VALUES (NULL, 'Red Cross'), (NULL, 'Red Crescent');
INSERT INTO `charity_organization_types` (`id`, `name`) VALUES (NULL, 'Charity');


INSERT INTO `charity_organizations` (`id`, `country_code`, `english_name`, `native_name`, `description`, `http_link`, `charity_organization_type_id`) VALUES
(1, 'us', 'American Red Cross', 'American Red Cross', NULL, 'http://redcross.org', 1),
(2, 'us', 'Largest Charity Companies of United States [Forbes'' Version]', 'Largest Charity Companies of United States [Forbes'' Version]', NULL, 'http://www.forbes.com/top-charities/', 3),
(3, 'us', 'Feeding America', 'Feeding America', NULL, 'http://www.feedingamerica.org/', 3),
(4, 'us', 'Global Impact', 'Global Impact', NULL, 'http://www.charity.org/', 3),
(5, 'us', 'St. Jude Children''s Research Hospital', 'St. Jude Children''s Research Hospital', NULL, 'https://www.stjude.org/', 3),
(6, 'us', 'Common Ground', 'Common Ground', NULL, 'http://www.commonground.org/', 3),
(7, 'us', 'Children Inc', 'Children Inc', NULL, 'http://www.children-inc.org/', 3),
(8, 'us', 'The Trevor Project', 'The Trevor Project', NULL, 'http://www.thetrevorproject.org/', 3),
(9, 'us', 'Dignity Health', 'Dignity Health', NULL, 'https://www.dignityhealth.org/', 3),
(10, 'us', 'American Anglican Church', 'American Anglican Church', NULL, 'https://americananglican.org/donate/', 3),
(11, 'us', 'United Church of Christ', 'United Church of Christ', NULL, 'http://www.ucc.org/_donate', 3),
(12, 'gb', 'British Red Cross', 'British Red Cross', NULL, 'http://www.redcross.org.uk/Donate-Now', 1),
(13, 'gb', 'Comic Relief', 'Comic Relief', NULL, 'http://www.comicrelief.com/', 3),
(14, 'gb', 'Charity Choice', 'Charity Choice', NULL, 'http://www.charitychoice.co.uk/', 3),
(15, 'ru', 'Russian Red Cross', 'Российский Красный Крест', NULL, 'http://redcross.ru/?pid=5', 1),
(16, 'br', 'Charity Charities of Brazil', 'Caridades do Brasil', NULL, 'http://charity-charities.org/Brazil-charities/Brazil.html', 3),
(17, 'br', 'Center For Independent Living Of Rio De Janeiro', 'O Centro de Vida Independente do Rio de Janeiro', NULL, 'http://www.cvi-rio.org.br', 3),
(18, 'br', '', 'Campo - Centro Assessoria Ao Movimento Popular', NULL, 'http://www.campo.org.br', 3),
(19, 'br', 'Catholic Women For The Right To Decide - Brazil', 'Católicas pelo Direito de Decidir', NULL, 'http://www.catolicasonline.org.br', 3),
(20, 'br', 'Koinonia - Ecumenical Presence And Service', 'KOINONIA Presença Ecumênica e Serviço', NULL, 'http://koinonia.org.br', 3),
(21, 'br', 'Integration And Dignity Of The Patient Aids', 'Grupo pela vidda', NULL, 'http://www.aids.org.br', 3),
(22, 'br', 'House Of The Path', 'Casa Do Caminho', NULL, 'http://www.casadocaminhobrasil.org', 3),
(23, 'br', 'Brazilian Interdisciplinary Aids Association', 'ABIA – Associação Brasileira Interdisciplinar de AIDS', NULL, 'http://abiaids.org.br/', 3),
(24, 'br', 'Education Action', 'Ação Educativa', NULL, 'http://acaoeducativa.org.br/', 3),
(25, 'br', 'ADELCO - Association for Local Development Co-Produced Charity In Fortaleza Brazil', 'Associação para Desenvolvimento Local Co-Produzido', NULL, 'http://adelco.org.br/', 3),
(26, 'br', 'Friends of Iracambi', 'Amigos De Iracambi [Rosario Da Limeira, Brazil]', NULL, 'http://www.iracambi.com/', 3),
(27, 've', 'Peace Villages', 'Fundación Aldeas De Paz Venezuela', NULL, 'http://www.peacevillages.org', 3),
(28, 've', 'Socieven', 'Socieven', NULL, 'http://www.socieven.org/', 3),
(29, 've', 'International College Of Carabobo', 'Colegio Internacional De Carabobo', NULL, 'http://www.cic-valencia.org.ve/', 3),
(30, 've', 'Foundation Jose Maria Bengoa For The Feeding And Nutrition', 'Fundación Bengoa para la Alimentación y Nutrición', NULL, 'http://www.fundacionbengoa.org/', 3),
(31, 've', 'Foundation Light And Life', 'Centro Familia Cristiano  "Luz y Vida"', NULL, 'http://www.luzyvida.org/', 3),
(32, 've', 'Venezuelan Red Cross', 'Cruz Roja Venezolana', NULL, 'http://www.cruzrojavenezolana.org/', 1),
(33, 've', 'Friends Of The Foundation Or Warrants Child Protection Fundana', 'Amigos De La Fundación O Garantías Protección De Los Niños Fundana', NULL, 'http://www.fundana.org/', 3),
(34, 've', 'UNFPA, United Nations Population Fund United Nations', 'UNFPA, Fondo de Población de las Naciones Unidas Naciones Unidas', NULL, 'http://venezuela.unfpa.org/', 3),
(35, 've', 'Movement Of Integral Popular Education And Social Promotion', 'Movimiento De Educacion Popular Integral Y Promocion Social', NULL, 'http://www.feyalegria.org/', 3),
(36, 've', 'ACSOL. Solidarity Against AIDS', 'ACSOL. Solidaria Contra El Sida', NULL, 'http://www.acsol.org/', 3),
(37, 'ca', 'Lymphoma Coalition', 'Lymphoma Coalition', NULL, 'http://www.lymphomacoalition.org/', 3),
(38, 've', 'United Nations Development Program UNDP', 'Programa de las Naciones Unidas para el Desarrollo PNUD', NULL, 'http://www.pnud.org.ve/', 3),
(39, 'gb', 'United Kingdom Charities & Causes Events', 'United Kingdom Charities & Causes Events', NULL, 'https://www.eventbrite.co.uk/d/united-kingdom/charity-and-causes--events/', 3),
(40, 'gb', 'Action Medical Research', 'Action Medical Research', 'We are Action Medical Research, for children, for life – the leading UK-wide medical research charity dedicated to helping babies and children. ', 'http://www.action.org.uk/', 3),
(41, 'gb', 'AIDSARK', 'AIDSARK', 'To fund the supply of life saving Triple Combination Anti Retro Virals (ARVs) \r\nfor those who are unable to access this proven life saving medication. ', 'http://aidsark.org/', 3),
(42, 'gb', 'Alder Hey Children’s Charity', 'Alder Hey Children’s Charity', 'Supporting Alder Hey Children’s Hospital in Liverpool, funds raised help us to continue our pioneering work, to develop facilities and to offer the very highest standards of care to our young patients.', 'https://www.alderheycharity.org/', 3),
(43, 'gb', 'Alleyn''s School', 'Alleyn''s School', 'Co-educational excellence for all, in a caring, friendly and welcoming community. ', NULL, 3),
(44, 'gb', 'Become A Friend', NULL, 'The Become a Friend project links children with other children, continents apart.\r\nBAF is an offspring project, taking its inspiration from a larger healthcare programme based in Northern Kenya – the Nabakisho Healthcare Programme.', 'http://becomeafriend.net/', 3),
(45, 'gb', 'Bedales Schools Development Trust', NULL, 'The vision of Bedales'' founder, John Badley, was to create a school which \r\nwould be profoundly different from the public schools of his day. ', 'http://www.bedales.org.uk/', 3),
(46, 'gb', 'Ben Uri Gallery', NULL, 'The London Jewish Museum of Art - The Art Museum for Everyone, Bridging Communities Since 1915.', 'http://www.benuri.org.uk/', 3),
(48, 'gb', 'Moorfields Eye Charity', NULL, 'Moorfields Eye Charity raises funds, above and beyond those normally provided \r\nby the NHS, to enable Moorfields Eye Hospital NHS Foundation Trust to continue \r\nto provide the highest quality care for its patients and their families and to help \r\nensure it remains a world-class centre of excellence for ophthalmic research \r\nand education.', 'http://www.moorfields.nhs.uk/Getinvolved/MoorfieldsEyeCharity', 3),
(49, 'ru', 'QIWI Donation Page', 'Пожертвовать на благотворительность в России по QIWI', NULL, 'https://qiwi.com/payment/list.action?category=1237', 3),
(50, 'ru', 'United Way', 'БФ «Дорога вместе»', 'Благотворительный фонд «Дорога вместе» работает с 1993 года и поддерживает благотворительные программы, направленные на помощь:\r\nдетям группы риска\r\nинвалидам (дети и взрослые)\r\nпожилым людям', 'http://www.unitedway.ru/', 3),
(51, 'ru', 'Charitable Foundation "Good Deeds"', 'Благотворительный фонд «Добрые дела»', 'Благотворительный фонд «Добрые дела» - это некоммерческая, добровольная, волонтерская организация по оказанию помощи детям.\r\n\r\nЦелями фонда являются:  формирование имущества на основе добровольных взносов и иных не запрещенных законом поступлений и использование этого имущества и средств для осуществления благотворительной деятельности', 'http://www.bf-dd.ru/', 3),
(52, 'ru', 'Charity Foundation "Children of Earth"', 'Благотворительный фонд «Дети Земли»', NULL, 'http://www.childrenofearth.org/', 3),
(53, 'ru', 'Charity Foundation "New Life"', 'Благотворительный фонд "Новая жизнь"', 'Благотворительный фонд "Новая жизнь" создан для оказания помощи спортсменам с ограниченными возможностями здоровья. Выбор именно этой категории лиц для поддержки фондом не случаен. Руководитель фонда, сам в свое время, находясь на службе в рядах вооруженных сил СССР, потерял руку, оказался на грани, но не сломался, и поверить в себя и начать новую жизнь ему помог спорт.', 'http://www.fondnewlife.ru/', 3),
(54, 'ru', 'Charity Foundation "Nastenka"', 'Благотворительный Фонд «Настенька»', 'Благотворительный Фонд «Настенька» создан в феврале 2002 года по частной инициативе для оказания помощи пациентам НИИ детской онкологии и гематологии им. Н.Н.Блохина http://ronc.ru/.', 'http://www.nastenka.ru/howto/', 3),
(55, 'ru', 'Charity Foundation "Artemka"', 'Благотворительный фонд "Артёмка"', 'Основная задача фонда – сбор средств для оплаты лечения детей и взрослых с тяжелыми заболеваниями. \r\nФонд оказывает помощь в оплате операций, обследований, покупки жизненноважных лекарственных средств.', 'http://artemkafond.ru/', 3),
(56, 'ru', 'World of Help', 'Благотворительный Фонд\r\n"МИР ПОМОЩИ"', 'Целями фонда являются:\r\nоказание материальной и иной помощи несовершеннолетним детям, детям, оставшимся без попечения родителей, детям сиротам, инвалидам;\r\nоказание материальной и иной помощи домам малюток, детским домам, домам-интернатам, школам-интернатам, специальным учебно-воспитательным центрам, независимо от их национальности и вероисповедания.', 'http://world-of-help.com', 3),
(57, 'ru', 'The Orthodox TV channel Soyuz', 'Православный телеканал «Союз»', 'Телеканал «Союз» является православным по духу, но не чисто религиозным по содержанию СМИ. Это позитивное, семейное, домашнее телевидение, основанное на традиционных нравственных ценностях и традициях отечественной истории и культуры.', 'http://tv-soyuz.ru', 3),
(58, 'ru', 'Hope World Wide', 'Благотворительный фонд «Надежда по всему миру»', 'Благотворительный фонд «Надежда по всему миру» — это негосударственный Благотворительный фонд, зарегистрированный в марте 1996 г. (Благотворительный паспорт №348, выдан Московским Благотворительным Советом 12.04.2002г.). Основным направлением работы Благотворительного фонда «Надежда по всему миру» является социальная адаптация детей-воспитанников детских домов и школ-интернатов, детей-инвалидов, а также одиноких пенсионеров и ветеранов войны.', 'http://hopeww.ru', 3),
(59, 'ru', 'Child Hope', 'МБОО «Возрождение»', 'На сегодняшний день ревматические болезни — одна из основных причин развития детской инвалидности. А потому мы делаем всё возможное, чтобы общественность и власти страны обратились лицом к проблемам, стоящим перед больными детьми и их родителями.\r\n\r\nОбщественную организацию «Возрождение» беспокоит не только медицинский аспект проблем, связанных с заболеванием, но и их психологическая и социальная составляющие.', 'http://www.childhope.ru', 3),
(60, 'ru', NULL, 'МОО ВЕЧЕ', 'Межрегиональная общественная организация содействия сохранению отечественных традиций и культурного наследия «ВЕЧЕ» (МОО «ВЕЧЕ»), учреждена 26 апреля 2009 года, с целью разработки и реализации концепции развития Российской Федерации как сильного, независимого и справедливого государства, способного обеспечить свободу, достойную жизнь и равные права для каждого гражданина России.', 'http://veche-info.ru/', 3),
(61, 'us', 'American Fund for Charities', NULL, 'The American Fund seeks to be a world leader in supporting charities and non-profit organizations dedicated to bettering the lives of people, communities and the environment.', 'https://www.americanfund.info/', 3),
(62, 'ca', 'Canadian Feed The Children', NULL, 'Canadian Feed The Children (CFTC) envisions a world where children thrive free \r\nof poverty. We work with community-based organizations in Canada and internationally to achieve sustainable improvements to the health and self-sufficiency of children, families and communities through food security, education and capacity-building programs.', 'http://www.canadianfeedthechildren.ca/', 3),
(63, 'ca', 'Canadian Red Cross', NULL, NULL, 'http://www.redcross.ca', 1),
(64, 'ca', 'Hamilton Health Sciences Foundation', NULL, 'Through the generosity of our donors, we fund capital redevelopment projects, purchase medical equipment and technology, and we invest in research. Our mission is to support patient care, research and education across the Hamilton Health Sciences family of hospitals; our goal is Health Care, Transformed.', 'http://www.hamiltonhealth.ca/', 3),
(65, 'ca', 'Headwaters Health Care Foundation', NULL, 'Our Vision: Grow, inspire, motivate and nurture relationships with donors now \r\nand in the future. Our Mission: To advance and enhance Headwaters Health \r\nCare Centre''s capacity to provide patient-centred, compassionate care to \r\nour community.', 'http://www.headwatershealth.ca/', 3),
(66, 'ca', 'Hemoglobal', NULL, 'Hemoglobal® focuses on improving the health of children affected by blood diseases in Asia. We strive to provide the opportunity for a better outcome for children, regardless of birthplace. ', 'http://www.hemoglobal.org/', 3),
(67, 'ca', 'Huntsville District Memorial Hospital Foundation ', NULL, 'Huntsville District Memorial Hospital Foundation is a fundraising organization dedicated to improving health care services for the residents of Muskoka and East Parry Sound. ', 'http://www.hdmhfoundation.ca/', 3),
(68, 'ca', 'Lake Joseph Community Church', NULL, 'Lake Joseph Community Church is an Interdenominational church located on an attractive Lake Joseph shoreline property in Muskoka Ontario.', 'https://lakejosephchurch.ca/', 3),
(69, 'ca', 'Never Forgotten National Memorial Foundation', NULL, 'The Never Forgotten National Memorial will be a place for remembrance and gratitude. It will bring forth an exciting new era of commemoration, one allowing Canadians to honour and respect Our Fallen in a manner never previously experienced or possibly even imagined.', 'http://www.nfnm.ca/', 3),
(70, 'ca', 'Pediatric Oncology Group of Ontario [POGO]', NULL, 'Pediatric Oncology specialists in Ontario have collaborated closely, since 1983, \r\nto deliver the right care at the right time and in the right place for children with cancer and their families. This work has been carried out by Pediatric Oncology Group of Ontario [POGO].', 'http://www.pogo.ca/', 3),
(71, 'ca', 'West Parry Sound Health Centre Foundation', NULL, 'Working together with health care professionals, patients, our community \r\nand government, The West Parry Sound Health Centre Foundation’s \r\ngoal is to continually raise funds to support the on-going medical needs \r\nof the people who choose to live in the cottage country. ', 'http://www.wpshcf.com/', 3),
(72, 'au', 'Australian Red Cross', NULL, NULL, 'http://redcross.au/', 1),
(73, 'au', 'Australian Federation of AIS Organisation [AFAO]', NULL, 'AFAO is the national federation for the HIV community response. We provide leadership, coordination and support to the national policy, advocacy and health promotion response to HIV/AIDS.', 'http://www.afao.org.au/', 3),
(74, 'au', 'Florey Neuroscience Institutes', NULL, 'The Florey Neuroscience Institute is committed to improving our quality of life through brain research.', 'http://www.florey.edu.au/', 3),
(75, 'au', 'Royal Children''s Hospital Foundation', NULL, 'The Royal Children''s Hospital Foundation is the fundraising arm of Queensland''s Royal Children''s Hospital.  The Foundation raises around $13 million annually, \r\nand since inception in 1986 more than $100 million has been invested into \r\nworking wonders for sick kids.', 'http://www.workingwonders.com.au/', 3),
(76, 'au', 'Walter and Eliza Hall Institute', NULL, 'Since 1915, researchers at the Walter and Eliza Hall Institute of Medical Research have made significant discoveries into immunity, cancer and infectious diseases. These discoveries have improved the health of millions of people worldwide. With your help, we hope to improve the lives of tens of millions more. Our researchers tackle some of the most urgent and widespread health challenges facing humanity.', 'http://www.wehi.edu.au/', 3),
(77, 'au', 'Sydney Children''s Hospital Foundation', NULL, 'Sydney Children''s Hospital Foundation is the principal fundraising body for \r\nSydney Children''s Hospital, Randwick. We are dedicated to working with the community to improve the quality of life for this and future generations of\r\nseriously ill children from across NSW and beyond. ', 'http://www.schf.org.au/', 3),
(78, 'au', 'Macfarlane Burnet Institute for Medical Research and Public Health', NULL, 'We aim to achieve better health for poor and vulnerable communities in Australia and internationally through research, education and public health. ', 'http://www.burnet.edu.au/', 3),
(79, 'au', 'Australian Rainforest Conservation Society', NULL, 'The Australian Rainforest Conservation Society is a national, non-government organisation. Its goal, through research, lobbying, public education and \r\ngrass-roots support, is to protect, repair and restore the rainforests of Australia \r\nand to maximise the protection of forest biodiversity.', 'http://www.rainforest.org.au', 3),
(80, 'au', 'Conservation Volunteers Australia', NULL, 'The Conservation Foundation was established in 1982 by David Shreeve and \r\nDavid Bellamy to provide a means for people in the public, private and \r\nnot-for-profit sectors to work together on environmental causes. ', 'http://www.conservationvolunteers.com.au/', 3),
(81, 'au', 'State Library of NSW Foundation', NULL, 'The State Library of New South Wales is internationally renowned and one of Australia’s leading libraries. With an extensive collection of over five million items, the State Library aims to collect, preserve and make accessible the documentary heritage of NSW.', 'http://www.sl.nsw.gov.au/', 3),
(82, 'kh', 'Hope For Cambodian Children', NULL, 'Hope for Cambodian Children (HFCC) is a charitable foundation that cares for abandoned children and assists those from the poorest areas of the community.', 'https://hopeforcambodianchildren.org', 3),
(109, 'us', 'American Red Cross', NULL, NULL, 'http://redcross.org/', 1),
(110, 'gb', 'British Red Cross', NULL, NULL, 'http://redcross.org.uk/', 1),
(111, 'ru', 'Russian Charity List', '', NULL, 'http://www.evansnyc.com/charity/', 3),
(112, 'ua', 'Ukrainian Charity', '', NULL, 'https://goo.gl/niAr27', 3),
(113, 'cn', 'Chinese Red Cross', '', NULL, 'http://www.redcross.org.cn/', 1),
(114, 'es', 'Spanish Red Cross', 'Cruz Roja Española', NULL, 'http://www.cruzroja.es/', 1),
(115, 'jp', 'Japanese Red Cross', '', NULL, 'http://www.jrc.or.jp/', 1),
(116, 'eg', 'Egyptian Red Crescent', '', NULL, 'http://egyptianrc.org/', 2),
(117, 'br', 'Brazilian Red Cross', '', NULL, 'https://goo.gl/cX7xcr', 3),
(118, 'ph', 'Philippine Red Cross', 'Philippine Red Cross', NULL, 'http://redcross.org.ph/', 1),
(119, 'ng', 'Nigerian Red Cross Society', NULL, NULL, 'http://redcrossnigeria.org/', 1),
(120, 'mx', 'Mexican Red Cross', 'Cruz Roja Mexicana', NULL, 'http://cruzrojamexicana.org.mx/', 1),
(121, 'et', 'Ethiopian Red Cross', '', NULL, 'http://redcrosseth.org/', 1),
(122, 'de', 'German Red Cross', 'Deursches Rotes Kreuz', NULL, 'http://www.drk.de/', 1),
(123, 'co', 'Colombian Red Cross', '', NULL, 'http://www.cruzrojacolombiana.org/', 1),
(124, 'fr', 'French Red Cross', '', NULL, 'http://www.croix-rouge.fr/', 1),
(125, 'ke', 'Kenya Red Cross', NULL, NULL, 'http://www.kenyaredcross.org/', 1),
(126, 'se', 'Sweden Red Cross', 'Röda Korset', NULL, 'http://www.redcross.se/', 1),
(127, 'pe', 'Peruvian Red Cross', '', NULL, 'http://www.cruzroja.org.pe/', 1),
(128, 'id', NULL, '', NULL, 'http://www.pmi.or.id/', 1),
(129, 'ca', 'Canadian Red Cross', NULL, NULL, 'http://www.redcross.ca/donate', 1),
(130, 'gh', 'Red Cross of Ghana', NULL, NULL, 'http://www.redcrossghana.org/', 1),
(131, 'tz', 'Home', NULL, NULL, 'http://www.trcs.or.tz/', 1),
(132, 'ec', 'Red Cross of Ecuador', NULL, NULL, 'http://www.cruzroja.org.ec/', 1),
(133, 'gt', 'Red Cross of Guatemala', 'Cruz Roja Guatemalteca', NULL, 'http://www.cruzroja.gt/', 1),
(134, 'au', 'Australian Red Cross', NULL, NULL, 'http://www.redcross.org.au/donate.aspx', 1),
(135, 'az', NULL, '', NULL, 'http://www.redcrescent.az/', 2),
(136, 'bs', 'Red Cross of Bahamas', NULL, NULL, 'www.bahamasredcross.com ', 1),
(137, 'bz', 'Charity for Belize', NULL, NULL, 'https://goo.gl/ILXLDt ', 3),
(138, 'cm', 'Nonprofit [Limbe City]: Cameroon Christian Welfare Medical Foundation', 'Nonprofit [Limbe City]: Cameroon Christian Welfare Medical Foundation', NULL, 'http://www.idealist.org/view/org/8WbKBhBbx874/', 3),
(139, 'mz', 'Charity for Mozambique', '', NULL, 'https://goo.gl/SqMbSU ', 3),
(140, 'gr', 'Red Cross of Greece', NULL, NULL, 'http://www.redcross.gr/', 1),
(141, 'cl', 'Red Cross of Chile', 'Cruz Roja Chilena', NULL, 'http://www.cruzroja.cl/', 1),
(142, 'pg', '', NULL, NULL, 'https://goo.gl/Gx0a0n ', 3),
(143, 'py', 'Red Cross of Paraguay', 'Cruz Roja Paraguaya', NULL, 'http://www.cruzroja.org.py/', 1),
(144, 'sd', 'South Sudan Red Cross', NULL, NULL, 'http://southsudanredcross.org/', 1),
(145, 'ar', 'Red Cross of Argentina', 'Cruz Roja Argentina', NULL, 'http://www.cruzroja.org.ar/', 1),
(146, 'ro', 'Romanian Red Cross', '', NULL, 'http://www.crucearosie.ro/', 1),
(147, 'kr', 'Korean Red Cross', '', NULL, 'https://www.redcross.or.kr/eng/eng_main/main.do', 1),
(148, 'bg', 'Bulgarian Red Cross', NULL, NULL, 'http://redcross.bg/', 1),
(149, 'ht', 'Red Cross of Haiti', '', NULL, 'http://www.croixrouge.ht/', 1),
(150, 'nl', 'Red Cross of Netherlands', 'Rode Kruis', NULL, 'http://www.rodekruis.nl/', 1),
(151, 'vn', 'Vietnamese Red Cross', NULL, NULL, 'http://redcross.org.vn/', 1),
(152, 'hu', 'Hungarian Red Cross', '', NULL, 'http://www.voroskereszt.hu/', 1),
(153, 'cd', 'Congo Helping Hands', '', NULL, 'http://congohelpinghands.org/', 3),
(154, 'za', 'Youth for Christ', NULL, NULL, 'http://yfc.org.za/', 3),
(155, 've', 'Venezuelan Red Cross', '', NULL, 'http://www.cruzrojavenezolana.org/', 1),
(156, 'zm', 'Charity for Zambia', NULL, NULL, 'https://goo.gl/L6mt1K ', 3),
(157, 'mw', '', NULL, NULL, 'https://goo.gl/ouh5qK ', 3),
(158, 'zw', 'Zimbabwe - Save the Children', NULL, NULL, 'https://goo.gl/bmDftq ', 3),
(159, 'do', 'Dominican Republic - Save the Children', NULL, NULL, 'https://goo.gl/vzjjpR ', 3),
(160, 'bo', 'Charity for Bolivia', '', NULL, 'http://www.ayuda.org/', 3),
(161, 'rw', 'Red Cross of Rwanda', NULL, NULL, 'http://www.rwandaredcross.org/', 1),
(162, 'mg', 'Help Madagascar', '', NULL, 'http://helpmg.org/', 3),
(163, 'bi', 'Red Cross of Burundi', 'Croix Rouge Burundi', NULL, 'http://www.croixrougeburundi.org/', 1),
(164, 'md', 'Red Cross Moldova', NULL, NULL, 'http://redcross.md/', 1),
(165, 'in', 'Indian Red Cross', NULL, NULL, 'http://indianredcross.org/', 1),
(166, 'ci', 'Charity for Ivory Coast', '', NULL, 'http://www.icmrt.org', 3),
(167, 'be', 'Belgian Red Cross', '', NULL, 'http://www.redcross.be/', 1),
(168, 'cu', 'Charity for Cuba', '', NULL, 'http://www.sld.cu/sitios/cruzroja', 1),
(169, 'hn', 'Red Cross of Honduras', 'Cruz Roja Hondureña', NULL, 'http://www.cruzroja.org.hn/', 1),
(170, 'at', 'Austrian Red Cross', '', NULL, 'http://www.charity-charities.org/Austria-charities/Vienna.html', 3),
(171, 'ch', 'Swiss Red Cross', '', NULL, 'http://www.redcross.ch/', 1),
(172, 'by', 'Belorussian Red Cross', '', NULL, 'http://redcross.by/', 1),
(173, 'ni', 'Red Cross of Nicaragua', 'Cruz Roja Nicaragüense', NULL, 'http://cruzrojanicaraguense.org/', 1),
(174, 'sv', 'Red Cross of El Salvador', '', NULL, 'http://www.cruzrojasal.org.sv/', 1),
(175, 'dk', 'Danish Red Cross', 'Røde Kors', NULL, 'http://www.rodekors.dk/', 1),
(176, 'it', 'Italian Red Cross', 'Croce Rossa Italiana', NULL, 'http://cri.it/', 1),
(177, 'ao', 'Red Cross of Angola', 'Cruz Vermelha', NULL, 'http://www.cruzvermelha.og.ao/', 1),
(178, 'pt', 'Red Cross of Portugal', 'Cruz Vermelha Portuguesa - Cruz Vermelha Portuguesa', NULL, 'http://www.cruzvermelha.pt/', 1),
(179, 'td', 'Charity for Chad', '', NULL, 'http://www.chadnow.com/chad_links/chad_aid_links.php', 3),
(180, 'kz', 'Red Crescent of Kazakhstan', 'Красный Полумесяц Казахстана', NULL, 'http://www.redcrescent.kz/', 2),
(181, 'fi', 'Finnish Red Cross', NULL, NULL, 'http://www.redcross.fi/', 1),
(182, 'hr', 'Croatian Red Cross', 'Hrvatski Crveni križ', NULL, 'http://www.hck.hr/', 1),
(183, 'ie', 'Irish Red Cross', NULL, NULL, 'http://www.redcross.ie/', 1),
(184, 'bj', 'Red Cross of Benin', '', NULL, 'http://croixrougebenin.afredis.com/', 1),
(185, 'ge', 'Red Cross of Georgia [Europe]', 'საქართველოს წითელი ჯვარი', NULL, 'http://redcross.ge/', 1),
(186, 'cr', 'Red Cross of Costa Rica', '', NULL, 'http://www.cruzroja.or.cr/', 1),
(187, 'pr', 'Charity for Puerto Rico', '', NULL, 'http://www.charity-charities.org/PuertoRico-charities/PuertoRico.html', 3),
(188, 'no', 'Norwegian Red Cross', 'Røde Kors', NULL, 'http://www.rodekors.no/', 1),
(189, 'ug', 'Uganda Redcross Society', NULL, NULL, 'http://www.redcrossug.org/', 1),
(190, 'il', 'Israel Gives', '', NULL, 'http://www.israelgives.org/', 3),
(191, 'nz', 'New Zealand Red Cross', NULL, NULL, 'http://www.redcross.org.nz/', 1),
(192, 'bf', 'Red Cross of Burkina Faso', '', NULL, 'http://www.croixrougebf.org/', 1),
(193, 'pa', 'Red Cross of Panama', 'Cruz Roja', NULL, 'http://www.cruzroja.org.pa/', 1),
(194, 'am', 'Armenian Red Cross', NULL, NULL, 'http://redcross.am/', 1),
(195, 'my', 'Malaysian Care', NULL, NULL, 'http://www.malaysiancare.org/', 3),
(196, 'ae', 'Red Crescent of United Arab Emirates', '', NULL, 'http://www.rcuae.ae/', 2),
(197, 'uz', 'Red Crescent of Uzbekistan', '', NULL, 'http://redcrescent.uz/', 2),
(198, 'gy', 'Guyanese Red Cross', NULL, NULL, 'http://guyanaredcross.org.gy/', 1),
(199, 'uy', 'Red Cross of Uruguay', 'Cruz Roja', NULL, 'http://www.cruzrojauruguaya.org/', 1),
(200, 'tt', 'Charitable Organizations in Trinidad and Tobago', NULL, NULL, 'https://goo.gl/l57Ue1 ', 3),
(201, 'ph', 'Philippine Red Cross', NULL, NULL, 'http://redcross.org.ph/', 1),
(202, 'ht', 'Haitian Red Cross', '', NULL, 'http://www.croixrouge.ht/', 1),
(203, 'lv', 'Latvian Red Cross', 'LATVIJAS SARKANAIS KRUSTS', NULL, 'http://www.redcross.lv/en/', 1),
(204, 'mm', 'Myanmar Red Cross', NULL, NULL, 'http://www.redcross.org.mm/', 1),
(205, 'pk', 'Red Crescent of Pakistan', '', NULL, 'http://www.prcs.org.pk/', 2),
(206, 'cf', 'Central African Republic - Save the Children', NULL, NULL, 'https://goo.gl/HucaYG', 3),
(207, 'na', 'Namibian Red Cross', NULL, NULL, 'http://www.redcross.org.na', 1),
(208, 'tg', 'Red Cross of Togo', '', NULL, 'http://www.croixrouge-togo.org/', 1),
(209, 'ls', 'Red Cross of Lesotho', NULL, NULL, 'http://www.redcross.org.ls/', 1),
(210, 'jm', 'Red Cross of Jamaica', NULL, NULL, 'http://jamaicaredcross.org/', 1),
(211, 'ba', 'Red Cross of Bosnia and Herzegovina\n', 'Društvo Crvenog Krsta', NULL, 'http://www.rcsbh.org/', 1),
(212, 'lb', 'Lebanon County Christian Ministries', NULL, NULL, 'http://www.lccm.us/', 3),
(213, 'lk', 'Red Cross of Sri Lanka', NULL, NULL, 'http://www.redcross.lk/', 1),
(214, 'sa', 'Red Crescent of Saudia Arabia', '', NULL, 'http://saudiredcrescent.com/', 2),
(215, 'bw', 'Botswana Red Cross Society', NULL, NULL, 'http://www.botswanaredcross.org.bw/', 1),
(216, 'lr', 'Lifting Liberia', NULL, NULL, 'http://liftingliberia.org/', 3),
(217, 'tl', 'Red Cross of East Timor', '', NULL, 'http://www.redcross.tl/', 1),
(218, 'ga', 'Red Cross of Gabon', 'Croix Rouge Gabonaise', NULL, 'http://croixrouge-gabon.org/', 1),
(219, 'gn', 'Guinea - Save the Children', NULL, NULL, 'https://goo.gl/4CQyx8', 3),
(220, 'sz', 'Charity for Swaziland', NULL, NULL, 'https://goo.gl/6QVFBL ', 1),
(221, 'iq', 'Red Crescent of Iraq', '', NULL, 'http://www.ircs.org.iq/', 2),
(222, 'sg', 'Red Cross of Singapore', NULL, NULL, 'https://www.redcross.sg/', 1),
(223, 'cy', 'Cyprus Red Cross Society', NULL, NULL, 'http://www.redcross.org.cy/en/home', 1),
(224, 'hk', 'Red Cross of Hong Kong', '', NULL, 'http://redcross.org.hk/', 1),
(225, 'th', 'Thai Red Cross Society', NULL, NULL, 'https://redcross.or.th/', 1),
(226, 'gq', 'Charity for Equatorial Guinea', '', NULL, 'https://goo.gl/ZGXcEv ', 3),
(227, 'sl', 'Red Cross of Sierra Leone', 'アコムの返済方法', NULL, 'http://www.sierraleoneredcross.net/', 1),
(228, 'al', 'Red Cross of Albania', 'Kryqi Kuq Shqiptar', NULL, 'http://www.kksh.org.al/', 1),
(229, 'sn', 'Red Cross of Senegal', NULL, NULL, 'http://www.croixrougesenegal.com/', 1),
(230, 'fj', 'Fiji Red Cross', NULL, NULL, 'http://www.redcross.com.fj/', 1),
(231, 'sd', 'Red Crescent of Sudan', '', NULL, 'http://srcs.sd/', 2),
(232, 'cs', 'Red Cross of Montenegro', 'Crveni krst Crne Gore', NULL, 'http://www.ckcg.co.me/', 1),
(233, 'me', 'Red Cross of Montenegro', 'Crveni krst Crne Gore', NULL, 'http://www.ckcg.co.me/', 1),
(234, 'cv', 'Charity for Cape Verde', '', NULL, 'https://goo.gl/5DbtM6', 3),
(235, 'kw', 'Red Crescent of Kuwait', '', NULL, 'http://krcs.org.kw/', 2),
(236, 'bd', 'Bangladesh Red Crescent Society', NULL, NULL, 'http://www.bdrcs.org/', 2),
(237, 'mu', 'Mauritius Red Cross', NULL, NULL, 'http://www.mauritiusredcross.org/', 1),
(238, 'kp', 'North Korean Helping Hands', '', NULL, 'http://www.helpinghandskorea.org/', 3),
(239, 'mt', 'Red Cross of Malta', NULL, NULL, 'http://www.redcross.org.mt/', 1),
(240, 'jo', 'Red Cross of Jordan', '', NULL, 'http://www.jnrcs.org/', 2),
(241, 'dz', 'Red Crescent of Algeria', 'Croissant-Rouge Algérien', NULL, 'http://www.cra-algerie.org/', 2),
(242, 'ir', 'Red Crescent of Iran', 'جمعیت هلال احمر جمهوری اسلامی ایران', NULL, 'http://www.rcs.ir/', 2),
(243, 'lu', 'Red Cross of Luxembourg', 'Croix-Rouge Luxembourgeoise', NULL, 'http://www.croix-rouge.lu/', 1),
(244, 'ml', 'Red Cross of Mali', 'Croix-Rouge Malienne', NULL, 'http://www.croixrouge-mali.org/', 1),
(245, 'ma', 'Red Crescent of Morocco', '', NULL, 'http://www.croissant-rouge.ma/', 2),
(246, 'ee', 'Red Cross of Estonia', NULL, NULL, 'http://www.redcross.ee/', 1),
(247, 'bs', 'Red Cross of Bahamas', NULL, NULL, 'http://www.bahamasredcross.com/', 1),
(248, 'np', 'Nepal Red Cross Society', NULL, NULL, 'http://www.nrcs.org/', 1),
(249, 'qa', 'Red Crescent of Qatar', '', NULL, 'http://www.qrcs.org.qa/', 2),
(250, 'sr', 'Charity for Suriname', NULL, NULL, 'https://goo.gl/BPsz9U', 3),
(251, 'bb', 'Charity for Barbados', NULL, NULL, 'http://www.barbadosyp.com/Barbados/Charitable-Organisations', 3),
(252, 'bh', 'Red Crescent of Bahrain', 'Bahrain Red Cresent', NULL, 'http://www.rcsbahrain.org/', 2),
(253, 'om', 'Charity for Oman', '', NULL, 'https://goo.gl/P9hqeW', 3),
(254, 'ly', 'Libya Human Aid', '', NULL, 'https://www.libyahumanaid.org/', 3),
(255, 'gw', 'Charity for Guinea-Bissau', '', NULL, 'https://goo.gl/JrWyk6', 3),
(256, 'kh', 'Cambodian Red Cross', NULL, NULL, 'http://www.redcross.org.kh/index.php?lang=en', 1),
(257, 'tr', 'Red Crescent of Turkey', 'Türk Kızılayı | Anasayfa', NULL, 'http://www.kizilay.org.tr/', 2),
(258, 'tj', 'Red Crescent of Tajikistan', '', NULL, 'http://www.redcrescent.tj/', 2),
(259, 'tw', 'Red Cross of Taiwan', '', NULL, 'http://www.redcross.org.tw/', 1),
(260, 'fm', 'Charity for Micronesia "Habele"', NULL, NULL, 'http://www.habele.org/', 3),
(261, 'gd', 'Red Cross of Grenada', NULL, NULL, 'http://www.grenadaredcross.org/', 1),
(262, 'aw', 'Red Cross of Aruba', '', NULL, 'http://redcrossaruba.com/', 1),
(263, 'ne', 'Charity for Niger', '', NULL, 'https://goo.gl/QNi7hC', 3),
(264, 'to', 'Red Cross of Tonga', NULL, NULL, 'http://www.tongaredcross.com/', 1),
(265, 'sc', 'Red Cross of Seychelles', NULL, NULL, 'http://www.seychellesredcross.sc/', 1),
(266, 'gm', 'Red Cross of Gambia', NULL, NULL, 'http://www.redcross.gm/', 1),
(267, 'ad', 'Red Cross of Andorra', 'Creu Roja [Andorra]', NULL, 'http://www.creuroja.ad/', 1),
(268, 'as', 'Red Cross of American Samoa', NULL, NULL, 'https://redcross.wordpress.com/tag/american-samoa/', 1),
(269, 'ag', 'Charity for Antigua and Barbuda', NULL, NULL, 'https://goo.gl/eeNFnN', 3),
(270, 'dm', 'Charity for Dominica', NULL, NULL, 'https://goo.gl/yJBRvc', 3),
(271, 'gl', 'Charity for Greenland', '', NULL, 'https://goo.gl/J5pnNU', 3),
(272, 'dj', 'Charity for Djibouti', NULL, NULL, 'https://goo.gl/VeSbTX', 3),
(273, 'bn', 'Charity for Brunei', NULL, NULL, 'https://goo.gl/P7ccRU', 3),
(274, 'bm', 'Bermuda Red Cross', NULL, NULL, 'http://www.bermudaredcross.com/', 1),
(275, 'ky', 'Cayman Islands Red Cross', NULL, NULL, 'http://www.redcross.org.ky/', 1),
(277, 'li', 'Red Cross of Liechtenstein', 'Liechtensteinisches Rotes Kreuz', NULL, 'http://www.roteskreuz.li/', 1),
(278, 'mc', 'Red Cross of Monaco', 'Croix Rouge Monaco', NULL, 'http://www.croix-rouge.mc/', 1),
(279, 'tn', 'Charity for Tunisia', '', NULL, 'https://goo.gl/MMS1Fd ', 3),
(280, 'ye', 'Charity for Yemen "Zakat"', '', NULL, 'http://www.zakat.org/country/yemen/', 3),
(281, 'ck', 'Charity for Cook Islands "Cook Foundation"', NULL, NULL, 'http://cookfoundation.org/', 3),
(282, 'pw', 'Charity for Palau', NULL, NULL, 'https://goo.gl/Id7lKD', 3),
(284, 'km', 'Charity for Comoros', '', NULL, 'http://www.comoroscharity.org/', 3),
(285, 'mr', 'Charity for Mauritania', '', NULL, 'https://goo.gl/XK81gO', 3),
(286, 'so', 'Charity for Somalia', '', NULL, 'https://goo.gl/1Bn35T', 3),
(287, 'vt', 'Catholic Parents Online', NULL, NULL, 'http://www.catholicparents.org/', 3);
