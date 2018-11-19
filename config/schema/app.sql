create table branches (
  id      int not null,
  name    varchar(50) not null,
  description text,
  constraint branches_pk
             primary key (id)
);

INSERT INTO branches VALUES(1, 'Rat', null);
INSERT INTO branches VALUES(2, 'Ox', null);
INSERT INTO branches VALUES(3, 'Tiger', null);
INSERT INTO branches VALUES(4, 'Rabbit', null);
INSERT INTO branches VALUES(5, 'Dragon', null);
INSERT INTO branches VALUES(6, 'Snake', null);
INSERT INTO branches VALUES(7, 'Horse', null);
INSERT INTO branches VALUES(8, 'Sheep', null);
INSERT INTO branches VALUES(9, 'Monkey', null);
INSERT INTO branches VALUES(10, 'Rooster', null);
INSERT INTO branches VALUES(11, 'Dog', null);
INSERT INTO branches VALUES(12, 'Boar', null);

create table branch_rules (
  my_branch_id     int not null,
  match_branch_id  int not null,
  score            int,
  description      text,
  constraint branch_rules_pk
             primary key (my_branch_id, match_branch_id),
  constraint branch_rules_my_bid_fk
             foreign key (my_branch_id)
             references branches (id),
  constraint branch_rules_match_bid_fk
             foreign key (match_branch_id)
             references branches (id)
);

INSERT INTO branch_rules VALUES(1, 1, 2, null);
INSERT INTO branch_rules VALUES(1, 2, 2, null);
INSERT INTO branch_rules VALUES(1, 8, 2, null);
INSERT INTO branch_rules VALUES(1, 6, 2, null);
INSERT INTO branch_rules VALUES(1, 3, 1, null);
INSERT INTO branch_rules VALUES(1, 4, 1, null);
INSERT INTO branch_rules VALUES(1, 10, 1, null);
INSERT INTO branch_rules VALUES(1, 11, 1, null);
INSERT INTO branch_rules VALUES(1, 12, 1, null);
INSERT INTO branch_rules VALUES(1, 7, 0, null);
INSERT INTO branch_rules VALUES(2, 2, 2, null);
INSERT INTO branch_rules VALUES(2, 1, 2, null);
INSERT INTO branch_rules VALUES(2, 7, 2, null);
INSERT INTO branch_rules VALUES(2, 9, 2, null);
INSERT INTO branch_rules VALUES(2, 3, 1, null);
INSERT INTO branch_rules VALUES(2, 5, 1, null);
INSERT INTO branch_rules VALUES(2, 4, 1, null);
INSERT INTO branch_rules VALUES(2, 11, 1, null);
INSERT INTO branch_rules VALUES(2, 12, 1, null);
INSERT INTO branch_rules VALUES(2, 8, 0, null);
INSERT INTO branch_rules VALUES(3, 3, 2, null);
INSERT INTO branch_rules VALUES(3, 4, 2, null);
INSERT INTO branch_rules VALUES(3, 10, 2, null);
INSERT INTO branch_rules VALUES(3, 8, 2, null);
INSERT INTO branch_rules VALUES(3, 1, 1, null);
INSERT INTO branch_rules VALUES(3, 2, 1, null);
INSERT INTO branch_rules VALUES(3, 5, 1, null);
INSERT INTO branch_rules VALUES(3, 6, 1, null);
INSERT INTO branch_rules VALUES(3, 12, 1, null);
INSERT INTO branch_rules VALUES(3, 9, 0, null);
INSERT INTO branch_rules VALUES(4, 4, 2, null);
INSERT INTO branch_rules VALUES(4, 3, 2, null);
INSERT INTO branch_rules VALUES(4, 9, 2, null);
INSERT INTO branch_rules VALUES(4, 11, 2, null);
INSERT INTO branch_rules VALUES(4, 5, 1, null);
INSERT INTO branch_rules VALUES(4, 6, 1, null);
INSERT INTO branch_rules VALUES(4, 7, 1, null);
INSERT INTO branch_rules VALUES(4, 2, 1, null);
INSERT INTO branch_rules VALUES(4, 1, 1, null);
INSERT INTO branch_rules VALUES(4, 10, 0, null);
INSERT INTO branch_rules VALUES(5, 5, 2, null);
INSERT INTO branch_rules VALUES(5, 6, 2, null);
INSERT INTO branch_rules VALUES(5, 10, 2, null);
INSERT INTO branch_rules VALUES(5, 12, 2, null);
INSERT INTO branch_rules VALUES(5, 7, 1, null);
INSERT INTO branch_rules VALUES(5, 8, 1, null);
INSERT INTO branch_rules VALUES(5, 4, 1, null);
INSERT INTO branch_rules VALUES(5, 3, 1, null);
INSERT INTO branch_rules VALUES(5, 2, 1, null);
INSERT INTO branch_rules VALUES(5, 11, 0, null);
INSERT INTO branch_rules VALUES(6, 6, 2, null);
INSERT INTO branch_rules VALUES(6, 5, 2, null);
INSERT INTO branch_rules VALUES(6, 11, 2, null);
INSERT INTO branch_rules VALUES(6, 1, 2, null);
INSERT INTO branch_rules VALUES(6, 7, 1, null);
INSERT INTO branch_rules VALUES(6, 8, 1, null);
INSERT INTO branch_rules VALUES(6, 9, 1, null);
INSERT INTO branch_rules VALUES(6, 4, 1, null);
INSERT INTO branch_rules VALUES(6, 3, 1, null);
INSERT INTO branch_rules VALUES(6, 12, 0, null);
INSERT INTO branch_rules VALUES(7, 7, 2, null);
INSERT INTO branch_rules VALUES(7, 8, 2, null);
INSERT INTO branch_rules VALUES(7, 12, 2, null);
INSERT INTO branch_rules VALUES(7, 2, 2, null);
INSERT INTO branch_rules VALUES(7, 6, 1, null);
INSERT INTO branch_rules VALUES(7, 5, 1, null);
INSERT INTO branch_rules VALUES(7, 4, 1, null);
INSERT INTO branch_rules VALUES(7, 9, 1, null);
INSERT INTO branch_rules VALUES(7, 10, 1, null);
INSERT INTO branch_rules VALUES(7, 1, 0, null);
INSERT INTO branch_rules VALUES(8, 8, 2, null);
INSERT INTO branch_rules VALUES(8, 7, 2, null);
INSERT INTO branch_rules VALUES(8, 1, 2, null);
INSERT INTO branch_rules VALUES(8, 3, 2, null);
INSERT INTO branch_rules VALUES(8, 6, 1, null);
INSERT INTO branch_rules VALUES(8, 5, 1, null);
INSERT INTO branch_rules VALUES(8, 9, 1, null);
INSERT INTO branch_rules VALUES(8, 10, 1, null);
INSERT INTO branch_rules VALUES(8, 11, 1, null);
INSERT INTO branch_rules VALUES(8, 2, 0, null);
INSERT INTO branch_rules VALUES(9, 9, 2, null);
INSERT INTO branch_rules VALUES(9, 10, 2, null);
INSERT INTO branch_rules VALUES(9, 2, 2, null);
INSERT INTO branch_rules VALUES(9, 4, 2, null);
INSERT INTO branch_rules VALUES(9, 8, 1, null);
INSERT INTO branch_rules VALUES(9, 7, 1, null);
INSERT INTO branch_rules VALUES(9, 6, 1, null);
INSERT INTO branch_rules VALUES(9, 11, 1, null);
INSERT INTO branch_rules VALUES(9, 12, 1, null);
INSERT INTO branch_rules VALUES(9, 3, 0, null);
INSERT INTO branch_rules VALUES(10, 10, 2, null);
INSERT INTO branch_rules VALUES(10, 9, 2, null);
INSERT INTO branch_rules VALUES(10, 5, 2, null);
INSERT INTO branch_rules VALUES(10, 3, 2, null);
INSERT INTO branch_rules VALUES(10, 1, 1, null);
INSERT INTO branch_rules VALUES(10, 7, 1, null);
INSERT INTO branch_rules VALUES(10, 8, 1, null);
INSERT INTO branch_rules VALUES(10, 11, 1, null);
INSERT INTO branch_rules VALUES(10, 12, 1, null);
INSERT INTO branch_rules VALUES(10, 4, 0, null);
INSERT INTO branch_rules VALUES(11, 11, 2, null);
INSERT INTO branch_rules VALUES(11, 12, 2, null);
INSERT INTO branch_rules VALUES(11, 6, 2, null);
INSERT INTO branch_rules VALUES(11, 4, 2, null);
INSERT INTO branch_rules VALUES(11, 8, 1, null);
INSERT INTO branch_rules VALUES(11, 2, 1, null);
INSERT INTO branch_rules VALUES(11, 1, 1, null);
INSERT INTO branch_rules VALUES(11, 10, 1, null);
INSERT INTO branch_rules VALUES(11, 9, 1, null);
INSERT INTO branch_rules VALUES(11, 5, 0, null);
INSERT INTO branch_rules VALUES(12, 12, 2, null);
INSERT INTO branch_rules VALUES(12, 11, 2, null);
INSERT INTO branch_rules VALUES(12, 7, 2, null);
INSERT INTO branch_rules VALUES(12, 5, 2, null);
INSERT INTO branch_rules VALUES(12, 3, 1, null);
INSERT INTO branch_rules VALUES(12, 9, 1, null);
INSERT INTO branch_rules VALUES(12, 10, 1, null);
INSERT INTO branch_rules VALUES(12, 2, 1, null);
INSERT INTO branch_rules VALUES(12, 1, 1, null);
INSERT INTO branch_rules VALUES(12, 6, 0, null);

