<?php declare(strict_types=1);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\TextUI\XmlConfiguration;

use function getcwd;
use function is_dir;
use function is_file;
use function realpath;
use PHPUnit\TextUI\CliArguments\Configuration;

/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */
final class ConfigurationFileFinder
{
    public function find(Configuration $configuration): string|false
    {
        $useDefaultConfiguration = $configuration->useDefaultConfiguration();

        if ($configuration->hasConfigurationFile()) {
            if (is_dir($configuration->configurationFile())) {
                $candidate = $this->configurationFileInDirectory($configuration->configurationFile());

                if ($candidate) {
                    return $candidate;
                }

                return false;
            }

            return $configuration->configurationFile();
        }

        if ($useDefaultConfiguration) {
            $candidate = $this->configurationFileInDirectory(getcwd());

            if ($candidate) {
                return $candidate;
            }
        }

        return false;
    }

    private function configurationFileInDirectory(string $directory): string|false
    {
        $candidates = [
            $directory . '/phpunit.xml',
            $directory . '/phpunit.dist.xml',
            $directory . '/phpunit.xml.dist',
        ];

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return realpath($candidate);
            }
        }

        return false;
    }
}
