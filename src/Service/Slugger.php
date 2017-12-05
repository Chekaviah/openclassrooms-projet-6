<?php

namespace App\Service;

class Slugger
{
	/**
	 * @param string $text
	 * @return string
	 */
	public static function slugify(string $text): string
	{
		setlocale(LC_CTYPE, 'en-US');
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		$text = preg_replace('~[^-\w]+~', '', $text);
		$text = trim($text, '-');
		$text = preg_replace('~-+~', '-', $text);
		$text = strtolower($text);
		if (empty($text))
			return 'n-a';

		return $text;
	}
}