CREATE TABLE phases (
  id             int not null,
  name           varchar(50) not null,
  strength_title varchar(100),
  strength       text,
  weakness_title varchar(100),
  weakness       text,
  constraint phases_pk
             primary key (id)
);

INSERT INTO phases VALUES (1, 'Wood', 'Leader', 'Explorer of the unknown, innovative, adaptive and expansive', 'Controller', 'Over-do, over-perform, over-direct');
INSERT INTO phases VALUES (2, 'Fire', NULL, NULL, NULL, NULL);
INSERT INTO phases VALUES (3, 'Earth', 'Diplomat', 'Caring for ourselves and others, balance between giving and receiving', 'Rescuer', 'Inequality, taking on more than our share, unsupported');
INSERT INTO phases VALUES (4, 'Metal', 'Observer', 'Detached observation providing the structure for transformation', 'Inflexible', 'Over-structured, rigid, stuck');
INSERT INTO phases VALUES (5, 'Water', 'Philosopher', 'Brings light to what is hidden through reflection and renewal', 'Invisible', 'Withdrawn, reclusive and inaccessible');

create table stems (
  id         int not null,
  name       varchar(50) not null,
  phase_id   int not null,
  personality_type enum('Yin','Yang') not null,
  constraint stems_pk
             primary key (id),
  constraint stems_phase_id_fk
             foreign key (phase_id)
             references phases (id)
);

INSERT INTO stems VALUES (1, 'Yang Wood', 1, 'Yang');
INSERT INTO stems VALUES (2, 'Yin Wood', 1, 'Yin');
INSERT INTO stems VALUES (3, 'Yang Fire', 2, 'Yang');
INSERT INTO stems VALUES (4, 'Yin Fire', 2, 'Yin');
INSERT INTO stems VALUES (5, 'Yang Earth', 3, 'Yang');
INSERT INTO stems VALUES (6, 'Yin Earth', 3, 'Yin');
INSERT INTO stems VALUES (7, 'Yang Metal', 4, 'Yang');
INSERT INTO stems VALUES (8, 'Yin Metal', 4, 'Yin');
INSERT INTO stems VALUES (9, 'Yang Water', 5, 'Yang');
INSERT INTO stems VALUES (10, 'Yin Water', 5, 'Yin');

CREATE TABLE phase_rules (
  `my_type` varchar(5) NOT NULL DEFAULT '',
  `my_phase_id` int(11) NOT NULL DEFAULT '0',
  `match_type` varchar(5) NOT NULL DEFAULT '',
  `match_phase_id` int(11) NOT NULL DEFAULT '0',
  `score` int(11) DEFAULT NULL,
  PRIMARY KEY (`my_type`,`my_phase_id`,`match_type`,`match_phase_id`),
  KEY `fk_red_phase_rule_my_pid` (`my_phase_id`),
  KEY `fk_red_phase_rule_mat_pid` (`match_phase_id`)
);

-- 
-- Dumping data for table `red_phase_rule`
-- 

