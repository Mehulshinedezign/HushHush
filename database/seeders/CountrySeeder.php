<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            ['id' => '1', 'iso_code' => 'AF', 'name' => 'afghanistan'],
            ['id' => '2', 'iso_code' => 'AL', 'name' => 'albania'],
            ['id' => '3', 'iso_code' => 'DZ', 'name' => 'algeria'],
            ['id' => '4', 'iso_code' => 'AS', 'name' => 'american samoa'],
            ['id' => '5', 'iso_code' => 'AD', 'name' => 'andorra'],
            ['id' => '6', 'iso_code' => 'AO', 'name' => 'angola'],
            ['id' => '7', 'iso_code' => 'AI', 'name' => 'anguilla'],
            ['id' => '8', 'iso_code' => 'AQ', 'name' => 'antarctica'],
            ['id' => '9', 'iso_code' => 'AG', 'name' => 'antigua and barbuda'],
            ['id' => '10', 'iso_code' => 'AR', 'name' => 'argentina'],
            ['id' => '11', 'iso_code' => 'AM', 'name' => 'armenia'],
            ['id' => '12', 'iso_code' => 'AW', 'name' => 'aruba'],
            ['id' => '13', 'iso_code' => 'AU', 'name' => 'australia'],
            ['id' => '14', 'iso_code' => 'AT', 'name' => 'austria'],
            ['id' => '15', 'iso_code' => 'AZ', 'name' => 'azerbaijan'],
            ['id' => '16', 'iso_code' => 'BS', 'name' => 'bahamas'],
            ['id' => '17', 'iso_code' => 'BH', 'name' => 'bahrain'],
            ['id' => '18', 'iso_code' => 'BD', 'name' => 'bangladesh'],
            ['id' => '19', 'iso_code' => 'BB', 'name' => 'barbados'],
            ['id' => '20', 'iso_code' => 'BY', 'name' => 'belarus'],
            ['id' => '21', 'iso_code' => 'BE', 'name' => 'belgium'],
            ['id' => '22', 'iso_code' => 'BZ', 'name' => 'belize'],
            ['id' => '23', 'iso_code' => 'BJ', 'name' => 'benin'],
            ['id' => '24', 'iso_code' => 'BM', 'name' => 'bermuda'],
            ['id' => '25', 'iso_code' => 'BT', 'name' => 'bhutan'],
            ['id' => '26', 'iso_code' => 'BO', 'name' => 'bolivia'],
            ['id' => '27', 'iso_code' => 'BA', 'name' => 'bosnia and herzegovina'],
            ['id' => '28', 'iso_code' => 'BW', 'name' => 'botswana'],
            ['id' => '29', 'iso_code' => 'BV', 'name' => 'bouvet island'],
            ['id' => '30', 'iso_code' => 'BR', 'name' => 'brazil'],
            ['id' => '31', 'iso_code' => 'IO', 'name' => 'british indian ocean territory'],
            ['id' => '32', 'iso_code' => 'BN', 'name' => 'brunei darussalam'],
            ['id' => '33', 'iso_code' => 'BG', 'name' => 'bulgaria'],
            ['id' => '34', 'iso_code' => 'BF', 'name' => 'burkina faso'],
            ['id' => '35', 'iso_code' => 'BI', 'name' => 'burundi'],
            ['id' => '36', 'iso_code' => 'KH', 'name' => 'cambodia'],
            ['id' => '37', 'iso_code' => 'CM', 'name' => 'cameroon'],
            ['id' => '38', 'iso_code' => 'CA', 'name' => 'canada'],
            ['id' => '39', 'iso_code' => 'CV', 'name' => 'cape verde'],
            ['id' => '40', 'iso_code' => 'KY', 'name' => 'cayman islands'],
            ['id' => '41', 'iso_code' => 'CF', 'name' => 'central african republic'],
            ['id' => '42', 'iso_code' => 'TD', 'name' => 'chad'],
            ['id' => '43', 'iso_code' => 'CL', 'name' => 'chile'],
            ['id' => '44', 'iso_code' => 'CN', 'name' => 'china'],
            ['id' => '45', 'iso_code' => 'CX', 'name' => 'christmas island'],
            // ['id' => '46', 'iso_code' => 'CC', 'name' => 'cocos (keeling) islands'],
            ['id' => '47', 'iso_code' => 'CO', 'name' => 'colombia'],
            ['id' => '48', 'iso_code' => 'KM', 'name' => 'comoros'],
            ['id' => '49', 'iso_code' => 'CG', 'name' => 'congo'],
            ['id' => '50', 'iso_code' => 'CD', 'name' => 'congo the democratic republic of the'],
            ['id' => '51', 'iso_code' => 'CK', 'name' => 'cook islands'],
            ['id' => '52', 'iso_code' => 'CR', 'name' => 'costa rica'],
            ['id' => '53', 'iso_code' => 'CI', 'name' => 'cote d\'ivoire (ivory coast)'],
            ['id' => '54', 'iso_code' => 'HR', 'name' => 'croatia (hrvatska)'],
            ['id' => '55', 'iso_code' => 'CU', 'name' => 'cuba'],
            ['id' => '56', 'iso_code' => 'CY', 'name' => 'cyprus'],
            ['id' => '57', 'iso_code' => 'CZ', 'name' => 'czech republic'],
            ['id' => '58', 'iso_code' => 'DK', 'name' => 'denmark'],
            ['id' => '59', 'iso_code' => 'DJ', 'name' => 'djibouti'],
            ['id' => '60', 'iso_code' => 'DM', 'name' => 'dominica'],
            ['id' => '61', 'iso_code' => 'DO', 'name' => 'dominican republic'],
            ['id' => '62', 'iso_code' => 'TP', 'name' => 'east timor'],
            ['id' => '63', 'iso_code' => 'EC', 'name' => 'ecuador'],
            ['id' => '64', 'iso_code' => 'EG', 'name' => 'egypt'],
            ['id' => '65', 'iso_code' => 'SV', 'name' => 'el salvador'],
            ['id' => '66', 'iso_code' => 'GQ', 'name' => 'equatorial guinea'],
            ['id' => '67', 'iso_code' => 'ER', 'name' => 'eritrea'],
            ['id' => '68', 'iso_code' => 'EE', 'name' => 'estonia'],
            ['id' => '69', 'iso_code' => 'ET', 'name' => 'ethiopia'],
            ['id' => '70', 'iso_code' => 'CC', 'name' => 'external territories of australia'],
            ['id' => '71', 'iso_code' => 'FK', 'name' => 'falkland islands'],
            ['id' => '72', 'iso_code' => 'FO', 'name' => 'faroe islands'],
            ['id' => '73', 'iso_code' => 'FJ', 'name' => 'fiji islands'],
            ['id' => '74', 'iso_code' => 'FI', 'name' => 'finland'],
            ['id' => '75', 'iso_code' => 'FR', 'name' => 'france'],
            ['id' => '76', 'iso_code' => 'GF', 'name' => 'french guiana'],
            ['id' => '77', 'iso_code' => 'PF', 'name' => 'french polynesia'],
            ['id' => '78', 'iso_code' => 'TF', 'name' => 'french southern territories'],
            ['id' => '79', 'iso_code' => 'GA', 'name' => 'gabon'],
            ['id' => '80', 'iso_code' => 'GM', 'name' => 'gambia'],
            ['id' => '81', 'iso_code' => 'GE', 'name' => 'georgia'],
            ['id' => '82', 'iso_code' => 'DE', 'name' => 'germany'],
            ['id' => '83', 'iso_code' => 'GH', 'name' => 'ghana'],
            ['id' => '84', 'iso_code' => 'GI', 'name' => 'gibraltar'],
            ['id' => '85', 'iso_code' => 'GR', 'name' => 'greece'],
            ['id' => '86', 'iso_code' => 'GL', 'name' => 'greenland'],
            ['id' => '87', 'iso_code' => 'GD', 'name' => 'grenada'],
            ['id' => '88', 'iso_code' => 'GP', 'name' => 'guadeloupe'],
            ['id' => '89', 'iso_code' => 'GU', 'name' => 'guam'],
            ['id' => '90', 'iso_code' => 'GT', 'name' => 'guatemala'],
            ['id' => '91', 'iso_code' => 'GG', 'name' => 'guernsey and alderney'],
            ['id' => '92', 'iso_code' => 'GN', 'name' => 'guinea'],
            ['id' => '93', 'iso_code' => 'GW', 'name' => 'guinea-bissau'],
            ['id' => '94', 'iso_code' => 'GY', 'name' => 'guyana'],
            ['id' => '95', 'iso_code' => 'HT', 'name' => 'haiti'],
            ['id' => '96', 'iso_code' => 'HM', 'name' => 'heard and mcdonald islands'],
            ['id' => '97', 'iso_code' => 'HN', 'name' => 'honduras'],
            ['id' => '98', 'iso_code' => 'HK', 'name' => 'hong kong s.a.r.'],
            ['id' => '99', 'iso_code' => 'HU', 'name' => 'hungary'],
            ['id' => '100', 'iso_code' => 'IS', 'name' => 'iceland'],
            ['id' => '101', 'iso_code' => 'IN', 'name' => 'india'],
            ['id' => '102', 'iso_code' => 'ID', 'name' => 'indonesia'],
            ['id' => '103', 'iso_code' => 'IR', 'name' => 'iran'],
            ['id' => '104', 'iso_code' => 'IQ', 'name' => 'iraq'],
            ['id' => '105', 'iso_code' => 'IE', 'name' => 'ireland'],
            ['id' => '106', 'iso_code' => 'IL', 'name' => 'israel'],
            ['id' => '107', 'iso_code' => 'IT', 'name' => 'italy'],
            ['id' => '108', 'iso_code' => 'JM', 'name' => 'jamaica'],
            ['id' => '109', 'iso_code' => 'JP', 'name' => 'japan'],
            ['id' => '110', 'iso_code' => 'JE', 'name' => 'jersey'],
            ['id' => '111', 'iso_code' => 'JO', 'name' => 'jordan'],
            ['id' => '112', 'iso_code' => 'KZ', 'name' => 'kazakhstan'],
            ['id' => '113', 'iso_code' => 'KE', 'name' => 'kenya'],
            ['id' => '114', 'iso_code' => 'KI', 'name' => 'kiribati'],
            ['id' => '115', 'iso_code' => 'KP', 'name' => 'korea north'],
            ['id' => '116', 'iso_code' => 'KR', 'name' => 'korea south'],
            ['id' => '117', 'iso_code' => 'KG', 'name' => 'kuwait'],
            ['id' => '118', 'iso_code' => 'KG', 'name' => 'kyrgyzstan'],
            ['id' => '119', 'iso_code' => 'LA', 'name' => 'Lao People\'s Democratic Republic'],
            ['id' => '120', 'iso_code' => 'LV', 'name' => 'latvia'],
            ['id' => '121', 'iso_code' => 'LB', 'name' => 'lebanon'],
            ['id' => '122', 'iso_code' => 'LS', 'name' => 'lesotho'],
            ['id' => '123', 'iso_code' => 'LR', 'name' => 'liberia'],
            ['id' => '124', 'iso_code' => 'LY', 'name' => 'libya'],
            ['id' => '125', 'iso_code' => 'LI', 'name' => 'liechtenstein'],
            ['id' => '126', 'iso_code' => 'LT', 'name' => 'lithuania'],
            ['id' => '127', 'iso_code' => 'LU', 'name' => 'luxembourg'],
            ['id' => '128', 'iso_code' => 'MO', 'name' => 'macau s.a.r.'],
            ['id' => '129', 'iso_code' => 'MK', 'name' => 'macedonia'],
            ['id' => '130', 'iso_code' => 'MG', 'name' => 'madagascar'],
            ['id' => '131', 'iso_code' => 'MW', 'name' => 'malawi'],
            ['id' => '132', 'iso_code' => 'MY', 'name' => 'malaysia'],
            ['id' => '133', 'iso_code' => 'MV', 'name' => 'maldives'],
            ['id' => '134', 'iso_code' => 'ML', 'name' => 'mali'],
            ['id' => '135', 'iso_code' => 'MT', 'name' => 'malta'],
            ['id' => '136', 'iso_code' => 'IM', 'name' => 'man (isle of)'],
            ['id' => '137', 'iso_code' => 'MH', 'name' => 'marshall islands'],
            ['id' => '138', 'iso_code' => 'MQ', 'name' => 'martinique'],
            ['id' => '139', 'iso_code' => 'MR', 'name' => 'mauritania'],
            ['id' => '140', 'iso_code' => 'MU', 'name' => 'mauritius'],
            ['id' => '141', 'iso_code' => 'YT', 'name' => 'mayotte'],
            ['id' => '142', 'iso_code' => 'MX', 'name' => 'mexico'],
            ['id' => '143', 'iso_code' => 'FM', 'name' => 'micronesia'],
            ['id' => '144', 'iso_code' => 'MD', 'name' => 'moldova'],
            ['id' => '145', 'iso_code' => 'MC', 'name' => 'monaco'],
            ['id' => '146', 'iso_code' => 'MN', 'name' => 'mongolia'],
            ['id' => '147', 'iso_code' => 'MS', 'name' => 'montserrat'],
            ['id' => '148', 'iso_code' => 'MA', 'name' => 'morocco'],
            ['id' => '149', 'iso_code' => 'MZ', 'name' => 'mozambique'],
            ['id' => '150', 'iso_code' => 'MM', 'name' => 'myanmar'],
            ['id' => '151', 'iso_code' => 'NA', 'name' => 'namibia'],
            ['id' => '152', 'iso_code' => 'NR', 'name' => 'nauru'],
            ['id' => '153', 'iso_code' => 'NP', 'name' => 'nepal'],
            ['id' => '154', 'iso_code' => 'AN', 'name' => 'netherlands antilles'],
            ['id' => '155', 'iso_code' => 'NL', 'name' => 'netherlands the'],
            ['id' => '156', 'iso_code' => 'NC', 'name' => 'new caledonia'],
            ['id' => '157', 'iso_code' => 'NZ', 'name' => 'new zealand'],
            ['id' => '158', 'iso_code' => 'NI', 'name' => 'nicaragua'],
            ['id' => '159', 'iso_code' => 'NE', 'name' => 'niger'],
            ['id' => '160', 'iso_code' => 'NG', 'name' => 'nigeria'],
            ['id' => '161', 'iso_code' => 'NU', 'name' => 'niue'],
            ['id' => '162', 'iso_code' => 'NF', 'name' => 'norfolk island'],
            ['id' => '163', 'iso_code' => 'MP', 'name' => 'northern mariana islands'],
            ['id' => '164', 'iso_code' => 'NO', 'name' => 'norway'],
            ['id' => '165', 'iso_code' => 'OM', 'name' => 'oman'],
            ['id' => '166', 'iso_code' => 'PK', 'name' => 'pakistan'],
            ['id' => '167', 'iso_code' => 'PW', 'name' => 'palau'],
            ['id' => '168', 'iso_code' => 'PS', 'name' => 'palestinian territory occupied'],
            ['id' => '169', 'iso_code' => 'PA', 'name' => 'panama'],
            ['id' => '170', 'iso_code' => 'PG', 'name' => 'papua new guinea'],
            ['id' => '171', 'iso_code' => 'PY', 'name' => 'paraguay'],
            ['id' => '172', 'iso_code' => 'PE', 'name' => 'peru'],
            ['id' => '173', 'iso_code' => 'PH', 'name' => 'philippines'],
            ['id' => '174', 'iso_code' => 'PN', 'name' => 'pitcairn island'],
            ['id' => '175', 'iso_code' => 'PL', 'name' => 'poland'],
            ['id' => '176', 'iso_code' => 'PT', 'name' => 'portugal'],
            ['id' => '177', 'iso_code' => 'PR', 'name' => 'puerto rico'],
            ['id' => '178', 'iso_code' => 'QA', 'name' => 'qatar'],
            ['id' => '179', 'iso_code' => 'RE', 'name' => 'reunion'],
            ['id' => '180', 'iso_code' => 'RO', 'name' => 'romania'],
            ['id' => '181', 'iso_code' => 'RU', 'name' => 'russia'],
            ['id' => '182', 'iso_code' => 'RW', 'name' => 'rwanda'],
            ['id' => '183', 'iso_code' => 'SH', 'name' => 'saint helena'],
            ['id' => '184', 'iso_code' => 'KN', 'name' => 'saint kitts and nevis'],
            ['id' => '185', 'iso_code' => 'LC', 'name' => 'saint lucia'],
            ['id' => '186', 'iso_code' => 'PM', 'name' => 'saint pierre and miquelon'],
            ['id' => '187', 'iso_code' => 'VC', 'name' => 'saint vincent and the grenadines'],
            ['id' => '188', 'iso_code' => 'WS', 'name' => 'samoa'],
            ['id' => '189', 'iso_code' => 'SM', 'name' => 'san marino'],
            ['id' => '190', 'iso_code' => 'ST', 'name' => 'sao tome and principe'],
            ['id' => '191', 'iso_code' => 'SA', 'name' => 'saudi arabia'],
            ['id' => '192', 'iso_code' => 'SN', 'name' => 'senegal'],
            ['id' => '193', 'iso_code' => 'RS', 'name' => 'serbia'],
            ['id' => '194', 'iso_code' => 'SC', 'name' => 'seychelles'],
            ['id' => '195', 'iso_code' => 'SL', 'name' => 'sierra leone'],
            ['id' => '196', 'iso_code' => 'SG', 'name' => 'singapore'],
            ['id' => '197', 'iso_code' => 'SK', 'name' => 'slovakia'],
            ['id' => '198', 'iso_code' => 'SI', 'name' => 'slovenia'],
            // ['id' => '199', 'iso_code' => '', 'name' => 'smaller territories of the uk'],
            ['id' => '200', 'iso_code' => 'SB', 'name' => 'solomon islands'],
            ['id' => '201', 'iso_code' => 'SO', 'name' => 'somalia'],
            ['id' => '202', 'iso_code' => 'ZA', 'name' => 'south africa'],
            ['id' => '203', 'iso_code' => 'GS', 'name' => 'south georgia'],
            // ['id' => '204', 'iso_code' => '', 'name' => 'south sudan'],
            ['id' => '205', 'iso_code' => 'ES', 'name' => 'spain'],
            ['id' => '206', 'iso_code' => 'LK', 'name' => 'sri lanka'],
            ['id' => '207', 'iso_code' => 'SD', 'name' => 'sudan'],
            ['id' => '208', 'iso_code' => 'SR', 'name' => 'suriname'],
            ['id' => '209', 'iso_code' => 'SJ', 'name' => 'svalbard and jan mayen islands'],
            ['id' => '210', 'iso_code' => 'SZ', 'name' => 'swaziland'],
            ['id' => '211', 'iso_code' => 'SE', 'name' => 'sweden'],
            ['id' => '212', 'iso_code' => 'CH', 'name' => 'switzerland'],
            ['id' => '213', 'iso_code' => 'SY', 'name' => 'syria'],
            ['id' => '214', 'iso_code' => 'TW', 'name' => 'taiwan'],
            ['id' => '215', 'iso_code' => 'TJ', 'name' => 'tajikistan'],
            ['id' => '216', 'iso_code' => 'TZ', 'name' => 'tanzania'],
            ['id' => '217', 'iso_code' => 'TH', 'name' => 'thailand'],
            ['id' => '218', 'iso_code' => 'TG', 'name' => 'togo'],
            ['id' => '219', 'iso_code' => 'TK', 'name' => 'tokelau'],
            ['id' => '220', 'iso_code' => 'TO', 'name' => 'tonga'],
            ['id' => '221', 'iso_code' => 'TT', 'name' => 'trinidad and tobago'],
            ['id' => '222', 'iso_code' => 'TN', 'name' => 'tunisia'],
            ['id' => '223', 'iso_code' => 'TR', 'name' => 'turkey'],
            ['id' => '224', 'iso_code' => 'TM', 'name' => 'turkmenistan'],
            ['id' => '225', 'iso_code' => 'TC', 'name' => 'turks and caicos islands'],
            ['id' => '226', 'iso_code' => 'TV', 'name' => 'tuvalu'],
            ['id' => '227', 'iso_code' => 'UG', 'name' => 'uganda'],
            ['id' => '228', 'iso_code' => 'UA', 'name' => 'ukraine'],
            ['id' => '229', 'iso_code' => 'AE', 'name' => 'united arab emirates'],
            ['id' => '230', 'iso_code' => 'GB', 'name' => 'united kingdom'],
            ['id' => '231', 'iso_code' => 'US', 'name' => 'united states'],
            // ['id' => '232', 'iso_code' => '', 'name' => 'united states minor outlying islands'],
            ['id' => '233', 'iso_code' => 'UY', 'name' => 'uruguay'],
            ['id' => '234', 'iso_code' => 'UZ', 'name' => 'uzbekistan'],
            ['id' => '235', 'iso_code' => 'VU', 'name' => 'vanuatu'],
            ['id' => '236', 'iso_code' => 'VA', 'name' => 'vatican city state (holy see)'],
            ['id' => '237', 'iso_code' => 'VE', 'name' => 'venezuela'],
            ['id' => '238', 'iso_code' => 'VN', 'name' => 'vietnam'],
            ['id' => '239', 'iso_code' => 'VG', 'name' => 'virgin islands (british)'],
            ['id' => '240', 'iso_code' => 'VI', 'name' => 'virgin islands (us)'],
            ['id' => '241', 'iso_code' => 'WF', 'name' => 'wallis and futuna islands'],
            ['id' => '242', 'iso_code' => 'EH', 'name' => 'western sahara'],
            ['id' => '243', 'iso_code' => 'YE', 'name' => 'yemen'],
            ['id' => '244', 'iso_code' => 'YU', 'name' => 'yugoslavia'],
            ['id' => '245', 'iso_code' => 'ZM', 'name' => 'zambia'],
            ['id' => '246', 'iso_code' => 'ZW', 'name' => 'zimbabwe']
        ];

        Country::insert($countries);
    }
}
