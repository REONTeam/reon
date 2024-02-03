<?php
// SPDX-License-Identifier: MIT
// This is a very small script that asks the GBC to send an Authentication header along with the data.
define('CORE_PATH', dirname(dirname(__DIR__)) . '/cgb');
require_once(CORE_PATH.'/core.php');
require_once(CORE_PATH.'/auth.php');

	doAuth();
    serveFileOrExecScript($_GET["name"], "upload");


/* (These docs credited to Háčky from Glitch City)
An authentication attempt begins by sending an HTTP GET request, to which the server responds with 401 Unauthorized and a WWW-Authenticate: GB00 name="…" header, where the name is an arbitrary 36-byte value encoded in Base64.

The game then sends another GET request, this time with the header Authorization: GB00 name="…", where the name is a concatenation of two separate Base64-encoded values: the first is the first 32 bytes from the WWW-Authenticate name, and the second is a 36-byte value determined by a byzantine procedure:

    The login password is appended to the 48-character Base64-encoded WWW-Authenticate name, and then this string is hashed with MD5.
    The 36-byte WWW-Authenticate value is split into two 18-byte values, the first containing all of the even-numbered bits of the original and the second containing all of the odd-numbered bits. The first byte of each two-byte pair fills the most significant bits of each output byte, and the second byte fills the least significant bits. These values are concatenated into a new 36-byte string.
    The login ID is appended to the MD5 hash from step 1. This string is padded with $FF until it is 35 bytes long, then a $00 is added to make it 36 bytes.
    The 36-byte strings produced in steps 2 and 3 are xor’d.
    But that would have been too simple, so then each byte has bits 0, 3, and 6 rotated into bits 3, 6, and 0.

The first step necessitates that the server retains users’ plaintext passwords in order to calculate arbitrary MD5 hashes from them—unless the value in the WWW-Authenticate header is predetermined, which would allow the hash to be precalculated, but would make the rest of this shell game even more pointless as a successful authentication attempt could be replayed.

If the Authorization header is valid, the server responds with 200 OK and a Gb-Auth-ID header which contains an arbitrary string. The game then sends its POST request and includes the same Gb-Auth-ID header.
*/
?>