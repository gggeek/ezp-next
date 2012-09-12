<?php
/**
 * File contains: eZ\Publish\Core\Repository\Tests\Service\UrlAliasBase class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Repository\Tests\Service;

use eZ\Publish\Core\Repository\Tests\Service\Base as BaseServiceTest,
    eZ\Publish\API\Repository\Values\Content\UrlAlias,
    eZ\Publish\SPI\Persistence\Content\UrlAlias as SPIUrlAlias,
    eZ\Publish\Core\Repository\Values\Content\Location;

/**
 * Test case for UrlAlias Service
 */
abstract class UrlAliasBase extends BaseServiceTest
{
    /**
     * Test for the lookup() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::lookup
     * @group a
     */
    public function testLookupRootLocation()
    {
        $urlAliasService = $this->repository->getURLAliasService();

        $urlAlias = $urlAliasService->lookup( "" );

        self::assertEquals(
            new UrlAlias(
                array(
                    "id" => "0-d41d8cd98f00b204e9800998ecf8427e",
                    "type" => UrlAlias::LOCATION,
                    "destination" => "2",
                    "path" => "",
                    "languageCodes" => array( "eng-US", "eng-GB" ),
                    "alwaysAvailable" => true,
                    "isHistory" => false,
                    "isCustom" => false,
                    "forward" => false,
                )
            ),
            $urlAlias
        );
    }

    /**
     * Test for the lookup() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::lookup
     */
    public function testLookupAlwaysAvailable()
    {
        $urlAliasService = $this->repository->getURLAliasService();

        $urlAlias = $urlAliasService->lookup( "Users" );

        self::assertEquals(
            new UrlAlias(
                array(
                    "id" => "0-9bc65c2abec141778ffaa729489f3e87",
                    "type" => UrlAlias::LOCATION,
                    "destination" => "5",
                    "path" => "Users",
                    "languageCodes" => array( "eng-US" ),
                    "alwaysAvailable" => true,
                    "isHistory" => false,
                    "isCustom" => false,
                    "forward" => false,
                )
            ),
            $urlAlias
        );
    }

    /**
     * Test for the lookup() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::lookup
     */
    public function testLookupAlwaysAvailableAlwaysFound()
    {
        $urlAliasService = $this->repository->getURLAliasService();
        $configuration = array(
            "prioritizedLanguageList" => array( "ger-DE" ),
            "showAllTranslations" => false,
        );
        $this->setConfiguration( $urlAliasService, $configuration );

        $urlAlias = $urlAliasService->lookup( "Users" );

        self::assertEquals(
            new UrlAlias(
                array(
                    "id" => "0-9bc65c2abec141778ffaa729489f3e87",
                    "type" => UrlAlias::LOCATION,
                    "destination" => "5",
                    "path" => "Users",
                    "languageCodes" => array( "eng-US" ),
                    "alwaysAvailable" => true,
                    "isHistory" => false,
                    "isCustom" => false,
                    "forward" => false,
                )
            ),
            $urlAlias
        );
    }

    /**
     * Test for the lookup() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::lookup
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testLookupThrowsNotFoundExceptionUrl()
    {
        $urlAliasService = $this->repository->getURLAliasService();

        $urlAliasService->lookup( "jabberwocky" );
    }

    /**
     * Test for the lookup() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::lookup
     */
    public function testLookup()
    {
        $urlAliasService = $this->repository->getURLAliasService();

        $urlAlias = $urlAliasService->lookup( "Design/Plain-site" );

        self::assertEquals(
            new UrlAlias(
                array(
                    "id" => "25-49a39d99a955d95aa5d636275656a07a",
                    "type" => UrlAlias::LOCATION,
                    "destination" => "56",
                    "path" => "Design/Plain-site",
                    "languageCodes" => array( "eng-US" ),
                    "alwaysAvailable" => false,
                    "isHistory" => false,
                    "isCustom" => false,
                    "forward" => false,
                )
            ),
            $urlAlias
        );
    }

    /**
     * Test for the lookup() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::lookup
     */
    public function testLookupCaseInsensitive()
    {
        $urlAliasService = $this->repository->getURLAliasService();

        $urlAlias = $urlAliasService->lookup( "DESIGN/PLAIN-SITE" );

        self::assertEquals(
            new UrlAlias(
                array(
                    "id" => "25-49a39d99a955d95aa5d636275656a07a",
                    "type" => UrlAlias::LOCATION,
                    "destination" => "56",
                    "path" => "Design/Plain-site",
                    "languageCodes" => array( "eng-US" ),
                    "alwaysAvailable" => false,
                    "isHistory" => false,
                    "isCustom" => false,
                    "forward" => false,
                )
            ),
            $urlAlias
        );
    }

    /**
     * Test for the lookup() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::lookup
     */
    public function testLookupWithLanguageCode()
    {
        $urlAliasService = $this->repository->getURLAliasService();

        $urlAlias = $urlAliasService->lookup( "Design/Plain-site", "eng-US" );

        self::assertEquals(
            new UrlAlias(
                array(
                    "id" => "25-49a39d99a955d95aa5d636275656a07a",
                    "type" => UrlAlias::LOCATION,
                    "destination" => "56",
                    "path" => "Design/Plain-site",
                    "languageCodes" => array( "eng-US" ),
                    "alwaysAvailable" => false,
                    "isHistory" => false,
                    "isCustom" => false,
                    "forward" => false,
                )
            ),
            $urlAlias
        );
    }

    /**
     * Test for the lookup() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::lookup
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testLookupThrowsNotFoundExceptionTranslation()
    {
        $urlAliasService = $this->repository->getURLAliasService();

        $urlAliasService->lookup( "Design/Plain-site", "eng-GB" );
    }

    /**
     * Test for the lookup() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::lookup
     */
    public function testLookupWithShowAllTranslations()
    {
        $urlAliasService = $this->repository->getURLAliasService();
        $configuration = array(
            "prioritizedLanguageList" => array( "ger-DE" ),
            "showAllTranslations" => true,
        );
        $this->setConfiguration( $urlAliasService, $configuration );

        $urlAlias = $urlAliasService->lookup( "Design/Plain-site" );

        self::assertEquals(
            new UrlAlias(
                array(
                    "id" => "25-49a39d99a955d95aa5d636275656a07a",
                    "type" => UrlAlias::LOCATION,
                    "destination" => "56",
                    "path" => "Design/Plain-site",
                    "languageCodes" => array( "eng-US" ),
                    "alwaysAvailable" => false,
                    "isHistory" => false,
                    "isCustom" => false,
                    "forward" => false,
                )
            ),
            $urlAlias
        );
    }

    /**
     * Test for the lookup() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::lookup
     */
    public function testLookupHistory()
    {
        $urlAliasService = $this->repository->getURLAliasService();

        $urlAlias = $urlAliasService->lookup( "Design/eZ-publish" );

        self::assertEquals(
            new UrlAlias(
                array(
                    "id" => "25-10e4c3cb527fb9963258469986c16240",
                    "type" => UrlAlias::LOCATION,
                    "destination" => "56",
                    "path" => "Design/eZ-publish",
                    "languageCodes" => array( "eng-US" ),
                    "alwaysAvailable" => false,
                    "isHistory" => true,
                    "isCustom" => false,
                    "forward" => false,
                )
            ),
            $urlAlias
        );
    }

