<?php

declare(strict_types=1);

/*
 * This file is part of schachbulle/contao-kongresse-bundle.
 *
 * (c) Frank Hoppe
 *
 * @license LGPL-3.0-or-later
 */

namespace Schachbulle\ContaoKongresseBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Schachbulle\ContaoKongresseBundle\ContaoKongresseBundle;

/**
 * Registriert das Bundle im Contao Manager.
 */
class Plugin implements BundlePluginInterface
{
	/**
	 * Meldet das Bundle beim Kernel an.
	 *
	 * Es wird nach dem Contao-Core geladen, damit die DCA-Dateien dieses
	 * Bundles die Definitionen des Cores erweitern können.
	 *
	 * @param ParserInterface $parser Parser des Contao Managers für Bundle-Konfigurationen
	 *
	 * @return array<BundleConfig> Die Konfiguration dieses einen Bundles
	 */
	public function getBundles(ParserInterface $parser)
	{
		return array(
			BundleConfig::create(ContaoKongresseBundle::class)
				->setLoadAfter(array(ContaoCoreBundle::class)),
		);
	}
}
