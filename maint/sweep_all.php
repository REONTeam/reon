<?php
require_once(__DIR__ . '/../../web/scripts/bxt_legality_check.php');
require_once(__DIR__ . "/../../scripts/bxt_legality_policy.php");



$dsn  = getenv('REON_SWEEP_DSN') ?: 'mysql:host=127.0.0.1;dbname=reon;charset=utf8mb4';
$user = getenv('REON_SWEEP_USER') ?: 'reon';
$pass = getenv('REON_SWEEP_PASS') ?: 'password';

$BATTLE_TOWER_ALLOWED_CLASS_IDS = [47,22,23,24,30,32,36,37,38,40,41,43,44,48,50,52,54,27,58,49,59,65,56,45,20,57,25,29,33,34,39,53,60,62,28];
$banned = bxt_load_banned_words(__DIR__ . '/banned_words.txt');

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Throwable $e) {
    fwrite(STDERR, "DB error: " . $e->getMessage() . PHP_EOL);
    exit(1);
}

function table_has(PDO $pdo, string $t, array $cols): bool {
    $in = str_repeat('?,', count($cols)-1) . '?';
    $q = $pdo->prepare(
        "SELECT COUNT(*) FROM information_schema.columns " .
        "WHERE table_schema = DATABASE() AND table_name = ? AND column_name IN ($in)"
    );
    $q->execute(array_merge([$t], $cols));
    return ((int)$q->fetchColumn() === count($cols));
}

function region_table_to_encoding(string $table): string {
    $t = strtolower($table);
    if (str_starts_with($t, 'bxtj')) return 'jp';
    if (str_starts_with($t, 'bxte')) return 'en';
    if (str_starts_with($t, 'bxtk')) return 'en';
    if (str_starts_with($t, 'bxts')) return 'en';
    if (str_starts_with($t, 'bxtp')) return 'en';
    if (str_starts_with($t, 'bxtu')) return 'en';
    if (str_starts_with($t, 'bxt_')) return 'en';
    return 'en';
}

function sweep_exchange(PDO $pdo, string $t, array $banned) {
    if (!table_has($pdo, $t, ['id','pokemon','mail'])) {
        echo "Skip: $t
"; return;
    }
    $enc = region_table_to_encoding($t);
    $sel = $pdo->query("SELECT id, pokemon, mail FROM `$t`");
    $del = $pdo->prepare("DELETE FROM `$t` WHERE id=:id LIMIT 1");

    $sc = 0; $rm = 0;
    while ($r = $sel->fetch
        if (isset($r['num_trainers_defeated']) && (int)$r['num_trainers_defeated'] > 7) { $del->execute([':id'=>$r['id']]); echo "Deleted $t.".$r['id']." (num_trainers_defeated>7)\n"; continue; }
()) {
        $sc++;
        $illegal = false;
        try {
            [$ok, $det] = legality_check_pk2_bytes_with_details($r['pokemon']);
            if (!$ok) $illegal = true;
            else if ((int)($det['speciesId'] ?? 0) > 251) $illegal = true;
            else if (!empty($det['isEgg'])) $illegal = true;
            else if (!bxt_policy_allow_nickname($det, $banned)) $illegal = true;
            else if (!bxt_policy_allow_ot($det, $banned)) $illegal = true;
            else if (!bxt_policy_allow_mail_table($r['mail'] ?? '', $enc, $banned)) $illegal = true;
        } catch (Throwable $e) {
            $illegal = true;
        }
        if ($illegal) {
            $del->execute([':id' => $r['id']]);
            $rm++;
            echo "Deleted $t." . $r['id'] . "
";
        }
    }
    echo "$t scanned=$sc deleted=$rm
";
}

function sweep_bt_named(PDO $pdo, string $t, string $idcol, array $banned) {
    // Prefer player_name column if present; fall back to legacy `name`
    $col = 'player_name';
    if (!table_has($pdo, $t, [$idcol, $col])) {
        if (!table_has($pdo, $t, [$idcol,'name'])) {
            echo "Skip name-ban: $t
"; return;
        }
        $col = 'name';
    }

    $enc = region_table_to_encoding($t);
    $sel = $pdo->query("SELECT `$idcol` AS id, `$col` AS name FROM `$t`");
    $del = $pdo->prepare("DELETE FROM `$t` WHERE `$idcol`=:id LIMIT 1");
    $sc = 0; $rm = 0;
    while ($r = $sel->fetch()) {
        $sc++;
        $txt = bxt_decode_text_table((string)$r['name'], $enc);
        if ($txt !== '' && bxt_contains_banned($txt, $banned)) {
            $del->execute([':id' => $r['id']]);
            $rm++;
            echo "Deleted $t." . $r['id'] . " (banned name)
";
        }
    }
    echo "$t name-scan scanned=$sc deleted=$rm
";
}

