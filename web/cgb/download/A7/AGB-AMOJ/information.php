<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/monopoly.php");

	$chars = array(
		"あ", "い", "う", "え", "お", "か", "き", "く",
		"け", "こ", "さ", "し", "す", "せ", "そ", "た",
		"ち", "つ", "て", "と", "な", "に", "ぬ", "ね",
		"の", "は", "ひ", "ふ", "へ", "ほ", "ま", "み",
		"む", "め", "も", "や", "ゆ", "よ", "ら", "り",
		"る", "れ", "ろ", "わ", "を", "ん", "ゃ", "ゅ",
		"ょ", "。", "、", "が", "ぎ", "ぐ", "げ", "ご",
		"ざ", "じ", "ず", "ぜ", "ぞ", "だ", "ぢ", "づ",
		"で", "ど", "ば", "び", "ぶ", "べ", "ぼ", "ぱ",
		"ぴ", "ぷ", "ぺ", "ぽ", "ア", "イ", "ウ", "エ",
		"オ", "カ", "キ", "ク", "ケ", "コ", "サ", "シ",
		"ス", "セ", "ソ", "タ", "チ", "ツ", "テ", "ト",
		"ナ", "ニ", "ヌ", "ネ", "ノ", "ハ", "ヒ", "フ",
		"ヘ", "ホ", "マ", "ミ", "ム", "メ", "モ", "ヤ",
		"ユ", "ヨ", "ラ", "リ", "ル", "レ", "ロ", "ワ",
		"ヲ", "ン", "ャ", "ュ", "ョ", "ー", "！", "ガ",
		"ギ", "グ", "ゲ", "ゴ", "ザ", "ジ", "ズ", "ゼ",
		"ゾ", "ダ", "ヂ", "ヅ", "デ", "ド", "バ", "ビ",
		"ブ", "ベ", "ボ", "パ", "ピ", "プ", "ペ", "ポ",
		"＄", "０", "１", "２", "３", "４", "５", "６",
		"７", "８", "９", "　", "ァ", "ィ", "ゥ", "ェ",
		"ォ", "ッ", "？", "ヴ", "っ", "家", "軒", "抵",
		"当", "価", "格", "建", "設", "水", "道", "費",
		"抖", "鉄", "会", "社", "電", "力", "地", "中",
		"海", "公", "井", "通", "ぁ", "ぃ", "ぅ", "ぇ",
		"ぉ", "゛", "゜", "枚", "倍", "Ａ", "Ｂ", "Ｃ",
		"Ｄ", "Ｅ", "Ｆ", "Ｇ", "Ｈ", "Ｉ", "Ｊ", "Ｋ",
		"Ｌ", "Ｍ", "Ｎ", "Ｏ", "Ｐ", "Ｑ", "Ｒ", "Ｓ",
		"Ｔ", "Ｕ", "Ｖ", "Ｗ", "Ｘ", "Ｙ", "Ｚ", "％",
		"：", "⋯", "♪", "♥", "～", "男", "女", "位",
		"才" //, "入", "口", "交", "渉" // these last four exist in the game's font, but are skipped when writing to the screen.
	);

	$palettes = array(
		"0" => 9,
		"1" => 11,
		"2" => 14,
		"3" => 13,
		"4" => 10, // this is not blue! i can't tell what it is, but it's not blue!
		"5" => 15,
		"6" => 12,
		"7" => 8
	);

	$db = connectMySQL();
	$stmt = $db->prepare("select text from amoj_news where timestamp <= current_timestamp() order by id desc limit 1");
	$stmt->execute();
	$result = fancy_get_result($stmt);

	if (sizeof($result) != 0) {
		$text = explode("\n", $result[0]["text"], 65);
		$palette = 8;
		for ($i = 0; $i < 64; $i++) {
			if ($i >= sizeof($text)) {
				echo str_repeat("\xA3\0", 19);
			} else {
				$line = $text[$i];
				$length = 0;
				for ($j = 0; $j < mb_strlen($line); $j++) {
					if (mb_substr($line, $j, 3) === "\x1B[3" && mb_substr($line, $j + 4, 1) === "m" && array_key_exists($palettes, mb_substr($line, $j + 3, 1))) {
						$palette = $palettes[mb_substr($line, $j + 3, 1)];
						$j += 4;
						continue;
					}
					$char = mb_substr($line, $j, 1);
					if (0x20 <= ord($char) && ord($char) <= 0x7E) {
						echo pack("C", ord($char) - 0x20);
						echo pack("C", $palette * 16 + 2);
						$length++;
					} else {
						$ord = array_search($char, $chars, true);
						if ($ord !== false) {
							echo pack("C", $ord);
							echo pack("C", $palette * 16);
							$length++;
						}
					}
					if ($length == 19) {
						break;
					}
				}
				echo str_repeat("\xA3\0", 19 - $length);
			}
			echo $i == 63 ? "\xFF\0" : "\xFE\0";
		}
	}
?>