    public function providerForTestListAutogeneratedLocationAliasesPath()
    {
        $pathElement1 = array(
            "always-available" => true,
            "translations" => array(
                "cro-HR" => "jedan",
            )
        );
        $pathElement2 = array(
            "always-available" => false,
            "translations" => array(
                "cro-HR" => "dva",
                "eng-GB" => "two",
            )
        );
        $pathElement3 = array(
            "always-available" => false,
            "translations" => array(
                "cro-HR" => "tri",
                "eng-GB" => "three",
                "ger-DE" => "drei",
            )
        );
        $pathData1 = array( $pathElement1 );
        $pathData2 = array( $pathElement1, $pathElement2 );
        $pathData3 = array( $pathElement1, $pathElement2, $pathElement3 );
        $spiUrlAliases1 = array(
            new SPIUrlAlias(
                array(
                    "pathData" => $pathData1,
                    "languageCodes" => array( "cro-HR" ),
                    "alwaysAvailable" => true,
                )
            )
        );
        $spiUrlAliases2 = array(
            new SPIUrlAlias(
                array(
                    "pathData" => $pathData2,
                    "languageCodes" => array( "cro-HR" ),
                    "alwaysAvailable" => false,
                )
            ),
            new SPIUrlAlias(
                array(
                    "pathData" => $pathData2,
                    "languageCodes" => array( "eng-GB" ),
                    "alwaysAvailable" => false,
                )
            )
        );
        $spiUrlAliases3 = array(
            new SPIUrlAlias(
                array(
                    "pathData" => $pathData3,
                    "languageCodes" => array( "cro-HR" ),
                    "alwaysAvailable" => false,
                )
            ),
            new SPIUrlAlias(
                array(
                    "pathData" => $pathData3,
                    "languageCodes" => array( "eng-GB" ),
                    "alwaysAvailable" => false,
                )
            ),
            new SPIUrlAlias(
                array(
                    "pathData" => $pathData3,
                    "languageCodes" => array( "ger-DE" ),
                    "alwaysAvailable" => false,
                )
            )
        );

        return array(
            array(
                $spiUrlAliases1,
                array( "cro-HR" ),
                array(
                    "cro-HR" => "jedan",
                ),
                "cro-HR",
            ),
            array(
                $spiUrlAliases1,
                array( "eng-GB" ),
                array(
                    "cro-HR" => "jedan",
                ),
                "cro-HR",
            ),
            array(
                $spiUrlAliases1,
                array( "ger-DE" ),
                array(
                    "cro-HR" => "jedan",
                ),
                "cro-HR",
            ),
            array(
                $spiUrlAliases1,
                array( "cro-HR", "eng-GB", "ger-DE" ),
                array(
                    "cro-HR" => "jedan",
                ),
                "cro-HR",
            ),
            array(
                $spiUrlAliases2,
                array( "cro-HR" ),
                array(
                    "cro-HR" => "jedan/dva",
                ),
                "cro-HR",
            ),
            array(
                $spiUrlAliases2,
                array( "eng-GB" ),
                array(
                    "eng-GB" => "jedan/two",
                ),
                "eng-GB",
            ),
            array(
                $spiUrlAliases2,
                array( "cro-HR", "eng-GB" ),
                array(
                    "cro-HR" => "jedan/dva",
                    "eng-GB" => "jedan/two",
                ),
                "cro-HR",
            ),
            array(
                $spiUrlAliases2,
                array( "cro-HR", "ger-DE" ),
                array(
                    "cro-HR" => "jedan/dva",
                ),
                "cro-HR",
            ),
            array(
                $spiUrlAliases2,
                array( "eng-GB", "cro-HR" ),
                array(
                    "cro-HR" => "jedan/dva",
                    "eng-GB" => "jedan/two",
                ),
                "eng-GB",
            ),
            array(
                $spiUrlAliases2,
                array( "eng-GB", "ger-DE" ),
                array(
                    "eng-GB" => "jedan/two",
                ),
                "eng-GB",
            ),
            array(
                $spiUrlAliases2,
                array( "ger-DE", "cro-HR" ),
                array(
                    "cro-HR" => "jedan/dva",
                ),
                "cro-HR",
            ),
            array(
                $spiUrlAliases2,
                array( "ger-DE", "eng-GB" ),
                array(
                    "eng-GB" => "jedan/two",
                ),
                "eng-GB",
            ),
            array(
                $spiUrlAliases2,
                array( "cro-HR", "eng-GB", "ger-DE" ),
                array(
                    "cro-HR" => "jedan/dva",
                    "eng-GB" => "jedan/two",
                ),
                "cro-HR",
            ),
            array(
                $spiUrlAliases2,
                array( "cro-HR", "ger-DE", "eng-GB" ),
                array(
                    "cro-HR" => "jedan/dva",
                    "eng-GB" => "jedan/two",
                ),
                "cro-HR",
            ),
            array(
                $spiUrlAliases2,
                array( "eng-GB", "cro-HR", "ger-DE" ),
                array(
                    "cro-HR" => "jedan/dva",
                    "eng-GB" => "jedan/two",
                ),
                "eng-GB",
            ),
            array(
                $spiUrlAliases2,
                array( "eng-GB", "ger-DE", "cro-HR" ),
                array(
                    "cro-HR" => "jedan/dva",
                    "eng-GB" => "jedan/two",
                ),
                "eng-GB",
            ),
            array(
                $spiUrlAliases2,
                array( "ger-DE", "cro-HR", "eng-GB" ),
                array(
                    "cro-HR" => "jedan/dva",
                    "eng-GB" => "jedan/two",
                ),
                "cro-HR",
            ),
            array(
                $spiUrlAliases2,
                array( "ger-DE", "eng-GB", "cro-HR" ),
                array(
                    "cro-HR" => "jedan/dva",
                    "eng-GB" => "jedan/two",
                ),
                "eng-GB",
            ),
            array(
                $spiUrlAliases3,
                array( "cro-HR" ),
                array(
                    "cro-HR" => "jedan/dva/tri",
                ),
                "cro-HR",
            ),
            array(
                $spiUrlAliases3,
                array( "eng-GB" ),
                array(
                    "eng-GB" => "jedan/two/three",
                ),
                "eng-GB",
            ),
            array(
                $spiUrlAliases3,
                array( "cro-HR", "eng-GB" ),
                array(
                    "cro-HR" => "jedan/dva/tri",
                    "eng-GB" => "jedan/dva/three",
                ),
                "cro-HR",
            ),
            array(
                $spiUrlAliases3,
                array( "cro-HR", "ger-DE" ),
                array(
                    "cro-HR" => "jedan/dva/tri",
                    "ger-DE" => "jedan/dva/drei",
                ),
                "cro-HR",
            ),
            array(
                $spiUrlAliases3,
                array( "eng-GB", "cro-HR" ),
                array(
                    "cro-HR" => "jedan/two/tri",
                    "eng-GB" => "jedan/two/three",
                ),
                "eng-GB",
            ),
            array(
                $spiUrlAliases3,
                array( "eng-GB", "ger-DE" ),
                array(
                    "eng-GB" => "jedan/two/three",
                    "ger-DE" => "jedan/two/drei",
                ),
                "eng-GB",
            ),
            array(
                $spiUrlAliases3,
                array( "ger-DE", "eng-GB" ),
                array(
                    "eng-GB" => "jedan/two/three",
                    "ger-DE" => "jedan/two/drei",
                ),
                "ger-DE",
            ),
            array(
                $spiUrlAliases3,
                array( "ger-DE", "cro-HR" ),
                array(
                    "cro-HR" => "jedan/dva/tri",
                    "ger-DE" => "jedan/dva/drei",
                ),
                "ger-DE",
            ),
            array(
                $spiUrlAliases3,
                array( "cro-HR", "eng-GB", "ger-DE" ),
                array(
                    "cro-HR" => "jedan/dva/tri",
                    "eng-GB" => "jedan/dva/three",
                    "ger-DE" => "jedan/dva/drei",
                ),
                "cro-HR",
            ),
            array(
                $spiUrlAliases3,
                array( "cro-HR", "ger-DE", "eng-GB" ),
                array(
                    "cro-HR" => "jedan/dva/tri",
                    "eng-GB" => "jedan/dva/three",
                    "ger-DE" => "jedan/dva/drei",
                ),
                "cro-HR",
            ),
            array(
                $spiUrlAliases3,
                array( "eng-GB", "cro-HR", "ger-DE" ),
                array(
                    "cro-HR" => "jedan/two/tri",
                    "eng-GB" => "jedan/two/three",
                    "ger-DE" => "jedan/two/drei",
                ),
                "eng-GB",
            ),
            array(
                $spiUrlAliases3,
                array( "eng-GB", "ger-DE", "cro-HR" ),
                array(
                    "cro-HR" => "jedan/two/tri",
                    "eng-GB" => "jedan/two/three",
                    "ger-DE" => "jedan/two/drei",
                ),
                "eng-GB",
            ),
            array(
                $spiUrlAliases3,
                array( "ger-DE", "cro-HR", "eng-GB" ),
                array(
                    "cro-HR" => "jedan/dva/tri",
                    "eng-GB" => "jedan/dva/three",
                    "ger-DE" => "jedan/dva/drei",
                ),
                "ger-DE",
            ),
            array(
                $spiUrlAliases3,
                array( "ger-DE", "eng-GB", "cro-HR" ),
                array(
                    "cro-HR" => "jedan/two/tri",
                    "eng-GB" => "jedan/two/three",
                    "ger-DE" => "jedan/two/drei",
                ),
                "ger-DE",
            ),
        );
    }