INSERT INTO phase_rules VALUES ('Yang', 1, 'Yang', 1, 2);
INSERT INTO phase_rules VALUES ('Yang', 1, 'Yang', 2, 0);
INSERT INTO phase_rules VALUES ('Yang', 1, 'Yang', 3, 0);
INSERT INTO phase_rules VALUES ('Yang', 1, 'Yang', 4, 0);
INSERT INTO phase_rules VALUES ('Yang', 1, 'Yang', 5, 0);
INSERT INTO phase_rules VALUES ('Yang', 1, 'Yin', 1, 2);
INSERT INTO phase_rules VALUES ('Yang', 1, 'Yin', 2, -1);
INSERT INTO phase_rules VALUES ('Yang', 1, 'Yin', 3, 1);
INSERT INTO phase_rules VALUES ('Yang', 1, 'Yin', 4, -2);
INSERT INTO phase_rules VALUES ('Yang', 1, 'Yin', 5, 2);
INSERT INTO phase_rules VALUES ('Yang', 2, 'Yang', 1, 0);
INSERT INTO phase_rules VALUES ('Yang', 2, 'Yang', 2, 2);
INSERT INTO phase_rules VALUES ('Yang', 2, 'Yang', 3, 0);
INSERT INTO phase_rules VALUES ('Yang', 2, 'Yang', 4, 0);
INSERT INTO phase_rules VALUES ('Yang', 2, 'Yang', 5, 0);
INSERT INTO phase_rules VALUES ('Yang', 2, 'Yin', 1, 2);
INSERT INTO phase_rules VALUES ('Yang', 2, 'Yin', 2, 2);
INSERT INTO phase_rules VALUES ('Yang', 2, 'Yin', 3, -1);
INSERT INTO phase_rules VALUES ('Yang', 2, 'Yin', 4, 1);
INSERT INTO phase_rules VALUES ('Yang', 2, 'Yin', 5, -2);
INSERT INTO phase_rules VALUES ('Yang', 3, 'Yang', 1, 0);
INSERT INTO phase_rules VALUES ('Yang', 3, 'Yang', 2, 0);
INSERT INTO phase_rules VALUES ('Yang', 3, 'Yang', 3, 2);
INSERT INTO phase_rules VALUES ('Yang', 3, 'Yang', 4, 0);
INSERT INTO phase_rules VALUES ('Yang', 3, 'Yang', 5, 0);
INSERT INTO phase_rules VALUES ('Yang', 3, 'Yin', 1, -2);
INSERT INTO phase_rules VALUES ('Yang', 3, 'Yin', 2, 2);
INSERT INTO phase_rules VALUES ('Yang', 3, 'Yin', 3, 2);
INSERT INTO phase_rules VALUES ('Yang', 3, 'Yin', 4, -1);
INSERT INTO phase_rules VALUES ('Yang', 3, 'Yin', 5, 1);
INSERT INTO phase_rules VALUES ('Yang', 4, 'Yang', 1, 0);
INSERT INTO phase_rules VALUES ('Yang', 4, 'Yang', 2, 0);
INSERT INTO phase_rules VALUES ('Yang', 4, 'Yang', 3, 0);
INSERT INTO phase_rules VALUES ('Yang', 4, 'Yang', 4, 2);
INSERT INTO phase_rules VALUES ('Yang', 4, 'Yang', 5, 0);
INSERT INTO phase_rules VALUES ('Yang', 4, 'Yin', 1, 1);
INSERT INTO phase_rules VALUES ('Yang', 4, 'Yin', 2, -2);
INSERT INTO phase_rules VALUES ('Yang', 4, 'Yin', 3, 2);
INSERT INTO phase_rules VALUES ('Yang', 4, 'Yin', 4, 2);
INSERT INTO phase_rules VALUES ('Yang', 4, 'Yin', 5, -1);
INSERT INTO phase_rules VALUES ('Yang', 5, 'Yang', 1, 0);
INSERT INTO phase_rules VALUES ('Yang', 5, 'Yang', 2, 0);
INSERT INTO phase_rules VALUES ('Yang', 5, 'Yang', 3, 0);
INSERT INTO phase_rules VALUES ('Yang', 5, 'Yang', 4, 0);
INSERT INTO phase_rules VALUES ('Yang', 5, 'Yang', 5, 2);
INSERT INTO phase_rules VALUES ('Yang', 5, 'Yin', 1, -1);
INSERT INTO phase_rules VALUES ('Yang', 5, 'Yin', 2, 1);
INSERT INTO phase_rules VALUES ('Yang', 5, 'Yin', 3, -2);
INSERT INTO phase_rules VALUES ('Yang', 5, 'Yin', 4, 2);
INSERT INTO phase_rules VALUES ('Yang', 5, 'Yin', 5, 2);
INSERT INTO phase_rules VALUES ('Yin', 1, 'Yang', 1, 2);
INSERT INTO phase_rules VALUES ('Yin', 1, 'Yang', 2, 2);
INSERT INTO phase_rules VALUES ('Yin', 1, 'Yang', 3, -1);
INSERT INTO phase_rules VALUES ('Yin', 1, 'Yang', 4, 1);
INSERT INTO phase_rules VALUES ('Yin', 1, 'Yang', 5, -2);
INSERT INTO phase_rules VALUES ('Yin', 1, 'Yin', 1, 2);
INSERT INTO phase_rules VALUES ('Yin', 1, 'Yin', 2, 0);
INSERT INTO phase_rules VALUES ('Yin', 1, 'Yin', 3, 0);
INSERT INTO phase_rules VALUES ('Yin', 1, 'Yin', 4, 0);
INSERT INTO phase_rules VALUES ('Yin', 1, 'Yin', 5, 0);
INSERT INTO phase_rules VALUES ('Yin', 2, 'Yang', 1, -2);
INSERT INTO phase_rules VALUES ('Yin', 2, 'Yang', 2, 2);
INSERT INTO phase_rules VALUES ('Yin', 2, 'Yang', 3, 2);
INSERT INTO phase_rules VALUES ('Yin', 2, 'Yang', 4, -1);
INSERT INTO phase_rules VALUES ('Yin', 2, 'Yang', 5, 1);
INSERT INTO phase_rules VALUES ('Yin', 2, 'Yin', 1, 0);
INSERT INTO phase_rules VALUES ('Yin', 2, 'Yin', 2, 2);
INSERT INTO phase_rules VALUES ('Yin', 2, 'Yin', 3, 0);
INSERT INTO phase_rules VALUES ('Yin', 2, 'Yin', 4, 0);
INSERT INTO phase_rules VALUES ('Yin', 2, 'Yin', 5, 0);
INSERT INTO phase_rules VALUES ('Yin', 3, 'Yang', 1, 1);
INSERT INTO phase_rules VALUES ('Yin', 3, 'Yang', 2, -2);
INSERT INTO phase_rules VALUES ('Yin', 3, 'Yang', 3, 2);
INSERT INTO phase_rules VALUES ('Yin', 3, 'Yang', 4, 2);
INSERT INTO phase_rules VALUES ('Yin', 3, 'Yang', 5, -1);
INSERT INTO phase_rules VALUES ('Yin', 3, 'Yin', 1, 0);
INSERT INTO phase_rules VALUES ('Yin', 3, 'Yin', 2, 0);
INSERT INTO phase_rules VALUES ('Yin', 3, 'Yin', 3, 2);
INSERT INTO phase_rules VALUES ('Yin', 3, 'Yin', 4, 0);
INSERT INTO phase_rules VALUES ('Yin', 3, 'Yin', 5, 0);
INSERT INTO phase_rules VALUES ('Yin', 4, 'Yang', 1, -1);
INSERT INTO phase_rules VALUES ('Yin', 4, 'Yang', 2, 1);
INSERT INTO phase_rules VALUES ('Yin', 4, 'Yang', 3, -2);
INSERT INTO phase_rules VALUES ('Yin', 4, 'Yang', 4, 2);
INSERT INTO phase_rules VALUES ('Yin', 4, 'Yang', 5, 2);
INSERT INTO phase_rules VALUES ('Yin', 4, 'Yin', 1, 0);
INSERT INTO phase_rules VALUES ('Yin', 4, 'Yin', 2, 0);
INSERT INTO phase_rules VALUES ('Yin', 4, 'Yin', 3, 0);
INSERT INTO phase_rules VALUES ('Yin', 4, 'Yin', 4, 2);
INSERT INTO phase_rules VALUES ('Yin', 4, 'Yin', 5, 0);
INSERT INTO phase_rules VALUES ('Yin', 5, 'Yang', 1, 2);
INSERT INTO phase_rules VALUES ('Yin', 5, 'Yang', 2, -1);
INSERT INTO phase_rules VALUES ('Yin', 5, 'Yang', 3, 1);
INSERT INTO phase_rules VALUES ('Yin', 5, 'Yang', 4, -2);
INSERT INTO phase_rules VALUES ('Yin', 5, 'Yang', 5, 2);
INSERT INTO phase_rules VALUES ('Yin', 5, 'Yin', 1, 0);
INSERT INTO phase_rules VALUES ('Yin', 5, 'Yin', 2, 0);
INSERT INTO phase_rules VALUES ('Yin', 5, 'Yin', 3, 0);
INSERT INTO phase_rules VALUES ('Yin', 5, 'Yin', 4, 0);
INSERT INTO phase_rules VALUES ('Yin', 5, 'Yin', 5, 2);