function sweep_bt(PDO $pdo, string $t, string $idcol, array $banned, array $BATTLE_TOWER_ALLOWED_CLASS_IDS) {
    if (!table_has($pdo, $t, [$idcol,'pokemon1','pokemon2','pokemon3','level'])) {
        echo "Skip: $t
"; return;
    }
    $hasClass = table_has($pdo, $t, [$idcol,'class']);
    $cols = "`$idcol` AS id, `pokemon1`,`pokemon2`,`pokemon3`,`level`" . ($hasClass ? ", `class`" : "");
    $sel = $pdo->query("SELECT " . $cols . " FROM `$t`");
    $del = $pdo->prepare("DELETE FROM `$t` WHERE `$idcol`=:id LIMIT 1");

    $sc = 0; $rm = 0;
    while ($r = $sel->fetch()) {
        $sc++;
        $cap = battle_tower_cap((int)$r['level']);
        $illegal = false;
        $helds = [];
        $species = [];

        foreach (['pokemon1','pokemon2','pokemon3'] as $slot) {
            try {
                [$ok, $det] = legality_check_pk2_bytes_with_details($r[$slot]);
                if (!$ok) { $illegal = true; break; }
                if ((int)($det['speciesId'] ?? 0) > 251) { $illegal = true; break; }
                if (!empty($det['isEgg'])) { $illegal = true; break; }
                if (!bxt_policy_allow_nickname($det, $banned)) { $illegal = true; break; }
                $lvl = (int)($det['level'] ?? 0);
                if ($lvl > $cap) { $illegal = true; break; }
                $helds[] = (int)($det['heldItem'] ?? -1);
                $species[] = (int)($det['speciesId'] ?? 0);
            } catch (Throwable $e) {
                $illegal = true; break;
            }
        }

        // Trainer class whitelist for trainers and records
        if (!$illegal && (str_ends_with($t, '_battle_tower_trainers') || str_ends_with($t, '_battle_tower_records'))) {
            if ($hasClass && isset($r['class'])) {
                $cls = (int)$r['class'];
                if (!in_array($cls, $BATTLE_TOWER_ALLOWED_CLASS_IDS, true)) { $illegal = true; }
            } else {
                $illegal = true;
            }
        }

        // Unique held items (ignore 'no item')
        if (!$illegal) {
            $nonEmptyHelds = [];
            foreach ($helds as $h) {
                $h = (int)$h;
                if ($h > 0) $nonEmptyHelds[] = $h;
            }
            if (count($nonEmptyHelds) !== count(array_unique($nonEmptyHelds))) {
                $illegal = true;
            }
        }

        // Legendary restriction for low tiers
        if (!$illegal) {
            $idx = (int)$r['level'];
            if ($idx < 6) {
                $legendary = [150,151,249,250,251];
                foreach ($species as $sp) {
                    if (in_array($sp, $legendary, true)) { $illegal = true; break; }
                }
            }
        }

        if ($illegal) {
            $del->execute([':id' => $r['id']]);
            $rm++;
            echo "Deleted $t." . $r['id'] . "
";
        }
    }
    echo "$t scanned=$sc deleted=$rm
";
}

# GTC tables (if present)
foreach (['bxt_exchange'] as $t) {
    sweep_exchange($pdo, $t, $banned);
}

# Battle Tower tables across regions
foreach (['bxtj','bxte','bxtk','bxts','bxtp','bxtu'] as $prefix) {
    sweep_bt_named($pdo, "{$prefix}_battle_tower_leaders",  'id',  $banned);
    sweep_bt_named($pdo, "{$prefix}_battle_tower_trainers", 'no',  $banned);
    sweep_bt_named($pdo, "{$prefix}_battle_tower_records",  'id',  $banned);
    sweep_bt($pdo,       "{$prefix}_battle_tower_trainers", 'no',  $banned, $BATTLE_TOWER_ALLOWED_CLASS_IDS);
    sweep_bt($pdo,       "{$prefix}_battle_tower_records",  'id',  $banned, $BATTLE_TOWER_ALLOWED_CLASS_IDS);
}


# Ranking tables: enforce banned player_name
foreach (['bxtj_ranking','bxte_ranking','bxtf_ranking','bxtd_ranking','bxti_ranking','bxtp_ranking','bxts_ranking','bxtu_ranking'] as $t)
  sweep_ranking_table($pdo, $t, $banned);

echo "Sweep complete.
";

function sweep_ranking_table(PDO $pdo, string $t, array $banned) {
  try {
    $sel = $pdo->query("SELECT id, player_name FROM `$t`");
  } catch (Throwable $e) {
    echo "Skip ranking: $t (" . $e->getMessage() . ")\n";
    return;
  }
  $enc = region_table_to_encoding($t);
  $del = $pdo->prepare("DELETE FROM `$t` WHERE id = :id LIMIT 1");
  $sc = 0; $rm = 0;
  while ($r = $sel->fetch()) {
    $sc++;
    $decoded = bxt_decode_text_table((string)$r['player_name'], $enc);
    if ($decoded !== '' && bxt_contains_banned($decoded, $banned)) {
      $del->execute([':id' => $r['id']]);
      $rm++;
      echo "Deleted $t." . $r['id'] . " (banned player_name)\n";
    }
  }
  echo "$t ranking scanned=$sc deleted=$rm\n";
}