    /**
     * Test for the listLocationAliases() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::listLocationAliases
     * @dataProvider providerForTestListAutogeneratedLocationAliasesPath
     */
    public function testListAutogeneratedLocationAliasesPath( $spiUrlAliases, $prioritizedLanguageCodes, $paths, $dummy )
    {
        $urlAliasService = $this->getMockedUrlAliasService();
        $configuration = array(
            "prioritizedLanguageList" => $prioritizedLanguageCodes,
            "showAllTranslations" => false,
        );
        $this->setConfiguration( $urlAliasService, $configuration );

        $urlAliasHandler = $this->getUrlAliasHandlerMock();
        $urlAliasHandler->expects(
            $this->once()
        )->method(
            "listURLAliasesForLocation"
        )->with(
            $this->equalTo( 42 ),
            $this->equalTo( false )
        )->will(
            $this->returnValue( $spiUrlAliases )
        );

        $location = $this->getLocationStub();
        $urlAliases = $urlAliasService->listLocationAliases( $location, false, null );

        self::assertEquals(
            count( $paths ),
            count( $urlAliases )
        );

        foreach ( $urlAliases as $index => $urlAlias )
        {
            $pathKeys = array_keys( $paths );
            self::assertEquals(
                $paths[$pathKeys[$index]],
                $urlAlias->path
            );
            self::assertEquals(
                array( $pathKeys[$index] ),
                $urlAlias->languageCodes
            );
        }
    }

    public function providerForTestListAutogeneratedLocationAliasesEmpty()
    {
        $pathElement1 = array(
            "always-available" => true,
            "translations" => array(
                "cro-HR" => "jedan",
            )
        );
        $pathElement2 = array(
            "always-available" => false,
            "translations" => array(
                "cro-HR" => "dva",
                "eng-GB" => "two",
            )
        );
        $pathElement3 = array(
            "always-available" => false,
            "translations" => array(
                "cro-HR" => "tri",
                "eng-GB" => "three",
                "ger-DE" => "drei",
            )
        );
        $pathData2 = array( $pathElement1, $pathElement2 );
        $pathData3 = array( $pathElement1, $pathElement2, $pathElement3 );
        $spiUrlAliases2 = array(
            new SPIUrlAlias(
                array(
                    "pathData" => $pathData2,
                    "languageCodes" => array( "cro-HR" ),
                    "alwaysAvailable" => false,
                )
            ),
            new SPIUrlAlias(
                array(
                    "pathData" => $pathData2,
                    "languageCodes" => array( "eng-GB" ),
                    "alwaysAvailable" => false,
                )
            )
        );
        $spiUrlAliases3 = array(
            new SPIUrlAlias(
                array(
                    "pathData" => $pathData3,
                    "languageCodes" => array( "cro-HR" ),
                    "alwaysAvailable" => false,
                )
            ),
            new SPIUrlAlias(
                array(
                    "pathData" => $pathData3,
                    "languageCodes" => array( "eng-GB" ),
                    "alwaysAvailable" => false,
                )
            ),
            new SPIUrlAlias(
                array(
                    "pathData" => $pathData3,
                    "languageCodes" => array( "ger-DE" ),
                    "alwaysAvailable" => false,
                )
            )
        );

        return array(
            array(
                $spiUrlAliases2,
                array( "ger-DE" ),
            ),
            array(
                $spiUrlAliases3,
                array( "ger-DE" ),
            ),
        );
    }

    /**
     * Test for the listLocationAliases() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::listLocationAliases
     * @dataProvider providerForTestListAutogeneratedLocationAliasesEmpty
     */
    public function testListAutogeneratedLocationAliasesEmpty( $spiUrlAliases, $prioritizedLanguageCodes )
    {
        $urlAliasService = $this->getMockedUrlAliasService();
        $configuration = array(
            "prioritizedLanguageList" => $prioritizedLanguageCodes,
            "showAllTranslations" => false,
        );
        $this->setConfiguration( $urlAliasService, $configuration );
        $urlAliasHandler = $this->getUrlAliasHandlerMock();
        $urlAliasHandler->expects(
            $this->once()
        )->method(
            "listURLAliasesForLocation"
        )->with(
            $this->equalTo( 42 ),
            $this->equalTo( false )
        )->will(
            $this->returnValue( $spiUrlAliases )
        );

        $location = $this->getLocationStub();
        $urlAliases = $urlAliasService->listLocationAliases( $location, false, null );

        self::assertEmpty( $urlAliases );
    }

