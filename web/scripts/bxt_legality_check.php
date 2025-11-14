<?php
require_once __DIR__ . '/bxt_legality_policy.php';

/**
 * Run the LegalityCheckerConsole against a raw Gen 2 pk2 / trade blob.
 *
 * @param string        $pkm_raw    Raw blob (binary string)
 * @param callable|null $logger     Optional logger(string $msg): void
 * @param int           $timeout_ms Unused (blocking run), kept for API stability
 *
 * @return array [bool $ok, array $details]
 */
function legality_check_pk2_bytes_with_details(string $pkm_raw, ?callable $logger = null, int $timeout_ms = 2500): array
{
    // Resolve legality checker binary:
    // 1) POKEMON_LEGALITY_BIN environment variable
    // 2) Fallback to repo-local path, relative to this file:
    //    reon/app/pokemon-legality/LegalityCheckerConsole/out/pokemon-legality
    $bin = getenv('POKEMON_LEGALITY_BIN');
    if ($bin === false || $bin === '') {
        $bin = realpath(__DIR__ . '/../../app/pokemon-legality/LegalityCheckerConsole/out/pokemon-legality');
    }

    if ($bin === false || !file_exists($bin)) {
        $msg = "bxt_legality_check: legality binary not found at {$bin}";
        error_log($msg);
        throw new RuntimeException($msg);
    }

    $len = strlen($pkm_raw);
    $hex = bin2hex($pkm_raw);

    if ($logger !== null) {
        $logger(sprintf('legality_debug: raw length = %d bytes', $len));
        $logger('legality_debug: raw hex = ' . $hex);
    } else {
        error_log(sprintf('legality_debug: raw length = %d bytes', $len));
        error_log('legality_debug: raw hex = ' . $hex);
    }

    // ★★★ DEBUG FILE OUTPUT REMOVED COMPLETELY ★★★

    // Child command: binary reads blob from STDIN ("-")
    $cmd = escapeshellarg($bin) . ' -';
    $descriptorSpec = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ];

    $pipes   = [];
    $process = proc_open($cmd, $descriptorSpec, $pipes, null, null);

    if (!is_resource($process)) {
        $msg = 'bxt_legality_check: failed to start legality checker process';
        error_log($msg);
        throw new RuntimeException($msg);
    }

    try {
        $written = fwrite($pipes[0], $pkm_raw);
        fflush($pipes[0]);
        fclose($pipes[0]);

        if ($logger !== null) {
            $logger(sprintf('legality_check: raw pk2 length = %d bytes', $len));
            $logger(sprintf('legality_check: wrote %d bytes to stdin', (int)$written));
        } else {
            error_log(sprintf('legality_check: raw pk2 length = %d bytes', $len));
            error_log(sprintf('legality_check: wrote %d bytes to stdin', (int)$written));
        }

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);

        fclose($pipes[1]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);

        if ($logger !== null) {
            $logger(sprintf('legality_check: exit=%d', $exitCode));
            if ($stdout !== false && $stdout !== '') {
                $logger('legality_check: stdout=' . trim($stdout));
            }
            if ($stderr !== false && $stderr !== '') {
                $logger('legality_check: stderr=' . trim($stderr));
            }
        } else {
            error_log(sprintf('legality_check: exit=%d', $exitCode));
            if ($stdout !== false && $stdout !== '') {
                error_log('legality_check: stdout=' . trim($stdout));
            }
            if ($stderr !== false && $stderr !== '') {
                error_log('legality_check: stderr=' . trim($stderr));
            }
        }

        $details = json_decode(trim($stdout), true);
        if (!is_array($details)) {
            $details = [
                'ok'  => ($exitCode === 0),
                'raw' => $stdout !== false ? trim($stdout) : '',
            ];
        }

        return [($exitCode === 0), $details];

    } catch (\Throwable $e) {
        try {
            if (is_resource($process)) {
                proc_terminate($process);
                proc_close($process);
            }
        } catch (\Throwable $ignored) {}

        if ($logger !== null) {
            $logger('legality_check: exception=' . $e->getMessage());
        } else {
            error_log('legality_check: exception=' . $e->getMessage());
        }

        throw $e;
    }
}

/**
 * Simple boolean wrapper used elsewhere in the repo.
 *
 * @param string        $pkm_raw Raw blob (binary string)
 * @param callable|null $logger
 *
 * @return bool True if legal, false otherwise (based on EXE exit code)
 */
function legality_check_pk2_bytes(string $pkm_raw, ?callable $logger = null): bool
{
    [$ok, $_] = legality_check_pk2_bytes_with_details($pkm_raw, $logger);
    return $ok;
}
