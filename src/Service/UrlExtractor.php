<?php

namespace App\Service;

/**
 * Class UrlExtractor
 *
 * @author Mathieu GUILLEMINOT <guilleminotm@gmail.com>
 */
class UrlExtractor
{
	/**
	 * @param string $url
     *
	 * @return array
	 */
	public static function extractId(string $url): array
	{
		//DAILYMOTION
		if(preg_match('/dailymotion/', $url)) {
			return [
				'id' => strtok(basename($url), '_'),
				'website' => 'dailymotion'
			];
		}

		//YOUTUBE
		if (preg_match('%(?:youtube(?:-nocookie)?\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})%i', $url, $match)) {
			return [
				'id' => $match[1],
				'website' => 'youtube'
			];
		}

		return [];
	}
}