create table stem_rules (
  my_stem_id       int,
  match_stem_id    int,
  score            int,
  constraint stem_rules_pk
             primary key (my_stem_id, match_stem_id),
  constraint stem_rules_my_sid_fk
             foreign key (my_stem_id)
             references stems (id),
  constraint stem_rules_match_sid_fk
             foreign key (match_stem_id)
             references stems (id)
);

insert
  into stem_rules (my_stem_id, match_stem_id, score)
select my_stems.id,
       match_stems.id,
       phase_rules.score
  from phase_rules
       inner join stems my_stems
          on phase_rules.my_type = my_stems.personality_type
         and phase_rules.my_phase_id = my_stems.phase_id
       inner join stems match_stems
          on phase_rules.match_type = match_stems.personality_type
         and phase_rules.match_phase_id = match_stems.phase_id;

create table countries (
  country_code char(2),
  name         varchar(255) not null,
  constraint countries_pk
             primary key (country_code)
);

insert into countries values
('AD',	'Andorra'),
('AE',	'United Arab Emirates'),
('AF',	'Afghanistan'),
('AG',	'Antigua and Barbuda'),
('AI',	'Anguilla'),
('AL',	'Albania'),
('AM',	'Armenia'),
('AO',	'Angola'),
('AQ',	'Antarctica'),
('AR',	'Argentina'),
('AS',	'American Samoa'),
('AT',	'Austria'),
('AU',	'Australia'),
('AW',	'Aruba'),
('AX',	'Åland Islands'),
('AZ',	'Azerbaijan'),
('BA',	'Bosnia and Herzegovina'),
('BB',	'Barbados'),
('BD',	'Bangladesh'),
('BE',	'Belgium'),
('BF',	'Burkina Faso'),
('BG',	'Bulgaria'),
('BH',	'Bahrain'),
('BI',	'Burundi'),
('BJ',	'Benin'),
('BL',	'Saint Barthélemy'),
('BM',	'Bermuda'),
('BN',	'Brunei Darussalam'),
('BO',	'Bolivia, Plurinational State of'),
('BQ',	'Bonaire, Sint Eustatius and Saba'),
('BR',	'Brazil'),
('BS',	'Bahamas'),
('BT',	'Bhutan'),
('BV',	'Bouvet Island'),
('BW',	'Botswana'),
('BY',	'Belarus'),
('BZ',	'Belize'),
('CA',	'Canada'),
('CC',	'Cocos (Keeling) Islands'),
('CD',	'Congo, the Democratic Republic of the'),
('CF',	'Central African Republic'),
('CG',	'Congo'),
('CH',	'Switzerland'),
('CI',	'Côte d\'Ivoire'),
('CK',	'Cook Islands'),
('CL',	'Chile'),
('CM',	'Cameroon'),
('CN',	'China'),
('CO',	'Colombia'),
('CR',	'Costa Rica'),
('CU',	'Cuba'),
('CV',	'Cabo Verde'),
('CW',	'Curaçao'),
('CX',	'Christmas Island'),
('CY',	'Cyprus'),
('CZ',	'Czechia'),
('DE',	'Germany'),
('DJ',	'Djibouti'),
('DK',	'Denmark'),
('DM',	'Dominica'),
('DO',	'Dominican Republic'),
('DZ',	'Algeria'),
('EC',	'Ecuador'),
('EE',	'Estonia'),
('EG',	'Egypt'),
('EH',	'Western Sahara'),
('ER',	'Eritrea'),
('ES',	'Spain'),
('ET',	'Ethiopia'),
('FI',	'Finland'),
('FJ',	'Fiji'),
('FK',	'Falkland Islands (Malvinas)'),
('FM',	'Micronesia, Federated States of'),
('FO',	'Faroe Islands'),
('FR',	'France'),
('GA',	'Gabon'),
('GB',	'United Kingdom of Great Britain and Northern Ireland'),
('GD',	'Grenada'),
('GE',	'Georgia'),
('GF',	'French Guiana'),
('GG',	'Guernsey'),
('GH',	'Ghana'),
('GI',	'Gibraltar'),
('GL',	'Greenland'),
('GM',	'Gambia'),
('GN',	'Guinea'),
('GP',	'Guadeloupe'),
('GQ',	'Equatorial Guinea'),
('GR',	'Greece'),
('GS',	'South Georgia and the South Sandwich Islands'),
('GT',	'Guatemala'),
('GU',	'Guam'),
('GW',	'Guinea-Bissau'),
('GY',	'Guyana'),
('HK',	'Hong Kong'),
('HM',	'Heard Island and McDonald Islands'),
('HN',	'Honduras'),
('HR',	'Croatia'),
('HT',	'Haiti'),
('HU',	'Hungary'),
('ID',	'Indonesia'),
('IE',	'Ireland'),
('IL',	'Israel'),
('IM',	'Isle of Man'),
('IN',	'India'),
('IO',	'British Indian Ocean Territory'),
('IQ',	'Iraq'),
('IR',	'Iran, Islamic Republic of'),
('IS',	'Iceland'),
('IT',	'Italy'),
('JE',	'Jersey'),
('JM',	'Jamaica'),
('JO',	'Jordan'),
('JP',	'Japan'),
('KE',	'Kenya'),
('KG',	'Kyrgyzstan'),
('KH',	'Cambodia'),
('KI',	'Kiribati'),
('KM',	'Comoros'),
('KN',	'Saint Kitts and Nevis'),
('KP',	'Korea, Democratic People\'s Republic of'),
('KR',	'Korea, Republic of'),
('KW',	'Kuwait'),
('KY',	'Cayman Islands'),
('KZ',	'Kazakhstan'),
('LA',	'Lao People\'s Democratic Republic'),
('LB',	'Lebanon'),
('LC',	'Saint Lucia'),
('LI',	'Liechtenstein'),
('LK',	'Sri Lanka'),
('LR',	'Liberia'),
('LS',	'Lesotho'),
('LT',	'Lithuania'),
('LU',	'Luxembourg'),
('LV',	'Latvia'),
('LY',	'Libya'),
('MA',	'Morocco'),
('MC',	'Monaco'),
('MD',	'Moldova, Republic of'),
('ME',	'Montenegro'),
('MF',	'Saint Martin (French part)'),
('MG',	'Madagascar'),
('MH',	'Marshall Islands'),
('MK',	'Macedonia, the former Yugoslav Republic of'),
('ML',	'Mali'),
('MM',	'Myanmar'),
('MN',	'Mongolia'),
('MO',	'Macao'),
('MP',	'Northern Mariana Islands'),
('MQ',	'Martinique'),
('MR',	'Mauritania'),
('MS',	'Montserrat'),
('MT',	'Malta'),
('MU',	'Mauritius'),
('MV',	'Maldives'),
('MW',	'Malawi'),
('MX',	'Mexico'),
('MY',	'Malaysia'),
('MZ',	'Mozambique'),
('NA',	'Namibia'),
('NC',	'New Caledonia'),
('NE',	'Niger'),
('NF',	'Norfolk Island'),
('NG',	'Nigeria'),
('NI',	'Nicaragua'),
('NL',	'Netherlands'),
('NO',	'Norway'),
('NP',	'Nepal'),
('NR',	'Nauru'),
('NU',	'Niue'),
('NZ',	'New Zealand'),
('OM',	'Oman'),
('PA',	'Panama'),
('PE',	'Peru'),
('PF',	'French Polynesia'),
('PG',	'Papua New Guinea'),
('PH',	'Philippines'),
('PK',	'Pakistan'),
('PL',	'Poland'),
('PM',	'Saint Pierre and Miquelon'),
('PN',	'Pitcairn'),
('PR',	'Puerto Rico'),
('PS',	'Palestine, State of'),
('PT',	'Portugal'),
('PW',	'Palau'),
('PY',	'Paraguay'),
('QA',	'Qatar'),
('RE',	'Réunion'),
('RO',	'Romania'),
('RS',	'Serbia'),
('RU',	'Russian Federation'),
('RW',	'Rwanda'),
('SA',	'Saudi Arabia'),
('SB',	'Solomon Islands'),
('SC',	'Seychelles'),
('SD',	'Sudan'),
('SE',	'Sweden'),
('SG',	'Singapore'),
('SH',	'Saint Helena, Ascension and Tristan da Cunha'),
('SI',	'Slovenia'),
('SJ',	'Svalbard and Jan Mayen'),
('SK',	'Slovakia'),
('SL',	'Sierra Leone'),
('SM',	'San Marino'),
('SN',	'Senegal'),
('SO',	'Somalia'),
('SR',	'Suriname'),
('SS',	'South Sudan'),
('ST',	'Sao Tome and Principe'),
('SV',	'El Salvador'),
('SX',	'Sint Maarten (Dutch part)'),
('SY',	'Syrian Arab Republic'),
('SZ',	'Swaziland'),
('TC',	'Turks and Caicos Islands'),
('TD',	'Chad'),
('TF',	'French Southern Territories'),
('TG',	'Togo'),
('TH',	'Thailand'),
('TJ',	'Tajikistan'),
('TK',	'Tokelau'),
('TL',	'Timor-Leste'),
('TM',	'Turkmenistan'),
('TN',	'Tunisia'),
('TO',	'Tonga'),
('TR',	'Turkey'),
('TT',	'Trinidad and Tobago'),
('TV',	'Tuvalu'),
('TW',	'Taiwan, Province of China'),
('TZ',	'Tanzania, United Republic of'),
('UA',	'Ukraine'),
('UG',	'Uganda'),
('UM',	'United States Minor Outlying Islands'),
('US',	'United States of America'),
('UY',	'Uruguay'),
('UZ',	'Uzbekistan'),
('VA',	'Holy See'),
('VC',	'Saint Vincent and the Grenadines'),
('VE',	'Venezuela, Bolivarian Republic of'),
('VG',	'Virgin Islands, British'),
('VI',	'Virgin Islands, U.S.'),
('VN',	'Viet Nam'),
('VU',	'Vanuatu'),
('WF',	'Wallis and Futuna'),
('WS',	'Samoa'),
('YE',	'Yemen'),
('YT',	'Mayotte'),
('ZA',	'South Africa'),
('ZM',	'Zambia'),
('ZW',	'Zimbabwe');