    public function providerForTestListAutogeneratedLocationAliasesWithLanguageCodePath()
    {
        $pathElement1 = array(
            "always-available" => true,
            "translations" => array(
                "cro-HR" => "jedan",
            )
        );
        $pathElement2 = array(
            "always-available" => false,
            "translations" => array(
                "cro-HR" => "dva",
                "eng-GB" => "two",
            )
        );
        $pathElement3 = array(
            "always-available" => false,
            "translations" => array(
                "cro-HR" => "tri",
                "eng-GB" => "three",
                "ger-DE" => "drei",
            )
        );
        $pathData1 = array( $pathElement1 );
        $pathData2 = array( $pathElement1, $pathElement2 );
        $pathData3 = array( $pathElement1, $pathElement2, $pathElement3 );
        $spiUrlAliases1 = array(
            new SPIUrlAlias(
                array(
                    "pathData" => $pathData1,
                    "languageCodes" => array( "cro-HR" ),
                    "alwaysAvailable" => true,
                )
            )
        );
        $spiUrlAliases2 = array(
            new SPIUrlAlias(
                array(
                    "pathData" => $pathData2,
                    "languageCodes" => array( "cro-HR" ),
                    "alwaysAvailable" => false,
                )
            ),
            new SPIUrlAlias(
                array(
                    "pathData" => $pathData2,
                    "languageCodes" => array( "eng-GB" ),
                    "alwaysAvailable" => false,
                )
            )
        );
        $spiUrlAliases3 = array(
            new SPIUrlAlias(
                array(
                    "pathData" => $pathData3,
                    "languageCodes" => array( "cro-HR" ),
                    "alwaysAvailable" => false,
                )
            ),
            new SPIUrlAlias(
                array(
                    "pathData" => $pathData3,
                    "languageCodes" => array( "eng-GB" ),
                    "alwaysAvailable" => false,
                )
            ),
            new SPIUrlAlias(
                array(
                    "pathData" => $pathData3,
                    "languageCodes" => array( "ger-DE" ),
                    "alwaysAvailable" => false,
                )
            )
        );

        return array(
            array(
                $spiUrlAliases1,
                "cro-HR",
                array( "cro-HR" ),
                array(
                    "jedan",
                ),
            ),
            array(
                $spiUrlAliases1,
                "cro-HR",
                array( "eng-GB" ),
                array(
                    "jedan",
                ),
            ),
            array(
                $spiUrlAliases2,
                "cro-HR",
                array( "cro-HR" ),
                array(
                    "jedan/dva",
                ),
            ),
            array(
                $spiUrlAliases2,
                "eng-GB",
                array( "eng-GB" ),
                array(
                    "jedan/two",
                ),
            ),
            array(
                $spiUrlAliases2,
                "eng-GB",
                array( "cro-HR", "eng-GB" ),
                array(
                    "jedan/two",
                ),
            ),
            array(
                $spiUrlAliases2,
                "cro-HR",
                array( "cro-HR", "ger-DE" ),
                array(
                    "jedan/dva",
                ),
            ),
            array(
                $spiUrlAliases2,
                "cro-HR",
                array( "eng-GB", "cro-HR" ),
                array(
                    "jedan/dva",
                ),
            ),
            array(
                $spiUrlAliases2,
                "eng-GB",
                array( "eng-GB", "ger-DE" ),
                array(
                    "jedan/two",
                ),
            ),
            array(
                $spiUrlAliases2,
                "cro-HR",
                array( "ger-DE", "cro-HR" ),
                array(
                    "jedan/dva",
                ),
            ),
            array(
                $spiUrlAliases2,
                "eng-GB",
                array( "ger-DE", "eng-GB" ),
                array(
                    "jedan/two",
                ),
            ),
            array(
                $spiUrlAliases2,
                "cro-HR",
                array( "cro-HR", "eng-GB", "ger-DE" ),
                array(
                    "jedan/dva",
                ),
            ),
            array(
                $spiUrlAliases2,
                "eng-GB",
                array( "cro-HR", "ger-DE", "eng-GB" ),
                array(
                    "jedan/two",
                ),
            ),
            array(
                $spiUrlAliases2,
                "cro-HR",
                array( "eng-GB", "cro-HR", "ger-DE" ),
                array(
                    "jedan/dva",
                ),
            ),
            array(
                $spiUrlAliases2,
                "cro-HR",
                array( "eng-GB", "ger-DE", "cro-HR" ),
                array(
                    "jedan/dva",
                ),
            ),
            array(
                $spiUrlAliases2,
                "cro-HR",
                array( "ger-DE", "cro-HR", "eng-GB" ),
                array(
                    "jedan/dva",
                ),
            ),
            array(
                $spiUrlAliases2,
                "cro-HR",
                array( "ger-DE", "eng-GB", "cro-HR" ),
                array(
                    "jedan/dva",
                ),
            ),
            array(
                $spiUrlAliases3,
                "cro-HR",
                array( "cro-HR" ),
                array(
                    "jedan/dva/tri",
                ),
            ),
            array(
                $spiUrlAliases3,
                "eng-GB",
                array( "eng-GB" ),
                array(
                    "jedan/two/three",
                ),
            ),
            array(
                $spiUrlAliases3,
                "eng-GB",
                array( "cro-HR", "eng-GB" ),
                array(
                    "jedan/dva/three",
                ),
            ),
            array(
                $spiUrlAliases3,
                "ger-DE",
                array( "cro-HR", "ger-DE" ),
                array(
                    "jedan/dva/drei",
                ),
            ),
            array(
                $spiUrlAliases3,
                "cro-HR",
                array( "eng-GB", "cro-HR" ),
                array(
                    "jedan/two/tri",
                ),
            ),
            array(
                $spiUrlAliases3,
                "ger-DE",
                array( "eng-GB", "ger-DE" ),
                array(
                    "jedan/two/drei",
                ),
            ),
            array(
                $spiUrlAliases3,
                "eng-GB",
                array( "ger-DE", "eng-GB" ),
                array(
                    "jedan/two/three",
                ),
            ),
            array(
                $spiUrlAliases3,
                "ger-DE",
                array( "ger-DE", "cro-HR" ),
                array(
                    "jedan/dva/drei",
                ),
            ),
            array(
                $spiUrlAliases3,
                "ger-DE",
                array( "cro-HR", "eng-GB", "ger-DE" ),
                array(
                    "jedan/dva/drei",
                ),
            ),
            array(
                $spiUrlAliases3,
                "ger-DE",
                array( "cro-HR", "ger-DE", "eng-GB" ),
                array(
                    "jedan/dva/drei",
                ),
            ),
            array(
                $spiUrlAliases3,
                "ger-DE",
                array( "eng-GB", "cro-HR", "ger-DE" ),
                array(
                    "jedan/two/drei",
                ),
            ),
            array(
                $spiUrlAliases3,
                "ger-DE",
                array( "eng-GB", "ger-DE", "cro-HR" ),
                array(
                    "jedan/two/drei",
                ),
            ),
            array(
                $spiUrlAliases3,
                "eng-GB",
                array( "ger-DE", "cro-HR", "eng-GB" ),
                array(
                    "jedan/dva/three",
                ),
            ),
            array(
                $spiUrlAliases3,
                "cro-HR",
                array( "ger-DE", "eng-GB", "cro-HR" ),
                array(
                    "jedan/two/tri",
                ),
            ),
        );
    }

    /**
     * Test for the listLocationAliases() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::listLocationAliases
     * @dataProvider providerForTestListAutogeneratedLocationAliasesWithLanguageCodePath
     */
    public function testListAutogeneratedLocationAliasesWithLanguageCodePath(
        $spiUrlAliases,
        $languageCode,
        $prioritizedLanguageCodes,
        $paths
    )
    {
        $urlAliasService = $this->getMockedUrlAliasService();
        $configuration = array(
            "prioritizedLanguageList" => $prioritizedLanguageCodes,
            "showAllTranslations" => false,
        );
        $this->setConfiguration( $urlAliasService, $configuration );
        $urlAliasHandler = $this->getUrlAliasHandlerMock();
        $urlAliasHandler->expects(
            $this->once()
        )->method(
            "listURLAliasesForLocation"
        )->with(
            $this->equalTo( 42 ),
            $this->equalTo( false )
        )->will(
            $this->returnValue( $spiUrlAliases )
        );

        $location = $this->getLocationStub();
        $urlAliases = $urlAliasService->listLocationAliases( $location, false, $languageCode );

        self::assertEquals(
            count( $paths ),
            count( $urlAliases )
        );

        foreach ( $urlAliases as $index => $urlAlias )
        {
            self::assertEquals(
                $paths[$index],
                $urlAlias->path
            );
        }
    }

