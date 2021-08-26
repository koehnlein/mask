<?php

declare(strict_types=1);

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

use MASK\Mask\Utility\GeneralUtility;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class GeneralUtilityTest extends UnitTestCase
{
    public function getTemplatePathDataProvider()
    {
        return [
            'UpperCamelCase exists' => [
                ['content' => 'typo3conf/ext/mask/Tests/Unit/Fixtures/Templates/'],
                'upper_exists',
                false,
                null,
                false,
                Environment::getPublicPath() . '/typo3conf/ext/mask/Tests/Unit/Fixtures/Templates/UpperExists.html'
            ],
            'File does not exist' => [
                ['content' => 'typo3conf/ext/mask/Tests/Unit/Fixtures/Templates/'],
                'noelement',
                false,
                null,
                false,
                Environment::getPublicPath() . '/typo3conf/ext/mask/Tests/Unit/Fixtures/Templates/Noelement.html'
            ],
            'under_scored exists' => [
                ['content' => 'typo3conf/ext/mask/Tests/Unit/Fixtures/Templates/'],
                'under_scored',
                false,
                null,
                false,
                Environment::getPublicPath() . '/typo3conf/ext/mask/Tests/Unit/Fixtures/Templates/under_scored.html'
            ],
            'Uc_first exists' => [
                ['content' => 'typo3conf/ext/mask/Tests/Unit/Fixtures/Templates/'],
                'uc_first',
                false,
                null,
                false,
                Environment::getPublicPath() . '/typo3conf/ext/mask/Tests/Unit/Fixtures/Templates/Uc_first.html'
            ],
            'Manually configured path works' => [
                ['content' => ''],
                'upper_exists',
                false,
                'typo3conf/ext/mask/Tests/Unit/Fixtures/Templates/',
                false,
                'typo3conf/ext/mask/Tests/Unit/Fixtures/Templates/UpperExists.html'
            ],
            'Manually configured absolute path works' => [
                ['content' => ''],
                'upper_exists',
                false,
                Environment::getPublicPath() . '/typo3conf/ext/mask/Tests/Unit/Fixtures/Templates/',
                false,
                Environment::getPublicPath() . '/typo3conf/ext/mask/Tests/Unit/Fixtures/Templates/UpperExists.html'
            ],
            'Only template is returned' => [
                ['content' => 'typo3conf/ext/mask/Tests/Unit/Fixtures/Templates/'],
                'upper_exists',
                true,
                null,
                false,
                'UpperExists.html'
            ],
            'Only template without extension returned' => [
                ['content' => 'typo3conf/ext/mask/Tests/Unit/Fixtures/Templates/'],
                'upper_exists',
                true,
                null,
                true,
                'UpperExists'
            ],
            'Manually configured path and only template' => [
                ['content' => ''],
                'upper_exists',
                true,
                'typo3conf/ext/mask/Tests/Unit/Fixtures/Templates/',
                false,
                'UpperExists.html'
            ],
            'Empty path returns empty string' => [
                ['content' => ''],
                'does_not_exist',
                false,
                null,
                false,
                ''
            ],
            'Wrong path returns empty string' => [
                ['content' => '/does/not/exist'],
                'does_not_exist',
                false,
                null,
                false,
                ''
            ],
            'Empty element key returns empty string' => [
                ['content' => 'typo3conf/ext/mask/Tests/Unit/Fixtures/Templates/'],
                '',
                false,
                null,
                false,
                ''
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getTemplatePathDataProvider
     * @param $settings
     * @param $elementKey
     * @param $onlyTemplateName
     * @param $path
     * @param $expectedPath
     */
    public function getTemplatePath($settings, $elementKey, $onlyTemplateName, $path, $removeExtension, $expectedPath)
    {
        $this->resetSingletonInstances = true;
        $path = GeneralUtility::getTemplatePath($settings, $elementKey, $onlyTemplateName, $path, $removeExtension);
        self::assertSame($expectedPath, $path);
    }

    public function removeBlankOptionsDataProvider()
    {
        return [
            'Array mixed filled and empty' => [
                [
                    'key' => [
                        'option1' => 'setting',
                        'option2' => ['setting'],
                        'option3' => '',
                        'option4' => [],
                        'option5' => null,
                        'option6' => false,
                        'option7' => 0,
                        'option8' => '0',
                        'option9' => [
                            'option1' => 'setting',
                            'option2' => ['setting'],
                            'option3' => '',
                            'option4' => [],
                            'option5' => null,
                            'option6' => false,
                            'option7' => 0,
                            'option8' => '0',
                        ],
                    ],
                ],
                [
                    'key' => [
                        'option1' => 'setting',
                        'option2' => ['setting'],
                        'option5' => null,
                        'option6' => false,
                        'option7' => 0,
                        'option8' => '0',
                        'option9' => [
                            'option1' => 'setting',
                            'option2' => ['setting'],
                            'option5' => null,
                            'option6' => false,
                            'option7' => 0,
                            'option8' => '0',
                        ],
                    ],
                ]
            ],
        ];
    }

    /**
     * @test
     * @dataProvider removeBlankOptionsDataProvider
     * @param $array
     * @param $expected
     */
    public function removeBlankOptions($array, $expected)
    {
        self::assertSame($expected, GeneralUtility::removeBlankOptions($array));
    }

    public function getFirstNoneTabFieldDataProvider()
    {
        return [
            'Tab is first element' => [
                ['--div--;My Tab', 'tx_mask_the_field', 'tx_mask_another_field'],
                'tx_mask_the_field'
            ],
            'Tab is not first element' => [
                ['tx_mask_the_field', '--div--;My Tab', 'tx_mask_another_field'],
                'tx_mask_the_field'
            ]
        ];
    }

    /**
     * @test
     * @dataProvider getFirstNoneTabFieldDataProvider
     * @param $data
     * @param $expected
     */
    public function getFirstNoneTabField($data, $expected)
    {
        self::assertSame($expected, GeneralUtility::getFirstNoneTabField($data));
    }
}