CREATE TABLE users (
  `id`               int(11) NOT NULL AUTO_INCREMENT,
  `name`             varchar(255) DEFAULT NULL,
  `birth_date`       date DEFAULT NULL,
  `hour_num`         int(11) DEFAULT NULL,
  `day_stem_id`      int(11) DEFAULT NULL,
  `month_stem_id`    int(11) DEFAULT NULL,
  `year_stem_id`     int(11) DEFAULT NULL,
  `hour_stem_id`     int(11) DEFAULT NULL,
  `day_branch_id`    int(11) DEFAULT NULL,
  `month_branch_id`  int(11) DEFAULT NULL,
  `year_branch_id`   int(11) DEFAULT NULL,
  `hour_branch_id`   int(11) DEFAULT NULL,
   gender            enum('Female', 'Male', 'Other'), 
  `match_min_age`    int(11) DEFAULT '25',
  `match_max_age`    int(11) DEFAULT '60',
  `personality_type` enum('Yin', 'Yang'),
  `phase_id`         int(11) DEFAULT NULL,
  `email`            varchar(255) NOT NULL,
  `country_code`     char(2) DEFAULT NULL,
  timezone           varchar(50) default 'America/New_York',
  `zipcode`          varchar(50) DEFAULT NULL,
  latitude           float(10,6) default 0,
  longitude          float(10,6) default 0,
  address            varchar(500),
  `password`         varchar(500) DEFAULT NULL,
  `introduction`     text,
  `distance`         int(11) DEFAULT '9999',
  status             enum('Pending', 'Active', 'Inactive', 'Deleted') default 'Active',
  created_date       timestamp default current_timestamp,
  deactivated_date   timestamp null,
  deleted_date       timestamp null,
  constraint users_pk PRIMARY KEY (`id`),
  constraint users_day_stem_id_fk
             foreign key (day_stem_id)
             references stems (id),
  constraint users_month_stem_id_fk
             foreign key (month_stem_id)
             references stems (id),
  constraint users_year_stem_id_fk
             foreign key (year_stem_id)
             references stems (id),
  constraint users_hour_stem_id_fk
             foreign key (hour_stem_id)
             references stems (id),
  constraint users_day_branch_id_fk
             foreign key (day_branch_id)
             references branches (id),
  constraint users_month_branch_id_fk
             foreign key (month_branch_id)
             references branches (id),
  constraint users_year_branch_id_fk
             foreign key (year_branch_id)
             references branches (id),
  constraint users_hour_branch_id_fk
             foreign key (hour_branch_id)
             references branches (id),
  constraint users_country_code_fk
             foreign key (country_code)
             references countries (country_code)
);
create unique index users_email_uk on users (email);
create unique index users_name_uk on users (name);
alter table users character set utf8mb4;