    public function providerForTestListAutogeneratedLocationAliasesWithLanguageCodeEmpty()
    {
        $pathElement1 = array(
            "always-available" => true,
            "translations" => array(
                "cro-HR" => "jedan",
            )
        );
        $pathElement2 = array(
            "always-available" => false,
            "translations" => array(
                "cro-HR" => "dva",
                "eng-GB" => "two",
            )
        );
        $pathElement3 = array(
            "always-available" => false,
            "translations" => array(
                "cro-HR" => "tri",
                "eng-GB" => "three",
                "ger-DE" => "drei",
            )
        );
        $pathData1 = array( $pathElement1 );
        $pathData2 = array( $pathElement1, $pathElement2 );
        $pathData3 = array( $pathElement1, $pathElement2, $pathElement3 );
        $spiUrlAliases1 = array(
            new SPIUrlAlias(
                array(
                    "pathData" => $pathData1,
                    "languageCodes" => array( "cro-HR" ),
                    "alwaysAvailable" => true,
                )
            )
        );
        $spiUrlAliases2 = array(
            new SPIUrlAlias(
                array(
                    "pathData" => $pathData2,
                    "languageCodes" => array( "cro-HR" ),
                    "alwaysAvailable" => false,
                )
            ),
            new SPIUrlAlias(
                array(
                    "pathData" => $pathData2,
                    "languageCodes" => array( "eng-GB" ),
                    "alwaysAvailable" => false,
                )
            )
        );
        $spiUrlAliases3 = array(
            new SPIUrlAlias(
                array(
                    "pathData" => $pathData3,
                    "languageCodes" => array( "cro-HR" ),
                    "alwaysAvailable" => false,
                )
            ),
            new SPIUrlAlias(
                array(
                    "pathData" => $pathData3,
                    "languageCodes" => array( "eng-GB" ),
                    "alwaysAvailable" => false,
                )
            ),
            new SPIUrlAlias(
                array(
                    "pathData" => $pathData3,
                    "languageCodes" => array( "ger-DE" ),
                    "alwaysAvailable" => false,
                )
            )
        );

        return array(
            array(
                $spiUrlAliases1,
                "eng-GB",
                array( "ger-DE" ),
            ),
            array(
                $spiUrlAliases1,
                "ger-DE",
                array( "cro-HR", "eng-GB", "ger-DE" ),
            ),
            array(
                $spiUrlAliases2,
                "eng-GB",
                array( "cro-HR" ),
            ),
            array(
                $spiUrlAliases2,
                "ger-DE",
                array( "cro-HR", "eng-GB" ),
            ),
            array(
                $spiUrlAliases2,
                "ger-DE",
                array( "cro-HR", "ger-DE" ),
            ),
            array(
                $spiUrlAliases2,
                "ger-DE",
                array( "eng-GB", "ger-DE" ),
            ),
            array(
                $spiUrlAliases2,
                "ger-DE",
                array( "ger-DE", "cro-HR" ),
            ),
            array(
                $spiUrlAliases2,
                "ger-DE",
                array( "ger-DE", "eng-GB" ),
            ),
            array(
                $spiUrlAliases2,
                "ger-DE",
                array( "cro-HR", "eng-GB", "ger-DE" ),
            ),
            array(
                $spiUrlAliases2,
                "ger-DE",
                array( "cro-HR", "ger-DE", "eng-GB" ),
            ),
            array(
                $spiUrlAliases2,
                "ger-DE",
                array( "eng-GB", "cro-HR", "ger-DE" ),
            ),
            array(
                $spiUrlAliases2,
                "ger-DE",
                array( "eng-GB", "ger-DE", "cro-HR" ),
            ),
            array(
                $spiUrlAliases2,
                "ger-DE",
                array( "ger-DE", "cro-HR", "eng-GB" ),
            ),
            array(
                $spiUrlAliases2,
                "ger-DE",
                array( "ger-DE", "eng-GB", "cro-HR" ),
            ),
            array(
                $spiUrlAliases3,
                "ger-DE",
                array( "cro-HR" ),
            ),
            array(
                $spiUrlAliases3,
                "cro-HR",
                array( "eng-GB" ),
            ),
            array(
                $spiUrlAliases3,
                "ger-DE",
                array( "cro-HR", "eng-GB" ),
            ),
            array(
                $spiUrlAliases3,
                "eng-GB",
                array( "cro-HR", "ger-DE" ),
            ),
            array(
                $spiUrlAliases3,
                "ger-DE",
                array( "eng-GB", "cro-HR" ),
            ),
            array(
                $spiUrlAliases3,
                "cro-HR",
                array( "eng-GB", "ger-DE" ),
            ),
            array(
                $spiUrlAliases3,
                "cro-HR",
                array( "ger-DE", "eng-GB" ),
            ),
            array(
                $spiUrlAliases3,
                "eng-GB",
                array( "ger-DE", "cro-HR" ),
            ),
        );
    }

    /**
     * Test for the listLocationAliases() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::listLocationAliases
     * @dataProvider providerForTestListAutogeneratedLocationAliasesWithLanguageCodeEmpty
     */
    public function testListAutogeneratedLocationAliasesWithLanguageCodeEmpty(
        $spiUrlAliases,
        $languageCode,
        $prioritizedLanguageCodes
    )
    {
        $urlAliasService = $this->getMockedUrlAliasService();
        $configuration = array(
            "prioritizedLanguageList" => $prioritizedLanguageCodes,
            "showAllTranslations" => false,
        );
        $this->setConfiguration( $urlAliasService, $configuration );
        $urlAliasHandler = $this->getUrlAliasHandlerMock();
        $urlAliasHandler->expects(
            $this->once()
        )->method(
            "listURLAliasesForLocation"
        )->with(
            $this->equalTo( 42 ),
            $this->equalTo( false )
        )->will(
            $this->returnValue( $spiUrlAliases )
        );

        $location = $this->getLocationStub();
        $urlAliases = $urlAliasService->listLocationAliases( $location, false, $languageCode );

        self::assertEmpty( $urlAliases );
    }

    public function providerForTestListAutogeneratedLocationAliasesMultipleLanguagesPath()
    {
        $spiUrlAliases = array(
            new SPIUrlAlias(
                array(
                    "pathData" => array(
                        array(
                            "always-available" => false,
                            "translations" => array(
                                "cro-HR" => "jedan",
                                "eng-GB" => "jedan",
                            )
                        ),
                        array(
                            "always-available" => false,
                            "translations" => array(
                                "eng-GB" => "dva",
                                "ger-DE" => "dva",
                            )
                        )
                    ),
                    "languageCodes" => array( "eng-GB", "ger-DE" ),
                    "alwaysAvailable" => false,
                )
            ),
        );

        return array(
            array(
                $spiUrlAliases,
                array( "cro-HR", "ger-DE" ),
                array(
                    "jedan/dva",
                ),
            ),
            array(
                $spiUrlAliases,
                array( "ger-DE", "cro-HR" ),
                array(
                    "jedan/dva",
                ),
            ),
            array(
                $spiUrlAliases,
                array( "eng-GB" ),
                array(
                    "jedan/dva",
                ),
            ),
            array(
                $spiUrlAliases,
                array( "eng-GB", "ger-DE", "cro-HR" ),
                array(
                    "jedan/dva",
                ),
            ),
        );
    }

    /**
     * Test for the listLocationAliases() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::listLocationAliases
     * @dataProvider providerForTestListAutogeneratedLocationAliasesMultipleLanguagesPath
     */
    public function testListAutogeneratedLocationAliasesMultipleLanguagesPath( $spiUrlAliases, $prioritizedLanguageCodes, $paths )
    {
        $urlAliasService = $this->getMockedUrlAliasService();
        $configuration = array(
            "prioritizedLanguageList" => $prioritizedLanguageCodes,
            "showAllTranslations" => false,
        );
        $this->setConfiguration( $urlAliasService, $configuration );
        $urlAliasHandler = $this->getUrlAliasHandlerMock();
        $urlAliasHandler->expects(
            $this->once()
        )->method(
            "listURLAliasesForLocation"
        )->with(
            $this->equalTo( 42 ),
            $this->equalTo( false )
        )->will(
            $this->returnValue( $spiUrlAliases )
        );

        $location = $this->getLocationStub();
        $urlAliases = $urlAliasService->listLocationAliases( $location, false, null );

        self::assertEquals(
            count( $paths ),
            count( $urlAliases )
        );

        foreach ( $urlAliases as $index => $urlAlias )
        {
            self::assertEquals(
                $paths[$index],
                $urlAlias->path
            );
        }
    }

