<?php
// SPDX-License-Identifier: MIT

require_once(CORE_PATH . "/database.php");
require_once(CORE_PATH . "/pokemon/trade_corner.php");

// Region selector: default to 'e' (English) if not provided.
$region = isset($_GET['region']) ? strtolower($_GET['region']) : 'e';

// Limit selector with sane bounds.
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 100;
if ($limit <= 0) {
    $limit = 100;
}
if ($limit > 500) {
    $limit = 500;
}

// Fetch offers using pooled region logic.
// tradeCornerListOffers() already respects:
//   - trade_corner_enabled
//   - region_groups['trade_corner']
//   - game_region_map-based allowed regions
$rows = tradeCornerListOffers($region, $limit);

// Optional JSON output: ?json=1
if (isset($_GET['json']) && $_GET['json'] === '1') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($rows, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

// Simple HTML table for debugging / admin view.
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Trade Corner Offers (region <?= htmlspecialchars($region, ENT_QUOTES, 'UTF-8') ?>)</title>
    <style>
        body { font-family: sans-serif; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 4px 6px; font-size: 12px; }
        th { background: #eee; }
        .mono { font-family: monospace; }
    </style>
</head>
<body>
<h1>Trade Corner Offers (region <?= htmlspecialchars($region, ENT_QUOTES, 'UTF-8') ?>)</h1>
<p>Source regions (pooled via region_groups['trade_corner'] and game_region_map-based allowed regions) are internal to tradeCornerListOffers().</p>

<table>
    <thead>
        <tr>
            <th>game_region</th>
            <th>trainer_id</th>
            <th>secret_id</th>
            <th>offer_species</th>
            <th>offer_gender</th>
            <th>request_species</th>
            <th>request_gender</th>
            <th>player_name_decode</th>
            <th>player_region_decode</th>
            <th>player_zip_decode</th>
            <th>player_message_decode</th>
            <th>timestamp</th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($rows as $row): ?>
        <tr>
            <td class="mono"><?= htmlspecialchars($row['game_region'], ENT_QUOTES, 'UTF-8') ?></td>
            <td class="mono"><?= htmlspecialchars($row['trainer_id'], ENT_QUOTES, 'UTF-8') ?></td>
            <td class="mono"><?= htmlspecialchars($row['secret_id'], ENT_QUOTES, 'UTF-8') ?></td>
            <td class="mono"><?= htmlspecialchars($row['offer_species_decode'], ENT_QUOTES, 'UTF-8') ?></td>
            <td class="mono"><?= htmlspecialchars($row['offer_gender_decode'], ENT_QUOTES, 'UTF-8') ?></td>
            <td class="mono"><?= htmlspecialchars($row['request_species_decode'], ENT_QUOTES, 'UTF-8') ?></td>
            <td class="mono"><?= htmlspecialchars($row['request_gender_decode'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($row['player_name_decode'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= isset($row['player_region_decode']) ? htmlspecialchars($row['player_region_decode'], ENT_QUOTES, 'UTF-8') : '' ?></td>
            <td><?= isset($row['player_zip_decode']) ? htmlspecialchars($row['player_zip_decode'], ENT_QUOTES, 'UTF-8') : '' ?></td>
            <td><?= isset($row['player_message_decode']) ? htmlspecialchars($row['player_message_decode'], ENT_QUOTES, 'UTF-8') : '' ?></td>
            <td class="mono"><?= htmlspecialchars($row['timestamp'], ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
<?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