insert into users (id, name, birth_date, hour_num, day_stem_id, month_stem_id, year_stem_id, hour_stem_id, day_branch_id, month_branch_id, year_branch_id, hour_branch_id,
gender, match_min_age, match_max_age, personality_type, phase_id, email, country_code, zipcode, password, introduction, distance)
values
 (1, 'Sucheela', '1975-02-01', 18, 5, 4, 1, 8, 3, 2, 3, 10, 'Female', 18, 99, 'Yang', 3, 'sucheela.n@gmail.com', 'US', '11217', '96e79218965eb72c92a549dd5a330112', 'I''m a good person.\r\n\r\n&lt;asdfjlsdkf&gt;&amp;?', 20),
 (2, 'Alex', '1973-08-21', 4, 6, 7, 10, 3, 2, 9, 2, 3, 'Male', 25, 60, 'Yang', 4, 'alex.garciaosuna@gmail.com', 'US', '11217', NULL, NULL, 9999),
 (3, 'Robert', '1955-04-02', 0, 10, 7, 2, 9, 6, 5, 8, 1, 'Male', 25, 60, NULL, NULL, 'robert@internet.com', 'US', '11217', NULL, NULL, 9999),
 (4, 'Elvira', '1941-04-02', 0, 7, 9, 8, 3, 11, 5, 6, 1, 'Female', 25, 60, NULL, NULL, 'elvira@internet.com', 'US', '11217', NULL, NULL, 9999),
 (5, 'Monica', '1970-09-16', 4, 6, 2, 7, 3, 12, 10, 11, 3, 'Female', 25, 60, 'Yang', 3, 'monica@internet.com', 'US', '11217', NULL, NULL, 9999),
 (6, 'Timothy', '1969-02-07', 0, 10, 2, 5, 9, 2, 2, 9, 1, 'Male', 25, 60, NULL, NULL, 'timothy@internet.com', 'US', '11217', NULL, NULL, 9999),
 (7, 'Jose', '1974-03-17', 0, 4, 4, 1, 7, 6, 4, 3, 1, 'Male', 25, 60, NULL, NULL, 'jose@internet.com', 'US', '11217', NULL, NULL, 9999),
 (8, 'Jennifer', '1975-01-23', 14, 6, 4, 1, 8, 6, 2, 3, 8, 'Female', 25, 60, NULL, NULL, 'jennifer@internet.com', 'US', '11217', NULL, NULL, 9999),
 (9, 'Cindy', '1976-03-06', 0, 4, 8, 3, 7, 6, 4, 5, 1, 'Female', 25, 60, NULL, NULL, 'cindy@internet.com', 'US', '11217', NULL, NULL, 9999),
 (10, 'Kaoru', '1974-03-08', 0, 5, 4, 1, 9, 9, 4, 3, 1, 'Female', 25, 60, NULL, NULL, 'kaoru@internet.com', 'US', '11217', NULL, NULL, 9999),
 (11, 'Julio', '1945-12-17', 0, 7, 5, 2, 3, 9, 1, 10, 1, 'Male', 25, 60, NULL, NULL, 'julio@internet.com', 'US', '11217', NULL, NULL, 9999),
 (12, 'Morgane', '1990-05-29', 12, 1, 9, 7, 7, 7, 7, 7, 7, 'Female', 25, 60, NULL, NULL, 'morgane@internet.com', 'US', '11217', NULL, NULL, 9999),
 (13, 'Anne Sophie', '1987-04-01', 10, 7, 1, 4, 8, 5, 5, 4, 6, 'Female', 25, 60, NULL, NULL, 'anne.sophie@internet.com', 'US', '11217', NULL, NULL, 9999),
 (14, 'Avinash', '1974-02-07', 12, 6, 3, 1, 7, 4, 3, 3, 7, 'Male', 25, 60, NULL, NULL, 'avinash@internet.com', 'US', '11217', NULL, NULL, 9999),
 (15, 'Emperor Palpatine', '1969-12-31', 12, 1, 3, 8, 7, 7, 9, 2, 7, 'Other', 25, 60, NULL, NULL, 'palpatine@internet.com', 'US', '11217', NULL, NULL, 9999),
 (16, 'Luke Skywalker', '1998-02-13', 4, 8, 1, 5, 7, 4, 3, 3, 3, 'Male', 25, 60, NULL, NULL, 'lskywalker@internet.com', 'US', '11217', NULL, NULL, 9999),
 (17, 'Anakin Skywalker', '1974-05-20', 12, 8, 6, 1, 1, 10, 6, 3, 7, 'Male', 25, 60, NULL, NULL, 'askywalker@internet.com', 'US', '11217', NULL, NULL, 9999),
 (18, 'Count Dooku', '1962-04-13', 20, 8, 1, 9, 5, 6, 5, 3, 11, 'Male', 25, 60, NULL, NULL, 'donut@internet.com', 'US', '11217', NULL, NULL, 9999),
 (19, 'Retardo Dumbass', '1946-10-30', 12, 4, 6, 3, 3, 2, 12, 11, 7, 'Male', 25, 60, NULL, NULL, 'dumbass@internet.com', 'US', '11217', NULL, NULL, 9999),
 (20, 'Teal\\\\\\''c', '1934-04-18', 0, 10, 5, 1, 9, 4, 5, 11, 1, 'Male', 25, 60, NULL, NULL, 'tealc@internet.com', 'US', '11217', NULL, NULL, 9999),
 (21, 'Joe !', '1929-10-21', 8, 2, 1, 6, 7, 12, 11, 6, 5, 'Male', 25, 60, NULL, NULL, 'joey@internet.com', 'US', '11217', NULL, NULL, 9999),
 (22, 'Old Dirty Bastard', '1968-06-14', 14, 2, 5, 5, 10, 4, 7, 9, 8, 'Male', 25, 60, NULL, NULL, 'bastard@internet.com', 'US', '11217', NULL, NULL, 9999),
 (23, 'Sponge Bob', '1999-05-06', 14, 5, 5, 6, 6, 7, 5, 4, 8, 'Male', 25, 60, NULL, NULL, 'spongebob@internet.com', 'US', '11217', NULL, NULL, 9999),
 (24, 'Telford', '1992-03-15', 12, 7, 10, 9, 9, 3, 4, 9, 7, 'Male', 25, 60, NULL, NULL, 'telford@internet.com', 'US', '11217', NULL, NULL, 9999),
 (25, 'Tony Soprano', '1965-05-10', 4, 1, 8, 2, 3, 1, 6, 6, 3, 'Male', 25, 60, NULL, NULL, 'tonysop@internet.com', 'US', '11217', NULL, NULL, 9999),
 (26, 'Homer Simpson', '1957-08-17', 12, 8, 5, 4, 1, 10, 9, 10, 7, 'Male', 25, 60, NULL, NULL, 'homer@internet.com', 'US', '11217', NULL, NULL, 9999),
 (27, 'John Frak', '1958-04-09', 16, 3, 2, 5, 3, 5, 4, 11, 9, 'Male', 25, 60, NULL, NULL, 'freak@internet.com', 'US', '11217', NULL, NULL, 9999),
 (28, 'Albert Einstein III', '1951-09-13', 6, 3, 4, 8, 8, 5, 10, 4, 4, 'Male', 25, 60, NULL, NULL, 'einstein@internet.com', 'US', '11217', NULL, NULL, 9999),
 (29, 'Alf Creature', '1969-09-13', 2, 8, 10, 6, 6, 4, 10, 10, 2, 'Other', 25, 60, NULL, NULL, 'misteralf@internet.com', 'US', '11217', NULL, NULL, 9999),
 (30, 'Marmaduke', '1973-12-05', 8, 2, 1, 10, 7, 12, 1, 2, 5, 'Male', 25, 60, NULL, NULL, 'marmaduke@internet.com', 'US', '11217', NULL, NULL, 9999),
 (31, 'Sofia Vergara', '1970-04-10', 20, 7, 7, 7, 3, 9, 5, 11, 11, 'Female', 25, 60, NULL, NULL, 'vergara@internet.com', 'US', '11217', NULL, NULL, 9999),
 (32, 'Lilly Bonfudenberg', '1960-10-16', 16, 4, 2, 7, 5, 2, 10, 1, 9, 'Female', 25, 60, NULL, NULL, 'lilly@internet.com', 'US', '11217', NULL, NULL, 9999),
 (33, 'Salma Hayek', '1965-12-07', 14, 2, 5, 2, 10, 8, 1, 6, 8, 'Female', 25, 60, NULL, NULL, 'slama@internet.com', 'US', '11217', NULL, NULL, 9999),
 (34, 'Rita Hayworth-Bates', '1953-01-22', 4, 10, 10, 9, 1, 10, 2, 5, 3, 'Female', 25, 60, NULL, NULL, 'rita@internet.com', 'US', '11217', NULL, NULL, 9999),
 (35, 'Miley Cyrus', '1987-04-01', 23, 7, 1, 4, 3, 5, 5, 4, 1, 'Female', 25, 60, NULL, NULL, 'miley@internet.com', 'US', '11217', NULL, NULL, 9999),
 (36, 'Jaden', '2003-12-20', 20, 4, 1, 10, 7, 4, 1, 8, 11, 'Male', 25, 60, NULL, NULL, 'jaden@internet.com', 'US', '11217', NULL, NULL, 9999),
 (37, 'Simone', '2005-10-05', 14, 9, 3, 2, 4, 11, 11, 10, 8, 'Female', 25, 60, NULL, NULL, 'simone@internet.com', 'US', '11217', NULL, NULL, 9999),
 (38, 'Martouf Tokra', '1922-09-01', 2, 9, 5, 9, 8, 11, 9, 11, 2, 'Male', 25, 60, NULL, NULL, 'tokra@internet.com', 'US', '11217', NULL, NULL, 9999),
 (39, 'Christopher Judgemyballs', '1969-08-08', 16, 2, 8, 6, 1, 4, 8, 10, 9, 'Male', 25, 60, NULL, NULL, 'christ@internet.com', 'US', '11217', NULL, NULL, 9999),
 (40, 'Michael Getshankedalot', '1970-03-08', 0, 4, 6, 7, 7, 12, 4, 11, 1, 'Male', 25, 60, NULL, NULL, 'micahel@internet.com', 'US', '11217', NULL, NULL, 9999),
 (41, 'Don Superpimp Davis', '1954-11-03', 16, 10, 2, 1, 7, 12, 12, 7, 9, 'Male', 25, 60, NULL, NULL, 'pimp@internet.com', 'US', '11217', NULL, NULL, 9999),
 (42, 'Amanda Tappingdatass', '1966-06-11', 23, 8, 10, 3, 5, 2, 6, 7, 1, 'Female', 25, 60, NULL, NULL, 'tap@internet.com', 'US', '11217', NULL, NULL, 9999),
 (73, 'sdsdsdsdsd', '1904-03-02', 4, 1, 3, 1, 3, 7, 3, 5, 3, 'Male', 25, 60, NULL, NULL, 'sumit06cs89@gmail.com', 'US', '11111111', '96e79218965eb72c92a549dd5a330112', NULL, 9999),
 (74, '2323232', '1904-02-01', 0, 1, 2, 10, 1, 1, 2, 4, 1, 'Male', 25, 60, NULL, NULL, 'admin@gmail.com', 'US', '3232323', '96e79218965eb72c92a549dd5a330112', NULL, 9999),
 (76, '1111', '1903-05-02', 0, 7, 4, 10, 3, 1, 6, 4, 1, 'Male', 25, 60, NULL, NULL, 'ty@gmail.com', 'US', '23232323', '96e79218965eb72c92a549dd5a330112', NULL, 9999);