    public function providerForTestListAutogeneratedLocationAliasesMultipleLanguagesEmpty()
    {
        $spiUrlAliases = array(
            new SPIUrlAlias(
                array(
                    "pathData" => array(
                        array(
                            "always-available" => false,
                            "translations" => array(
                                "cro-HR" => "jedan",
                                "eng-GB" => "jedan",
                            )
                        ),
                        array(
                            "always-available" => false,
                            "translations" => array(
                                "eng-GB" => "dva",
                                "ger-DE" => "dva",
                            )
                        )
                    ),
                    "languageCodes" => array( "eng-GB", "ger-DE" ),
                    "alwaysAvailable" => false,
                )
            ),
        );

        return array(
            array(
                $spiUrlAliases,
                array( "cro-HR" ),
            ),
            array(
                $spiUrlAliases,
                array( "ger-DE" ),
            ),
        );
    }

    /**
     * Test for the listLocationAliases() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::listLocationAliases
     * @dataProvider providerForTestListAutogeneratedLocationAliasesMultipleLanguagesEmpty
     */
    public function testListAutogeneratedLocationAliasesMultipleLanguagesEmpty( $spiUrlAliases, $prioritizedLanguageCodes )
    {
        $urlAliasService = $this->getMockedUrlAliasService();
        $configuration = array(
            "prioritizedLanguageList" => $prioritizedLanguageCodes,
            "showAllTranslations" => false,
        );
        $this->setConfiguration( $urlAliasService, $configuration );
        $urlAliasHandler = $this->getUrlAliasHandlerMock();
        $urlAliasHandler->expects(
            $this->once()
        )->method(
            "listURLAliasesForLocation"
        )->with(
            $this->equalTo( 42 ),
            $this->equalTo( false )
        )->will(
            $this->returnValue( $spiUrlAliases )
        );

        $location = $this->getLocationStub();
        $urlAliases = $urlAliasService->listLocationAliases( $location, false, null );

        self::assertEmpty( $urlAliases );
    }

    public function providerForTestListAutogeneratedLocationAliasesWithLanguageCodeMultipleLanguagesPath()
    {
        $spiUrlAliases = array(
            new SPIUrlAlias(
                array(
                    "pathData" => array(
                        array(
                            "always-available" => false,
                            "translations" => array(
                                "cro-HR" => "jedan",
                                "eng-GB" => "jedan",
                            )
                        ),
                        array(
                            "always-available" => false,
                            "translations" => array(
                                "eng-GB" => "dva",
                                "ger-DE" => "dva",
                            )
                        )
                    ),
                    "languageCodes" => array( "eng-GB", "ger-DE" ),
                    "alwaysAvailable" => false,
                )
            ),
        );

        return array(
            array(
                $spiUrlAliases,
                "ger-DE",
                array( "cro-HR", "ger-DE" ),
                array(
                    "jedan/dva",
                ),
            ),
            array(
                $spiUrlAliases,
                "ger-DE",
                array( "ger-DE", "cro-HR" ),
                array(
                    "jedan/dva",
                ),
            ),
            array(
                $spiUrlAliases,
                "eng-GB",
                array( "eng-GB" ),
                array(
                    "jedan/dva",
                ),
            ),
            array(
                $spiUrlAliases,
                "eng-GB",
                array( "eng-GB", "ger-DE", "cro-HR" ),
                array(
                    "jedan/dva",
                ),
            ),
        );
    }

    /**
     * Test for the listLocationAliases() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::listLocationAliases
     * @dataProvider providerForTestListAutogeneratedLocationAliasesWithLanguageCodeMultipleLanguagesPath
     */
    public function testListAutogeneratedLocationAliasesWithLanguageCodeMultipleLanguagesPath(
        $spiUrlAliases,
        $languageCode,
        $prioritizedLanguageCodes,
        $paths
    )
    {
        $urlAliasService = $this->getMockedUrlAliasService();
        $configuration = array(
            "prioritizedLanguageList" => $prioritizedLanguageCodes,
            "showAllTranslations" => false,
        );
        $this->setConfiguration( $urlAliasService, $configuration );
        $urlAliasHandler = $this->getUrlAliasHandlerMock();
        $urlAliasHandler->expects(
            $this->once()
        )->method(
            "listURLAliasesForLocation"
        )->with(
            $this->equalTo( 42 ),
            $this->equalTo( false )
        )->will(
            $this->returnValue( $spiUrlAliases )
        );

        $location = $this->getLocationStub();
        $urlAliases = $urlAliasService->listLocationAliases( $location, false, $languageCode );

        self::assertEquals(
            count( $paths ),
            count( $urlAliases )
        );

        foreach ( $urlAliases as $index => $urlAlias )
        {
            self::assertEquals(
                $paths[$index],
                $urlAlias->path
            );
        }
    }

    public function providerForTestListAutogeneratedLocationAliasesWithLanguageCodeMultipleLanguagesEmpty()
    {
        $spiUrlAliases = array(
            new SPIUrlAlias(
                array(
                    "pathData" => array(
                        array(
                            "always-available" => false,
                            "translations" => array(
                                "cro-HR" => "jedan",
                                "eng-GB" => "jedan",
                            )
                        ),
                        array(
                            "always-available" => false,
                            "translations" => array(
                                "eng-GB" => "dva",
                                "ger-DE" => "dva",
                            )
                        )
                    ),
                    "languageCodes" => array( "eng-GB", "ger-DE" ),
                    "alwaysAvailable" => false,
                )
            ),
        );

        return array(
            array(
                $spiUrlAliases,
                "cro-HR",
                array( "cro-HR" ),
            ),
            array(
                $spiUrlAliases,
                "cro-HR",
                array( "cro-HR", "eng-GB" ),
            ),
            array(
                $spiUrlAliases,
                "cro-HR",
                array( "ger-DE" ),
            ),
            array(
                $spiUrlAliases,
                "cro-HR",
                array( "cro-HR", "eng-GB", "ger-DE" ),
            ),
        );
    }

    /**
     * Test for the listLocationAliases() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::listLocationAliases
     * @dataProvider providerForTestListAutogeneratedLocationAliasesWithLanguageCodeMultipleLanguagesEmpty
     */
    public function testListAutogeneratedLocationAliasesWithLanguageCodeMultipleLanguagesEmpty(
        $spiUrlAliases,
        $languageCode,
        $prioritizedLanguageCodes
    )
    {
        $urlAliasService = $this->getMockedUrlAliasService();
        $configuration = array(
            "prioritizedLanguageList" => $prioritizedLanguageCodes,
            "showAllTranslations" => false,
        );
        $this->setConfiguration( $urlAliasService, $configuration );
        $urlAliasHandler = $this->getUrlAliasHandlerMock();
        $urlAliasHandler->expects(
            $this->once()
        )->method(
            "listURLAliasesForLocation"
        )->with(
            $this->equalTo( 42 ),
            $this->equalTo( false )
        )->will(
            $this->returnValue( $spiUrlAliases )
        );

        $location = $this->getLocationStub();
        $urlAliases = $urlAliasService->listLocationAliases( $location, false, $languageCode );

        self::assertEmpty( $urlAliases );
    }

    public function providerForTestListAutogeneratedLocationAliasesAlwaysAvailablePath()
    {
        $spiUrlAliases = array(
            new SPIUrlAlias(
                array(
                    "pathData" => array(
                        array(
                            "always-available" => false,
                            "translations" => array(
                                "cro-HR" => "jedan",
                                "eng-GB" => "one",
                            )
                        ),
                        array(
                            "always-available" => true,
                            "translations" => array(
                                "ger-DE" => "zwei",
                            )
                        )
                    ),
                    "languageCodes" => array( "ger-DE" ),
                    "alwaysAvailable" => true,
                )
            ),
        );

        return array(
            array(
                $spiUrlAliases,
                array( "cro-HR", "ger-DE" ),
                array(
                    "jedan/zwei",
                ),
            ),
            array(
                $spiUrlAliases,
                array( "ger-DE", "cro-HR" ),
                array(
                    "jedan/zwei",
                ),
            ),
            array(
                $spiUrlAliases,
                array( "eng-GB" ),
                array(
                    "one/zwei",
                ),
            ),
            array(
                $spiUrlAliases,
                array( "cro-HR", "eng-GB", "ger-DE" ),
                array(
                    "jedan/zwei",
                ),
            ),
            array(
                $spiUrlAliases,
                array( "eng-GB", "ger-DE", "cro-HR" ),
                array(
                    "one/zwei",
                ),
            ),
        );
    }

