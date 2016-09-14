<?php

namespace Messenger\Model\Core;

class Config
{
    /**
     * @param array $configs
     * @throws \Exception
     */
    public function setConfigs(array $configs)
    {
        if (!is_writable(dirname($this->getConfigFilePath()))) {
            throw new \Exception('Config dir is not writable');
        }

        $configs = var_export($configs, true);

        file_put_contents($this->getConfigFilePath(), "<?php\nreturn {$configs};\n?>");
    }

    /**
     * @return array
     */
    public function getConfigs()
    {
        if (!is_file($this->getConfigFilePath())) {
            return [];
        }

        $configs = include $this->getConfigFilePath();

        return empty($configs) ? [] : $configs;
    }

    /**
     * @return string
     */
    private function getConfigFilePath()
    {
        $paths = new Paths();

        return $paths->getConfigFilePath();
    }
}