create table user_password_resets (
  id            int auto_increment,
  user_id       int not null,
  password      varchar(500) not null,
  created_date  timestamp default current_timestamp,
  is_active     enum('0', '1') default '1',
  constraint user_password_resets_pk
             primary key (id),
  constraint user_password_resets_uid_fk
             foreign key (user_id)
             references users (id) on delete cascade
);

create table user_into_genders (
  user_id        int,
  gender         enum('Male', 'Female', 'Other'),
  constraint user_into_genders_pk
             primary key (user_id, gender),
  constraint user_into_genders_uid_fk
             foreign key (user_id)
             references users (id) on delete cascade
);

insert into user_into_genders (user_id, gender)
select id, 'Male'
  from users
 where gender = 'Female';
insert into user_into_genders (user_id, gender)
select id, 'Female'
  from users
 where gender = 'Male';
insert into user_into_genders (user_id, gender)
select id, 'Other'
  from users
 where id between 1 and 50;
insert into user_into_genders (user_id, gender)
select id, 'Female'
  from users
 where id > 20 and gender = 'Female';
insert into user_into_genders (user_id, gender)
select id, 'Male'
  from users
 where id between 10 and 50 and gender = 'Male';


create table user_images (
  id               int auto_increment,
  user_id          int not null,
  file_name        varchar(255),
  is_default       enum('0', '1') default '0',
  is_hidden        enum('0', '1') default '0',
  sort_order       int,
  created_date     timestamp default current_timestamp,
  constraint user_images_pk
             primary key (id),
  constraint user_images_uid_fk
             foreign key (user_id)
             references users (id) on delete cascade
);

