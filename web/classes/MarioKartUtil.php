<?php

	require_once dirname(__DIR__)."/vendor/autoload.php";
	require_once("DBUtil.php");
	
	class MarioKartUtil {

		private static $instance;

		private final function  __construct() {
		}

		public static function getInstance() {
			if(!isset(self::$instance)) {
				self::$instance = new MarioKartUtil();
			}
			return self::$instance;
		}

        public function getString($region, $raw_text) {
            $char_table = [];
            switch ($region) {
                case "j":
                    $char_table = self::TableJP;
                    break;
            }
            $str = "";
            foreach(unpack("C*", $raw_text) as $b) {
                if ($b === 0) break;
                $str .= $char_table[$b];
            }
            return $str;
        }

        function convertTime(int $centiseconds): DateInterval {
            $totalSeconds = floor($centiseconds / 100);
            $remainingCentiseconds = $centiseconds % 100;
            
            $minutes = floor($totalSeconds / 60);
            $seconds = $totalSeconds % 60;
            
            $interval = new DateInterval('PT0S');
            $interval->i = $minutes;
            $interval->s = $seconds;
            $interval->f = $remainingCentiseconds / 100;
            
            return $interval;
        }

        private const TableJP =
        [
            '危','！','”','＃','＄','％','’','＆','（','）','＊','＋','，','ー','．','／', // 00-0F
            '０','１','２','３','４','５','６','７','８','９','：','；','＜','＝','＞','？',// 10-1F
            '＠','Ａ','Ｂ','Ｃ','Ｄ','Ｅ','Ｆ','Ｇ','Ｈ','Ｉ','Ｊ','Ｋ','Ｌ','Ｍ','Ｎ','Ｏ', // 20-2F
            'Ｐ','Ｑ','Ｒ','Ｓ','Ｔ','Ｕ','Ｖ','Ｗ','Ｘ','Ｙ','Ｚ','［','￥','］','＾','＿', // 30-3F
            '‘','ａ','ｂ','ｃ','ｄ','ｅ','ｆ','ｇ','ｈ','ｉ','ｊ','ｋ','ｌ','ｍ','ｎ','ｏ', // 40-4F
            'ｐ','ｑ','ｒ','ｓ','ｔ','ｕ','ｖ','ｗ','ｘ','ｙ','ｚ','｛','｜','｝','〜','　', // 50-5F
            'ぁ','あ','ぃ','い','ぅ','う','ぇ','え','ぉ','お','か','が','き','ぎ','く','ぐ', // 60-6F
            'け','げ','こ','ご','さ','ざ','し','じ','す','ず','せ','ぜ','そ','ぞ','た','だ', // 70-7F
            'ち','ぢ','っ','つ','づ','て','で','と','ど','な','に','ぬ','ね','の','は','ば', // 80-8F
            'ぱ','ひ','び','ぴ','ふ','ぶ','ぷ','へ','べ','ぺ','ほ','ぼ','ぽ','ま','み','む', // 90-9F
            'め','も','ゃ','や','ゅ','ゆ','ょ','よ','ら','り','る','れ','ろ','わ','を','ん', // A0-AF
            'ァ','ア','ィ','イ','ゥ','ウ','ェ','エ','ォ','オ','カ','ガ','キ','ギ','ク','グ', // B0-BF
            'ケ','ゲ','コ','ゴ','サ','ザ','シ','ジ','ス','ズ','セ','ゼ','ソ','ゾ','タ','ダ', // C0-CF
            'チ','ヂ','ッ','ツ','ヅ','テ','デ','ト','ド','ナ','ニ','ヌ','ネ','ノ','ハ','バ', // D0-DF
            'パ','ヒ','ビ','ピ','フ','ブ','プ','ヘ','ベ','ペ','ホ','ボ','ポ','マ','ミ','ム', // E0-EF
            'メ','モ','ャ','ヤ','ュ','ユ','ョ','ヨ','ラ','リ','ル','レ','ロ','ワ','ヲ','ン', // F0-FF
        ];
    }
?>