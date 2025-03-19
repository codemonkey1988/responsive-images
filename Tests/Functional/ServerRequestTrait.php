<?php

declare(strict_types=1);

/*
 * This file is part of the "responsive_images" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\ResponsiveImages\Tests\Functional;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\NormalizedParams;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\TypoScript\AST\Node\RootNode;
use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;

trait ServerRequestTrait
{
    protected function buildFakeServerRequest(
        string $url = 'https://t3ext-responsive-images.ddev.site',
        string $method = 'GET'
    ): ServerRequestInterface {
        $requestUrlParts = parse_url($url);
        $docRoot = $this->instancePath;

        $serverParams = [
            'DOCUMENT_ROOT' => $docRoot,
            'HTTP_USER_AGENT' => 'TYPO3 Functional Test Request',
            'HTTP_HOST' => $requestUrlParts['host'] ?? 'localhost',
            'SERVER_NAME' => $requestUrlParts['host'] ?? 'localhost',
            'SERVER_ADDR' => '127.0.0.1',
            'REMOTE_ADDR' => '127.0.0.1',
            'SCRIPT_NAME' => '/index.php',
            'PHP_SELF' => '/index.php',
            'SCRIPT_FILENAME' => $docRoot . '/index.php',
            'PATH_TRANSLATED' => $docRoot . '/index.php',
            'QUERY_STRING' => $requestUrlParts['query'] ?? '',
            'REQUEST_URI' => ($requestUrlParts['path'] ?? '') . (isset($requestUrlParts['query']) ? '?' . $requestUrlParts['query'] : ''),
            'REQUEST_METHOD' => $method,
        ];
        // Define HTTPS and server port
        if (isset($requestUrlParts['scheme'])) {
            if ($requestUrlParts['scheme'] === 'https') {
                $serverParams['HTTPS'] = 'on';
                $serverParams['SERVER_PORT'] = '443';
            } else {
                $serverParams['SERVER_PORT'] = '80';
            }
        }

        // Define a port if used in the URL
        if (isset($requestUrlParts['port'])) {
            $serverParams['SERVER_PORT'] = $requestUrlParts['port'];
        }

        $typo3Version = new Typo3Version();
        if ($typo3Version->getMajorVersion() > 12) {
            // @phpstan-ignore-next-line
            $typoScript = new FrontendTypoScript(new RootNode(), [], [], []);
        } else {
            // @phpstan-ignore-next-line
            $typoScript = new FrontendTypoScript(new RootNode(), []);
        }

        $typoScript->setSetupArray($this->getTypoScriptSetup());
        // The method was introduced in v13.
        if (method_exists($typoScript, 'setConfigArray')) {
            $typoScript->setConfigArray([]);
        }

        // set up normalizedParams
        $request = new ServerRequest($url, $method, null, [], $serverParams);
        return $request
            ->withAttribute('normalizedParams', NormalizedParams::createFromRequest($request))
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE)
            ->withAttribute('frontend.typoscript', $typoScript);
    }

    /**
     * @return array<string, mixed>
     */
    private function getTypoScriptSetup(): array
    {
        $defaultVariant = [
            'croppingVariantKey' => 'default',
            'providedImageSizes.' => [
                '10.' => [
                    'width' => '1000',
                ],
            ],
            'sizes.' => [
                '10.' => [
                    'viewportMediaCondition' => '(min-width: 971px)',
                    'assumedImageWidth' => '585px',
                ],
                '20.' => [
                    'viewportMediaCondition' => '(min-width: 751px)',
                    'assumedImageWidth' => '485px',
                ],
                '30.' => [
                    'viewportMediaCondition' => '(min-width: 421px)',
                    'assumedImageWidth' => '375px',
                ],
                '40.' => [
                    'assumedImageWidth' => '420px',
                ],
            ],
        ];

        $largeVariant = [
            'croppingVariantKey' => 'large',
            'providedImageSizes.' => [
                '10.' => [
                    'width' => '1000',
                ],
            ],
            'sizes.' => [
                '10.' => [
                    'assumedImageWidth' => '100vw',
                ],
            ],
        ];

        return [
            'config.' => [],
            'plugin.' => [
                'tx_responsiveimages.' => [
                    'settings.' => [
                        'variants.' => [
                            'default.' => $defaultVariant,
                            'large.' => $largeVariant,
                        ],
                    ],
                ],
            ]
        ];
    }
}