    /**
     * Test for the listLocationAliases() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::listLocationAliases
     * @dataProvider providerForTestListAutogeneratedLocationAliasesAlwaysAvailablePath
     */
    public function testListAutogeneratedLocationAliasesAlwaysAvailablePath(
        $spiUrlAliases,
        $prioritizedLanguageCodes,
        $paths
    )
    {
        $urlAliasService = $this->getMockedUrlAliasService();
        $configuration = array(
            "prioritizedLanguageList" => $prioritizedLanguageCodes,
            "showAllTranslations" => false,
        );
        $this->setConfiguration( $urlAliasService, $configuration );
        $urlAliasHandler = $this->getUrlAliasHandlerMock();
        $urlAliasHandler->expects(
            $this->once()
        )->method(
            "listURLAliasesForLocation"
        )->with(
            $this->equalTo( 42 ),
            $this->equalTo( false )
        )->will(
            $this->returnValue( $spiUrlAliases )
        );

        $location = $this->getLocationStub();
        $urlAliases = $urlAliasService->listLocationAliases( $location, false, null );

        self::assertEquals(
            count( $paths ),
            count( $urlAliases )
        );

        foreach ( $urlAliases as $index => $urlAlias )
        {
            self::assertEquals(
                $paths[$index],
                $urlAlias->path
            );
        }
    }

    public function providerForTestListAutogeneratedLocationAliasesWithLanguageCodeAlwaysAvailablePath()
    {
        $spiUrlAliases = array(
            new SPIUrlAlias(
                array(
                    "pathData" => array(
                        array(
                            "always-available" => false,
                            "translations" => array(
                                "cro-HR" => "jedan",
                                "eng-GB" => "one",
                            )
                        ),
                        array(
                            "always-available" => true,
                            "translations" => array(
                                "ger-DE" => "zwei",
                            )
                        )
                    ),
                    "languageCodes" => array( "ger-DE" ),
                    "alwaysAvailable" => true,
                )
            ),
        );

        return array(
            array(
                $spiUrlAliases,
                "ger-DE",
                array( "cro-HR", "ger-DE" ),
                array(
                    "jedan/zwei",
                ),
            ),
            array(
                $spiUrlAliases,
                "ger-DE",
                array( "ger-DE", "cro-HR" ),
                array(
                    "jedan/zwei",
                ),
            ),
        );
    }

    /**
     * Test for the listLocationAliases() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::listLocationAliases
     * @dataProvider providerForTestListAutogeneratedLocationAliasesWithLanguageCodeAlwaysAvailablePath
     */
    public function testListAutogeneratedLocationAliasesWithLanguageCodeAlwaysAvailablePath(
        $spiUrlAliases,
        $languageCode,
        $prioritizedLanguageCodes,
        $paths
    )
    {
        $urlAliasService = $this->getMockedUrlAliasService();
        $configuration = array(
            "prioritizedLanguageList" => $prioritizedLanguageCodes,
            "showAllTranslations" => false,
        );
        $this->setConfiguration( $urlAliasService, $configuration );
        $urlAliasHandler = $this->getUrlAliasHandlerMock();
        $urlAliasHandler->expects(
            $this->once()
        )->method(
            "listURLAliasesForLocation"
        )->with(
            $this->equalTo( 42 ),
            $this->equalTo( false )
        )->will(
            $this->returnValue( $spiUrlAliases )
        );

        $location = $this->getLocationStub();
        $urlAliases = $urlAliasService->listLocationAliases( $location, false, $languageCode );

        self::assertEquals(
            count( $paths ),
            count( $urlAliases )
        );

        foreach ( $urlAliases as $index => $urlAlias )
        {
            self::assertEquals(
                $paths[$index],
                $urlAlias->path
            );
        }
    }

    public function providerForTestListAutogeneratedLocationAliasesWithLanguageCodeAlwaysAvailableEmpty()
    {
        $spiUrlAliases = array(
            new SPIUrlAlias(
                array(
                    "pathData" => array(
                        array(
                            "always-available" => false,
                            "translations" => array(
                                "cro-HR" => "jedan",
                                "eng-GB" => "one",
                            )
                        ),
                        array(
                            "always-available" => true,
                            "translations" => array(
                                "ger-DE" => "zwei",
                            )
                        )
                    ),
                    "languageCodes" => array( "ger-DE" ),
                    "alwaysAvailable" => true,
                )
            ),
        );

        return array(
            array(
                $spiUrlAliases,
                "eng-GB",
                array( "eng-GB" ),
            ),
            array(
                $spiUrlAliases,
                "eng-GB",
                array( "cro-HR", "eng-GB", "ger-DE" ),
            ),
            array(
                $spiUrlAliases,
                "eng-GB",
                array( "eng-GB", "ger-DE", "cro-HR" ),
            ),
        );
    }

    /**
     * Test for the listLocationAliases() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::listLocationAliases
     * @dataProvider providerForTestListAutogeneratedLocationAliasesWithLanguageCodeAlwaysAvailableEmpty
     */
    public function testListAutogeneratedLocationAliasesWithLanguageCodeAlwaysAvailableEmpty(
        $spiUrlAliases,
        $languageCode,
        $prioritizedLanguageCodes
    )
    {
        $urlAliasService = $this->getMockedUrlAliasService();
        $configuration = array(
            "prioritizedLanguageList" => $prioritizedLanguageCodes,
            "showAllTranslations" => false,
        );
        $this->setConfiguration( $urlAliasService, $configuration );
        $urlAliasHandler = $this->getUrlAliasHandlerMock();
        $urlAliasHandler->expects(
            $this->once()
        )->method(
            "listURLAliasesForLocation"
        )->with(
            $this->equalTo( 42 ),
            $this->equalTo( false )
        )->will(
            $this->returnValue( $spiUrlAliases )
        );

        $location = $this->getLocationStub();
        $urlAliases = $urlAliasService->listLocationAliases( $location, false, $languageCode );

        self::assertEmpty( $urlAliases );
    }

    public function providerForTestReverseLookup()
    {
        return $this->providerForTestListAutogeneratedLocationAliasesPath();
    }

    /**
     * Test for the reverseLookup() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::reverseLookup
     * @dataProvider providerForTestReverseLookup
     */
    public function testReverseLookupPath( $spiUrlAliases, $prioritizedLanguageCodes, $paths, $reverseLookupLanguageCode )
    {
        $urlAliasService = $this->getMockedUrlAliasService();
        $configuration = array(
            "prioritizedLanguageList" => $prioritizedLanguageCodes,
            "showAllTranslations" => false,
        );
        $this->setConfiguration( $urlAliasService, $configuration );
        $urlAliasHandler = $this->getUrlAliasHandlerMock();
        $urlAliasHandler->expects(
            $this->once()
        )->method(
            "listURLAliasesForLocation"
        )->with(
            $this->equalTo( 42 ),
            $this->equalTo( false )
        )->will(
            $this->returnValue( $spiUrlAliases )
        );

        $location = $this->getLocationStub();
        $urlAlias = $urlAliasService->reverseLookup( $location, null );

        if ( $urlAlias->languageCodes === array( $reverseLookupLanguageCode ) )
        {
            self::assertEquals(
                $paths[$reverseLookupLanguageCode],
                $urlAlias->path
            );
        }
        else
        {
            self::fail();
        }
    }

