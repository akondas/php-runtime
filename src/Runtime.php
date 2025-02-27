<?php

declare(strict_types=1);

namespace Akondas;

final readonly class Runtime
{
    /**
     * @throws \RuntimeException
     */
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

    /**
     * @throws \RuntimeException
     */
    public function freeMemory(): int
    {
        return $this->totalMemory() - memory_get_usage();
    }

    /**
     * @throws \RuntimeException
     */
    public function availableProcessors(): int
    {
        try {
            $cores = exec('nproc');
        } catch (\Throwable) {
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

    private function getMemoryFromSystem(): bool|string
    {
        try {
            return exec('awk \'/MemTotal/ {print $2}\' /proc/meminfo');
        } catch (\Throwable) {
            return false;
        }
    }
}
