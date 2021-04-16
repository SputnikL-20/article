<?php
/**
 * 
 */

class Constants
{
	const LINK_URL_FIND  = 'https://autotrade.su/moscow/find/';
	const CATALOG		 = './tmp/';
	const ARTICLE 		 = './src/article.json';
	const RECORDERED_ART = './src/recorded_article.json';

	const CONNECT_DB	 = 'sqlite:TestDB.sqlite';
	const PDO_LOGS		 = './logs/PDOErrors.log';

	const PATT_AUTOPART  = '#/moscow/autopart/([a-z0-9-]+?)/([a-z0-9-]+?)">#su';
	const LINK_AUTOPART  = 'https://autotrade.su/moscow/autopart/';
	const PATT_ARTICLE	 = '#<div[^>]*?><b[^>]*?>[Артикул:]+\s*?</b>(.+?)</div>#su';
	const PATT_BRAND	 = '#<div[^>]*?><b[^>]*?>[Бренд:]+\s*?</b>(.+?)</div>#su';
	const PATT_COUNTRY	 = '#<div[^>]*?><b[^>]*?>[Страна:]+\s*?</b>(.+?)</div>#su';
	const PATT_PRICE	 = '#<span[^>]*?>([0-9]+?)</span>#su';
	const PATT_COUNT 	 = '#<i[^>]*?></i>[В\sналичии]+\s([0-9].+?)#su';
	const PATT_ORDER 	 = '#<span[^>]*?>([А-яа-я0-9].+?)</span>#su';
	const PATT_TH		 = '#<th[^>]*>(.+?)</th>#su';
	const PATT_TD 		 = '#<td[^>]*>(.+?)</td>#su';
	const PATT_SRC 		 = '#<img src="https://static.autotrade.su/nomenclature/wm/(.+?)"#is';
	const FIND_IMG 		 = 'https://static.autotrade.su/nomenclature/wm/';
}