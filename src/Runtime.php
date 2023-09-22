<?php

declare(strict_types=1);

namespace Akondas;

class Runtime
{
    public function totalMemory(): int
    {
        $iniLimit = ini_get('memory_limit');
        if ($iniLimit !== '-1' && $iniLimit !== '') {
            return $this->getMemoryFromIniString($iniLimit);
        }

        $systemMemory = $this->getMemoryFromSystem();
        if ($systemMemory !== false) {
            return (int) $systemMemory * 1024;
        }

        throw new \RuntimeException('No possibility to determine memory limits');
    }

    public function freeMemory(): int
    {
        return $this->totalMemory() - memory_get_usage();
    }

    public function availableProcessors(): int
    {
        try {
            $cores = exec('nproc');
        } catch (\Throwable $e) {
            $cores = false;
        }

        if (is_numeric($cores)) {
            return (int) $cores;
        }

        throw new \RuntimeException('No possibility to determine available processors');
    }

    private function getMemoryFromIniString(string $iniLimit): int
    {
        $unit = strtolower(substr($iniLimit, -1, 1));
        $units = [1 => 'k', 'm', 'g'];
        if (in_array($unit, $units, true)) {
            /** @var int $exponent */
            $exponent = array_search($unit, $units, true);

            return (int) $iniLimit * pow(1024, $exponent);
        }

        return (int) $iniLimit;
    }

    /**
     * @return string|bool
     */
    private function getMemoryFromSystem()
    {
        try {
            return exec('awk \'/MemTotal/ {print $2}\' /proc/meminfo');
        } catch (\Throwable $e) {
            return false;
        }
    }
}
