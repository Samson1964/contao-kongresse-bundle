<?php

declare(strict_types=1);

/*
 * This file is part of schachbulle/contao-kongresse-bundle.
 *
 * (c) Frank Hoppe
 *
 * @license LGPL-3.0-or-later
 */

namespace Schachbulle\ContaoKongresseBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Lädt die Service-Konfiguration des Bundles in den Symfony-Container.
 */
class ContaoKongresseExtension extends Extension
{
	/**
	 * Bindet Resources/config/services.yaml in den Container ein.
	 *
	 * @param array            $mergedConfig Die zusammengeführte Bundle-Konfiguration, hier ungenutzt
	 * @param ContainerBuilder $container    Der Container, in den die Services geschrieben werden
	 */
	public function load(array $mergedConfig, ContainerBuilder $container): void
	{
		$loader = new YamlFileLoader(
			$container,
			new FileLocator(__DIR__ . '/../Resources/config')
		);

		$loader->load('services.yaml');
	}
}
