<?php

namespace App\Service;

class UrlExtractor
{
	/**
	 * @param string $url
	 * @return array
	 */
	public static function extractId(string $url): string
	{
		$media = array(
			'id' => '',
			'website' => ''
		);

		//DAILYMOTION
		preg_match('#&lt;object[^&gt;]+&gt;.+?http://www.dailymotion.com/swf/video/([A-Za-z0-9]+).+?&lt;/object&gt;#s', $url, $matches);
		if(!isset($matches[1])) {
			preg_match('#http://www.dailymotion.com/video/([A-Za-z0-9]+)#s', $url, $matches);
			if(!isset($matches[1])) {
				preg_match('#http://www.dailymotion.com/embed/video/([A-Za-z0-9]+)#s', $url, $matches);
				if(strlen($matches[1])){ $media_url = 'dailymotion:_:'.$matches[1]; }
			}elseif(strlen($matches[1])){
				$media['id'] = $matches[1];
				$media['website'] = 'dailymotion';
			}
		}elseif(strlen($matches[1])){
			if(strlen($matches[1])){
				$media['id'] = $matches[1];
				$media['website'] = 'dailymotion';
			}
		}

		//YOUTUBE
		if(preg_match('#(?&lt;=(?:v|i)=)[a-zA-Z0-9-]+(?=&amp;)|(?&lt;=(?:v|i)/)[^&amp;n]+|(?&lt;=embed/)[^"&amp;n]+|(?&lt;=(?:v|i)=)[^&amp;n]+|(?&lt;=youtu.be/)[^&amp;n]+#', $url, $videoid)){
			if(strlen($videoid[0])) {
				$media['id'] = $videoid[0];
				$media['website'] = 'youtube';
			}
		}

		return $media;
	}
}