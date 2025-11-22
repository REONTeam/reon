<?php
ini_set('log_errors', 1);
error_log('BXT_DEBUG_TRADE_CORNER_LEG_FILE_LOADED account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none'));
// SPDX-License-Identifier: MIT

require_once(CORE_PATH . "/database.php");
require_once(CORE_PATH . "/pokemon/func.php");
require_once(__DIR__ . "/../../scripts/bxt_legality_check.php");
require_once(__DIR__ . "/../../scripts/bxt_legality_policy.php");
require_once(CORE_PATH . "/pokemon/trade_corner.php");

/**
 * Trade Corner legality wrappers.
 *
 * process_trade_request() itself is defined in trade_corner.php and already:
 *  - decodes the upload blob into structured fields
 *  - populates bxt_exchange including *_decode mirror columns
 *  - runs Pokémon legality checks and banned-word filters
 *
 * For the exchange entry endpoints (10.entry.php), including this file is
 * sufficient because it pulls in trade_corner.php.
 *
 * The only extra wrapper we currently expose here is for the cancel flow,
 * which keeps naming consistent with the BT legality entrypoints.
 */

/**
 * Handle Trade Corner cancellation with legality wrapper name.
 *
 * The payload for cancel only contains IDs (trainer/secret/etc.) and does
 * not include Pokémon or free text, so no additional legality checks beyond
 * the decode+delete in process_cancel_request() are required.
 */


/**
 * Handle Trade Corner trade upload with legality wrapper name.
 * This is a thin wrapper around process_trade_request(), which already:
 *  - decodes the upload
 *  - runs PK2 legality checks
 *  - enforces policy
 *  - inserts into bxt_exchange
 */
function process_trade_request_legality($region, $request_data)
{
    process_trade_request($region, $request_data);
}

function process_cancel_request_legality($region, $request_data)
{
    // Delegate to the core implementation.
    process_cancel_request($region, $request_data);
}