create table user_favorites (
  user_id         int not null,
  fav_user_id     int,
  created_date    timestamp default current_timestamp,
  constraint user_favorites_pk
             primary key (user_id, fav_user_id),
  constraint user_favorites_user_id_fk
             foreign key (user_id)
             references users (id) on delete cascade,
  constraint user_favorites_fuid_fk
             foreign key (fav_user_id)
             references users (id) on delete cascade
);

create table user_visitors (
  user_id           int,
  visitor_user_id   int,
  last_visited_date timestamp default current_timestamp on update current_timestamp,
  constraint user_visitors_pk
             primary key (user_id, visitor_user_id),
  constraint user_visitors_user_id_fk
             foreign key (user_id)
             references users (id) on delete cascade,
  constraint user_visitors_vuid_fk
             foreign key (visitor_user_id)
             references users (id) on delete cascade
);

create table user_blocks (
  user_id         int,
  blocked_user_id int,
  created_date    timestamp default current_timestamp,
  constraint user_blocks_pk
             primary key (user_id, blocked_user_id),
  constraint user_blocks_user_id_fk
             foreign key (user_id)
             references users (id) on delete cascade,
  constraint user_blocks_buid_fk
             foreign key (blocked_user_id)
             references users (id) on delete cascade
);

create table messages (
  id                  int auto_increment,
  thread_id           varchar(50),
  from_user_id        int,
  to_user_id          int,
  message             text,
  created_date        timestamp default current_timestamp,
  is_opened           enum('0', '1') default '0',
  is_hidden           enum('0', '1') default '0'
  constraint messages_pk
             primary key (id),
  constraint messages_from_uid_fk
             foreign key (from_user_id)
             references users (id) on delete set null,
  constraint messages_to_uid_fk
             foreign key (to_user_id)
             references users (id) on delete set null
)character set utf8mb4;


create table user_email_changes (
  id             int auto_increment,
  user_id        int,
  email          varchar(255) not null,
  password       varchar(500) not null,
  created_date   timestamp default current_timestamp,
  is_active      enum('0' ,'1') default '1',
  constraint user_email_changes_pk 
             primary key (id),
  constraint user_email_changes_uid_fk
             foreign key (user_id)
             references users (id) on delete cascade
) character set utf8mb4;

create table user_deletes (
  id             int auto_increment,
  user_id        int,
  password       varchar(500) not null,
  created_date   timestamp default current_timestamp,
  is_active      enum('0' ,'1') default '1',
  constraint user_deletes_pk 
             primary key (id),
  constraint user_deletes_uid_fk
             foreign key (user_id)
             references users (id) on delete cascade
) character set utf8mb4;

create table user_logins (
  id             int auto_increment,
  user_id        int,
  email          varchar(255),
  login_date     timestamp default current_timestamp,
  is_successful  enum('0', '1') default '0',
  constraint user_logins_pk
             primary key (id),
  constraint user_logins_uid_fk
             foreign key (user_id)
             references users (id) on delete cascade
) character set utf8mb4;

create table user_clicks (
  id              int auto_increment,
  user_id         int,
  click_date      timestamp default current_timestamp,
  uri             varchar(255),
  query_string    varchar(255),
  referer         varchar(255),
  constraint user_clicks_pk
             primary key (id),
  constraint user_clicks_uid_fk
             foreign key (user_id)
             references users (id) on delete cascade
) character set utf8mb4;
