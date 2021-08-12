<?php

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace MASK\Mask\Tests\Unit;

use MASK\Mask\Domain\Repository\StorageRepository;
use MASK\Mask\Loader\LoaderInterface;
use MASK\Mask\Loader\TableDefinitionCollection;
use MASK\Mask\Tests\Unit\ConfigurationLoader\FakeConfigurationLoader;

trait StorageRepositoryCreatorTrait
{
    protected function createStorageRepository(array $json): StorageRepository
    {
        $tableDefinitionCollection = TableDefinitionCollection::createFromInternalArray($json);
        $loader = $this->prophesize(LoaderInterface::class);
        $loader->load()->willReturn($tableDefinitionCollection);
        $configurationLoader = new FakeConfigurationLoader();

        return new StorageRepository($loader->reveal(), $configurationLoader);
    }
}
