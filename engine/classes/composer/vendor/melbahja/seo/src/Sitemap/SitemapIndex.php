<?php
namespace Melbahja\Seo\Sitemap;

use SimpleXMLElement;
use Melbahja\Seo\Exceptions\SitemapException;

/**
 * @package Melbahja\Seo
 * @since v2.0
 * @see https://git.io/phpseo
 * @license MIT
 * @copyright 2019-present Mohamed Elabhja
 */
class SitemapIndex
{

	/**
	 * Build it
	 *
	 * @param  string $name
	 * @param  string $path
	 * @param  string $url
	 * @param  array  &$maps
	 * @return bool
	 */
	public static function build(string $index, string $path, string $url, array &$maps): bool
	{
		if (is_writable($path = (($path[-1] !== '/') ? "{$path}/" : $path)) === false) {

			throw new SitemapException("The path {$path} is not writable");
		
		} elseif ($url[-1] !== '/') {

			$url .= '/';
		}

		$dom = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"/>');

		foreach ($maps as $name => $file)
		{
			if (rename($file, ($dest = $path . $name)) === false) {
				throw new SitemapException("Moving the file {$dest} failed!");
			}

			$sitemap = $dom->addChild('sitemap');
			$sitemap->addChild('loc', $url . $name);
			$sitemap->addChild('lastmod', date('c'));
		}

		return $dom->asXML($path . $index);
	} 
}
