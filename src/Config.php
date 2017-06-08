<?php

namespace Choccybiccy\Smpp;

use Illuminate\Config\Repository;
use Symfony\Component\Yaml\Yaml;

class Config extends Repository
{
    /**
     * @param string $yamlPath
     * @return static
     */
    public static function loadFromYaml($yamlPath)
    {
        if (!file_exists($yamlPath)) {
            throw new \RuntimeException('Cannot find config file as ' . $yamlPath);
        }
        return new static(Yaml::parse(file_get_contents($yamlPath)));
    }
}