    public function providerForTestReverseLookupAlwaysAvailablePath()
    {
        return $this->providerForTestListAutogeneratedLocationAliasesAlwaysAvailablePath();
    }

    /**
     * Test for the reverseLookup() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::reverseLookup
     * @dataProvider providerForTestReverseLookupAlwaysAvailablePath
     */
    public function testReverseLookupAlwaysAvailablePath(
        $spiUrlAliases,
        $prioritizedLanguageCodes,
        $paths
    )
    {
        $urlAliasService = $this->getMockedUrlAliasService();
        $configuration = array(
            "prioritizedLanguageList" => $prioritizedLanguageCodes,
            "showAllTranslations" => false,
        );
        $this->setConfiguration( $urlAliasService, $configuration );
        $urlAliasHandler = $this->getUrlAliasHandlerMock();
        $urlAliasHandler->expects(
            $this->once()
        )->method(
            "listURLAliasesForLocation"
        )->with(
            $this->equalTo( 42 ),
            $this->equalTo( false )
        )->will(
            $this->returnValue( $spiUrlAliases )
        );

        $location = $this->getLocationStub();
        $urlAlias = $urlAliasService->reverseLookup( $location, null );

        self::assertEquals(
            reset( $paths ),
            $urlAlias->path
        );
    }

    /**
     * Test for the reverseLookup() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::reverseLookup
     */
    public function testReverseLookup()
    {
        $urlAliasService = $this->repository->getURLAliasService();
        $configuration = array(
            "prioritizedLanguageList" => array(
                "eng-US"
            ),
            "showAllTranslations" => false,
        );
        $this->setConfiguration( $urlAliasService, $configuration );

        $location = $this->getLocationStub( 56 );
        $urlAlias = $urlAliasService->reverseLookup( $location );

        self::assertEquals(
            new UrlAlias(
                array(
                    "id" => "25-49a39d99a955d95aa5d636275656a07a",
                    "type" => UrlAlias::LOCATION,
                    "destination" => 56,
                    "path" => "Design/Plain-site",
                    "languageCodes" => array( "eng-US" ),
                    "alwaysAvailable" => false,
                    "isHistory" => false,
                    "isCustom" => false,
                    "forward" => false,
                )
            ),
            $urlAlias
        );
    }

    /**
     * Test for the reverseLookup() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::reverseLookup
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testReverseLookupThrowsNotFoundException()
    {
        $urlAliasService = $this->repository->getURLAliasService();
        $configuration = array(
            "prioritizedLanguageList" => array(
                "ger-DE"
            ),
            "showAllTranslations" => false,
        );
        $this->setConfiguration( $urlAliasService, $configuration );

        $location = $this->getLocationStub( 56 );
        $urlAliasService->reverseLookup( $location );
    }







    /**
     * Test for the createUrlAlias() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::createUrlAlias
     * @group cc
     */
    public function testCreateUrlAlias()
    {
        $urlAliasService = $this->repository->getURLAliasService();

        $location = $this->getLocationStub( 56 );
        $urlAlias = $urlAliasService->createUrlAlias( $location, "my/custom/alias", "eng-GB", false, false );

        self::assertEquals(
            new UrlAlias(
                array(
                    "id" => "42-724874d1be77f450a09b305fc1534afb",
                    "type" => UrlAlias::LOCATION,
                    "destination" => 56,
                    "path" => "my/custom/alias",
                    "languageCodes" => array( "eng-GB" ),
                    "alwaysAvailable" => false,
                    "isHistory" => false,
                    "isCustom" => true,
                    "forward" => false,
                )
            ),
            $urlAlias
        );
        self::assertEquals(
            $urlAlias,
            $urlAliasService->lookup( "my/custom/alias" )
        );
    }

    /**
     * Test for the createUrlAlias() method.
     *
     * @covers \eZ\Publish\Core\Repository\ContentService::createUrlAlias
     * @group cc
     */
    public function testCreateUrlAliasVariation()
    {
        $urlAliasService = $this->repository->getURLAliasService();

        $location = $this->getLocationStub( 56 );
        $urlAlias = $urlAliasService->createUrlAlias( $location, "my/custom/alias", "eng-GB", true, true );

        self::assertEquals(
            new UrlAlias(
                array(
                    "id" => "42-724874d1be77f450a09b305fc1534afb",
                    "type" => UrlAlias::LOCATION,
                    "destination" => 56,
                    "path" => "my/custom/alias",
                    "languageCodes" => array( "eng-GB" ),
                    "alwaysAvailable" => true,
                    "isHistory" => false,
                    "isCustom" => true,
                    "forward" => true,
                )
            ),
            $urlAlias
        );
        self::assertEquals(
            $urlAlias,
            $urlAliasService->lookup( "my/custom/alias" )
        );
    }
























    /**
     * @param int $id
     *
     * @return \eZ\Publish\Core\Repository\Values\Content\Location
     */
    protected function getLocationStub( $id = 42 )
    {
        return new Location( array( "id" => $id ) );
    }

    protected function getMockedUrlAliasService()
    {
        $urlAliasService = $this->repository->getURLAliasService();

        $refObject = new \ReflectionObject( $urlAliasService );
        $refProperty = $refObject->getProperty( 'persistenceHandler' );
        $refProperty->setAccessible( true );
        $refProperty->setValue(
            $urlAliasService,
            $this->getPersistenceHandlerMock()
        );

        return $urlAliasService;
    }

    protected function setConfiguration( $urlAliasService, array $configuration )
    {
        $refObject = new \ReflectionObject( $urlAliasService );
        $refProperty = $refObject->getProperty( 'settings' );
        $refProperty->setAccessible( true );
        $refProperty->setValue(
            $urlAliasService,
            $configuration
        );
    }

    /**
     * UrlAlias Handler mock
     *
     * @var \eZ\Publish\SPI\Persistence\Content\UrlAlias\Handler|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $urlAliasHandlerMock;

    /**
     * Returns a UrlAlias Handler mock
     *
     * @return \eZ\Publish\SPI\Persistence\Content\UrlAlias\Handler|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getUrlAliasHandlerMock()
    {
        if ( !isset( $this->urlAliasHandlerMock ) )
        {
            $this->urlAliasHandlerMock = $this->getMock(
                "eZ\\Publish\\SPI\\Persistence\\Content\\UrlAlias\\Handler",
                array(),
                array(),
                '',
                false
            );
        }
        return $this->urlAliasHandlerMock;
    }

    /**
     * Persistence Handler mock
     *
     * @var \eZ\Publish\SPI\Persistence\Handler|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $persistenceHandlerMock;

    /**
     * Returns a persistence Handler mock
     *
     * @return \eZ\Publish\SPI\Persistence\Handler|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getPersistenceHandlerMock()
    {
        if ( !isset( $this->persistenceHandlerMock ) )
        {
            $this->persistenceHandlerMock = $this->getMock(
                "eZ\\Publish\\SPI\\Persistence\\Handler",
                array(),
                array(),
                '',
                false
            );
            $this->persistenceHandlerMock->expects(
                $this->any()
            )->method(
                "urlAliasHandler"
            )->will(
                $this->returnValue(
                    $this->getUrlAliasHandlerMock()
                )
            );
        }

        return $this->persistenceHandlerMock;
    }

    /**
     * Returns the content service to test with $methods mocked
     *
     * @param string[] $methods
     * @return \eZ\Publish\Core\Repository\ContentService|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getPartlyMockedService( array $methods = array() )
    {
        return $this->getMock(
            "eZ\\Publish\\Core\\Repository\\UrlAliasService",
            $methods,
            array(
                $this->getMock( 'eZ\\Publish\\API\\Repository\\Repository' ),
                $this->getPersistenceHandlerMock()
            )
        );
    }
}
