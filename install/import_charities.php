<?php

	// [!] WARNING: This script shall be run only after deployment of database sofia from ../base/sofia.sql
	echo 'WARNING: This script shall be run only after deployment of database sofia from ../base/sofia.sql' . PHP_EOL;

	$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
	require '../config.php';

	$charity_array = array(
		['code' => 'en-us', 'country' => 'United States', 'type' => 'Red Cross', 'http_link' => 'http://redcross.org/']
		, ['code' => 'en-gb', 'country' => 'United Kingdom', 'type' => 'Red Cross', 'http_link' => 'http://redcross.org.uk/']
		, ['code' => 'ru-ru', 'country' => 'Russia', 'type' => 'Charity', 'http_link' => 'http://www.evansnyc.com/charity/']
		, ['code' => 'uk-ua', 'country' => 'Ukraine', 'type' => 'Charity', 'http_link' => 'https://goo.gl/niAr27']
		, ['code' => 'zh-cn', 'country' => 'China', 'type' => 'Red Cross', 'http_link' => 'http://www.redcross.org.cn/']
		, ['code' => 'es-es', 'country' => 'Spain', 'type' => 'Red Cross', 'http_link' => 'http://www.cruzroja.es/']
		, ['code' => 'ja-jp', 'country' => 'Japan', 'type' => 'Red Cross', 'http_link' => 'http://www.jrc.or.jp/']
		, ['code' => 'ar-eg', 'country' => 'Egypt', 'type' => 'Red Crescent', 'http_link' => 'http://egyptianrc.org/']
		, ['code' => 'pt-br', 'country' => 'Brazil', 'type' => 'Charity', 'http_link' => 'https://goo.gl/cX7xcr']
		, ['code' => 'fl-ph', 'country' => 'Philippines', 'type' => 'Red Cross', 'http_link' => 'http://redcross.org.ph/']
		, ['code' => 'en-ng', 'country' => 'Nigeria', 'type' => 'Red Cross', 'http_link' => 'http://redcrossnigeria.org/']
		, ['code' => 'es-mx', 'country' => 'Mexico', 'type' => 'Red Cross', 'http_link' => 'http://cruzrojamexicana.org.mx/']
		, ['code' => 'am-et', 'country' => 'Ethiopia', 'type' => 'Red Cross', 'http_link' => 'http://redcrosseth.org/']
		, ['code' => 'de-de', 'country' => 'Germany', 'type' => 'Red Cross', 'http_link' => 'http://www.drk.de/']
		, ['code' => 'es-co', 'country' => 'Colombia', 'type' => 'Red Cross', 'http_link' => 'http://www.cruzrojacolombiana.org/']
		, ['code' => 'fr-fr', 'country' => 'France', 'type' => 'Red Cross', 'http_link' => 'http://www.croix-rouge.fr/']
		, ['code' => 'en-ke', 'country' => 'Kenya', 'type' => 'Red Cross', 'http_link' => 'http://www.kenyaredcross.org/']
		, ['code' => 'sv-se', 'country' => 'Sweden', 'type' => 'Red Cross', 'http_link' => 'http://www.redcross.se/']
		, ['code' => 'es-pe', 'country' => 'Peru', 'type' => 'Red Cross', 'http_link' => 'http://www.cruzroja.org.pe/']
		, ['code' => 'id-id', 'country' => 'Indonesia', 'type' => 'Red Cross', 'http_link' => 'http://www.pmi.or.id/']
		, ['code' => 'en-ca', 'country' => 'Canada', 'type' => 'Red Cross', 'http_link' => 'http://www.redcross.ca/donate']
		, ['code' => 'en-gh', 'country' => 'Ghana', 'type' => 'Red Cross', 'http_link' => 'http://www.redcrossghana.org/']
		, ['code' => 'en-tz', 'country' => 'Tanzania', 'type' => 'Red Cross', 'http_link' => 'http://www.trcs.or.tz/']
		, ['code' => 'es-ec', 'country' => 'Ecuador', 'type' => 'Red Cross', 'http_link' => 'http://www.cruzroja.org.ec/']
		, ['code' => 'es-gt', 'country' => 'Guatemala', 'type' => 'Red Cross', 'http_link' => 'http://www.cruzroja.gt/']
		, ['code' => 'en-au', 'country' => 'Australia', 'type' => 'Red Cross', 'http_link' => 'http://www.redcross.org.au/donate.aspx']
		, ['code' => 'az-az', 'country' => 'Azerbaijan', 'type' => 'Red Crescent', 'http_link' => 'http://www.redcrescent.az/']
		, ['code' => 'en-bs', 'country' => 'Bahamas', 'type' => 'Red Cross', 'http_link' => 'www.bahamasredcross.com ']
		, ['code' => 'en-bz', 'country' => 'Belize', 'type' => 'Charity', 'http_link' => 'https://goo.gl/ILXLDt ']
		, ['code' => 'fr-cm', 'country' => 'Cameroon', 'type' => 'Charity', 'http_link' => 'http://www.idealist.org/view/org/8WbKBhBbx874/']
		, ['code' => 'pt-mz', 'country' => 'Mozambique', 'type' => 'Charity', 'http_link' => 'https://goo.gl/SqMbSU ']
		, ['code' => 'el-gr', 'country' => 'Greece', 'type' => 'Red Cross', 'http_link' => 'http://www.redcross.gr/']
		, ['code' => 'es-cl', 'country' => 'Chile', 'type' => 'Red Cross', 'http_link' => 'http://www.cruzroja.cl/']
		, ['code' => 'en-pg', 'country' => 'Papua New Guinea', 'type' => 'Charity', 'http_link' => 'https://goo.gl/Gx0a0n ']
		, ['code' => 'es-py', 'country' => 'Paraguay', 'type' => 'Red Cross', 'http_link' => 'http://www.cruzroja.org.py/']
		, ['code' => 'en-sd', 'country' => 'South Sudan', 'type' => 'Red Cross', 'http_link' => 'http://southsudanredcross.org/']
		, ['code' => 'es-ar', 'country' => 'Argentina', 'type' => 'Red Cross', 'http_link' => 'http://www.cruzroja.org.ar/']
		, ['code' => 'ro-ro', 'country' => 'Romania', 'type' => 'Red Cross', 'http_link' => 'http://www.crucearosie.ro/']
		, ['code' => 'ko-kr', 'country' => 'South Korea', 'type' => 'Red Cross', 'http_link' => 'https://www.redcross.or.kr/eng/eng_main/main.do']
		, ['code' => 'bg-bg', 'country' => 'Bulgaria', 'type' => 'Red Cross', 'http_link' => 'http://en.redcross.bg/']
		, ['code' => 'cr-ht', 'country' => 'Haiti', 'type' => 'Red Cross', 'http_link' => 'http://www.croixrouge.ht/']
		, ['code' => 'nl-nl', 'country' => 'Netherlands', 'type' => 'Red Cross', 'http_link' => 'http://www.rodekruis.nl/']
		, ['code' => 'vi-vn', 'country' => 'Vietnam', 'type' => 'Red Cross', 'http_link' => 'http://redcross.org.vn/']
		, ['code' => 'hu-hu', 'country' => 'Hungary', 'type' => 'Red Cross', 'http_link' => 'http://www.voroskereszt.hu/']
		, ['code' => 'fr-cd', 'country' => 'Congo', 'type' => 'Charity', 'http_link' => 'http://congohelpinghands.org/']
		, ['code' => 'en-za', 'country' => 'South Africa', 'type' => 'Charity', 'http_link' => 'http://yfc.org.za/']
		, ['code' => 'es-ve', 'country' => 'Venezuela', 'type' => 'Red Cross', 'http_link' => 'http://www.cruzrojavenezolana.org/']
		, ['code' => 'pt-zm', 'country' => 'Zambia', 'type' => 'Charity', 'http_link' => 'https://goo.gl/L6mt1K ']
		, ['code' => 'en-mw', 'country' => 'Malawi', 'type' => 'Charity', 'http_link' => 'https://goo.gl/ouh5qK ']
		, ['code' => 'en-zw', 'country' => 'Zimbabwe', 'type' => 'Charity', 'http_link' => 'https://goo.gl/bmDftq ']
		, ['code' => 'es-do', 'country' => 'Dominican Republic', 'type' => 'Charity', 'http_link' => 'https://goo.gl/vzjjpR ']
		, ['code' => 'es-bo', 'country' => 'Bolivia', 'type' => 'Charity', 'http_link' => 'http://www.ayuda.org/']
		, ['code' => 'en-rw', 'country' => 'Rwanda', 'type' => 'Red Cross', 'http_link' => 'http://www.rwandaredcross.org/']
		, ['code' => 'fr-mg', 'country' => 'Madagascar', 'type' => 'Charity', 'http_link' => 'http://helpmg.org/']
		, ['code' => 'fr-bi', 'country' => 'Burundi', 'type' => 'Red Cross', 'http_link' => 'http://www.croixrougeburundi.org/']
		, ['code' => 'ro-md', 'country' => 'Moldova', 'type' => 'Red Cross', 'http_link' => 'http://redcross.md/']
		, ['code' => 'en-in', 'country' => 'India', 'type' => 'Red Cross', 'http_link' => 'http://indianredcross.org/']
		, ['code' => 'fr-ci', 'country' => 'Ivory Coast', 'type' => 'Charity', 'http_link' => 'http://www.icmrt.org']
		, ['code' => 'nl-be', 'country' => 'Belgium', 'type' => 'Red Cross', 'http_link' => 'http://www.redcross.be/']
		, ['code' => 'es-cu', 'country' => 'Cuba', 'type' => 'Red Cross', 'http_link' => 'http://www.sld.cu/sitios/cruzroja']
		, ['code' => 'es-hn', 'country' => 'Honduras', 'type' => 'Red Cross', 'http_link' => 'http://www.cruzroja.org.hn/']
		, ['code' => 'de-at', 'country' => 'Austria', 'type' => 'Charity', 'http_link' => 'http://www.charity-charities.org/Austria-charities/Vienna.html']
		, ['code' => 'fr-ch', 'country' => 'Switzerland', 'type' => 'Red Cross', 'http_link' => 'http://www.redcross.ch/']
		, ['code' => 'ru-by', 'country' => 'Belarus', 'type' => 'Red Cross', 'http_link' => 'http://redcross.by/']
		, ['code' => 'es-ni', 'country' => 'Nicaragua', 'type' => 'Red Cross', 'http_link' => 'http://cruzrojanicaraguense.org/']
		, ['code' => 'es-sv', 'country' => 'El Salvador', 'type' => 'Red Cross', 'http_link' => 'http://www.cruzrojasal.org.sv/']
		, ['code' => 'da-dk', 'country' => 'Denmark', 'type' => 'Red Cross', 'http_link' => 'http://www.rodekors.dk/']
		, ['code' => 'it-it', 'country' => 'Italy', 'type' => 'Red Cross', 'http_link' => 'http://cri.it/']
		, ['code' => 'pt-ao', 'country' => 'Angola', 'type' => 'Red Cross', 'http_link' => 'http://www.cruzvermelha.og.ao/']
		, ['code' => 'pt-pt', 'country' => 'Portugal', 'type' => 'Red Cross', 'http_link' => 'http://www.cruzvermelha.pt/']
		, ['code' => 'fr-td', 'country' => 'Chad', 'type' => 'Charity', 'http_link' => 'http://www.chadnow.com/chad_links/chad_aid_links.php']
		, ['code' => 'ru-kz', 'country' => 'Kazakhstan', 'type' => 'Red Crescent', 'http_link' => 'http://www.redcrescent.kz/']
		, ['code' => 'fi-fi', 'country' => 'Finland', 'type' => 'Red Cross', 'http_link' => 'http://www.redcross.fi/']
		, ['code' => 'hr-hr', 'country' => 'Croatia', 'type' => 'Red Cross', 'http_link' => 'http://www.hck.hr/']
		, ['code' => 'en-ie', 'country' => 'Ireland', 'type' => 'Red Cross', 'http_link' => 'http://www.redcross.ie/']
		, ['code' => 'fr-bj', 'country' => 'Benin', 'type' => 'Red Cross', 'http_link' => 'http://croixrougebenin.afredis.com/']
		, ['code' => 'ru-ge', 'country' => 'Georgia', 'type' => 'Red Cross', 'http_link' => 'http://redcross.ge/']
		, ['code' => 'es-cr', 'country' => 'Costa Rica', 'type' => 'Red Cross', 'http_link' => 'http://www.cruzroja.or.cr/']
		, ['code' => 'es-pr', 'country' => 'Puerto Rico', 'type' => 'Charity', 'http_link' => 'http://www.charity-charities.org/PuertoRico-charities/PuertoRico.html']
		, ['code' => 'no-no', 'country' => 'Norway', 'type' => 'Red Cross', 'http_link' => 'http://www.rodekors.no/']
		, ['code' => 'en-ug', 'country' => 'Uganda', 'type' => 'Red Cross', 'http_link' => 'http://www.redcrossug.org/']
		, ['code' => 'hb-il', 'country' => 'Israel', 'type' => 'Charity', 'http_link' => 'http://www.israelgives.org/']
		, ['code' => 'en-nz', 'country' => 'New Zealand', 'type' => 'Red Cross', 'http_link' => 'http://www.redcross.org.nz/']
		, ['code' => 'fr-bf', 'country' => 'Burkina Faso', 'type' => 'Red Cross', 'http_link' => 'http://www.croixrougebf.org/']
		, ['code' => 'es-pa', 'country' => 'Panama', 'type' => 'Red Cross', 'http_link' => 'http://www.cruzroja.org.pa/']
		, ['code' => 'ru-am', 'country' => 'Armenia', 'type' => 'Red Cross', 'http_link' => 'http://redcross.am/']
		, ['code' => 'en-my', 'country' => 'Malaysia', 'type' => 'Charity', 'http_link' => 'http://www.malaysiancare.org/']
		, ['code' => 'ar-ae', 'country' => 'United Arab Emirates', 'type' => 'Red Crescent', 'http_link' => 'http://www.rcuae.ae/']
		, ['code' => 'ru-uz', 'country' => 'Uzbekistan', 'type' => 'Red Crescent', 'http_link' => 'http://redcrescent.uz/']
		, ['code' => 'en-gy', 'country' => 'Guyana', 'type' => 'Red Cross', 'http_link' => 'http://guyanaredcross.org.gy/']
		, ['code' => 'es-uy', 'country' => 'Uruguay', 'type' => 'Red Cross', 'http_link' => 'http://www.cruzrojauruguaya.org/']
		, ['code' => 'en-tt', 'country' => 'Trinidad and Tobago', 'type' => 'Charity', 'http_link' => 'https://goo.gl/l57Ue1 ']
		, ['code' => 'en-ph', 'country' => 'Philippines', 'type' => 'Red Cross', 'http_link' => 'http://redcross.org.ph/']
		, ['code' => 'fr-ht', 'country' => 'Haiti', 'type' => 'Red Cross', 'http_link' => 'http://www.croixrouge.ht/']
		, ['code' => 'lv-lv', 'country' => 'Latvia', 'type' => 'Red Cross', 'http_link' => 'http://www.redcross.lv/en/']
		, ['code' => 'my-mm', 'country' => 'Myanmar', 'type' => 'Red Cross', 'http_link' => 'http://www.redcross.org.mm/']
		, ['code' => 'ur-pk', 'country' => 'Pakistan', 'type' => 'Red Crescent', 'http_link' => 'http://www.prcs.org.pk/']
		, ['code' => 'fr-cf', 'country' => 'Central African Republic', 'type' => 'Charity', 'http_link' => 'https://goo.gl/HucaYG']
		, ['code' => 'en-na', 'country' => 'Namibia', 'type' => 'Red Cross', 'http_link' => 'http://www.redcross.org.na']
		, ['code' => 'fr-tg', 'country' => 'Togo', 'type' => 'Red Cross', 'http_link' => 'http://www.croixrouge-togo.org/']
		, ['code' => 'en-ls', 'country' => 'Lesotho', 'type' => 'Red Cross', 'http_link' => 'http://www.redcross.org.ls/']
		, ['code' => 'en-jm', 'country' => 'Jamaica', 'type' => 'Red Cross', 'http_link' => 'http://jamaicaredcross.org/']
		, ['code' => 'hr-ba', 'country' => 'Bosnia and Herzegovina', 'type' => 'Red Cross', 'http_link' => 'http://www.rcsbh.org/']
		, ['code' => 'ar-lb', 'country' => 'Lebanon', 'type' => 'Charity', 'http_link' => 'http://www.lccm.us/']
		, ['code' => 'en-lk', 'country' => 'Sri Lanka', 'type' => 'Red Cross', 'http_link' => 'http://www.redcross.lk/']
		, ['code' => 'ar-sa', 'country' => 'Saudia Arabia', 'type' => 'Red Crescent', 'http_link' => 'http://saudiredcrescent.com/']
		, ['code' => 'en-bw', 'country' => 'Botswana', 'type' => 'Red Cross', 'http_link' => 'http://www.botswanaredcross.org.bw/']
		, ['code' => 'en-lr', 'country' => 'Liberia', 'type' => 'Charity', 'http_link' => 'http://liftingliberia.org/']
		, ['code' => 'pt-tl', 'country' => 'East Timor', 'type' => 'Red Cross', 'http_link' => 'http://www.redcross.tl/']
		, ['code' => 'fr-ga', 'country' => 'Gabon', 'type' => 'Red Cross', 'http_link' => 'http://croixrouge-gabon.org/']
		, ['code' => 'fr-gn', 'country' => 'Guinea', 'type' => 'Charity', 'http_link' => 'https://goo.gl/4CQyx8']
		, ['code' => 'en-sz', 'country' => 'Swaziland', 'type' => 'Red Cross', 'http_link' => 'https://goo.gl/6QVFBL ']
		, ['code' => 'ar-iq', 'country' => 'Iraq', 'type' => 'Red Crescent', 'http_link' => 'http://www.ircs.org.iq/']
		, ['code' => 'en-sg', 'country' => 'Singapore', 'type' => 'Red Cross', 'http_link' => 'https://www.redcross.sg/']
		, ['code' => 'el-cy', 'country' => 'Cyprus', 'type' => 'Red Cross', 'http_link' => 'http://www.redcross.org.cy/en/home']
		, ['code' => 'cn-hk', 'country' => 'Hong Kong', 'type' => 'Red Cross', 'http_link' => 'http://redcross.org.hk/']
		, ['code' => 'th-th', 'country' => 'Thailand', 'type' => 'Red Cross', 'http_link' => 'https://english.redcross.or.th/']
		, ['code' => 'fr-gq', 'country' => 'Equatorial Guinea', 'type' => 'Charity', 'http_link' => 'https://goo.gl/ZGXcEv ']
		, ['code' => 'en-sl', 'country' => 'Sierra Leone', 'type' => 'Red Cross', 'http_link' => 'http://www.sierraleoneredcross.net/']
		, ['code' => 'sq-al', 'country' => 'Albania', 'type' => 'Red Cross', 'http_link' => 'http://www.kksh.org.al/']
		, ['code' => 'fr-sn', 'country' => 'Senegal', 'type' => 'Red Cross', 'http_link' => 'http://www.croixrougesenegal.com/']
		, ['code' => 'en-fj', 'country' => 'Fiji', 'type' => 'Red Cross', 'http_link' => 'http://www.redcross.com.fj/']
		, ['code' => 'ar-sd', 'country' => 'Sudan', 'type' => 'Red Crescent', 'http_link' => 'http://srcs.sd/']
		, ['code' => 'sq-cs', 'country' => 'Montenegro', 'type' => 'Red Cross', 'http_link' => 'http://www.ckcg.co.me/']
		, ['code' => 'hr-me', 'country' => 'Montenegro', 'type' => 'Red Cross', 'http_link' => 'http://www.ckcg.co.me/']
		, ['code' => 'pt-cv', 'country' => 'Cape Verde', 'type' => 'Charity', 'http_link' => 'https://goo.gl/5DbtM6']
		, ['code' => 'ar-kw', 'country' => 'Kuwait', 'type' => 'Red Crescent', 'http_link' => 'http://krcs.org.kw/']
		, ['code' => 'en-bd', 'country' => 'Bangladesh', 'type' => 'Red Crescent', 'http_link' => 'http://www.bdrcs.org/']
		, ['code' => 'en-mu', 'country' => 'Mauritius', 'type' => 'Red Cross', 'http_link' => 'http://www.mauritiusredcross.org/']
		, ['code' => 'kp-ko', 'country' => 'North Korea', 'type' => 'Charity', 'http_link' => 'http://www.helpinghandskorea.org/']
		, ['code' => 'en-mt', 'country' => 'Malta', 'type' => 'Red Cross', 'http_link' => 'http://www.redcross.org.mt/']
		, ['code' => 'ar-jo', 'country' => 'Jordan', 'type' => 'Red Crescent', 'http_link' => 'http://www.jnrcs.org/']
		, ['code' => 'ar-dz', 'country' => 'Algeria', 'type' => 'Red Crescent', 'http_link' => 'http://www.cra-algerie.org/']
		, ['code' => 'fa-ir', 'country' => 'Iran', 'type' => 'Red Crescent', 'http_link' => 'http://www.rcs.ir/']
		, ['code' => 'de-lu', 'country' => 'Luxembourg', 'type' => 'Red Cross', 'http_link' => 'http://www.croix-rouge.lu/en/']
		, ['code' => 'fr-ml', 'country' => 'Mali', 'type' => 'Red Cross', 'http_link' => 'http://www.croixrouge-mali.org/']
		, ['code' => 'fr-ma', 'country' => 'Morocco', 'type' => 'Red Crescent', 'http_link' => 'http://www.croissant-rouge.ma/']
		, ['code' => 'ru-ee', 'country' => 'Estonia', 'type' => 'Red Cross', 'http_link' => 'http://www.redcross.ee/']
		, ['code' => 'en-ag', 'country' => 'Bahamas', 'type' => 'Red Cross', 'http_link' => 'http://www.bahamasredcross.com/']
		, ['code' => 'np', 'country' => 'Nepal', 'type' => 'Red Cross', 'http_link' => 'http://www.nrcs.org/']
		, ['code' => 'ar-qa', 'country' => 'Qatar', 'type' => 'Red Crescent', 'http_link' => 'http://www.qrcs.org.qa/']
		, ['code' => 'en-sr', 'country' => 'Suriname', 'type' => 'Charity', 'http_link' => 'https://goo.gl/BPsz9U']
		, ['code' => 'en-bb', 'country' => 'Barbados', 'type' => 'Charity', 'http_link' => 'http://www.barbadosyp.com/Barbados/Charitable-Organisations']
		, ['code' => 'ar-bh', 'country' => 'Bahrain', 'type' => 'Red Crescent', 'http_link' => 'http://www.rcsbahrain.org/']
		, ['code' => 'ar-om', 'country' => 'Oman', 'type' => 'Charity', 'http_link' => 'https://goo.gl/P9hqeW']
		, ['code' => 'ar-ly', 'country' => 'Libya', 'type' => 'Charity', 'http_link' => 'https://www.libyahumanaid.org/']
		, ['code' => 'pt-gw', 'country' => 'Guinea-Bissau', 'type' => 'Charity', 'http_link' => 'https://goo.gl/JrWyk6']
		, ['code' => 'kh-kh', 'country' => 'Cambodia', 'type' => 'Red Cross', 'http_link' => 'http://www.redcross.org.kh/index.php?lang=en']
		, ['code' => 'tr-tr', 'country' => 'Turkey', 'type' => 'Red Crescent', 'http_link' => 'http://www.kizilay.org.tr/']
		, ['code' => 'tg-tj', 'country' => 'Tajikistan', 'type' => 'Red Crescent', 'http_link' => 'http://www.redcrescent.tj/']
		, ['code' => 'сn-tw', 'country' => 'Taiwan', 'type' => 'Red Cross', 'http_link' => 'http://www.redcross.org.tw/english/']
		, ['code' => 'en-fm', 'country' => 'Micronesia, Federated States of', 'type' => 'Charity', 'http_link' => 'http://www.habele.org/']
		, ['code' => 'en-gd', 'country' => 'Grenada', 'type' => 'Red Cross', 'http_link' => 'http://www.grenadaredcross.org/']
		, ['code' => 'nl-aw', 'country' => 'Aruba', 'type' => 'Red Cross', 'http_link' => 'http://redcrossaruba.com/']
		, ['code' => 'fr-ne', 'country' => 'Niger', 'type' => 'Charity', 'http_link' => 'https://goo.gl/QNi7hC']
		, ['code' => 'en-to', 'country' => 'Tonga', 'type' => 'Red Cross', 'http_link' => 'http://www.tongaredcross.com/']
		, ['code' => 'en-sc', 'country' => 'Seychelles', 'type' => 'Red Cross', 'http_link' => 'http://www.seychellesredcross.sc/']
		, ['code' => 'en-gm', 'country' => 'Gambia', 'type' => 'Red Cross', 'http_link' => 'http://www.redcross.gm/']
		, ['code' => 'es-ad', 'country' => 'Andorra', 'type' => 'Red Cross', 'http_link' => 'http://www.creuroja.ad/']
		, ['code' => 'en-as', 'country' => 'American Samoa', 'type' => 'Red Cross', 'http_link' => 'https://redcross.wordpress.com/tag/american-samoa/']
		, ['code' => 'en-ag', 'country' => 'Antigua and Barbuda', 'type' => 'Red Cross', 'http_link' => 'https://goo.gl/eeNFnN']
		, ['code' => 'en-dm', 'country' => 'Dominica', 'type' => 'Charity', 'http_link' => 'https://goo.gl/yJBRvc']
		, ['code' => 'da-gl', 'country' => 'Greenland', 'type' => 'Charity', 'http_link' => 'https://goo.gl/J5pnNU']
		, ['code' => 'ar-dj', 'country' => 'Djibouti', 'type' => 'Red Crescent', 'http_link' => 'http://www.redcrescent-dj.org/']
		, ['code' => 'en-bn', 'country' => 'Brunei', 'type' => 'Charity', 'http_link' => 'https://goo.gl/P7ccRU']
		, ['code' => 'en-bm', 'country' => 'Bermuda', 'type' => 'Red Cross', 'http_link' => 'http://www.bermudaredcross.com/']
		, ['code' => 'en-ky', 'country' => 'Cayman Islands', 'type' => 'Red Cross', 'http_link' => 'http://www.redcross.org.ky/']
		, ['code' => 'da-fo', 'country' => 'Faroe Islands', 'type' => 'Charity is not found', 'http_link' => '#']
		, ['code' => 'de-li', 'country' => 'Liechtenstein', 'type' => 'Red Cross', 'http_link' => 'http://www.roteskreuz.li/']
		, ['code' => 'fr-md', 'country' => 'Monaco', 'type' => 'Red Cross', 'http_link' => 'http://www.croix-rouge.mc/']
		, ['code' => 'ar-tn', 'country' => 'Tunisia', 'type' => 'Charity', 'http_link' => 'https://goo.gl/MMS1Fd ']
		, ['code' => 'ar-ye', 'country' => 'Yemen', 'type' => 'Charity', 'http_link' => 'http://www.zakat.org/country/yemen/']
		, ['code' => 'en-ck', 'country' => 'Cook Islands', 'type' => 'Charity', 'http_link' => 'http://cookfoundation.org/']
		, ['code' => 'en-pw', 'country' => 'Palau', 'type' => 'Charity', 'http_link' => 'https://goo.gl/Id7lKD']
		, ['code' => 'en-an', 'country' => 'Anguilla', 'type' => 'Charity is not found', 'http_link' => '#']
		, ['code' => 'fr-km', 'country' => 'Comoros', 'type' => 'Charity', 'http_link' => 'http://www.comoroscharity.org/']
		, ['code' => 'ar-mr', 'country' => 'Mauritania', 'type' => 'Charity', 'http_link' => 'https://goo.gl/XK81gO']
		, ['code' => 'ar-so', 'country' => 'Somalia', 'type' => 'Charity', 'http_link' => 'https://goo.gl/1Bn35T']
		, ['code' => 'it-vt', 'country' => 'Vatican City', 'type' => 'Charity', 'http_link' => 'http://www.catholicparents.org/']
	);

	foreach ($charity_array as $item) 
	{
		if (strpos($item['code'], '-') !== FALSE)
		{
			$array =  explode('-', $item['code']);
			$country_code = $array[1];
			$language_code = $array[0];
		}
		else
		{
			$country_code = $item['code'];
			$language_code = '';
			echo 'Strange country code: `' . $country_code . '`.' . PHP_EOL;
		}

		try
		{
			$html_page = file($item['http_link']);
			//$html_page = file_get_contents($item['http_link']);
		}
		catch (Exception $e)
		{

		}
		$name = '';
		
		foreach ($html_page as $line) 
		{
			if (stripos($line, '<title>'))
			{
				$name = strip_tags($line);
				break;
			}
		}

/*		$matches = [];
		$pattern = '(?<=\<title\>).*?(?=\<\/title\>)';
		preg_match($pattern, $html_page, $matches);
		$name = $matches[0];
*/
		//$name = preg_replace('/<title[^>]*
		//? >([\\s\\S]*?)<\/title>/', '\\1', $html_page);

		while(strpos($name, '  '))
			$name = str_replace('  ', ' ', $name);
		$name = str_replace('\r\n', '', $name);
		$name = str_replace('\n', '', $name);
		$name = str_replace('\t', '', $name);
		$name = str_replace(chr(0x09), '', $name);
		$name = trim($name);

		$query = 'insert into charity_organizations
			(country_code, english_name, native_name, http_link, charity_organization_type_id) '
			. 'values (:code, ';
		if ($language_code === 'en')
			$query .= ":name, null, ";
		else
			$query .= "null, :name, ";
		echo  $language_code . '-' . $country_code . '. ' . json_encode($item) . "\$name = '$name', " . PHP_EOL;
		$query .= ":http_link, (select id from charity_organization_types where name = :type));";

		$insert_statement = $links['sofia']['pdo'] -> prepare($query);
		$result = $insert_statement -> execute(['name' => $name, 'http_link' => $item['http_link'], 'type' => $item['type'], 'code' => $country_code]);

		if(!$result)
		{
			echo __FILE__ . ':' . __LINE__ . ' Insert PDO query exception. Info = {' . $insert_statement -> errorInfo() . '}' . PHP_EOL;
		}
		
	}

	echo 'End of Script.' . PHP_EOL;

?>