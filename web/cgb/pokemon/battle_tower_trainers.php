<?php
	// SPDX-License-Identifier: MIT
	
	function getBattleTowerPlaceholderTrainerForRegion($region, $level, $number) {
		if ($level == 0){
			switch ($region) {
				case "j": return getBattleTowerPlaceholderTrainerJP($number);
				case "e": return getBattleTowerPlaceholderTrainerEN($number);
				case "f": return getBattleTowerPlaceholderTrainerFR($number);
				case "d": return getBattleTowerPlaceholderTrainerDE($number);
				case "i": return getBattleTowerPlaceholderTrainerIT($number);
				case "s": return getBattleTowerPlaceholderTrainerES($number);
			}
		}
		if ($level == 1){
			switch ($region) {
				case "j": return getBattleTowerPlaceholderTrainerJP1($number);
				case "e": return getBattleTowerPlaceholderTrainerEN1($number);
				case "f": return getBattleTowerPlaceholderTrainerFR1($number);
				case "d": return getBattleTowerPlaceholderTrainerDE1($number);
				case "i": return getBattleTowerPlaceholderTrainerIT1($number);
				case "s": return getBattleTowerPlaceholderTrainerES1($number);
			}
		}
		if ($level == 2){
			switch ($region) {
				case "j": return getBattleTowerPlaceholderTrainerJP2($number);
				case "e": return getBattleTowerPlaceholderTrainerEN2($number);
				case "f": return getBattleTowerPlaceholderTrainerFR2($number);
				case "d": return getBattleTowerPlaceholderTrainerDE2($number);
				case "i": return getBattleTowerPlaceholderTrainerIT2($number);
				case "s": return getBattleTowerPlaceholderTrainerES2($number);
			}
		}
		if ($level == 3){
			switch ($region) {
				case "j": return getBattleTowerPlaceholderTrainerJP3($number);
				case "e": return getBattleTowerPlaceholderTrainerEN3($number);
				case "f": return getBattleTowerPlaceholderTrainerFR3($number);
				case "d": return getBattleTowerPlaceholderTrainerDE3($number);
				case "i": return getBattleTowerPlaceholderTrainerIT3($number);
				case "s": return getBattleTowerPlaceholderTrainerES3($number);
			}
		}
		if ($level == 4){
			switch ($region) {
				case "j": return getBattleTowerPlaceholderTrainerJP4($number);
				case "e": return getBattleTowerPlaceholderTrainerEN4($number);
				case "f": return getBattleTowerPlaceholderTrainerFR4($number);
				case "d": return getBattleTowerPlaceholderTrainerDE4($number);
				case "i": return getBattleTowerPlaceholderTrainerIT4($number);
				case "s": return getBattleTowerPlaceholderTrainerES4($number);
			}
		}
		if ($level == 5){
			switch ($region) {
				case "j": return getBattleTowerPlaceholderTrainerJP5($number);
				case "e": return getBattleTowerPlaceholderTrainerEN5($number);
				case "f": return getBattleTowerPlaceholderTrainerFR5($number);
				case "d": return getBattleTowerPlaceholderTrainerDE5($number);
				case "i": return getBattleTowerPlaceholderTrainerIT5($number);
				case "s": return getBattleTowerPlaceholderTrainerES5($number);
			}
		}
		if ($level == 6){
			switch ($region) {
				case "j": return getBattleTowerPlaceholderTrainerJP6($number);
				case "e": return getBattleTowerPlaceholderTrainerEN6($number);
				case "f": return getBattleTowerPlaceholderTrainerFR6($number);
				case "d": return getBattleTowerPlaceholderTrainerDE6($number);
				case "i": return getBattleTowerPlaceholderTrainerIT6($number);
				case "s": return getBattleTowerPlaceholderTrainerES6($number);
			}
		}
		if ($level == 7){
			switch ($region) {
				case "j": return getBattleTowerPlaceholderTrainerJP7($number);
				case "e": return getBattleTowerPlaceholderTrainerEN7($number);
				case "f": return getBattleTowerPlaceholderTrainerFR7($number);
				case "d": return getBattleTowerPlaceholderTrainerDE7($number);
				case "i": return getBattleTowerPlaceholderTrainerIT7($number);
				case "s": return getBattleTowerPlaceholderTrainerES7($number);
			}
		}
		if ($level == 8){
			switch ($region) {
				case "j": return getBattleTowerPlaceholderTrainerJP8($number);
				case "e": return getBattleTowerPlaceholderTrainerEN8($number);
				case "f": return getBattleTowerPlaceholderTrainerFR8($number);
				case "d": return getBattleTowerPlaceholderTrainerDE8($number);
				case "i": return getBattleTowerPlaceholderTrainerIT8($number);
				case "s": return getBattleTowerPlaceholderTrainerES8($number);
			}
		}
		if ($level == 9){
			switch ($region) {
				case "j": return getBattleTowerPlaceholderTrainerJP9($number);
				case "e": return getBattleTowerPlaceholderTrainerEN9($number);
				case "f": return getBattleTowerPlaceholderTrainerFR9($number);
				case "d": return getBattleTowerPlaceholderTrainerDE9($number);
				case "i": return getBattleTowerPlaceholderTrainerIT9($number);
				case "s": return getBattleTowerPlaceholderTrainerES9($number);
			}
		}
	}

	function getBattleTowerPlaceholderTrainerJP($number) {
		$placeholderTrainer0 = array();
		
		// Trainer 1
		$placeholderTrainer0[6] = array();
		$placeholderTrainer0[6]["name"] = hex2bin("BF263D5050");
		$placeholderTrainer0[6]["class"] = hexdec("25");
		$placeholderTrainer0[6]["pokemon1"] = hex2bin("876D553FF72E00000003E8C3509C409C4088B89C40DDBD0F050F14640000000A0000002900290019001800250022001F8AAB0FE38C50");
		$placeholderTrainer0[6]["pokemon2"] = hex2bin("C492BD5EF45C00000003E89C40C35088B89C409C40EDFB0A0A0A0A640000000A000000270027001A001800230026001F83E39BB05050");
		$placeholderTrainer0[6]["pokemon3"] = hex2bin("C5AEF7E7F45C00000003E89C409C40AFC8C3509C40DBEF0F0F0A0A640000000A0000002E002E00190022001A001900271BA5AC86E350");
		$placeholderTrainer0[6]["message_start"] = hex2bin("37030e09250B1c0D120D0005");
		$placeholderTrainer0[6]["message_win"] = hex2bin("0e091a0B30043f062004210D");
		$placeholderTrainer0[6]["message_lose"] = hex2bin("0e09300420040b0634030605");
		
		// Trainer 2
		$placeholderTrainer0[5] = array();
		$placeholderTrainer0[5]["name"] = hex2bin("D6BC305050");
		$placeholderTrainer0[5]["class"] = hexdec("1E");
		$placeholderTrainer0[5]["pokemon1"] = hex2bin("CA7744F3DBC200000003E8C350C350C350C350C3507FD714141905640000000A000000420042001200190013001200178EE394AB8C50");
		$placeholderTrainer0[5]["pokemon2"] = hex2bin("736DB33F59D500000003E89C4075309C4075307530EFCF0F050A0F640000000A0000002F002F001F001D001D0014001C05A6E3A55050");
		$placeholderTrainer0[5]["pokemon3"] = hex2bin("DE8C395E69F600000003E89C407530821475307530FEFD0F0A1405640000000A0000002600260017001D00130018001C8A95E3095050");
		$placeholderTrainer0[5]["message_start"] = hex2bin("3603ca0022082004330D110D");
		$placeholderTrainer0[5]["message_win"] = hex2bin("370506053f06ca002004060D");
		$placeholderTrainer0[5]["message_lose"] = hex2bin("ca00210309061204280B0c0D");
		
		// Trainer 3
		$placeholderTrainer0[4] = array();
		$placeholderTrainer0[4]["name"] = hex2bin("CFBD305050");
		$placeholderTrainer0[4]["class"] = hexdec("2B");
		$placeholderTrainer0[4]["pokemon1"] = hex2bin("F1AE3B593F5C00000003E8753075307530753088B8BBDF050A050A640000000A0000002E002E001B0020001F0014001A9EA68FAB8750");
		$placeholderTrainer0[4]["pokemon2"] = hex2bin("8E923F30592C00000003E875307530753075307530DBFB05140A19640000000A0000002B002B0020001800260017001A4292A5505050");
		$placeholderTrainer0[4]["pokemon3"] = hex2bin("836D3B39555E00000003E875307530753075307530FDEB050F0F0A640000000A000000340034001D001B0018001C001EA542A58C5050");
		$placeholderTrainer0[4]["message_start"] = hex2bin("320631040202030422020005");
		$placeholderTrainer0[4]["message_win"] = hex2bin("40042d06130D18033f04130D");
		$placeholderTrainer0[4]["message_lose"] = hex2bin("1a0C3304320D09062202130D");
		
		// Trainer 4
		$placeholderTrainer0[3] = array();
		$placeholderTrainer0[3]["name"] = hex2bin("C0CF305050");
		$placeholderTrainer0[3]["class"] = hexdec("14");
		$placeholderTrainer0[3]["pokemon1"] = hex2bin("D7AEA3B9393B00000003E8753088B8753075307530FBBF14140F05640000000A000000260026001F001600220013001B95AEE3A55050");
		$placeholderTrainer0[3]["pokemon2"] = hex2bin("E9035E3B3FA100000003E8753075309C4075307530FBDE0A05050A640000000A0000002C002C001C001E00170021001F43D809AB5050");
		$placeholderTrainer0[3]["pokemon3"] = hex2bin("C877C3D4DCF700000003E875307530753075307530EFDF0505140F640000000A00000025002500180018001C001D001D9F829D505050");
		$placeholderTrainer0[3]["message_start"] = hex2bin("220C220C0a090b082f062207");
		$placeholderTrainer0[3]["message_win"] = hex2bin("22062c041a060d073f0D0005");
		$placeholderTrainer0[3]["message_lose"] = hex2bin("0b0809062c041f0E1c0B410B");
		
		// Trainer 5
		$placeholderTrainer0[2] = array();
		$placeholderTrainer0[2]["name"] = hex2bin("C6BCC95050");
		$placeholderTrainer0[2]["class"] = hexdec("3B");
		$placeholderTrainer0[2]["pokemon1"] = hex2bin("E4AEB94C2EF100000003E875307530753080E87530FDFE140A1405640000000A000000240024001800110019001C001612A61AA65050");
		$placeholderTrainer0[2]["pokemon2"] = hex2bin("CB523CBDF76100000003E875307530753075307530EDFD140A0F1E640000000A000000270027001C0018001D001D001886D8ABD88650");
		$placeholderTrainer0[2]["pokemon3"] = hex2bin("F2491D4CCD4600000003E87D009C40753075307530DFCE0F0A140F640000000A0000004D004D000E000E0016001B00279941948C5050");
		$placeholderTrainer0[2]["message_start"] = hex2bin("a30041031f0429001c040302");
		$placeholderTrainer0[2]["message_win"] = hex2bin("3d0538067100180330065300");
		$placeholderTrainer0[2]["message_lose"] = hex2bin("1e05b900210D3006d1000a06");
		
		// Trainer 6
		$placeholderTrainer0[1] = array();
		$placeholderTrainer0[1]["name"] = hex2bin("BBC4B35050");
		$placeholderTrainer0[1]["class"] = hexdec("19");
		$placeholderTrainer0[1]["pokemon1"] = hex2bin("8F6D1DB6AD3900000003E875307530753075307530EFF70F0A0F0F640000000A00000039003900220019001200170020851509AB5050");
		$placeholderTrainer0[1]["pokemon2"] = hex2bin("67525CCAA85D00000003E875307530753075307530FEFE0A050A19640000000A0000002D002D001F001D00170025001994AC8BE35050");
		$placeholderTrainer0[1]["pokemon3"] = hex2bin("D6AEB3CB44F900000003E875307530753075307530F7F70F0A140F640000000A0000002B002B00250019001D0012001DCDA587A88C50");
		$placeholderTrainer0[1]["message_start"] = hex2bin("1302310604021c040d0D310D");
		$placeholderTrainer0[1]["message_win"] = hex2bin("3907310624073d044004300D");
		$placeholderTrainer0[1]["message_lose"] = hex2bin("030608050205140708051a0D");
		
		// Trainer 7
		$placeholderTrainer0[0] = array();
		$placeholderTrainer0[0]["name"] = hex2bin("B5B5C05050");
		$placeholderTrainer0[0]["class"] = hexdec("16");
		$placeholderTrainer0[0]["pokemon1"] = hex2bin("C9ADED00000000000003E875307530753075307530FFFF0F000000000000000A000000240024001A00150015001A001580AB98E3AB50");
		$placeholderTrainer0[0]["pokemon2"] = hex2bin("80521DCF27C400000003E87530753075307530753065570F0F1E0F000000000A000000280028001E001D00200012001888AB8FA88C50");
		$placeholderTrainer0[0]["pokemon3"] = hex2bin("7A495CF4071D00000003E87530753075307530753073670A0A0F0F000000000A00000022002200130016001C001E002214D8A2E31350");
		$placeholderTrainer0[0]["message_start"] = hex2bin("0d02070200052202100B0a0D");
		$placeholderTrainer0[0]["message_win"] = hex2bin("16020102080D2c042307230D");
		$placeholderTrainer0[0]["message_lose"] = hex2bin("16020102080D02032004230D");

		return $placeholderTrainer0[$number];
	}
	
	function getBattleTowerPlaceholderTrainerJP1($number) {
		$placeholderTrainer1 = array();
		
		// Trainer 1
		$placeholderTrainer1[6] = array();
		$placeholderTrainer1[6]["name"] = hex2bin("CFC2305050");
		$placeholderTrainer1[6]["class"] = hexdec("2C");
		$placeholderTrainer1[6]["pokemon1"] = hex2bin("C592B65CBDD50000001F40C350C350C350C350C350CFBC0A0A0A0F6400000014000000510051002E0042002E002C00481BA5AC86E350");
		$placeholderTrainer1[6]["pokemon2"] = hex2bin("79AE695E39F40000001F40C350C350C350C350C350DBDB140A0F0A6400000014000000470047003300360043003C00368C8FE39EE350");
		$placeholderTrainer1[6]["pokemon3"] = hex2bin("826D3F52557E0000001F40C350C350C350C350C350FAFD050A0F056400000014000000530053004800330036002D003D06ADA5138C50");
		$placeholderTrainer1[6]["message_start"] = hex2bin("1e022202000520082c040302");
		$placeholderTrainer1[6]["message_win"] = hex2bin("1d05410841083505400B0005");
		$placeholderTrainer1[6]["message_lose"] = hex2bin("0b050b020005040414080005");
		
		// Trainer 2
		$placeholderTrainer1[5] = array();
		$placeholderTrainer1[5]["name"] = hex2bin("B6DCB6D050");
		$placeholderTrainer1[5]["class"] = hexdec("22");
		$placeholderTrainer1[5]["pokemon1"] = hex2bin("D0AE2EE7CF590000001F40C350AFC8C3507530C350FFFF140F0F0A64000000140000004D004D00370066001F002C0030990597A65050");
		$placeholderTrainer1[5]["pokemon2"] = hex2bin("418B5EF45C090000001F40C350C3507530C3509C40FDEF0A0A0A0F6400000014000000440044002A00240045004B00379BE312B0AB50");
		$placeholderTrainer1[5]["pokemon3"] = hex2bin("3B03352E3FE70000001F4088B8AFC8C350D6D8C350DBFB0F14050F640000001400000051005100400034003C003C00348281AB12B050");
		$placeholderTrainer1[5]["message_start"] = hex2bin("060C090600000b091e0C370D");
		$placeholderTrainer1[5]["message_win"] = hex2bin("410306043406220C1509230D");
		$placeholderTrainer1[5]["message_lose"] = hex2bin("02032208410D2604310D0105");
		
		// Trainer 3
		$placeholderTrainer1[4] = array();
		$placeholderTrainer1[4]["name"] = hex2bin("B5B8C0C650");
		$placeholderTrainer1[4]["class"] = hexdec("3B");
		$placeholderTrainer1[4]["pokemon1"] = hex2bin("D677CBB3E0590000001F40C3507530AFC87530AFC8DFDE0A0F0A0A64000000140000004E004E0044003300340025003BCDA587A88C50");
		$placeholderTrainer1[4]["pokemon2"] = hex2bin("67923F5E5C8A0000001F40AFC8C350C350AFC8AFC8FDEB050A0A0F6400000014000000530053003C0037002B0046002E94AC8BE35050");
		$placeholderTrainer1[4]["pokemon3"] = hex2bin("8EAE9C3F59520000001F40AFC8C3509C40C350AFC8FBBB0A050A0A64000000140000004E004E0040002D0048002C00324292A5505050");
		$placeholderTrainer1[4]["message_start"] = hex2bin("220C300401052c0423070105");
		$placeholderTrainer1[4]["message_win"] = hex2bin("37033d043c0D1c0329083c0D");
		$placeholderTrainer1[4]["message_lose"] = hex2bin("1804300D06051407300D0605");
		
		// Trainer 4
		$placeholderTrainer1[3] = array();
		$placeholderTrainer1[3]["name"] = hex2bin("BBB2C45050");
		$placeholderTrainer1[3]["class"] = hexdec("3C");
		$placeholderTrainer1[3]["pokemon1"] = hex2bin("F2035E4287440000001F40C350C35075307530C350BDFE0A190A1464000000140000009400940018001600290033004B9941948C5050");
		$placeholderTrainer1[3]["pokemon2"] = hex2bin("83AE5E553B6D0000001F40D6D875309C40D6D87530FED70A0F050A640000001400000062006200350034002D00320036A542A58C5050");
		$placeholderTrainer1[3]["pokemon3"] = hex2bin("19A35556465C0000001F40AFC8C350AFC8C350C350FCFE0F140F0A64000000140000003A003A002C0020003A00290025418590AE8250");
		$placeholderTrainer1[3]["message_start"] = hex2bin("0e0604052c041e0C380D0005");
		$placeholderTrainer1[3]["message_win"] = hex2bin("3f033f062004330D040D0000");
		$placeholderTrainer1[3]["message_lose"] = hex2bin("2004260D04050c04260D0405");
		
		// Trainer 5
		$placeholderTrainer1[2] = array();
		$placeholderTrainer1[2]["name"] = hex2bin("B2C0B8D750");
		$placeholderTrainer1[2]["class"] = hexdec("3A");
		$placeholderTrainer1[2]["pokemon1"] = hex2bin("D477D3A35CC90000001F409C40AFC89C40AFC8C350FDFE19140A0A64000000140000004900490049003C002F002B003599AC8A9F5050");
		$placeholderTrainer1[2]["pokemon2"] = hex2bin("6BAE090807050000001F40C350AFC888B8C3507530FBFD0F0F0F146400000014000000430043003F003200340020003E8315A9A5E350");
		$placeholderTrainer1[2]["pokemon3"] = hex2bin("800355593F3B0000001F40C3509C40C35075307530FBEF0F0A050564000000140000004C004C003D003A003F0023002F88AB8FA88C50");
		$placeholderTrainer1[2]["message_start"] = hex2bin("1802330328042c0409070305");
		$placeholderTrainer1[2]["message_win"] = hex2bin("3004080900051a0232030005");
		$placeholderTrainer1[2]["message_lose"] = hex2bin("3f052607010526050f020005");
		
		// Trainer 6
		$placeholderTrainer1[1] = array();
		$placeholderTrainer1[1]["name"] = hex2bin("D0BFC95050");
		$placeholderTrainer1[1]["class"] = hexdec("35");
		$placeholderTrainer1[1]["pokemon1"] = hex2bin("B85F393BD5F00000001F409C409C409C409C409C40EDF70F050F056400000014000000520052002800340029002500319DD8A6D85050");
		$placeholderTrainer1[1]["pokemon2"] = hex2bin("F1525957D5390000001F409C409C409C409C409C40DFFE0A0A0F0F64000000140000005300530034003F003D002400309EA68FAB8750");
		$placeholderTrainer1[1]["pokemon3"] = hex2bin("28AE3F3B7ED50000001F409C409C409C409C409C40C7FE0505050F6400000014000000620062002F00230027003200284287D8AB5050");
		$placeholderTrainer1[1]["message_start"] = hex2bin("3a0B3a0B0005420319083004");
		$placeholderTrainer1[1]["message_win"] = hex2bin("3903130613061908210D0000");
		$placeholderTrainer1[1]["message_lose"] = hex2bin("39030902000540033a082a0D");
		
		// Trainer 7
		$placeholderTrainer1[0] = array();
		$placeholderTrainer1[0]["name"] = hex2bin("B4C9D3C450");
		$placeholderTrainer1[0]["class"] = hexdec("2D");
		$placeholderTrainer1[0]["pokemon1"] = hex2bin("28685ECFF41D0000001F4075307530753075307530C7770A0F0A0F0000000014000000610061002E00220022002E00244287D8AB5050");
		$placeholderTrainer1[0]["pokemon2"] = hex2bin("22AD3B5939090000001F40753075307530753075305646050A0F0F00000000140000004A004A0034002E00310032002E951386AB0750");
		$placeholderTrainer1[0]["pokemon3"] = hex2bin("C349855939F00000001F40753075307530753075305547140A0F05000000001400000051005100310031001D002A002A9684E3505050");
		$placeholderTrainer1[0]["message_start"] = hex2bin("3105110E30041b0807080c0D");
		$placeholderTrainer1[0]["message_win"] = hex2bin("1b021b02010515020a040e0D");
		$placeholderTrainer1[0]["message_lose"] = hex2bin("2205310602051d063b070e0D");
		
		return $placeholderTrainer1[$number];
	}
	
	function getBattleTowerPlaceholderTrainerJP2($number) {
		$placeholderTrainer2 = array();
		
		// Trainer 1
		$placeholderTrainer2[6] = array();
		$placeholderTrainer2[6]["name"] = hex2bin("CFC2D0D450");
		$placeholderTrainer2[6]["class"] = hexdec("31");
		$placeholderTrainer2[6]["pokemon1"] = hex2bin("876D55562EBD0000006978C350C350C350C350D6D8DBED0F14140A640000001E00000067006700440040006B006000578AAB0FE38C50");
		$placeholderTrainer2[6]["pokemon2"] = hex2bin("3E0368395A420000006978C350D6D8D6D8D6D8C350DDFB0F0F0519640000001E0000007700770051005700490046005295AFA817AB50");
		$placeholderTrainer2[6]["pokemon3"] = hex2bin("7992565E69390000006978C350C350C350C350C350FFFF140A140F640000001E000000650065004B00510063005A00518C8FE39EE350");
		$placeholderTrainer2[6]["message_start"] = hex2bin("1b022c040d0D34030003110D");
		$placeholderTrainer2[6]["message_win"] = hex2bin("1b0831040b0D2f0801050000");
		$placeholderTrainer2[6]["message_lose"] = hex2bin("060510050f06110D06050000");
		
		// Trainer 2
		$placeholderTrainer2[5] = array();
		$placeholderTrainer2[5]["name"] = hex2bin("BBBBB75050");
		$placeholderTrainer2[5]["class"] = hexdec("3E");
		$placeholderTrainer2[5]["pokemon1"] = hex2bin("7CAE3B8E8AD50000006978C350C350C3507530C350FBEE050A0F0F640000001E000000660066003C0031005300620056A6E30BAEA550");
		$placeholderTrainer2[5]["pokemon2"] = hex2bin("335259BCA3BD0000006978C350C3507530C350C350EFFF0A0A140A640000001E000000510051004D00380066003C00480F0793D88450");
		$placeholderTrainer2[5]["pokemon3"] = hex2bin("B603CAF14C680000006978AFC8AFC8C350D6D8C350DFDB05050A0F640000001E0000006D006D004C0051003C0052005886A781999450");
		$placeholderTrainer2[5]["message_start"] = hex2bin("420330041f0B160E1007340D");
		$placeholderTrainer2[5]["message_win"] = hex2bin("170E040D0000420319083004");
		$placeholderTrainer2[5]["message_lose"] = hex2bin("0c05350501051e063608410D");
		
		// Trainer 3
		$placeholderTrainer2[4] = array();
		$placeholderTrainer2[4]["name"] = hex2bin("B1B5B75050");
		$placeholderTrainer2[4]["class"] = hexdec("30");
		$placeholderTrainer2[4]["pokemon1"] = hex2bin("F2925C7387B60000006978C3507530AFC87530AFC8FBED0A140A0A640000001E000000D900D900200021003B0049006D9941948C5050");
		$placeholderTrainer2[4]["pokemon2"] = hex2bin("E58A35F2F78A0000006978AFC8C350C350AFC8AFC8FDED0F0F0F0F640000001E0000006C006C0054003B0056005E004CCDA605E35050");
		$placeholderTrainer2[4]["pokemon3"] = hex2bin("446DEE08597E0000006978AFC8C3509C40C350AFC8FDBE050F0A05640000001E000000760076006C004B003D004400508581D886E350");
		$placeholderTrainer2[4]["message_start"] = hex2bin("1803420343043a0B3b0B0305");
		$placeholderTrainer2[4]["message_win"] = hex2bin("2c0506051b034304390B3c04");
		$placeholderTrainer2[4]["message_lose"] = hex2bin("140B0f05060540033f04200E");
		
		// Trainer 4
		$placeholderTrainer2[3] = array();
		$placeholderTrainer2[3]["name"] = hex2bin("B8CFD3C450");
		$placeholderTrainer2[3]["class"] = hexdec("27");
		$placeholderTrainer2[3]["pokemon1"] = hex2bin("A9AED56D5C110000006978C350C35075307530C350EFDC0F0A0A23640000001E0000006F006F0053004A00670046004C87A814AC9350");
		$placeholderTrainer2[3]["pokemon2"] = hex2bin("E9035E693FA10000006978D6D875309C40D6D87530DFDB0A14050A640000001E0000007500750049005300420057005143D809AB5050");
		$placeholderTrainer2[3]["pokemon3"] = hex2bin("697659D83F9B0000006978AFC8C350AFC8C3507530DDEB0A14050AFF0000001E000000630063004D005E00380036004805A505A55050");
		$placeholderTrainer2[3]["message_start"] = hex2bin("0c0536073004270B1008310D");
		$placeholderTrainer2[3]["message_win"] = hex2bin("110E30044103070D2f080305");
		$placeholderTrainer2[3]["message_lose"] = hex2bin("06053f06110E300430070605");
		
		// Trainer 5
		$placeholderTrainer2[2] = array();
		$placeholderTrainer2[2]["name"] = hex2bin("B2C1C9BE50");
		$placeholderTrainer2[2]["class"] = hexdec("26");
		$placeholderTrainer2[2]["pokemon1"] = hex2bin("65037155B65700000069789C40AFC89C40AFC8C350BDEF1E0F0A0A640000001E000000620062003900450071004E004E9DA69D81AB50");
		$placeholderTrainer2[2]["pokemon2"] = hex2bin("8392F037C4460000006978C350AFC888B8C3507530FDEB05190F0F640000001E0000008E008E0050004A0041004B0051A542A58C5050");
		$placeholderTrainer2[2]["pokemon3"] = hex2bin("ABAEF05739AF0000006978C3509C40C35075307530DDEB050A0F0F640000001E0000008B008B003E0040004200460046A5AB8FE3AB50");
		$placeholderTrainer2[2]["message_start"] = hex2bin("3603060644040b0E160D0005");
		$placeholderTrainer2[2]["message_win"] = hex2bin("320D0806090E44040908220D");
		$placeholderTrainer2[2]["message_lose"] = hex2bin("040E090E44042908050D0000");
		
		// Trainer 6
		$placeholderTrainer2[1] = array();
		$placeholderTrainer2[1]["name"] = hex2bin("CACFC95050");
		$placeholderTrainer2[1]["class"] = hexdec("21");
		$placeholderTrainer2[1]["pokemon1"] = hex2bin("C46D5D815CF40000006978AFC8C350C350C350C350EFF719140A0A640000001E0000006300630044004200600067005283E39BB05050");
		$placeholderTrainer2[1]["pokemon2"] = hex2bin("4952235CBC3D0000006978C350AFC8C350B798AFC8FEFE140A0A14640000001E0000006E006E00470044005A004D0065138787A50850");
		$placeholderTrainer2[1]["pokemon3"] = hex2bin("5EAEA87A65CA0000006978C350AFC8C350C350C350F7F70A1E0F05640000001E0000006500650044003D00600067004608AB05E35050");
		$placeholderTrainer2[1]["message_start"] = hex2bin("3f062c04340D140513020402");
		$placeholderTrainer2[1]["message_win"] = hex2bin("0c053f04380D02032908320D");
		$placeholderTrainer2[1]["message_lose"] = hex2bin("35051506400502033407040D");
		
		// Trainer 7
		$placeholderTrainer2[0] = array();
		$placeholderTrainer2[0]["name"] = hex2bin("B626D45050");
		$placeholderTrainer2[0]["class"] = hexdec("36");
		$placeholderTrainer2[0]["pokemon1"] = hex2bin("D9AE1DB62E2B00000069787530753075307530753077450F0A141E000000001E00000072007200640043003500410041D8AB079D5050");
		$placeholderTrainer2[0]["pokemon2"] = hex2bin("160377E44081000000697875307530753075307530677714142314000000001E000000600060004B003D0052003A003A849513D8A650");
		$placeholderTrainer2[0]["pokemon3"] = hex2bin("396D4302B374000000697875307530753075307530776714190F1E000000001E0000006300630055003A004E003A00408489D80AA650");
		$placeholderTrainer2[0]["message_start"] = hex2bin("12030204070728042c0D0005");
		$placeholderTrainer2[0]["message_win"] = hex2bin("2c0312032b060a04060D310D");
		$placeholderTrainer2[0]["message_lose"] = hex2bin("2c03120306040d06280B0205");
		
		return $placeholderTrainer2[$number];
	}
	
	function getBattleTowerPlaceholderTrainerJP3($number) {
		$placeholderTrainer3 = array();
		
		// Trainer 1
		$placeholderTrainer3[6] = array();
		$placeholderTrainer3[6]["name"] = hex2bin("B2BCCAD750");
		$placeholderTrainer3[6]["class"] = hexdec("20");
		$placeholderTrainer3[6]["pokemon1"] = hex2bin("80AED83F59E7000000FA00C350C350C350C350C350FDFE14050A0FFF000000280000008F008F00770071007F0046005E88AB8FA88C50");
		$placeholderTrainer3[6]["pokemon2"] = hex2bin("E69239E13F3B000000FA00C350C350C350C350C350FDEF0F14050564000000280000008E008E00730071006A0073007386AB0713A550");
		$placeholderTrainer3[6]["pokemon3"] = hex2bin("8F49D522F459000000FA00C350C350C350C350C350EDDD0F0F0A0A6400000028000000CD00CD007E0059003D0059007D851509AB5050");
		$placeholderTrainer3[6]["message_start"] = hex2bin("41043c0B340D400300030d0D");
		$placeholderTrainer3[6]["message_win"] = hex2bin("130C3308310D1e0927073b0D");
		$placeholderTrainer3[6]["message_lose"] = hex2bin("22081504110D2c030c043b0D");
		
		// Trainer 2
		$placeholderTrainer3[5] = array();
		$placeholderTrainer3[5]["name"] = hex2bin("B6DCD1D750");
		$placeholderTrainer3[5]["class"] = hexdec("1D");
		$placeholderTrainer3[5]["pokemon1"] = hex2bin("8392553A6D39000000FA00C350C350C350C350C350FDEB0F0A0A0F6400000028000000BA00BA006B006500560067006FA542A58C5050");
		$placeholderTrainer3[5]["pokemon2"] = hex2bin("D0AEC9E7595C000000FA00C350C350C350C350C350EFDB0A0F0A0A6400000028000000890089006A00C7003D004F0057990597A65050");
		$placeholderTrainer3[5]["pokemon3"] = hex2bin("41525E096907000000FA00C350AFC8C350D6D8C350DDEF0A0F140F64000000280000007E007E004C004900870093006B9BE312B0AB50");
		$placeholderTrainer3[5]["message_start"] = hex2bin("030A3a0D2c041c0B3507260D");
		$placeholderTrainer3[5]["message_win"] = hex2bin("24094304260D0f031f070405");
		$placeholderTrainer3[5]["message_lose"] = hex2bin("02032408260D3f0313040405");
		
		// Trainer 3
		$placeholderTrainer3[4] = array();
		$placeholderTrainer3[4]["name"] = hex2bin("B6DCC1CFD9");
		$placeholderTrainer3[4]["class"] = hexdec("29");
		$placeholderTrainer3[4]["pokemon1"] = hex2bin("79923B55395E000000FA00C350C350AFC8C350AFC8FDBE050F0F0A640000002800000083008300630068007F007500698C8FE39EE350");
		$placeholderTrainer3[4]["pokemon2"] = hex2bin("CAAE44F3DBC2000000FA00AFC8C350C350C350C350BFE7141419056400000028000000E900E9003E00550040003B004F8EE394AB8C50");
		$placeholderTrainer3[4]["pokemon3"] = hex2bin("4C779959059D000000FA00AFC8C3509C40C350AFC8DDED050A140A6400000028000000910091007D008B004A0050005809A8E395AD50");
		$placeholderTrainer3[4]["message_start"] = hex2bin("06050605060506052c040305");
		$placeholderTrainer3[4]["message_win"] = hex2bin("060506050605060507040305");
		$placeholderTrainer3[4]["message_lose"] = hex2bin("060506050605060533040305");
		
		// Trainer 4
		$placeholderTrainer3[3] = array();
		$placeholderTrainer3[3]["name"] = hex2bin("D3D8505050");
		$placeholderTrainer3[3]["class"] = hexdec("32");
		$placeholderTrainer3[3]["pokemon1"] = hex2bin("D48CA3D3E43F000000FA00C350C350C3509C40C350BDFE1419140564000000280000008B008B008B007500590052006699AC8A9F5050");
		$placeholderTrainer3[3]["pokemon2"] = hex2bin("3352593FBCBD000000FA00AFC8C350C350C350C350FEBB0A050A0A64000000280000006C006C0067004E0083004B005B0F0793D88450");
		$placeholderTrainer3[3]["pokemon3"] = hex2bin("506D395E593B000000FA00AFC8C350AFC8C350C350BFCF0F0A0A0564000000280000009D009D005F007E003C00770067A213A5AB5050");
		$placeholderTrainer3[3]["message_start"] = hex2bin("2705010500001804190D0105");
		$placeholderTrainer3[3]["message_win"] = hex2bin("41050000000016063d04190D");
		$placeholderTrainer3[3]["message_lose"] = hex2bin("20040505000021030c04190D");
		
		// Trainer 5
		$placeholderTrainer3[2] = array();
		$placeholderTrainer3[2]["name"] = hex2bin("B2DCBCC050");
		$placeholderTrainer3[2]["class"] = hexdec("1C");
		$placeholderTrainer3[2]["pokemon1"] = hex2bin("E900B0A03CA8000000FA00C350AFC8C350C350C350BCEF1E1E140A64000000280000009300930063006C0056007B007343D809AB5050");
		$placeholderTrainer3[2]["pokemon2"] = hex2bin("3B8AAC2B222E000000FA00C350C350C350C350C350FEBB191E0F146400000028000000980098007F0066006F007300638281AB12B050");
		$placeholderTrainer3[2]["pokemon3"] = hex2bin("CD92E5B65CC9000000FA00C350C350C350C350C350FA7F280A0A0A64000000280000008C008C006F00930040005700579BF4A7938C50");
		$placeholderTrainer3[2]["message_start"] = hex2bin("03024303270428042d0D0005");
		$placeholderTrainer3[2]["message_win"] = hex2bin("30050405020338063e04410D");
		$placeholderTrainer3[2]["message_lose"] = hex2bin("160E0405020338062004410D");
		
		// Trainer 6
		$placeholderTrainer3[1] = array();
		$placeholderTrainer3[1]["name"] = hex2bin("DCC0C53D50");
		$placeholderTrainer3[1]["class"] = hexdec("41");
		$placeholderTrainer3[1]["pokemon1"] = hex2bin("8BAEAE37F6F9000000FA00C350C350C350C350C350EFF70A19050F64000000280000008500850056008B0053007C0058849F8C8FE350");
		$placeholderTrainer3[1]["pokemon2"] = hex2bin("0652535213A3000000FA00C350C350C350C350C350FEFE0F0A0F1464000000280000008E008E006A00640077007D006AD80AE313AB50");
		$placeholderTrainer3[1]["pokemon3"] = hex2bin("67037917F45D000000FA00C350C350C350C350C350F7E70A140A1964000000280000009E009E0073006400520084005494AC8BE35050");
		$placeholderTrainer3[1]["message_start"] = hex2bin("34030003110D03041c0B070D");
		$placeholderTrainer3[1]["message_win"] = hex2bin("2905000500004004110D3e0D");
		$placeholderTrainer3[1]["message_lose"] = hex2bin("0306110D0005160E0d062006");
		
		// Trainer 7
		$placeholderTrainer3[0] = array();
		$placeholderTrainer3[0]["name"] = hex2bin("CFC2BCCF50");
		$placeholderTrainer3[0]["class"] = hexdec("34");
		$placeholderTrainer3[0]["pokemon1"] = hex2bin("61035D091D32000000FA0075307530753075307530777A190F0F1400000000280000009200920056005300510058007A8CD8E318E350");
		$placeholderTrainer3[0]["pokemon2"] = hex2bin("5949675C7C6A000000FA0075307530753075307530756B280A141E0000000028000000A100A1006F005600430053006F3D933D93AB50");
		$placeholderTrainer3[0]["pokemon3"] = hex2bin("7D52710981AD000000FA007530753075307530753065771E0F140F00000000280000007C007C005D0047006F0067005F83A716E35050");
		$placeholderTrainer3[0]["message_start"] = hex2bin("1903170B20061b0B060C1a0B");
		$placeholderTrainer3[0]["message_win"] = hex2bin("3403060420061b0B060C1a0B");
		$placeholderTrainer3[0]["message_lose"] = hex2bin("3403360420061b0B060C1a0B");
		
		return $placeholderTrainer3[$number];
	}
	
	function getBattleTowerPlaceholderTrainerJP4($number) {
		$placeholderTrainer4 = array();
		
		// Trainer 1
		$placeholderTrainer4[6] = array();
		$placeholderTrainer4[6]["name"] = hex2bin("B5B5BBDC50");
		$placeholderTrainer4[6]["class"] = hexdec("3B");
		$placeholderTrainer4[6]["pokemon1"] = hex2bin("E6AE393F3BE1000001E848D6D8D6D8EA60C350D6D8DDFF0F0505140000000032000000B300B3008E008F00840090009086AB0713A550");
		$placeholderTrainer4[6]["pokemon2"] = hex2bin("E56D9CF28A35000001E848D6D8C350EA60EA60EA60DDFC0A0F0F0F0000000032000000B200B2008700620091009D007FCDA605E35050");
		$placeholderTrainer4[6]["pokemon3"] = hex2bin("D592C99C5C23000001E848EA60EA60EA60EA60D6D8FDCF0A0A0A1400000000320000007B007B003C01160034003B0117911791175050");
		$placeholderTrainer4[6]["message_start"] = hex2bin("0a030c0C250A210E2c040302");
		$placeholderTrainer4[6]["message_win"] = hex2bin("1009030809060a033104160D");
		$placeholderTrainer4[6]["message_lose"] = hex2bin("270517070405020C0a092f0D");
		
		// Trainer 2
		$placeholderTrainer4[5] = array();
		$placeholderTrainer4[5]["name"] = hex2bin("B5B5CAD750");
		$placeholderTrainer4[5]["class"] = hexdec("14");
		$placeholderTrainer4[5]["pokemon1"] = hex2bin("8F923F5939F4000001E848EA60D6D8D6D8EA60D6D8FDEF050A0F0A0000000032000001070107009F0070004F0072009F851509AB5050");
		$placeholderTrainer4[5]["pokemon2"] = hex2bin("83AE55396D3B000001E848D6D8EA60EA60D6D8EA60DDDD0F0F0A050000000032000000EA00EA00850080006B0085008FA542A58C5050");
		$placeholderTrainer4[5]["pokemon3"] = hex2bin("87525556F7ED000001E848D6D8EA60DEA8D6D8D6D8EDFF0F140F0F0000000032000000A100A10072006B00B3009F00908AAB0FE38C50");
		$placeholderTrainer4[5]["message_start"] = hex2bin("15022c040302240439040f0D");
		$placeholderTrainer4[5]["message_win"] = hex2bin("1e02110201050d043908050D");
		$placeholderTrainer4[5]["message_lose"] = hex2bin("29050802000543030c043b0D");
		
		// Trainer 3
		$placeholderTrainer4[4] = array();
		$placeholderTrainer4[4]["name"] = hex2bin("B2C4B35050");
		$placeholderTrainer4[4]["class"] = hexdec("1D");
		$placeholderTrainer4[4]["pokemon1"] = hex2bin("D4923FA361E8000001E848AFC8C3509C40C350AFC8DFED05141E230000000032000000A900A900AF0091006F0063007C99AC8A9F5050");
		$placeholderTrainer4[4]["pokemon2"] = hex2bin("C7549C395E85000001E848C3509C40AFC8C350C350DFDE0A0F0A140F00000032000000C400C40076007E004B0092009CA21386AB0750");
		$placeholderTrainer4[4]["pokemon3"] = hex2bin("44AEEE597E09000001E8489C40AFC8C3509C40ABE0FFEC050A050F0D00000032000000BB00BB00B0007F0063006C00808581D886E350");
		$placeholderTrainer4[4]["message_start"] = hex2bin("100B040400050508210D0000");
		$placeholderTrainer4[4]["message_win"] = hex2bin("1f050102000518033c08310D");
		$placeholderTrainer4[4]["message_lose"] = hex2bin("0206230B310343031307160D");
		
		// Trainer 4
		$placeholderTrainer4[3] = array();
		$placeholderTrainer4[3]["name"] = hex2bin("C5B6D1D750");
		$placeholderTrainer4[3]["class"] = hexdec("36");
		$placeholderTrainer4[3]["pokemon1"] = hex2bin("798C56695539000001E848AFC8ABE09C40AFC89C40FFFF14140F0F0000000032000000A100A10079008200A1009100828C8FE39EE350");
		$placeholderTrainer4[3]["pokemon2"] = hex2bin("335259A33FBC000001E848AFC89C40C350AFC8C350F7FE0A14050A0000000032000000870087007D005900A6006000740F0793D88450");
		$placeholderTrainer4[3]["pokemon3"] = hex2bin("656D5599F39C000001E848C350AFC8D2F09C40C3507DFE0F05140A0000000032000000A100A10058007500B9007E007E9DA69D81AB50");
		$placeholderTrainer4[3]["message_start"] = hex2bin("0f0E190D00050b0E2c04190D");
		$placeholderTrainer4[3]["message_win"] = hex2bin("0e0E2c0406051f07190D0105");
		$placeholderTrainer4[3]["message_lose"] = hex2bin("060537032904320D190D0105");
		
		// Trainer 5
		$placeholderTrainer4[2] = array();
		$placeholderTrainer4[2]["name"] = hex2bin("B3B4D1D750");
		$placeholderTrainer4[2]["class"] = hexdec("18");
		$placeholderTrainer4[2]["pokemon1"] = hex2bin("8E523F597EE7000001E848AFC8C350C350AFC8AFC8FDDD050A050F0000000032000000B500B50098006E00AE006800774292A5505050");
		$placeholderTrainer4[2]["pokemon2"] = hex2bin("A9926DD53F5C000001E848AFC89C40C3509C40C350EFFF0A0F050A0000000032000000B200B20086007F00AF0075007F87A814AC9350");
		$placeholderTrainer4[2]["pokemon3"] = hex2bin("916D4155563F000001E848AFC8C350AFC89C40C350FDDE140F14050000000032000000BE00BE00890081008F00AB00888AAB0FE35050");
		$placeholderTrainer4[2]["message_start"] = hex2bin("350306040d06290B050D0000");
		$placeholderTrainer4[2]["message_win"] = hex2bin("160E30040b0D2908160D0005");
		$placeholderTrainer4[2]["message_lose"] = hex2bin("110E30043007060529080305");
		
		// Trainer 6
		$placeholderTrainer4[1] = array();
		$placeholderTrainer4[1]["name"] = hex2bin("C0D1D75050");
		$placeholderTrainer4[1]["class"] = hexdec("35");
		$placeholderTrainer4[1]["pokemon1"] = hex2bin("E3AEC913D35C000001E848AFC8C350C350C3509C40D7ED0A0F190A0000000032000000A400A4007D00B30074005300718380E39F1350");
		$placeholderTrainer4[1]["pokemon2"] = hex2bin("CD92C95C99CF000001E848C350C350D6D8AFC89C40CFDD0A0A050F0000000032000000A900A9008600BD0054006700679BF4A7938C50");
		$placeholderTrainer4[1]["pokemon3"] = hex2bin("D06DC9E79C59000001E848AFC8C350C3509C40AFC8DDDD0A0F0A0A0000000032000000B000B0008200F500490063006D990597A65050");
		$placeholderTrainer4[1]["message_start"] = hex2bin("180403050b0D2c041e0C0105");
		$placeholderTrainer4[1]["message_win"] = hex2bin("1f051d071d072c0421070105");
		$placeholderTrainer4[1]["message_lose"] = hex2bin("0a051202320D110C0c061a0D");
		
		// Trainer 7
		$placeholderTrainer4[0] = array();
		$placeholderTrainer4[0]["name"] = hex2bin("BBB628C150");
		$placeholderTrainer4[0]["class"] = hexdec("1E");
		$placeholderTrainer4[0]["pokemon1"] = hex2bin("CB8C8AF25E59000001E8489C409C409C409C409C4045560F0F0A0A6400000032000000A100A1007200640078007E006586D8ABD88650");
		$placeholderTrainer4[0]["pokemon2"] = hex2bin("826D3F39F0C0000001E8489C409C409C409C409C407565050F05056400000032000000C100C100A200720075005F008706ADA5138C50");
		$placeholderTrainer4[0]["pokemon3"] = hex2bin("90AE3B3F2EC4000001E8489C409C409C409C409C4045560505140F0000000032000000B500B5007700870078008300A19BD8E30AE350");
		$placeholderTrainer4[0]["message_start"] = hex2bin("0c0C37031a034004230B260A");
		$placeholderTrainer4[0]["message_win"] = hex2bin("3f0609041b07370322082706");
		$placeholderTrainer4[0]["message_lose"] = hex2bin("040E03050000260A3b081b07");
		
		return $placeholderTrainer4[$number];
	}
	
	function getBattleTowerPlaceholderTrainerJP5($number) {
		$placeholderTrainer5 = array();
		
		// Trainer 1
		$placeholderTrainer5[6] = array();
		$placeholderTrainer5[6]["name"] = hex2bin("BD27D3D850");
		$placeholderTrainer5[6]["class"] = hexdec("14");
		$placeholderTrainer5[6]["pokemon1"] = hex2bin("E692E1393F3B0000034BC0D6D8D6D8C350EA60EA60DDFE140F0505640000003C000000D300D300A900A700A100AC00AC86AB0713A550");
		$placeholderTrainer5[6]["pokemon2"] = hex2bin("F8AEF2599D3F0000034BC0D6D8EA60D6D8EA60D6D8FDED0F0A0A05640000003C000000F000F000DC00BB008300A900AF14AB06A58C50");
		$placeholderTrainer5[6]["pokemon3"] = hex2bin("E56D35F28A9C0000034BC0EA60D6D8D6D8EA60D6D8FBEF0F0F0F0A640000003C000000D400D400A5007100AC00BD0099CDA605E35050");
		$placeholderTrainer5[6]["message_start"] = hex2bin("18031b08290B4008320D0005");
		$placeholderTrainer5[6]["message_win"] = hex2bin("2f06290B0b0D2f08230D020D");
		$placeholderTrainer5[6]["message_lose"] = hex2bin("3505130E2e0622062f082706");
		
		// Trainer 2
		$placeholderTrainer5[5] = array();
		$placeholderTrainer5[5]["name"] = hex2bin("BCD3D4CF30");
		$placeholderTrainer5[5]["class"] = hexdec("38");
		$placeholderTrainer5[5]["pokemon1"] = hex2bin("E9923B695C5E0000034BC0D6D8C350C350C350D6D8DDDE05140A0A640000003C000000DF00DF009500A1007D00B600AA43D809AB5050");
		$placeholderTrainer5[5]["pokemon2"] = hex2bin("444907EE09590000034BC0C350C350AFC8C350C350FDEF0F050F0A640000003C000000E200E200D4009400780086009E8581D886E350");
		$placeholderTrainer5[5]["pokemon3"] = hex2bin("91549C4155560000034BC0C350AFC8C350D6D8C350DDFD0A140F14640000003C000000E500E500A0009B00B100CB00A18AAB0FE35050");
		$placeholderTrainer5[5]["message_start"] = hex2bin("1c03020E31032a0D240D0000");
		$placeholderTrainer5[5]["message_win"] = hex2bin("18030b0D3008400804050000");
		$placeholderTrainer5[5]["message_lose"] = hex2bin("1c0501050303020E2704340D");
		
		// Trainer 3
		$placeholderTrainer5[4] = array();
		$placeholderTrainer5[4]["name"] = hex2bin("C9D3C45050");
		$placeholderTrainer5[4]["class"] = hexdec("17");
		$placeholderTrainer5[4]["pokemon1"] = hex2bin("CAAE44F3C2DB0000034BC0C350C350AFC8C350AFC8FDED14140519640000003C0000015A015A005F007A005E005C007A8EE394AB8C50");
		$placeholderTrainer5[4]["pokemon2"] = hex2bin("8E923F30592C0000034BC0AFC8C350C350AFC8AFC8FDDD05140A19640000003C000000D700D700B6008300D0007C008E4292A5505050");
		$placeholderTrainer5[4]["pokemon3"] = hex2bin("956D3FC455390000034BC0AFC8C3509C40C350AFC8DDFD050F0F0F640000003C000000E500E500D600A4009800AC00AC8581D8AEE350");
		$placeholderTrainer5[4]["message_start"] = hex2bin("330330042c041c0B35070005");
		$placeholderTrainer5[4]["message_win"] = hex2bin("30042c0420061e0940064004");
		$placeholderTrainer5[4]["message_lose"] = hex2bin("30042c0420061e0940062207");
		
		// Trainer 4
		$placeholderTrainer5[3] = array();
		$placeholderTrainer5[3]["name"] = hex2bin("2CDEC5B250");
		$placeholderTrainer5[3]["class"] = hexdec("25");
		$placeholderTrainer5[3]["pokemon1"] = hex2bin("C5AEBDEC5EB90000034BC0C350C350C350C350C350FDEF0A050A14640000003C000000E800E8008600B90084008000D41BA5AC86E350");
		$placeholderTrainer5[3]["pokemon2"] = hex2bin("3B8A35F2F5E70000034BC0D6D8C3509C40D6D8C350FDED0F0F050F640000003C000000E400E400BC009200AA00AD00958281AB12B050");
		$placeholderTrainer5[3]["pokemon3"] = hex2bin("E36DD3135CB60000034BC0C350C350AFC8C350C350FBEB190F0A0A640000003C000000C400C4009800DA008A006300878380E39F1350");
		$placeholderTrainer5[3]["message_start"] = hex2bin("220C220C160A350D1c0B3f0D");
		$placeholderTrainer5[3]["message_win"] = hex2bin("1305160A40062307060D0605");
		$placeholderTrainer5[3]["message_lose"] = hex2bin("3f06160A160D37032c042607");
		
		// Trainer 5
		$placeholderTrainer5[2] = array();
		$placeholderTrainer5[2]["name"] = hex2bin("BAB3C95050");
		$placeholderTrainer5[2]["class"] = hexdec("3C");
		$placeholderTrainer5[2]["pokemon1"] = hex2bin("F292875CB65E0000034BC0C350AFC8C350AFC8C350FBCD0A0A0A0A640000003C000001A801A80042003F0075008F00D79941948C5050");
		$placeholderTrainer5[2]["pokemon2"] = hex2bin("8F689D3922590000034BC0C350AFC8C350C350C350FAFC0A0F0F0A640000003C00000133013300BA0080005C008200B8851509AB5050");
		$placeholderTrainer5[2]["pokemon3"] = hex2bin("D677B3E059440000034BC0C3509C40C350C350C350DFED0F0A0A14640000003C000000D600D600C80092009C006500A7CDA587A88C50");
		$placeholderTrainer5[2]["message_start"] = hex2bin("0e060508040D000000000000");
		$placeholderTrainer5[2]["message_win"] = hex2bin("140506051202000000000000");
		$placeholderTrainer5[2]["message_lose"] = hex2bin("23050106180D000000000000");
		
		// Trainer 6
		$placeholderTrainer5[1] = array();
		$placeholderTrainer5[1]["name"] = hex2bin("BED4505050");
		$placeholderTrainer5[1]["class"] = hexdec("34");
		$placeholderTrainer5[1]["pokemon1"] = hex2bin("7C6D3B5EF7C40000034BC0C350C350C350C350C350FFEB050A0F0F640000003C000000C400C40074006200A800BD00A5A6E30BAEA550");
		$placeholderTrainer5[1]["pokemon2"] = hex2bin("09AE3959E53B0000034BC0C350C350C350C350C350FEFE0F0A2805640000003C000000D100D1009B00AE0095009C00B485A0AC878C50");
		$placeholderTrainer5[1]["pokemon3"] = hex2bin("70495939E79D0000034BC0C350C350C350C350C350FBFA0A0F0F0A640000003C000000F500F500D400C30068006800688A8113AB5050");
		$placeholderTrainer5[1]["message_start"] = hex2bin("180327030305200B27060605");
		$placeholderTrainer5[1]["message_win"] = hex2bin("200E12072b0D2c030e083507");
		$placeholderTrainer5[1]["message_lose"] = hex2bin("37032703030542072f080605");
		
		// Trainer 7
		$placeholderTrainer5[0] = array();
		$placeholderTrainer5[0]["name"] = hex2bin("CC2CDCD750");
		$placeholderTrainer5[0]["class"] = hexdec("36");
		$placeholderTrainer5[0]["pokemon1"] = hex2bin("1C8C59A33FAD0000034BC075307530753075307530B7670A14050F000000003C000000C900C900A400AB0074005D00698AAB1318AB50");
		$placeholderTrainer5[0]["pokemon2"] = hex2bin("2FAE93CA3FBC0000034BC075307530753075307530665F0F05050A000000003C000000AB00AB0098008600480078009018A58D879350");
		$placeholderTrainer5[0]["pokemon3"] = hex2bin("4C03995907DA0000034BC0753075307530753075307657050A0F14000000003C000000CD00CD00AB00C2005A0069007509A8E395AD50");
		$placeholderTrainer5[0]["message_start"] = hex2bin("3603300417042c030c043b0D");
		$placeholderTrainer5[0]["message_win"] = hex2bin("18033207150D35050d07070D");
		$placeholderTrainer5[0]["message_lose"] = hex2bin("090506050f0C0904070D3b0D");
		
		return $placeholderTrainer5[$number];
	}
	
	function getBattleTowerPlaceholderTrainerJP6($number) {
		$placeholderTrainer6 = array();
		
		// Trainer 1
		$placeholderTrainer6[6] = array();
		$placeholderTrainer6[6]["name"] = hex2bin("C6BC305050");
		$placeholderTrainer6[6]["class"] = hexdec("19");
		$placeholderTrainer6[6]["pokemon1"] = hex2bin("876D553FF72E0000053BD8EA60DAC0D6D8C350EA60FBEF0F050F146400000046000000E700E7009E009100F500DE00C98AAB0FE38C50");
		$placeholderTrainer6[6]["pokemon2"] = hex2bin("86923BF739BD0000053BD8C350C350EA60EA60C350BFEF050F0F0A64000000460000013E013E00950098009E00DA00C58BADA9E30C50");
		$placeholderTrainer6[6]["pokemon3"] = hex2bin("C5AEB9ECF45C0000053BD8D2F0EA60D6D8C350E290DDDD14050A0A6400000046000001120112009C00D90098009500F71BA5AC86E350");
		$placeholderTrainer6[6]["message_start"] = hex2bin("0b05140624040a0D3c0D0005");
		$placeholderTrainer6[6]["message_win"] = hex2bin("14050902320D1e060407060D");
		$placeholderTrainer6[6]["message_lose"] = hex2bin("1a0533040305160200020705");
		
		// Trainer 2
		$placeholderTrainer6[5] = array();
		$placeholderTrainer6[5]["name"] = hex2bin("D6BCB5B650");
		$placeholderTrainer6[5]["class"] = hexdec("20");
		$placeholderTrainer6[5]["pokemon1"] = hex2bin("F2AE4487F7550000053BD8D6D8D6D8D6D8E290C350DFED140A0F0F6400000046000001EF01EF004D0050008F00A600FA9941948C5050");
		$placeholderTrainer6[5]["pokemon2"] = hex2bin("8F929D593BF70000053BD8D6D8D6D8EA60D6D8C350DDDD0A0A050F64000000460000016D016D00D9009C0069009800D7851509AB5050");
		$placeholderTrainer6[5]["pokemon3"] = hex2bin("E552F235B92E0000053BD8E290C350D6D8EA60D6D8DDCD0F0F14146400000046000000F500F500BB008500C500D900AFCDA605E35050");
		$placeholderTrainer6[5]["message_start"] = hex2bin("0a0332070b0E22062c04290B");
		$placeholderTrainer6[5]["message_win"] = hex2bin("0a03120D24041a0B330D030D");
		$placeholderTrainer6[5]["message_lose"] = hex2bin("0a0335041b0706051e061407");
		
		// Trainer 3
		$placeholderTrainer6[4] = array();
		$placeholderTrainer6[4]["name"] = hex2bin("B1BBC95050");
		$placeholderTrainer6[4]["class"] = hexdec("3E");
		$placeholderTrainer6[4]["pokemon1"] = hex2bin("F89259F29D3F0000053BD8C350AFC8AFC8C350AFC8DBDF0A0F0A05640000004600000117011700F700D3009300C400CB14AB06A58C50");
		$placeholderTrainer6[4]["pokemon2"] = hex2bin("91AE5541563F0000053BD8AFC8C350C350AFC8AFC8DBDF0F141405640000004600000108010800BB00B100C800EE00BD8AAB0FE35050");
		$placeholderTrainer6[4]["pokemon3"] = hex2bin("676D9C995ECA0000053BD8AFC8C3509C40C350AFC8DDED0A050A0564000000460000010C010C00C200B1008C00EB009794AC8BE35050");
		$placeholderTrainer6[4]["message_start"] = hex2bin("0e062c04000D06033104040D");
		$placeholderTrainer6[4]["message_win"] = hex2bin("0c051202000540030904010D");
		$placeholderTrainer6[4]["message_lose"] = hex2bin("35053608000502033104210D");
		
		// Trainer 4
		$placeholderTrainer6[3] = array();
		$placeholderTrainer6[3]["name"] = hex2bin("C13A505050");
		$placeholderTrainer6[3]["class"] = hexdec("1E");
		$placeholderTrainer6[3]["pokemon1"] = hex2bin("C5AEECB95EF70000053BD8C350C350AFC8AFC8C350FDEB05140A0F64000000460000010D010D009B00D60098008E00F01BA5AC86E350");
		$placeholderTrainer6[3]["pokemon2"] = hex2bin("820339553F2E0000053BD8D6D8AFC8C350D6D8C350DBEF0F0F051464000000460000010F010F00EB00A900B2009400CC06ADA5138C50");
		$placeholderTrainer6[3]["pokemon3"] = hex2bin("C36D5939BCE70000053BD8C350C350AFC8C350C350DEDD0A0F0A0F64000000460000010A010A00B400B4006E009800989684E3505050");
		$placeholderTrainer6[3]["message_start"] = hex2bin("2205070E3004c50010020105");
		$placeholderTrainer6[3]["message_win"] = hex2bin("0c0C00013004820021040105");
		$placeholderTrainer6[3]["message_lose"] = hex2bin("100111053004c3003f040605");
		
		// Trainer 5
		$placeholderTrainer6[2] = array();
		$placeholderTrainer6[2]["name"] = hex2bin("B2BCB6DC50");
		$placeholderTrainer6[2]["class"] = hexdec("16");
		$placeholderTrainer6[2]["pokemon1"] = hex2bin("D98CA3593F090000053BD8C350AFC8C350AFC8C350FDED140A050F640000004600000106010600F500A6008A00A600A6D8AB079D5050");
		$placeholderTrainer6[2]["pokemon2"] = hex2bin("7A5273075EE30000053BD8C350AFC8AFC8C350C350BDFB140F0A056400000046000000C300C30078009700BE00C600E214D8A2E31350");
		$placeholderTrainer6[2]["pokemon3"] = hex2bin("3949EE08099D0000053BD8C3509C40C350C350C350BDEF050F0F0A6400000046000000E300E300CA009100C4009400A28489D80AA650");
		$placeholderTrainer6[2]["message_start"] = hex2bin("3a073b07360330042104060D");
		$placeholderTrainer6[2]["message_win"] = hex2bin("170E030500003f062104210D");
		$placeholderTrainer6[2]["message_lose"] = hex2bin("36053603300433063f040c0D");
		
		// Trainer 6
		$placeholderTrainer6[1] = array();
		$placeholderTrainer6[1]["name"] = hex2bin("B2DEC5D050");
		$placeholderTrainer6[1]["class"] = hexdec("22");
		$placeholderTrainer6[1]["pokemon1"] = hex2bin("CBAE61E2F2590000053BD8C350C350C350C350C350FEFD1E280F0A6400000046000000E700E700B0009A00B700BB009886D8ABD88650");
		$placeholderTrainer6[1]["pokemon2"] = hex2bin("6A77B3CB22190000053BD8C350C350C350C350C350FEFE0F0A0F056400000046000000CA00CA00E8008900BA007000D98AA99FA5E350");
		$placeholderTrainer6[1]["pokemon3"] = hex2bin("D603B3CBE0590000053BD8C350C350C350C350C350F7F70F0A0A0A6400000046000000FB00FB00EF009E00B7006D00BACDA587A88C50");
		$placeholderTrainer6[1]["message_start"] = hex2bin("43032004410D170606030a04");
		$placeholderTrainer6[1]["message_win"] = hex2bin("3a061304040D08063f04310D");
		$placeholderTrainer6[1]["message_lose"] = hex2bin("43033904040517063904410D");
		
		// Trainer 7
		$placeholderTrainer6[0] = array();
		$placeholderTrainer6[0]["name"] = hex2bin("BADE34B350");
		$placeholderTrainer6[0]["class"] = hexdec("28");
		$placeholderTrainer6[0]["pokemon1"] = hex2bin("0303F14CEB3F0000053BD8753075307530753075307644050A05050000000046000000E900E9009F009F009800B400B49B8B06145050");
		$placeholderTrainer6[0]["pokemon2"] = hex2bin("068CA3593F350000053BD8753075307530753075305644140A050F0000000046000000E600E6009F009800B400C1009FD80AE313AB50");
		$placeholderTrainer6[0]["pokemon3"] = hex2bin("094938083FE70000053BD8753075307530753075307664050F050F0000000046000000E700E700A100B70098009F00BB85A0AC878C50");
		$placeholderTrainer6[0]["message_start"] = hex2bin("3603160408060908060D0000");
		$placeholderTrainer6[0]["message_win"] = hex2bin("1f0B0305120E0f0E2c0D0005");
		$placeholderTrainer6[0]["message_lose"] = hex2bin("3a061604380638062c0D0005");
		
		return $placeholderTrainer6[$number];
	}
	
	function getBattleTowerPlaceholderTrainerJP7($number) {
		$placeholderTrainer7 = array();
		
		// Trainer 1
		$placeholderTrainer7[6] = array();
		$placeholderTrainer7[6]["name"] = hex2bin("CCB8C52650");
		$placeholderTrainer7[6]["class"] = hexdec("32");
		$placeholderTrainer7[6]["pokemon1"] = hex2bin("876D5655E72E000007D000EA60D6D8EA60D6D8D6D8FDEB140F0F14640000005000000107010700B300AA011900F500DD8AAB0FE38C50");
		$placeholderTrainer7[6]["pokemon2"] = hex2bin("8F929CBBAD59000007D000EA60D6D8C350D8CCEA60DBED0A0A0F0A64000000500000019F019F00F800AA007900B200FA851509AB5050");
		$placeholderTrainer7[6]["pokemon3"] = hex2bin("E5549CF235F1000007D000D6D8C350D6D8D6D8D6D8FDDB0A0F0F05640000005000000118011800D9009800E000F500C5CDA605E35050");
		$placeholderTrainer7[6]["message_start"] = hex2bin("1105250333042f08190D0005");
		$placeholderTrainer7[6]["message_win"] = hex2bin("110525033f062604190D0005");
		$placeholderTrainer7[6]["message_lose"] = hex2bin("1105210335041407190D0605");
		
		// Trainer 2
		$placeholderTrainer7[5] = array();
		$placeholderTrainer7[5]["name"] = hex2bin("B1CD505050");
		$placeholderTrainer7[5]["class"] = hexdec("29");
		$placeholderTrainer7[5]["pokemon1"] = hex2bin("80AE5922E73F000007D000C350C350C3507530C350FDDE0A0F0F05640000005000000114011400E900DD00EC008700B788AB8FA88C50");
		$placeholderTrainer7[5]["pokemon2"] = hex2bin("83549C396D5E000007D000C350C350C350D6D8C350DFDB0A0F0A0A64000000500000016E016E00CD00C900A800CA00DAA542A58C5050");
		$placeholderTrainer7[5]["pokemon3"] = hex2bin("F86D9CF2599D000007D000C350D6D8C350D6D8C350DFDB0A0F0A0A64000000500000013E013E011E00F900A900DA00E214AB06A58C50");
		$placeholderTrainer7[5]["message_start"] = hex2bin("2c040a0D04053303290B050D");
		$placeholderTrainer7[5]["message_win"] = hex2bin("000604050902330307043507");
		$placeholderTrainer7[5]["message_lose"] = hex2bin("33033a08050D3f062908050D");
		
		// Trainer 3
		$placeholderTrainer7[4] = array();
		$placeholderTrainer7[4]["name"] = hex2bin("B6DCB25050");
		$placeholderTrainer7[4]["class"] = hexdec("1C");
		$placeholderTrainer7[4]["pokemon1"] = hex2bin("5E0055F76DA8000007D000C350C350AFC8D6D8C350DEDD0F0F0A0A6400000050000000F700F700AD00A500F8011500BD08AB05E35050");
		$placeholderTrainer7[4]["pokemon2"] = hex2bin("CD92995C4CCF000007D000AFC8C350C350AFC8C350FDED050A0A0F640000005000000111011100D90125008500A500A59BF4A7938C50");
		$placeholderTrainer7[4]["pokemon3"] = hex2bin("E6549C393BE1000007D000AFC8C3509C40D6D8C350FBED0A0F0514640000005000000111011100E100D600D100DD00DD86AB0713A550");
		$placeholderTrainer7[4]["message_start"] = hex2bin("05031b044103390B3a0B0105");
		$placeholderTrainer7[4]["message_win"] = hex2bin("2c040305000040073e0D0005");
		$placeholderTrainer7[4]["message_lose"] = hex2bin("000E0905040519091d0B0305");
		
		// Trainer 4
		$placeholderTrainer7[3] = array();
		$placeholderTrainer7[3]["name"] = hex2bin("B52EB75050");
		$placeholderTrainer7[3]["class"] = hexdec("26");
		$placeholderTrainer7[3]["pokemon1"] = hex2bin("95AE563955C8000007D000C350C350C350C350AFC8DDDD140F0F0F64000000500000012F012F011C00DD00C500E400E48581D8AEE350");
		$placeholderTrainer7[3]["pokemon2"] = hex2bin("E9925E693FA1000007D000D6D8C3509C40D6D8C350DFED0A14050A640000005000000125012500C500D500A900ED00DD43D809AB5050");
		$placeholderTrainer7[3]["pokemon3"] = hex2bin("7C498E3B8A5E000007D000D6D8C350AFC8C350C350DFDF0A050F0A64000000500000010801080095007F00DD010100E1A6E30BAEA550");
		$placeholderTrainer7[3]["message_start"] = hex2bin("1c0C200605083e0D00050000");
		$placeholderTrainer7[3]["message_win"] = hex2bin("18031f0E110A1c0B150B1c06");
		$placeholderTrainer7[3]["message_lose"] = hex2bin("030E060C210C140D06051f08");
		
		// Trainer 5
		$placeholderTrainer7[2] = array();
		$placeholderTrainer7[2]["name"] = hex2bin("CABE26DC50");
		$placeholderTrainer7[2]["class"] = hexdec("18");
		$placeholderTrainer7[2]["pokemon1"] = hex2bin("E2AE396D3B11000007D0009C40AFC89C40AFC8C350DFDC0F0A05236400000050000001000100008400B500B400C401249DAB8F81AB50");
		$placeholderTrainer7[2]["pokemon2"] = hex2bin("E349D313BD5C000007D000C350AFC888B8C350C350DDEF190F0A0A640000005000000102010200C4011E00B7008900B98380E39F1350");
		$placeholderTrainer7[2]["pokemon3"] = hex2bin("928A358FD33F000007D000C3509C40C3509C40C350DDFE0F05190564000000500000012C012C00E100D500D5010F00CF9BE981A2E350");
		$placeholderTrainer7[2]["message_start"] = hex2bin("37030e0108011906270B0302");
		$placeholderTrainer7[2]["message_win"] = hex2bin("0e0108011b080508210D0000");
		$placeholderTrainer7[2]["message_lose"] = hex2bin("360333040405000441083b0D");
		
		// Trainer 6
		$placeholderTrainer7[1] = array();
		$placeholderTrainer7[1]["name"] = hex2bin("D4C527BBDC");
		$placeholderTrainer7[1]["class"] = hexdec("3A");
		$placeholderTrainer7[1]["pokemon1"] = hex2bin("8E6D3F9C592E000007D000C3509C40C3509C40C350FFED050A0A1464000000500000011A011A00ED00B1011300A500BD4292A5505050");
		$placeholderTrainer7[1]["pokemon2"] = hex2bin("65525599F35C000007D000C350C3509C409C40C350FFEF0F05140A6400000050000000FA00FA009900B5012300C900C99DA69D81AB50");
		$placeholderTrainer7[1]["pokemon3"] = hex2bin("338CA359A8BD000007D000C350C3509C40C3509C40FDDD140A0A0A6400000050000000D600D600C900910105009100B10F0793D88450");
		$placeholderTrainer7[1]["message_start"] = hex2bin("06020005340300030d0D0005");
		$placeholderTrainer7[1]["message_win"] = hex2bin("3c06050816042f08330D020D");
		$placeholderTrainer7[1]["message_lose"] = hex2bin("1c050005180305081b040c0D");
		
		// Trainer 7
		$placeholderTrainer7[0] = array();
		$placeholderTrainer7[0]["name"] = hex2bin("D3DBB5B650");
		$placeholderTrainer7[0]["class"] = hexdec("19");
		$placeholderTrainer7[0]["pokemon1"] = hex2bin("4749CABC3F5C000007D000753075307530753075306565050A050A000000005000000104010400D9009700A100CF008F829117AC9350");
		$placeholderTrainer7[0]["pokemon2"] = hex2bin("7FAE3F42465C000007D00075307530753075307530746405190F0A0000000050000000F100F100FA00CD00B90085009D8581A88C5050");
		$placeholderTrainer7[0]["pokemon3"] = hex2bin("D2032EF73F09000007D000753075307530753075307657140F050F00000000500000011E011E00F200A900770092009207A5AB16A650");
		$placeholderTrainer7[0]["message_start"] = hex2bin("2c0413092006390320043c0D");
		$placeholderTrainer7[0]["message_win"] = hex2bin("050343040b0D2204060D310D");
		$placeholderTrainer7[0]["message_lose"] = hex2bin("2408000502030908060D3b0D");
		
		return $placeholderTrainer7[$number];
	}
	
	function getBattleTowerPlaceholderTrainerJP8($number) {
		$placeholderTrainer8 = array();
		
		// Trainer 1
		$placeholderTrainer8[6] = array();
		$placeholderTrainer8[6]["name"] = hex2bin("CBD7D4CF50");
		$placeholderTrainer8[6]["class"] = hexdec("41");
		$placeholderTrainer8[6]["pokemon1"] = hex2bin("C552B9BDEC6D00000B1FA8EA60EA60D6D8EA60D6D8FDED140A050A640000005A0000015D015D00CB011600CA00BC013A1BA5AC86E350");
		$placeholderTrainer8[6]["pokemon2"] = hex2bin("95497E3FC83B00000B1FA8FDE8C350DAC0EA60EA60FDED05050F05640000005A000001570157014200FC00E5010701078581D8AEE350");
		$placeholderTrainer8[6]["pokemon3"] = hex2bin("79926955395E00000B1FA8EA60EA60EA60D6D8EA60DFDD140F0F0A640000005A00000121012100DA00EF011F010700EC8C8FE39EE350");
		$placeholderTrainer8[6]["message_start"] = hex2bin("06032004240D2404100B0005");
		$placeholderTrainer8[6]["message_win"] = hex2bin("390700052a0604043c040000");
		$placeholderTrainer8[6]["message_lose"] = hex2bin("24041c0B070D2f0809060605");
		
		// Trainer 2
		$placeholderTrainer8[5] = array();
		$placeholderTrainer8[5]["name"] = hex2bin("B626505050");
		$placeholderTrainer8[5]["class"] = hexdec("21");
		$placeholderTrainer8[5]["pokemon1"] = hex2bin("5B92993B39C400000B1FA8C350C350C350C350C350DBDF05050F0F640000005A0000010A010A00F8018E00CB00EA00A218A68BEBAB50");
		$placeholderTrainer8[5]["pokemon2"] = hex2bin("A9AE11723FCA00000B1FA8C350C350C350C350C350FDCF231E0505640000005A00000145014500F300DD013600CF00E187A814AC9350");
		$placeholderTrainer8[5]["pokemon3"] = hex2bin("E9495C5E69B600000B1FA8C350AFC8C350D6D8C350FDED0A0A140A640000005A00000145014500DF00EF00BE010A00F843D809AB5050");
		$placeholderTrainer8[5]["message_start"] = hex2bin("4407230B330D2c0420062408");
		$placeholderTrainer8[5]["message_win"] = hex2bin("240800050e0E1208090B2806");
		$placeholderTrainer8[5]["message_lose"] = hex2bin("24080005020313061108410D");
		
		// Trainer 3
		$placeholderTrainer8[4] = array();
		$placeholderTrainer8[4]["name"] = hex2bin("D43CB75050");
		$placeholderTrainer8[4]["class"] = hexdec("17");
		$placeholderTrainer8[4]["pokemon1"] = hex2bin("E692E1393F3B00000B1FA8C350C350D6D8C350AFC8DFDE140F0505640000005A00000135013500F800FF00E600F800F886AB0713A550");
		$placeholderTrainer8[4]["pokemon2"] = hex2bin("F8493FF2599D00000B1FA8C350D6D8C350AFC8C350DFDE050F0A0A640000005A0000016201620141011700B900FA010314AB06A58C50");
		$placeholderTrainer8[4]["pokemon3"] = hex2bin("83549C39555E00000B1FA8AFC8C350C350C350D6D8BDEF0A0F0F0A640000005A00000195019500E300DD00BB00ED00FFA542A58C5050");
		$placeholderTrainer8[4]["message_start"] = hex2bin("3603060630040f0B020B3b0D");
		$placeholderTrainer8[4]["message_win"] = hex2bin("310D000539032004210D0000");
		$placeholderTrainer8[4]["message_lose"] = hex2bin("040E02050606200E0b062706");
		
		// Trainer 4
		$placeholderTrainer8[3] = array();
		$placeholderTrainer8[3]["name"] = hex2bin("D0BCCF5050");
		$placeholderTrainer8[3]["class"] = hexdec("27");
		$placeholderTrainer8[3]["pokemon1"] = hex2bin("C4AE5EF7F1EA00000B1FA8D6D8C350C350D6D8C350DDFE0A0F0505640000005A00000126012600C200B9011A013900FA83E39BB05050");
		$placeholderTrainer8[3]["pokemon2"] = hex2bin("4449EEE97E5900000B1FA8D6D8D6D8C350D6D8C350DDED050A050A640000005A000001510151013A00DD00B500C200E68581D886E350");
		$placeholderTrainer8[3]["pokemon3"] = hex2bin("8F6D7E39593F00000B1FA8AFC8C350D6D8C350C350FEFD050F0A05640000005A000001C701C7011700C7008700C20113851509AB5050");
		$placeholderTrainer8[3]["message_start"] = hex2bin("14050402090E08082e08410D");
		$placeholderTrainer8[3]["message_win"] = hex2bin("34052e08210D3f0318080005");
		$placeholderTrainer8[3]["message_lose"] = hex2bin("360505020005170639040005");
		
		// Trainer 5
		$placeholderTrainer8[2] = array();
		$placeholderTrainer8[2]["name"] = hex2bin("CCB8B25050");
		$placeholderTrainer8[2]["class"] = hexdec("16");
		$placeholderTrainer8[2]["pokemon1"] = hex2bin("3B54F135F59C00000B1FA8C350AFC8C350AFC8D6D8DFDE050F050A640000005A000001500150011200E100F7010600E28281AB12B050");
		$placeholderTrainer8[2]["pokemon2"] = hex2bin("F2924CF1877E00000B1FA8C350AFC8C350C350C350BDFE0A050A05640000005A000001A801A8005A005F00B400D601429941948C5050");
		$placeholderTrainer8[2]["pokemon3"] = hex2bin("E50335F2F14C00000B1FA8C3509C40C350C350C350DBFE0F0F050A640000005A00000135013500EB00A400FC011500DFCDA605E35050");
		$placeholderTrainer8[2]["message_start"] = hex2bin("34030003110D41080b060604");
		$placeholderTrainer8[2]["message_win"] = hex2bin("390700050000360330040708");
		$placeholderTrainer8[2]["message_lose"] = hex2bin("1005150800053a061e06140D");
		
		// Trainer 6
		$placeholderTrainer8[1] = array();
		$placeholderTrainer8[1]["name"] = hex2bin("CBD7B2DC50");
		$placeholderTrainer8[1]["class"] = hexdec("2B");
		$placeholderTrainer8[1]["pokemon1"] = hex2bin("E349C9D35CD800000B1FA8C350C350C350C350C350EFF70A190A14FF0000005A00000117011700DF014D00CF008B00C18380E39F1350");
		$placeholderTrainer8[1]["pokemon2"] = hex2bin("D5925C23B6E300000B1FA8C350C350C350C350C350FEFE0A140A05640000005A000000CB00CB006301ED005A006101ED911791175050");
		$placeholderTrainer8[1]["pokemon3"] = hex2bin("88543F35F72E00000B1FA8C350C350C350C350C350F7F7050F0F14640000005A000001250125013B00AF00C600EE010916E38C8FE350");
		$placeholderTrainer8[1]["message_start"] = hex2bin("370537050005380538054105");
		$placeholderTrainer8[1]["message_win"] = hex2bin("250504053205310541054105");
		$placeholderTrainer8[1]["message_lose"] = hex2bin("0f0504050f050f0516050105");
		
		// Trainer 7
		$placeholderTrainer8[0] = array();
		$placeholderTrainer8[0]["name"] = hex2bin("B7BC2BB750");
		$placeholderTrainer8[0]["class"] = hexdec("24");
		$placeholderTrainer8[0]["pokemon1"] = hex2bin("F192D059D52200000B1FA87530753075307530753047570A0A0F0F000000005A00000142014200C200F500E8008000B69EA68FAB8750");
		$placeholderTrainer8[0]["pokemon2"] = hex2bin("8068553FD55900000B1FA87530753075307530753065760F050F0A000000005A0000011C011C00EA00DF00FE007E00B488AB8FA88C50");
		$placeholderTrainer8[0]["pokemon3"] = hex2bin("59495CBCD5CA00000B1FA87530753075307530753054440A0A0F05000000005A00000156015600F100B9008C00A700E63D933D93AB50");
		$placeholderTrainer8[0]["message_start"] = hex2bin("1f0E3604400D2f081a0D0005");
		$placeholderTrainer8[0]["message_win"] = hex2bin("2106350524061a06120E0000");
		$placeholderTrainer8[0]["message_lose"] = hex2bin("1b0531060205280D270D0000");
		
		return $placeholderTrainer8[$number];
	}
	
	function getBattleTowerPlaceholderTrainerJP9($number) {
		$placeholderTrainer9 = array();
		
		// Trainer 1
		$placeholderTrainer9[6] = array();
		$placeholderTrainer9[6]["name"] = hex2bin("C02CD85050");
		$placeholderTrainer9[6]["class"] = hexdec("24");
		$placeholderTrainer9[6]["pokemon1"] = hex2bin("E554F2352E9C00000F4240EA60EA60EA60EA60EA60FDED0F0F140A64000000640000015B015B011400C0011C013800FCCDA605E35050");
		$placeholderTrainer9[6]["pokemon2"] = hex2bin("4449EE593FE900000F4240EA60EA60EA60EA60EA60FDEF050A050A6400000064000001790179016400FC00CC00E2010A8581D886E350");
		$placeholderTrainer9[6]["pokemon3"] = hex2bin("E69239E19C5C00000F4240EA60EA60EA60EA60EA60DFFE0F140A0A64000000640000015D015D011A011E010A011C011C86AB0713A550");
		$placeholderTrainer9[6]["message_start"] = hex2bin("41080b063703080620043b0D");
		$placeholderTrainer9[6]["message_win"] = hex2bin("150216062607130C3308320D");
		$placeholderTrainer9[6]["message_lose"] = hex2bin("2d07000535030b0428060605");
		
		// Trainer 2
		$placeholderTrainer9[5] = array();
		$placeholderTrainer9[5]["name"] = hex2bin("B7B8C15050");
		$placeholderTrainer9[5]["class"] = hexdec("1E");
		$placeholderTrainer9[5]["pokemon1"] = hex2bin("8703552E56E700000F4240C350C350C3507530C350FDFE0F14140F640000006400000143014300DC00CE0152013401168AAB0FE38C50");
		$placeholderTrainer9[5]["pokemon2"] = hex2bin("80523F59E75500000F4240C350C350C350C350C350FDEF050A0F0F640000006400000155015501220114013400AA00E688AB8FA88C50");
		$placeholderTrainer9[5]["pokemon3"] = hex2bin("3B9235F5E73F00000F4240D6D8C350C350D6D8C350DDEF0F050F056400000064000001760176013200F60119012200FA8281AB12B050");
		$placeholderTrainer9[5]["message_start"] = hex2bin("0c082c041c0B06070a0D0305");
		$placeholderTrainer9[5]["message_win"] = hex2bin("3006060700052c0420062407");
		$placeholderTrainer9[5]["message_lose"] = hex2bin("30060b0700052c0420062207");
		
		// Trainer 3
		$placeholderTrainer9[4] = array();
		$placeholderTrainer9[4]["name"] = hex2bin("D3D8DCB750");
		$placeholderTrainer9[4]["class"] = hexdec("14");
		$placeholderTrainer9[4]["pokemon1"] = hex2bin("068C3559A31300000F4240C350C350D6D8D6D8D6D8FEDF0F0A140F6400000064000001570157010200F7012101370107D80AE313AB50");
		$placeholderTrainer9[4]["pokemon2"] = hex2bin("6503565599F300000F4240AFC8C350C350AFC8AFC8FBEF140F0514640000006400000135013500BE00DE016E00F800F89DA69D81AB50");
		$placeholderTrainer9[4]["pokemon3"] = hex2bin("706D39593F9D00000F4240D6D8C350D6D8C350AFC8FDEF0F0A050A6400000064000001940194015E014900A800B200B28A8113AB5050");
		$placeholderTrainer9[4]["message_start"] = hex2bin("220C220C0a09040B02072706");
		$placeholderTrainer9[4]["message_win"] = hex2bin("0f0C0a0A2206020727060405");
		$placeholderTrainer9[4]["message_lose"] = hex2bin("3a060e0A2206050802070405");
		
		// Trainer 4
		$placeholderTrainer9[3] = array();
		$placeholderTrainer9[3]["name"] = hex2bin("C0C5B65050");
		$placeholderTrainer9[3]["class"] = hexdec("29");
		$placeholderTrainer9[3]["pokemon1"] = hex2bin("D092593FCFF200000F4240C350C350D6D8EA60C350FDDE0A050F0F6400000064000001570157010401E9009800C600DA990597A65050");
		$placeholderTrainer9[3]["pokemon2"] = hex2bin("165241D33FBD00000F4240D6D8C350C350D6D8C350FDCF1419050A6400000064000001440144010E00D8011F00D400D4849513D8A650");
		$placeholderTrainer9[3]["pokemon3"] = hex2bin("C877C3D4DCF700000F4240AFC8C350D6D8C350D6D8BDEF0505140F640000006400000135013500CA00D10102010701079F829D505050");
		$placeholderTrainer9[3]["message_start"] = hex2bin("2d0B0208370D100529070305");
		$placeholderTrainer9[3]["message_win"] = hex2bin("200E0e0E0d06420730082806");
		$placeholderTrainer9[3]["message_lose"] = hex2bin("100504050405100510050405");
		
		// Trainer 5
		$placeholderTrainer9[2] = array();
		$placeholderTrainer9[2]["name"] = hex2bin("B2B2C95050");
		$placeholderTrainer9[2]["class"] = hexdec("27");
		$placeholderTrainer9[2]["pokemon1"] = hex2bin("D78CA33B8AB900000F4240C350C350BB80AFC8C350FDEF14050F1464000000640000012D012D011800C3013C00A000F095AEE3A55050");
		$placeholderTrainer9[2]["pokemon2"] = hex2bin("D449D33FA35C00000F4240C350C350C350C350AFC8FBFE1905140A64000000640000014D014D015E011A00DC00C400F699AC8A9F5050");
		$placeholderTrainer9[2]["pokemon3"] = hex2bin("F292553B7E8700000F4240C3509C40C35075307530DDFE0F05050A6400000064000002BF02BF0065006A00BC00E2015A9941948C5050");
		$placeholderTrainer9[2]["message_start"] = hex2bin("3f03160E3f080b0D2f08410D");
		$placeholderTrainer9[2]["message_win"] = hex2bin("3405000502033a063107310D");
		$placeholderTrainer9[2]["message_lose"] = hex2bin("210E03053f03160E26070305");
		
		// Trainer 6
		$placeholderTrainer9[1] = array();
		$placeholderTrainer9[1]["name"] = hex2bin("C0B6275050");
		$placeholderTrainer9[1]["class"] = hexdec("2D");
		$placeholderTrainer9[1]["pokemon1"] = hex2bin("DD549C3B3F5900000F4240C350C350C350C350C350FEF70A05050A6400000064000001830183012200F800BE00C200C281989FE35050");
		$placeholderTrainer9[1]["pokemon2"] = hex2bin("67495E5C99CA00000F4240C350C350C350C350C350FEFE0A0A050564000000640000017701770118010200C8015200DA94AC8BE35050");
		$placeholderTrainer9[1]["pokemon3"] = hex2bin("8B9239F63B5C00000F4240C350C350C350C350C350FBE70F05050A64000000640000014B014B00D2014C00C6013000D6849F8C8FE350");
		$placeholderTrainer9[1]["message_start"] = hex2bin("3a062c031203170C280B060D");
		$placeholderTrainer9[1]["message_win"] = hex2bin("3a06170C230B0d062f08220D");
		$placeholderTrainer9[1]["message_lose"] = hex2bin("14070405170C1c0B1b070005");
		
		// Trainer 7
		$placeholderTrainer9[0] = array();
		$placeholderTrainer9[0]["name"] = hex2bin("B5B62BB750");
		$placeholderTrainer9[0]["class"] = hexdec("30");
		$placeholderTrainer9[0]["pokemon1"] = hex2bin("4C0399599D7E00000F4240753075307530753075307446050A0A050000000064000001490149011A013C009200AA00BE09A8E395AD50");
		$placeholderTrainer9[0]["pokemon2"] = hex2bin("6B774407090800000F4240753075307530753075306776140F0F0F0000000064000001090109010E00DC00D6008201188315A9A5E350");
		$placeholderTrainer9[0]["pokemon3"] = hex2bin("AB4939F0C06D00000F42407530753075307530753076570F05050A0000000064000001A901A900B200B000C000D600D6A5AB8FE3AB50");
		$placeholderTrainer9[0]["message_start"] = hex2bin("1b0200050c082c04230B320D");
		$placeholderTrainer9[0]["message_win"] = hex2bin("2b071f043b0D090709070005");
		$placeholderTrainer9[0]["message_lose"] = hex2bin("260500053f032207d100320D");
		
		return $placeholderTrainer9[$number];
	}
	
	function getBattleTowerPlaceholderTrainerEN($number) {
		$placeholderTrainer0 = array();
		
		// Trainer 1
		$placeholderTrainer0[6] = array();
		$placeholderTrainer0[6]["name"] = hex2bin("87808D928E8D50505050");
		$placeholderTrainer0[6]["class"] = hexdec("25");
		$placeholderTrainer0[6]["pokemon1"] = hex2bin("876D553FF72E00000003E8C3509C409C4088B89C40DDBD0F050F14640000000A0000002900290019001800250022001F898E8B93848E8D50505050");
		$placeholderTrainer0[6]["pokemon2"] = hex2bin("C492BD5EF45C00000003E89C40C35088B89C409C40EDFB0A0A0A0A640000000A000000270027001A001800230026001F84928F848E8D5050505050");
		$placeholderTrainer0[6]["pokemon3"] = hex2bin("C5AEF7E7F45C00000003E89C409C40AFC8C3509C40DBEF0F0F0A0A640000000A0000002E002E00190022001A00190027948C8191848E8D50505050");
		$placeholderTrainer0[6]["message_start"] = hex2bin("01030E0924033004");
		$placeholderTrainer0[6]["message_win"] = hex2bin("1A0B30040F0D2004");
		$placeholderTrainer0[6]["message_lose"] = hex2bin("370320040B060605");
		
		// Trainer 2
		$placeholderTrainer0[5] = array();
		$placeholderTrainer0[5]["name"] = hex2bin("92809698849150505050");
		$placeholderTrainer0[5]["class"] = hexdec("1E");
		$placeholderTrainer0[5]["pokemon1"] = hex2bin("CA7744F3DBC200000003E8C350C350C350C350C3507FD714141905640000000A00000042004200120019001300120017968E818194858584935050");
		$placeholderTrainer0[5]["pokemon2"] = hex2bin("736DB33F59D500000003E89C4075309C4075307530EFCF0F050A0F640000000A0000002F002F001F001D001D0014001C8A808D8680928A87808D50");
		$placeholderTrainer0[5]["pokemon3"] = hex2bin("DE8C395E69F600000003E89C407530821475307530FEFD0F0A1405640000000A0000002600260017001D00130018001C828E91928E8B8050505050");
		$placeholderTrainer0[5]["message_start"] = hex2bin("2403CA000B0D2004");
		$placeholderTrainer0[5]["message_win"] = hex2bin("CA00050C05040105");
		$placeholderTrainer0[5]["message_lose"] = hex2bin("CA00280B12042705");
		
		// Trainer 3
		$placeholderTrainer0[4] = array();
		$placeholderTrainer0[4]["name"] = hex2bin("8C809294838050505050");
		$placeholderTrainer0[4]["class"] = hexdec("2B");
		$placeholderTrainer0[4]["pokemon1"] = hex2bin("F1AE3B593F5C00000003E8753075307530753088B8BBDF050A050A640000000A0000002E002E001B0020001F0014001A8C888B93808D8A50505050");
		$placeholderTrainer0[4]["pokemon2"] = hex2bin("8E923F30592C00000003E875307530753075307530DBFB05140A19640000000A0000002B002B0020001800260017001A8084918E83808293988B50");
		$placeholderTrainer0[4]["pokemon3"] = hex2bin("836D3B39555E00000003E875307530753075307530FDEB050F0F0A640000000A000000340034001D001B0018001C001E8B808F9180925050505050");
		$placeholderTrainer0[4]["message_start"] = hex2bin("0203290604050202");
		$placeholderTrainer0[4]["message_win"] = hex2bin("0F063D0406033F04");
		$placeholderTrainer0[4]["message_lose"] = hex2bin("030D110C3304020D");
		
		// Trainer 4
		$placeholderTrainer0[3] = array();
		$placeholderTrainer0[3]["name"] = hex2bin("8D88828A848B50505050");
		$placeholderTrainer0[3]["class"] = hexdec("14");
		$placeholderTrainer0[3]["pokemon1"] = hex2bin("D7AEA3B9393B00000003E8753088B8753075307530FBBF14140F05640000000A000000260026001F001600220013001B928D848092848B50505050");
		$placeholderTrainer0[3]["pokemon2"] = hex2bin("E9035E3B3FA100000003E8753075309C4075307530FBDE0A05050A640000000A0000002C002C001C001E00170021001F8F8E9198868E8DF8505050");
		$placeholderTrainer0[3]["pokemon3"] = hex2bin("C877C3D4DCF700000003E875307530753075307530EFDF0505140F640000000A00000025002500180018001C001D001D8C88928391848095949250");
		$placeholderTrainer0[3]["message_start"] = hex2bin("2503050C0B08350B");
		$placeholderTrainer0[3]["message_win"] = hex2bin("350B360D2C04380D");
		$placeholderTrainer0[3]["message_lose"] = hex2bin("380D0B082E0D2C04");
		
		// Trainer 5
		$placeholderTrainer0[2] = array();
		$placeholderTrainer0[2]["name"] = hex2bin("8E8B928E8D5050505050");
		$placeholderTrainer0[2]["class"] = hexdec("3B");
		$placeholderTrainer0[2]["pokemon1"] = hex2bin("E4AEB94C2EF100000003E875307530753080E87530FDFE140A1405640000000A000000240024001800110019001C0016878E948D838E9491505050");
		$placeholderTrainer0[2]["pokemon2"] = hex2bin("CB523CBDF76100000003E875307530753075307530EDFD140A0F1E640000000A000000270027001C0018001D001D00188688918085809188865050");
		$placeholderTrainer0[2]["pokemon3"] = hex2bin("F2491D4CCD4600000003E87D009C40753075307530DFCE0F0A140F640000000A0000004D004D000E000E0016001B0027818B889292849850505050");
		$placeholderTrainer0[2]["message_start"] = hex2bin("25037700200D1904");
		$placeholderTrainer0[2]["message_win"] = hex2bin("220D200D71000105");
		$placeholderTrainer0[2]["message_lose"] = hex2bin("25030A0662000105");
		
		// Trainer 6
		$placeholderTrainer0[1] = array();
		$placeholderTrainer0[1]["name"] = hex2bin("9980818E918E96928A88");
		$placeholderTrainer0[1]["class"] = hexdec("19");
		$placeholderTrainer0[1]["pokemon1"] = hex2bin("8F6D1DB6AD3900000003E875307530753075307530EFF70F0A0F0F640000000A00000039003900220019001200170020928D8E918B809750505050");
		$placeholderTrainer0[1]["pokemon2"] = hex2bin("67525CCAA85D00000003E875307530753075307530FEFE0A050A19640000000A0000002D002D001F001D001700250019849784868694938E915050");
		$placeholderTrainer0[1]["pokemon3"] = hex2bin("D6AEB3CB44F900000003E875307530753075307530F7F70F0A140F640000000A0000002B002B00250019001D0012001D8784918082918E92925050");
		$placeholderTrainer0[1]["message_start"] = hex2bin("2503050300030202");
		$placeholderTrainer0[1]["message_win"] = hex2bin("29051E0E200D4004");
		$placeholderTrainer0[1]["message_lose"] = hex2bin("380601050F060305");
		
		// Trainer 7
		$placeholderTrainer0[0] = array();
		$placeholderTrainer0[0]["name"] = hex2bin("96918886879350505050");
		$placeholderTrainer0[0]["class"] = hexdec("16");
		$placeholderTrainer0[0]["pokemon1"] = hex2bin("C9ADED00000000000003E875307530753075307530FFFF0F000000000000000A000000240024001A00150015001A0015948D8E968D505050505050");
		$placeholderTrainer0[0]["pokemon2"] = hex2bin("80521DCF27C400000003E87530753075307530753065570F0F1E0F000000000A000000280028001E001D002000120018938094918E925050505050");
		$placeholderTrainer0[0]["pokemon3"] = hex2bin("7A495CF4071D00000003E87530753075307530753073670A0A0F0F000000000A00000022002200130016001C001E00228C91E88C888C8450505050");
		$placeholderTrainer0[0]["message_start"] = hex2bin("0C020D0D2C04240D");
		$placeholderTrainer0[0]["message_win"] = hex2bin("00022A0D0D0E2C04");
		$placeholderTrainer0[0]["message_lose"] = hex2bin("000206032F062004");
		
		return $placeholderTrainer0[$number];
	}
	
	function getBattleTowerPlaceholderTrainerEN1($number) {
		$placeholderTrainer1 = array();
		
		// Trainer 1
		$placeholderTrainer1[6] = array();
		$placeholderTrainer1[6]["name"] = hex2bin("808B8497808D83849150");
		$placeholderTrainer1[6]["class"] = hexdec("2C");
		$placeholderTrainer1[6]["pokemon1"] = hex2bin("C592B65CBDD50000001F40C350C350C350C350C350CFBC0A0A0A0F6400000014000000510051002E0042002E002C0048948C8191848E8D50505050");
		$placeholderTrainer1[6]["pokemon2"] = hex2bin("79AE695E39F40000001F40C350C350C350C350C350DBDB140A0F0A6400000014000000470047003300360043003C0036929380918C888450505050");
		$placeholderTrainer1[6]["pokemon3"] = hex2bin("826D3F52557E0000001F40C350C350C350C350C350FAFD050A0F056400000014000000530053004800330036002D003D8698809180838E92505050");
		$placeholderTrainer1[6]["message_start"] = hex2bin("0C020D0D180D3D08");
		$placeholderTrainer1[6]["message_win"] = hex2bin("1D05010502033504");
		$placeholderTrainer1[6]["message_lose"] = hex2bin("1D05010509040F0C");
		
		// Trainer 2
		$placeholderTrainer1[5] = array();
		$placeholderTrainer1[5]["name"] = hex2bin("8A8096808A808C885050");
		$placeholderTrainer1[5]["class"] = hexdec("22");
		$placeholderTrainer1[5]["pokemon1"] = hex2bin("D0AE2EE7CF590000001F40C350AFC8C3507530C350FFFF140F0F0A64000000140000004D004D00370066001F002C0030929384848B889750505050");
		$placeholderTrainer1[5]["pokemon2"] = hex2bin("418B5EF45C090000001F40C350C3507530C3509C40FDEF0A0A0A0F6400000014000000440044002A00240045004B0037808B808A8099808C505050");
		$placeholderTrainer1[5]["pokemon3"] = hex2bin("3B03352E3FE70000001F4088B8AFC8C350D6D8C350DBFB0F14050F640000001400000051005100400034003C003C0034809182808D888D84505050");
		$placeholderTrainer1[5]["message_start"] = hex2bin("0D0D150C2403180A");
		$placeholderTrainer1[5]["message_win"] = hex2bin("04042403220C1509");
		$placeholderTrainer1[5]["message_lose"] = hex2bin("0603200D26042705");
		
		// Trainer 3
		$placeholderTrainer1[4] = array();
		$placeholderTrainer1[4]["name"] = hex2bin("8188828A849393505050");
		$placeholderTrainer1[4]["class"] = hexdec("3B");
		$placeholderTrainer1[4]["pokemon1"] = hex2bin("D677CBB3E0590000001F40C3507530AFC87530AFC8DFDE0A0F0A0A64000000140000004E004E0044003300340025003B8784918082918E92925050");
		$placeholderTrainer1[4]["pokemon2"] = hex2bin("67923F5E5C8A0000001F40AFC8C350C350AFC8AFC8FDEB050A0A0F6400000014000000530053003C0037002B0046002E849784868694938E915050");
		$placeholderTrainer1[4]["pokemon3"] = hex2bin("8EAE9C3F59520000001F40AFC8C3509C40C350AFC8FBBB0A050A0A64000000140000004E004E0040002D0048002C00328084918E83808293988B50");
		$placeholderTrainer1[4]["message_start"] = hex2bin("23070D0E220C2C04");
		$placeholderTrainer1[4]["message_win"] = hex2bin("0203150D3D071203");
		$placeholderTrainer1[4]["message_lose"] = hex2bin("0F0506050603300D");
		
		// Trainer 4
		$placeholderTrainer1[3] = array();
		$placeholderTrainer1[3]["name"] = hex2bin("928088938E5050505050");
		$placeholderTrainer1[3]["class"] = hexdec("3C");
		$placeholderTrainer1[3]["pokemon1"] = hex2bin("F2035E4287440000001F40C350C35075307530C350BDFE0A190A1464000000140000009400940018001600290033004B818B889292849850505050");
		$placeholderTrainer1[3]["pokemon2"] = hex2bin("83AE5E553B6D0000001F40D6D875309C40D6D87530FED70A0F050A640000001400000062006200350034002D003200368B808F9180925050505050");
		$placeholderTrainer1[3]["pokemon3"] = hex2bin("19A35556465C0000001F40AFC8C350AFC8C350C350FCFE0F140F0A64000000140000003A003A002C0020003A002900258F888A8082879450505050");
		$placeholderTrainer1[3]["message_start"] = hex2bin("10040D0D150C0105");
		$placeholderTrainer1[3]["message_win"] = hex2bin("4105110E100D3D04");
		$placeholderTrainer1[3]["message_lose"] = hex2bin("25030C042A0D0203");
		
		// Trainer 5
		$placeholderTrainer1[2] = array();
		$placeholderTrainer1[2]["name"] = hex2bin("82918096858E91835050");
		$placeholderTrainer1[2]["class"] = hexdec("3A");
		$placeholderTrainer1[2]["pokemon1"] = hex2bin("D477D3A35CC90000001F409C40AFC89C40AFC8C350FDFE19140A0A64000000140000004900490049003C002F002B0035928288998E915050505050");
		$placeholderTrainer1[2]["pokemon2"] = hex2bin("6BAE090807050000001F40C350AFC888B8C3507530FBFD0F0F0F146400000014000000430043003F003200340020003E8788938C8E8D8287808D50");
		$placeholderTrainer1[2]["pokemon3"] = hex2bin("800355593F3B0000001F40C3509C40C35075307530FBEF0F0A050564000000140000004C004C003D003A003F0023002F938094918E925050505050");
		$placeholderTrainer1[2]["message_start"] = hex2bin("200D280404050202");
		$placeholderTrainer1[2]["message_win"] = hex2bin("0809300401051302");
		$placeholderTrainer1[2]["message_lose"] = hex2bin("3F05250326070105");
		
		// Trainer 6
		$placeholderTrainer1[1] = array();
		$placeholderTrainer1[1]["name"] = hex2bin("83888099505050505050");
		$placeholderTrainer1[1]["class"] = hexdec("35");
		$placeholderTrainer1[1]["pokemon1"] = hex2bin("B85F393BD5F00000001F409C409C409C409C409C40EDF70F050F056400000014000000520052002800340029002500318099948C8091888B8B5050");
		$placeholderTrainer1[1]["pokemon2"] = hex2bin("F1525957D5390000001F409C409C409C409C409C40DFFE0A0A0F0F64000000140000005300530034003F003D002400308C888B93808D8A50505050");
		$placeholderTrainer1[1]["pokemon3"] = hex2bin("28AE3F3B7ED50000001F409C409C409C409C409C40C7FE0505050F6400000014000000620062002F0023002700320028968886868B989394858550");
		$placeholderTrainer1[1]["message_start"] = hex2bin("3507240319083004");
		$placeholderTrainer1[1]["message_win"] = hex2bin("3703250D19082705");
		$placeholderTrainer1[1]["message_lose"] = hex2bin("2503090222033A08");
		
		// Trainer 7
		$placeholderTrainer1[0] = array();
		$placeholderTrainer1[0]["name"] = hex2bin("849188828A928E8D5050");
		$placeholderTrainer1[0]["class"] = hexdec("2D");
		$placeholderTrainer1[0]["pokemon1"] = hex2bin("28685ECFF41D0000001F4075307530753075307530C7770A0F0A0F0000000014000000610061002E00220022002E0024968886868B989394858550");
		$placeholderTrainer1[0]["pokemon2"] = hex2bin("22AD3B5939090000001F40753075307530753075305646050A0F0F00000000140000004A004A0034002E00310032002E8D88838E8A888D86505050");
		$placeholderTrainer1[0]["pokemon3"] = hex2bin("C349855939F00000001F40753075307530753075305547140A0F05000000001400000051005100310031001D002A002A9094808692889184505050");
		$placeholderTrainer1[0]["message_start"] = hex2bin("050330040F0D1808");
		$placeholderTrainer1[0]["message_win"] = hex2bin("1B02010502030A04");
		$placeholderTrainer1[0]["message_lose"] = hex2bin("0F0D020331040405");
		
		return $placeholderTrainer1[$number];
	}
	
	function getBattleTowerPlaceholderTrainerEN2($number) {
		$placeholderTrainer2 = array();
		
		// Trainer 1
		$placeholderTrainer2[6] = array();
		$placeholderTrainer2[6]["name"] = hex2bin("858088918588848B8350");
		$placeholderTrainer2[6]["class"] = hexdec("31");
		$placeholderTrainer2[6]["pokemon1"] = hex2bin("876D55562EBD0000006978C350C350C350C350D6D8DBED0F14140A640000001E00000067006700440040006B00600057898E8B93848E8D50505050");
		$placeholderTrainer2[6]["pokemon2"] = hex2bin("3E0368395A420000006978C350D6D8D6D8D6D8C350DDFB0F0F0519640000001E000000770077005100570049004600528F8E8B8896918093875050");
		$placeholderTrainer2[6]["pokemon3"] = hex2bin("7992565E69390000006978C350C350C350C350C350FFFF140A140F640000001E000000650065004B00510063005A0051929380918C888450505050");
		$placeholderTrainer2[6]["message_start"] = hex2bin("1B02210D00030202");
		$placeholderTrainer2[6]["message_win"] = hex2bin("13050603220D3104");
		$placeholderTrainer2[6]["message_lose"] = hex2bin("0F0506052D033008");
		
		// Trainer 2
		$placeholderTrainer2[5] = array();
		$placeholderTrainer2[5]["name"] = hex2bin("87948D93849150505050");
		$placeholderTrainer2[5]["class"] = hexdec("3E");
		$placeholderTrainer2[5]["pokemon1"] = hex2bin("7CAE3B8E8AD50000006978C350C350C3507530C350FBEE050A0F0F640000001E000000660066003C003100530062005689988D9750505050505050");
		$placeholderTrainer2[5]["pokemon2"] = hex2bin("335259BCA3BD0000006978C350C3507530C350C350EFFF0A0A140A640000001E000000510051004D00380066003C00488394869391888E50505050");
		$placeholderTrainer2[5]["pokemon3"] = hex2bin("B603CAF14C680000006978AFC8AFC8C350D6D8C350DFDB05050A0F640000001E0000006D006D004C0051003C0052005881848B8B8E92928E8C5050");
		$placeholderTrainer2[5]["message_start"] = hex2bin("240330040F0D1908");
		$placeholderTrainer2[5]["message_win"] = hex2bin("1D03330319080405");
		$placeholderTrainer2[5]["message_lose"] = hex2bin("3505060336080105");
		
		// Trainer 3
		$placeholderTrainer2[4] = array();
		$placeholderTrainer2[4]["name"] = hex2bin("87888B8B505050505050");
		$placeholderTrainer2[4]["class"] = hexdec("30");
		$placeholderTrainer2[4]["pokemon1"] = hex2bin("F2925C7387B60000006978C3507530AFC87530AFC8FBED0A140A0A640000001E000000D900D900200021003B0049006D818B889292849850505050");
		$placeholderTrainer2[4]["pokemon2"] = hex2bin("E58A35F2F78A0000006978AFC8C350C350AFC8AFC8FDED0F0F0F0F640000001E0000006C006C0054003B0056005E004C878E948D838E8E8C505050");
		$placeholderTrainer2[4]["pokemon3"] = hex2bin("446DEE08597E0000006978AFC8C3509C40C350AFC8FDBE050F0A05640000001E000000760076006C004B003D004400508C808287808C8F50505050");
		$placeholderTrainer2[4]["message_start"] = hex2bin("3507240343040405");
		$placeholderTrainer2[4]["message_win"] = hex2bin("3105060501033C04");
		$placeholderTrainer2[4]["message_lose"] = hex2bin("0F05060525033F04");
		
		// Trainer 4
		$placeholderTrainer2[3] = array();
		$placeholderTrainer2[3]["name"] = hex2bin("89809588849150505050");
		$placeholderTrainer2[3]["class"] = hexdec("27");
		$placeholderTrainer2[3]["pokemon1"] = hex2bin("A9AED56D5C110000006978C350C35075307530C350EFDC0F0A0A23640000001E0000006F006F0053004A00670046004C82918E8180935050505050");
		$placeholderTrainer2[3]["pokemon2"] = hex2bin("E9035E693FA10000006978D6D875309C40D6D87530DFDB0A14050A640000001E000000750075004900530042005700518F8E9198868E8DF8505050");
		$placeholderTrainer2[3]["pokemon3"] = hex2bin("697659D83F9B0000006978AFC8C350AFC8C3507530DDEB0A14050AFF0000001E000000630063004D005E0038003600488C80918E96808A50505050");
		$placeholderTrainer2[3]["message_start"] = hex2bin("02031E0D36073004");
		$placeholderTrainer2[3]["message_win"] = hex2bin("020B1203130E0405");
		$placeholderTrainer2[3]["message_lose"] = hex2bin("020D01033007130E");
		
		// Trainer 5
		$placeholderTrainer2[2] = array();
		$placeholderTrainer2[2]["name"] = hex2bin("8A8094858C808D505050");
		$placeholderTrainer2[2]["class"] = hexdec("26");
		$placeholderTrainer2[2]["pokemon1"] = hex2bin("65037155B65700000069789C40AFC89C40AFC8C350BDEF1E0F0A0A640000001E000000620062003900450071004E004E848B848293918E83845050");
		$placeholderTrainer2[2]["pokemon2"] = hex2bin("8392F037C4460000006978C350AFC888B8C3507530FDEB05190F0F640000001E0000008E008E0050004A0041004B00518B808F9180925050505050");
		$placeholderTrainer2[2]["pokemon3"] = hex2bin("ABAEF05739AF0000006978C3509C40C35075307530DDEB050A0F0F640000001E0000008B008B003E00400042004600468B808D9394918D50505050");
		$placeholderTrainer2[2]["message_start"] = hex2bin("010306060D0E4404");
		$placeholderTrainer2[2]["message_win"] = hex2bin("0806200D44042705");
		$placeholderTrainer2[2]["message_lose"] = hex2bin("110E4404150D3604");
		
		// Trainer 6
		$placeholderTrainer2[1] = array();
		$placeholderTrainer2[1]["name"] = hex2bin("8B808D82809293849150");
		$placeholderTrainer2[1]["class"] = hexdec("21");
		$placeholderTrainer2[1]["pokemon1"] = hex2bin("C46D5D815CF40000006978AFC8C350C350C350C350EFF719140A0A640000001E0000006300630044004200600067005284928F848E8D5050505050");
		$placeholderTrainer2[1]["pokemon2"] = hex2bin("4952235CBC3D0000006978C350AFC8C350B798AFC8FEFE140A0A14640000001E0000006E006E00470044005A004D006593848D9380829194848B50");
		$placeholderTrainer2[1]["pokemon3"] = hex2bin("5EAEA87A65CA0000006978C350AFC8C350C350C350F7F70A1E0F05640000001E0000006500650044003D00600067004686848D8680915050505050");
		$placeholderTrainer2[1]["message_start"] = hex2bin("200D2C0404050202");
		$placeholderTrainer2[1]["message_win"] = hex2bin("0C05060314063F04");
		$placeholderTrainer2[1]["message_lose"] = hex2bin("0F0D020334070405");
		
		// Trainer 7
		$placeholderTrainer2[0] = array();
		$placeholderTrainer2[0]["name"] = hex2bin("8C848C8087888B8B5050");
		$placeholderTrainer2[0]["class"] = hexdec("36");
		$placeholderTrainer2[0]["pokemon1"] = hex2bin("D9AE1DB62E2B00000069787530753075307530753077450F0A141E000000001E000000720072006400430035004100419491928091888D86505050");
		$placeholderTrainer2[0]["pokemon2"] = hex2bin("160377E44081000000697875307530753075307530677714142314000000001E000000600060004B003D0052003A003A858480918E965050505050");
		$placeholderTrainer2[0]["pokemon3"] = hex2bin("396D4302B374000000697875307530753075307530776714190F1E000000001E0000006300630055003A004E003A00408F91888C84808F84505050");
		$placeholderTrainer2[0]["message_start"] = hex2bin("2503200D3E072804");
		$placeholderTrainer2[0]["message_win"] = hex2bin("0203150D3D071203");
		$placeholderTrainer2[0]["message_lose"] = hex2bin("0103280B06040305");
		
		return $placeholderTrainer2[$number];
	}
	
	function getBattleTowerPlaceholderTrainerEN3($number) {
		$placeholderTrainer3 = array();
		
		// Trainer 1
		$placeholderTrainer3[6] = array();
		$placeholderTrainer3[6]["name"] = hex2bin("8E819188848D50505050");
		$placeholderTrainer3[6]["class"] = hexdec("20");
		$placeholderTrainer3[6]["pokemon1"] = hex2bin("80AED83F59E7000000FA00C350C350C350C350C350FDFE14050A0FFF000000280000008F008F00770071007F0046005E938094918E925050505050");
		$placeholderTrainer3[6]["pokemon2"] = hex2bin("E69239E13F3B000000FA00C350C350C350C350C350FDEF0F14050564000000280000008E008E00730071006A007300738A888D8683918050505050");
		$placeholderTrainer3[6]["pokemon3"] = hex2bin("8F49D522F459000000FA00C350C350C350C350C350EDDD0F0F0A0A6400000028000000CD00CD007E0059003D0059007D928D8E918B809750505050");
		$placeholderTrainer3[6]["message_start"] = hex2bin("0203060404053506");
		$placeholderTrainer3[6]["message_win"] = hex2bin("020310083C06180A");
		$placeholderTrainer3[6]["message_lose"] = hex2bin("120E1306330D1504");
		
		// Trainer 2
		$placeholderTrainer3[5] = array();
		$placeholderTrainer3[5]["name"] = hex2bin("85918E92935050505050");
		$placeholderTrainer3[5]["class"] = hexdec("1D");
		$placeholderTrainer3[5]["pokemon1"] = hex2bin("8392553A6D39000000FA00C350C350C350C350C350FDEB0F0A0A0F6400000028000000BA00BA006B006500560067006F8B808F9180925050505050");
		$placeholderTrainer3[5]["pokemon2"] = hex2bin("D0AEC9E7595C000000FA00C350C350C350C350C350EFDB0A0F0A0A6400000028000000890089006A00C7003D004F0057929384848B889750505050");
		$placeholderTrainer3[5]["pokemon3"] = hex2bin("41525E096907000000FA00C350AFC8C350D6D8C350DDEF0A0F140F64000000280000007E007E004C004900870093006B808B808A8099808C505050");
		$placeholderTrainer3[5]["message_start"] = hex2bin("0D0E2C04000D3408");
		$placeholderTrainer3[5]["message_win"] = hex2bin("20030F0D1F07020D");
		$placeholderTrainer3[5]["message_lose"] = hex2bin("250D240801031304");
		
		// Trainer 3
		$placeholderTrainer3[4] = array();
		$placeholderTrainer3[4]["name"] = hex2bin("8C8E9192845050505050");
		$placeholderTrainer3[4]["class"] = hexdec("29");
		$placeholderTrainer3[4]["pokemon1"] = hex2bin("79923B55395E000000FA00C350C350AFC8C350AFC8FDBE050F0F0A640000002800000083008300630068007F00750069929380918C888450505050");
		$placeholderTrainer3[4]["pokemon2"] = hex2bin("CAAE44F3DBC2000000FA00AFC8C350C350C350C350BFE7141419056400000028000000E900E9003E00550040003B004F968E818194858584935050");
		$placeholderTrainer3[4]["pokemon3"] = hex2bin("4C779959059D000000FA00AFC8C3509C40C350AFC8DDED050A140A6400000028000000910091007D008B004A00500058868E8B848C505050505050");
		$placeholderTrainer3[4]["message_start"] = hex2bin("060506052C040405");
		$placeholderTrainer3[4]["message_win"] = hex2bin("0605010307040405");
		$placeholderTrainer3[4]["message_lose"] = hex2bin("0605010335040405");
		
		// Trainer 4
		$placeholderTrainer3[3] = array();
		$placeholderTrainer3[3]["name"] = hex2bin("989485948D8450505050");
		$placeholderTrainer3[3]["class"] = hexdec("32");
		$placeholderTrainer3[3]["pokemon1"] = hex2bin("D48CA3D3E43F000000FA00C350C350C3509C40C350BDFE1419140564000000280000008B008B008B0075005900520066928288998E915050505050");
		$placeholderTrainer3[3]["pokemon2"] = hex2bin("3352593FBCBD000000FA00AFC8C350C350C350C350FEBB0A050A0A64000000280000006C006C0067004E0083004B005B8394869391888E50505050");
		$placeholderTrainer3[3]["pokemon3"] = hex2bin("506D395E593B000000FA00AFC8C350AFC8C350C350BFCF0F0A0A0564000000280000009D009D005F007E003C00770067928B8E9681918E50505050");
		$placeholderTrainer3[3]["message_start"] = hex2bin("2905200D18040105");
		$placeholderTrainer3[3]["message_win"] = hex2bin("4105380D3D040105");
		$placeholderTrainer3[3]["message_lose"] = hex2bin("1105050525030C04");
		
		// Trainer 5
		$placeholderTrainer3[2] = array();
		$placeholderTrainer3[2]["name"] = hex2bin("918089808D5050505050");
		$placeholderTrainer3[2]["class"] = hexdec("1C");
		$placeholderTrainer3[2]["pokemon1"] = hex2bin("E900B0A03CA8000000FA00C350AFC8C350C350C350BCEF1E1E140A64000000280000009300930063006C0056007B00738F8E9198868E8DF8505050");
		$placeholderTrainer3[2]["pokemon2"] = hex2bin("3B8AAC2B222E000000FA00C350C350C350C350C350FEBB191E0F146400000028000000980098007F0066006F00730063809182808D888D84505050");
		$placeholderTrainer3[2]["pokemon3"] = hex2bin("CD92E5B65CC9000000FA00C350C350C350C350C350FA7F280A0A0A64000000280000008C008C006F0093004000570057858E919184939184929250");
		$placeholderTrainer3[2]["message_start"] = hex2bin("11042503200D2704");
		$placeholderTrainer3[2]["message_win"] = hex2bin("0603190E3F040105");
		$placeholderTrainer3[2]["message_lose"] = hex2bin("3806010506032004");
		
		// Trainer 6
		$placeholderTrainer3[1] = array();
		$placeholderTrainer3[1]["name"] = hex2bin("918E8391888694849950");
		$placeholderTrainer3[1]["class"] = hexdec("41");
		$placeholderTrainer3[1]["pokemon1"] = hex2bin("8BAEAE37F6F9000000FA00C350C350C350C350C350EFF70A19050F64000000280000008500850056008B0053007C00588E8C809293809150505050");
		$placeholderTrainer3[1]["pokemon2"] = hex2bin("0652535213A3000000FA00C350C350C350C350C350FEFE0F0A0F1464000000280000008E008E006A00640077007D006A8287809188998091835050");
		$placeholderTrainer3[1]["pokemon3"] = hex2bin("67037917F45D000000FA00C350C350C350C350C350F7E70A140A1964000000280000009E009E00730064005200840054849784868694938E915050");
		$placeholderTrainer3[1]["message_start"] = hex2bin("2503220D28061A06");
		$placeholderTrainer3[1]["message_win"] = hex2bin("3D050105380D3D04");
		$placeholderTrainer3[1]["message_lose"] = hex2bin("3204390D38060105");
		
		// Trainer 7
		$placeholderTrainer3[0] = array();
		$placeholderTrainer3[0]["name"] = hex2bin("92808D938880868E5050");
		$placeholderTrainer3[0]["class"] = hexdec("34");
		$placeholderTrainer3[0]["pokemon1"] = hex2bin("61035D091D32000000FA0075307530753075307530777A190F0F1400000000280000009200920056005300510058007A87988F8D8E505050505050");
		$placeholderTrainer3[0]["pokemon2"] = hex2bin("5949675C7C6A000000FA0075307530753075307530756B280A141E0000000028000000A100A1006F005600430053006F8C948A5050505050505050");
		$placeholderTrainer3[0]["pokemon3"] = hex2bin("7D52710981AD000000FA007530753075307530753065771E0F140F00000000280000007C007C005D0047006F0067005F848B848293808194999950");
		$placeholderTrainer3[0]["message_start"] = hex2bin("0D0E2C040B0D2108");
		$placeholderTrainer3[0]["message_win"] = hex2bin("0D0E1904100D2108");
		$placeholderTrainer3[0]["message_lose"] = hex2bin("0D0E3304100D2108");
		
		return $placeholderTrainer3[$number];
	}
	
	function getBattleTowerPlaceholderTrainerEN4($number) {
		$placeholderTrainer4 = array();
		
		// Trainer 1
		$placeholderTrainer4[6] = array();
		$placeholderTrainer4[6]["name"] = hex2bin("92938E828A5050505050");
		$placeholderTrainer4[6]["class"] = hexdec("3B");
		$placeholderTrainer4[6]["pokemon1"] = hex2bin("E6AE393F3BE1000001E848D6D8D6D8EA60C350D6D8DDFF0F0505140000000032000000B300B3008E008F0084009000908A888D8683918050505050");
		$placeholderTrainer4[6]["pokemon2"] = hex2bin("E56D9CF28A35000001E848D6D8C350EA60EA60EA60DDFC0A0F0F0F0000000032000000B200B2008700620091009D007F878E948D838E8E8C505050");
		$placeholderTrainer4[6]["pokemon3"] = hex2bin("D592C99C5C23000001E848EA60EA60EA60EA60D6D8FDCF0A0A0A1400000000320000007B007B003C01160034003B0117928794828A8B8450505050");
		$placeholderTrainer4[6]["message_start"] = hex2bin("0C0C25032B0D250A");
		$placeholderTrainer4[6]["message_win"] = hex2bin("1009200D23090105");
		$placeholderTrainer4[6]["message_lose"] = hex2bin("3E052503350B020C");
		
		// Trainer 2
		$placeholderTrainer4[5] = array();
		$placeholderTrainer4[5]["name"] = hex2bin("938794918C808D505050");
		$placeholderTrainer4[5]["class"] = hexdec("14");
		$placeholderTrainer4[5]["pokemon1"] = hex2bin("8F923F5939F4000001E848EA60D6D8D6D8EA60D6D8FDEF050A0F0A0000000032000001070107009F0070004F0072009F928D8E918B809750505050");
		$placeholderTrainer4[5]["pokemon2"] = hex2bin("83AE55396D3B000001E848D6D8EA60EA60D6D8EA60DDDD0F0F0A050000000032000000EA00EA00850080006B0085008F8B808F9180925050505050");
		$placeholderTrainer4[5]["pokemon3"] = hex2bin("87525556F7ED000001E848D6D8EA60DEA8D6D8D6D8EDFF0F140F0F0000000032000000A100A10072006B00B3009F0090898E8B93848E8D50505050");
		$placeholderTrainer4[5]["message_start"] = hex2bin("0202170D24040105");
		$placeholderTrainer4[5]["message_win"] = hex2bin("0A02010323071904");
		$placeholderTrainer4[5]["message_lose"] = hex2bin("4105250326051804");
		
		// Trainer 3
		$placeholderTrainer4[4] = array();
		$placeholderTrainer4[4]["name"] = hex2bin("95808B848D93888D8E50");
		$placeholderTrainer4[4]["class"] = hexdec("1D");
		$placeholderTrainer4[4]["pokemon1"] = hex2bin("D4923FA361E8000001E848AFC8C3509C40C350AFC8DFED05141E230000000032000000A900A900AF0091006F0063007C928288998E915050505050");
		$placeholderTrainer4[4]["pokemon2"] = hex2bin("C7549C395E85000001E848C3509C40AFC8C350C350DFDE0A0F0A140F00000032000000C400C40076007E004B0092009C928B8E968A888D86505050");
		$placeholderTrainer4[4]["pokemon3"] = hex2bin("44AEEE597E09000001E8489C40AFC8C3509C40ABE0FFEC050A050F0D00000032000000BB00BB00B0007F0063006C00808C808287808C8F50505050");
		$placeholderTrainer4[4]["message_start"] = hex2bin("160D0404100B0405");
		$placeholderTrainer4[4]["message_win"] = hex2bin("0002010506033C08");
		$placeholderTrainer4[4]["message_lose"] = hex2bin("2405010506030206");
		
		// Trainer 4
		$placeholderTrainer4[3] = array();
		$placeholderTrainer4[3]["name"] = hex2bin("9680868D849150505050");
		$placeholderTrainer4[3]["class"] = hexdec("36");
		$placeholderTrainer4[3]["pokemon1"] = hex2bin("798C56695539000001E848AFC8ABE09C40AFC89C40FFFF14140F0F0000000032000000A100A10079008200A100910082929380918C888450505050");
		$placeholderTrainer4[3]["pokemon2"] = hex2bin("335259A33FBC000001E848AFC89C40C350AFC8C350F7FE0A14050A0000000032000000870087007D005900A6006000748394869391888E50505050");
		$placeholderTrainer4[3]["pokemon3"] = hex2bin("656D5599F39C000001E848C350AFC8D2F09C40C3507DFE0F05140A0000000032000000A100A10058007500B9007E007E848B848293918E83845050");
		$placeholderTrainer4[3]["message_start"] = hex2bin("120C2E0D30062C04");
		$placeholderTrainer4[3]["message_win"] = hex2bin("1502250338080105");
		$placeholderTrainer4[3]["message_lose"] = hex2bin("0103140D13040105");
		
		// Trainer 5
		$placeholderTrainer4[2] = array();
		$placeholderTrainer4[2]["name"] = hex2bin("98809384925050505050");
		$placeholderTrainer4[2]["class"] = hexdec("18");
		$placeholderTrainer4[2]["pokemon1"] = hex2bin("8E523F597EE7000001E848AFC8C350C350AFC8AFC8FDDD050A050F0000000032000000B500B50098006E00AE006800778084918E83808293988B50");
		$placeholderTrainer4[2]["pokemon2"] = hex2bin("A9926DD53F5C000001E848AFC89C40C3509C40C350EFFF0A0F050A0000000032000000B200B20086007F00AF0075007F82918E8180935050505050");
		$placeholderTrainer4[2]["pokemon3"] = hex2bin("916D4155563F000001E848AFC8C350AFC89C40C350FDDE140F14050000000032000000BE00BE00890081008F00AB008899808F838E925050505050");
		$placeholderTrainer4[2]["message_start"] = hex2bin("0203150D3D071203");
		$placeholderTrainer4[2]["message_win"] = hex2bin("120D30041D031106");
		$placeholderTrainer4[2]["message_lose"] = hex2bin("0103300705033004");
		
		// Trainer 6
		$placeholderTrainer4[1] = array();
		$placeholderTrainer4[1]["name"] = hex2bin("808D8391849692505050");
		$placeholderTrainer4[1]["class"] = hexdec("35");
		$placeholderTrainer4[1]["pokemon1"] = hex2bin("E3AEC913D35C000001E848AFC8C350C350C3509C40D7ED0A0F190A0000000032000000A400A4007D00B3007400530071928A80918C8E9198505050");
		$placeholderTrainer4[1]["pokemon2"] = hex2bin("CD92C95C99CF000001E848C350C350D6D8AFC89C40CFDD0A0A050F0000000032000000A900A9008600BD005400670067858E919184939184929250");
		$placeholderTrainer4[1]["pokemon3"] = hex2bin("D06DC9E79C59000001E848AFC8C350C3509C40AFC8DDDD0A0F0A0A0000000032000000B000B0008200F500490063006D929384848B889750505050");
		$placeholderTrainer4[1]["message_start"] = hex2bin("2C04040526080202");
		$placeholderTrainer4[1]["message_win"] = hex2bin("110E100D1F060105");
		$placeholderTrainer4[1]["message_lose"] = hex2bin("330734061A0C120C");
		
		// Trainer 7
		$placeholderTrainer4[0] = array();
		$placeholderTrainer4[0]["name"] = hex2bin("8180878D505050505050");
		$placeholderTrainer4[0]["class"] = hexdec("1E");
		$placeholderTrainer4[0]["pokemon1"] = hex2bin("CB8C8AF25E59000001E8489C409C409C409C409C4045560F0F0A0A6400000032000000A100A1007200640078007E00658688918085809188865050");
		$placeholderTrainer4[0]["pokemon2"] = hex2bin("826D3F39F0C0000001E8489C409C409C409C409C407565050F05056400000032000000C100C100A200720075005F00878698809180838E92505050");
		$placeholderTrainer4[0]["pokemon3"] = hex2bin("90AE3B3F2EC4000001E8489C409C409C409C409C4045560505140F0000000032000000B500B5007700870078008300A18091938882948D8E505050");
		$placeholderTrainer4[0]["message_start"] = hex2bin("0C0C0203000D3604");
		$placeholderTrainer4[0]["message_win"] = hex2bin("3507030525033E07");
		$placeholderTrainer4[0]["message_lose"] = hex2bin("2403260A110D2807");
		
		return $placeholderTrainer4[$number];
	}
	
	function getBattleTowerPlaceholderTrainerEN5($number) {
		$placeholderTrainer5 = array();
		
		// Trainer 1
		$placeholderTrainer5[6] = array();
		$placeholderTrainer5[6]["name"] = hex2bin("8C8E9188505050505050");
		$placeholderTrainer5[6]["class"] = hexdec("14");
		$placeholderTrainer5[6]["pokemon1"] = hex2bin("E692E1393F3B0000034BC0D6D8D6D8C350EA60EA60DDFE140F0505640000003C000000D300D300A900A700A100AC00AC8A888D8683918050505050");
		$placeholderTrainer5[6]["pokemon2"] = hex2bin("F8AEF2599D3F0000034BC0D6D8EA60D6D8EA60D6D8FDED0F0A0A05640000003C000000F000F000DC00BB008300A900AF939891808D889380915050");
		$placeholderTrainer5[6]["pokemon3"] = hex2bin("E56D35F28A9C0000034BC0EA60D6D8D6D8EA60D6D8FBEF0F0F0F0A640000003C000000D400D400A5007100AC00BD0099878E948D838E8E8C505050");
		$placeholderTrainer5[6]["message_start"] = hex2bin("0103100706030008");
		$placeholderTrainer5[6]["message_win"] = hex2bin("220D28070B060404");
		$placeholderTrainer5[6]["message_lose"] = hex2bin("0603220D110E0508");
		
		// Trainer 2
		$placeholderTrainer5[5] = array();
		$placeholderTrainer5[5]["name"] = hex2bin("8194828A8C808D505050");
		$placeholderTrainer5[5]["class"] = hexdec("38");
		$placeholderTrainer5[5]["pokemon1"] = hex2bin("E9923B695C5E0000034BC0D6D8C350C350C350D6D8DDDE05140A0A640000003C000000DF00DF009500A1007D00B600AA8F8E9198868E8DF8505050");
		$placeholderTrainer5[5]["pokemon2"] = hex2bin("444907EE09590000034BC0C350C350AFC8C350C350FDEF0F050F0A640000003C000000E200E200D4009400780086009E8C808287808C8F50505050");
		$placeholderTrainer5[5]["pokemon3"] = hex2bin("91549C4155560000034BC0C350AFC8C350D6D8C350DDFD0A140F14640000003C000000E500E500A0009B00B100CB00A199808F838E925050505050");
		$placeholderTrainer5[5]["message_start"] = hex2bin("0603110E28040405");
		$placeholderTrainer5[5]["message_win"] = hex2bin("2D03220D0203020D");
		$placeholderTrainer5[5]["message_lose"] = hex2bin("06033006200D2704");
		
		// Trainer 3
		$placeholderTrainer5[4] = array();
		$placeholderTrainer5[4]["name"] = hex2bin("828E8181505050505050");
		$placeholderTrainer5[4]["class"] = hexdec("17");
		$placeholderTrainer5[4]["pokemon1"] = hex2bin("CAAE44F3C2DB0000034BC0C350C350AFC8C350AFC8FDED14140519640000003C0000015A015A005F007A005E005C007A968E818194858584935050");
		$placeholderTrainer5[4]["pokemon2"] = hex2bin("8E923F30592C0000034BC0AFC8C350C350AFC8AFC8FDDD05140A19640000003C000000D700D700B6008300D0007C008E8084918E83808293988B50");
		$placeholderTrainer5[4]["pokemon3"] = hex2bin("956D3FC455390000034BC0AFC8C3509C40C350AFC8DDFD050F0F0F640000003C000000E500E500D600A4009800AC00AC839180868E8D8893845050");
		$placeholderTrainer5[4]["message_start"] = hex2bin("01031B0B30041604");
		$placeholderTrainer5[4]["message_win"] = hex2bin("30041D040F0D1E09");
		$placeholderTrainer5[4]["message_lose"] = hex2bin("010329071E094006");
		
		// Trainer 4
		$placeholderTrainer5[3] = array();
		$placeholderTrainer5[3]["name"] = hex2bin("87948687849250505050");
		$placeholderTrainer5[3]["class"] = hexdec("25");
		$placeholderTrainer5[3]["pokemon1"] = hex2bin("C5AEBDEC5EB90000034BC0C350C350C350C350C350FDEF0A050A14640000003C000000E800E8008600B90084008000D4948C8191848E8D50505050");
		$placeholderTrainer5[3]["pokemon2"] = hex2bin("3B8A35F2F5E70000034BC0D6D8C3509C40D6D8C350FDED0F0F050F640000003C000000E400E400BC009200AA00AD0095809182808D888D84505050");
		$placeholderTrainer5[3]["pokemon3"] = hex2bin("E36DD3135CB60000034BC0C350C350AFC8C350C350FBEB190F0A0A640000003C000000C400C4009800DA008A00630087928A80918C8E9198505050");
		$placeholderTrainer5[3]["message_start"] = hex2bin("2503160A0E0E030C");
		$placeholderTrainer5[3]["message_win"] = hex2bin("0103080623070D0E");
		$placeholderTrainer5[3]["message_lose"] = hex2bin("2503300D350D160A");
		
		// Trainer 5
		$placeholderTrainer5[2] = array();
		$placeholderTrainer5[2]["name"] = hex2bin("80918893805050505050");
		$placeholderTrainer5[2]["class"] = hexdec("3C");
		$placeholderTrainer5[2]["pokemon1"] = hex2bin("F292875CB65E0000034BC0C350AFC8C350AFC8C350FBCD0A0A0A0A640000003C000001A801A80042003F0075008F00D7818B889292849850505050");
		$placeholderTrainer5[2]["pokemon2"] = hex2bin("8F689D3922590000034BC0C350AFC8C350C350C350FAFC0A0F0F0A640000003C00000133013300BA0080005C008200B8928D8E918B809750505050");
		$placeholderTrainer5[2]["pokemon3"] = hex2bin("D677B3E059440000034BC0C3509C40C350C350C350DFED0F0A0A14640000003C000000D600D600C80092009C006500A78784918082918E92925050");
		$placeholderTrainer5[2]["message_start"] = hex2bin("15020D0D150C0405");
		$placeholderTrainer5[2]["message_win"] = hex2bin("1502120212030105");
		$placeholderTrainer5[2]["message_lose"] = hex2bin("0A05160E26050508");
		
		// Trainer 6
		$placeholderTrainer5[1] = array();
		$placeholderTrainer5[1]["name"] = hex2bin("848092938E8D50505050");
		$placeholderTrainer5[1]["class"] = hexdec("34");
		$placeholderTrainer5[1]["pokemon1"] = hex2bin("7C6D3B5EF7C40000034BC0C350C350C350C350C350FFEB050A0F0F640000003C000000C400C40074006200A800BD00A589988D9750505050505050");
		$placeholderTrainer5[1]["pokemon2"] = hex2bin("09AE3959E53B0000034BC0C350C350C350C350C350FEFE0F0A2805640000003C000000D100D1009B00AE0095009C00B4818B8092938E8892845050");
		$placeholderTrainer5[1]["pokemon3"] = hex2bin("70495939E79D0000034BC0C350C350C350C350C350FBFA0A0F0F0A640000003C000000F500F500D400C3006800680068918798838E8D5050505050");
		$placeholderTrainer5[1]["message_start"] = hex2bin("27030F0D02030405");
		$placeholderTrainer5[1]["message_win"] = hex2bin("1906200D23090405");
		$placeholderTrainer5[1]["message_lose"] = hex2bin("1203040527031C0B");
		
		// Trainer 7
		$placeholderTrainer5[0] = array();
		$placeholderTrainer5[0]["name"] = hex2bin("859184848C808D505050");
		$placeholderTrainer5[0]["class"] = hexdec("36");
		$placeholderTrainer5[0]["pokemon1"] = hex2bin("1C8C59A33FAD0000034BC075307530753075307530B7670A14050F000000003C000000C900C900A400AB0074005D006992808D83928B8092875050");
		$placeholderTrainer5[0]["pokemon2"] = hex2bin("2FAE93CA3FBC0000034BC075307530753075307530665F0F05050A000000003C000000AB00AB009800860048007800908F80918092848293505050");
		$placeholderTrainer5[0]["pokemon3"] = hex2bin("4C03995907DA0000034BC0753075307530753075307657050A0F14000000003C000000CD00CD00AB00C2005A00690075868E8B848C505050505050");
		$placeholderTrainer5[0]["message_start"] = hex2bin("25031D0612080105");
		$placeholderTrainer5[0]["message_win"] = hex2bin("3A060105170D1304");
		$placeholderTrainer5[0]["message_lose"] = hex2bin("3E05170D3604180C");
		
		return $placeholderTrainer5[$number];
	}
	
	function getBattleTowerPlaceholderTrainerEN6($number) {
		$placeholderTrainer6 = array();
		
		// Trainer 1
		$placeholderTrainer6[6] = array();
		$placeholderTrainer6[6]["name"] = hex2bin("86888492845050505050");
		$placeholderTrainer6[6]["class"] = hexdec("19");
		$placeholderTrainer6[6]["pokemon1"] = hex2bin("876D553FF72E0000053BD8EA60DAC0D6D8C350EA60FBEF0F050F146400000046000000E700E7009E009100F500DE00C9898E8B93848E8D50505050");
		$placeholderTrainer6[6]["pokemon2"] = hex2bin("86923BF739BD0000053BD8C350C350EA60EA60C350BFEF050F0F0A64000000460000013E013E00950098009E00DA00C595808F8E91848E8D505050");
		$placeholderTrainer6[6]["pokemon3"] = hex2bin("C5AEB9ECF45C0000053BD8D2F0EA60D6D8C350E290DDDD14050A0A6400000046000001120112009C00D90098009500F7948C8191848E8D50505050");
		$placeholderTrainer6[6]["message_start"] = hex2bin("0B050103000D2404");
		$placeholderTrainer6[6]["message_win"] = hex2bin("1D050A0225030407");
		$placeholderTrainer6[6]["message_lose"] = hex2bin("0103350404050102");
		
		// Trainer 2
		$placeholderTrainer6[5] = array();
		$placeholderTrainer6[5]["name"] = hex2bin("87809382878491505050");
		$placeholderTrainer6[5]["class"] = hexdec("20");
		$placeholderTrainer6[5]["pokemon1"] = hex2bin("F2AE4487F7550000053BD8D6D8D6D8D6D8E290C350DFED140A0F0F6400000046000001EF01EF004D0050008F00A600FA818B889292849850505050");
		$placeholderTrainer6[5]["pokemon2"] = hex2bin("8F929D593BF70000053BD8D6D8D6D8EA60D6D8C350DDDD0A0A050F64000000460000016D016D00D9009C0069009800D7928D8E918B809750505050");
		$placeholderTrainer6[5]["pokemon3"] = hex2bin("E552F235B92E0000053BD8E290C350D6D8EA60D6D8DDCD0F0F14146400000046000000F500F500BB008500C500D900AF878E948D838E8E8C505050");
		$placeholderTrainer6[5]["message_start"] = hex2bin("0D0E09030B0D290B");
		$placeholderTrainer6[5]["message_win"] = hex2bin("170D24042B0D0903");
		$placeholderTrainer6[5]["message_lose"] = hex2bin("360509033504020D");
		
		// Trainer 3
		$placeholderTrainer6[4] = array();
		$placeholderTrainer6[4]["name"] = hex2bin("8980828A928E8D505050");
		$placeholderTrainer6[4]["class"] = hexdec("3E");
		$placeholderTrainer6[4]["pokemon1"] = hex2bin("F89259F29D3F0000053BD8C350AFC8AFC8C350AFC8DBDF0A0F0A05640000004600000117011700F700D3009300C400CB939891808D889380915050");
		$placeholderTrainer6[4]["pokemon2"] = hex2bin("91AE5541563F0000053BD8AFC8C350C350AFC8AFC8DBDF0F141405640000004600000108010800BB00B100C800EE00BD99808F838E925050505050");
		$placeholderTrainer6[4]["pokemon3"] = hex2bin("676D9C995ECA0000053BD8AFC8C3509C40C350AFC8DDED0A050A0564000000460000010C010C00C200B1008C00EB0097849784868694938E915050");
		$placeholderTrainer6[4]["message_start"] = hex2bin("1104060D31040105");
		$placeholderTrainer6[4]["message_win"] = hex2bin("0A02400801030704");
		$placeholderTrainer6[4]["message_lose"] = hex2bin("0203110D31040105");
		
		// Trainer 4
		$placeholderTrainer6[3] = array();
		$placeholderTrainer6[3]["name"] = hex2bin("8A80878D505050505050");
		$placeholderTrainer6[3]["class"] = hexdec("1E");
		$placeholderTrainer6[3]["pokemon1"] = hex2bin("C5AEECB95EF70000053BD8C350C350AFC8AFC8C350FDEB05140A0F64000000460000010D010D009B00D60098008E00F0948C8191848E8D50505050");
		$placeholderTrainer6[3]["pokemon2"] = hex2bin("820339553F2E0000053BD8D6D8AFC8C350D6D8C350DBEF0F0F051464000000460000010F010F00EB00A900B2009400CC8698809180838E92505050");
		$placeholderTrainer6[3]["pokemon3"] = hex2bin("C36D5939BCE70000053BD8C350C350AFC8C350C350DEDD0A0F0A0F64000000460000010A010A00B400B4006E009800989094808692889184505050");
		$placeholderTrainer6[3]["message_start"] = hex2bin("0104070E43043004");
		$placeholderTrainer6[3]["message_win"] = hex2bin("C500 0B0D20040205");
		$placeholderTrainer6[3]["message_lose"] = hex2bin("1105C3000B0D3F04");
		
		// Trainer 5
		$placeholderTrainer6[2] = array();
		$placeholderTrainer6[2]["name"] = hex2bin("8B848E8D865050505050");
		$placeholderTrainer6[2]["class"] = hexdec("16");
		$placeholderTrainer6[2]["pokemon1"] = hex2bin("D98CA3593F090000053BD8C350AFC8C350AFC8C350FDED140A050F640000004600000106010600F500A6008A00A600A69491928091888D86505050");
		$placeholderTrainer6[2]["pokemon2"] = hex2bin("7A5273075EE30000053BD8C350AFC8AFC8C350C350BDFB140F0A056400000046000000C300C30078009700BE00C600E28C91E88C888C8450505050");
		$placeholderTrainer6[2]["pokemon3"] = hex2bin("3949EE08099D0000053BD8C3509C40C350C350C350BDEF050F0F0A6400000046000000E300E300CA009100C4009400A28F91888C84808F84505050");
		$placeholderTrainer6[2]["message_start"] = hex2bin("240330040F0D2004");
		$placeholderTrainer6[2]["message_win"] = hex2bin("21042E0D3D072705");
		$placeholderTrainer6[2]["message_lose"] = hex2bin("3605370333063E04");
		
		// Trainer 6
		$placeholderTrainer6[1] = array();
		$placeholderTrainer6[1]["name"] = hex2bin("8C8091888D8E50505050");
		$placeholderTrainer6[1]["class"] = hexdec("22");
		$placeholderTrainer6[1]["pokemon1"] = hex2bin("CBAE61E2F2590000053BD8C350C350C350C350C350FEFD1E280F0A6400000046000000E700E700B0009A00B700BB00988688918085809188865050");
		$placeholderTrainer6[1]["pokemon2"] = hex2bin("6A77B3CB22190000053BD8C350C350C350C350C350FEFE0F0A0F056400000046000000CA00CA00E8008900BA007000D98788938C8E8D8B84845050");
		$placeholderTrainer6[1]["pokemon3"] = hex2bin("D603B3CBE0590000053BD8C350C350C350C350C350F7F70F0A0A0A6400000046000000FB00FB00EF009E00B7006D00BA8784918082918E92925050");
		$placeholderTrainer6[1]["message_start"] = hex2bin("0203150D06040105");
		$placeholderTrainer6[1]["message_win"] = hex2bin("1304040506033F04");
		$placeholderTrainer6[1]["message_lose"] = hex2bin("0103140D3A040D0E");
		
		// Trainer 7
		$placeholderTrainer6[0] = array();
		$placeholderTrainer6[0]["name"] = hex2bin("8D84968C808D50505050");
		$placeholderTrainer6[0]["class"] = hexdec("28");
		$placeholderTrainer6[0]["pokemon1"] = hex2bin("0303F14CEB3F0000053BD8753075307530753075307644050A05050000000046000000E900E9009F009F009800B400B495848D9492809491505050");
		$placeholderTrainer6[0]["pokemon2"] = hex2bin("068CA3593F350000053BD8753075307530753075305644140A050F0000000046000000E600E6009F009800B400C1009F8287809188998091835050");
		$placeholderTrainer6[0]["pokemon3"] = hex2bin("094938083FE70000053BD8753075307530753075307664050F050F0000000046000000E700E700A100B70098009F00BB818B8092938E8892845050");
		$placeholderTrainer6[0]["message_start"] = hex2bin("0A020B0609040105");
		$placeholderTrainer6[0]["message_win"] = hex2bin("2403300442040105");
		$placeholderTrainer6[0]["message_lose"] = hex2bin("290D100D14061508");
		
		return $placeholderTrainer6[$number];
	}
	
	function getBattleTowerPlaceholderTrainerEN7($number) {
		$placeholderTrainer7 = array();
		
		// Trainer 1
		$placeholderTrainer7[6] = array();
		$placeholderTrainer7[6]["name"] = hex2bin("8D869498848D50505050");
		$placeholderTrainer7[6]["class"] = hexdec("32");
		$placeholderTrainer7[6]["pokemon1"] = hex2bin("876D5655E72E000007D000EA60D6D8EA60D6D8D6D8FDEB140F0F14640000005000000107010700B300AA011900F500DD898E8B93848E8D50505050");
		$placeholderTrainer7[6]["pokemon2"] = hex2bin("8F929CBBAD59000007D000EA60D6D8C350D8CCEA60DBED0A0A0F0A64000000500000019F019F00F800AA007900B200FA928D8E918B809750505050");
		$placeholderTrainer7[6]["pokemon3"] = hex2bin("E5549CF235F1000007D000D6D8C350D6D8D6D8D6D8FDDB0A0F0F05640000005000000118011800D9009800E000F500C5878E948D838E8E8C505050");
		$placeholderTrainer7[6]["message_start"] = hex2bin("12050103140D3604");
		$placeholderTrainer7[6]["message_win"] = hex2bin("12052503200D2604");
		$placeholderTrainer7[6]["message_lose"] = hex2bin("3605010335040105");
		
		// Trainer 2
		$placeholderTrainer7[5] = array();
		$placeholderTrainer7[5]["name"] = hex2bin("8E8683848D5050505050");
		$placeholderTrainer7[5]["class"] = hexdec("29");
		$placeholderTrainer7[5]["pokemon1"] = hex2bin("80AE5922E73F000007D000C350C350C3507530C350FDDE0A0F0F05640000005000000114011400E900DD00EC008700B7938094918E925050505050");
		$placeholderTrainer7[5]["pokemon2"] = hex2bin("83549C396D5E000007D000C350C350C350D6D8C350DFDB0A0F0A0A64000000500000016E016E00CD00C900A800CA00DA8B808F9180925050505050");
		$placeholderTrainer7[5]["pokemon3"] = hex2bin("F86D9CF2599D000007D000C350D6D8C350D6D8C350DFDB0A0F0A0A64000000500000013E013E011E00F900A900DA00E2939891808D889380915050");
		$placeholderTrainer7[5]["message_start"] = hex2bin("01030F0725032906");
		$placeholderTrainer7[5]["message_win"] = hex2bin("0A023A0B390D0404");
		$placeholderTrainer7[5]["message_lose"] = hex2bin("2503220D05081106");
		
		// Trainer 3
		$placeholderTrainer7[4] = array();
		$placeholderTrainer7[4]["name"] = hex2bin("8F80918A505050505050");
		$placeholderTrainer7[4]["class"] = hexdec("1C");
		$placeholderTrainer7[4]["pokemon1"] = hex2bin("5E0055F76DA8000007D000C350C350AFC8D6D8C350DEDD0F0F0A0A6400000050000000F700F700AD00A500F8011500BD86848D8680915050505050");
		$placeholderTrainer7[4]["pokemon2"] = hex2bin("CD92995C4CCF000007D000AFC8C350C350AFC8C350FDED050A0A0F640000005000000111011100D90125008500A500A5858E919184939184929250");
		$placeholderTrainer7[4]["pokemon3"] = hex2bin("E6549C393BE1000007D000AFC8C3509C40D6D8C350FBED0A0F0514640000005000000111011100E100D600D100DD00DD8A888D8683918050505050");
		$placeholderTrainer7[4]["message_start"] = hex2bin("390B120305032D08");
		$placeholderTrainer7[4]["message_win"] = hex2bin("4407360D0E0E2C04");
		$placeholderTrainer7[4]["message_lose"] = hex2bin("30072E0D09090405");
		
		// Trainer 4
		$placeholderTrainer7[3] = array();
		$placeholderTrainer7[3]["name"] = hex2bin("9180888D845050505050");
		$placeholderTrainer7[3]["class"] = hexdec("26");
		$placeholderTrainer7[3]["pokemon1"] = hex2bin("95AE563955C8000007D000C350C350C350C350AFC8DDDD140F0F0F64000000500000012F012F011C00DD00C500E400E4839180868E8D8893845050");
		$placeholderTrainer7[3]["pokemon2"] = hex2bin("E9925E693FA1000007D000D6D8C3509C40D6D8C350DFED0A14050A640000005000000125012500C500D500A900ED00DD8F8E9198868E8DF8505050");
		$placeholderTrainer7[3]["pokemon3"] = hex2bin("7C498E3B8A5E000007D000D6D8C350AFC8C350C350DFDF0A050F0A64000000500000010801080095007F00DD010100E189988D9750505050505050");
		$placeholderTrainer7[3]["message_start"] = hex2bin("1C0C0B0D05082705");
		$placeholderTrainer7[3]["message_win"] = hex2bin("01030B092A0D110A");
		$placeholderTrainer7[3]["message_lose"] = hex2bin("1F08390D210C0605");
		
		// Trainer 5
		$placeholderTrainer7[2] = array();
		$placeholderTrainer7[2]["name"] = hex2bin("92848B8B925050505050");
		$placeholderTrainer7[2]["class"] = hexdec("18");
		$placeholderTrainer7[2]["pokemon1"] = hex2bin("E2AE396D3B11000007D0009C40AFC89C40AFC8C350DFDC0F0A05236400000050000001000100008400B500B400C401248C808D93888D8450505050");
		$placeholderTrainer7[2]["pokemon2"] = hex2bin("E349D313BD5C000007D000C350AFC888B8C350C350DDEF190F0A0A640000005000000102010200C4011E00B7008900B9928A80918C8E9198505050");
		$placeholderTrainer7[2]["pokemon3"] = hex2bin("928A358FD33F000007D000C3509C40C3509C40C350DDFE0F05190564000000500000012C012C00E100D500D5010F00CF8C8E8B9391849250505050");
		$placeholderTrainer7[2]["message_start"] = hex2bin("0904270B0E010801");
		$placeholderTrainer7[2]["message_win"] = hex2bin("0E0108010B0D2004");
		$placeholderTrainer7[2]["message_lose"] = hex2bin("160E200D28070004");
		
		// Trainer 6
		$placeholderTrainer7[1] = array();
		$placeholderTrainer7[1]["name"] = hex2bin("918E828A96848B8B5050");
		$placeholderTrainer7[1]["class"] = hexdec("3A");
		$placeholderTrainer7[1]["pokemon1"] = hex2bin("8E6D3F9C592E000007D000C3509C40C3509C40C350FFED050A0A1464000000500000011A011A00ED00B1011300A500BD8084918E83808293988B50");
		$placeholderTrainer7[1]["pokemon2"] = hex2bin("65525599F35C000007D000C350C3509C409C40C350FFEF0F05140A6400000050000000FA00FA009900B5012300C900C9848B848293918E83845050");
		$placeholderTrainer7[1]["pokemon3"] = hex2bin("338CA359A8BD000007D000C350C3509C40C3509C40FDDD140A0A0A6400000050000000D600D600C900910105009100B18394869391888E50505050");
		$placeholderTrainer7[1]["message_start"] = hex2bin("1B02230D01050202");
		$placeholderTrainer7[1]["message_win"] = hex2bin("2605300D16040405");
		$placeholderTrainer7[1]["message_lose"] = hex2bin("05082C041B040105");
		
		// Trainer 7
		$placeholderTrainer7[0] = array();
		$placeholderTrainer7[0]["name"] = hex2bin("93878E918D938E8D5050");
		$placeholderTrainer7[0]["class"] = hexdec("19");
		$placeholderTrainer7[0]["pokemon1"] = hex2bin("4749CABC3F5C000007D000753075307530753075306565050A050A000000005000000104010400D9009700A100CF008F9588829391848481848B50");
		$placeholderTrainer7[0]["pokemon2"] = hex2bin("7FAE3F42465C000007D00075307530753075307530746405190F0A0000000050000000F100F100FA00CD00B90085009D8F888D9288915050505050");
		$placeholderTrainer7[0]["pokemon3"] = hex2bin("D2032EF73F09000007D000753075307530753075307657140F050F00000000500000011E011E00F200A90077009200928691808D81948B8B505050");
		$placeholderTrainer7[0]["message_start"] = hex2bin("2C0413090B0D2204");
		$placeholderTrainer7[0]["message_win"] = hex2bin("2705380D22040405");
		$placeholderTrainer7[0]["message_lose"] = hex2bin("0C05260D02030B04");
		
		return $placeholderTrainer7[$number];
	}
	
	function getBattleTowerPlaceholderTrainerEN8($number) {
		$placeholderTrainer8 = array();
		
		// Trainer 1
		$placeholderTrainer8[6] = array();
		$placeholderTrainer8[6]["name"] = hex2bin("9394918D849150505050");
		$placeholderTrainer8[6]["class"] = hexdec("41");
		$placeholderTrainer8[6]["pokemon1"] = hex2bin("C552B9BDEC6D00000B1FA8EA60EA60D6D8EA60D6D8FDED140A050A640000005A0000015D015D00CB011600CA00BC013A948C8191848E8D50505050");
		$placeholderTrainer8[6]["pokemon2"] = hex2bin("95497E3FC83B00000B1FA8FDE8C350DAC0EA60EA60FDED05050F05640000005A000001570157014200FC00E501070107839180868E8D8893845050");
		$placeholderTrainer8[6]["pokemon3"] = hex2bin("79926955395E00000B1FA8EA60EA60EA60D6D8EA60DFDD140F0F0A640000005A00000121012100DA00EF011F010700EC929380918C888450505050");
		$placeholderTrainer8[6]["message_start"] = hex2bin("0603200404052404");
		$placeholderTrainer8[6]["message_win"] = hex2bin("26033C0419040105");
		$placeholderTrainer8[6]["message_lose"] = hex2bin("0203170D24040605");
		
		// Trainer 2
		$placeholderTrainer8[5] = array();
		$placeholderTrainer8[5]["name"] = hex2bin("95808D5083988A845050");
		$placeholderTrainer8[5]["class"] = hexdec("21");
		$placeholderTrainer8[5]["pokemon1"] = hex2bin("5B92993B39C400000B1FA8C350C350C350C350C350DBDF05050F0F640000005A0000010A010A00F8018E00CB00EA00A2828B8E9892938491505050");
		$placeholderTrainer8[5]["pokemon2"] = hex2bin("A9AE11723FCA00000B1FA8C350C350C350C350C350FDCF231E0505640000005A00000145014500F300DD013600CF00E182918E8180935050505050");
		$placeholderTrainer8[5]["pokemon3"] = hex2bin("E9495C5E69B600000B1FA8C350AFC8C350D6D8C350FDED0A0A140A640000005A00000145014500DF00EF00BE010A00F88F8E9198868E8DF8505050");
		$placeholderTrainer8[5]["message_start"] = hex2bin("25030A0624080202");
		$placeholderTrainer8[5]["message_win"] = hex2bin("0F06240804040105");
		$placeholderTrainer8[5]["message_lose"] = hex2bin("02030F0D24080105");
		
		// Trainer 3
		$placeholderTrainer8[4] = array();
		$placeholderTrainer8[4]["name"] = hex2bin("96808B8A849150505050");
		$placeholderTrainer8[4]["class"] = hexdec("17");
		$placeholderTrainer8[4]["pokemon1"] = hex2bin("E692E1393F3B00000B1FA8C350C350D6D8C350AFC8DFDE140F0505640000005A00000135013500F800FF00E600F800F88A888D8683918050505050");
		$placeholderTrainer8[4]["pokemon2"] = hex2bin("F8493FF2599D00000B1FA8C350D6D8C350AFC8C350DFDE050F0A0A640000005A0000016201620141011700B900FA0103939891808D889380915050");
		$placeholderTrainer8[4]["pokemon3"] = hex2bin("83549C39555E00000B1FA8AFC8C350C350C350D6D8BDEF0A0F0F0A640000005A00000195019500E300DD00BB00ED00FF8B808F9180925050505050");
		$placeholderTrainer8[4]["message_start"] = hex2bin("0D0D0E0B05033004");
		$placeholderTrainer8[4]["message_win"] = hex2bin("1D03390320040405");
		$placeholderTrainer8[4]["message_lose"] = hex2bin("0103170D0E0B110E");
		
		// Trainer 4
		$placeholderTrainer8[3] = array();
		$placeholderTrainer8[3]["name"] = hex2bin("8C849884915050505050");
		$placeholderTrainer8[3]["class"] = hexdec("27");
		$placeholderTrainer8[3]["pokemon1"] = hex2bin("C4AE5EF7F1EA00000B1FA8D6D8C350C350D6D8C350DDFE0A0F0505640000005A00000126012600C200B9011A013900FA84928F848E8D5050505050");
		$placeholderTrainer8[3]["pokemon2"] = hex2bin("4449EEE97E5900000B1FA8D6D8D6D8C350D6D8C350DDED050A050A640000005A000001510151013A00DD00B500C200E68C808287808C8F50505050");
		$placeholderTrainer8[3]["pokemon3"] = hex2bin("8F6D7E39593F00000B1FA8AFC8C350D6D8C350C350FEFD050F0A05640000005A000001C701C7011700C7008700C20113928D8E918B809750505050");
		$placeholderTrainer8[3]["message_start"] = hex2bin("020201031E0D0808");
		$placeholderTrainer8[3]["message_win"] = hex2bin("350704052E080808");
		$placeholderTrainer8[3]["message_lose"] = hex2bin("0D0E150D060D0105");
		
		// Trainer 5
		$placeholderTrainer8[2] = array();
		$placeholderTrainer8[2]["name"] = hex2bin("898E878D928E8D505050");
		$placeholderTrainer8[2]["class"] = hexdec("16");
		$placeholderTrainer8[2]["pokemon1"] = hex2bin("3B54F135F59C00000B1FA8C350AFC8C350AFC8D6D8DFDE050F050A640000005A000001500150011200E100F7010600E2809182808D888D84505050");
		$placeholderTrainer8[2]["pokemon2"] = hex2bin("F2924CF1877E00000B1FA8C350AFC8C350C350C350BDFE0A050A05640000005A000001A801A8005A005F00B400D60142818B889292849850505050");
		$placeholderTrainer8[2]["pokemon3"] = hex2bin("E50335F2F14C00000B1FA8C3509C40C350C350C350DBFE0F0F050A640000005A00000135013500EB00A400FC011500DF878E948D838E8E8C505050");
		$placeholderTrainer8[2]["message_start"] = hex2bin("25033E040B060904");
		$placeholderTrainer8[2]["message_win"] = hex2bin("240330040F0D3E07");
		$placeholderTrainer8[2]["message_lose"] = hex2bin("110E100D15080105");
		
		// Trainer 6
		$placeholderTrainer8[1] = array();
		$placeholderTrainer8[1]["name"] = hex2bin("8083808C925050505050");
		$placeholderTrainer8[1]["class"] = hexdec("2B");
		$placeholderTrainer8[1]["pokemon1"] = hex2bin("E349C9D35CD800000B1FA8C350C350C350C350C350EFF70A190A14FF0000005A00000117011700DF014D00CF008B00C1928A80918C8E9198505050");
		$placeholderTrainer8[1]["pokemon2"] = hex2bin("D5925C23B6E300000B1FA8C350C350C350C350C350FEFE0A140A05640000005A000000CB00CB006301ED005A006101ED928794828A8B8450505050");
		$placeholderTrainer8[1]["pokemon3"] = hex2bin("88543F35F72E00000B1FA8C350C350C350C350C350F7F7050F0F14640000005A000001250125013B00AF00C600EE0109858B8091848E8D50505050");
		$placeholderTrainer8[1]["message_start"] = hex2bin("3705010538054105");
		$placeholderTrainer8[1]["message_win"] = hex2bin("2505020D31054105");
		$placeholderTrainer8[1]["message_lose"] = hex2bin("0F05020D0A050205");
		
		// Trainer 7
		$placeholderTrainer8[0] = array();
		$placeholderTrainer8[0]["name"] = hex2bin("928C8893875050505050");
		$placeholderTrainer8[0]["class"] = hexdec("24");
		$placeholderTrainer8[0]["pokemon1"] = hex2bin("F192D059D52200000B1FA87530753075307530753047570A0A0F0F000000005A00000142014200C200F500E8008000B68C888B93808D8A50505050");
		$placeholderTrainer8[0]["pokemon2"] = hex2bin("8068553FD55900000B1FA87530753075307530753065760F050F0A000000005A0000011C011C00EA00DF00FE007E00B4938094918E925050505050");
		$placeholderTrainer8[0]["pokemon3"] = hex2bin("59495CBCD5CA00000B1FA87530753075307530753054440A0A0F05000000005A00000156015600F100B9008C00A700E68C948A5050505050505050");
		$placeholderTrainer8[0]["message_start"] = hex2bin("0103140D36040105");
		$placeholderTrainer8[0]["message_win"] = hex2bin("2D03190631080105");
		$placeholderTrainer8[0]["message_lose"] = hex2bin("1B05060331040305");
		
		return $placeholderTrainer8[$number];
	}
	
	function getBattleTowerPlaceholderTrainerEN9($number) {
		$placeholderTrainer9 = array();
		
		// Trainer 1
		$placeholderTrainer9[6] = array();
		$placeholderTrainer9[6]["name"] = hex2bin("93808988918850505050");
		$placeholderTrainer9[6]["class"] = hexdec("24");
		$placeholderTrainer9[6]["pokemon1"] = hex2bin("E554F2352E9C00000F4240EA60EA60EA60EA60EA60FDED0F0F140A64000000640000015B015B011400C0011C013800FC878E948D838E8E8C505050");
		$placeholderTrainer9[6]["pokemon2"] = hex2bin("4449EE593FE900000F4240EA60EA60EA60EA60EA60FDEF050A050A6400000064000001790179016400FC00CC00E2010A8C808287808C8F50505050");
		$placeholderTrainer9[6]["pokemon3"] = hex2bin("E69239E19C5C00000F4240EA60EA60EA60EA60EA60DFFE0F140A0A64000000640000015D015D011A011E010A011C011C8A888D8683918050505050");
		$placeholderTrainer9[6]["message_start"] = hex2bin("2505250308062004");
		$placeholderTrainer9[6]["message_win"] = hex2bin("110E100D33082705");
		$placeholderTrainer9[6]["message_lose"] = hex2bin("27051E0E200D2D07");
		
		// Trainer 2
		$placeholderTrainer9[5] = array();
		$placeholderTrainer9[5]["name"] = hex2bin("81808A84915050505050");
		$placeholderTrainer9[5]["class"] = hexdec("1E");
		$placeholderTrainer9[5]["pokemon1"] = hex2bin("8703552E56E700000F4240C350C350C3507530C350FDFE0F14140F640000006400000143014300DC00CE015201340116898E8B93848E8D50505050");
		$placeholderTrainer9[5]["pokemon2"] = hex2bin("80523F59E75500000F4240C350C350C350C350C350FDEF050A0F0F640000006400000155015501220114013400AA00E6938094918E925050505050");
		$placeholderTrainer9[5]["pokemon3"] = hex2bin("3B9235F5E73F00000F4240D6D8C350C350D6D8C350DDEF0F050F056400000064000001760176013200F60119012200FA809182808D888D84505050");
		$placeholderTrainer9[5]["message_start"] = hex2bin("0D0E2C04000D0607");
		$placeholderTrainer9[5]["message_win"] = hex2bin("1E0E210D15062C04");
		$placeholderTrainer9[5]["message_lose"] = hex2bin("110E100D200D2207");
		
		// Trainer 3
		$placeholderTrainer9[4] = array();
		$placeholderTrainer9[4]["name"] = hex2bin("828E8B8B888D92505050");
		$placeholderTrainer9[4]["class"] = hexdec("14");
		$placeholderTrainer9[4]["pokemon1"] = hex2bin("068C3559A31300000F4240C350C350D6D8D6D8D6D8FEDF0F0A140F6400000064000001570157010200F70121013701078287809188998091835050");
		$placeholderTrainer9[4]["pokemon2"] = hex2bin("6503565599F300000F4240AFC8C350C350AFC8AFC8FBEF140F0514640000006400000135013500BE00DE016E00F800F8848B848293918E83845050");
		$placeholderTrainer9[4]["pokemon3"] = hex2bin("706D39593F9D00000F4240D6D8C350D6D8C350AFC8FDEF0F0A050A6400000064000001940194015E014900A800B200B2918798838E8D5050505050");
		$placeholderTrainer9[4]["message_start"] = hex2bin("2503250D2C08020D");
		$placeholderTrainer9[4]["message_win"] = hex2bin("01031E0D0A0A260A");
		$placeholderTrainer9[4]["message_lose"] = hex2bin("0103160D3E060A0B");
		
		// Trainer 4
		$placeholderTrainer9[3] = array();
		$placeholderTrainer9[3]["name"] = hex2bin("928C8091935050505050");
		$placeholderTrainer9[3]["class"] = hexdec("29");
		$placeholderTrainer9[3]["pokemon1"] = hex2bin("D092593FCFF200000F4240C350C350D6D8EA60C350FDDE0A050F0F6400000064000001570157010401E9009800C600DA929384848B889750505050");
		$placeholderTrainer9[3]["pokemon2"] = hex2bin("165241D33FBD00000F4240D6D8C350C350D6D8C350FDCF1419050A6400000064000001440144010E00D8011F00D400D4858480918E965050505050");
		$placeholderTrainer9[3]["pokemon3"] = hex2bin("C877C3D4DCF700000F4240AFC8C350D6D8C350D6D8BDEF0505140F640000006400000135013500CA00D10102010701078C88928391848095949250");
		$placeholderTrainer9[3]["message_start"] = hex2bin("100529070103020D");
		$placeholderTrainer9[3]["message_win"] = hex2bin("2705110E340B0405");
		$placeholderTrainer9[3]["message_lose"] = hex2bin("1005020D1005020D");
		
		// Trainer 5
		$placeholderTrainer9[2] = array();
		$placeholderTrainer9[2]["name"] = hex2bin("83988A92939180505050");
		$placeholderTrainer9[2]["class"] = hexdec("27");
		$placeholderTrainer9[2]["pokemon1"] = hex2bin("D78CA33B8AB900000F4240C350C350BB80AFC8C350FDEF14050F1464000000640000012D012D011800C3013C00A000F0928D848092848B50505050");
		$placeholderTrainer9[2]["pokemon2"] = hex2bin("D449D33FA35C00000F4240C350C350C350C350AFC8FBFE1905140A64000000640000014D014D015E011A00DC00C400F6928288998E915050505050");
		$placeholderTrainer9[2]["pokemon3"] = hex2bin("F292553B7E8700000F4240C3509C40C35075307530DDFE0F05050A6400000064000002BF02BF0065006A00BC00E2015A818B889292849850505050");
		$placeholderTrainer9[2]["message_start"] = hex2bin("0D0E000D060D3F08");
		$placeholderTrainer9[2]["message_win"] = hex2bin("0203110D3A063107");
		$placeholderTrainer9[2]["message_lose"] = hex2bin("2705160E2607020D");
		
		// Trainer 6
		$placeholderTrainer9[1] = array();
		$placeholderTrainer9[1]["name"] = hex2bin("8480938E8D5050505050");
		$placeholderTrainer9[1]["class"] = hexdec("2D");
		$placeholderTrainer9[1]["pokemon1"] = hex2bin("DD549C3B3F5900000F4240C350C350C350C350C350FEF70A05050A6400000064000001830183012200F800BE00C200C28F888B8E9296888D845050");
		$placeholderTrainer9[1]["pokemon2"] = hex2bin("67495E5C99CA00000F4240C350C350C350C350C350FEFE0A0A050564000000640000017701770118010200C8015200DA849784868694938E915050");
		$placeholderTrainer9[1]["pokemon3"] = hex2bin("8B9239F63B5C00000F4240C350C350C350C350C350FBE70F05050A64000000640000014B014B00D2014C00C6013000D68E8C809293809150505050");
		$placeholderTrainer9[1]["message_start"] = hex2bin("0203150D170C1203");
		$placeholderTrainer9[1]["message_win"] = hex2bin("0103230B2E0D1904");
		$placeholderTrainer9[1]["message_lose"] = hex2bin("0F063A0D0103170C");
		
		// Trainer 7
		$placeholderTrainer9[0] = array();
		$placeholderTrainer9[0]["name"] = hex2bin("968E8D86505050505050");
		$placeholderTrainer9[0]["class"] = hexdec("30");
		$placeholderTrainer9[0]["pokemon1"] = hex2bin("4C0399599D7E00000F4240753075307530753075307446050A0A050000000064000001490149011A013C009200AA00BE868E8B848C505050505050");
		$placeholderTrainer9[0]["pokemon2"] = hex2bin("6B774407090800000F4240753075307530753075306776140F0F0F0000000064000001090109010E00DC00D6008201188788938C8E8D8287808D50");
		$placeholderTrainer9[0]["pokemon3"] = hex2bin("AB4939F0C06D00000F42407530753075307530753076570F05050A0000000064000001A901A900B200B000C000D600D68B808D9394918D50505050");
		$placeholderTrainer9[0]["message_start"] = hex2bin("1B020D0D2C040C08");
		$placeholderTrainer9[0]["message_win"] = hex2bin("1105160E22040105");
		$placeholderTrainer9[0]["message_lose"] = hex2bin("32041E0E200D2207");
		
		return $placeholderTrainer9[$number];
	}

	function getBattleTowerPlaceholderTrainerFR($number) {
		$placeholderTrainer0 = array();
		
		// Trainer 1
		$placeholderTrainer0[6] = array();
		$placeholderTrainer0[6]["name"] = hex2bin("8F80868D8E8B50505050");
		$placeholderTrainer0[6]["class"] = hexdec("25");
		$placeholderTrainer0[6]["pokemon1"] = hex2bin("876D553FF72E00000003E8C3509C409C4088B89C40DDBD0F050F14640000000A0000002900290019001800250022001F958E8B93808B8850505050");
		$placeholderTrainer0[6]["pokemon2"] = hex2bin("C492BD5EF45C00000003E89C40C35088B89C409C40EDFB0A0A0A0A640000000A000000270027001A001800230026001F8C848D93808B8850505050");
		$placeholderTrainer0[6]["pokemon3"] = hex2bin("C5AEF7E7F45C00000003E89C409C40AFC8C3509C40DBEF0F0F0A0A640000000A0000002E002E00190022001A001900278D8E8293808B8850505050");
		$placeholderTrainer0[6]["message_start"] = hex2bin("24033004340D0E09");
		$placeholderTrainer0[6]["message_win"] = hex2bin("2C0330040F0D2004");
		$placeholderTrainer0[6]["message_lose"] = hex2bin("370320040B060605");
		
		// Trainer 2
		$placeholderTrainer0[5] = array();
		$placeholderTrainer0[5]["name"] = hex2bin("839491808D8350505050");
		$placeholderTrainer0[5]["class"] = hexdec("1E");
		$placeholderTrainer0[5]["pokemon1"] = hex2bin("CA7744F3DBC200000003E8C350C350C350C350C3507FD714141905640000000A00000042004200120019001300120017968E818194858584935050");
		$placeholderTrainer0[5]["pokemon2"] = hex2bin("736DB33F59D500000003E89C4075309C4075307530EFCF0F050A0F640000000A0000002F002F001F001D001D0014001C8A808D868E949184975050");
		$placeholderTrainer0[5]["pokemon3"] = hex2bin("FR8C395E69F600000003E89C407530821475307530FEFD0F0A1405640000000A0000002600260017001D00130018001C828E9180988E8D50505050");
		$placeholderTrainer0[5]["message_start"] = hex2bin("2403CA000B0D1E06");
		$placeholderTrainer0[5]["message_win"] = hex2bin("CA000504050C0105");
		$placeholderTrainer0[5]["message_lose"] = hex2bin("CA0004053C070305");
		
		// Trainer 3
		$placeholderTrainer0[4] = array();
		$placeholderTrainer0[4]["name"] = hex2bin("9188928E8B8850505050");
		$placeholderTrainer0[4]["class"] = hexdec("2B");
		$placeholderTrainer0[4]["pokemon1"] = hex2bin("F1AE3B593F5C00000003E8753075307530753088B8BBDF050A050A640000000A0000002E002E001B0020001F0014001A848291848C849487505050");
		$placeholderTrainer0[4]["pokemon2"] = hex2bin("8E923F30592C00000003E875307530753075307530DBFB05140A19640000000A0000002B002B0020001800260017001A8F93849180505050505050");
		$placeholderTrainer0[4]["pokemon3"] = hex2bin("836D3B39555E00000003E875307530753075307530FFRB050F0F0A640000000A000000340034001D001B0018001C001E8B8E8A878B809292505050");
		$placeholderTrainer0[4]["message_start"] = hex2bin("0603290604050202");
		$placeholderTrainer0[4]["message_win"] = hex2bin("2F063D0406030C04");
		$placeholderTrainer0[4]["message_lose"] = hex2bin("030D04063504020D");
		
		// Trainer 4
		$placeholderTrainer0[3] = array();
		$placeholderTrainer0[3]["name"] = hex2bin("8C8E8C8E505050505050");
		$placeholderTrainer0[3]["class"] = hexdec("14");
		$placeholderTrainer0[3]["pokemon1"] = hex2bin("D7AEA3B9393B00000003E8753088B8753075307530FBBF14140F05640000000A000000260026001F001600220013001B8580918594918493505050");
		$placeholderTrainer0[3]["pokemon2"] = hex2bin("E9035E3B3FA100000003E8753075309C4075307530FBFR0A05050A640000000A0000002C002C001C001E00170021001F8F8E9198868E8DF8505050");
		$placeholderTrainer0[3]["pokemon3"] = hex2bin("C877C3D4DCF700000003E875307530753075307530EFDF0505140F640000000A00000025002500180018001C001D001D858494858E918495845050");
		$placeholderTrainer0[3]["message_start"] = hex2bin("350B050C350B020D");
		$placeholderTrainer0[3]["message_win"] = hex2bin("350B2D032A0D0604");
		$placeholderTrainer0[3]["message_lose"] = hex2bin("340B220D2A0D0604");
		
		// Trainer 5
		$placeholderTrainer0[2] = array();
		$placeholderTrainer0[2]["name"] = hex2bin("80948191845050505050");
		$placeholderTrainer0[2]["class"] = hexdec("3B");
		$placeholderTrainer0[2]["pokemon1"] = hex2bin("E4AEB94C2EF100000003E875307530753080E87530FDFE140A1405640000000A000000240024001800110019001C00168C808B8E92928450505050");
		$placeholderTrainer0[2]["pokemon2"] = hex2bin("CB523CBDF76100000003E875307530753075307530EDFD140A0F1E640000000A000000270027001C0018001D001D00188688918085809188865050");
		$placeholderTrainer0[2]["pokemon3"] = hex2bin("F2491D4CCD4600000003E87D009C40753075307530DFCE0F0A140F640000000A0000004D004D000E000E0016001B0027818B889292849850505050");
		$placeholderTrainer0[2]["message_start"] = hex2bin("4304030125036C00");
		$placeholderTrainer0[2]["message_win"] = hex2bin("2D0312030E066300");
		$placeholderTrainer0[2]["message_lose"] = hex2bin("1E0E42000A063304");
		
		// Trainer 6
		$placeholderTrainer0[1] = array();
		$placeholderTrainer0[1]["name"] = hex2bin("93918E86849150505050");
		$placeholderTrainer0[1]["class"] = hexdec("19");
		$placeholderTrainer0[1]["pokemon1"] = hex2bin("8F6D1DB6AD3900000003E875307530753075307530EFF70F0A0F0F640000000A00000039003900220019001200170020918E8D858B849750505050");
		$placeholderTrainer0[1]["pokemon2"] = hex2bin("67525CCAA85D00000003E875307530753075307530FEFE0A050A19640000000A0000002D002D001F001D001700250019849784868694938E915050");
		$placeholderTrainer0[1]["pokemon3"] = hex2bin("D6AEB3CB44F900000003E875307530753075307530F7F70F0A140F640000000A0000002B002B00250019001D0012001D8784918082918E92925050");
		$placeholderTrainer0[1]["message_start"] = hex2bin("2503030325040202");
		$placeholderTrainer0[1]["message_win"] = hex2bin("4105160E06063D04");
		$placeholderTrainer0[1]["message_lose"] = hex2bin("320D04050F060005");
		
		// Trainer 7
		$placeholderTrainer0[0] = array();
		$placeholderTrainer0[0]["name"] = hex2bin("808D8098805050505050");
		$placeholderTrainer0[0]["class"] = hexdec("16");
		$placeholderTrainer0[0]["pokemon1"] = hex2bin("C9AFRD00000000000003E875307530753075307530FFFF0F000000000000000A000000240024001A00150015001A00159980918188505050505050");
		$placeholderTrainer0[0]["pokemon2"] = hex2bin("80521DCF27C400000003E87530753075307530753065570F0F1E0F000000000A000000280028001E001D002000120018938094918E925050505050");
		$placeholderTrainer0[0]["pokemon3"] = hex2bin("7A495CF4071D00000003E87530753075307530753073670A0A0F0F000000000A00000022002200130016001C001E00228CE88C888C845050505050");
		$placeholderTrainer0[0]["message_start"] = hex2bin("0702200D0301240D");
		$placeholderTrainer0[0]["message_win"] = hex2bin("01022A0D0D0E0301");
		$placeholderTrainer0[0]["message_lose"] = hex2bin("3204060307082004");
		
		return $placeholderTrainer0[$number];
	}
	
	function getBattleTowerPlaceholderTrainerFR1($number) {
		$placeholderTrainer1 = array();
		
		// Trainer 1
		$placeholderTrainer1[6] = array();		
		$placeholderTrainer1[6]["name"] = hex2bin("938E8F808A5050505050");
		$placeholderTrainer1[6]["class"] = hexdec("2C");
		$placeholderTrainer1[6]["pokemon1"] = hex2bin("C592B65CBDD50000001F40C350C350C350C350C350CFBC0A0A0A0F6400000014000000510051002E0042002E002C00488D8E8293808B8850505050");
		$placeholderTrainer1[6]["pokemon2"] = hex2bin("79AE695E39F40000001F40C350C350C350C350C350DBDB140A0F0A6400000014000000470047003300360043003C0036929380918E929250505050");
		$placeholderTrainer1[6]["pokemon3"] = hex2bin("826D3F52557E0000001F40C350C350C350C350C350FAFD050A0F056400000014000000530053004800330036002D003D8B84958880938E91505050");
		$placeholderTrainer1[6]["message_start"] = hex2bin("2906220636040405");
		$placeholderTrainer1[6]["message_win"] = hex2bin("1D05010507033504");
		$placeholderTrainer1[6]["message_lose"] = hex2bin("1D05010526033504");
		
		// Trainer 2
		$placeholderTrainer1[5] = array();
		$placeholderTrainer1[5]["name"] = hex2bin("8F8E9493809183505050");
		$placeholderTrainer1[5]["class"] = hexdec("22");
		$placeholderTrainer1[5]["pokemon1"] = hex2bin("D0AE2EE7CF590000001F40C350AFC8C3507530C350FFFF140F0F0A64000000140000004D004D00370066001F002C0030929384848B889750505050");
		$placeholderTrainer1[5]["pokemon2"] = hex2bin("418B5EF45C090000001F40C350C3507530C3509C40FFRF0A0A0A0F6400000014000000440044002A00240045004B0037808B808A8099808C505050");
		$placeholderTrainer1[5]["pokemon3"] = hex2bin("3B03352E3FE70000001F4088B8AFC8C350D6D8C350DBFB0F14050F640000001400000051005100400034003C003C0034809182808D888D50505050");
		$placeholderTrainer1[5]["message_start"] = hex2bin("150B17032F0D1203");
		$placeholderTrainer1[5]["message_win"] = hex2bin("2603040605040C0C");
		$placeholderTrainer1[5]["message_lose"] = hex2bin("0603200D26040C0D");
		
		// Trainer 3
		$placeholderTrainer1[4] = array();
		$placeholderTrainer1[4]["name"] = hex2bin("8380918E8D5050505050");
		$placeholderTrainer1[4]["class"] = hexdec("3B");
		$placeholderTrainer1[4]["pokemon1"] = hex2bin("D677CBB3E0590000001F40C3507530AFC87530AFC8DFFR0A0F0A0A64000000140000004E004E0044003300340025003B8784918082918E92925050");
		$placeholderTrainer1[4]["pokemon2"] = hex2bin("67923F5E5C8A0000001F40AFC8C350C350AFC8AFC8FFRB050A0A0F6400000014000000530053003C0037002B0046002E849784868694938E915050");
		$placeholderTrainer1[4]["pokemon3"] = hex2bin("8EAE9C3F59520000001F40AFC8C3509C40C350AFC8FBBB0A050A0A64000000140000004E004E0040002D0048002C00328F93849180505050505050");
		$placeholderTrainer1[4]["message_start"] = hex2bin("0301040C0301050C");
		$placeholderTrainer1[4]["message_win"] = hex2bin("060620042A0D1703");
		$placeholderTrainer1[4]["message_lose"] = hex2bin("0F05060306062004");
		
		// Trainer 4
		$placeholderTrainer1[3] = array();
		$placeholderTrainer1[3]["name"] = hex2bin("808D8994505050505050");
		$placeholderTrainer1[3]["class"] = hexdec("3C");
		$placeholderTrainer1[3]["pokemon1"] = hex2bin("F2035E4287440000001F40C350C35075307530C350BDFE0A190A1464000000140000009400940018001600290033004B818B889292849850505050");
		$placeholderTrainer1[3]["pokemon2"] = hex2bin("83AE5E553B6D0000001F40D6D875309C40D6D87530FED70A0F050A640000001400000062006200350034002D003200368B8E8A878B809292505050");
		$placeholderTrainer1[3]["pokemon3"] = hex2bin("19A35556465C0000001F40AFC8C350AFC8C350C350FCFE0F140F0A64000000140000003A003A002C0020003A002900258F888A8082879450505050");
		$placeholderTrainer1[3]["message_start"] = hex2bin("02022A0D0A061904");
		$placeholderTrainer1[3]["message_win"] = hex2bin("0B0506063D040105");
		$placeholderTrainer1[3]["message_lose"] = hex2bin("02022A0D210D3304");
		
		// Trainer 5
		$placeholderTrainer1[2] = array();
		$placeholderTrainer1[2]["name"] = hex2bin("82878091818893505050");
		$placeholderTrainer1[2]["class"] = hexdec("3A");
		$placeholderTrainer1[2]["pokemon1"] = hex2bin("D477D3A35CC90000001F409C40AFC89C40AFC8C350FDFE19140A0A64000000140000004900490049003C002F002B003582889980988E9750505050");
		$placeholderTrainer1[2]["pokemon2"] = hex2bin("6BAE090807050000001F40C350AFC888B8C3507530FBFD0F0F0F146400000014000000430043003F003200340020003E9398868D8E8D5050505050");
		$placeholderTrainer1[2]["pokemon3"] = hex2bin("800355593F3B0000001F40C3509C40C35075307530FBEF0F0A050564000000140000004C004C003D003A003F0023002F938094918E925050505050");
		$placeholderTrainer1[2]["message_start"] = hex2bin("0603280404050301");
		$placeholderTrainer1[2]["message_win"] = hex2bin("1904300401051302");
		$placeholderTrainer1[2]["message_lose"] = hex2bin("3F0525030A040105");
		
		// Trainer 6
		$placeholderTrainer1[1] = array();
		$placeholderTrainer1[1]["name"] = hex2bin("8D888A88505050505050");
		$placeholderTrainer1[1]["class"] = hexdec("35");
		$placeholderTrainer1[1]["pokemon1"] = hex2bin("B85F393BD5F00000001F409C409C409C409C409C40EDF70F050F056400000014000000520052002800340029002500318099948C8091888B8B5050");
		$placeholderTrainer1[1]["pokemon2"] = hex2bin("F1525957D5390000001F409C409C409C409C409C40DFFE0A0A0F0F64000000140000005300530034003F003D00240030848291848C849487505050");
		$placeholderTrainer1[1]["pokemon3"] = hex2bin("28AE3F3B7ED50000001F409C409C409C409C409C40C7FE0505050F6400000014000000620062002F0023002700320028968886868B989394858550");
		$placeholderTrainer1[1]["message_start"] = hex2bin("3A0B2C031B083004");
		$placeholderTrainer1[1]["message_win"] = hex2bin("33030F0D1B080C0D");
		$placeholderTrainer1[1]["message_lose"] = hex2bin("0A022503210D2508");
		
		// Trainer 7
		$placeholderTrainer1[0] = array();
		$placeholderTrainer1[0]["name"] = hex2bin("928E95808D8493505050");
		$placeholderTrainer1[0]["class"] = hexdec("2D");
		$placeholderTrainer1[0]["pokemon1"] = hex2bin("28685ECFF41D0000001F4075307530753075307530C7770A0F0A0F0000000014000000610061002E00220022002E0024968886868B989394858550");
		$placeholderTrainer1[0]["pokemon2"] = hex2bin("22AD3B5939090000001F40753075307530753075305646050A0F0F00000000140000004A004A0034002E00310032002E8D88838E8A888D86505050");
		$placeholderTrainer1[0]["pokemon3"] = hex2bin("C349855939F00000001F40753075307530753075305547140A0F05000000001400000051005100310031001D002A002A8C80918088929384505050");
		$placeholderTrainer1[0]["message_start"] = hex2bin("050330040F0D1808");
		$placeholderTrainer1[0]["message_win"] = hex2bin("1A02010507033504");
		$placeholderTrainer1[0]["message_lose"] = hex2bin("060331040A060405");
		
		return $placeholderTrainer1[$number];
	}
	
	function getBattleTowerPlaceholderTrainerFR2($number) {
		$placeholderTrainer2 = array();
		
		// Trainer 1
		$placeholderTrainer2[6] = array();		
		$placeholderTrainer2[6]["name"] = hex2bin("92808D82878499505050");
		$placeholderTrainer2[6]["class"] = hexdec("31");
		$placeholderTrainer2[6]["pokemon1"] = hex2bin("876D55562EBD0000006978C350C350C350C350D6D8DBED0F14140A640000001E00000067006700440040006B00600057958E8B93808B8850505050");
		$placeholderTrainer2[6]["pokemon2"] = hex2bin("3E0368395A420000006978C350D6D8D6D8D6D8C350DDFB0F0F0519640000001E000000770077005100570049004600529380919380918350505050");
		$placeholderTrainer2[6]["pokemon3"] = hex2bin("7992565E69390000006978C350C350C350C350C350FFFF140A140F640000001E000000650065004B00510063005A0051929380918E929250505050");
		$placeholderTrainer2[6]["message_start"] = hex2bin("200D000301050202");
		$placeholderTrainer2[6]["message_win"] = hex2bin("15050603220D3104");
		$placeholderTrainer2[6]["message_lose"] = hex2bin("0F0506052D033008");
		
		// Trainer 2
		$placeholderTrainer2[5] = array();
		$placeholderTrainer2[5]["name"] = hex2bin("8B808C88828784505050");
		$placeholderTrainer2[5]["class"] = hexdec("3E");
		$placeholderTrainer2[5]["pokemon1"] = hex2bin("7CAE3B8E8AD50000006978C350C350C3507530C350FBEE050A0F0F640000001E000000660066003C00310053006200568B888F8F8E94938E945050");
		$placeholderTrainer2[5]["pokemon2"] = hex2bin("335259BCA3BD0000006978C350C3507530C350C350EFFF0A0A140A640000001E000000510051004D00380066003C00489391888E8F888A84949150");
		$placeholderTrainer2[5]["pokemon3"] = hex2bin("B603CAF14C680000006978AFC8AFC8C350D6D8C350DFDB05050A0F640000001E0000006D006D004C0051003C0052005881848B8B8E92928E8C5050");
		$placeholderTrainer2[5]["message_start"] = hex2bin("2C0330040F0D3F07");
		$placeholderTrainer2[5]["message_win"] = hex2bin("33030F0D3F070C0D");
		$placeholderTrainer2[5]["message_lose"] = hex2bin("1E05060339060105");
		
		// Trainer 3
		$placeholderTrainer2[4] = array();
		$placeholderTrainer2[4]["name"] = hex2bin("8F888D83849150505050");
		$placeholderTrainer2[4]["class"] = hexdec("30");
		$placeholderTrainer2[4]["pokemon1"] = hex2bin("F2925C7387B60000006978C3507530AFC87530AFC8FBED0A140A0A640000001E000000D900D900200021003B0049006D818B889292849850505050");
		$placeholderTrainer2[4]["pokemon2"] = hex2bin("E58A35F2F78A0000006978AFC8C350C350AFC8AFC8FFRD0F0F0F0F640000001E0000006C006C0054003B0056005E004C878E948D838E8E8C505050");
		$placeholderTrainer2[4]["pokemon3"] = hex2bin("446FRE08597E0000006978AFC8C3509C40C350AFC8FDBE050F0A05640000001E000000760076006C004B003D004400508C80828A8E868D84949150");
		$placeholderTrainer2[4]["message_start"] = hex2bin("3A0B240343040105");
		$placeholderTrainer2[4]["message_win"] = hex2bin("2105060526030504");
		$placeholderTrainer2[4]["message_lose"] = hex2bin("4005060525030C04");
		
		// Trainer 4
		$placeholderTrainer2[3] = array();
		$placeholderTrainer2[3]["name"] = hex2bin("809187808D5050505050");
		$placeholderTrainer2[3]["class"] = hexdec("27");
		$placeholderTrainer2[3]["pokemon1"] = hex2bin("A9AED56D5C110000006978C350C35075307530C350EFDC0F0A0A23640000001E0000006F006F0053004A00670046004C8D8E9293848D8584915050");
		$placeholderTrainer2[3]["pokemon2"] = hex2bin("E9035E693FA10000006978D6D875309C40D6D87530DFDB0A14050A640000001E000000750075004900530042005700518F8E9198868E8DF8505050");
		$placeholderTrainer2[3]["pokemon3"] = hex2bin("697659D83F9B0000006978AFC8C350AFC8C3507530DFRB0A14050AFF0000001E000000630063004D005E0038003600488E92928093948494915050");
		$placeholderTrainer2[3]["message_start"] = hex2bin("050330040F0D3607");
		$placeholderTrainer2[3]["message_win"] = hex2bin("030B120305033004");
		$placeholderTrainer2[3]["message_lose"] = hex2bin("020D030B0D061203");
		
		// Trainer 5
		$placeholderTrainer2[2] = array();
		$placeholderTrainer2[2]["name"] = hex2bin("818E9186505050505050");
		$placeholderTrainer2[2]["class"] = hexdec("26");
		$placeholderTrainer2[2]["pokemon1"] = hex2bin("65037155B65700000069789C40AFC89C40AFC8C350BFRF1E0F0A0A640000001E000000620062003900450071004E004E848B848293918E83845050");
		$placeholderTrainer2[2]["pokemon2"] = hex2bin("8392F037C4460000006978C350AFC888B8C3507530FFRB05190F0F640000001E0000008E008E0050004A0041004B00518B8E8A878B809292505050");
		$placeholderTrainer2[2]["pokemon3"] = hex2bin("ABAEF05739AF0000006978C3509C40C35075307530DFRB050A0F0F640000001E0000008B008B003E00400042004600468B808D9394918D50505050");
		$placeholderTrainer2[2]["message_start"] = hex2bin("01032C0B1A034404");
		$placeholderTrainer2[2]["message_win"] = hex2bin("140E1E0844040C0D");
		$placeholderTrainer2[2]["message_lose"] = hex2bin("1A0344040B0D3A08");
		
		// Trainer 6
		$placeholderTrainer2[1] = array();
		$placeholderTrainer2[1]["name"] = hex2bin("92938885859850505050");
		$placeholderTrainer2[1]["class"] = hexdec("21");
		$placeholderTrainer2[1]["pokemon1"] = hex2bin("C46D5D815CF40000006978AFC8C350C350C350C350EFF719140A0A640000001E000000630063004400420060006700528C848D93808B8850505050");
		$placeholderTrainer2[1]["pokemon2"] = hex2bin("4952235CBC3D0000006978C350AFC8C350B798AFC8FEFE140A0A14640000001E0000006E006E00470044005A004D006593848D9380829194848B50");
		$placeholderTrainer2[1]["pokemon3"] = hex2bin("5EAEA87A65CA0000006978C350AFC8C350C350C350F7F70A1E0F05640000001E0000006500650044003D0060006700468482938E8F8B80928C8050");
		$placeholderTrainer2[1]["message_start"] = hex2bin("200D030104050202");
		$placeholderTrainer2[1]["message_win"] = hex2bin("0C05060314060C04");
		$placeholderTrainer2[1]["message_lose"] = hex2bin("06030D07320D0405");
		
		// Trainer 7
		$placeholderTrainer2[0] = array();
		$placeholderTrainer2[0]["name"] = hex2bin("818E8B84505050505050");
		$placeholderTrainer2[0]["class"] = hexdec("36");
		$placeholderTrainer2[0]["pokemon1"] = hex2bin("D9AE1DB62E2B00000069787530753075307530753077450F0A141E000000001E000000720072006400430035004100419491928091888D86505050");
		$placeholderTrainer2[0]["pokemon2"] = hex2bin("160377E44081000000697875307530753075307530677714142314000000001E000000600060004B003D0052003A003A91808F809283848F888250");
		$placeholderTrainer2[0]["pokemon3"] = hex2bin("396D4302B374000000697875307530753075307530776714190F1E000000001E0000006300630055003A004E003A0040828E8B8E9292888D868450");
		$placeholderTrainer2[0]["message_start"] = hex2bin("2503200D05082804");
		$placeholderTrainer2[0]["message_win"] = hex2bin("3103340D29030904");
		$placeholderTrainer2[0]["message_lose"] = hex2bin("2603220D05040305");
		
		return $placeholderTrainer2[$number];
	}
	
	function getBattleTowerPlaceholderTrainerFR3($number) {
		$placeholderTrainer3 = array();
		
		// Trainer 1
		$placeholderTrainer3[6] = array();		
		$placeholderTrainer3[6]["name"] = hex2bin("8C888D848B5050505050");
		$placeholderTrainer3[6]["class"] = hexdec("20");
		$placeholderTrainer3[6]["pokemon1"] = hex2bin("80AED83F59E7000000FA00C350C350C350C350C350FDFE14050A0FFF000000280000008F008F00770071007F0046005E938094918E925050505050");
		$placeholderTrainer3[6]["pokemon2"] = hex2bin("E69239E13F3B000000FA00C350C350C350C350C350FFRF0F14050564000000280000008E008E00730071006A0073007387988F8E918E8850505050");
		$placeholderTrainer3[6]["pokemon3"] = hex2bin("8F49D522F459000000FA00C350C350C350C350C350EDDD0F0F0A0A6400000028000000CD00CD007E0059003D0059007D918E8D858B849750505050");
		$placeholderTrainer3[6]["message_start"] = hex2bin("1703040506040405");
		$placeholderTrainer3[6]["message_win"] = hex2bin("150B1703300D0105");
		$placeholderTrainer3[6]["message_lose"] = hex2bin("07031306330D1504");
		
		// Trainer 2
		$placeholderTrainer3[5] = array();
		$placeholderTrainer3[5]["name"] = hex2bin("92828788858491505050");
		$placeholderTrainer3[5]["class"] = hexdec("1D");
		$placeholderTrainer3[5]["pokemon1"] = hex2bin("8392553A6D39000000FA00C350C350C350C350C350FFRB0F0A0A0F6400000028000000BA00BA006B006500560067006F8B8E8A878B809292505050");
		$placeholderTrainer3[5]["pokemon2"] = hex2bin("D0AEC9E7595C000000FA00C350C350C350C350C350EFDB0A0F0A0A6400000028000000890089006A00C7003D004F0057929384848B889750505050");
		$placeholderTrainer3[5]["pokemon3"] = hex2bin("41525E096907000000FA00C350AFC8C350D6D8C350DFRF0A0F140F64000000280000007E007E004C004900870093006B808B808A8099808C505050");
		$placeholderTrainer3[5]["message_start"] = hex2bin("200D1F04330D2704");
		$placeholderTrainer3[5]["message_win"] = hex2bin("2F061F07080E2003");
		$placeholderTrainer3[5]["message_lose"] = hex2bin("060624082A0D1203");
		
		// Trainer 3
		$placeholderTrainer3[4] = array();
		$placeholderTrainer3[4]["name"] = hex2bin("8191808C809250505050");
		$placeholderTrainer3[4]["class"] = hexdec("29");
		$placeholderTrainer3[4]["pokemon1"] = hex2bin("79923B55395E000000FA00C350C350AFC8C350AFC8FDBE050F0F0A640000002800000083008300630068007F00750069929380918E929250505050");
		$placeholderTrainer3[4]["pokemon2"] = hex2bin("CAAE44F3DBC2000000FA00AFC8C350C350C350C350BFE7141419056400000028000000E900E9003E00550040003B004F968E818194858584935050");
		$placeholderTrainer3[4]["pokemon3"] = hex2bin("4C779959059D000000FA00AFC8C3509C40C350AFC8DFRD050A140A6400000028000000910091007D008B004A0050005886918E8B848C5050505050");
		$placeholderTrainer3[4]["message_start"] = hex2bin("0605060503010405");
		$placeholderTrainer3[4]["message_win"] = hex2bin("0605260305040405");
		$placeholderTrainer3[4]["message_lose"] = hex2bin("0605260335040405");
		
		// Trainer 4
		$placeholderTrainer3[3] = array();
		$placeholderTrainer3[3]["name"] = hex2bin("868E8A94505050505050");
		$placeholderTrainer3[3]["class"] = hexdec("32");
		$placeholderTrainer3[3]["pokemon1"] = hex2bin("D48CA3D3E43F000000FA00C350C350C3509C40C350BDFE1419140564000000280000008B008B008B007500590052006682889980988E9750505050");
		$placeholderTrainer3[3]["pokemon2"] = hex2bin("3352593FBCBD000000FA00AFC8C350C350C350C350FEBB0A050A0A64000000280000006C006C0067004E0083004B005B9391888E8F888A84949150");
		$placeholderTrainer3[3]["pokemon3"] = hex2bin("506D395E593B000000FA00AFC8C350AFC8C350C350BFCF0F0A0A0564000000280000009D009D005F007E003C00770067858B808680838E92925050");
		$placeholderTrainer3[3]["message_start"] = hex2bin("3F05200D03010105");
		$placeholderTrainer3[3]["message_win"] = hex2bin("1A0506063D040105");
		$placeholderTrainer3[3]["message_lose"] = hex2bin("220D3D040D0E0301");
		
		// Trainer 5
		$placeholderTrainer3[2] = array();
		$placeholderTrainer3[2]["name"] = hex2bin("8180818E945050505050");
		$placeholderTrainer3[2]["class"] = hexdec("1C");
		$placeholderTrainer3[2]["pokemon1"] = hex2bin("E900B0A03CA8000000FA00C350AFC8C350C350C350BCEF1E1E140A64000000280000009300930063006C0056007B00738F8E9198868E8DF8505050");
		$placeholderTrainer3[2]["pokemon2"] = hex2bin("3B8AAC2B222E000000FA00C350C350C350C350C350FEBB191E0F146400000028000000980098007F0066006F00730063809182808D888D50505050");
		$placeholderTrainer3[2]["pokemon3"] = hex2bin("CD92E5B65CC9000000FA00C350C350C350C350C350FA7F280A0A0A64000000280000008C008C006F0093004000570057858E919184939184929250");
		$placeholderTrainer3[2]["message_start"] = hex2bin("3A0B2503210D2704");
		$placeholderTrainer3[2]["message_win"] = hex2bin("06032F060C040105");
		$placeholderTrainer3[2]["message_lose"] = hex2bin("3104010506032104");
		
		// Trainer 6
		$placeholderTrainer3[1] = array();
		$placeholderTrainer3[1]["name"] = hex2bin("808B9584925050505050");
		$placeholderTrainer3[1]["class"] = hexdec("41");
		$placeholderTrainer3[1]["pokemon1"] = hex2bin("8BAEAE37F6F9000000FA00C350C350C350C350C350EFF70A19050F64000000280000008500850056008B0053007C0058808C8E8D88929380915050");
		$placeholderTrainer3[1]["pokemon2"] = hex2bin("0652535213A3000000FA00C350C350C350C350C350FEFE0F0A0F1464000000280000008E008E006A00640077007D006A8391808280948584945050");
		$placeholderTrainer3[1]["pokemon3"] = hex2bin("67037917F45D000000FA00C350C350C350C350C350F7E70A140A1964000000280000009E009E00730064005200840054849784868694938E915050");
		$placeholderTrainer3[1]["message_start"] = hex2bin("1203250331040105");
		$placeholderTrainer3[1]["message_win"] = hex2bin("3A05010506063D04");
		$placeholderTrainer3[1]["message_lose"] = hex2bin("3204220D2B070105");
		
		// Trainer 7
		$placeholderTrainer3[0] = array();
		$placeholderTrainer3[0]["name"] = hex2bin("8F8882878E8D50505050");
		$placeholderTrainer3[0]["class"] = hexdec("34");
		$placeholderTrainer3[0]["pokemon1"] = hex2bin("61035D091D32000000FA0075307530753075307530777A190F0F1400000000280000009200920056005300510058007A87988F8D8E8C8083845050");
		$placeholderTrainer3[0]["pokemon2"] = hex2bin("5949675C7C6A000000FA0075307530753075307530756B280A141E0000000028000000A100A1006F005600430053006F86918E9380838C8E919550");
		$placeholderTrainer3[0]["pokemon3"] = hex2bin("7D52710981AD000000FA007530753075307530753065771E0F140F00000000280000007C007C005D0047006F0067005F848B848293808194999950");
		$placeholderTrainer3[0]["message_start"] = hex2bin("0D0E0301100D2108");
		$placeholderTrainer3[0]["message_win"] = hex2bin("1A031904100D1A0B");
		$placeholderTrainer3[0]["message_lose"] = hex2bin("1A033304100D1A0B");
		
		return $placeholderTrainer3[$number];
	}
	
	function getBattleTowerPlaceholderTrainerFR4($number) {
		$placeholderTrainer4 = array();
		
		// Trainer 1
		$placeholderTrainer4[6] = array();		
		$placeholderTrainer4[6]["name"] = hex2bin("86808D8E8D5050505050");
		$placeholderTrainer4[6]["class"] = hexdec("3B");
		$placeholderTrainer4[6]["pokemon1"] = hex2bin("E6AE393F3BE1000001E848D6D8D6D8EA60C350D6D8DDFF0F0505140000000032000000B300B3008E008F00840090009087988F8E918E8850505050");
		$placeholderTrainer4[6]["pokemon2"] = hex2bin("E56D9CF28A35000001E848D6D8C350EA60EA60EA60DDFC0A0F0F0F0000000032000000B200B2008700620091009D007F878E948D838E8E8C505050");
		$placeholderTrainer4[6]["pokemon3"] = hex2bin("D592C99C5C23000001E848EA60EA60EA60EA60D6D8FDCF0A0A0A1400000000320000007B007B003C01160034003B01178280918093918E82505050");
		$placeholderTrainer4[6]["message_start"] = hex2bin("0C0C2503360D250A");
		$placeholderTrainer4[6]["message_win"] = hex2bin("2C03250A330D2309");
		$placeholderTrainer4[6]["message_lose"] = hex2bin("3E05000D350B020C");
		
		// Trainer 2
		$placeholderTrainer4[5] = array();
		$placeholderTrainer4[5]["name"] = hex2bin("81808194505050505050");
		$placeholderTrainer4[5]["class"] = hexdec("14");
		$placeholderTrainer4[5]["pokemon1"] = hex2bin("8F923F5939F4000001E848EA60D6D8D6D8EA60D6D8FFRF050A0F0A0000000032000001070107009F0070004F0072009F918E8D858B849750505050");
		$placeholderTrainer4[5]["pokemon2"] = hex2bin("83AE55396D3B000001E848D6D8EA60EA60D6D8EA60DDDD0F0F0A050000000032000000EA00EA00850080006B0085008F8B8E8A878B809292505050");
		$placeholderTrainer4[5]["pokemon3"] = hex2bin("87525556F7ED000001E848D6D8EA60FRA8D6D8D6D8EDFF0F140F0F0000000032000000A100A10072006B00B3009F0090958E8B93808B8850505050");
		$placeholderTrainer4[5]["message_start"] = hex2bin("030B170333060105");
		$placeholderTrainer4[5]["message_win"] = hex2bin("0A021203390D0604");
		$placeholderTrainer4[5]["message_lose"] = hex2bin("3204390D220D3604");
		
		// Trainer 3
		$placeholderTrainer4[4] = array();
		$placeholderTrainer4[4]["name"] = hex2bin("83888F80929094808B84");
		$placeholderTrainer4[4]["class"] = hexdec("1D");
		$placeholderTrainer4[4]["pokemon1"] = hex2bin("D4923FA361E8000001E848AFC8C3509C40C350AFC8DFED05141E230000000032000000A900A900AF0091006F0063007C82889980988E9750505050");
		$placeholderTrainer4[4]["pokemon2"] = hex2bin("C7549C395E85000001E848C3509C40AFC8C350C350DFFR0A0F0A140F00000032000000C400C40076007E004B0092009C918E888680838050505050");
		$placeholderTrainer4[4]["pokemon3"] = hex2bin("44AEEE597E09000001E8489C40AFC8C3509C40ABE0FFEC050A050F0D00000032000000BB00BB00B0007F0063006C00808C80828A8E868D84949150");
		$placeholderTrainer4[4]["message_start"] = hex2bin("01030504100B0405");
		$placeholderTrainer4[4]["message_win"] = hex2bin("0102010506032B07");
		$placeholderTrainer4[4]["message_lose"] = hex2bin("2405010506030206");
		
		// Trainer 4
		$placeholderTrainer4[3] = array();
		$placeholderTrainer4[3]["name"] = hex2bin("8F888E8B849350505050");
		$placeholderTrainer4[3]["class"] = hexdec("36");
		$placeholderTrainer4[3]["pokemon1"] = hex2bin("798C56695539000001E848AFC8ABE09C40AFC89C40FFFF14140F0F0000000032000000A100A10079008200A100910082929380918E929250505050");
		$placeholderTrainer4[3]["pokemon2"] = hex2bin("335259A33FBC000001E848AFC89C40C350AFC8C350F7FE0A14050A0000000032000000870087007D005900A6006000749391888E8F888A84949150");
		$placeholderTrainer4[3]["pokemon3"] = hex2bin("656D5599F39C000001E848C350AFC8D2F09C40C3507DFE0F05140A0000000032000000A100A10058007500B9007E007E848B848293918E83845050");
		$placeholderTrainer4[3]["message_start"] = hex2bin("03010405210E220D");
		$placeholderTrainer4[3]["message_win"] = hex2bin("0508020D38080105");
		$placeholderTrainer4[3]["message_lose"] = hex2bin("10022A0D06040105");
		
		// Trainer 5
		$placeholderTrainer4[2] = array();
		$placeholderTrainer4[2]["name"] = hex2bin("8B808C94505050505050");
		$placeholderTrainer4[2]["class"] = hexdec("18");
		$placeholderTrainer4[2]["pokemon1"] = hex2bin("8E523F597EE7000001E848AFC8C350C350AFC8AFC8FDDD050A050F0000000032000000B500B50098006E00AE006800778F93849180505050505050");
		$placeholderTrainer4[2]["pokemon2"] = hex2bin("A9926DD53F5C000001E848AFC89C40C3509C40C350EFFF0A0F050A0000000032000000B200B20086007F00AF0075007F8D8E9293848D8584915050");
		$placeholderTrainer4[2]["pokemon3"] = hex2bin("916D4155563F000001E848AFC8C350AFC89C40C350FDFR140F14050000000032000000BE00BE00890081008F00AB0088848B848293878E91505050");
		$placeholderTrainer4[2]["message_start"] = hex2bin("25033B0401050105");
		$placeholderTrainer4[2]["message_win"] = hex2bin("050330040F0D3E04");
		$placeholderTrainer4[2]["message_lose"] = hex2bin("030B120305033004");
		
		// Trainer 6
		$placeholderTrainer4[1] = array();
		$placeholderTrainer4[1]["name"] = hex2bin("8F88828A505050505050");
		$placeholderTrainer4[1]["class"] = hexdec("35");
		$placeholderTrainer4[1]["pokemon1"] = hex2bin("E3AEC913D35C000001E848AFC8C350C350C3509C40D7ED0A0F190A0000000032000000A400A4007D00B30074005300718088918C94918450505050");
		$placeholderTrainer4[1]["pokemon2"] = hex2bin("CD92C95C99CF000001E848C350C350D6D8AFC89C40CFDD0A0A050F0000000032000000A900A9008600BD005400670067858E919184939184929250");
		$placeholderTrainer4[1]["pokemon3"] = hex2bin("D06DC9E79C59000001E848AFC8C350C3509C40AFC8DDDD0A0F0A0A0000000032000000B000B0008200F500490063006D929384848B889750505050");
		$placeholderTrainer4[1]["message_start"] = hex2bin("030104053B050202");
		$placeholderTrainer4[1]["message_win"] = hex2bin("15060D0E03010105");
		$placeholderTrainer4[1]["message_lose"] = hex2bin("040C000D06040105");
		
		// Trainer 7
		$placeholderTrainer4[0] = array();
		$placeholderTrainer4[0]["name"] = hex2bin("8B80938E949150505050");
		$placeholderTrainer4[0]["class"] = hexdec("1E");
		$placeholderTrainer4[0]["pokemon1"] = hex2bin("CB8C8AF25E59000001E8489C409C409C409C409C4045560F0F0A0A6400000032000000A100A1007200640078007E00658688918085809188865050");
		$placeholderTrainer4[0]["pokemon2"] = hex2bin("826D3F39F0C0000001E8489C409C409C409C409C407565050F05056400000032000000C100C100A200720075005F00878B84958880938E91505050");
		$placeholderTrainer4[0]["pokemon3"] = hex2bin("90AE3B3F2EC4000001E8489C409C409C409C409C4045560505140F0000000032000000B500B5007700870078008300A1809193888A8E83888D5050");
		$placeholderTrainer4[0]["message_start"] = hex2bin("0C0C090D36040105");
		$placeholderTrainer4[0]["message_win"] = hex2bin("0502020525031506");
		$placeholderTrainer4[0]["message_lose"] = hex2bin("200D22071A030004");
		
		return $placeholderTrainer4[$number];
	}
	
	function getBattleTowerPlaceholderTrainerFR5($number) {
		$placeholderTrainer5 = array();
		
		// Trainer 1
		$placeholderTrainer5[6] = array();		
		$placeholderTrainer5[6]["name"] = hex2bin("81849994505050505050");
		$placeholderTrainer5[6]["class"] = hexdec("14");
		$placeholderTrainer5[6]["pokemon1"] = hex2bin("E692E1393F3B0000034BC0D6D8D6D8C350EA60EA60DDFE140F0505640000003C000000D300D300A900A700A100AC00AC87988F8E918E8850505050");
		$placeholderTrainer5[6]["pokemon2"] = hex2bin("F8AEF2599D3F0000034BC0D6D8EA60D6D8EA60D6D8FFRD0F0A0A05640000003C000000F000F000DC00BB008300A900AF939891808D8E8288855050");
		$placeholderTrainer5[6]["pokemon3"] = hex2bin("E56D35F28A9C0000034BC0EA60D6D8D6D8EA60D6D8FBEF0F0F0F0A640000003C000000D400D400A5007100AC00BD0099878E948D838E8E8C505050");
		$placeholderTrainer5[6]["message_start"] = hex2bin("0603070800080C0D");
		$placeholderTrainer5[6]["message_win"] = hex2bin("0C050B0626030504");
		$placeholderTrainer5[6]["message_lose"] = hex2bin("1B05210217030105");
		
		// Trainer 2
		$placeholderTrainer5[5] = array();
		$placeholderTrainer5[5]["name"] = hex2bin("94849294868850505050");
		$placeholderTrainer5[5]["class"] = hexdec("38");
		$placeholderTrainer5[5]["pokemon1"] = hex2bin("E9923B695C5E0000034BC0D6D8C350C350C350D6D8DDFR05140A0A640000003C000000DF00DF009500A1007D00B600AA8F8E9198868E8DF8505050");
		$placeholderTrainer5[5]["pokemon2"] = hex2bin("444907EE09590000034BC0C350C350AFC8C350C350FFRF0F050F0A640000003C000000E200E200D4009400780086009E8C80828A8E868D84949150");
		$placeholderTrainer5[5]["pokemon3"] = hex2bin("91549C4155560000034BC0C350AFC8C350D6D8C350DDFD0A140F14640000003C000000E500E500A0009B00B100CB00A1848B848293878E91505050");
		$placeholderTrainer5[5]["message_start"] = hex2bin("06030D0E28040C0D");
		$placeholderTrainer5[5]["message_win"] = hex2bin("2D03220D1703020D");
		$placeholderTrainer5[5]["message_lose"] = hex2bin("06032F06210D2704");
		
		// Trainer 3
		$placeholderTrainer5[4] = array();
		$placeholderTrainer5[4]["name"] = hex2bin("83949584918684915050");
		$placeholderTrainer5[4]["class"] = hexdec("17");
		$placeholderTrainer5[4]["pokemon1"] = hex2bin("CAAE44F3C2DB0000034BC0C350C350AFC8C350AFC8FFRD14140519640000003C0000015A015A005F007A005E005C007A968E818194858584935050");
		$placeholderTrainer5[4]["pokemon2"] = hex2bin("8E923F30592C0000034BC0AFC8C350C350AFC8AFC8FDDD05140A19640000003C000000D700D700B6008300D0007C008E8F93849180505050505050");
		$placeholderTrainer5[4]["pokemon3"] = hex2bin("956D3FC455390000034BC0AFC8C3509C40C350AFC8DDFD050F0F0F640000003C000000E500E500D600A4009800AC00AC839180828E8B8E92928450");
		$placeholderTrainer5[4]["message_start"] = hex2bin("01031C0B2C033004");
		$placeholderTrainer5[4]["message_win"] = hex2bin("210D190422061E09");
		$placeholderTrainer5[4]["message_lose"] = hex2bin("000D1E091A033304");
		
		// Trainer 4
		$placeholderTrainer5[3] = array();
		$placeholderTrainer5[3]["name"] = hex2bin("8A8491868E8093505050");
		$placeholderTrainer5[3]["class"] = hexdec("25");
		$placeholderTrainer5[3]["pokemon1"] = hex2bin("C5AEBFRC5EB90000034BC0C350C350C350C350C350FFRF0A050A14640000003C000000E800E8008600B90084008000D48D8E8293808B8850505050");
		$placeholderTrainer5[3]["pokemon2"] = hex2bin("3B8A35F2F5E70000034BC0D6D8C3509C40D6D8C350FFRD0F0F050F640000003C000000E400E400BC009200AA00AD0095809182808D888D50505050");
		$placeholderTrainer5[3]["pokemon3"] = hex2bin("E36DD3135CB60000034BC0C350C350AFC8C350C350FBEB190F0A0A640000003C000000C400C4009800DA008A006300878088918C94918450505050");
		$placeholderTrainer5[3]["message_start"] = hex2bin("0103160A110C1203");
		$placeholderTrainer5[3]["message_win"] = hex2bin("390D12021C061203");
		$placeholderTrainer5[3]["message_lose"] = hex2bin("390D3C060A06160A");
		
		// Trainer 5
		$placeholderTrainer5[2] = array();
		$placeholderTrainer5[2]["name"] = hex2bin("929499948A8850505050");
		$placeholderTrainer5[2]["class"] = hexdec("3C");
		$placeholderTrainer5[2]["pokemon1"] = hex2bin("F292875CB65E0000034BC0C350AFC8C350AFC8C350FBCD0A0A0A0A640000003C000001A801A80042003F0075008F00D7818B889292849850505050");
		$placeholderTrainer5[2]["pokemon2"] = hex2bin("8F689D3922590000034BC0C350AFC8C350C350C350FAFC0A0F0F0A640000003C00000133013300BA0080005C008200B8918E8D858B849750505050");
		$placeholderTrainer5[2]["pokemon3"] = hex2bin("D677B3E059440000034BC0C3509C40C350C350C350DFED0F0A0A14640000003C000000D600D600C80092009C006500A78784918082918E92925050");
		$placeholderTrainer5[2]["message_start"] = hex2bin("1502020D020D0402");
		$placeholderTrainer5[2]["message_win"] = hex2bin("1502020D11020105");
		$placeholderTrainer5[2]["message_lose"] = hex2bin("0A052603220D3106");
		
		// Trainer 6
		$placeholderTrainer5[1] = array();
		$placeholderTrainer5[1]["name"] = hex2bin("8C888287848B50505050");
		$placeholderTrainer5[1]["class"] = hexdec("34");
		$placeholderTrainer5[1]["pokemon1"] = hex2bin("7C6D3B5EF7C40000034BC0C350C350C350C350C350FFEB050A0F0F640000003C000000C400C40074006200A800BD00A58B888F8F8E94938E945050");
		$placeholderTrainer5[1]["pokemon2"] = hex2bin("09AE3959E53B0000034BC0C350C350C350C350C350FEFE0F0A2805640000003C000000D100D1009B00AE0095009C00B4938E9193808D8A50505050");
		$placeholderTrainer5[1]["pokemon3"] = hex2bin("70495939E79D0000034BC0C350C350C350C350C350FBFA0A0F0F0A640000003C000000F500F500D400C30068006800689187888D8E8584918E9250");
		$placeholderTrainer5[1]["message_start"] = hex2bin("2703060317030405");
		$placeholderTrainer5[1]["message_win"] = hex2bin("200D3F0823090405");
		$placeholderTrainer5[1]["message_lose"] = hex2bin("1806120304051005");
		
		// Trainer 7
		$placeholderTrainer5[0] = array();
		$placeholderTrainer5[0]["name"] = hex2bin("8B84828B849182505050");
		$placeholderTrainer5[0]["class"] = hexdec("36");
		$placeholderTrainer5[0]["pokemon1"] = hex2bin("1C8C59A33FAD0000034BC075307530753075307530B7670A14050F000000003C000000C900C900A400AB0074005D00699280818B80889184809450");
		$placeholderTrainer5[0]["pokemon2"] = hex2bin("2FAE93CA3FBC0000034BC075307530753075307530665F0F05050A000000003C000000AB00AB009800860048007800908F80918092848293505050");
		$placeholderTrainer5[0]["pokemon3"] = hex2bin("4C03995907DA0000034BC0753075307530753075307657050A0F14000000003C000000CD00CD00AB00C2005A0069007586918E8B848C5050505050");
		$placeholderTrainer5[0]["message_start"] = hex2bin("26031306330D1504");
		$placeholderTrainer5[0]["message_win"] = hex2bin("3A0601051C0E0404");
		$placeholderTrainer5[0]["message_lose"] = hex2bin("1B07010526033504");
		
		return $placeholderTrainer5[$number];
	}
	
	function getBattleTowerPlaceholderTrainerFR6($number) {
		$placeholderTrainer6 = array();
		
		// Trainer 1
		$placeholderTrainer6[6] = array();		
		$placeholderTrainer6[6]["name"] = hex2bin("83948F8E8D8350505050");
		$placeholderTrainer6[6]["class"] = hexdec("19");
		$placeholderTrainer6[6]["pokemon1"] = hex2bin("876D553FF72E0000053BD8EA60DAC0D6D8C350EA60FBEF0F050F146400000046000000E700E7009E009100F500FR00C9958E8B93808B8850505050");
		$placeholderTrainer6[6]["pokemon2"] = hex2bin("86923BF739BD0000053BD8C350C350EA60EA60C350BFEF050F0F0A64000000460000013E013E00950098009E00DA00C5809094808B885050505050");
		$placeholderTrainer6[6]["pokemon3"] = hex2bin("C5AEB9ECF45C0000053BD8D2F0EA60D6D8C350E290DDDD14050A0A6400000046000001120112009C00D90098009500F78D8E8293808B8850505050");
		$placeholderTrainer6[6]["message_start"] = hex2bin("250601062004240D");
		$placeholderTrainer6[6]["message_win"] = hex2bin("1D050A021C0E1807");
		$placeholderTrainer6[6]["message_lose"] = hex2bin("2603350404050102");
		
		// Trainer 2
		$placeholderTrainer6[5] = array();
		$placeholderTrainer6[5]["name"] = hex2bin("81888680918350505050");
		$placeholderTrainer6[5]["class"] = hexdec("20");
		$placeholderTrainer6[5]["pokemon1"] = hex2bin("F2AE4487F7550000053BD8D6D8D6D8D6D8E290C350DFED140A0F0F6400000046000001EF01EF004D0050008F00A600FA818B889292849850505050");
		$placeholderTrainer6[5]["pokemon2"] = hex2bin("8F929D593BF70000053BD8D6D8D6D8EA60D6D8C350DDDD0A0A050F64000000460000016D016D00D9009C0069009800D7918E8D858B849750505050");
		$placeholderTrainer6[5]["pokemon3"] = hex2bin("E552F235B92E0000053BD8E290C350D6D8EA60D6D8DDCD0F0F14146400000046000000F500F500BB008500C500D900AF878E948D838E8E8C505050");
		$placeholderTrainer6[5]["message_start"] = hex2bin("0D0E09030B0D2104");
		$placeholderTrainer6[5]["message_win"] = hex2bin("250633062F0D0903");
		$placeholderTrainer6[5]["message_lose"] = hex2bin("090322063504020D");
		
		// Trainer 3
		$placeholderTrainer6[4] = array();
		$placeholderTrainer6[4]["name"] = hex2bin("8C888287809183505050");
		$placeholderTrainer6[4]["class"] = hexdec("3E");
		$placeholderTrainer6[4]["pokemon1"] = hex2bin("F89259F29D3F0000053BD8C350AFC8AFC8C350AFC8DBDF0A0F0A05640000004600000117011700F700D3009300C400CB939891808D8E8288855050");
		$placeholderTrainer6[4]["pokemon2"] = hex2bin("91AE5541563F0000053BD8AFC8C350C350AFC8AFC8DBDF0F141405640000004600000108010800BB00B100C800EE00BD848B848293878E91505050");
		$placeholderTrainer6[4]["pokemon3"] = hex2bin("676D9C995ECA0000053BD8AFC8C3509C40C350AFC8DFRD0A050A0564000000460000010C010C00C200B1008C00EB0097849784868694938E915050");
		$placeholderTrainer6[4]["message_start"] = hex2bin("200D030131040105");
		$placeholderTrainer6[4]["message_win"] = hex2bin("0A02260305040105");
		$placeholderTrainer6[4]["message_lose"] = hex2bin("0603310417030105");
		
		// Trainer 4
		$placeholderTrainer6[3] = array();
		$placeholderTrainer6[3]["name"] = hex2bin("8F8093848B5050505050");
		$placeholderTrainer6[3]["class"] = hexdec("1E");
		$placeholderTrainer6[3]["pokemon1"] = hex2bin("C5AEECB95EF70000053BD8C350C350AFC8AFC8C350FFRB05140A0F64000000460000010D010D009B00D60098008E00F08D8E8293808B8850505050");
		$placeholderTrainer6[3]["pokemon2"] = hex2bin("820339553F2E0000053BD8D6D8AFC8C350D6D8C350DBEF0F0F051464000000460000010F010F00EB00A900B2009400CC8B84958880938E91505050");
		$placeholderTrainer6[3]["pokemon3"] = hex2bin("C36D5939BCE70000053BD8C350C350AFC8C350C350FRDD0A0F0A0F64000000460000010A010A00B400B4006E009800988C80918088929384505050");
		$placeholderTrainer6[3]["message_start"] = hex2bin("01042A031E064304");
		$placeholderTrainer6[3]["message_win"] = hex2bin("C5000B0D21040205");
		$placeholderTrainer6[3]["message_lose"] = hex2bin("3204C3000B0D0C04");
		
		// Trainer 5
		$placeholderTrainer6[2] = array();
		$placeholderTrainer6[2]["name"] = hex2bin("918E8391888694849950");
		$placeholderTrainer6[2]["class"] = hexdec("16");
		$placeholderTrainer6[2]["pokemon1"] = hex2bin("D98CA3593F090000053BD8C350AFC8C350AFC8C350FFRD140A050F640000004600000106010600F500A6008A00A600A69491928091888D86505050");
		$placeholderTrainer6[2]["pokemon2"] = hex2bin("7A5273075EE30000053BD8C350AFC8AFC8C350C350BDFB140F0A056400000046000000C300C30078009700BE00C600E28CE88C888C845050505050");
		$placeholderTrainer6[2]["pokemon3"] = hex2bin("3949EE08099D0000053BD8C3509C40C350C350C350BFRF050F0F0A6400000046000000E300E300CA009100C4009400A2828E8B8E9292888D868450");
		$placeholderTrainer6[2]["message_start"] = hex2bin("2C0330040F0D1E06");
		$placeholderTrainer6[2]["message_win"] = hex2bin("2503060620040105");
		$placeholderTrainer6[2]["message_lose"] = hex2bin("3605130606063E04");
		
		// Trainer 6
		$placeholderTrainer6[1] = array();
		$placeholderTrainer6[1]["name"] = hex2bin("8B848D84948550505050");
		$placeholderTrainer6[1]["class"] = hexdec("22");
		$placeholderTrainer6[1]["pokemon1"] = hex2bin("CBAE61E2F2590000053BD8C350C350C350C350C350FEFD1E280F0A6400000046000000E700E700B0009A00B700BB00988688918085809188865050");
		$placeholderTrainer6[1]["pokemon2"] = hex2bin("6A77B3CB22190000053BD8C350C350C350C350C350FEFE0F0A0F056400000046000000CA00CA00E8008900BA007000D98788938C8E8D8B84845050");
		$placeholderTrainer6[1]["pokemon3"] = hex2bin("D603B3CBE0590000053BD8C350C350C350C350C350F7F70F0A0A0A6400000046000000FB00FB00EF009E00B7006D00BA8784918082918E92925050");
		$placeholderTrainer6[1]["message_start"] = hex2bin("090D36042D03170E");
		$placeholderTrainer6[1]["message_win"] = hex2bin("1E05010506033E04");
		$placeholderTrainer6[1]["message_lose"] = hex2bin("01031D0B330D3604");
		
		// Trainer 7
		$placeholderTrainer6[0] = array();
		$placeholderTrainer6[0]["name"] = hex2bin("93918E80838482505050");
		$placeholderTrainer6[0]["class"] = hexdec("28");
		$placeholderTrainer6[0]["pokemon1"] = hex2bin("0303F14CEB3F0000053BD8753075307530753075307644050A05050000000046000000E900E9009F009F009800B400B4858B8E9188998091918450");
		$placeholderTrainer6[0]["pokemon2"] = hex2bin("068CA3593F350000053BD8753075307530753075305644140A050F0000000046000000E600E6009F009800B400C1009F8391808280948584945050");
		$placeholderTrainer6[0]["pokemon3"] = hex2bin("094938083FE70000053BD8753075307530753075307664050F050F0000000046000000E700E700A100B70098009F00BB938E9193808D8A50505050");
		$placeholderTrainer6[0]["message_start"] = hex2bin("0A02000D06040105");
		$placeholderTrainer6[0]["message_win"] = hex2bin("2403300431060105");
		$placeholderTrainer6[0]["message_lose"] = hex2bin("26033A0605040205");
		
		return $placeholderTrainer6[$number];
	}
	
	function getBattleTowerPlaceholderTrainerFR7($number) {
		$placeholderTrainer7 = array();
		
		// Trainer 1
		$placeholderTrainer7[6] = array();		
		$placeholderTrainer7[6]["name"] = hex2bin("81848D91808C8E945050");
		$placeholderTrainer7[6]["class"] = hexdec("32");
		$placeholderTrainer7[6]["pokemon1"] = hex2bin("876D5655E72E000007D000EA60D6D8EA60D6D8D6D8FFRB140F0F14640000005000000107010700B300AA011900F500DD958E8B93808B8850505050");
		$placeholderTrainer7[6]["pokemon2"] = hex2bin("8F929CBBAD59000007D000EA60D6D8C350D8CCEA60DBED0A0A0F0A64000000500000019F019F00F800AA007900B200FA918E8D858B849750505050");
		$placeholderTrainer7[6]["pokemon3"] = hex2bin("E5549CF235F1000007D000D6D8C350D6D8D6D8D6D8FDDB0A0F0F05640000005000000118011800D9009800E000F500C5878E948D838E8E8C505050");
		$placeholderTrainer7[6]["message_start"] = hex2bin("3F05250306062004");
		$placeholderTrainer7[6]["message_win"] = hex2bin("3F05250315060105");
		$placeholderTrainer7[6]["message_lose"] = hex2bin("0F05260335040105");
		
		// Trainer 2
		$placeholderTrainer7[5] = array();
		$placeholderTrainer7[5]["name"] = hex2bin("828E9393848D50505050");
		$placeholderTrainer7[5]["class"] = hexdec("29");
		$placeholderTrainer7[5]["pokemon1"] = hex2bin("80AE5922E73F000007D000C350C350C3507530C350FDFR0A0F0F05640000005000000114011400E900DD00EC008700B7938094918E925050505050");
		$placeholderTrainer7[5]["pokemon2"] = hex2bin("83549C396D5E000007D000C350C350C350D6D8C350DFDB0A0F0A0A64000000500000016E016E00CD00C900A800CA00DA8B8E8A878B809292505050");
		$placeholderTrainer7[5]["pokemon3"] = hex2bin("F86D9CF2599D000007D000C350D6D8C350D6D8C350DFDB0A0F0A0A64000000500000013E013E011E00F900A900DA00E2939891808D8E8288855050");
		$placeholderTrainer7[5]["message_start"] = hex2bin("0103210B25031E06");
		$placeholderTrainer7[5]["message_win"] = hex2bin("0A02260305040105");
		$placeholderTrainer7[5]["message_lose"] = hex2bin("2503220D12080105");
		
		// Trainer 3
		$placeholderTrainer7[4] = array();
		$placeholderTrainer7[4]["name"] = hex2bin("8C8E8D83985050505050");
		$placeholderTrainer7[4]["class"] = hexdec("1C");
		$placeholderTrainer7[4]["pokemon1"] = hex2bin("5E0055F76DA8000007D000C350C350AFC8D6D8C350FRDD0F0F0A0A6400000050000000F700F700AD00A500F8011500BD8482938E8F8B80928C8050");
		$placeholderTrainer7[4]["pokemon2"] = hex2bin("CD92995C4CCF000007D000AFC8C350C350AFC8C350FFRD050A0A0F640000005000000111011100D90125008500A500A5858E919184939184929250");
		$placeholderTrainer7[4]["pokemon3"] = hex2bin("E6549C393BE1000007D000AFC8C3509C40D6D8C350FBED0A0F0514640000005000000111011100E100D600D100DD00DD87988F8E918E8850505050");
		$placeholderTrainer7[4]["message_start"] = hex2bin("0603120817030405");
		$placeholderTrainer7[4]["message_win"] = hex2bin("1C0E2D070D0E0301");
		$placeholderTrainer7[4]["message_lose"] = hex2bin("200D2A0819090405");
		
		// Trainer 4
		$placeholderTrainer7[3] = array();
		$placeholderTrainer7[3]["name"] = hex2bin("918E8184919350505050");
		$placeholderTrainer7[3]["class"] = hexdec("26");
		$placeholderTrainer7[3]["pokemon1"] = hex2bin("95AE563955C8000007D000C350C350C350C350AFC8DDDD140F0F0F64000000500000012F012F011C00DD00C500E400E4839180828E8B8E92928450");
		$placeholderTrainer7[3]["pokemon2"] = hex2bin("E9925E693FA1000007D000D6D8C3509C40D6D8C350DFED0A14050A640000005000000125012500C500D500A900ED00DD8F8E9198868E8DF8505050");
		$placeholderTrainer7[3]["pokemon3"] = hex2bin("7C498E3B8A5E000007D000D6D8C350AFC8C350C350DFDF0A050F0A64000000500000010801080095007F00DD010100E18B888F8F8E94938E945050");
		$placeholderTrainer7[3]["message_start"] = hex2bin("18080E061C0C0C0D");
		$placeholderTrainer7[3]["message_win"] = hex2bin("2C062A0D0A060B09");
		$placeholderTrainer7[3]["message_lose"] = hex2bin("0E06210C29031C07");
		
		// Trainer 5
		$placeholderTrainer7[2] = array();
		$placeholderTrainer7[2]["name"] = hex2bin("8F888085505050505050");
		$placeholderTrainer7[2]["class"] = hexdec("18");
		$placeholderTrainer7[2]["pokemon1"] = hex2bin("E2AE396D3B11000007D0009C40AFC89C40AFC8C350DFDC0F0A05236400000050000001000100008400B500B400C4012483848C808D938050505050");
		$placeholderTrainer7[2]["pokemon2"] = hex2bin("E349D313BD5C000007D000C350AFC888B8C350C350DFRF190F0A0A640000005000000102010200C4011E00B7008900B98088918C94918450505050");
		$placeholderTrainer7[2]["pokemon3"] = hex2bin("928A358FD33F000007D000C3509C40C3509C40C350DDFE0F05190564000000500000012C012C00E100D500D5010F00CF92948B8594918050505050");
		$placeholderTrainer7[2]["message_start"] = hex2bin("390D0D0630040E01");
		$placeholderTrainer7[2]["message_win"] = hex2bin("0D0630040E013106");
		$placeholderTrainer7[2]["message_lose"] = hex2bin("280730040103210B");
		
		// Trainer 6
		$placeholderTrainer7[1] = array();
		$placeholderTrainer7[1]["name"] = hex2bin("8180918A8E8585505050");
		$placeholderTrainer7[1]["class"] = hexdec("3A");
		$placeholderTrainer7[1]["pokemon1"] = hex2bin("8E6D3F9C592E000007D000C3509C40C3509C40C350FFED050A0A1464000000500000011A011A00ED00B1011300A500BD8F93849180505050505050");
		$placeholderTrainer7[1]["pokemon2"] = hex2bin("65525599F35C000007D000C350C3509C409C40C350FFEF0F05140A6400000050000000FA00FA009900B5012300C900C9848B848293918E83845050");
		$placeholderTrainer7[1]["pokemon3"] = hex2bin("338CA359A8BD000007D000C350C3509C40C3509C40FDDD140A0A0A6400000050000000D600D600C900910105009100B19391888E8F888A84949150");
		$placeholderTrainer7[1]["message_start"] = hex2bin("0B02210201050202");
		$placeholderTrainer7[1]["message_win"] = hex2bin("2202160300040405");
		$placeholderTrainer7[1]["message_lose"] = hex2bin("0703010D15040105");
		
		// Trainer 7
		$placeholderTrainer7[0] = array();
		$placeholderTrainer7[0]["name"] = hex2bin("8B808C818B888D505050");
		$placeholderTrainer7[0]["class"] = hexdec("19");
		$placeholderTrainer7[0]["pokemon1"] = hex2bin("4749CABC3F5C000007D000753075307530753075306565050A050A000000005000000104010400D9009700A100CF008F9588829391848481848B50");
		$placeholderTrainer7[0]["pokemon2"] = hex2bin("7FAE3F42465C000007D00075307530753075307530746405190F0A0000000050000000F100F100FA00CD00B90085009D9282809180819194938450");
		$placeholderTrainer7[0]["pokemon3"] = hex2bin("D2032EF73F09000007D000753075307530753075307657140F050F00000000500000011E011E00F200A90077009200928691808D81948B8B505050");
		$placeholderTrainer7[0]["message_start"] = hex2bin("120E030101063D04");
		$placeholderTrainer7[0]["message_win"] = hex2bin("1805250D3D040405");
		$placeholderTrainer7[0]["message_lose"] = hex2bin("030D0703010D1504");
		
		return $placeholderTrainer7[$number];
	}
	
	function getBattleTowerPlaceholderTrainerFR8($number) {
		$placeholderTrainer8 = array();
		
		// Trainer 1
		$placeholderTrainer8[6] = array();		
		$placeholderTrainer8[6]["name"] = hex2bin("87948D93849150505050");
		$placeholderTrainer8[6]["class"] = hexdec("41");
		$placeholderTrainer8[6]["pokemon1"] = hex2bin("C552B9BFRC6D00000B1FA8EA60EA60D6D8EA60D6D8FFRD140A050A640000005A0000015D015D00CB011600CA00BC013A8D8E8293808B8850505050");
		$placeholderTrainer8[6]["pokemon2"] = hex2bin("95497E3FC83B00000B1FA8FFR8C350DAC0EA60EA60FFRD05050F05640000005A000001570157014200FC00E501070107839180828E8B8E92928450");
		$placeholderTrainer8[6]["pokemon3"] = hex2bin("79926955395E00000B1FA8EA60EA60EA60D6D8EA60DFDD140F0F0A640000005A00000121012100DA00EF011F010700EC929380918E929250505050");
		$placeholderTrainer8[6]["message_start"] = hex2bin("0603200404051E05");
		$placeholderTrainer8[6]["message_win"] = hex2bin("1904010519040105");
		$placeholderTrainer8[6]["message_lose"] = hex2bin("090D330617030605");
		
		// Trainer 2
		$placeholderTrainer8[5] = array();
		$placeholderTrainer8[5]["name"] = hex2bin("8A888B8B985050505050");
		$placeholderTrainer8[5]["class"] = hexdec("21");
		$placeholderTrainer8[5]["pokemon1"] = hex2bin("5B92993B39C400000B1FA8C350C350C350C350C350DBDF05050F0F640000005A0000010A010A00F8018E00CB00EA00A28291949293808191885050");
		$placeholderTrainer8[5]["pokemon2"] = hex2bin("A9AE11723FCA00000B1FA8C350C350C350C350C350FDCF231E0505640000005A00000145014500F300DD013600CF00E18D8E9293848D8584915050");
		$placeholderTrainer8[5]["pokemon3"] = hex2bin("E9495C5E69B600000B1FA8C350AFC8C350D6D8C350FFRD0A0A140A640000005A00000145014500DF00EF00BE010A00F88F8E9198868E8DF8505050");
		$placeholderTrainer8[5]["message_start"] = hex2bin("0202200D03012408");
		$placeholderTrainer8[5]["message_win"] = hex2bin("2408260305040105");
		$placeholderTrainer8[5]["message_lose"] = hex2bin("1703060324080105");
		
		// Trainer 3
		$placeholderTrainer8[4] = array();
		$placeholderTrainer8[4]["name"] = hex2bin("868E9491888E50505050");
		$placeholderTrainer8[4]["class"] = hexdec("17");
		$placeholderTrainer8[4]["pokemon1"] = hex2bin("E692E1393F3B00000B1FA8C350C350D6D8C350AFC8DFFR140F0505640000005A00000135013500F800FF00E600F800F887988F8E918E8850505050");
		$placeholderTrainer8[4]["pokemon2"] = hex2bin("F8493FF2599D00000B1FA8C350D6D8C350AFC8C350DFFR050F0A0A640000005A0000016201620141011700B900FA0103939891808D8E8288855050");
		$placeholderTrainer8[4]["pokemon3"] = hex2bin("83549C39555E00000B1FA8AFC8C350C350C350D6D8BFRF0A0F0F0A640000005A00000195019500E300DD00BB00ED00FF8B8E8A878B809292505050");
		$placeholderTrainer8[4]["message_start"] = hex2bin("360402031C0B0405");
		$placeholderTrainer8[4]["message_win"] = hex2bin("2A03200426050405");
		$placeholderTrainer8[4]["message_lose"] = hex2bin("06041C0602031C0B");
		
		// Trainer 4
		$placeholderTrainer8[3] = array();
		$placeholderTrainer8[3]["name"] = hex2bin("9282878C889393505050");
		$placeholderTrainer8[3]["class"] = hexdec("27");
		$placeholderTrainer8[3]["pokemon1"] = hex2bin("C4AE5EF7F1EA00000B1FA8D6D8C350C350D6D8C350DDFE0A0F0505640000005A00000126012600C200B9011A013900FA8C848D93808B8850505050");
		$placeholderTrainer8[3]["pokemon2"] = hex2bin("4449EEE97E5900000B1FA8D6D8D6D8C350D6D8C350DFRD050A050A640000005A000001510151013A00DD00B500C200E68C80828A8E868D84949150");
		$placeholderTrainer8[3]["pokemon3"] = hex2bin("8F6D7E39593F00000B1FA8AFC8C350D6D8C350C350FEFD050F0A05640000005A000001C701C7011700C7008700C20113918E8D858B849750505050");
		$placeholderTrainer8[3]["message_start"] = hex2bin("0202250304080105");
		$placeholderTrainer8[3]["message_win"] = hex2bin("0408330D06040105");
		$placeholderTrainer8[3]["message_lose"] = hex2bin("3C0704050F060305");
		
		// Trainer 5
		$placeholderTrainer8[2] = array();
		$placeholderTrainer8[2]["name"] = hex2bin("8C809193888D50505050");
		$placeholderTrainer8[2]["class"] = hexdec("16");
		$placeholderTrainer8[2]["pokemon1"] = hex2bin("3B54F135F59C00000B1FA8C350AFC8C350AFC8D6D8DFFR050F050A640000005A000001500150011200E100F7010600E2809182808D888D50505050");
		$placeholderTrainer8[2]["pokemon2"] = hex2bin("F2924CF1877E00000B1FA8C350AFC8C350C350C350BDFE0A050A05640000005A000001A801A8005A005F00B400D60142818B889292849850505050");
		$placeholderTrainer8[2]["pokemon3"] = hex2bin("E50335F2F14C00000B1FA8C3509C40C350C350C350DBFE0F0F050A640000005A00000135013500EB00A400FC011500DF878E948D838E8E8C505050");
		$placeholderTrainer8[2]["message_start"] = hex2bin("25033E040B06020D");
		$placeholderTrainer8[2]["message_win"] = hex2bin("2C0330040F0D1E06");
		$placeholderTrainer8[2]["message_lose"] = hex2bin("26033A0605040105");
		
		// Trainer 6
		$placeholderTrainer8[1] = array();
		$placeholderTrainer8[1]["name"] = hex2bin("8E8B8486505050505050");
		$placeholderTrainer8[1]["class"] = hexdec("2B");
		$placeholderTrainer8[1]["pokemon1"] = hex2bin("E349C9D35CD800000B1FA8C350C350C350C350C350EFF70A190A14FF0000005A00000117011700DF014D00CF008B00C18088918C94918450505050");
		$placeholderTrainer8[1]["pokemon2"] = hex2bin("D5925C23B6E300000B1FA8C350C350C350C350C350FEFE0A140A05640000005A000000CB00CB006301ED005A006101ED8280918093918E82505050");
		$placeholderTrainer8[1]["pokemon3"] = hex2bin("88543F35F72E00000B1FA8C350C350C350C350C350F7F7050F0F14640000005A000001250125013B00AF00C600EE01098F98918E8B885050505050");
		$placeholderTrainer8[1]["message_start"] = hex2bin("2C05010521052E05");
		$placeholderTrainer8[1]["message_win"] = hex2bin("2105020D27052E05");
		$placeholderTrainer8[1]["message_lose"] = hex2bin("2F05020D16050205");
		
		// Trainer 7
		$placeholderTrainer8[0] = array();
		$placeholderTrainer8[0]["name"] = hex2bin("8B8081948B8B84505050");
		$placeholderTrainer8[0]["class"] = hexdec("24");
		$placeholderTrainer8[0]["pokemon1"] = hex2bin("F192D059D52200000B1FA87530753075307530753047570A0A0F0F000000005A00000142014200C200F500E8008000B6848291848C849487505050");
		$placeholderTrainer8[0]["pokemon2"] = hex2bin("8068553FD55900000B1FA87530753075307530753065760F050F0A000000005A0000011C011C00EA00DF00FE007E00B4938094918E925050505050");
		$placeholderTrainer8[0]["pokemon3"] = hex2bin("59495CBCD5CA00000B1FA87530753075307530753054440A0A0F05000000005A00000156015600F100B9008C00A700E686918E9380838C8E919550");
		$placeholderTrainer8[0]["message_start"] = hex2bin("000D220D36040105");
		$placeholderTrainer8[0]["message_win"] = hex2bin("1E0E150431080105");
		$placeholderTrainer8[0]["message_lose"] = hex2bin("2005160E31040305");
		
		return $placeholderTrainer8[$number];
	}
	
	function getBattleTowerPlaceholderTrainerFR9($number) {
		$placeholderTrainer9 = array();
		
		// Trainer 1
		$placeholderTrainer9[6] = array();		
		$placeholderTrainer9[6]["name"] = hex2bin("8B809391948585845050");
		$placeholderTrainer9[6]["class"] = hexdec("24");
		$placeholderTrainer9[6]["pokemon1"] = hex2bin("E554F2352E9C00000F4240EA60EA60EA60EA60EA60FFRD0F0F140A64000000640000015B015B011400C0011C013800FC878E948D838E8E8C505050");
		$placeholderTrainer9[6]["pokemon2"] = hex2bin("4449EE593FE900000F4240EA60EA60EA60EA60EA60FFRF050A050A6400000064000001790179016400FC00CC00E2010A8C80828A8E868D84949150");
		$placeholderTrainer9[6]["pokemon3"] = hex2bin("E69239E19C5C00000F4240EA60EA60EA60EA60EA60DFFE0F140A0A64000000640000015D015D011A011E010A011C011C87988F8E918E8850505050");
		$placeholderTrainer9[6]["message_start"] = hex2bin("2505250307082004");
		$placeholderTrainer9[6]["message_win"] = hex2bin("33080D0E03010C0D");
		$placeholderTrainer9[6]["message_lose"] = hex2bin("320D04051C0E2D07");
		
		// Trainer 2
		$placeholderTrainer9[5] = array();
		$placeholderTrainer9[5]["name"] = hex2bin("85888287849150505050");
		$placeholderTrainer9[5]["class"] = hexdec("1E");
		$placeholderTrainer9[5]["pokemon1"] = hex2bin("8703552E56E700000F4240C350C350C3507530C350FDFE0F14140F640000006400000143014300DC00CE015201340116958E8B93808B8850505050");
		$placeholderTrainer9[5]["pokemon2"] = hex2bin("80523F59E75500000F4240C350C350C350C350C350FFRF050A0F0F640000006400000155015501220114013400AA00E6938094918E925050505050");
		$placeholderTrainer9[5]["pokemon3"] = hex2bin("3B9235F5E73F00000F4240D6D8C350C350D6D8C350DFRF0F050F056400000064000001760176013200F60119012200FA809182808D888D50505050");
		$placeholderTrainer9[5]["message_start"] = hex2bin("3E070105200D0301");
		$placeholderTrainer9[5]["message_win"] = hex2bin("1E0E1E0803010105");
		$placeholderTrainer9[5]["message_lose"] = hex2bin("3204160E39060105");
		
		// Trainer 3
		$placeholderTrainer9[4] = array();
		$placeholderTrainer9[4]["name"] = hex2bin("8F808B94505050505050");
		$placeholderTrainer9[4]["class"] = hexdec("14");
		$placeholderTrainer9[4]["pokemon1"] = hex2bin("068C3559A31300000F4240C350C350D6D8D6D8D6D8FEDF0F0A140F6400000064000001570157010200F70121013701078391808280948584945050");
		$placeholderTrainer9[4]["pokemon2"] = hex2bin("6503565599F300000F4240AFC8C350C350AFC8AFC8FBEF140F0514640000006400000135013500BE00FR016E00F800F8848B848293918E83845050");
		$placeholderTrainer9[4]["pokemon3"] = hex2bin("706D39593F9D00000F4240D6D8C350D6D8C350AFC8FFRF0F0A050A6400000064000001940194015E014900A800B200B29187888D8E8584918E9250");
		$placeholderTrainer9[4]["message_start"] = hex2bin("2503250D2C08020D");
		$placeholderTrainer9[4]["message_win"] = hex2bin("390D300D0A063B06");
		$placeholderTrainer9[4]["message_lose"] = hex2bin("390D300D0A061D0A");
		
		// Trainer 4
		$placeholderTrainer9[3] = array();
		$placeholderTrainer9[3]["name"] = hex2bin("858B8494919850505050");
		$placeholderTrainer9[3]["class"] = hexdec("29");
		$placeholderTrainer9[3]["pokemon1"] = hex2bin("D092593FCFF200000F4240C350C350D6D8EA60C350FDFR0A050F0F6400000064000001570157010401E9009800C600DA929384848B889750505050");
		$placeholderTrainer9[3]["pokemon2"] = hex2bin("165241D33FBD00000F4240D6D8C350C350D6D8C350FDCF1419050A6400000064000001440144010E00D8011F00D400D491808F809283848F888250");
		$placeholderTrainer9[3]["pokemon3"] = hex2bin("C877C3D4DCF700000F4240AFC8C350D6D8C350D6D8BFRF0505140F640000006400000135013500CA00D1010201070107858494858E918495845050");
		$placeholderTrainer9[3]["message_start"] = hex2bin("1802110B17030405");
		$placeholderTrainer9[3]["message_win"] = hex2bin("030D1C06080B0405");
		$placeholderTrainer9[3]["message_lose"] = hex2bin("1005020D1005020D");
		
		// Trainer 5
		$placeholderTrainer9[2] = array();
		$placeholderTrainer9[2]["name"] = hex2bin("8B80809192848D505050");
		$placeholderTrainer9[2]["class"] = hexdec("27");
		$placeholderTrainer9[2]["pokemon1"] = hex2bin("D78CA33B8AB900000F4240C350C350BB80AFC8C350FFRF14050F1464000000640000012D012D011800C3013C00A000F08580918594918493505050");
		$placeholderTrainer9[2]["pokemon2"] = hex2bin("D449D33FA35C00000F4240C350C350C350C350AFC8FBFE1905140A64000000640000014D014D015E011A00DC00C400F682889980988E9750505050");
		$placeholderTrainer9[2]["pokemon3"] = hex2bin("F292553B7E8700000F4240C3509C40C35075307530DDFE0F05050A6400000064000002BF02BF0065006A00BC00E2015A818B889292849850505050");
		$placeholderTrainer9[2]["message_start"] = hex2bin("1C06340D060D3F08");
		$placeholderTrainer9[2]["message_win"] = hex2bin("0A0619041C063107");
		$placeholderTrainer9[2]["message_lose"] = hex2bin("18051C0E4004020D");
		
		// Trainer 6
		$placeholderTrainer9[1] = array();
		$placeholderTrainer9[1]["name"] = hex2bin("808B8883849350505050");
		$placeholderTrainer9[1]["class"] = hexdec("2D");
		$placeholderTrainer9[1]["pokemon1"] = hex2bin("DD549C3B3F5900000F4240C350C350C350C350C350FEF70A05050A6400000064000001830183012200F800BE00C200C2828E828788868D8E8D5050");
		$placeholderTrainer9[1]["pokemon2"] = hex2bin("67495E5C99CA00000F4240C350C350C350C350C350FEFE0A0A050564000000640000017701770118010200C8015200DA849784868694938E915050");
		$placeholderTrainer9[1]["pokemon3"] = hex2bin("8B9239F63B5C00000F4240C350C350C350C350C350FBE70F05050A64000000640000014B014B00D2014C00C6013000D6808C8E8D88929380915050");
		$placeholderTrainer9[1]["message_start"] = hex2bin("2503290622060604");
		$placeholderTrainer9[1]["message_win"] = hex2bin("220612030A061904");
		$placeholderTrainer9[1]["message_lose"] = hex2bin("2203220D2906020D");
		
		// Trainer 7
		$placeholderTrainer9[0] = array();
		$placeholderTrainer9[0]["name"] = hex2bin("99809580938050505050");
		$placeholderTrainer9[0]["class"] = hexdec("30");
		$placeholderTrainer9[0]["pokemon1"] = hex2bin("4C0399599D7E00000F4240753075307530753075307446050A0A050000000064000001490149011A013C009200AA00BE86918E8B848C5050505050");
		$placeholderTrainer9[0]["pokemon2"] = hex2bin("6B774407090800000F4240753075307530753075306776140F0F0F0000000064000001090109010E00DC00D6008201189398868D8E8D5050505050");
		$placeholderTrainer9[0]["pokemon3"] = hex2bin("AB4939F0C06D00000F42407530753075307530753076570F05050A0000000064000001A901A900B200B000C000D600D68B808D9394918D50505050");
		$placeholderTrainer9[0]["message_start"] = hex2bin("0B02200D03010405");
		$placeholderTrainer9[0]["message_win"] = hex2bin("160E06063D040105");
		$placeholderTrainer9[0]["message_lose"] = hex2bin("32041C0E39063304");
		
		return $placeholderTrainer9[$number];
	}
	function getBattleTowerPlaceholderTrainerDE($number) {
		$placeholderTrainer0 = array();
		
		// Trainer 1
		$placeholderTrainer0[6] = array();
		$placeholderTrainer0[6]["name"] = hex2bin("81808287505050505050");
		$placeholderTrainer0[6]["class"] = hexdec("25");
		$placeholderTrainer0[6]["pokemon1"] = hex2bin("876D553FF72E00000003E8C3509C409C4088B89C40DDBD0F050F14640000000A0000002900290019001800250022001F818B889399805050505050");
		$placeholderTrainer0[6]["pokemon2"] = hex2bin("C492BD5EF45C00000003E89C40C35088B89C409C40EDFB0A0A0A0A640000000A000000270027001A001800230026001F8F9288808D805050505050");
		$placeholderTrainer0[6]["pokemon3"] = hex2bin("C5AEF7E7F45C00000003E89C409C40AFC8C3509C40DBEF0F0F0A0A640000000A0000002E002E00190022001A001900278D80828793809180505050");
		$placeholderTrainer0[6]["message_start"] = hex2bin("01030E0929033004");
		$placeholderTrainer0[6]["message_win"] = hex2bin("1A0B3004120D2408");
		$placeholderTrainer0[6]["message_lose"] = hex2bin("370324080B060605");
		
		// Trainer 2
		$placeholderTrainer0[5] = array();
		$placeholderTrainer0[5]["name"] = hex2bin("87848C8C505050505050");
		$placeholderTrainer0[5]["class"] = hexdec("1E");
		$placeholderTrainer0[5]["pokemon1"] = hex2bin("CA7744F3DBC200000003E8C350C350C350C350C3507FD714141905640000000A00000042004200120019001300120017968E818194858584935050");
		$placeholderTrainer0[5]["pokemon2"] = hex2bin("736DB33F59D500000003E89C4075309C4075307530EFCF0F050A0F640000000A0000002F002F001F001D001D0014001C8A808D86808C8050505050");
		$placeholderTrainer0[5]["pokemon3"] = hex2bin("DE8C395E69F600000003E89C407530821475307530FEFD0F0A1405640000000A0000002600260017001D00130018001C828E9180928E8D8D505050");
		$placeholderTrainer0[5]["message_start"] = hex2bin("2403CA000B0D290B");
		$placeholderTrainer0[5]["message_win"] = hex2bin("CA003208050C0504");
		$placeholderTrainer0[5]["message_lose"] = hex2bin("CA0044032F080507");
		
		// Trainer 3
		$placeholderTrainer0[4] = array();
		$placeholderTrainer0[4]["name"] = hex2bin("838E8B808D5050505050");
		$placeholderTrainer0[4]["class"] = hexdec("2B");
		$placeholderTrainer0[4]["pokemon1"] = hex2bin("F1AE3B593F5C00000003E8753075307530753088B8BBDF050A050A640000000A0000002E002E001B0020001F0014001A8C888B93808D8A50505050");
		$placeholderTrainer0[4]["pokemon2"] = hex2bin("8E923F30592C00000003E875307530753075307530DBFB05140A19640000000A0000002B002B0020001800260017001A8084918E83808293988B50");
		$placeholderTrainer0[4]["pokemon3"] = hex2bin("836D3B39555E00000003E875307530753075307530FDEB050F0F0A640000000A000000340034001D001B0018001C001E8B808F9180925050505050");
		$placeholderTrainer0[4]["message_start"] = hex2bin("0603290604050202");
		$placeholderTrainer0[4]["message_win"] = hex2bin("160E08062E0D3F08");
		$placeholderTrainer0[4]["message_lose"] = hex2bin("0602050C3504020D");
		
		// Trainer 4
		$placeholderTrainer0[3] = array();
		$placeholderTrainer0[3]["name"] = hex2bin("8D8E81848B5050505050");
		$placeholderTrainer0[3]["class"] = hexdec("14");
		$placeholderTrainer0[3]["pokemon1"] = hex2bin("D7AEA3B9393B00000003E8753088B8753075307530FBBF14140F05640000000A000000260026001F001600220013001B928D888481848B50505050");
		$placeholderTrainer0[3]["pokemon2"] = hex2bin("E9035E3B3FA100000003E8753075309C4075307530FBDE0A05050A640000000A0000002C002C001C001E00170021001F8F8E9198868E8DF8505050");
		$placeholderTrainer0[3]["pokemon3"] = hex2bin("C877C3D4DCF700000003E875307530753075307530EFDF0505140F640000000A00000025002500180018001C001D001D939180948D859486888B50");
		$placeholderTrainer0[3]["message_start"] = hex2bin("2603050C13060A09");
		$placeholderTrainer0[3]["message_win"] = hex2bin("380D2C04120D0A09");
		$placeholderTrainer0[3]["message_lose"] = hex2bin("2E08350B2A0B120C");
		
		// Trainer 5
		$placeholderTrainer0[2] = array();
		$placeholderTrainer0[2]["name"] = hex2bin("8F848F8F848B50505050");
		$placeholderTrainer0[2]["class"] = hexdec("3B");
		$placeholderTrainer0[2]["pokemon1"] = hex2bin("E4AEB94C2EF100000003E875307530753080E87530FDFE140A1405640000000A000000240024001800110019001C001687948D8394929384915050");
		$placeholderTrainer0[2]["pokemon2"] = hex2bin("CB523CBDF76100000003E875307530753075307530EDFD140A0F1E640000000A000000270027001C0018001D001D00188688918085809188865050");
		$placeholderTrainer0[2]["pokemon3"] = hex2bin("F2491D4CCD4600000003E87D009C40753075307530DFCE0F0A140F640000000A0000004D004D000E000E0016001B0027818B889292849850505050");
		$placeholderTrainer0[2]["message_start"] = hex2bin("110E32087B003F08");
		$placeholderTrainer0[2]["message_win"] = hex2bin("200D08069B000301");
		$placeholderTrainer0[2]["message_lose"] = hex2bin("160ECE0009062103");
		
		// Trainer 6
		$placeholderTrainer0[1] = array();
		$placeholderTrainer0[1]["name"] = hex2bin("968488828A8491935050");
		$placeholderTrainer0[1]["class"] = hexdec("19");
		$placeholderTrainer0[1]["pokemon1"] = hex2bin("8F6D1DB6AD3900000003E875307530753075307530EFF70F0A0F0F640000000A0000003900390022001900120017002091848B80978E5050505050");
		$placeholderTrainer0[1]["pokemon2"] = hex2bin("67525CCAA85D00000003E875307530753075307530FEFE0A050A19640000000A0000002D002D001F001D001700250019849784868694938E915050");
		$placeholderTrainer0[1]["pokemon3"] = hex2bin("D6AEB3CB44F900000003E875307530753075307530F7F70F0A140F640000000A0000002B002B00250019001D0012001D8784918082918E92925050");
		$placeholderTrainer0[1]["message_start"] = hex2bin("2503040325040202");
		$placeholderTrainer0[1]["message_win"] = hex2bin("2905160E210D0C09");
		$placeholderTrainer0[1]["message_lose"] = hex2bin("1E0E010506020305");
		
		// Trainer 7
		$placeholderTrainer0[0] = array();
		$placeholderTrainer0[0]["name"] = hex2bin("81808384915050505050");
		$placeholderTrainer0[0]["class"] = hexdec("16");
		$placeholderTrainer0[0]["pokemon1"] = hex2bin("C9ADED00000000000003E875307530753075307530FFFF0F000000000000000A000000240024001A00150015001A001588828E868D88938E505050");
		$placeholderTrainer0[0]["pokemon2"] = hex2bin("80521DCF27C400000003E87530753075307530753065570F0F1E0F000000000A000000280028001E001D002000120018938094918E925050505050");
		$placeholderTrainer0[0]["pokemon3"] = hex2bin("7A495CF4071D00000003E87530753075307530753073670A0A0F0F000000000A00000022002200130016001C001E00228F808D93888C8E92505050");
		$placeholderTrainer0[0]["message_start"] = hex2bin("0C020D0D2D04240D");
		$placeholderTrainer0[0]["message_win"] = hex2bin("00022A0D0303120C");
		$placeholderTrainer0[0]["message_lose"] = hex2bin("0002060308062408");
		
		return $placeholderTrainer0[$number];
	}
	
	function getBattleTowerPlaceholderTrainerDE1($number) {
		$placeholderTrainer1 = array();
		
		// Trainer 1
		$placeholderTrainer1[6] = array();
		$placeholderTrainer1[6]["name"] = hex2bin("96848184915050505050");
		$placeholderTrainer1[6]["class"] = hexdec("2C");
		$placeholderTrainer1[6]["pokemon1"] = hex2bin("C592B65CBDD50000001F40C350C350C350C350C350CFBC0A0A0A0F6400000014000000510051002E0042002E002C00488D80828793809180505050");
		$placeholderTrainer1[6]["pokemon2"] = hex2bin("79AE695E39F40000001F40C350C350C350C350C350DBDB140A0F0A6400000014000000470047003300360043003C0036929380918C888450505050");
		$placeholderTrainer1[6]["pokemon3"] = hex2bin("826D3F52557E0000001F40C350C350C350C350C350FAFD050A0F056400000014000000530053004800330036002D003D86809180838E9250505050");
		$placeholderTrainer1[6]["message_start"] = hex2bin("0C020D0D2F062D04");
		$placeholderTrainer1[6]["message_win"] = hex2bin("1D05010507033504");
		$placeholderTrainer1[6]["message_lose"] = hex2bin("1D05010509040F0C");
		
		// Trainer 2
		$placeholderTrainer1[5] = array();
		$placeholderTrainer1[5]["name"] = hex2bin("8E8184918B8450505050");
		$placeholderTrainer1[5]["class"] = hexdec("22");
		$placeholderTrainer1[5]["pokemon1"] = hex2bin("D0AE2EE7CF590000001F40C350AFC8C3507530C350FFFF140F0F0A64000000140000004D004D00370066001F002C0030929384848B889750505050");
		$placeholderTrainer1[5]["pokemon2"] = hex2bin("418B5EF45C090000001F40C350C3507530C3509C40FDEF0A0A0A0F6400000014000000440044002A00240045004B003792888C92808B8050505050");
		$placeholderTrainer1[5]["pokemon3"] = hex2bin("3B03352E3FE70000001F4088B8AFC8C350D6D8C350DBFB0F14050F640000001400000051005100400034003C003C003480918A808D885050505050");
		$placeholderTrainer1[5]["message_start"] = hex2bin("110E0B0902070A0B");
		$placeholderTrainer1[5]["message_win"] = hex2bin("0C0E1509100D0807");
		$placeholderTrainer1[5]["message_lose"] = hex2bin("0603200D2604030D");
		
		// Trainer 3
		$placeholderTrainer1[4] = array();
		$placeholderTrainer1[4]["name"] = hex2bin("848D8391849250505050");
		$placeholderTrainer1[4]["class"] = hexdec("3B");
		$placeholderTrainer1[4]["pokemon1"] = hex2bin("D677CBB3E0590000001F40C3507530AFC87530AFC8DFDE0A0F0A0A64000000140000004E004E0044003300340025003B8784918082918E92925050");
		$placeholderTrainer1[4]["pokemon2"] = hex2bin("67923F5E5C8A0000001F40AFC8C350C350AFC8AFC8FDEB050A0A0F6400000014000000530053003C0037002B0046002E849784868694938E915050");
		$placeholderTrainer1[4]["pokemon3"] = hex2bin("8EAE9C3F59520000001F40AFC8C3509C40C350AFC8FBBB0A050A0A64000000140000004E004E0040002D0048002C00328084918E83808293988B50");
		$placeholderTrainer1[4]["message_start"] = hex2bin("220C2D040B0D3E07");
		$placeholderTrainer1[4]["message_win"] = hex2bin("1203430B04052905");
		$placeholderTrainer1[4]["message_lose"] = hex2bin("0F0506050603300D");
		
		// Trainer 4
		$placeholderTrainer1[3] = array();
		$placeholderTrainer1[3]["name"] = hex2bin("87888B83505050505050");
		$placeholderTrainer1[3]["class"] = hexdec("3C");
		$placeholderTrainer1[3]["pokemon1"] = hex2bin("F2035E4287440000001F40C350C35075307530C350BDFE0A190A1464000000140000009400940018001600290033004B818B889292849850505050");
		$placeholderTrainer1[3]["pokemon2"] = hex2bin("83AE5E553B6D0000001F40D6D875309C40D6D87530FED70A0F050A640000001400000062006200350034002D003200368B808F9180925050505050");
		$placeholderTrainer1[3]["pokemon3"] = hex2bin("19A35556465C0000001F40AFC8C350AFC8C350C350FCFE0F140F0A64000000140000003A003A002C0020003A002900258F888A8082879450505050");
		$placeholderTrainer1[3]["message_start"] = hex2bin("2B050105180C0402");
		$placeholderTrainer1[3]["message_win"] = hex2bin("4105160E0E053F08");
		$placeholderTrainer1[3]["message_lose"] = hex2bin("060321042A0D1203");
		
		// Trainer 5
		$placeholderTrainer1[2] = array();
		$placeholderTrainer1[2]["name"] = hex2bin("96888D83505050505050");
		$placeholderTrainer1[2]["class"] = hexdec("3A");
		$placeholderTrainer1[2]["pokemon1"] = hex2bin("D477D3A35CC90000001F409C40AFC89C40AFC8C350FDFE19140A0A64000000140000004900490049003C002F002B003592828784918E9750505050");
		$placeholderTrainer1[2]["pokemon2"] = hex2bin("6BAE090807050000001F40C350AFC888B8C3507530FBFD0F0F0F146400000014000000430043003F003200340020003E8D8E828A8287808D505050");
		$placeholderTrainer1[2]["pokemon3"] = hex2bin("800355593F3B0000001F40C3509C40C35075307530FBEF0F0A050564000000140000004C004C003D003A003F0023002F938094918E925050505050");
		$placeholderTrainer1[2]["message_start"] = hex2bin("200D280404050202");
		$placeholderTrainer1[2]["message_win"] = hex2bin("080901050E023004");
		$placeholderTrainer1[2]["message_lose"] = hex2bin("350525031C070105");
		
		// Trainer 6
		$placeholderTrainer1[1] = array();
		$placeholderTrainer1[1]["name"] = hex2bin("859184948D8350505050");
		$placeholderTrainer1[1]["class"] = hexdec("35");
		$placeholderTrainer1[1]["pokemon1"] = hex2bin("B85F393BD5F00000001F409C409C409C409C409C40EDF70F050F056400000014000000520052002800340029002500318099948C8091888B8B5050");
		$placeholderTrainer1[1]["pokemon2"] = hex2bin("F1525957D5390000001F409C409C409C409C409C40DFFE0A0A0F0F64000000140000005300530034003F003D002400308C888B93808D8A50505050");
		$placeholderTrainer1[1]["pokemon3"] = hex2bin("28AE3F3B7ED50000001F409C409C409C409C409C40C7FE0505050F6400000014000000620062002F0023002700320028968886868B989394858550");
		$placeholderTrainer1[1]["message_start"] = hex2bin("29033004120D1908");
		$placeholderTrainer1[1]["message_win"] = hex2bin("3703250D19080C0D");
		$placeholderTrainer1[1]["message_lose"] = hex2bin("0902160E3A08020D");
		
		// Trainer 7
		$placeholderTrainer1[0] = array();
		$placeholderTrainer1[0]["name"] = hex2bin("87808C8C849150505050");
		$placeholderTrainer1[0]["class"] = hexdec("2D");
		$placeholderTrainer1[0]["pokemon1"] = hex2bin("28685ECFF41D0000001F4075307530753075307530C7770A0F0A0F0000000014000000610061002E00220022002E0024968886868B989394858550");
		$placeholderTrainer1[0]["pokemon2"] = hex2bin("22AD3B5939090000001F40753075307530753075305646050A0F0F00000000140000004A004A0034002E00310032002E8D88838E8A888D86505050");
		$placeholderTrainer1[0]["pokemon3"] = hex2bin("C349855939F00000001F40753075307530753075305547140A0F05000000001400000051005100310031001D002A002A8C8E918B8E918350505050");
		$placeholderTrainer1[0]["message_start"] = hex2bin("03033004120D1808");
		$placeholderTrainer1[0]["message_win"] = hex2bin("3D05010519040C04");
		$placeholderTrainer1[0]["message_lose"] = hex2bin("0B0D110E04033106");
		
		return $placeholderTrainer1[$number];
	}
	
	function getBattleTowerPlaceholderTrainerDE2($number) {
		$placeholderTrainer2 = array();
		
		// Trainer 1
		$placeholderTrainer2[6] = array();
		$placeholderTrainer2[6]["name"] = hex2bin("8A94878D505050505050");
		$placeholderTrainer2[6]["class"] = hexdec("31");
		$placeholderTrainer2[6]["pokemon1"] = hex2bin("876D55562EBD0000006978C350C350C350C350D6D8DBED0F14140A640000001E00000067006700440040006B00600057818B889399805050505050");
		$placeholderTrainer2[6]["pokemon2"] = hex2bin("3E0368395A420000006978C350D6D8D6D8D6D8C350DDFB0F0F0519640000001E000000770077005100570049004600529094808F8F8E5050505050");
		$placeholderTrainer2[6]["pokemon3"] = hex2bin("7992565E69390000006978C350C350C350C350C350FFFF140A140F640000001E000000650065004B00510063005A0051929380918C888450505050");
		$placeholderTrainer2[6]["message_start"] = hex2bin("1B02200D25040202");
		$placeholderTrainer2[6]["message_win"] = hex2bin("13050603170D3104");
		$placeholderTrainer2[6]["message_lose"] = hex2bin("0F050605160E3008");
		
		// Trainer 2
		$placeholderTrainer2[5] = array();
		$placeholderTrainer2[5]["name"] = hex2bin("89C08684915050505050");
		$placeholderTrainer2[5]["class"] = hexdec("3E");
		$placeholderTrainer2[5]["pokemon1"] = hex2bin("7CAE3B8E8AD50000006978C350C350C3507530C350FBEE050A0F0F640000001E000000660066003C0031005300620056918E9292808D8050505050");
		$placeholderTrainer2[5]["pokemon2"] = hex2bin("335259BCA3BD0000006978C350C3507530C350C350EFFF0A0A140A640000001E000000510051004D00380066003C00488388868391885050505050");
		$placeholderTrainer2[5]["pokemon3"] = hex2bin("B603CAF14C680000006978AFC8AFC8C350D6D8C350DFDB05050A0F640000001E0000006D006D004C0051003C0052005881848B8B8E92928E8C5050");
		$placeholderTrainer2[5]["message_start"] = hex2bin("1A0B3004120D1908");
		$placeholderTrainer2[5]["message_win"] = hex2bin("120D3F03170D1908");
		$placeholderTrainer2[5]["message_lose"] = hex2bin("1B0506030E053608");
		
		// Trainer 3
		$placeholderTrainer2[4] = array();
		$placeholderTrainer2[4]["name"] = hex2bin("81849186505050505050");
		$placeholderTrainer2[4]["class"] = hexdec("30");
		$placeholderTrainer2[4]["pokemon1"] = hex2bin("F2925C7387B60000006978C3507530AFC87530AFC8FBED0A140A0A640000001E000000D900D900200021003B0049006D818B889292849850505050");
		$placeholderTrainer2[4]["pokemon2"] = hex2bin("E58A35F2F78A0000006978AFC8C350C350AFC8AFC8FDED0F0F0F0F640000001E0000006C006C0054003B0056005E004C878E948D838E8E8C505050");
		$placeholderTrainer2[4]["pokemon3"] = hex2bin("446DEE08597E0000006978AFC8C3509C40C350AFC8FDBE050F0A05640000001E000000760076006C004B003D004400508C8082878E8C8488505050");
		$placeholderTrainer2[4]["message_start"] = hex2bin("240343040B0D070E");
		$placeholderTrainer2[4]["message_win"] = hex2bin("3105060508090105");
		$placeholderTrainer2[4]["message_lose"] = hex2bin("0F0525032E0D3E04");
		
		// Trainer 4
		$placeholderTrainer2[3] = array();
		$placeholderTrainer2[3]["name"] = hex2bin("8594BE50505050505050");
		$placeholderTrainer2[3]["class"] = hexdec("27");
		$placeholderTrainer2[3]["pokemon1"] = hex2bin("A9AED56D5C110000006978C350C35075307530C350EFDC0F0A0A23640000001E0000006F006F0053004A00670046004C888A928180935050505050");
		$placeholderTrainer2[3]["pokemon2"] = hex2bin("E9035E693FA10000006978D6D875309C40D6D87530DFDB0A14050A640000001E000000750075004900530042005700518F8E9198868E8DF8505050");
		$placeholderTrainer2[3]["pokemon3"] = hex2bin("697659D83F9B0000006978AFC8C350AFC8C3507530DDEB0A14050AFF0000001E000000630063004D005E0038003600488A8D8E8686805050505050");
		$placeholderTrainer2[3]["message_start"] = hex2bin("03033004120D3607");
		$placeholderTrainer2[3]["message_win"] = hex2bin("070D030330040405");
		$placeholderTrainer2[3]["message_lose"] = hex2bin("020D010310083F03");
		
		// Trainer 5
		$placeholderTrainer2[2] = array();
		$placeholderTrainer2[2]["name"] = hex2bin("86918EBE505050505050");
		$placeholderTrainer2[2]["class"] = hexdec("26");
		$placeholderTrainer2[2]["pokemon1"] = hex2bin("65037155B65700000069789C40AFC89C40AFC8C350BDEF1E0F0A0A640000001E000000620062003900450071004E004E8B848A93918E81808B5050");
		$placeholderTrainer2[2]["pokemon2"] = hex2bin("8392F037C4460000006978C350AFC888B8C3507530FDEB05190F0F640000001E0000008E008E0050004A0041004B00518B808F9180925050505050");
		$placeholderTrainer2[2]["pokemon3"] = hex2bin("ABAEF05739AF0000006978C3509C40C35075307530DDEB050A0F0F640000001E0000008B008B003E00400042004600468B808D9394918D50505050");
		$placeholderTrainer2[2]["message_start"] = hex2bin("010306061A0B4404");
		$placeholderTrainer2[2]["message_win"] = hex2bin("1A0B44040B0D3B04");
		$placeholderTrainer2[2]["message_lose"] = hex2bin("1A0B44043604170D");
		
		// Trainer 6
		$placeholderTrainer2[1] = array();
		$placeholderTrainer2[1]["name"] = hex2bin("8C808884915050505050");
		$placeholderTrainer2[1]["class"] = hexdec("21");
		$placeholderTrainer2[1]["pokemon1"] = hex2bin("C46D5D815CF40000006978AFC8C350C350C350C350EFF719140A0A640000001E000000630063004400420060006700528F9288808D805050505050");
		$placeholderTrainer2[1]["pokemon2"] = hex2bin("4952235CBC3D0000006978C350AFC8C350B798AFC8FEFE140A0A14640000001E0000006E006E00470044005A004D006593848D938E978050505050");
		$placeholderTrainer2[1]["pokemon3"] = hex2bin("5EAEA87A65CA0000006978C350AFC8C350C350C350F7F70A1E0F05640000001E0000006500650044003D00600067004686848D8680915050505050");
		$placeholderTrainer2[1]["message_start"] = hex2bin("200D030104050402");
		$placeholderTrainer2[1]["message_win"] = hex2bin("0C05060337063F04");
		$placeholderTrainer2[1]["message_lose"] = hex2bin("0F0D020334070405");
		
		// Trainer 7
		$placeholderTrainer2[0] = array();
		$placeholderTrainer2[0]["name"] = hex2bin("8184828A849150505050");
		$placeholderTrainer2[0]["class"] = hexdec("36");
		$placeholderTrainer2[0]["pokemon1"] = hex2bin("D9AE1DB62E2B00000069787530753075307530753077450F0A141E000000001E000000720072006400430035004100419491928091888D86505050");
		$placeholderTrainer2[0]["pokemon2"] = hex2bin("160377E44081000000697875307530753075307530677714142314000000001E000000600060004B003D0052003A003A88818893808A5050505050");
		$placeholderTrainer2[0]["pokemon3"] = hex2bin("396D4302B374000000697875307530753075307530776714190F1E000000001E0000006300630055003A004E003A00409180928085855050505050");
		$placeholderTrainer2[0]["message_start"] = hex2bin("2503200D24082804");
		$placeholderTrainer2[0]["message_win"] = hex2bin("25033F0821040105");
		$placeholderTrainer2[0]["message_lose"] = hex2bin("010344030A040305");
		
		return $placeholderTrainer2[$number];
	}
	
	function getBattleTowerPlaceholderTrainerDE3($number) {
		$placeholderTrainer3 = array();
		
		// Trainer 1
		$placeholderTrainer3[6] = array();
		$placeholderTrainer3[6]["name"] = hex2bin("93878E8C92848D505050");
		$placeholderTrainer3[6]["class"] = hexdec("20");
		$placeholderTrainer3[6]["pokemon1"] = hex2bin("80AED83F59E7000000FA00C350C350C350C350C350FDFE14050A0FFF000000280000008F008F00770071007F0046005E938094918E925050505050");
		$placeholderTrainer3[6]["pokemon2"] = hex2bin("E69239E13F3B000000FA00C350C350C350C350C350FDEF0F14050564000000280000008E008E00730071006A007300739284848391808A888D8650");
		$placeholderTrainer3[6]["pokemon3"] = hex2bin("8F49D522F459000000FA00C350C350C350C350C350EDDD0F0F0A0A6400000028000000CD00CD007E0059003D0059007D91848B80978E5050505050");
		$placeholderTrainer3[6]["message_start"] = hex2bin("1203430B04053506");
		$placeholderTrainer3[6]["message_win"] = hex2bin("04030B09400B0405");
		$placeholderTrainer3[6]["message_lose"] = hex2bin("0703130615040105");
		
		// Trainer 2
		$placeholderTrainer3[5] = array();
		$placeholderTrainer3[5]["name"] = hex2bin("8A9180828A5050505050");
		$placeholderTrainer3[5]["class"] = hexdec("1D");
		$placeholderTrainer3[5]["pokemon1"] = hex2bin("8392553A6D39000000FA00C350C350C350C350C350FDEB0F0A0A0F6400000028000000BA00BA006B006500560067006F8B808F9180925050505050");
		$placeholderTrainer3[5]["pokemon2"] = hex2bin("D0AEC9E7595C000000FA00C350C350C350C350C350EFDB0A0F0A0A6400000028000000890089006A00C7003D004F0057929384848B889750505050");
		$placeholderTrainer3[5]["pokemon3"] = hex2bin("41525E096907000000FA00C350AFC8C350D6D8C350DDEF0A0F140F64000000280000007E007E004C004900870093006B92888C92808B8050505050");
		$placeholderTrainer3[5]["message_start"] = hex2bin("0C0E030132080008");
		$placeholderTrainer3[5]["message_win"] = hex2bin("2003120D1F07020D");
		$placeholderTrainer3[5]["message_lose"] = hex2bin("210401032306010B");
		
		// Trainer 3
		$placeholderTrainer3[4] = array();
		$placeholderTrainer3[4]["name"] = hex2bin("91848C8C849193505050");
		$placeholderTrainer3[4]["class"] = hexdec("29");
		$placeholderTrainer3[4]["pokemon1"] = hex2bin("79923B55395E000000FA00C350C350AFC8C350AFC8FDBE050F0F0A640000002800000083008300630068007F00750069929380918C888450505050");
		$placeholderTrainer3[4]["pokemon2"] = hex2bin("CAAE44F3DBC2000000FA00AFC8C350C350C350C350BFE7141419056400000028000000E900E9003E00550040003B004F968E818194858584935050");
		$placeholderTrainer3[4]["pokemon3"] = hex2bin("4C779959059D000000FA00AFC8C3509C40C350AFC8DDED050A140A6400000028000000910091007D008B004A0050005886848E9680995050505050");
		$placeholderTrainer3[4]["message_start"] = hex2bin("0605060503010405");
		$placeholderTrainer3[4]["message_win"] = hex2bin("06051E0D07040405");
		$placeholderTrainer3[4]["message_lose"] = hex2bin("06051E0D35040405");
		
		// Trainer 4
		$placeholderTrainer3[3] = array();
		$placeholderTrainer3[3]["name"] = hex2bin("81849284915050505050");
		$placeholderTrainer3[3]["class"] = hexdec("32");
		$placeholderTrainer3[3]["pokemon1"] = hex2bin("D48CA3D3E43F000000FA00C350C350C3509C40C350BDFE1419140564000000280000008B008B008B007500590052006692828784918E9750505050");
		$placeholderTrainer3[3]["pokemon2"] = hex2bin("3352593FBCBD000000FA00AFC8C350C350C350C350FEBB0A050A0A64000000280000006C006C0067004E0083004B005B8388868391885050505050");
		$placeholderTrainer3[3]["pokemon3"] = hex2bin("506D395E593B000000FA00AFC8C350AFC8C350C350BFCF0F0A0A0564000000280000009D009D005F007E003C007700678B80878C94925050505050");
		$placeholderTrainer3[3]["message_start"] = hex2bin("2905200D1F040105");
		$placeholderTrainer3[3]["message_win"] = hex2bin("41052E0D3F080105");
		$placeholderTrainer3[3]["message_lose"] = hex2bin("1205050525033D08");
		
		// Trainer 5
		$placeholderTrainer3[2] = array();
		$placeholderTrainer3[2]["name"] = hex2bin("81809484915050505050");
		$placeholderTrainer3[2]["class"] = hexdec("1C");
		$placeholderTrainer3[2]["pokemon1"] = hex2bin("E900B0A03CA8000000FA00C350AFC8C350C350C350BCEF1E1E140A64000000280000009300930063006C0056007B00738F8E9198868E8DF8505050");
		$placeholderTrainer3[2]["pokemon2"] = hex2bin("3B8AAC2B222E000000FA00C350C350C350C350C350FEBB191E0F146400000028000000980098007F0066006F0073006380918A808D885050505050");
		$placeholderTrainer3[2]["pokemon3"] = hex2bin("CD92E5B65CC9000000FA00C350C350C350C350C350FA7F280A0A0A64000000280000008C008C006F0093004000570057858E919184939184929250");
		$placeholderTrainer3[2]["message_start"] = hex2bin("11042503210D2704");
		$placeholderTrainer3[2]["message_win"] = hex2bin("060313062E0D3E04");
		$placeholderTrainer3[2]["message_lose"] = hex2bin("0C04010506032104");
		
		// Trainer 6
		$placeholderTrainer3[1] = array();
		$placeholderTrainer3[1]["name"] = hex2bin("878E858C808D8D505050");
		$placeholderTrainer3[1]["class"] = hexdec("41");
		$placeholderTrainer3[1]["pokemon1"] = hex2bin("8BAEAE37F6F9000000FA00C350C350C350C350C350EFF70A19050F64000000280000008500850056008B0053007C0058808C8E918E928E50505050");
		$placeholderTrainer3[1]["pokemon2"] = hex2bin("0652535213A3000000FA00C350C350C350C350C350FEFE0F0A0F1464000000280000008E008E006A00640077007D006A868B9491808A5050505050");
		$placeholderTrainer3[1]["pokemon3"] = hex2bin("67037917F45D000000FA00C350C350C350C350C350F7E70A140A1964000000280000009E009E00730064005200840054849784868694938E915050");
		$placeholderTrainer3[1]["message_start"] = hex2bin("01030A0D170D2806");
		$placeholderTrainer3[1]["message_win"] = hex2bin("3D0501052E0D3F08");
		$placeholderTrainer3[1]["message_lose"] = hex2bin("06020B0D110E1B06");
		
		// Trainer 7
		$placeholderTrainer3[0] = array();
		$placeholderTrainer3[0]["name"] = hex2bin("83918E8B8B5050505050");
		$placeholderTrainer3[0]["class"] = hexdec("34");
		$placeholderTrainer3[0]["pokemon1"] = hex2bin("61035D091D32000000FA0075307530753075307530777A190F0F1400000000280000009200920056005300510058007A87988F8D8E505050505050");
		$placeholderTrainer3[0]["pokemon2"] = hex2bin("5949675C7C6A000000FA0075307530753075307530756B280A141E0000000028000000A100A1006F005600430053006F928B84888C8E8A50505050");
		$placeholderTrainer3[0]["pokemon3"] = hex2bin("7D52710981AD000000FA007530753075307530753065771E0F140F00000000280000007C007C005D0047006F0067005F848B848293808194999950");
		$placeholderTrainer3[0]["message_start"] = hex2bin("0C0E03010B0D2108");
		$placeholderTrainer3[0]["message_win"] = hex2bin("0C0E1904100D2108");
		$placeholderTrainer3[0]["message_lose"] = hex2bin("0C0E3304100D2108");
		
		return $placeholderTrainer3[$number];
	}
	
	function getBattleTowerPlaceholderTrainerDE4($number) {
		$placeholderTrainer4 = array();
		
		// Trainer 1
		$placeholderTrainer4[6] = array();
		$placeholderTrainer4[6]["name"] = hex2bin("96888D93849150505050");
		$placeholderTrainer4[6]["class"] = hexdec("3B");
		$placeholderTrainer4[6]["pokemon1"] = hex2bin("E6AE393F3BE1000001E848D6D8D6D8EA60C350D6D8DDFF0F0505140000000032000000B300B3008E008F0084009000909284848391808A888D8650");
		$placeholderTrainer4[6]["pokemon2"] = hex2bin("E56D9CF28A35000001E848D6D8C350EA60EA60EA60DDFC0A0F0F0F0000000032000000B200B2008700620091009D007F878E948D838E8E8C505050");
		$placeholderTrainer4[6]["pokemon3"] = hex2bin("D592C99C5C23000001E848EA60EA60EA60EA60D6D8FDCF0A0A0A1400000000320000007B007B003C01160034003B01178F8E9393918E9393505050");
		$placeholderTrainer4[6]["message_start"] = hex2bin("0C0C180D0103250A");
		$placeholderTrainer4[6]["message_win"] = hex2bin("160E3F08200D2309");
		$placeholderTrainer4[6]["message_lose"] = hex2bin("3E052306010C350B");
		
		// Trainer 2
		$placeholderTrainer4[5] = array();
		$placeholderTrainer4[5]["name"] = hex2bin("928E8C8C849150505050");
		$placeholderTrainer4[5]["class"] = hexdec("14");
		$placeholderTrainer4[5]["pokemon1"] = hex2bin("8F923F5939F4000001E848EA60D6D8D6D8EA60D6D8FDEF050A0F0A0000000032000001070107009F0070004F0072009F91848B80978E5050505050");
		$placeholderTrainer4[5]["pokemon2"] = hex2bin("83AE55396D3B000001E848D6D8EA60EA60D6D8EA60DDDD0F0F0A050000000032000000EA00EA00850080006B0085008F8B808F9180925050505050");
		$placeholderTrainer4[5]["pokemon3"] = hex2bin("87525556F7ED000001E848D6D8EA60DEA8D6D8D6D8EDFF0F140F0F0000000032000000A100A10072006B00B3009F0090818B889399805050505050");
		$placeholderTrainer4[5]["message_start"] = hex2bin("0402060D170D3E04");
		$placeholderTrainer4[5]["message_win"] = hex2bin("0A020B0406071203");
		$placeholderTrainer4[5]["message_lose"] = hex2bin("0B052503170D290B");
		
		// Trainer 3
		$placeholderTrainer4[4] = array();
		$placeholderTrainer4[4]["name"] = hex2bin("8CC29399848B50505050");
		$placeholderTrainer4[4]["class"] = hexdec("1D");
		$placeholderTrainer4[4]["pokemon1"] = hex2bin("D4923FA361E8000001E848AFC8C3509C40C350AFC8DFED05141E230000000032000000A900A900AF0091006F0063007C92828784918E9750505050");
		$placeholderTrainer4[4]["pokemon2"] = hex2bin("C7549C395E85000001E848C3509C40AFC8C350C350DFDE0A0F0A140F00000032000000C400C40076007E004B0092009C8B809282878E8A888D8650");
		$placeholderTrainer4[4]["pokemon3"] = hex2bin("44AEEE597E09000001E8489C40AFC8C3509C40ABE0FFEC050A050F0D00000032000000BB00BB00B0007F0063006C00808C8082878E8C8488505050");
		$placeholderTrainer4[4]["message_start"] = hex2bin("080D010305040405");
		$placeholderTrainer4[4]["message_win"] = hex2bin("0002010506032B07");
		$placeholderTrainer4[4]["message_lose"] = hex2bin("3005010506030206");
		
		// Trainer 4
		$placeholderTrainer4[3] = array();
		$placeholderTrainer4[3]["name"] = hex2bin("91888483848B50505050");
		$placeholderTrainer4[3]["class"] = hexdec("36");
		$placeholderTrainer4[3]["pokemon1"] = hex2bin("798C56695539000001E848AFC8ABE09C40AFC89C40FFFF14140F0F0000000032000000A100A10079008200A100910082929380918C888450505050");
		$placeholderTrainer4[3]["pokemon2"] = hex2bin("335259A33FBC000001E848AFC89C40C350AFC8C350F7FE0A14050A0000000032000000870087007D005900A6006000748388868391885050505050");
		$placeholderTrainer4[3]["pokemon3"] = hex2bin("656D5599F39C000001E848C350AFC8D2F09C40C3507DFE0F05140A0000000032000000A100A10058007500B9007E007E8B848A93918E81808B5050");
		$placeholderTrainer4[3]["message_start"] = hex2bin("120C28082E0D2D04");
		$placeholderTrainer4[3]["message_win"] = hex2bin("2405160E1F070105");
		$placeholderTrainer4[3]["message_lose"] = hex2bin("080D2908010B0105");
		
		// Trainer 5
		$placeholderTrainer4[2] = array();
		$placeholderTrainer4[2]["name"] = hex2bin("81918E92845050505050");
		$placeholderTrainer4[2]["class"] = hexdec("18");
		$placeholderTrainer4[2]["pokemon1"] = hex2bin("8E523F597EE7000001E848AFC8C350C350AFC8AFC8FDDD050A050F0000000032000000B500B50098006E00AE006800778084918E83808293988B50");
		$placeholderTrainer4[2]["pokemon2"] = hex2bin("A9926DD53F5C000001E848AFC89C40C3509C40C350EFFF0A0F050A0000000032000000B200B20086007F00AF0075007F888A928180935050505050");
		$placeholderTrainer4[2]["pokemon3"] = hex2bin("916D4155563F000001E848AFC8C350AFC89C40C350FDDE140F14050000000032000000BE00BE00890081008F00AB008899808F838E925050505050");
		$placeholderTrainer4[2]["message_start"] = hex2bin("1203170404053506");
		$placeholderTrainer4[2]["message_win"] = hex2bin("1A0B30041D03130E");
		$placeholderTrainer4[2]["message_lose"] = hex2bin("0103100803033004");
		
		// Trainer 6
		$placeholderTrainer4[1] = array();
		$placeholderTrainer4[1]["name"] = hex2bin("8C808D8D505050505050");
		$placeholderTrainer4[1]["class"] = hexdec("35");
		$placeholderTrainer4[1]["pokemon1"] = hex2bin("E3AEC913D35C000001E848AFC8C350C350C3509C40D7ED0A0F190A0000000032000000A400A4007D00B30074005300718F808D998084918E8D5050");
		$placeholderTrainer4[1]["pokemon2"] = hex2bin("CD92C95C99CF000001E848C350C350D6D8AFC89C40CFDD0A0A050F0000000032000000A900A9008600BD005400670067858E919184939184929250");
		$placeholderTrainer4[1]["pokemon3"] = hex2bin("D06DC9E79C59000001E848AFC8C350C3509C40AFC8DDDD0A0F0A0A0000000032000000B000B0008200F500490063006D929384848B889750505050");
		$placeholderTrainer4[1]["message_start"] = hex2bin("2D04040521060402");
		$placeholderTrainer4[1]["message_win"] = hex2bin("110E100D1F060105");
		$placeholderTrainer4[1]["message_lose"] = hex2bin("3F0D1A0C06040103");
		
		// Trainer 7
		$placeholderTrainer4[0] = array();
		$placeholderTrainer4[0]["name"] = hex2bin("85918892828750505050");
		$placeholderTrainer4[0]["class"] = hexdec("1E");
		$placeholderTrainer4[0]["pokemon1"] = hex2bin("CB8C8AF25E59000001E8489C409C409C409C409C4045560F0F0A0A6400000032000000A100A1007200640078007E00658688918085809188865050");
		$placeholderTrainer4[0]["pokemon2"] = hex2bin("826D3F39F0C0000001E8489C409C409C409C409C407565050F05056400000032000000C100C100A200720075005F008786809180838E9250505050");
		$placeholderTrainer4[0]["pokemon3"] = hex2bin("90AE3B3F2EC4000001E8489C409C409C409C409C4045560505140F0000000032000000B500B5007700870078008300A180918A938E925050505050");
		$placeholderTrainer4[0]["message_start"] = hex2bin("0C0C360402030105");
		$placeholderTrainer4[0]["message_win"] = hex2bin("2603170D04060105");
		$placeholderTrainer4[0]["message_lose"] = hex2bin("2903260A3A033D08");
		
		return $placeholderTrainer4[$number];
	}
	
	function getBattleTowerPlaceholderTrainerDE5($number) {
		$placeholderTrainer5 = array();
		
		// Trainer 1
		$placeholderTrainer5[6] = array();
		$placeholderTrainer5[6]["name"] = hex2bin("80948491505050505050");
		$placeholderTrainer5[6]["class"] = hexdec("14");
		$placeholderTrainer5[6]["pokemon1"] = hex2bin("E692E1393F3B0000034BC0D6D8D6D8C350EA60EA60DDFE140F0505640000003C000000D300D300A900A700A100AC00AC9284848391808A888D8650");
		$placeholderTrainer5[6]["pokemon2"] = hex2bin("F8AEF2599D3F0000034BC0D6D8EA60D6D8EA60D6D8FDED0F0A0A05640000003C000000F000F000DC00BB008300A900AF8384928F8E938091505050");
		$placeholderTrainer5[6]["pokemon3"] = hex2bin("E56D35F28A9C0000034BC0EA60D6D8D6D8EA60D6D8FBEF0F0F0F0A640000003C000000D400D400A5007100AC00BD0099878E948D838E8E8C505050");
		$placeholderTrainer5[6]["message_start"] = hex2bin("0C0E030132083707");
		$placeholderTrainer5[6]["message_win"] = hex2bin("170D3D080B060904");
		$placeholderTrainer5[6]["message_lose"] = hex2bin("160E170D250D1506");
		
		// Trainer 2
		$placeholderTrainer5[5] = array();
		$placeholderTrainer5[5]["name"] = hex2bin("928287C0858491925050");
		$placeholderTrainer5[5]["class"] = hexdec("38");
		$placeholderTrainer5[5]["pokemon1"] = hex2bin("E9923B695C5E0000034BC0D6D8C350C350C350D6D8DDDE05140A0A640000003C000000DF00DF009500A1007D00B600AA8F8E9198868E8DF8505050");
		$placeholderTrainer5[5]["pokemon2"] = hex2bin("444907EE09590000034BC0C350C350AFC8C350C350FDEF0F050F0A640000003C000000E200E200D4009400780086009E8C8082878E8C8488505050");
		$placeholderTrainer5[5]["pokemon3"] = hex2bin("91549C4155560000034BC0C350AFC8C350D6D8C350DDFD0A140F14640000003C000000E500E500A0009B00B100CB00A199808F838E925050505050");
		$placeholderTrainer5[5]["message_start"] = hex2bin("06030C0E28040405");
		$placeholderTrainer5[5]["message_win"] = hex2bin("0603290D170D020D");
		$placeholderTrainer5[5]["message_lose"] = hex2bin("06030806210D2704");
		
		// Trainer 3
		$placeholderTrainer5[4] = array();
		$placeholderTrainer5[4]["name"] = hex2bin("85849850505050505050");
		$placeholderTrainer5[4]["class"] = hexdec("17");
		$placeholderTrainer5[4]["pokemon1"] = hex2bin("CAAE44F3C2DB0000034BC0C350C350AFC8C350AFC8FDED14140519640000003C0000015A015A005F007A005E005C007A968E818194858584935050");
		$placeholderTrainer5[4]["pokemon2"] = hex2bin("8E923F30592C0000034BC0AFC8C350C350AFC8AFC8FDDD05140A19640000003C000000D700D700B6008300D0007C008E8084918E83808293988B50");
		$placeholderTrainer5[4]["pokemon3"] = hex2bin("956D3FC455390000034BC0AFC8C3509C40C350AFC8DDFD050F0F0F640000003C000000E500E500D600A4009800AC00AC839180868E91808D505050");
		$placeholderTrainer5[4]["message_start"] = hex2bin("2603210D30041604");
		$placeholderTrainer5[4]["message_win"] = hex2bin("30042C041D0E1E09");
		$placeholderTrainer5[4]["message_lose"] = hex2bin("010329073C061E09");
		
		// Trainer 4
		$placeholderTrainer5[3] = array();
		$placeholderTrainer5[3]["name"] = hex2bin("889292888D8650505050");
		$placeholderTrainer5[3]["class"] = hexdec("25");
		$placeholderTrainer5[3]["pokemon1"] = hex2bin("C5AEBDEC5EB90000034BC0C350C350C350C350C350FDEF0A050A14640000003C000000E800E8008600B90084008000D48D80828793809180505050");
		$placeholderTrainer5[3]["pokemon2"] = hex2bin("3B8A35F2F5E70000034BC0D6D8C3509C40D6D8C350FDED0F0F050F640000003C000000E400E400BC009200AA00AD009580918A808D885050505050");
		$placeholderTrainer5[3]["pokemon3"] = hex2bin("E36DD3135CB60000034BC0C350C350AFC8C350C350FBEB190F0A0A640000003C000000C400C4009800DA008A006300878F808D998084918E8D5050");
		$placeholderTrainer5[3]["message_start"] = hex2bin("01033007220C160A");
		$placeholderTrainer5[3]["message_win"] = hex2bin("01032306110E2307");
		$placeholderTrainer5[3]["message_lose"] = hex2bin("160A3208300D1B03");
		
		// Trainer 5
		$placeholderTrainer5[2] = array();
		$placeholderTrainer5[2]["name"] = hex2bin("89C0878D505050505050");
		$placeholderTrainer5[2]["class"] = hexdec("3C");
		$placeholderTrainer5[2]["pokemon1"] = hex2bin("F292875CB65E0000034BC0C350AFC8C350AFC8C350FBCD0A0A0A0A640000003C000001A801A80042003F0075008F00D7818B889292849850505050");
		$placeholderTrainer5[2]["pokemon2"] = hex2bin("8F689D3922590000034BC0C350AFC8C350C350C350FAFC0A0F0F0A640000003C00000133013300BA0080005C008200B891848B80978E5050505050");
		$placeholderTrainer5[2]["pokemon3"] = hex2bin("D677B3E059440000034BC0C3509C40C350C350C350DFED0F0A0A14640000003C000000D600D600C80092009C006500A78784918082918E92925050");
		$placeholderTrainer5[2]["message_start"] = hex2bin("21060D0D2D040405");
		$placeholderTrainer5[2]["message_win"] = hex2bin("3E050602100B0405");
		$placeholderTrainer5[2]["message_lose"] = hex2bin("0A05160E170D0508");
		
		// Trainer 6
		$placeholderTrainer5[1] = array();
		$placeholderTrainer5[1]["name"] = hex2bin("8184828A505050505050");
		$placeholderTrainer5[1]["class"] = hexdec("34");
		$placeholderTrainer5[1]["pokemon1"] = hex2bin("7C6D3B5EF7C40000034BC0C350C350C350C350C350FFEB050A0F0F640000003C000000C400C40074006200A800BD00A5918E9292808D8050505050");
		$placeholderTrainer5[1]["pokemon2"] = hex2bin("09AE3959E53B0000034BC0C350C350C350C350C350FEFE0F0A2805640000003C000000D100D1009B00AE0095009C00B4939491938E8A5050505050");
		$placeholderTrainer5[1]["pokemon3"] = hex2bin("70495939E79D0000034BC0C350C350C350C350C350FBFA0A0F0F0A640000003C000000F500F500D400C300680068006891889984918E9250505050");
		$placeholderTrainer5[1]["message_start"] = hex2bin("27030F0D02030405");
		$placeholderTrainer5[1]["message_win"] = hex2bin("1906200D23090405");
		$placeholderTrainer5[1]["message_lose"] = hex2bin("0103040527031C0B");
		
		// Trainer 7
		$placeholderTrainer5[0] = array();
		$placeholderTrainer5[0]["name"] = hex2bin("85889282878491505050");
		$placeholderTrainer5[0]["class"] = hexdec("36");
		$placeholderTrainer5[0]["pokemon1"] = hex2bin("1C8C59A33FAD0000034BC075307530753075307530B7670A14050F000000003C000000C900C900A400AB0074005D006992808D83808C8491505050");
		$placeholderTrainer5[0]["pokemon2"] = hex2bin("2FAE93CA3FBC0000034BC075307530753075307530665F0F05050A000000003C000000AB00AB009800860048007800908F80918092848A50505050");
		$placeholderTrainer5[0]["pokemon3"] = hex2bin("4C03995907DA0000034BC0753075307530753075307657050A0F14000000003C000000CD00CD00AB00C2005A0069007586848E9680995050505050");
		$placeholderTrainer5[0]["message_start"] = hex2bin("25031D06290B0105");
		$placeholderTrainer5[0]["message_win"] = hex2bin("3A060105170D010B");
		$placeholderTrainer5[0]["message_lose"] = hex2bin("3E053604180C170D");
		
		return $placeholderTrainer5[$number];
	}
	
	function getBattleTowerPlaceholderTrainerDE6($number) {
		$placeholderTrainer6 = array();
		
		// Trainer 1
		$placeholderTrainer6[6] = array();
		$placeholderTrainer6[6]["name"] = hex2bin("8A9188828A9284915050");
		$placeholderTrainer6[6]["class"] = hexdec("19");
		$placeholderTrainer6[6]["pokemon1"] = hex2bin("876D553FF72E0000053BD8EA60DAC0D6D8C350EA60FBEF0F050F146400000046000000E700E7009E009100F500DE00C9818B889399805050505050");
		$placeholderTrainer6[6]["pokemon2"] = hex2bin("86923BF739BD0000053BD8C350C350EA60EA60C350BFEF050F0F0A64000000460000013E013E00950098009E00DA00C5809094808D805050505050");
		$placeholderTrainer6[6]["pokemon3"] = hex2bin("C5AEB9ECF45C0000053BD8D2F0EA60D6D8C350E290DDDD14050A0A6400000046000001120112009C00D90098009500F78D80828793809180505050");
		$placeholderTrainer6[6]["message_start"] = hex2bin("0103180D170D3106");
		$placeholderTrainer6[6]["message_win"] = hex2bin("1D050A0225030F07");
		$placeholderTrainer6[6]["message_lose"] = hex2bin("0103360404052102");
		
		// Trainer 2
		$placeholderTrainer6[5] = array();
		$placeholderTrainer6[5]["name"] = hex2bin("8F918E92935050505050");
		$placeholderTrainer6[5]["class"] = hexdec("20");
		$placeholderTrainer6[5]["pokemon1"] = hex2bin("F2AE4487F7550000053BD8D6D8D6D8D6D8E290C350DFED140A0F0F6400000046000001EF01EF004D0050008F00A600FA818B889292849850505050");
		$placeholderTrainer6[5]["pokemon2"] = hex2bin("8F929D593BF70000053BD8D6D8D6D8EA60D6D8C350DDDD0A0A050F64000000460000016D016D00D9009C0069009800D791848B80978E5050505050");
		$placeholderTrainer6[5]["pokemon3"] = hex2bin("E552F235B92E0000053BD8E290C350D6D8EA60D6D8DDCD0F0F14146400000046000000F500F500BB008500C500D900AF878E948D838E8E8C505050");
		$placeholderTrainer6[5]["message_start"] = hex2bin("0C0E09030B0D290B");
		$placeholderTrainer6[5]["message_win"] = hex2bin("09030B0D2F080706");
		$placeholderTrainer6[5]["message_lose"] = hex2bin("3605090303083504");
		
		// Trainer 3
		$placeholderTrainer6[4] = array();
		$placeholderTrainer6[4]["name"] = hex2bin("8191C192848B50505050");
		$placeholderTrainer6[4]["class"] = hexdec("3E");
		$placeholderTrainer6[4]["pokemon1"] = hex2bin("F89259F29D3F0000053BD8C350AFC8AFC8C350AFC8DBDF0A0F0A05640000004600000117011700F700D3009300C400CB8384928F8E938091505050");
		$placeholderTrainer6[4]["pokemon2"] = hex2bin("91AE5541563F0000053BD8AFC8C350C350AFC8AFC8DBDF0F141405640000004600000108010800BB00B100C800EE00BD99808F838E925050505050");
		$placeholderTrainer6[4]["pokemon3"] = hex2bin("676D9C995ECA0000053BD8AFC8C3509C40C350AFC8DDED0A050A0564000000460000010C010C00C200B1008C00EB0097849784868694938E915050");
		$placeholderTrainer6[4]["message_start"] = hex2bin("1104060D31040105");
		$placeholderTrainer6[4]["message_win"] = hex2bin("0A020F0E01030604");
		$placeholderTrainer6[4]["message_lose"] = hex2bin("110E100D31040105");
		
		// Trainer 4
		$placeholderTrainer6[3] = array();
		$placeholderTrainer6[3]["name"] = hex2bin("86848892505050505050");
		$placeholderTrainer6[3]["class"] = hexdec("1E");
		$placeholderTrainer6[3]["pokemon1"] = hex2bin("C5AEECB95EF70000053BD8C350C350AFC8AFC8C350FDEB05140A0F64000000460000010D010D009B00D60098008E00F08D80828793809180505050");
		$placeholderTrainer6[3]["pokemon2"] = hex2bin("820339553F2E0000053BD8D6D8AFC8C350D6D8C350DBEF0F0F051464000000460000010F010F00EB00A900B2009400CC86809180838E9250505050");
		$placeholderTrainer6[3]["pokemon3"] = hex2bin("C36D5939BCE70000053BD8C350C350AFC8C350C350DEDD0A0F0A0F64000000460000010A010A00B400B4006E009800988C8E918B8E918350505050");
		$placeholderTrainer6[3]["message_start"] = hex2bin("0104290320043004");
		$placeholderTrainer6[3]["message_win"] = hex2bin("C5000B0D290B0005");
		$placeholderTrainer6[3]["message_lose"] = hex2bin("3505C3000B0D3F04");
		
		// Trainer 5
		$placeholderTrainer6[2] = array();
		$placeholderTrainer6[2]["name"] = hex2bin("8C888287848B50505050");
		$placeholderTrainer6[2]["class"] = hexdec("16");
		$placeholderTrainer6[2]["pokemon1"] = hex2bin("D98CA3593F090000053BD8C350AFC8C350AFC8C350FDED140A050F640000004600000106010600F500A6008A00A600A69491928091888D86505050");
		$placeholderTrainer6[2]["pokemon2"] = hex2bin("7A5273075EE30000053BD8C350AFC8AFC8C350C350BDFB140F0A056400000046000000C300C30078009700BE00C600E28F808D93888C8E92505050");
		$placeholderTrainer6[2]["pokemon3"] = hex2bin("3949EE08099D0000053BD8C3509C40C350C350C350BDEF050F0F0A6400000046000000E300E300CA009100C4009400A29180928085855050505050");
		$placeholderTrainer6[2]["message_start"] = hex2bin("29033004120D2408");
		$placeholderTrainer6[2]["message_win"] = hex2bin("3F033A0321040C0D");
		$placeholderTrainer6[2]["message_lose"] = hex2bin("0F0537032E0D3E04");
		
		// Trainer 6
		$placeholderTrainer6[1] = array();
		$placeholderTrainer6[1]["name"] = hex2bin("9282878C889393505050");
		$placeholderTrainer6[1]["class"] = hexdec("22");
		$placeholderTrainer6[1]["pokemon1"] = hex2bin("CBAE61E2F2590000053BD8C350C350C350C350C350FEFD1E280F0A6400000046000000E700E700B0009A00B700BB00988688918085809188865050");
		$placeholderTrainer6[1]["pokemon2"] = hex2bin("6A77B3CB22190000053BD8C350C350C350C350C350FEFE0F0A0F056400000046000000CA00CA00E8008900BA007000D98788938C8E8D8B84845050");
		$placeholderTrainer6[1]["pokemon3"] = hex2bin("D603B3CBE0590000053BD8C350C350C350C350C350F7F70F0A0A0A6400000046000000FB00FB00EF009E00B7006D00BA8784918082918E92925050");
		$placeholderTrainer6[1]["message_start"] = hex2bin("0B0432080C041B03");
		$placeholderTrainer6[1]["message_win"] = hex2bin("010B04052D033E04");
		$placeholderTrainer6[1]["message_lose"] = hex2bin("050D170D3A040105");
		
		// Trainer 7
		$placeholderTrainer6[0] = array();
		$placeholderTrainer6[0]["name"] = hex2bin("8A8B84888D5050505050");
		$placeholderTrainer6[0]["class"] = hexdec("28");
		$placeholderTrainer6[0]["pokemon1"] = hex2bin("0303F14CEB3F0000053BD8753075307530753075307644050A05050000000046000000E900E9009F009F009800B400B481889280858B8E91505050");
		$placeholderTrainer6[0]["pokemon2"] = hex2bin("068CA3593F350000053BD8753075307530753075305644140A050F0000000046000000E600E6009F009800B400C1009F868B9491808A5050505050");
		$placeholderTrainer6[0]["pokemon3"] = hex2bin("094938083FE70000053BD8753075307530753075307664050F050F0000000046000000E700E700A100B70098009F00BB939491938E8A5050505050");
		$placeholderTrainer6[0]["message_start"] = hex2bin("09020B0624031904");
		$placeholderTrainer6[0]["message_win"] = hex2bin("29033004120D3B04");
		$placeholderTrainer6[0]["message_lose"] = hex2bin("160E210D44070105");
		
		return $placeholderTrainer6[$number];
	}
	
	function getBattleTowerPlaceholderTrainerDE7($number) {
		$placeholderTrainer7 = array();
		
		// Trainer 1
		$placeholderTrainer7[6] = array();
		$placeholderTrainer7[6]["name"] = hex2bin("918494BE505050505050");
		$placeholderTrainer7[6]["class"] = hexdec("32");
		$placeholderTrainer7[6]["pokemon1"] = hex2bin("876D5655E72E000007D000EA60D6D8EA60D6D8D6D8FDEB140F0F14640000005000000107010700B300AA011900F500DD818B889399805050505050");
		$placeholderTrainer7[6]["pokemon2"] = hex2bin("8F929CBBAD59000007D000EA60D6D8C350D8CCEA60DBED0A0A0F0A64000000500000019F019F00F800AA007900B200FA91848B80978E5050505050");
		$placeholderTrainer7[6]["pokemon3"] = hex2bin("E5549CF235F1000007D000D6D8C350D6D8D6D8D6D8FDDB0A0F0F05640000005000000118011800D9009800E000F500C5878E948D838E8E8C505050");
		$placeholderTrainer7[6]["message_start"] = hex2bin("2D050103140D3604");
		$placeholderTrainer7[6]["message_win"] = hex2bin("2D052503200D2604");
		$placeholderTrainer7[6]["message_lose"] = hex2bin("0F05260335040105");
		
		// Trainer 2
		$placeholderTrainer7[5] = array();
		$placeholderTrainer7[5]["name"] = hex2bin("91848882878491935050");
		$placeholderTrainer7[5]["class"] = hexdec("29");
		$placeholderTrainer7[5]["pokemon1"] = hex2bin("80AE5922E73F000007D000C350C350C3507530C350FDDE0A0F0F05640000005000000114011400E900DD00EC008700B7938094918E925050505050");
		$placeholderTrainer7[5]["pokemon2"] = hex2bin("83549C396D5E000007D000C350C350C350D6D8C350DFDB0A0F0A0A64000000500000016E016E00CD00C900A800CA00DA8B808F9180925050505050");
		$placeholderTrainer7[5]["pokemon3"] = hex2bin("F86D9CF2599D000007D000C350D6D8C350D6D8C350DFDB0A0F0A0A64000000500000013E013E011E00F900A900DA00E28384928F8E938091505050");
		$placeholderTrainer7[5]["message_start"] = hex2bin("01033B0625032906");
		$placeholderTrainer7[5]["message_win"] = hex2bin("0A022603180C0704");
		$placeholderTrainer7[5]["message_lose"] = hex2bin("250308062F082504");
		
		// Trainer 3
		$placeholderTrainer7[4] = array();
		$placeholderTrainer7[4]["name"] = hex2bin("8B888493995050505050");
		$placeholderTrainer7[4]["class"] = hexdec("1C");
		$placeholderTrainer7[4]["pokemon1"] = hex2bin("5E0055F76DA8000007D000C350C350AFC8D6D8C350DEDD0F0F0A0A6400000050000000F700F700AD00A500F8011500BD86848D8680915050505050");
		$placeholderTrainer7[4]["pokemon2"] = hex2bin("CD92995C4CCF000007D000AFC8C350C350AFC8C350FDED050A0A0F640000005000000111011100D90125008500A500A5858E919184939184929250");
		$placeholderTrainer7[4]["pokemon3"] = hex2bin("E6549C393BE1000007D000AFC8C3509C40D6D8C350FBED0A0F0514640000005000000111011100E100D600D100DD00DD9284848391808A888D8650");
		$placeholderTrainer7[4]["message_start"] = hex2bin("07032D0804050402");
		$placeholderTrainer7[4]["message_win"] = hex2bin("150E2C042F0D4407");
		$placeholderTrainer7[4]["message_lose"] = hex2bin("0B0D200D0909240D");
		
		// Trainer 4
		$placeholderTrainer7[3] = array();
		$placeholderTrainer7[3]["name"] = hex2bin("818B8488505050505050");
		$placeholderTrainer7[3]["class"] = hexdec("26");
		$placeholderTrainer7[3]["pokemon1"] = hex2bin("95AE563955C8000007D000C350C350C350C350AFC8DDDD140F0F0F64000000500000012F012F011C00DD00C500E400E4839180868E91808D505050");
		$placeholderTrainer7[3]["pokemon2"] = hex2bin("E9925E693FA1000007D000D6D8C3509C40D6D8C350DFED0A14050A640000005000000125012500C500D500A900ED00DD8F8E9198868E8DF8505050");
		$placeholderTrainer7[3]["pokemon3"] = hex2bin("7C498E3B8A5E000007D000D6D8C350AFC8C350C350DFDF0A050F0A64000000500000010801080095007F00DD010100E1918E9292808D8050505050");
		$placeholderTrainer7[3]["message_start"] = hex2bin("1C0C0B0D15060C0D");
		$placeholderTrainer7[3]["message_win"] = hex2bin("0B090B0D2403110A");
		$placeholderTrainer7[3]["message_lose"] = hex2bin("1F080602210C0605");
		
		// Trainer 5
		$placeholderTrainer7[2] = array();
		$placeholderTrainer7[2]["name"] = hex2bin("8AC18D88865050505050");
		$placeholderTrainer7[2]["class"] = hexdec("18");
		$placeholderTrainer7[2]["pokemon1"] = hex2bin("E2AE396D3B11000007D0009C40AFC89C40AFC8C350DFDC0F0A05236400000050000001000100008400B500B400C401248C808D9380975050505050");
		$placeholderTrainer7[2]["pokemon2"] = hex2bin("E349D313BD5C000007D000C350AFC888B8C350C350DDEF190F0A0A640000005000000102010200C4011E00B7008900B98F808D998084918E8D5050");
		$placeholderTrainer7[2]["pokemon3"] = hex2bin("928A358FD33F000007D000C3509C40C3509C40C350DDFE0F05190564000000500000012C012C00E100D500D5010F00CF8B809580838E9250505050");
		$placeholderTrainer7[2]["message_start"] = hex2bin("300417060E010B04");
		$placeholderTrainer7[2]["message_win"] = hex2bin("0E010B0D3F082408");
		$placeholderTrainer7[2]["message_lose"] = hex2bin("0004100D170D2408");
		
		// Trainer 6
		$placeholderTrainer7[1] = array();
		$placeholderTrainer7[1]["name"] = hex2bin("928F8E918D5050505050");
		$placeholderTrainer7[1]["class"] = hexdec("3A");
		$placeholderTrainer7[1]["pokemon1"] = hex2bin("8E6D3F9C592E000007D000C3509C40C3509C40C350FFED050A0A1464000000500000011A011A00ED00B1011300A500BD8084918E83808293988B50");
		$placeholderTrainer7[1]["pokemon2"] = hex2bin("65525599F35C000007D000C350C3509C409C40C350FFEF0F05140A6400000050000000FA00FA009900B5012300C900C98B848A93918E81808B5050");
		$placeholderTrainer7[1]["pokemon3"] = hex2bin("338CA359A8BD000007D000C350C3509C40C3509C40FDDD140A0A0A6400000050000000D600D600C900910105009100B18388868391885050505050");
		$placeholderTrainer7[1]["message_start"] = hex2bin("1B02020301050402");
		$placeholderTrainer7[1]["message_win"] = hex2bin("160E120E0E0E0405");
		$placeholderTrainer7[1]["message_lose"] = hex2bin("240803011B040105");
		
		// Trainer 7
		$placeholderTrainer7[0] = array();
		$placeholderTrainer7[0]["name"] = hex2bin("9980878D505050505050");
		$placeholderTrainer7[0]["class"] = hexdec("19");
		$placeholderTrainer7[0]["pokemon1"] = hex2bin("4749CABC3F5C000007D000753075307530753075306565050A050A000000005000000104010400D9009700A100CF008F9588829391848481848B50");
		$placeholderTrainer7[0]["pokemon2"] = hex2bin("7FAE3F42465C000007D00075307530753075307530746405190F0A0000000050000000F100F100FA00CD00B90085009D8F888D9288915050505050");
		$placeholderTrainer7[0]["pokemon3"] = hex2bin("D2032EF73F09000007D000753075307530753075307657140F050F00000000500000011E011E00F200A90077009200928691808D81948B8B505050");
		$placeholderTrainer7[0]["message_start"] = hex2bin("0C0E13090B0D2204");
		$placeholderTrainer7[0]["message_win"] = hex2bin("27052E0D22040405");
		$placeholderTrainer7[0]["message_lose"] = hex2bin("0C05260D04031904");
		
		return $placeholderTrainer7[$number];
	}
	
	function getBattleTowerPlaceholderTrainerDE8($number) {
		$placeholderTrainer8 = array();
		
		// Trainer 1
		$placeholderTrainer8[6] = array();
		$placeholderTrainer8[6]["name"] = hex2bin("858092848B5050505050");
		$placeholderTrainer8[6]["class"] = hexdec("41");
		$placeholderTrainer8[6]["pokemon1"] = hex2bin("C552B9BDEC6D00000B1FA8EA60EA60D6D8EA60D6D8FDED140A050A640000005A0000015D015D00CB011600CA00BC013A8D80828793809180505050");
		$placeholderTrainer8[6]["pokemon2"] = hex2bin("95497E3FC83B00000B1FA8FDE8C350DAC0EA60EA60FDED05050F05640000005A000001570157014200FC00E501070107839180868E91808D505050");
		$placeholderTrainer8[6]["pokemon3"] = hex2bin("79926955395E00000B1FA8EA60EA60EA60D6D8EA60DFDD140F0F0A640000005A00000121012100DA00EF011F010700EC929380918C888450505050");
		$placeholderTrainer8[6]["message_start"] = hex2bin("0603290B030D1B05");
		$placeholderTrainer8[6]["message_win"] = hex2bin("2603070401051904");
		$placeholderTrainer8[6]["message_lose"] = hex2bin("210E1203170D2404");
		
		// Trainer 2
		$placeholderTrainer8[5] = array();
		$placeholderTrainer8[5]["name"] = hex2bin("83888493995050505050");
		$placeholderTrainer8[5]["class"] = hexdec("21");
		$placeholderTrainer8[5]["pokemon1"] = hex2bin("5B92993B39C400000B1FA8C350C350C350C350C350DBDF05050F0F640000005A0000010A010A00F8018E00CB00EA00A2809492938E925050505050");
		$placeholderTrainer8[5]["pokemon2"] = hex2bin("A9AE11723FCA00000B1FA8C350C350C350C350C350FDCF231E0505640000005A00000145014500F300DD013600CF00E1888A928180935050505050");
		$placeholderTrainer8[5]["pokemon3"] = hex2bin("E9495C5E69B600000B1FA8C350AFC8C350D6D8C350FDED0A0A140A640000005A00000145014500DF00EF00BE010A00F88F8E9198868E8DF8505050");
		$placeholderTrainer8[5]["message_start"] = hex2bin("2603240836060202");
		$placeholderTrainer8[5]["message_win"] = hex2bin("0602240809040105");
		$placeholderTrainer8[5]["message_lose"] = hex2bin("0603380D24080105");
		
		// Trainer 3
		$placeholderTrainer8[4] = array();
		$placeholderTrainer8[4]["name"] = hex2bin("87888D99505050505050");
		$placeholderTrainer8[4]["class"] = hexdec("17");
		$placeholderTrainer8[4]["pokemon1"] = hex2bin("E692E1393F3B00000B1FA8C350C350D6D8C350AFC8DFDE140F0505640000005A00000135013500F800FF00E600F800F89284848391808A888D8650");
		$placeholderTrainer8[4]["pokemon2"] = hex2bin("F8493FF2599D00000B1FA8C350D6D8C350AFC8C350DFDE050F0A0A640000005A0000016201620141011700B900FA01038384928F8E938091505050");
		$placeholderTrainer8[4]["pokemon3"] = hex2bin("83549C39555E00000B1FA8AFC8C350C350C350D6D8BDEF0A0F0F0A640000005A00000195019500E300DD00BB00ED00FF8B808F9180925050505050");
		$placeholderTrainer8[4]["message_start"] = hex2bin("0B092A0D03033004");
		$placeholderTrainer8[4]["message_win"] = hex2bin("3D0308063E070C0D");
		$placeholderTrainer8[4]["message_lose"] = hex2bin("0E0E0E0B0B0D0C04");
		
		// Trainer 4
		$placeholderTrainer8[3] = array();
		$placeholderTrainer8[3]["name"] = hex2bin("8691C28D505050505050");
		$placeholderTrainer8[3]["class"] = hexdec("27");
		$placeholderTrainer8[3]["pokemon1"] = hex2bin("C4AE5EF7F1EA00000B1FA8D6D8C350C350D6D8C350DDFE0A0F0505640000005A00000126012600C200B9011A013900FA8F9288808D805050505050");
		$placeholderTrainer8[3]["pokemon2"] = hex2bin("4449EEE97E5900000B1FA8D6D8D6D8C350D6D8C350DDED050A050A640000005A000001510151013A00DD00B500C200E68C8082878E8C8488505050");
		$placeholderTrainer8[3]["pokemon3"] = hex2bin("8F6D7E39593F00000B1FA8AFC8C350D6D8C350C350FEFD050F0A05640000005A000001C701C7011700C7008700C2011391848B80978E5050505050");
		$placeholderTrainer8[3]["message_start"] = hex2bin("0202260308080105");
		$placeholderTrainer8[3]["message_win"] = hex2bin("0C0D2E0808080105");
		$placeholderTrainer8[3]["message_lose"] = hex2bin("27052D030C040105");
		
		// Trainer 5
		$placeholderTrainer8[2] = array();
		$placeholderTrainer8[2]["name"] = hex2bin("81949186849150505050");
		$placeholderTrainer8[2]["class"] = hexdec("16");
		$placeholderTrainer8[2]["pokemon1"] = hex2bin("3B54F135F59C00000B1FA8C350AFC8C350AFC8D6D8DFDE050F050A640000005A000001500150011200E100F7010600E280918A808D885050505050");
		$placeholderTrainer8[2]["pokemon2"] = hex2bin("F2924CF1877E00000B1FA8C350AFC8C350C350C350BDFE0A050A05640000005A000001A801A8005A005F00B400D60142818B889292849850505050");
		$placeholderTrainer8[2]["pokemon3"] = hex2bin("E50335F2F14C00000B1FA8C3509C40C350C350C350DBFE0F0F050A640000005A00000135013500EB00A400FC011500DF878E948D838E8E8C505050");
		$placeholderTrainer8[2]["message_start"] = hex2bin("25033E040B060904");
		$placeholderTrainer8[2]["message_win"] = hex2bin("29033004120D0D09");
		$placeholderTrainer8[2]["message_lose"] = hex2bin("0103230609080105");
		
		// Trainer 6
		$placeholderTrainer8[1] = array();
		$placeholderTrainer8[1]["name"] = hex2bin("8C849850505050505050");
		$placeholderTrainer8[1]["class"] = hexdec("2B");
		$placeholderTrainer8[1]["pokemon1"] = hex2bin("E349C9D35CD800000B1FA8C350C350C350C350C350EFF70A190A14FF0000005A00000117011700DF014D00CF008B00C18F808D998084918E8D5050");
		$placeholderTrainer8[1]["pokemon2"] = hex2bin("D5925C23B6E300000B1FA8C350C350C350C350C350FEFE0A140A05640000005A000000CB00CB006301ED005A006101ED8F8E9393918E9393505050");
		$placeholderTrainer8[1]["pokemon3"] = hex2bin("88543F35F72E00000B1FA8C350C350C350C350C350F7F7050F0F14640000005A000001250125013B00AF00C600EE0109858B808C80918050505050");
		$placeholderTrainer8[1]["message_start"] = hex2bin("3705010538054105");
		$placeholderTrainer8[1]["message_win"] = hex2bin("3205020D31054105");
		$placeholderTrainer8[1]["message_lose"] = hex2bin("0F05020D0A050005");
		
		// Trainer 7
		$placeholderTrainer8[0] = array();
		$placeholderTrainer8[0]["name"] = hex2bin("99848750505050505050");
		$placeholderTrainer8[0]["class"] = hexdec("24");
		$placeholderTrainer8[0]["pokemon1"] = hex2bin("F192D059D52200000B1FA87530753075307530753047570A0A0F0F000000005A00000142014200C200F500E8008000B68C888B93808D8A50505050");
		$placeholderTrainer8[0]["pokemon2"] = hex2bin("8068553FD55900000B1FA87530753075307530753065760F050F0A000000005A0000011C011C00EA00DF00FE007E00B4938094918E925050505050");
		$placeholderTrainer8[0]["pokemon3"] = hex2bin("59495CBCD5CA00000B1FA87530753075307530753054440A0A0F05000000005A00000156015600F100B9008C00A700E6928B84888C8E8A50505050");
		$placeholderTrainer8[0]["message_start"] = hex2bin("2F0833042A0D1203");
		$placeholderTrainer8[0]["message_win"] = hex2bin("0B0D3F0831080105");
		$placeholderTrainer8[0]["message_lose"] = hex2bin("170C06030E053106");
		
		return $placeholderTrainer8[$number];
	}
	
	function getBattleTowerPlaceholderTrainerDE9($number) {
		$placeholderTrainer9 = array();
		
		// Trainer 1
		$placeholderTrainer9[6] = array();
		$placeholderTrainer9[6]["name"] = hex2bin("8A948D99505050505050");
		$placeholderTrainer9[6]["class"] = hexdec("24");
		$placeholderTrainer9[6]["pokemon1"] = hex2bin("E554F2352E9C00000F4240EA60EA60EA60EA60EA60FDED0F0F140A64000000640000015B015B011400C0011C013800FC878E948D838E8E8C505050");
		$placeholderTrainer9[6]["pokemon2"] = hex2bin("4449EE593FE900000F4240EA60EA60EA60EA60EA60FDEF050A050A6400000064000001790179016400FC00CC00E2010A8C8082878E8C8488505050");
		$placeholderTrainer9[6]["pokemon3"] = hex2bin("E69239E19C5C00000F4240EA60EA60EA60EA60EA60DFFE0F140A0A64000000640000015D015D011A011E010A011C011C9284848391808A888D8650");
		$placeholderTrainer9[6]["message_start"] = hex2bin("3205260330062D08");
		$placeholderTrainer9[6]["message_win"] = hex2bin("160E33080C0D1705");
		$placeholderTrainer9[6]["message_lose"] = hex2bin("1805250D210D2704");
		
		// Trainer 2
		$placeholderTrainer9[5] = array();
		$placeholderTrainer9[5]["name"] = hex2bin("9293948C8F8550505050");
		$placeholderTrainer9[5]["class"] = hexdec("1E");
		$placeholderTrainer9[5]["pokemon1"] = hex2bin("8703552E56E700000F4240C350C350C3507530C350FDFE0F14140F640000006400000143014300DC00CE015201340116818B889399805050505050");
		$placeholderTrainer9[5]["pokemon2"] = hex2bin("80523F59E75500000F4240C350C350C350C350C350FDEF050A0F0F640000006400000155015501220114013400AA00E6938094918E925050505050");
		$placeholderTrainer9[5]["pokemon3"] = hex2bin("3B9235F5E73F00000F4240D6D8C350C350D6D8C350DDEF0F050F056400000064000001760176013200F60119012200FA80918A808D885050505050");
		$placeholderTrainer9[5]["message_start"] = hex2bin("0C0E030103084407");
		$placeholderTrainer9[5]["message_win"] = hex2bin("0C0E0301100D2308");
		$placeholderTrainer9[5]["message_lose"] = hex2bin("160E200D2207020D");
		
		// Trainer 3
		$placeholderTrainer9[4] = array();
		$placeholderTrainer9[4]["name"] = hex2bin("85918884835050505050");
		$placeholderTrainer9[4]["class"] = hexdec("14");
		$placeholderTrainer9[4]["pokemon1"] = hex2bin("068C3559A31300000F4240C350C350D6D8D6D8D6D8FEDF0F0A140F6400000064000001570157010200F7012101370107868B9491808A5050505050");
		$placeholderTrainer9[4]["pokemon2"] = hex2bin("6503565599F300000F4240AFC8C350C350AFC8AFC8FBEF140F0514640000006400000135013500BE00DE016E00F800F88B848A93918E81808B5050");
		$placeholderTrainer9[4]["pokemon3"] = hex2bin("706D39593F9D00000F4240D6D8C350D6D8C350AFC8FDEF0F0A050A6400000064000001940194015E014900A800B200B291889984918E9250505050");
		$placeholderTrainer9[4]["message_start"] = hex2bin("2503250D2C08020D");
		$placeholderTrainer9[4]["message_win"] = hex2bin("2603260A2A0D0A0A");
		$placeholderTrainer9[4]["message_lose"] = hex2bin("3E0601090908020D");
		
		// Trainer 4
		$placeholderTrainer9[3] = array();
		$placeholderTrainer9[3]["name"] = hex2bin("9282878D848883849150");
		$placeholderTrainer9[3]["class"] = hexdec("29");
		$placeholderTrainer9[3]["pokemon1"] = hex2bin("D092593FCFF200000F4240C350C350D6D8EA60C350FDDE0A050F0F6400000064000001570157010401E9009800C600DA929384848B889750505050");
		$placeholderTrainer9[3]["pokemon2"] = hex2bin("165241D33FBD00000F4240D6D8C350C350D6D8C350FDCF1419050A6400000064000001440144010E00D8011F00D400D488818893808A5050505050");
		$placeholderTrainer9[3]["pokemon3"] = hex2bin("C877C3D4DCF700000F4240AFC8C350D6D8C350D6D8BDEF0505140F640000006400000135013500CA00D1010201070107939180948D859486888B50");
		$placeholderTrainer9[3]["message_start"] = hex2bin("100529070103020D");
		$placeholderTrainer9[3]["message_win"] = hex2bin("1805110E02070405");
		$placeholderTrainer9[3]["message_lose"] = hex2bin("1005020D1005020D");
		
		// Trainer 5
		$placeholderTrainer9[2] = array();
		$placeholderTrainer9[2]["name"] = hex2bin("81808091505050505050");
		$placeholderTrainer9[2]["class"] = hexdec("27");
		$placeholderTrainer9[2]["pokemon1"] = hex2bin("D78CA33B8AB900000F4240C350C350BB80AFC8C350FDEF14050F1464000000640000012D012D011800C3013C00A000F0928D888481848B50505050");
		$placeholderTrainer9[2]["pokemon2"] = hex2bin("D449D33FA35C00000F4240C350C350C350C350AFC8FBFE1905140A64000000640000014D014D015E011A00DC00C400F692828784918E9750505050");
		$placeholderTrainer9[2]["pokemon3"] = hex2bin("F292553B7E8700000F4240C3509C40C35075307530DDFE0F05050A6400000064000002BF02BF0065006A00BC00E2015A818B889292849850505050");
		$placeholderTrainer9[2]["message_start"] = hex2bin("110E32083F080105");
		$placeholderTrainer9[2]["message_win"] = hex2bin("160E200D20030C0A");
		$placeholderTrainer9[2]["message_lose"] = hex2bin("1805160E3008020D");
		
		// Trainer 6
		$placeholderTrainer9[1] = array();
		$placeholderTrainer9[1]["name"] = hex2bin("8A888485849150505050");
		$placeholderTrainer9[1]["class"] = hexdec("2D");
		$placeholderTrainer9[1]["pokemon1"] = hex2bin("DD549C3B3F5900000F4240C350C350C350C350C350FEF70A05050A6400000064000001830183012200F800BE00C200C28A848885848B5050505050");
		$placeholderTrainer9[1]["pokemon2"] = hex2bin("67495E5C99CA00000F4240C350C350C350C350C350FEFE0A0A050564000000640000017701770118010200C8015200DA849784868694938E915050");
		$placeholderTrainer9[1]["pokemon3"] = hex2bin("8B9239F63B5C00000F4240C350C350C350C350C350FBE70F05050A64000000640000014B014B00D2014C00C6013000D6808C8E918E928E50505050");
		$placeholderTrainer9[1]["message_start"] = hex2bin("200D170C0B0D0C04");
		$placeholderTrainer9[1]["message_win"] = hex2bin("1904140B180C2103");
		$placeholderTrainer9[1]["message_lose"] = hex2bin("160E200D170C020D");
		
		// Trainer 7
		$placeholderTrainer9[0] = array();
		$placeholderTrainer9[0]["name"] = hex2bin("96888D99505050505050");
		$placeholderTrainer9[0]["class"] = hexdec("30");
		$placeholderTrainer9[0]["pokemon1"] = hex2bin("4C0399599D7E00000F4240753075307530753075307446050A0A050000000064000001490149011A013C009200AA00BE86848E9680995050505050");
		$placeholderTrainer9[0]["pokemon2"] = hex2bin("6B774407090800000F4240753075307530753075306776140F0F0F0000000064000001090109010E00DC00D6008201188D8E828A8287808D505050");
		$placeholderTrainer9[0]["pokemon3"] = hex2bin("AB4939F0C06D00000F42407530753075307530753076570F05050A0000000064000001A901A900B200B000C000D600D68B808D9394918D50505050");
		$placeholderTrainer9[0]["message_start"] = hex2bin("1B020D0D0C082D04");
		$placeholderTrainer9[0]["message_win"] = hex2bin("3405160E170D3D04");
		$placeholderTrainer9[0]["message_lose"] = hex2bin("3204250D200D2207");
		
		return $placeholderTrainer9[$number];
	}
	function getBattleTowerPlaceholderTrainerIT($number) {
		$placeholderTrainer0 = array();
		
		// Trainer 1
		$placeholderTrainer0[6] = array();
		$placeholderTrainer0[6]["name"] = hex2bin("918E9292885050505050");
		$placeholderTrainer0[6]["class"] = hexdec("25");
		$placeholderTrainer0[6]["pokemon1"] = hex2bin("876D553FF72E00000003E8C3509C409C4088B89C40DDBD0F050F14640000000A0000002900290019001800250022001F898E8B93848E8D50505050");
		$placeholderTrainer0[6]["pokemon2"] = hex2bin("C492BD5EF45C00000003E89C40C35088B89C409C40EDFB0A0A0A0A640000000A000000270027001A001800230026001F84928F848E8D5050505050");
		$placeholderTrainer0[6]["pokemon3"] = hex2bin("C5AEF7E7F45C00000003E89C409C40AFC8C3509C40DBEF0F0F0A0A640000000A0000002E002E00190022001A00190027948C8191848E8D50505050");
		$placeholderTrainer0[6]["message_start"] = hex2bin("150B0E090C063004");
		$placeholderTrainer0[6]["message_win"] = hex2bin("410D30040F0D2104");
		$placeholderTrainer0[6]["message_lose"] = hex2bin("0F0D21041C030605");
		
		// Trainer 2
		$placeholderTrainer0[5] = array();
		$placeholderTrainer0[5]["name"] = hex2bin("8F849188505050505050");
		$placeholderTrainer0[5]["class"] = hexdec("1E");
		$placeholderTrainer0[5]["pokemon1"] = hex2bin("CA7744F3DBC200000003E8C350C350C350C350C3507FD714141905640000000A00000042004200120019001300120017968E818194858584935050");
		$placeholderTrainer0[5]["pokemon2"] = hex2bin("736DB33F59D500000003E89C4075309C4075307530EFCF0F050A0F640000000A0000002F002F001F001D001D0014001C8A808D8680928A87808D50");
		$placeholderTrainer0[5]["pokemon3"] = hex2bin("DE8C395E69F600000003E89C407530821475307530FEFD0F0A1405640000000A0000002600260017001D00130018001C828E91928E8B8050505050");
		$placeholderTrainer0[5]["message_start"] = hex2bin("2403CA000B0D2004");
		$placeholderTrainer0[5]["message_win"] = hex2bin("CA000504050C0105");
		$placeholderTrainer0[5]["message_lose"] = hex2bin("CA001F0D07041805");
		
		// Trainer 3
		$placeholderTrainer0[4] = array();
		$placeholderTrainer0[4]["name"] = hex2bin("91888282885050505050");
		$placeholderTrainer0[4]["class"] = hexdec("2B");
		$placeholderTrainer0[4]["pokemon1"] = hex2bin("F1AE3B593F5C00000003E8753075307530753088B8BBDF050A050A640000000A0000002E002E001B0020001F0014001A8C888B93808D8A50505050");
		$placeholderTrainer0[4]["pokemon2"] = hex2bin("8E923F30592C00000003E875307530753075307530DBFB05140A19640000000A0000002B002B0020001800260017001A8084918E83808293988B50");
		$placeholderTrainer0[4]["pokemon3"] = hex2bin("836D3B39555E00000003E875307530753075307530FDEB050F0F0A640000000A000000340034001D001B0018001C001E8B808F9180925050505050");
		$placeholderTrainer0[4]["message_start"] = hex2bin("010D1D02120E1F0B");
		$placeholderTrainer0[4]["message_win"] = hex2bin("17063F08010D3E04");
		$placeholderTrainer0[4]["message_lose"] = hex2bin("030D36043C06020D");
		
		// Trainer 4
		$placeholderTrainer0[3] = array();
		$placeholderTrainer0[3]["name"] = hex2bin("8188808D828E50505050");
		$placeholderTrainer0[3]["class"] = hexdec("14");
		$placeholderTrainer0[3]["pokemon1"] = hex2bin("D7AEA3B9393B00000003E8753088B8753075307530FBBF14140F05640000000A000000260026001F001600220013001B928D848092848B50505050");
		$placeholderTrainer0[3]["pokemon2"] = hex2bin("E9035E3B3FA100000003E8753075309C4075307530FBDE0A05050A640000000A0000002C002C001C001E00170021001F8F8E9198868E8DF8505050");
		$placeholderTrainer0[3]["pokemon3"] = hex2bin("C877C3D4DCF700000003E875307530753075307530EFDF0505140F640000000A00000025002500180018001C001D001D8C88928391848095949250");
		$placeholderTrainer0[3]["message_start"] = hex2bin("0F0D050C02060A09");
		$placeholderTrainer0[3]["message_win"] = hex2bin("1D0D03010B0D0A09");
		$placeholderTrainer0[3]["message_lose"] = hex2bin("17031E0C0706140B");
		
		// Trainer 5
		$placeholderTrainer0[2] = array();
		$placeholderTrainer0[2]["name"] = hex2bin("8E8B928E8D5050505050");
		$placeholderTrainer0[2]["class"] = hexdec("3B");
		$placeholderTrainer0[2]["pokemon1"] = hex2bin("E4AEB94C2EF100000003E875307530753080E87530FDFE140A1405640000000A000000240024001800110019001C0016878E948D838E9491505050");
		$placeholderTrainer0[2]["pokemon2"] = hex2bin("CB523CBDF76100000003E875307530753075307530EDFD140A0F1E640000000A000000270027001C0018001D001D00188688918085809188865050");
		$placeholderTrainer0[2]["pokemon3"] = hex2bin("F2491D4CCD4600000003E87D009C40753075307530DFCE0F0A140F640000000A0000004D004D000E000E0016001B0027818B889292849850505050");
		$placeholderTrainer0[2]["message_start"] = hex2bin("040677000C0D0904");
		$placeholderTrainer0[2]["message_win"] = hex2bin("1703200D71000105");
		$placeholderTrainer0[2]["message_lose"] = hex2bin("390462002A080105");
		
		// Trainer 6
		$placeholderTrainer0[1] = array();
		$placeholderTrainer0[1]["name"] = hex2bin("828E8D93888D88505050");
		$placeholderTrainer0[1]["class"] = hexdec("19");
		$placeholderTrainer0[1]["pokemon1"] = hex2bin("8F6D1DB6AD3900000003E875307530753075307530EFF70F0A0F0F640000000A00000039003900220019001200170020928D8E918B809750505050");
		$placeholderTrainer0[1]["pokemon2"] = hex2bin("67525CCAA85D00000003E875307530753075307530FEFE0A050A19640000000A0000002D002D001F001D001700250019849784868694938E915050");
		$placeholderTrainer0[1]["pokemon3"] = hex2bin("D6AEB3CB44F900000003E875307530753075307530F7F70F0A140F640000000A0000002B002B00250019001D0012001D8784918082918E92925050");
		$placeholderTrainer0[1]["message_start"] = hex2bin("030301050C0B1104");
		$placeholderTrainer0[1]["message_win"] = hex2bin("2905010D2C063E04");
		$placeholderTrainer0[1]["message_lose"] = hex2bin("050E03050F060305");
		
		// Trainer 7
		$placeholderTrainer0[0] = array();
		$placeholderTrainer0[0]["name"] = hex2bin("96918886879350505050");
		$placeholderTrainer0[0]["class"] = hexdec("16");
		$placeholderTrainer0[0]["pokemon1"] = hex2bin("C9ADED00000000000003E875307530753075307530FFFF0F000000000000000A000000240024001A00150015001A0015948D8E968D505050505050");
		$placeholderTrainer0[0]["pokemon2"] = hex2bin("80521DCF27C400000003E87530753075307530753065570F0F1E0F000000000A000000280028001E001D002000120018938094918E925050505050");
		$placeholderTrainer0[0]["pokemon3"] = hex2bin("7A495CF4071D00000003E87530753075307530753073670A0A0F0F000000000A00000022002200130016001C001E00228C91E88C888C8450505050");
		$placeholderTrainer0[0]["message_start"] = hex2bin("0C021C030C0B1104");
		$placeholderTrainer0[0]["message_win"] = hex2bin("010207062C0D0301");
		$placeholderTrainer0[0]["message_lose"] = hex2bin("0002010D010E2004");
		
		return $placeholderTrainer0[$number];
	}
	
	function getBattleTowerPlaceholderTrainerIT1($number) {
		$placeholderTrainer1 = array();
		
		// Trainer 1
		$placeholderTrainer1[6] = array();
		$placeholderTrainer1[6]["name"] = hex2bin("8F808D88505050505050");
		$placeholderTrainer1[6]["class"] = hexdec("2C");
		$placeholderTrainer1[6]["pokemon1"] = hex2bin("C592B65CBDD50000001F40C350C350C350C350C350CFBC0A0A0A0F6400000014000000510051002E0042002E002C0048948C8191848E8D50505050");
		$placeholderTrainer1[6]["pokemon2"] = hex2bin("79AE695E39F40000001F40C350C350C350C350C350DBDB140A0F0A6400000014000000470047003300360043003C0036929380918C888450505050");
		$placeholderTrainer1[6]["pokemon3"] = hex2bin("826D3F52557E0000001F40C350C350C350C350C350FAFD050A0F056400000014000000530053004800330036002D003D8698809180838E92505050");
		$placeholderTrainer1[6]["message_start"] = hex2bin("0C02030803010105");
		$placeholderTrainer1[6]["message_win"] = hex2bin("1D050105000D3504");
		$placeholderTrainer1[6]["message_lose"] = hex2bin("1D0501050904040C");
		
		// Trainer 2
		$placeholderTrainer1[5] = array();
		$placeholderTrainer1[5]["name"] = hex2bin("8C889193885050505050");
		$placeholderTrainer1[5]["class"] = hexdec("22");
		$placeholderTrainer1[5]["pokemon1"] = hex2bin("D0AE2EE7CF590000001F40C350AFC8C3507530C350FFFF140F0F0A64000000140000004D004D00370066001F002C0030929384848B889750505050");
		$placeholderTrainer1[5]["pokemon2"] = hex2bin("418B5EF45C090000001F40C350C3507530C3509C40FDEF0A0A0A0F6400000014000000440044002A00240045004B0037808B808A8099808C505050");
		$placeholderTrainer1[5]["pokemon3"] = hex2bin("3B03352E3FE70000001F4088B8AFC8C350D6D8C350DBFB0F14050F640000001400000051005100400034003C003C0034809182808D888D84505050");
		$placeholderTrainer1[5]["message_start"] = hex2bin("2302180C0006140B");
		$placeholderTrainer1[5]["message_win"] = hex2bin("0C0C210D0E0E1904");
		$placeholderTrainer1[5]["message_lose"] = hex2bin("010D2E0D240A1805");
		
		// Trainer 3
		$placeholderTrainer1[4] = array();
		$placeholderTrainer1[4]["name"] = hex2bin("8F80918E838850505050");
		$placeholderTrainer1[4]["class"] = hexdec("3B");
		$placeholderTrainer1[4]["pokemon1"] = hex2bin("D677CBB3E0590000001F40C3507530AFC87530AFC8DFDE0A0F0A0A64000000140000004E004E0044003300340025003B8784918082918E92925050");
		$placeholderTrainer1[4]["pokemon2"] = hex2bin("67923F5E5C8A0000001F40AFC8C350C350AFC8AFC8FDEB050A0A0F6400000014000000530053003C0037002B0046002E849784868694938E915050");
		$placeholderTrainer1[4]["pokemon3"] = hex2bin("8EAE9C3F59520000001F40AFC8C3509C40C350AFC8FBBB0A050A0A64000000140000004E004E0040002D0048002C00328084918E83808293988B50");
		$placeholderTrainer1[4]["message_start"] = hex2bin("310501050C0B1104");
		$placeholderTrainer1[4]["message_win"] = hex2bin("35061A0D110B1004");
		$placeholderTrainer1[4]["message_lose"] = hex2bin("0F050605000D1404");
		
		// Trainer 4
		$placeholderTrainer1[3] = array();
		$placeholderTrainer1[3]["name"] = hex2bin("85888D99885050505050");
		$placeholderTrainer1[3]["class"] = hexdec("3C");
		$placeholderTrainer1[3]["pokemon1"] = hex2bin("F2035E4287440000001F40C350C35075307530C350BDFE0A190A1464000000140000009400940018001600290033004B818B889292849850505050");
		$placeholderTrainer1[3]["pokemon2"] = hex2bin("83AE5E553B6D0000001F40D6D875309C40D6D87530FED70A0F050A640000001400000062006200350034002D003200368B808F9180925050505050");
		$placeholderTrainer1[3]["pokemon3"] = hex2bin("19A35556465C0000001F40AFC8C350AFC8C350C350FCFE0F140F0A64000000140000003A003A002C0020003A002900258F888A8082879450505050");
		$placeholderTrainer1[3]["message_start"] = hex2bin("1F0225060C0B0105");
		$placeholderTrainer1[3]["message_win"] = hex2bin("3C04010E1D063F08");
		$placeholderTrainer1[3]["message_lose"] = hex2bin("3506100B38060605");
		
		// Trainer 5
		$placeholderTrainer1[2] = array();
		$placeholderTrainer1[2]["name"] = hex2bin("91889288505050505050");
		$placeholderTrainer1[2]["class"] = hexdec("3A");
		$placeholderTrainer1[2]["pokemon1"] = hex2bin("D477D3A35CC90000001F409C40AFC89C40AFC8C350FDFE19140A0A64000000140000004900490049003C002F002B0035928288998E915050505050");
		$placeholderTrainer1[2]["pokemon2"] = hex2bin("6BAE090807050000001F40C350AFC888B8C3507530FBFD0F0F0F146400000014000000430043003F003200340020003E8788938C8E8D8287808D50");
		$placeholderTrainer1[2]["pokemon3"] = hex2bin("800355593F3B0000001F40C3509C40C35075307530FBEF0F0A050564000000140000004C004C003D003A003F0023002F938094918E925050505050");
		$placeholderTrainer1[2]["message_start"] = hex2bin("1B020D0D01050C0B");
		$placeholderTrainer1[2]["message_win"] = hex2bin("1B0D050D3C070105");
		$placeholderTrainer1[2]["message_lose"] = hex2bin("3C053604050C0105");
		
		// Trainer 6
		$placeholderTrainer1[1] = array();
		$placeholderTrainer1[1]["name"] = hex2bin("83808B888E5050505050");
		$placeholderTrainer1[1]["class"] = hexdec("35");
		$placeholderTrainer1[1]["pokemon1"] = hex2bin("B85F393BD5F00000001F409C409C409C409C409C40EDF70F050F056400000014000000520052002800340029002500318099948C8091888B8B5050");
		$placeholderTrainer1[1]["pokemon2"] = hex2bin("F1525957D5390000001F409C409C409C409C409C40DFFE0A0A0F0F64000000140000005300530034003F003D002400308C888B93808D8A50505050");
		$placeholderTrainer1[1]["pokemon3"] = hex2bin("28AE3F3B7ED50000001F409C409C409C409C409C40C7FE0505050F6400000014000000620062002F0023002700320028968886868B989394858550");
		$placeholderTrainer1[1]["message_start"] = hex2bin("240330040B0D1708");
		$placeholderTrainer1[1]["message_win"] = hex2bin("0B0D2C0617081805");
		$placeholderTrainer1[1]["message_lose"] = hex2bin("0A020F0D1F070605");
		
		// Trainer 7
		$placeholderTrainer1[0] = array();
		$placeholderTrainer1[0]["name"] = hex2bin("8188868B888050505050");
		$placeholderTrainer1[0]["class"] = hexdec("2D");
		$placeholderTrainer1[0]["pokemon1"] = hex2bin("28685ECFF41D0000001F4075307530753075307530C7770A0F0A0F0000000014000000610061002E00220022002E0024968886868B989394858550");
		$placeholderTrainer1[0]["pokemon2"] = hex2bin("22AD3B5939090000001F40753075307530753075305646050A0F0F00000000140000004A004A0034002E00310032002E8D88838E8A888D86505050");
		$placeholderTrainer1[0]["pokemon3"] = hex2bin("C349855939F00000001F40753075307530753075305547140A0F05000000001400000051005100310031001D002A002A9094808692889184505050");
		$placeholderTrainer1[0]["message_start"] = hex2bin("000D3004010E1508");
		$placeholderTrainer1[0]["message_win"] = hex2bin("11053506110B0804");
		$placeholderTrainer1[0]["message_lose"] = hex2bin("0D053F0506052205");
		
		return $placeholderTrainer1[$number];
	}
	
	function getBattleTowerPlaceholderTrainerIT2($number) {
		$placeholderTrainer2 = array();
		
		// Trainer 1
		$placeholderTrainer2[6] = array();
		$placeholderTrainer2[6]["name"] = hex2bin("8C8E9393885050505050");
		$placeholderTrainer2[6]["class"] = hexdec("31");
		$placeholderTrainer2[6]["pokemon1"] = hex2bin("876D55562EBD0000006978C350C350C350C350D6D8DBED0F14140A640000001E00000067006700440040006B00600057898E8B93848E8D50505050");
		$placeholderTrainer2[6]["pokemon2"] = hex2bin("3E0368395A420000006978C350D6D8D6D8D6D8C350DDFB0F0F0519640000001E000000770077005100570049004600528F8E8B8896918093875050");
		$placeholderTrainer2[6]["pokemon3"] = hex2bin("7992565E69390000006978C350C350C350C350C350FFFF140A140F640000001E000000650065004B00510063005A0051929380918C888450505050");
		$placeholderTrainer2[6]["message_start"] = hex2bin("1B02200D00030C0B");
		$placeholderTrainer2[6]["message_win"] = hex2bin("13053506010D0E04");
		$placeholderTrainer2[6]["message_lose"] = hex2bin("0F05060517031D0E");
		
		// Trainer 2
		$placeholderTrainer2[5] = array();
		$placeholderTrainer2[5]["name"] = hex2bin("87948D93849150505050");
		$placeholderTrainer2[5]["class"] = hexdec("3E");
		$placeholderTrainer2[5]["pokemon1"] = hex2bin("7CAE3B8E8AD50000006978C350C350C3507530C350FBEE050A0F0F640000001E000000660066003C003100530062005689988D9750505050505050");
		$placeholderTrainer2[5]["pokemon2"] = hex2bin("335259BCA3BD0000006978C350C3507530C350C350EFFF0A0A140A640000001E000000510051004D00380066003C00488394869391888E50505050");
		$placeholderTrainer2[5]["pokemon3"] = hex2bin("B603CAF14C680000006978AFC8AFC8C350D6D8C350DFDB05050A0F640000001E0000006D006D004C0051003C0052005881848B8B8E92928E8C5050");
		$placeholderTrainer2[5]["message_start"] = hex2bin("240330040B0D1708");
		$placeholderTrainer2[5]["message_win"] = hex2bin("35060B0D17080405");
		$placeholderTrainer2[5]["message_lose"] = hex2bin("130A010D36080105");
		
		// Trainer 3
		$placeholderTrainer2[4] = array();
		$placeholderTrainer2[4]["name"] = hex2bin("87888B8B505050505050");
		$placeholderTrainer2[4]["class"] = hexdec("30");
		$placeholderTrainer2[4]["pokemon1"] = hex2bin("F2925C7387B60000006978C3507530AFC87530AFC8FBED0A140A0A640000001E000000D900D900200021003B0049006D818B889292849850505050");
		$placeholderTrainer2[4]["pokemon2"] = hex2bin("E58A35F2F78A0000006978AFC8C350C350AFC8AFC8FDED0F0F0F0F640000001E0000006C006C0054003B0056005E004C878E948D838E8E8C505050");
		$placeholderTrainer2[4]["pokemon3"] = hex2bin("446DEE08597E0000006978AFC8C3509C40C350AFC8FDBE050F0A05640000001E000000760076006C004B003D004400508C808287808C8F50505050");
		$placeholderTrainer2[4]["message_start"] = hex2bin("340B240343040405");
		$placeholderTrainer2[4]["message_win"] = hex2bin("1A050605050D0704");
		$placeholderTrainer2[4]["message_lose"] = hex2bin("0F0506050F0D3E04");
		
		// Trainer 4
		$placeholderTrainer2[3] = array();
		$placeholderTrainer2[3]["name"] = hex2bin("858E9282878850505050");
		$placeholderTrainer2[3]["class"] = hexdec("27");
		$placeholderTrainer2[3]["pokemon1"] = hex2bin("A9AED56D5C110000006978C350C35075307530C350EFDC0F0A0A23640000001E0000006F006F0053004A00670046004C82918E8180935050505050");
		$placeholderTrainer2[3]["pokemon2"] = hex2bin("E9035E693FA10000006978D6D875309C40D6D87530DFDB0A14050A640000001E000000750075004900530042005700518F8E9198868E8DF8505050");
		$placeholderTrainer2[3]["pokemon3"] = hex2bin("697659D83F9B0000006978AFC8C350AFC8C3507530DDEB0A14050AFF0000001E000000630063004D005E0038003600488C80918E96808A50505050");
		$placeholderTrainer2[3]["message_start"] = hex2bin("200D300436070105");
		$placeholderTrainer2[3]["message_win"] = hex2bin("150B2B0D30040105");
		$placeholderTrainer2[3]["message_lose"] = hex2bin("150B2C063F070105");
		
		// Trainer 5
		$placeholderTrainer2[2] = array();
		$placeholderTrainer2[2]["name"] = hex2bin("8687888D885050505050");
		$placeholderTrainer2[2]["class"] = hexdec("26");
		$placeholderTrainer2[2]["pokemon1"] = hex2bin("65037155B65700000069789C40AFC89C40AFC8C350BDEF1E0F0A0A640000001E000000620062003900450071004E004E848B848293918E83845050");
		$placeholderTrainer2[2]["pokemon2"] = hex2bin("8392F037C4460000006978C350AFC888B8C3507530FDEB05190F0F640000001E0000008E008E0050004A0041004B00518B808F9180925050505050");
		$placeholderTrainer2[2]["pokemon3"] = hex2bin("ABAEF05739AF0000006978C3509C40C35075307530DDEB050A0F0F640000001E0000008B008B003E00400042004600468B808D9394918D50505050");
		$placeholderTrainer2[2]["message_start"] = hex2bin("0105250B2C0D4404");
		$placeholderTrainer2[2]["message_win"] = hex2bin("2E0D030844041805");
		$placeholderTrainer2[2]["message_lose"] = hex2bin("2C0D440419033404");
		
		// Trainer 6
		$placeholderTrainer2[1] = array();
		$placeholderTrainer2[1]["name"] = hex2bin("8B808D99805050505050");
		$placeholderTrainer2[1]["class"] = hexdec("21");
		$placeholderTrainer2[1]["pokemon1"] = hex2bin("C46D5D815CF40000006978AFC8C350C350C350C350EFF719140A0A640000001E0000006300630044004200600067005284928F848E8D5050505050");
		$placeholderTrainer2[1]["pokemon2"] = hex2bin("4952235CBC3D0000006978C350AFC8C350B798AFC8FEFE140A0A14640000001E0000006E006E00470044005A004D006593848D9380829194848B50");
		$placeholderTrainer2[1]["pokemon3"] = hex2bin("5EAEA87A65CA0000006978C350AFC8C350C350C350F7F70A1E0F05640000001E0000006500650044003D00600067004686848D8680915050505050");
		$placeholderTrainer2[1]["message_start"] = hex2bin("2E0D030104051F0B");
		$placeholderTrainer2[1]["message_win"] = hex2bin("0C05010D14063E04");
		$placeholderTrainer2[1]["message_lose"] = hex2bin("0D0D010D04070405");
		
		// Trainer 7
		$placeholderTrainer2[0] = array();
		$placeholderTrainer2[0]["name"] = hex2bin("8C8287888B5050505050");
		$placeholderTrainer2[0]["class"] = hexdec("36");
		$placeholderTrainer2[0]["pokemon1"] = hex2bin("D9AE1DB62E2B00000069787530753075307530753077450F0A141E000000001E000000720072006400430035004100419491928091888D86505050");
		$placeholderTrainer2[0]["pokemon2"] = hex2bin("160377E44081000000697875307530753075307530677714142314000000001E000000600060004B003D0052003A003A858480918E965050505050");
		$placeholderTrainer2[0]["pokemon3"] = hex2bin("396D4302B374000000697875307530753075307530776714190F1E000000001E0000006300630055003A004E003A00408F91888C84808F84505050");
		$placeholderTrainer2[0]["message_start"] = hex2bin("0F0D200D13082804");
		$placeholderTrainer2[0]["message_win"] = hex2bin("35061A0D110B1004");
		$placeholderTrainer2[0]["message_lose"] = hex2bin("3506050D07040305");
		
		return $placeholderTrainer2[$number];
	}
	
	function getBattleTowerPlaceholderTrainerIT3($number) {
		$placeholderTrainer3 = array();
		
		// Trainer 1
		$placeholderTrainer3[6] = array();
		$placeholderTrainer3[6]["name"] = hex2bin("819188808D8E50505050");
		$placeholderTrainer3[6]["class"] = hexdec("20");
		$placeholderTrainer3[6]["pokemon1"] = hex2bin("80AED83F59E7000000FA00C350C350C350C350C350FDFE14050A0FFF000000280000008F008F00770071007F0046005E938094918E925050505050");
		$placeholderTrainer3[6]["pokemon2"] = hex2bin("E69239E13F3B000000FA00C350C350C350C350C350FDEF0F14050564000000280000008E008E00730071006A007300738A888D8683918050505050");
		$placeholderTrainer3[6]["pokemon3"] = hex2bin("8F49D522F459000000FA00C350C350C350C350C350EDDD0F0F0A0A6400000028000000CD00CD007E0059003D0059007D928D8E918B809750505050");
		$placeholderTrainer3[6]["message_start"] = hex2bin("0D0D080404053F05");
		$placeholderTrainer3[6]["message_win"] = hex2bin("28043C062E0D120C");
		$placeholderTrainer3[6]["message_lose"] = hex2bin("280D000D00061208");
		
		// Trainer 2
		$placeholderTrainer3[5] = array();
		$placeholderTrainer3[5]["name"] = hex2bin("828091818E8D88505050");
		$placeholderTrainer3[5]["class"] = hexdec("1D");
		$placeholderTrainer3[5]["pokemon1"] = hex2bin("8392553A6D39000000FA00C350C350C350C350C350FDEB0F0A0A0F6400000028000000BA00BA006B006500560067006F8B808F9180925050505050");
		$placeholderTrainer3[5]["pokemon2"] = hex2bin("D0AEC9E7595C000000FA00C350C350C350C350C350EFDB0A0F0A0A6400000028000000890089006A00C7003D004F0057929384848B889750505050");
		$placeholderTrainer3[5]["pokemon3"] = hex2bin("41525E096907000000FA00C350AFC8C350D6D8C350DDEF0A0F140F64000000280000007E007E004C004900870093006B808B808A8099808C505050");
		$placeholderTrainer3[5]["message_start"] = hex2bin("2C0D0301180C3408");
		$placeholderTrainer3[5]["message_win"] = hex2bin("2F050E061107020D");
		$placeholderTrainer3[5]["message_lose"] = hex2bin("24080F0D3B061304");
		
		// Trainer 3
		$placeholderTrainer3[4] = array();
		$placeholderTrainer3[4]["name"] = hex2bin("8C8E9192845050505050");
		$placeholderTrainer3[4]["class"] = hexdec("29");
		$placeholderTrainer3[4]["pokemon1"] = hex2bin("79923B55395E000000FA00C350C350AFC8C350AFC8FDBE050F0F0A640000002800000083008300630068007F00750069929380918C888450505050");
		$placeholderTrainer3[4]["pokemon2"] = hex2bin("CAAE44F3DBC2000000FA00AFC8C350C350C350C350BFE7141419056400000028000000E900E9003E00550040003B004F968E818194858584935050");
		$placeholderTrainer3[4]["pokemon3"] = hex2bin("4C779959059D000000FA00AFC8C3509C40C350AFC8DDED050A140A6400000028000000910091007D008B004A00500058868E8B848C505050505050");
		$placeholderTrainer3[4]["message_start"] = hex2bin("0605060503010405");
		$placeholderTrainer3[4]["message_win"] = hex2bin("0605050D07040405");
		$placeholderTrainer3[4]["message_lose"] = hex2bin("0605050D35040405");
		
		// Trainer 4
		$placeholderTrainer3[3] = array();
		$placeholderTrainer3[3]["name"] = hex2bin("98948594505050505050");
		$placeholderTrainer3[3]["class"] = hexdec("32");
		$placeholderTrainer3[3]["pokemon1"] = hex2bin("D48CA3D3E43F000000FA00C350C350C3509C40C350BDFE1419140564000000280000008B008B008B0075005900520066928288998E915050505050");
		$placeholderTrainer3[3]["pokemon2"] = hex2bin("3352593FBCBD000000FA00AFC8C350C350C350C350FEBB0A050A0A64000000280000006C006C0067004E0083004B005B8394869391888E50505050");
		$placeholderTrainer3[3]["pokemon3"] = hex2bin("506D395E593B000000FA00AFC8C350AFC8C350C350BFCF0F0A0A0564000000280000009D009D005F007E003C00770067928B8E9681918E50505050");
		$placeholderTrainer3[3]["message_start"] = hex2bin("29052E0D03010105");
		$placeholderTrainer3[3]["message_win"] = hex2bin("0B051D063F080105");
		$placeholderTrainer3[3]["message_lose"] = hex2bin("13052F08120B0804");
		
		// Trainer 5
		$placeholderTrainer3[2] = array();
		$placeholderTrainer3[2]["name"] = hex2bin("91888D88505050505050");
		$placeholderTrainer3[2]["class"] = hexdec("1C");
		$placeholderTrainer3[2]["pokemon1"] = hex2bin("E900B0A03CA8000000FA00C350AFC8C350C350C350BCEF1E1E140A64000000280000009300930063006C0056007B00738F8E9198868E8DF8505050");
		$placeholderTrainer3[2]["pokemon2"] = hex2bin("3B8AAC2B222E000000FA00C350C350C350C350C350FEBB191E0F146400000028000000980098007F0066006F00730063809182808D888D84505050");
		$placeholderTrainer3[2]["pokemon3"] = hex2bin("CD92E5B65CC9000000FA00C350C350C350C350C350FA7F280A0A0A64000000280000008C008C006F0093004000570057858E919184939184929250");
		$placeholderTrainer3[2]["message_start"] = hex2bin("1F0B0F0D200D2A08");
		$placeholderTrainer3[2]["message_win"] = hex2bin("010D1D063E040105");
		$placeholderTrainer3[2]["message_lose"] = hex2bin("26050105010D2004");
		
		// Trainer 6
		$placeholderTrainer3[1] = array();
		$placeholderTrainer3[1]["name"] = hex2bin("8B8E8892885050505050");
		$placeholderTrainer3[1]["class"] = hexdec("41");
		$placeholderTrainer3[1]["pokemon1"] = hex2bin("8BAEAE37F6F9000000FA00C350C350C350C350C350EFF70A19050F64000000280000008500850056008B0053007C00588E8C809293809150505050");
		$placeholderTrainer3[1]["pokemon2"] = hex2bin("0652535213A3000000FA00C350C350C350C350C350FEFE0F0A0F1464000000280000008E008E006A00640077007D006A8287809188998091835050");
		$placeholderTrainer3[1]["pokemon3"] = hex2bin("67037917F45D000000FA00C350C350C350C350C350F7E70A140A1964000000280000009E009E00730064005200840054849784868694938E915050");
		$placeholderTrainer3[1]["message_start"] = hex2bin("35060F0D02060107");
		$placeholderTrainer3[1]["message_win"] = hex2bin("130501051D063F08");
		$placeholderTrainer3[1]["message_lose"] = hex2bin("3204050D35040105");
		
		// Trainer 7
		$placeholderTrainer3[0] = array();
		$placeholderTrainer3[0]["name"] = hex2bin("8B8E8F84995050505050");
		$placeholderTrainer3[0]["class"] = hexdec("34");
		$placeholderTrainer3[0]["pokemon1"] = hex2bin("61035D091D32000000FA0075307530753075307530777A190F0F1400000000280000009200920056005300510058007A87988F8D8E505050505050");
		$placeholderTrainer3[0]["pokemon2"] = hex2bin("5949675C7C6A000000FA0075307530753075307530756B280A141E0000000028000000A100A1006F005600430053006F8C948A5050505050505050");
		$placeholderTrainer3[0]["pokemon3"] = hex2bin("7D52710981AD000000FA007530753075307530753065771E0F140F00000000280000007C007C005D0047006F0067005F848B848293808194999950");
		$placeholderTrainer3[0]["message_start"] = hex2bin("2B0D170A0B0D0E04");
		$placeholderTrainer3[0]["message_win"] = hex2bin("1D0D19040B0D240A");
		$placeholderTrainer3[0]["message_lose"] = hex2bin("1D0D33040B0D240A");
		
		return $placeholderTrainer3[$number];
	}
	
	function getBattleTowerPlaceholderTrainerIT4($number) {
		$placeholderTrainer4 = array();
		
		// Trainer 1
		$placeholderTrainer4[6] = array();
		$placeholderTrainer4[6]["name"] = hex2bin("8C808D9980938E505050");
		$placeholderTrainer4[6]["class"] = hexdec("3B");
		$placeholderTrainer4[6]["pokemon1"] = hex2bin("E6AE393F3BE1000001E848D6D8D6D8EA60C350D6D8DDFF0F0505140000000032000000B300B3008E008F0084009000908A888D8683918050505050");
		$placeholderTrainer4[6]["pokemon2"] = hex2bin("E56D9CF28A35000001E848D6D8C350EA60EA60EA60DDFC0A0F0F0F0000000032000000B200B2008700620091009D007F878E948D838E8E8C505050");
		$placeholderTrainer4[6]["pokemon3"] = hex2bin("D592C99C5C23000001E848EA60EA60EA60EA60D6D8FDCF0A0A0A1400000000320000007B007B003C01160034003B0117928794828A8B8450505050");
		$placeholderTrainer4[6]["message_start"] = hex2bin("0C0C0F0D3D061F0A");
		$placeholderTrainer4[6]["message_win"] = hex2bin("1009200D23090105");
		$placeholderTrainer4[6]["message_lose"] = hex2bin("22050A09020C0605");
		
		// Trainer 2
		$placeholderTrainer4[5] = array();
		$placeholderTrainer4[5]["name"] = hex2bin("91809393885050505050");
		$placeholderTrainer4[5]["class"] = hexdec("14");
		$placeholderTrainer4[5]["pokemon1"] = hex2bin("8F923F5939F4000001E848EA60D6D8D6D8EA60D6D8FDEF050A0F0A0000000032000001070107009F0070004F0072009F928D8E918B809750505050");
		$placeholderTrainer4[5]["pokemon2"] = hex2bin("83AE55396D3B000001E848D6D8EA60EA60D6D8EA60DDDD0F0F0A050000000032000000EA00EA00850080006B0085008F8B808F9180925050505050");
		$placeholderTrainer4[5]["pokemon3"] = hex2bin("87525556F7ED000001E848D6D8EA60DEA8D6D8D6D8EDFF0F140F0F0000000032000000A100A10072006B00B3009F0090898E8B93848E8D50505050");
		$placeholderTrainer4[5]["message_start"] = hex2bin("1F0B150B38070105");
		$placeholderTrainer4[5]["message_win"] = hex2bin("0A021B041D0D1904");
		$placeholderTrainer4[5]["message_lose"] = hex2bin("13052F08120B0804");
		
		// Trainer 3
		$placeholderTrainer4[4] = array();
		$placeholderTrainer4[4]["name"] = hex2bin("95808B848D9388505050");
		$placeholderTrainer4[4]["class"] = hexdec("1D");
		$placeholderTrainer4[4]["pokemon1"] = hex2bin("D4923FA361E8000001E848AFC8C3509C40C350AFC8DFED05141E230000000032000000A900A900AF0091006F0063007C928288998E915050505050");
		$placeholderTrainer4[4]["pokemon2"] = hex2bin("C7549C395E85000001E848C3509C40AFC8C350C350DFDE0A0F0A140F00000032000000C400C40076007E004B0092009C928B8E968A888D86505050");
		$placeholderTrainer4[4]["pokemon3"] = hex2bin("44AEEE597E09000001E8489C40AFC8C3509C40ABE0FFEC050A050F0D00000032000000BB00BB00B0007F0063006C00808C808287808C8F50505050");
		$placeholderTrainer4[4]["message_start"] = hex2bin("1A0D030B08040405");
		$placeholderTrainer4[4]["message_win"] = hex2bin("01020105010D0007");
		$placeholderTrainer4[4]["message_lose"] = hex2bin("39050105010D3608");
		
		// Trainer 4
		$placeholderTrainer4[3] = array();
		$placeholderTrainer4[3]["name"] = hex2bin("81888D88505050505050");
		$placeholderTrainer4[3]["class"] = hexdec("36");
		$placeholderTrainer4[3]["pokemon1"] = hex2bin("798C56695539000001E848AFC8ABE09C40AFC89C40FFFF14140F0F0000000032000000A100A10079008200A100910082929380918C888450505050");
		$placeholderTrainer4[3]["pokemon2"] = hex2bin("335259A33FBC000001E848AFC89C40C350AFC8C350F7FE0A14050A0000000032000000870087007D005900A6006000748394869391888E50505050");
		$placeholderTrainer4[3]["pokemon3"] = hex2bin("656D5599F39C000001E848C350AFC8D2F09C40C3507DFE0F05140A0000000032000000A100A10058007500B9007E007E848B848293918E83845050");
		$placeholderTrainer4[3]["message_start"] = hex2bin("1E0C00062E0D0301");
		$placeholderTrainer4[3]["message_win"] = hex2bin("1502010D1F070105");
		$placeholderTrainer4[3]["message_lose"] = hex2bin("35060B0D2E0D1304");
		
		// Trainer 5
		$placeholderTrainer4[2] = array();
		$placeholderTrainer4[2]["name"] = hex2bin("818E8B8B805050505050");
		$placeholderTrainer4[2]["class"] = hexdec("18");
		$placeholderTrainer4[2]["pokemon1"] = hex2bin("8E523F597EE7000001E848AFC8C350C350AFC8AFC8FDDD050A050F0000000032000000B500B50098006E00AE006800778084918E83808293988B50");
		$placeholderTrainer4[2]["pokemon2"] = hex2bin("A9926DD53F5C000001E848AFC89C40C3509C40C350EFFF0A0F050A0000000032000000B200B20086007F00AF0075007F82918E8180935050505050");
		$placeholderTrainer4[2]["pokemon3"] = hex2bin("916D4155563F000001E848AFC8C350AFC89C40C350FDDE140F14050000000032000000BE00BE00890081008F00AB008899808F838E925050505050");
		$placeholderTrainer4[2]["message_start"] = hex2bin("35061A0D110B1004");
		$placeholderTrainer4[2]["message_win"] = hex2bin("16030F0D30043D04");
		$placeholderTrainer4[2]["message_lose"] = hex2bin("150B1D0D320D2404");
		
		// Trainer 6
		$placeholderTrainer4[1] = array();
		$placeholderTrainer4[1]["name"] = hex2bin("85808188505050505050");
		$placeholderTrainer4[1]["class"] = hexdec("35");
		$placeholderTrainer4[1]["pokemon1"] = hex2bin("E3AEC913D35C000001E848AFC8C350C350C3509C40D7ED0A0F190A0000000032000000A400A4007D00B3007400530071928A80918C8E9198505050");
		$placeholderTrainer4[1]["pokemon2"] = hex2bin("CD92C95C99CF000001E848C350C350D6D8AFC89C40CFDD0A0A050F0000000032000000A900A9008600BD005400670067858E919184939184929250");
		$placeholderTrainer4[1]["pokemon3"] = hex2bin("D06DC9E79C59000001E848AFC8C350C3509C40AFC8DDDD0A0F0A0A0000000032000000B000B0008200F500490063006D929384848B889750505050");
		$placeholderTrainer4[1]["message_start"] = hex2bin("0B0B04053B051F0B");
		$placeholderTrainer4[1]["message_win"] = hex2bin("0E06130803010105");
		$placeholderTrainer4[1]["message_lose"] = hex2bin("33071D0D110C120C");
		
		// Trainer 7
		$placeholderTrainer4[0] = array();
		$placeholderTrainer4[0]["name"] = hex2bin("91888E50505050505050");
		$placeholderTrainer4[0]["class"] = hexdec("1E");
		$placeholderTrainer4[0]["pokemon1"] = hex2bin("CB8C8AF25E59000001E8489C409C409C409C409C4045560F0F0A0A6400000032000000A100A1007200640078007E00658688918085809188865050");
		$placeholderTrainer4[0]["pokemon2"] = hex2bin("826D3F39F0C0000001E8489C409C409C409C409C407565050F05056400000032000000C100C100A200720075005F00878698809180838E92505050");
		$placeholderTrainer4[0]["pokemon3"] = hex2bin("90AE3B3F2EC4000001E8489C409C409C409C409C4045560505140F0000000032000000B500B5007700870078008300A18091938882948D8E505050");
		$placeholderTrainer4[0]["message_start"] = hex2bin("0C0C1D0B02063404");
		$placeholderTrainer4[0]["message_win"] = hex2bin("3C040F0D13080105");
		$placeholderTrainer4[0]["message_lose"] = hex2bin("410D260A0F0D240B");
		
		return $placeholderTrainer4[$number];
	}
	
	function getBattleTowerPlaceholderTrainerIT5($number) {
		$placeholderTrainer5 = array();
		
		// Trainer 1
		$placeholderTrainer5[6] = array();
		$placeholderTrainer5[6]["name"] = hex2bin("8C8E9188505050505050");
		$placeholderTrainer5[6]["class"] = hexdec("14");
		$placeholderTrainer5[6]["pokemon1"] = hex2bin("E692E1393F3B0000034BC0D6D8D6D8C350EA60EA60DDFE140F0505640000003C000000D300D300A900A700A100AC00AC8A888D8683918050505050");
		$placeholderTrainer5[6]["pokemon2"] = hex2bin("F8AEF2599D3F0000034BC0D6D8EA60D6D8EA60D6D8FDED0F0A0A05640000003C000000F000F000DC00BB008300A900AF939891808D889380915050");
		$placeholderTrainer5[6]["pokemon3"] = hex2bin("E56D35F28A9C0000034BC0EA60D6D8D6D8EA60D6D8FBEF0F0F0F0A640000003C000000D400D400A5007100AC00BD0099878E948D838E8E8C505050");
		$placeholderTrainer5[6]["message_start"] = hex2bin("330B0E06010D1308");
		$placeholderTrainer5[6]["message_win"] = hex2bin("3506130E050D0704");
		$placeholderTrainer5[6]["message_lose"] = hex2bin("3506010D2208130E");
		
		// Trainer 2
		$placeholderTrainer5[5] = array();
		$placeholderTrainer5[5]["name"] = hex2bin("808D8391849692505050");
		$placeholderTrainer5[5]["class"] = hexdec("38");
		$placeholderTrainer5[5]["pokemon1"] = hex2bin("E9923B695C5E0000034BC0D6D8C350C350C350D6D8DDDE05140A0A640000003C000000DF00DF009500A1007D00B600AA8F8E9198868E8DF8505050");
		$placeholderTrainer5[5]["pokemon2"] = hex2bin("444907EE09590000034BC0C350C350AFC8C350C350FDEF0F050F0A640000003C000000E200E200D4009400780086009E8C808287808C8F50505050");
		$placeholderTrainer5[5]["pokemon3"] = hex2bin("91549C4155560000034BC0C350AFC8C350D6D8C350DDFD0A140F14640000003C000000E500E500A0009B00B100CB00A199808F838E925050505050");
		$placeholderTrainer5[5]["message_start"] = hex2bin("010D290D2A080405");
		$placeholderTrainer5[5]["message_win"] = hex2bin("3506010D0D0D020D");
		$placeholderTrainer5[5]["message_lose"] = hex2bin("010D010E200D2A08");
		
		// Trainer 3
		$placeholderTrainer5[4] = array();
		$placeholderTrainer5[4]["name"] = hex2bin("8F8092938E9184505050");
		$placeholderTrainer5[4]["class"] = hexdec("17");
		$placeholderTrainer5[4]["pokemon1"] = hex2bin("CAAE44F3C2DB0000034BC0C350C350AFC8C350AFC8FDED14140519640000003C0000015A015A005F007A005E005C007A968E818194858584935050");
		$placeholderTrainer5[4]["pokemon2"] = hex2bin("8E923F30592C0000034BC0AFC8C350C350AFC8AFC8FDDD05140A19640000003C000000D700D700B6008300D0007C008E8084918E83808293988B50");
		$placeholderTrainer5[4]["pokemon3"] = hex2bin("956D3FC455390000034BC0AFC8C3509C40C350AFC8DDFD050F0F0F640000003C000000E500E500D600A4009800AC00AC839180868E8D8893845050");
		$placeholderTrainer5[4]["message_start"] = hex2bin("330B2E0D16043004");
		$placeholderTrainer5[4]["message_win"] = hex2bin("270D1D040F0D1604");
		$placeholderTrainer5[4]["message_lose"] = hex2bin("3706380B420B3C06");
		
		// Trainer 4
		$placeholderTrainer5[3] = array();
		$placeholderTrainer5[3]["name"] = hex2bin("928F80838E5050505050");
		$placeholderTrainer5[3]["class"] = hexdec("25");
		$placeholderTrainer5[3]["pokemon1"] = hex2bin("C5AEBDEC5EB90000034BC0C350C350C350C350C350FDEF0A050A14640000003C000000E800E8008600B90084008000D4948C8191848E8D50505050");
		$placeholderTrainer5[3]["pokemon2"] = hex2bin("3B8A35F2F5E70000034BC0D6D8C3509C40D6D8C350FDED0F0F050F640000003C000000E400E400BC009200AA00AD0095809182808D888D84505050");
		$placeholderTrainer5[3]["pokemon3"] = hex2bin("E36DD3135CB60000034BC0C350C350AFC8C350C350FBEB190F0A0A640000003C000000C400C4009800DA008A00630087928A80918C8E9198505050");
		$placeholderTrainer5[3]["message_start"] = hex2bin("0F0D050C0206160A");
		$placeholderTrainer5[3]["message_win"] = hex2bin("2B0D1A0D0B0D1B07");
		$placeholderTrainer5[3]["message_lose"] = hex2bin("0F0D05080206160A");
		
		// Trainer 5
		$placeholderTrainer5[2] = array();
		$placeholderTrainer5[2]["name"] = hex2bin("87888A8E505050505050");
		$placeholderTrainer5[2]["class"] = hexdec("3C");
		$placeholderTrainer5[2]["pokemon1"] = hex2bin("F292875CB65E0000034BC0C350AFC8C350AFC8C350FBCD0A0A0A0A640000003C000001A801A80042003F0075008F00D7818B889292849850505050");
		$placeholderTrainer5[2]["pokemon2"] = hex2bin("8F689D3922590000034BC0C350AFC8C350C350C350FAFC0A0F0F0A640000003C00000133013300BA0080005C008200B8928D8E918B809750505050");
		$placeholderTrainer5[2]["pokemon3"] = hex2bin("D677B3E059440000034BC0C3509C40C350C350C350DFED0F0A0A14640000003C000000D600D600C80092009C006500A78784918082918E92925050");
		$placeholderTrainer5[2]["message_start"] = hex2bin("1502150C180C0405");
		$placeholderTrainer5[2]["message_win"] = hex2bin("1502090222080105");
		$placeholderTrainer5[2]["message_lose"] = hex2bin("120535061E0B120E");
		
		// Trainer 6
		$placeholderTrainer5[1] = array();
		$placeholderTrainer5[1]["name"] = hex2bin("848092938E8D50505050");
		$placeholderTrainer5[1]["class"] = hexdec("34");
		$placeholderTrainer5[1]["pokemon1"] = hex2bin("7C6D3B5EF7C40000034BC0C350C350C350C350C350FFEB050A0F0F640000003C000000C400C40074006200A800BD00A589988D9750505050505050");
		$placeholderTrainer5[1]["pokemon2"] = hex2bin("09AE3959E53B0000034BC0C350C350C350C350C350FEFE0F0A2805640000003C000000D100D1009B00AE0095009C00B4818B8092938E8892845050");
		$placeholderTrainer5[1]["pokemon3"] = hex2bin("70495939E79D0000034BC0C350C350C350C350C350FBFA0A0F0F0A640000003C000000F500F500D400C3006800680068918798838E8D5050505050");
		$placeholderTrainer5[1]["message_start"] = hex2bin("2703010D0D0D0405");
		$placeholderTrainer5[1]["message_win"] = hex2bin("0B0D200D23090405");
		$placeholderTrainer5[1]["message_lose"] = hex2bin("0C0D04052703350B");
		
		// Trainer 7
		$placeholderTrainer5[0] = array();
		$placeholderTrainer5[0]["name"] = hex2bin("85809188505050505050");
		$placeholderTrainer5[0]["class"] = hexdec("36");
		$placeholderTrainer5[0]["pokemon1"] = hex2bin("1C8C59A33FAD0000034BC075307530753075307530B7670A14050F000000003C000000C900C900A400AB0074005D006992808D83928B8092875050");
		$placeholderTrainer5[0]["pokemon2"] = hex2bin("2FAE93CA3FBC0000034BC075307530753075307530665F0F05050A000000003C000000AB00AB009800860048007800908F80918092848293505050");
		$placeholderTrainer5[0]["pokemon3"] = hex2bin("4C03995907DA0000034BC0753075307530753075307657050A0F14000000003C000000CD00CD00AB00C2005A00690075868E8B848C505050505050");
		$placeholderTrainer5[0]["message_start"] = hex2bin("050D03082D080105");
		$placeholderTrainer5[0]["message_win"] = hex2bin("200601051D0E1304");
		$placeholderTrainer5[0]["message_lose"] = hex2bin("120535061E0B120E");
		
		return $placeholderTrainer5[$number];
	}
	
	function getBattleTowerPlaceholderTrainerIT6($number) {
		$placeholderTrainer6 = array();
		
		// Trainer 1
		$placeholderTrainer6[6] = array();
		$placeholderTrainer6[6]["name"] = hex2bin("818083808D8E50505050");
		$placeholderTrainer6[6]["class"] = hexdec("19");
		$placeholderTrainer6[6]["pokemon1"] = hex2bin("876D553FF72E0000053BD8EA60DAC0D6D8C350EA60FBEF0F050F146400000046000000E700E7009E009100F500DE00C9898E8B93848E8D50505050");
		$placeholderTrainer6[6]["pokemon2"] = hex2bin("86923BF739BD0000053BD8C350C350EA60EA60C350BFEF050F0F0A64000000460000013E013E00950098009E00DA00C595808F8E91848E8D505050");
		$placeholderTrainer6[6]["pokemon3"] = hex2bin("C5AEB9ECF45C0000053BD8D2F0EA60D6D8C350E290DDDD14050A0A6400000046000001120112009C00D90098009500F7948C8191848E8D50505050");
		$placeholderTrainer6[6]["message_start"] = hex2bin("2905240D1C0B3F08");
		$placeholderTrainer6[6]["message_win"] = hex2bin("1D050A020F0D0407");
		$placeholderTrainer6[6]["message_lose"] = hex2bin("050D350404050102");
		
		// Trainer 2
		$placeholderTrainer6[5] = array();
		$placeholderTrainer6[5]["name"] = hex2bin("92808D93885050505050");
		$placeholderTrainer6[5]["class"] = hexdec("20");
		$placeholderTrainer6[5]["pokemon1"] = hex2bin("F2AE4487F7550000053BD8D6D8D6D8D6D8E290C350DFED140A0F0F6400000046000001EF01EF004D0050008F00A600FA818B889292849850505050");
		$placeholderTrainer6[5]["pokemon2"] = hex2bin("8F929D593BF70000053BD8D6D8D6D8EA60D6D8C350DDDD0A0A050F64000000460000016D016D00D9009C0069009800D7928D8E918B809750505050");
		$placeholderTrainer6[5]["pokemon3"] = hex2bin("E552F235B92E0000053BD8E290C350D6D8EA60D6D8DDCD0F0F14146400000046000000F500F500BB008500C500D900AF878E948D838E8E8C505050");
		$placeholderTrainer6[5]["message_start"] = hex2bin("2B0D09030B0D3107");
		$placeholderTrainer6[5]["message_win"] = hex2bin("3506010704060903");
		$placeholderTrainer6[5]["message_lose"] = hex2bin("09031E0D3504020D");
		
		// Trainer 3
		$placeholderTrainer6[4] = array();
		$placeholderTrainer6[4]["name"] = hex2bin("8980828A928E8D505050");
		$placeholderTrainer6[4]["class"] = hexdec("3E");
		$placeholderTrainer6[4]["pokemon1"] = hex2bin("F89259F29D3F0000053BD8C350AFC8AFC8C350AFC8DBDF0A0F0A05640000004600000117011700F700D3009300C400CB939891808D889380915050");
		$placeholderTrainer6[4]["pokemon2"] = hex2bin("91AE5541563F0000053BD8AFC8C350C350AFC8AFC8DBDF0F141405640000004600000108010800BB00B100C800EE00BD99808F838E925050505050");
		$placeholderTrainer6[4]["pokemon3"] = hex2bin("676D9C995ECA0000053BD8AFC8C3509C40C350AFC8DDED0A050A0564000000460000010C010C00C200B1008C00EB0097849784868694938E915050");
		$placeholderTrainer6[4]["message_start"] = hex2bin("1F0B010D0E040105");
		$placeholderTrainer6[4]["message_win"] = hex2bin("0A022406050D0704");
		$placeholderTrainer6[4]["message_lose"] = hex2bin("010D010E0E040105");
		
		// Trainer 4
		$placeholderTrainer6[3] = array();
		$placeholderTrainer6[3]["name"] = hex2bin("91845050505050505050");
		$placeholderTrainer6[3]["class"] = hexdec("1E");
		$placeholderTrainer6[3]["pokemon1"] = hex2bin("C5AEECB95EF70000053BD8C350C350AFC8AFC8C350FDEB05140A0F64000000460000010D010D009B00D60098008E00F0948C8191848E8D50505050");
		$placeholderTrainer6[3]["pokemon2"] = hex2bin("820339553F2E0000053BD8D6D8AFC8C350D6D8C350DBEF0F0F051464000000460000010F010F00EB00A900B2009400CC8698809180838E92505050");
		$placeholderTrainer6[3]["pokemon3"] = hex2bin("C36D5939BCE70000053BD8C350C350AFC8C350C350DEDD0A0F0A0F64000000460000010A010A00B400B4006E009800989094808692889184505050");
		$placeholderTrainer6[3]["message_start"] = hex2bin("0104300400064304");
		$placeholderTrainer6[3]["message_win"] = hex2bin("C5000B0D20040005");
		$placeholderTrainer6[3]["message_lose"] = hex2bin("1305C3000B0D3E04");
		
		// Trainer 5
		$placeholderTrainer6[2] = array();
		$placeholderTrainer6[2]["name"] = hex2bin("8B848E8D885050505050");
		$placeholderTrainer6[2]["class"] = hexdec("16");
		$placeholderTrainer6[2]["pokemon1"] = hex2bin("D98CA3593F090000053BD8C350AFC8C350AFC8C350FDED140A050F640000004600000106010600F500A6008A00A600A69491928091888D86505050");
		$placeholderTrainer6[2]["pokemon2"] = hex2bin("7A5273075EE30000053BD8C350AFC8AFC8C350C350BDFB140F0A056400000046000000C300C30078009700BE00C600E28C91E88C888C8450505050");
		$placeholderTrainer6[2]["pokemon3"] = hex2bin("3949EE08099D0000053BD8C3509C40C350C350C350BDEF050F0F0A6400000046000000E300E300CA009100C4009400A28F91888C84808F84505050");
		$placeholderTrainer6[2]["message_start"] = hex2bin("410D30040F0D2104");
		$placeholderTrainer6[2]["message_win"] = hex2bin("010E1D0621041805");
		$placeholderTrainer6[2]["message_lose"] = hex2bin("36050F0D1D063D04");
		
		// Trainer 6
		$placeholderTrainer6[1] = array();
		$placeholderTrainer6[1]["name"] = hex2bin("8C8091888D8850505050");
		$placeholderTrainer6[1]["class"] = hexdec("22");
		$placeholderTrainer6[1]["pokemon1"] = hex2bin("CBAE61E2F2590000053BD8C350C350C350C350C350FEFD1E280F0A6400000046000000E700E700B0009A00B700BB00988688918085809188865050");
		$placeholderTrainer6[1]["pokemon2"] = hex2bin("6A77B3CB22190000053BD8C350C350C350C350C350FEFE0F0A0F056400000046000000CA00CA00E8008900BA007000D98788938C8E8D8B84845050");
		$placeholderTrainer6[1]["pokemon3"] = hex2bin("D603B3CBE0590000053BD8C350C350C350C350C350F7F70F0A0A0A6400000046000000FB00FB00EF009E00B7006D00BA8784918082918E92925050");
		$placeholderTrainer6[1]["message_start"] = hex2bin("3506110B08040105");
		$placeholderTrainer6[1]["message_win"] = hex2bin("0104010D1D063E04");
		$placeholderTrainer6[1]["message_lose"] = hex2bin("0C0D35060C043C06");
		
		// Trainer 7
		$placeholderTrainer6[0] = array();
		$placeholderTrainer6[0]["name"] = hex2bin("8D84968C808D50505050");
		$placeholderTrainer6[0]["class"] = hexdec("28");
		$placeholderTrainer6[0]["pokemon1"] = hex2bin("0303F14CEB3F0000053BD8753075307530753075307644050A05050000000046000000E900E9009F009F009800B400B495848D9492809491505050");
		$placeholderTrainer6[0]["pokemon2"] = hex2bin("068CA3593F350000053BD8753075307530753075305644140A050F0000000046000000E600E6009F009800B400C1009F8287809188998091835050");
		$placeholderTrainer6[0]["pokemon3"] = hex2bin("094938083FE70000053BD8753075307530753075307664050F050F0000000046000000E700E700A100B70098009F00BB818B8092938E8892845050");
		$placeholderTrainer6[0]["message_start"] = hex2bin("0A02380B08040105");
		$placeholderTrainer6[0]["message_win"] = hex2bin("410D30043F04050C");
		$placeholderTrainer6[0]["message_lose"] = hex2bin("0B060F0D1D061B0E");
		
		return $placeholderTrainer6[$number];
	}
	
	function getBattleTowerPlaceholderTrainerIT7($number) {
		$placeholderTrainer7 = array();
		
		// Trainer 1
		$placeholderTrainer7[6] = array();
		$placeholderTrainer7[6]["name"] = hex2bin("8A809680505050505050");
		$placeholderTrainer7[6]["class"] = hexdec("32");
		$placeholderTrainer7[6]["pokemon1"] = hex2bin("876D5655E72E000007D000EA60D6D8EA60D6D8D6D8FDEB140F0F14640000005000000107010700B300AA011900F500DD898E8B93848E8D50505050");
		$placeholderTrainer7[6]["pokemon2"] = hex2bin("8F929CBBAD59000007D000EA60D6D8C350D8CCEA60DBED0A0A0F0A64000000500000019F019F00F800AA007900B200FA928D8E918B809750505050");
		$placeholderTrainer7[6]["pokemon3"] = hex2bin("E5549CF235F1000007D000D6D8C350D6D8D6D8D6D8FDDB0A0F0F05640000005000000118011800D9009800E000F500C5878E948D838E8E8C505050");
		$placeholderTrainer7[6]["message_start"] = hex2bin("12050C0D35060C04");
		$placeholderTrainer7[6]["message_win"] = hex2bin("12050F0D200D1308");
		$placeholderTrainer7[6]["message_lose"] = hex2bin("3605050D35040105");
		
		// Trainer 2
		$placeholderTrainer7[5] = array();
		$placeholderTrainer7[5]["name"] = hex2bin("8C949180505050505050");
		$placeholderTrainer7[5]["class"] = hexdec("29");
		$placeholderTrainer7[5]["pokemon1"] = hex2bin("80AE5922E73F000007D000C350C350C3507530C350FDDE0A0F0F05640000005000000114011400E900DD00EC008700B7938094918E925050505050");
		$placeholderTrainer7[5]["pokemon2"] = hex2bin("83549C396D5E000007D000C350C350C350D6D8C350DFDB0A0F0A0A64000000500000016E016E00CD00C900A800CA00DA8B808F9180925050505050");
		$placeholderTrainer7[5]["pokemon3"] = hex2bin("F86D9CF2599D000007D000C350D6D8C350D6D8C350DFDB0A0F0A0A64000000500000013E013E011E00F900A900DA00E2939891808D889380915050");
		$placeholderTrainer7[5]["message_start"] = hex2bin("330B0006010B0E04");
		$placeholderTrainer7[5]["message_win"] = hex2bin("0A02050D07040C0D");
		$placeholderTrainer7[5]["message_lose"] = hex2bin("35060F0D1D060508");
		
		// Trainer 3
		$placeholderTrainer7[4] = array();
		$placeholderTrainer7[4]["name"] = hex2bin("8F80918A505050505050");
		$placeholderTrainer7[4]["class"] = hexdec("1C");
		$placeholderTrainer7[4]["pokemon1"] = hex2bin("5E0055F76DA8000007D000C350C350AFC8D6D8C350DEDD0F0F0A0A6400000050000000F700F700AD00A500F8011500BD86848D8680915050505050");
		$placeholderTrainer7[4]["pokemon2"] = hex2bin("CD92995C4CCF000007D000AFC8C350C350AFC8C350FDED050A0A0F640000005000000111011100D90125008500A500A5858E919184939184929250");
		$placeholderTrainer7[4]["pokemon3"] = hex2bin("E6549C393BE1000007D000AFC8C3509C40D6D8C350FBED0A0F0514640000005000000111011100E100D600D100DD00DD8A888D8683918050505050");
		$placeholderTrainer7[4]["message_start"] = hex2bin("270B1D0D320D2D08");
		$placeholderTrainer7[4]["message_win"] = hex2bin("38073D062C0D0301");
		$placeholderTrainer7[4]["message_lose"] = hex2bin("160B2D0D09090405");
		
		// Trainer 4
		$placeholderTrainer7[3] = array();
		$placeholderTrainer7[3]["name"] = hex2bin("86809393885050505050");
		$placeholderTrainer7[3]["class"] = hexdec("26");
		$placeholderTrainer7[3]["pokemon1"] = hex2bin("95AE563955C8000007D000C350C350C350C350AFC8DDDD140F0F0F64000000500000012F012F011C00DD00C500E400E4839180868E8D8893845050");
		$placeholderTrainer7[3]["pokemon2"] = hex2bin("E9925E693FA1000007D000D6D8C3509C40D6D8C350DFED0A14050A640000005000000125012500C500D500A900ED00DD8F8E9198868E8DF8505050");
		$placeholderTrainer7[3]["pokemon3"] = hex2bin("7C498E3B8A5E000007D000D6D8C350AFC8C350C350DFDF0A050F0A64000000500000010801080095007F00DD010100E189988D9750505050505050");
		$placeholderTrainer7[3]["message_start"] = hex2bin("1C0C18050E061708");
		$placeholderTrainer7[3]["message_win"] = hex2bin("0F0D28040706110A");
		$placeholderTrainer7[3]["message_lose"] = hex2bin("1F080F062A0D210C");
		
		// Trainer 5
		$placeholderTrainer7[2] = array();
		$placeholderTrainer7[2]["name"] = hex2bin("92848B8B925050505050");
		$placeholderTrainer7[2]["class"] = hexdec("18");
		$placeholderTrainer7[2]["pokemon1"] = hex2bin("E2AE396D3B11000007D0009C40AFC89C40AFC8C350DFDC0F0A05236400000050000001000100008400B500B400C401248C808D93888D8450505050");
		$placeholderTrainer7[2]["pokemon2"] = hex2bin("E349D313BD5C000007D000C350AFC888B8C350C350DDEF190F0A0A640000005000000102010200C4011E00B7008900B9928A80918C8E9198505050");
		$placeholderTrainer7[2]["pokemon3"] = hex2bin("928A358FD33F000007D000C3509C40C3509C40C350DDFE0F05190564000000500000012C012C00E100D500D5010F00CF8C8E8B9391849250505050");
		$placeholderTrainer7[2]["message_start"] = hex2bin("0904250B020E0E01");
		$placeholderTrainer7[2]["message_win"] = hex2bin("290D0E010B0D2004");
		$placeholderTrainer7[2]["message_lose"] = hex2bin("200D080114063608");
		
		// Trainer 6
		$placeholderTrainer7[1] = array();
		$placeholderTrainer7[1]["name"] = hex2bin("96848B8B505050505050");
		$placeholderTrainer7[1]["class"] = hexdec("3A");
		$placeholderTrainer7[1]["pokemon1"] = hex2bin("8E6D3F9C592E000007D000C3509C40C3509C40C350FFED050A0A1464000000500000011A011A00ED00B1011300A500BD8084918E83808293988B50");
		$placeholderTrainer7[1]["pokemon2"] = hex2bin("65525599F35C000007D000C350C3509C409C40C350FFEF0F05140A6400000050000000FA00FA009900B5012300C900C9848B848293918E83845050");
		$placeholderTrainer7[1]["pokemon3"] = hex2bin("338CA359A8BD000007D000C350C3509C40C3509C40FDDD140A0A0A6400000050000000D600D600C900910105009100B18394869391888E50505050");
		$placeholderTrainer7[1]["message_start"] = hex2bin("1B020D0D01051F0B");
		$placeholderTrainer7[1]["message_win"] = hex2bin("1D0E030816040405");
		$placeholderTrainer7[1]["message_lose"] = hex2bin("2E0D030803010105");
		
		// Trainer 7
		$placeholderTrainer7[0] = array();
		$placeholderTrainer7[0]["name"] = hex2bin("8F888282888D888D8850");
		$placeholderTrainer7[0]["class"] = hexdec("19");
		$placeholderTrainer7[0]["pokemon1"] = hex2bin("4749CABC3F5C000007D000753075307530753075306565050A050A000000005000000104010400D9009700A100CF008F9588829391848481848B50");
		$placeholderTrainer7[0]["pokemon2"] = hex2bin("7FAE3F42465C000007D00075307530753075307530746405190F0A0000000050000000F100F100FA00CD00B90085009D8F888D9288915050505050");
		$placeholderTrainer7[0]["pokemon3"] = hex2bin("D2032EF73F09000007D000753075307530753075307657140F050F00000000500000011E011E00F200A90077009200928691808D81948B8B505050");
		$placeholderTrainer7[0]["message_start"] = hex2bin("2C0D13091B0D1F04");
		$placeholderTrainer7[0]["message_win"] = hex2bin("010D3B0613040405");
		$placeholderTrainer7[0]["message_lose"] = hex2bin("0C052406110B0804");
		
		return $placeholderTrainer7[$number];
	}
	
	function getBattleTowerPlaceholderTrainerIT8($number) {
		$placeholderTrainer8 = array();
		
		// Trainer 1
		$placeholderTrainer8[6] = array();
		$placeholderTrainer8[6]["name"] = hex2bin("828E9293805050505050");
		$placeholderTrainer8[6]["class"] = hexdec("41");
		$placeholderTrainer8[6]["pokemon1"] = hex2bin("C552B9BDEC6D00000B1FA8EA60EA60D6D8EA60D6D8FDED140A050A640000005A0000015D015D00CB011600CA00BC013A948C8191848E8D50505050");
		$placeholderTrainer8[6]["pokemon2"] = hex2bin("95497E3FC83B00000B1FA8FDE8C350DAC0EA60EA60FDED05050F05640000005A000001570157014200FC00E501070107839180868E8D8893845050");
		$placeholderTrainer8[6]["pokemon3"] = hex2bin("79926955395E00000B1FA8EA60EA60EA60D6D8EA60DFDD140F0F0A640000005A00000121012100DA00EF011F010700EC929380918C888450505050");
		$placeholderTrainer8[6]["message_start"] = hex2bin("010D200404050C04");
		$placeholderTrainer8[6]["message_win"] = hex2bin("0502050D07040105");
		$placeholderTrainer8[6]["message_lose"] = hex2bin("3506010D3E040605");
		
		// Trainer 2
		$placeholderTrainer8[5] = array();
		$placeholderTrainer8[5]["name"] = hex2bin("938E9288505050505050");
		$placeholderTrainer8[5]["class"] = hexdec("21");
		$placeholderTrainer8[5]["pokemon1"] = hex2bin("5B92993B39C400000B1FA8C350C350C350C350C350DBDF05050F0F640000005A0000010A010A00F8018E00CB00EA00A2828B8E9892938491505050");
		$placeholderTrainer8[5]["pokemon2"] = hex2bin("A9AE11723FCA00000B1FA8C350C350C350C350C350FDCF231E0505640000005A00000145014500F300DD013600CF00E182918E8180935050505050");
		$placeholderTrainer8[5]["pokemon3"] = hex2bin("E9495C5E69B600000B1FA8C350AFC8C350D6D8C350FDED0A0A140A640000005A00000145014500DF00EF00BE010A00F88F8E9198868E8DF8505050");
		$placeholderTrainer8[5]["message_start"] = hex2bin("0F0D010E24081F0B");
		$placeholderTrainer8[5]["message_win"] = hex2bin("2408050D07040105");
		$placeholderTrainer8[5]["message_lose"] = hex2bin("0D0D010D24080105");
		
		// Trainer 3
		$placeholderTrainer8[4] = array();
		$placeholderTrainer8[4]["name"] = hex2bin("96808B8A849150505050");
		$placeholderTrainer8[4]["class"] = hexdec("17");
		$placeholderTrainer8[4]["pokemon1"] = hex2bin("E692E1393F3B00000B1FA8C350C350D6D8C350AFC8DFDE140F0505640000005A00000135013500F800FF00E600F800F88A888D8683918050505050");
		$placeholderTrainer8[4]["pokemon2"] = hex2bin("F8493FF2599D00000B1FA8C350D6D8C350AFC8C350DFDE050F0A0A640000005A0000016201620141011700B900FA0103939891808D889380915050");
		$placeholderTrainer8[4]["pokemon3"] = hex2bin("83549C39555E00000B1FA8AFC8C350C350C350D6D8BDEF0A0F0F0A640000005A00000195019500E300DD00BB00ED00FF8B808F9180925050505050");
		$placeholderTrainer8[4]["message_start"] = hex2bin("1E09290D310D3004");
		$placeholderTrainer8[4]["message_win"] = hex2bin("35060F0D20040405");
		$placeholderTrainer8[4]["message_lose"] = hex2bin("0C0D35061E093F07");
		
		// Trainer 4
		$placeholderTrainer8[3] = array();
		$placeholderTrainer8[3]["name"] = hex2bin("8C809884915050505050");
		$placeholderTrainer8[3]["class"] = hexdec("27");
		$placeholderTrainer8[3]["pokemon1"] = hex2bin("C4AE5EF7F1EA00000B1FA8D6D8C350C350D6D8C350DDFE0A0F0505640000005A00000126012600C200B9011A013900FA84928F848E8D5050505050");
		$placeholderTrainer8[3]["pokemon2"] = hex2bin("4449EEE97E5900000B1FA8D6D8D6D8C350D6D8C350DDED050A050A640000005A000001510151013A00DD00B500C200E68C808287808C8F50505050");
		$placeholderTrainer8[3]["pokemon3"] = hex2bin("8F6D7E39593F00000B1FA8AFC8C350D6D8C350C350FEFD050F0A05640000005A000001C701C7011700C7008700C20113928D8E918B809750505050");
		$placeholderTrainer8[3]["message_start"] = hex2bin("1F0B050D2E0D1604");
		$placeholderTrainer8[3]["message_win"] = hex2bin("170516043506170C");
		$placeholderTrainer8[3]["message_lose"] = hex2bin("3506120B010B0105");
		
		// Trainer 5
		$placeholderTrainer8[2] = array();
		$placeholderTrainer8[2]["name"] = hex2bin("898E878D928E8D505050");
		$placeholderTrainer8[2]["class"] = hexdec("16");
		$placeholderTrainer8[2]["pokemon1"] = hex2bin("3B54F135F59C00000B1FA8C350AFC8C350AFC8D6D8DFDE050F050A640000005A000001500150011200E100F7010600E2809182808D888D84505050");
		$placeholderTrainer8[2]["pokemon2"] = hex2bin("F2924CF1877E00000B1FA8C350AFC8C350C350C350BDFE0A050A05640000005A000001A801A8005A005F00B400D60142818B889292849850505050");
		$placeholderTrainer8[2]["pokemon3"] = hex2bin("E50335F2F14C00000B1FA8C3509C40C350C350C350DBFE0F0F050A640000005A00000135013500EB00A400FC011500DF878E948D838E8E8C505050");
		$placeholderTrainer8[2]["message_start"] = hex2bin("0F0D3E040B060904");
		$placeholderTrainer8[2]["message_win"] = hex2bin("410D30040F0D2104");
		$placeholderTrainer8[2]["message_lose"] = hex2bin("240D0F0D090B1B0E");
		
		// Trainer 6
		$placeholderTrainer8[1] = array();
		$placeholderTrainer8[1]["name"] = hex2bin("8083808C925050505050");
		$placeholderTrainer8[1]["class"] = hexdec("2B");
		$placeholderTrainer8[1]["pokemon1"] = hex2bin("E349C9D35CD800000B1FA8C350C350C350C350C350EFF70A190A14FF0000005A00000117011700DF014D00CF008B00C1928A80918C8E9198505050");
		$placeholderTrainer8[1]["pokemon2"] = hex2bin("D5925C23B6E300000B1FA8C350C350C350C350C350FEFE0A140A05640000005A000000CB00CB006301ED005A006101ED928794828A8B8450505050");
		$placeholderTrainer8[1]["pokemon3"] = hex2bin("88543F35F72E00000B1FA8C350C350C350C350C350F7F7050F0F14640000005A000001250125013B00AF00C600EE0109858B8091848E8D50505050");
		$placeholderTrainer8[1]["message_start"] = hex2bin("3705010538050B05");
		$placeholderTrainer8[1]["message_win"] = hex2bin("2505020D31050B05");
		$placeholderTrainer8[1]["message_lose"] = hex2bin("0F05020D20050005");
		
		// Trainer 7
		$placeholderTrainer8[0] = array();
		$placeholderTrainer8[0]["name"] = hex2bin("8C889388505050505050");
		$placeholderTrainer8[0]["class"] = hexdec("24");
		$placeholderTrainer8[0]["pokemon1"] = hex2bin("F192D059D52200000B1FA87530753075307530753047570A0A0F0F000000005A00000142014200C200F500E8008000B68C888B93808D8A50505050");
		$placeholderTrainer8[0]["pokemon2"] = hex2bin("8068553FD55900000B1FA87530753075307530753065760F050F0A000000005A0000011C011C00EA00DF00FE007E00B4938094918E925050505050");
		$placeholderTrainer8[0]["pokemon3"] = hex2bin("59495CBCD5CA00000B1FA87530753075307530753054440A0A0F05000000005A00000156015600F100B9008C00A700E68C948A5050505050505050");
		$placeholderTrainer8[0]["message_start"] = hex2bin("0C0D35060C040105");
		$placeholderTrainer8[0]["message_win"] = hex2bin("2E0D190431080105");
		$placeholderTrainer8[0]["message_lose"] = hex2bin("3204010D010E2004");
		
		return $placeholderTrainer8[$number];
	}
	
	function getBattleTowerPlaceholderTrainerIT9($number) {
		$placeholderTrainer9 = array();
		
		// Trainer 1
		$placeholderTrainer9[6] = array();
		$placeholderTrainer9[6]["name"] = hex2bin("93849288505050505050");
		$placeholderTrainer9[6]["class"] = hexdec("24");
		$placeholderTrainer9[6]["pokemon1"] = hex2bin("E554F2352E9C00000F4240EA60EA60EA60EA60EA60FDED0F0F140A64000000640000015B015B011400C0011C013800FC878E948D838E8E8C505050");
		$placeholderTrainer9[6]["pokemon2"] = hex2bin("4449EE593FE900000F4240EA60EA60EA60EA60EA60FDEF050A050A6400000064000001790179016400FC00CC00E2010A8C808287808C8F50505050");
		$placeholderTrainer9[6]["pokemon3"] = hex2bin("E69239E19C5C00000F4240EA60EA60EA60EA60EA60DFFE0F140A0A64000000640000015D015D011A011E010A011C011C8A888D8683918050505050");
		$placeholderTrainer9[6]["message_start"] = hex2bin("25050F0D14062004");
		$placeholderTrainer9[6]["message_win"] = hex2bin("2B0D0B0D32071805");
		$placeholderTrainer9[6]["message_lose"] = hex2bin("1805010E2E0D2D07");
		
		// Trainer 2
		$placeholderTrainer9[5] = array();
		$placeholderTrainer9[5]["name"] = hex2bin("86888D88505050505050");
		$placeholderTrainer9[5]["class"] = hexdec("1E");
		$placeholderTrainer9[5]["pokemon1"] = hex2bin("8703552E56E700000F4240C350C350C3507530C350FDFE0F14140F640000006400000143014300DC00CE015201340116898E8B93848E8D50505050");
		$placeholderTrainer9[5]["pokemon2"] = hex2bin("80523F59E75500000F4240C350C350C350C350C350FDEF050A0F0F640000006400000155015501220114013400AA00E6938094918E925050505050");
		$placeholderTrainer9[5]["pokemon3"] = hex2bin("3B9235F5E73F00000F4240D6D8C350C350D6D8C350DDEF0F050F056400000064000001760176013200F60119012200FA809182808D888D84505050");
		$placeholderTrainer9[5]["message_start"] = hex2bin("2C0D03010B0D2A07");
		$placeholderTrainer9[5]["message_win"] = hex2bin("010E2E0D13080301");
		$placeholderTrainer9[5]["message_lose"] = hex2bin("0E06360818040105");
		
		// Trainer 3
		$placeholderTrainer9[4] = array();
		$placeholderTrainer9[4]["name"] = hex2bin("8E919288505050505050");
		$placeholderTrainer9[4]["class"] = hexdec("14");
		$placeholderTrainer9[4]["pokemon1"] = hex2bin("068C3559A31300000F4240C350C350D6D8D6D8D6D8FEDF0F0A140F6400000064000001570157010200F70121013701078287809188998091835050");
		$placeholderTrainer9[4]["pokemon2"] = hex2bin("6503565599F300000F4240AFC8C350C350AFC8AFC8FBEF140F0514640000006400000135013500BE00DE016E00F800F8848B848293918E83845050");
		$placeholderTrainer9[4]["pokemon3"] = hex2bin("706D39593F9D00000F4240D6D8C350D6D8C350AFC8FDEF0F0A050A6400000064000001940194015E014900A800B200B2918798838E8D5050505050");
		$placeholderTrainer9[4]["message_start"] = hex2bin("0F0D1D062C08020D");
		$placeholderTrainer9[4]["message_win"] = hex2bin("0B0D1E0C00060A0A");
		$placeholderTrainer9[4]["message_lose"] = hex2bin("100B3F0B0104020D");
		
		// Trainer 4
		$placeholderTrainer9[3] = array();
		$placeholderTrainer9[3]["name"] = hex2bin("928C8091935050505050");
		$placeholderTrainer9[3]["class"] = hexdec("29");
		$placeholderTrainer9[3]["pokemon1"] = hex2bin("D092593FCFF200000F4240C350C350D6D8EA60C350FDDE0A050F0F6400000064000001570157010401E9009800C600DA929384848B889750505050");
		$placeholderTrainer9[3]["pokemon2"] = hex2bin("165241D33FBD00000F4240D6D8C350C350D6D8C350FDCF1419050A6400000064000001440144010E00D8011F00D400D4858480918E965050505050");
		$placeholderTrainer9[3]["pokemon3"] = hex2bin("C877C3D4DCF700000F4240AFC8C350D6D8C350D6D8BDEF0505140F640000006400000135013500CA00D10102010701078C88928391848095949250");
		$placeholderTrainer9[3]["message_start"] = hex2bin("3905100B020D0405");
		$placeholderTrainer9[3]["message_win"] = hex2bin("0C050B0D14080405");
		$placeholderTrainer9[3]["message_lose"] = hex2bin("3905020D3905020D");
		
		// Trainer 5
		$placeholderTrainer9[2] = array();
		$placeholderTrainer9[2]["name"] = hex2bin("8191948D885050505050");
		$placeholderTrainer9[2]["class"] = hexdec("27");
		$placeholderTrainer9[2]["pokemon1"] = hex2bin("D78CA33B8AB900000F4240C350C350BB80AFC8C350FDEF14050F1464000000640000012D012D011800C3013C00A000F0928D848092848B50505050");
		$placeholderTrainer9[2]["pokemon2"] = hex2bin("D449D33FA35C00000F4240C350C350C350C350AFC8FBFE1905140A64000000640000014D014D015E011A00DC00C400F6928288998E915050505050");
		$placeholderTrainer9[2]["pokemon3"] = hex2bin("F292553B7E8700000F4240C3509C40C35075307530DDFE0F05050A6400000064000002BF02BF0065006A00BC00E2015A818B889292849850505050");
		$placeholderTrainer9[2]["message_start"] = hex2bin("3C042D0D170A3F08");
		$placeholderTrainer9[2]["message_win"] = hex2bin("000D08092E0D3304");
		$placeholderTrainer9[2]["message_lose"] = hex2bin("0C050E063304020D");
		
		// Trainer 6
		$placeholderTrainer9[1] = array();
		$placeholderTrainer9[1]["name"] = hex2bin("898E9183808D50505050");
		$placeholderTrainer9[1]["class"] = hexdec("2D");
		$placeholderTrainer9[1]["pokemon1"] = hex2bin("DD549C3B3F5900000F4240C350C350C350C350C350FEF70A05050A6400000064000001830183012200F800BE00C200C28F888B8E9296888D845050");
		$placeholderTrainer9[1]["pokemon2"] = hex2bin("67495E5C99CA00000F4240C350C350C350C350C350FEFE0A0A050564000000640000017701770118010200C8015200DA849784868694938E915050");
		$placeholderTrainer9[1]["pokemon3"] = hex2bin("8B9239F63B5C00000F4240C350C350C350C350C350FBE70F05050A64000000640000014B014B00D2014C00C6013000D68E8C809293809150505050");
		$placeholderTrainer9[1]["message_start"] = hex2bin("35061A0D110B1004");
		$placeholderTrainer9[1]["message_win"] = hex2bin("0C0D1C0B3B061904");
		$placeholderTrainer9[1]["message_lose"] = hex2bin("0F061A0D000D3C07");
		
		// Trainer 7
		$placeholderTrainer9[0] = array();
		$placeholderTrainer9[0]["name"] = hex2bin("918899998E5050505050");
		$placeholderTrainer9[0]["class"] = hexdec("30");
		$placeholderTrainer9[0]["pokemon1"] = hex2bin("4C0399599D7E00000F4240753075307530753075307446050A0A050000000064000001490149011A013C009200AA00BE868E8B848C505050505050");
		$placeholderTrainer9[0]["pokemon2"] = hex2bin("6B774407090800000F4240753075307530753075306776140F0F0F0000000064000001090109010E00DC00D6008201188788938C8E8D8287808D50");
		$placeholderTrainer9[0]["pokemon3"] = hex2bin("AB4939F0C06D00000F42407530753075307530753076570F05050A0000000064000001A901A900B200B000C000D600D68B808D9394918D50505050");
		$placeholderTrainer9[0]["message_start"] = hex2bin("1B02050B2E0D0301");
		$placeholderTrainer9[0]["message_win"] = hex2bin("13050E061F040105");
		$placeholderTrainer9[0]["message_lose"] = hex2bin("3204010E2E0D3304");
		
		return $placeholderTrainer9[$number];
	}
	function getBattleTowerPlaceholderTrainerES($number) {
		$placeholderTrainer0 = array();
		
		// Trainer 1
		$placeholderTrainer0[6] = array();
		$placeholderTrainer0[6]["name"] = hex2bin("87808D928E8D50505050");
		$placeholderTrainer0[6]["class"] = hexdec("25");
		$placeholderTrainer0[6]["pokemon1"] = hex2bin("876D553FF72E00000003E8C3509C409C4088B89C40DDBD0F050F14640000000A0000002900290019001800250022001F898E8B93848E8D50505050");
		$placeholderTrainer0[6]["pokemon2"] = hex2bin("C492BD5EF45C00000003E89C40C35088B89C409C40EDFB0A0A0A0A640000000A000000270027001A001800230026001F84928F848E8D5050505050");
		$placeholderTrainer0[6]["pokemon3"] = hex2bin("C5AEF7E7F45C00000003E89C409C40AFC8C3509C40DBEF0F0F0A0A640000000A0000002E002E00190022001A00190027948C8191848E8D50505050");
		$placeholderTrainer0[6]["message_start"] = hex2bin("0E0900063D063004");
		$placeholderTrainer0[6]["message_win"] = hex2bin("3D063004100D0D08");
		$placeholderTrainer0[6]["message_lose"] = hex2bin("100D0D080B061306");
		
		// Trainer 2
		$placeholderTrainer0[5] = array();
		$placeholderTrainer0[5]["name"] = hex2bin("928E9880505050505050");
		$placeholderTrainer0[5]["class"] = hexdec("1E");
		$placeholderTrainer0[5]["pokemon1"] = hex2bin("CA7744F3DBC200000003E8C350C350C350C350C3507FD714141905640000000A00000042004200120019001300120017968E818194858584935050");
		$placeholderTrainer0[5]["pokemon2"] = hex2bin("736DB33F59D500000003E89C4075309C4075307530EFCF0F050A0F640000000A0000002F002F001F001D001D0014001C8A808D8680928A87808D50");
		$placeholderTrainer0[5]["pokemon3"] = hex2bin("DE8C395E69F600000003E89C407530821475307530FEFD0F0A1405640000000A0000002600260017001D00130018001C828E91928E8B8050505050");
		$placeholderTrainer0[5]["message_start"] = hex2bin("0C0ECA002D030208");
		$placeholderTrainer0[5]["message_win"] = hex2bin("3405CA00050C0504");
		$placeholderTrainer0[5]["message_lose"] = hex2bin("3505CA00280B1D04");
		
		// Trainer 3
		$placeholderTrainer0[4] = array();
		$placeholderTrainer0[4]["name"] = hex2bin("8C809294505050505050");
		$placeholderTrainer0[4]["class"] = hexdec("2B");
		$placeholderTrainer0[4]["pokemon1"] = hex2bin("F1AE3B593F5C00000003E8753075307530753088B8BBDF050A050A640000000A0000002E002E001B0020001F0014001A8C888B93808D8A50505050");
		$placeholderTrainer0[4]["pokemon2"] = hex2bin("8E923F30592C00000003E875307530753075307530DBFB05140A19640000000A0000002B002B0020001800260017001A8084918E83808293988B50");
		$placeholderTrainer0[4]["pokemon3"] = hex2bin("836D3B39555E00000003E875307530753075307530FDEB050F0F0A640000000A000000340034001D001B0018001C001E8B808F9180925050505050");
		$placeholderTrainer0[4]["message_start"] = hex2bin("03050F0D1B070405");
		$placeholderTrainer0[4]["message_win"] = hex2bin("100E3D040D0D0807");
		$placeholderTrainer0[4]["message_lose"] = hex2bin("030D050B36043605");
		
		// Trainer 4
		$placeholderTrainer0[3] = array();
		$placeholderTrainer0[3]["name"] = hex2bin("8D88828A985050505050");
		$placeholderTrainer0[3]["class"] = hexdec("14");
		$placeholderTrainer0[3]["pokemon1"] = hex2bin("D7AEA3B9393B00000003E8753088B8753075307530FBBF14140F05640000000A000000260026001F001600220013001B928D848092848B50505050");
		$placeholderTrainer0[3]["pokemon2"] = hex2bin("E9035E3B3FA100000003E8753075309C4075307530FBDE0A05050A640000000A0000002C002C001C001E00170021001F8F8E9198868E8DF8505050");
		$placeholderTrainer0[3]["pokemon3"] = hex2bin("C877C3D4DCF700000003E875307530753075307530EFDF0505140F640000000A00000025002500180018001C001D001D8C88928391848095949250");
		$placeholderTrainer0[3]["message_start"] = hex2bin("050C010D04082804");
		$placeholderTrainer0[3]["message_win"] = hex2bin("2804080E0E052C04");
		$placeholderTrainer0[3]["message_lose"] = hex2bin("1D06040806061D04");
		
		// Trainer 5
		$placeholderTrainer0[2] = array();
		$placeholderTrainer0[2]["name"] = hex2bin("8E8B928E8D5050505050");
		$placeholderTrainer0[2]["class"] = hexdec("3B");
		$placeholderTrainer0[2]["pokemon1"] = hex2bin("E4AEB94C2EF100000003E875307530753080E87530FDFE140A1405640000000A000000240024001800110019001C0016878E948D838E9491505050");
		$placeholderTrainer0[2]["pokemon2"] = hex2bin("CB523CBDF76100000003E875307530753075307530EDFD140A0F1E640000000A000000270027001C0018001D001D00188688918085809188865050");
		$placeholderTrainer0[2]["pokemon3"] = hex2bin("F2491D4CCD4600000003E87D009C40753075307530DFCE0F0A140F640000000A0000004D004D000E000E0016001B0027818B889292849850505050");
		$placeholderTrainer0[2]["message_start"] = hex2bin("1500060424061904");
		$placeholderTrainer0[2]["message_win"] = hex2bin("2E00030606043205");
		$placeholderTrainer0[2]["message_lose"] = hex2bin("0B0018060006010A");
		
		// Trainer 6
		$placeholderTrainer0[1] = array();
		$placeholderTrainer0[1]["name"] = hex2bin("9980818E918050505050");
		$placeholderTrainer0[1]["class"] = hexdec("19");
		$placeholderTrainer0[1]["pokemon1"] = hex2bin("8F6D1DB6AD3900000003E875307530753075307530EFF70F0A0F0F640000000A00000039003900220019001200170020928D8E918B809750505050");
		$placeholderTrainer0[1]["pokemon2"] = hex2bin("67525CCAA85D00000003E875307530753075307530FEFE0A050A19640000000A0000002D002D001F001D001700250019849784868694938E915050");
		$placeholderTrainer0[1]["pokemon3"] = hex2bin("D6AEB3CB44F900000003E875307530753075307530F7F70F0A140F640000000A0000002B002B00250019001D0012001D8784918082918E92925050");
		$placeholderTrainer0[1]["message_start"] = hex2bin("0C0D390600030202");
		$placeholderTrainer0[1]["message_win"] = hex2bin("1405070608070D0D");
		$placeholderTrainer0[1]["message_lose"] = hex2bin("02050B0601053505");
		
		// Trainer 7
		$placeholderTrainer0[0] = array();
		$placeholderTrainer0[0]["name"] = hex2bin("91948F8491938E505050");
		$placeholderTrainer0[0]["class"] = hexdec("16");
		$placeholderTrainer0[0]["pokemon1"] = hex2bin("C9ADED00000000000003E875307530753075307530FFFF0F000000000000000A000000240024001A00150015001A0015948D8E968D505050505050");
		$placeholderTrainer0[0]["pokemon2"] = hex2bin("80521DCF27C400000003E87530753075307530753065570F0F1E0F000000000A000000280028001E001D002000120018938094918E925050505050");
		$placeholderTrainer0[0]["pokemon3"] = hex2bin("7A495CF4071D00000003E87530753075307530753073670A0A0F0F000000000A00000022002200130016001C001E00228C91E88C888C8450505050");
		$placeholderTrainer0[0]["message_start"] = hex2bin("0202030526080405");
		$placeholderTrainer0[0]["message_win"] = hex2bin("01020C060C0E2C04");
		$placeholderTrainer0[0]["message_lose"] = hex2bin("01020D0D1D060208");
		
		return $placeholderTrainer0[$number];
	}
	
	function getBattleTowerPlaceholderTrainerES1($number) {
		$placeholderTrainer1 = array();
		
		// Trainer 1
		$placeholderTrainer1[6] = array();
		$placeholderTrainer1[6]["name"] = hex2bin("808B84898E5050505050");
		$placeholderTrainer1[6]["class"] = hexdec("2C");
		$placeholderTrainer1[6]["pokemon1"] = hex2bin("C592B65CBDD50000001F40C350C350C350C350C350CFBC0A0A0A0F6400000014000000510051002E0042002E002C0048948C8191848E8D50505050");
		$placeholderTrainer1[6]["pokemon2"] = hex2bin("79AE695E39F40000001F40C350C350C350C350C350DBDB140A0F0A6400000014000000470047003300360043003C0036929380918C888450505050");
		$placeholderTrainer1[6]["pokemon3"] = hex2bin("826D3F52557E0000001F40C350C350C350C350C350FAFD050A0F056400000014000000530053004800330036002D003D8698809180838E92505050");
		$placeholderTrainer1[6]["message_start"] = hex2bin("0D022E040E060D06");
		$placeholderTrainer1[6]["message_win"] = hex2bin("02051B020105440B");
		$placeholderTrainer1[6]["message_lose"] = hex2bin("02051B020105120E");
		
		// Trainer 2
		$placeholderTrainer1[5] = array();
		$placeholderTrainer1[5]["name"] = hex2bin("8A8096808A808C885050");
		$placeholderTrainer1[5]["class"] = hexdec("22");
		$placeholderTrainer1[5]["pokemon1"] = hex2bin("D0AE2EE7CF590000001F40C350AFC8C3507530C350FFFF140F0F0A64000000140000004D004D00370066001F002C0030929384848B889750505050");
		$placeholderTrainer1[5]["pokemon2"] = hex2bin("418B5EF45C090000001F40C350C3507530C3509C40FDEF0A0A0A0F6400000014000000440044002A00240045004B0037808B808A8099808C505050");
		$placeholderTrainer1[5]["pokemon3"] = hex2bin("3B03352E3FE70000001F4088B8AFC8C350D6D8C350DBFB0F14050F640000001400000051005100400034003C003C0034809182808D888D84505050");
		$placeholderTrainer1[5]["message_start"] = hex2bin("0202270D0C0E2804");
		$placeholderTrainer1[5]["message_win"] = hex2bin("07041E062804220C");
		$placeholderTrainer1[5]["message_lose"] = hex2bin("0D0D260626040C05");
		
		// Trainer 3
		$placeholderTrainer1[4] = array();
		$placeholderTrainer1[4]["name"] = hex2bin("958E8B838E5050505050");
		$placeholderTrainer1[4]["class"] = hexdec("3B");
		$placeholderTrainer1[4]["pokemon1"] = hex2bin("D677CBB3E0590000001F40C3507530AFC87530AFC8DFDE0A0F0A0A64000000140000004E004E0044003300340025003B8784918082918E92925050");
		$placeholderTrainer1[4]["pokemon2"] = hex2bin("67923F5E5C8A0000001F40AFC8C350C350AFC8AFC8FDEB050A0A0F6400000014000000530053003C0037002B0046002E849784868694938E915050");
		$placeholderTrainer1[4]["pokemon3"] = hex2bin("8EAE9C3F59520000001F40AFC8C3509C40C350AFC8FBBB0A050A0A64000000140000004E004E0040002D0048002C00328084918E83808293988B50");
		$placeholderTrainer1[4]["message_start"] = hex2bin("23070C0E2C04220C");
		$placeholderTrainer1[4]["message_win"] = hex2bin("02031C0E150D0A04");
		$placeholderTrainer1[4]["message_lose"] = hex2bin("190509050D0D0208");
		
		// Trainer 4
		$placeholderTrainer1[3] = array();
		$placeholderTrainer1[3]["name"] = hex2bin("8A888C50505050505050");
		$placeholderTrainer1[3]["class"] = hexdec("3C");
		$placeholderTrainer1[3]["pokemon1"] = hex2bin("F2035E4287440000001F40C350C35075307530C350BDFE0A190A1464000000140000009400940018001600290033004B818B889292849850505050");
		$placeholderTrainer1[3]["pokemon2"] = hex2bin("83AE5E553B6D0000001F40D6D875309C40D6D87530FED70A0F050A640000001400000062006200350034002D003200368B808F9180925050505050");
		$placeholderTrainer1[3]["pokemon3"] = hex2bin("19A35556465C0000001F40AFC8C350AFC8C350C350FCFE0F140F0A64000000140000003A003A002C0020003A002900258F888A8082879450505050");
		$placeholderTrainer1[3]["message_start"] = hex2bin("3F050006270D180E");
		$placeholderTrainer1[3]["message_win"] = hex2bin("0B05090B1D063D04");
		$placeholderTrainer1[3]["message_lose"] = hex2bin("1C0E010D1F0E4304");
		
		// Trainer 5
		$placeholderTrainer1[2] = array();
		$placeholderTrainer1[2]["name"] = hex2bin("8F808D50505050505050");
		$placeholderTrainer1[2]["class"] = hexdec("3A");
		$placeholderTrainer1[2]["pokemon1"] = hex2bin("D477D3A35CC90000001F409C40AFC89C40AFC8C350FDFE19140A0A64000000140000004900490049003C002F002B0035928288998E915050505050");
		$placeholderTrainer1[2]["pokemon2"] = hex2bin("6BAE090807050000001F40C350AFC888B8C3507530FBFD0F0F0F146400000014000000430043003F003200340020003E8788938C8E8D8287808D50");
		$placeholderTrainer1[2]["pokemon3"] = hex2bin("800355593F3B0000001F40C3509C40C35075307530FBEF0F0A050564000000140000004C004C003D003A003F0023002F938094918E925050505050");
		$placeholderTrainer1[2]["message_start"] = hex2bin("260628043C040202");
		$placeholderTrainer1[2]["message_win"] = hex2bin("3C04300408072002");
		$placeholderTrainer1[2]["message_lose"] = hex2bin("12050C0D26060807");
		
		// Trainer 6
		$placeholderTrainer1[1] = array();
		$placeholderTrainer1[1]["name"] = hex2bin("83C98099505050505050");
		$placeholderTrainer1[1]["class"] = hexdec("35");
		$placeholderTrainer1[1]["pokemon1"] = hex2bin("B85F393BD5F00000001F409C409C409C409C409C40EDF70F050F056400000014000000520052002800340029002500318099948C8091888B8B5050");
		$placeholderTrainer1[1]["pokemon2"] = hex2bin("F1525957D5390000001F409C409C409C409C409C40DFFE0A0A0F0F64000000140000005300530034003F003D002400308C888B93808D8A50505050");
		$placeholderTrainer1[1]["pokemon3"] = hex2bin("28AE3F3B7ED50000001F409C409C409C409C409C40C7FE0505050F6400000014000000620062002F0023002700320028968886868B989394858550");
		$placeholderTrainer1[1]["message_start"] = hex2bin("1D053E05100D3004");
		$placeholderTrainer1[1]["message_win"] = hex2bin("100D0E0E14080C05");
		$placeholderTrainer1[1]["message_lose"] = hex2bin("0902010D28071B02");
		
		// Trainer 7
		$placeholderTrainer1[0] = array();
		$placeholderTrainer1[0]["name"] = hex2bin("849188828A5050505050");
		$placeholderTrainer1[0]["class"] = hexdec("2D");
		$placeholderTrainer1[0]["pokemon1"] = hex2bin("28685ECFF41D0000001F4075307530753075307530C7770A0F0A0F0000000014000000610061002E00220022002E0024968886868B989394858550");
		$placeholderTrainer1[0]["pokemon2"] = hex2bin("22AD3B5939090000001F40753075307530753075305646050A0F0F00000000140000004A004A0034002E00310032002E8D88838E8A888D86505050");
		$placeholderTrainer1[0]["pokemon3"] = hex2bin("C349855939F00000001F40753075307530753075305547140A0F05000000001400000051005100310031001D002A002A9094808692889184505050");
		$placeholderTrainer1[0]["message_start"] = hex2bin("2E063004100D0F08");
		$placeholderTrainer1[0]["message_win"] = hex2bin("0B021C0E150D0A04");
		$placeholderTrainer1[0]["message_lose"] = hex2bin("0205010D15050105");
		
		return $placeholderTrainer1[$number];
	}
	
	function getBattleTowerPlaceholderTrainerES2($number) {
		$placeholderTrainer2 = array();
		
		// Trainer 1
		$placeholderTrainer2[6] = array();
		$placeholderTrainer2[6]["name"] = hex2bin("85808891505050505050");
		$placeholderTrainer2[6]["class"] = hexdec("31");
		$placeholderTrainer2[6]["pokemon1"] = hex2bin("876D55562EBD0000006978C350C350C350C350D6D8DBED0F14140A640000001E00000067006700440040006B00600057898E8B93848E8D50505050");
		$placeholderTrainer2[6]["pokemon2"] = hex2bin("3E0368395A420000006978C350D6D8D6D8D6D8C350DDFB0F0F0519640000001E000000770077005100570049004600528F8E8B8896918093875050");
		$placeholderTrainer2[6]["pokemon3"] = hex2bin("7992565E69390000006978C350C350C350C350C350FFFF140A140F640000001E000000650065004B00510063005A0051929380918C888450505050");
		$placeholderTrainer2[6]["message_start"] = hex2bin("080E260600030202");
		$placeholderTrainer2[6]["message_win"] = hex2bin("09051C0E1E0D3603");
		$placeholderTrainer2[6]["message_lose"] = hex2bin("110524051A021E02");
		
		// Trainer 2
		$placeholderTrainer2[5] = array();
		$placeholderTrainer2[5]["name"] = hex2bin("8C948585985050505050");
		$placeholderTrainer2[5]["class"] = hexdec("3E");
		$placeholderTrainer2[5]["pokemon1"] = hex2bin("7CAE3B8E8AD50000006978C350C350C3507530C350FBEE050A0F0F640000001E000000660066003C003100530062005689988D9750505050505050");
		$placeholderTrainer2[5]["pokemon2"] = hex2bin("335259BCA3BD0000006978C350C3507530C350C350EFFF0A0A140A640000001E000000510051004D00380066003C00488394869391888E50505050");
		$placeholderTrainer2[5]["pokemon3"] = hex2bin("B603CAF14C680000006978AFC8AFC8C350D6D8C350DFDB05050A0F640000001E0000006D006D004C0051003C0052005881848B8B8E92928E8C5050");
		$placeholderTrainer2[5]["message_start"] = hex2bin("1D053E05100D3004");
		$placeholderTrainer2[5]["message_win"] = hex2bin("1C0E100D35031408");
		$placeholderTrainer2[5]["message_lose"] = hex2bin("0D0D21061D062F07");
		
		// Trainer 3
		$placeholderTrainer2[4] = array();
		$placeholderTrainer2[4]["name"] = hex2bin("89948D88505050505050");
		$placeholderTrainer2[4]["class"] = hexdec("30");
		$placeholderTrainer2[4]["pokemon1"] = hex2bin("F2925C7387B60000006978C3507530AFC87530AFC8FBED0A140A0A640000001E000000D900D900200021003B0049006D818B889292849850505050");
		$placeholderTrainer2[4]["pokemon2"] = hex2bin("E58A35F2F78A0000006978AFC8C350C350AFC8AFC8FDED0F0F0F0F640000001E0000006C006C0054003B0056005E004C878E948D838E8E8C505050");
		$placeholderTrainer2[4]["pokemon3"] = hex2bin("446DEE08597E0000006978AFC8C3509C40C350AFC8FDBE050F0A05640000001E000000760076006C004B003D004400508C808287808C8F50505050");
		$placeholderTrainer2[4]["message_start"] = hex2bin("1E051D051E064304");
		$placeholderTrainer2[4]["message_win"] = hex2bin("310513051A061A0D");
		$placeholderTrainer2[4]["message_lose"] = hex2bin("1B0216050C0D2304");
		
		// Trainer 4
		$placeholderTrainer2[3] = array();
		$placeholderTrainer2[3]["name"] = hex2bin("89809584805050505050");
		$placeholderTrainer2[3]["class"] = hexdec("27");
		$placeholderTrainer2[3]["pokemon1"] = hex2bin("A9AED56D5C110000006978C350C35075307530C350EFDC0F0A0A23640000001E0000006F006F0053004A00670046004C82918E8180935050505050");
		$placeholderTrainer2[3]["pokemon2"] = hex2bin("E9035E693FA10000006978D6D875309C40D6D87530DFDB0A14050A640000001E000000750075004900530042005700518F8E9198868E8DF8505050");
		$placeholderTrainer2[3]["pokemon3"] = hex2bin("697659D83F9B0000006978AFC8C350AFC8C3507530DDEB0A14050AFF0000001E000000630063004D005E0038003600488C80918E96808A50505050");
		$placeholderTrainer2[3]["message_start"] = hex2bin("0B021E0D30043607");
		$placeholderTrainer2[3]["message_win"] = hex2bin("320D2E0630043607");
		$placeholderTrainer2[3]["message_lose"] = hex2bin("38052D06320D0C0E");
		
		// Trainer 5
		$placeholderTrainer2[2] = array();
		$placeholderTrainer2[2]["name"] = hex2bin("8A8094858C808D505050");
		$placeholderTrainer2[2]["class"] = hexdec("26");
		$placeholderTrainer2[2]["pokemon1"] = hex2bin("65037155B65700000069789C40AFC89C40AFC8C350BDEF1E0F0A0A640000001E000000620062003900450071004E004E848B848293918E83845050");
		$placeholderTrainer2[2]["pokemon2"] = hex2bin("8392F037C4460000006978C350AFC888B8C3507530FDEB05190F0F640000001E0000008E008E0050004A0041004B00518B808F9180925050505050");
		$placeholderTrainer2[2]["pokemon3"] = hex2bin("ABAEF05739AF0000006978C3509C40C35075307530DDEB050A0F0F640000001E0000008B008B003E00400042004600468B808D9394918D50505050");
		$placeholderTrainer2[2]["message_start"] = hex2bin("150D270B0C0E4404");
		$placeholderTrainer2[2]["message_win"] = hex2bin("4404380B08061E04");
		$placeholderTrainer2[2]["message_lose"] = hex2bin("0C0E4404280B3304");
		
		// Trainer 6
		$placeholderTrainer2[1] = array();
		$placeholderTrainer2[1]["name"] = hex2bin("82809293805050505050");
		$placeholderTrainer2[1]["class"] = hexdec("21");
		$placeholderTrainer2[1]["pokemon1"] = hex2bin("C46D5D815CF40000006978AFC8C350C350C350C350EFF719140A0A640000001E0000006300630044004200600067005284928F848E8D5050505050");
		$placeholderTrainer2[1]["pokemon2"] = hex2bin("4952235CBC3D0000006978C350AFC8C350B798AFC8FEFE140A0A14640000001E0000006E006E00470044005A004D006593848D9380829194848B50");
		$placeholderTrainer2[1]["pokemon3"] = hex2bin("5EAEA87A65CA0000006978C350AFC8C350C350C350F7F70A1E0F05640000001E0000006500650044003D00600067004686848D8680915050505050");
		$placeholderTrainer2[1]["message_start"] = hex2bin("030526062C040405");
		$placeholderTrainer2[1]["message_win"] = hex2bin("030D0D0D1D060807");
		$placeholderTrainer2[1]["message_lose"] = hex2bin("0C050D0D1D062308");
		
		// Trainer 7
		$placeholderTrainer2[0] = array();
		$placeholderTrainer2[0]["name"] = hex2bin("89948D888E9150505050");
		$placeholderTrainer2[0]["class"] = hexdec("36");
		$placeholderTrainer2[0]["pokemon1"] = hex2bin("D9AE1DB62E2B00000069787530753075307530753077450F0A141E000000001E000000720072006400430035004100419491928091888D86505050");
		$placeholderTrainer2[0]["pokemon2"] = hex2bin("160377E44081000000697875307530753075307530677714142314000000001E000000600060004B003D0052003A003A858480918E965050505050");
		$placeholderTrainer2[0]["pokemon3"] = hex2bin("396D4302B374000000697875307530753075307530776714190F1E000000001E0000006300630055003A004E003A00408F91888C84808F84505050");
		$placeholderTrainer2[0]["message_start"] = hex2bin("0C0D2606150B0F08");
		$placeholderTrainer2[0]["message_win"] = hex2bin("1C0E150D11040C05");
		$placeholderTrainer2[0]["message_lose"] = hex2bin("0C051C0E160D0604");
		
		return $placeholderTrainer2[$number];
	}
	
	function getBattleTowerPlaceholderTrainerES3($number) {
		$placeholderTrainer3 = array();
		
		// Trainer 1
		$placeholderTrainer3[6] = array();
		$placeholderTrainer3[6]["name"] = hex2bin("8E82CC8D505050505050");
		$placeholderTrainer3[6]["class"] = hexdec("20");
		$placeholderTrainer3[6]["pokemon1"] = hex2bin("80AED83F59E7000000FA00C350C350C350C350C350FDFE14050A0FFF000000280000008F008F00770071007F0046005E938094918E925050505050");
		$placeholderTrainer3[6]["pokemon2"] = hex2bin("E69239E13F3B000000FA00C350C350C350C350C350FDEF0F14050564000000280000008E008E00730071006A007300738A888D8683918050505050");
		$placeholderTrainer3[6]["pokemon3"] = hex2bin("8F49D522F459000000FA00C350C350C350C350C350EDDD0F0F0A0A6400000028000000CD00CD007E0059003D0059007D928D8E918B809750505050");
		$placeholderTrainer3[6]["message_start"] = hex2bin("0203120E0C052005");
		$placeholderTrainer3[6]["message_win"] = hex2bin("1F0D130616062804");
		$placeholderTrainer3[6]["message_lose"] = hex2bin("140E130615040B02");
		
		// Trainer 2
		$placeholderTrainer3[5] = array();
		$placeholderTrainer3[5]["name"] = hex2bin("85918E92935050505050");
		$placeholderTrainer3[5]["class"] = hexdec("1D");
		$placeholderTrainer3[5]["pokemon1"] = hex2bin("8392553A6D39000000FA00C350C350C350C350C350FDEB0F0A0A0F6400000028000000BA00BA006B006500560067006F8B808F9180925050505050");
		$placeholderTrainer3[5]["pokemon2"] = hex2bin("D0AEC9E7595C000000FA00C350C350C350C350C350EFDB0A0F0A0A6400000028000000890089006A00C7003D004F0057929384848B889750505050");
		$placeholderTrainer3[5]["pokemon3"] = hex2bin("41525E096907000000FA00C350AFC8C350D6D8C350DDEF0A0F140F64000000280000007E007E004C004900870093006B808B808A8099808C505050");
		$placeholderTrainer3[5]["message_start"] = hex2bin("0C0E2C042B0D3D0B");
		$placeholderTrainer3[5]["message_win"] = hex2bin("0706000E18061F07");
		$placeholderTrainer3[5]["message_lose"] = hex2bin("3E05360535051304");
		
		// Trainer 3
		$placeholderTrainer3[4] = array();
		$placeholderTrainer3[4]["name"] = hex2bin("8C8E9192505050505050");
		$placeholderTrainer3[4]["class"] = hexdec("29");
		$placeholderTrainer3[4]["pokemon1"] = hex2bin("79923B55395E000000FA00C350C350AFC8C350AFC8FDBE050F0F0A640000002800000083008300630068007F00750069929380918C888450505050");
		$placeholderTrainer3[4]["pokemon2"] = hex2bin("CAAE44F3DBC2000000FA00AFC8C350C350C350C350BFE7141419056400000028000000E900E9003E00550040003B004F968E818194858584935050");
		$placeholderTrainer3[4]["pokemon3"] = hex2bin("4C779959059D000000FA00AFC8C3509C40C350AFC8DDED050A140A6400000028000000910091007D008B004A00500058868E8B848C505050505050");
		$placeholderTrainer3[4]["message_start"] = hex2bin("030526062C040405");
		$placeholderTrainer3[4]["message_win"] = hex2bin("0C05030507040405");
		$placeholderTrainer3[4]["message_lose"] = hex2bin("0305050B36040405");
		
		// Trainer 4
		$placeholderTrainer3[3] = array();
		$placeholderTrainer3[3]["name"] = hex2bin("989485948D8450505050");
		$placeholderTrainer3[3]["class"] = hexdec("32");
		$placeholderTrainer3[3]["pokemon1"] = hex2bin("D48CA3D3E43F000000FA00C350C350C3509C40C350BDFE1419140564000000280000008B008B008B0075005900520066928288998E915050505050");
		$placeholderTrainer3[3]["pokemon2"] = hex2bin("3352593FBCBD000000FA00AFC8C350C350C350C350FEBB0A050A0A64000000280000006C006C0067004E0083004B005B8394869391888E50505050");
		$placeholderTrainer3[3]["pokemon3"] = hex2bin("506D395E593B000000FA00AFC8C350AFC8C350C350BFCF0F0A0A0564000000280000009D009D005F007E003C00770067928B8E9681918E50505050");
		$placeholderTrainer3[3]["message_start"] = hex2bin("0B0526061F041405");
		$placeholderTrainer3[3]["message_win"] = hex2bin("21051D063D041405");
		$placeholderTrainer3[3]["message_lose"] = hex2bin("11052405140D1F04");
		
		// Trainer 5
		$placeholderTrainer3[2] = array();
		$placeholderTrainer3[2]["name"] = hex2bin("91809880505050505050");
		$placeholderTrainer3[2]["class"] = hexdec("1C");
		$placeholderTrainer3[2]["pokemon1"] = hex2bin("E900B0A03CA8000000FA00C350AFC8C350C350C350BCEF1E1E140A64000000280000009300930063006C0056007B00738F8E9198868E8DF8505050");
		$placeholderTrainer3[2]["pokemon2"] = hex2bin("3B8AAC2B222E000000FA00C350C350C350C350C350FEBB191E0F146400000028000000980098007F0066006F00730063809182808D888D84505050");
		$placeholderTrainer3[2]["pokemon3"] = hex2bin("CD92E5B65CC9000000FA00C350C350C350C350C350FA7F280A0A0A64000000280000008C008C006F0093004000570057858E919184939184929250");
		$placeholderTrainer3[2]["message_start"] = hex2bin("02020C0D24062704");
		$placeholderTrainer3[2]["message_win"] = hex2bin("0D0D1D0608070A05");
		$placeholderTrainer3[2]["message_lose"] = hex2bin("140D07050D0D0208");
		
		// Trainer 6
		$placeholderTrainer3[1] = array();
		$placeholderTrainer3[1]["name"] = hex2bin("918E8391885050505050");
		$placeholderTrainer3[1]["class"] = hexdec("41");
		$placeholderTrainer3[1]["pokemon1"] = hex2bin("8BAEAE37F6F9000000FA00C350C350C350C350C350EFF70A19050F64000000280000008500850056008B0053007C00588E8C809293809150505050");
		$placeholderTrainer3[1]["pokemon2"] = hex2bin("0652535213A3000000FA00C350C350C350C350C350FEFE0F0A0F1464000000280000008E008E006A00640077007D006A8287809188998091835050");
		$placeholderTrainer3[1]["pokemon3"] = hex2bin("67037917F45D000000FA00C350C350C350C350C350F7E70A140A1964000000280000009E009E00730064005200840054849784868694938E915050");
		$placeholderTrainer3[1]["message_start"] = hex2bin("1C0E0C0D00081402");
		$placeholderTrainer3[1]["message_win"] = hex2bin("20021D063D040B05");
		$placeholderTrainer3[1]["message_lose"] = hex2bin("38050F05140D3505");
		
		// Trainer 7
		$placeholderTrainer3[0] = array();
		$placeholderTrainer3[0]["name"] = hex2bin("92808D938880868E5050");
		$placeholderTrainer3[0]["class"] = hexdec("34");
		$placeholderTrainer3[0]["pokemon1"] = hex2bin("61035D091D32000000FA0075307530753075307530777A190F0F1400000000280000009200920056005300510058007A87988F8D8E505050505050");
		$placeholderTrainer3[0]["pokemon2"] = hex2bin("5949675C7C6A000000FA0075307530753075307530756B280A141E0000000028000000A100A1006F005600430053006F8C948A5050505050505050");
		$placeholderTrainer3[0]["pokemon3"] = hex2bin("7D52710981AD000000FA007530753075307530753065771E0F140F00000000280000007C007C005D0047006F0067005F848B848293808194999950");
		$placeholderTrainer3[0]["message_start"] = hex2bin("2D0337040B0D0C08");
		$placeholderTrainer3[0]["message_win"] = hex2bin("2D031904090B0C08");
		$placeholderTrainer3[0]["message_lose"] = hex2bin("1E063304090B0B08");
		
		return $placeholderTrainer3[$number];
	}
	
	function getBattleTowerPlaceholderTrainerES4($number) {
		$placeholderTrainer4 = array();
		
		// Trainer 1
		$placeholderTrainer4[6] = array();
		$placeholderTrainer4[6]["name"] = hex2bin("828092828081848B5050");
		$placeholderTrainer4[6]["class"] = hexdec("3B");
		$placeholderTrainer4[6]["pokemon1"] = hex2bin("E6AE393F3BE1000001E848D6D8D6D8EA60C350D6D8DDFF0F0505140000000032000000B300B3008E008F0084009000908A888D8683918050505050");
		$placeholderTrainer4[6]["pokemon2"] = hex2bin("E56D9CF28A35000001E848D6D8C350EA60EA60EA60DDFC0A0F0F0F0000000032000000B200B2008700620091009D007F878E948D838E8E8C505050");
		$placeholderTrainer4[6]["pokemon3"] = hex2bin("D592C99C5C23000001E848EA60EA60EA60EA60D6D8FDCF0A0A0A1400000000320000007B007B003C01160034003B0117928794828A8B8450505050");
		$placeholderTrainer4[6]["message_start"] = hex2bin("0C0C010D0306250A");
		$placeholderTrainer4[6]["message_win"] = hex2bin("010D1D062E0B0F0E");
		$placeholderTrainer4[6]["message_lose"] = hex2bin("1A0D0706350B020C");
		
		// Trainer 2
		$placeholderTrainer4[5] = array();
		$placeholderTrainer4[5]["name"] = hex2bin("938794918C5050505050");
		$placeholderTrainer4[5]["class"] = hexdec("14");
		$placeholderTrainer4[5]["pokemon1"] = hex2bin("8F923F5939F4000001E848EA60D6D8D6D8EA60D6D8FDEF050A0F0A0000000032000001070107009F0070004F0072009F928D8E918B809750505050");
		$placeholderTrainer4[5]["pokemon2"] = hex2bin("83AE55396D3B000001E848D6D8EA60EA60D6D8EA60DDDD0F0F0A050000000032000000EA00EA00850080006B0085008F8B808F9180925050505050");
		$placeholderTrainer4[5]["pokemon3"] = hex2bin("87525556F7ED000001E848D6D8EA60DEA8D6D8D6D8EDFF0F140F0F0000000032000000A100A10072006B00B3009F0090898E8B93848E8D50505050");
		$placeholderTrainer4[5]["message_start"] = hex2bin("02021C0E340D0A02");
		$placeholderTrainer4[5]["message_win"] = hex2bin("09021706390D0604");
		$placeholderTrainer4[5]["message_lose"] = hex2bin("12051C0E0C0D3E03");
		
		// Trainer 3
		$placeholderTrainer4[4] = array();
		$placeholderTrainer4[4]["name"] = hex2bin("95808B848D93888D8050");
		$placeholderTrainer4[4]["class"] = hexdec("1D");
		$placeholderTrainer4[4]["pokemon1"] = hex2bin("D4923FA361E8000001E848AFC8C3509C40C350AFC8DFED05141E230000000032000000A900A900AF0091006F0063007C928288998E915050505050");
		$placeholderTrainer4[4]["pokemon2"] = hex2bin("C7549C395E85000001E848C3509C40AFC8C350C350DFDE0A0F0A140F00000032000000C400C40076007E004B0092009C928B8E968A888D86505050");
		$placeholderTrainer4[4]["pokemon3"] = hex2bin("44AEEE597E09000001E8489C40AFC8C3509C40ABE0FFEC050A050F0D00000032000000BB00BB00B0007F0063006C00808C808287808C8F50505050");
		$placeholderTrainer4[4]["message_start"] = hex2bin("160D060401030102");
		$placeholderTrainer4[4]["message_win"] = hex2bin("010201050D0D2208");
		$placeholderTrainer4[4]["message_lose"] = hex2bin("22020B020D0D2807");
		
		// Trainer 4
		$placeholderTrainer4[3] = array();
		$placeholderTrainer4[3]["name"] = hex2bin("9680868D849150505050");
		$placeholderTrainer4[3]["class"] = hexdec("36");
		$placeholderTrainer4[3]["pokemon1"] = hex2bin("798C56695539000001E848AFC8ABE09C40AFC89C40FFFF14140F0F0000000032000000A100A10079008200A100910082929380918C888450505050");
		$placeholderTrainer4[3]["pokemon2"] = hex2bin("335259A33FBC000001E848AFC89C40C350AFC8C350F7FE0A14050A0000000032000000870087007D005900A6006000748394869391888E50505050");
		$placeholderTrainer4[3]["pokemon3"] = hex2bin("656D5599F39C000001E848C350AFC8D2F09C40C3507DFE0F05140A0000000032000000A100A10058007500B9007E007E848B848293918E83845050");
		$placeholderTrainer4[3]["message_start"] = hex2bin("120C03061D040F0E");
		$placeholderTrainer4[3]["message_win"] = hex2bin("1502010D1F070B02");
		$placeholderTrainer4[3]["message_lose"] = hex2bin("1C0E0B0D1E06090C");
		
		// Trainer 5
		$placeholderTrainer4[2] = array();
		$placeholderTrainer4[2]["name"] = hex2bin("98809384925050505050");
		$placeholderTrainer4[2]["class"] = hexdec("18");
		$placeholderTrainer4[2]["pokemon1"] = hex2bin("8E523F597EE7000001E848AFC8C350C350AFC8AFC8FDDD050A050F0000000032000000B500B50098006E00AE006800778084918E83808293988B50");
		$placeholderTrainer4[2]["pokemon2"] = hex2bin("A9926DD53F5C000001E848AFC89C40C3509C40C350EFFF0A0F050A0000000032000000B200B20086007F00AF0075007F82918E8180935050505050");
		$placeholderTrainer4[2]["pokemon3"] = hex2bin("916D4155563F000001E848AFC8C350AFC89C40C350FDDE140F14050000000032000000BE00BE00890081008F00AB008899808F838E925050505050");
		$placeholderTrainer4[2]["message_start"] = hex2bin("02031C0E150D1104");
		$placeholderTrainer4[2]["message_win"] = hex2bin("1A0B30041C0E1D0D");
		$placeholderTrainer4[2]["message_lose"] = hex2bin("320D2E0630041E05");
		
		// Trainer 6
		$placeholderTrainer4[1] = array();
		$placeholderTrainer4[1]["name"] = hex2bin("8BCC8F84995050505050");
		$placeholderTrainer4[1]["class"] = hexdec("35");
		$placeholderTrainer4[1]["pokemon1"] = hex2bin("E3AEC913D35C000001E848AFC8C350C350C3509C40D7ED0A0F190A0000000032000000A400A4007D00B3007400530071928A80918C8E9198505050");
		$placeholderTrainer4[1]["pokemon2"] = hex2bin("CD92C95C99CF000001E848C350C350D6D8AFC89C40CFDD0A0A050F0000000032000000A900A9008600BD005400670067858E919184939184929250");
		$placeholderTrainer4[1]["pokemon3"] = hex2bin("D06DC9E79C59000001E848AFC8C350C3509C40AFC8DDDD0A0F0A0A0000000032000000B000B0008200F500490063006D929384848B889750505050");
		$placeholderTrainer4[1]["message_start"] = hex2bin("00061D0426080202");
		$placeholderTrainer4[1]["message_win"] = hex2bin("08051D0602080705");
		$placeholderTrainer4[1]["message_lose"] = hex2bin("180E04060B04040C");
		
		// Trainer 7
		$placeholderTrainer4[0] = array();
		$placeholderTrainer4[0]["name"] = hex2bin("81809150505050505050");
		$placeholderTrainer4[0]["class"] = hexdec("1E");
		$placeholderTrainer4[0]["pokemon1"] = hex2bin("CB8C8AF25E59000001E8489C409C409C409C409C4045560F0F0A0A6400000032000000A100A1007200640078007E00658688918085809188865050");
		$placeholderTrainer4[0]["pokemon2"] = hex2bin("826D3F39F0C0000001E8489C409C409C409C409C407565050F05056400000032000000C100C100A200720075005F00878698809180838E92505050");
		$placeholderTrainer4[0]["pokemon3"] = hex2bin("90AE3B3F2EC4000001E8489C409C409C409C409C4045560505140F0000000032000000B500B5007700870078008300A18091938882948D8E505050");
		$placeholderTrainer4[0]["message_start"] = hex2bin("0C0C0203110D3304");
		$placeholderTrainer4[0]["message_win"] = hex2bin("210B21060C0D0F08");
		$placeholderTrainer4[0]["message_lose"] = hex2bin("0103020D090B2807");
		
		return $placeholderTrainer4[$number];
	}
	
	function getBattleTowerPlaceholderTrainerES5($number) {
		$placeholderTrainer5 = array();
		
		// Trainer 1
		$placeholderTrainer5[6] = array();
		$placeholderTrainer5[6]["name"] = hex2bin("8C8E9188505050505050");
		$placeholderTrainer5[6]["class"] = hexdec("14");
		$placeholderTrainer5[6]["pokemon1"] = hex2bin("E692E1393F3B0000034BC0D6D8D6D8C350EA60EA60DDFE140F0505640000003C000000D300D300A900A700A100AC00AC8A888D8683918050505050");
		$placeholderTrainer5[6]["pokemon2"] = hex2bin("F8AEF2599D3F0000034BC0D6D8EA60D6D8EA60D6D8FDED0F0A0A05640000003C000000F000F000DC00BB008300A900AF939891808D889380915050");
		$placeholderTrainer5[6]["pokemon3"] = hex2bin("E56D35F28A9C0000034BC0EA60D6D8D6D8EA60D6D8FBEF0F0F0F0A640000003C000000D400D400A5007100AC00BD0099878E948D838E8E8C505050");
		$placeholderTrainer5[6]["message_start"] = hex2bin("0103360D0D0D2F08");
		$placeholderTrainer5[6]["message_win"] = hex2bin("1C0E28070B060404");
		$placeholderTrainer5[6]["message_lose"] = hex2bin("0D0D1C0E100E4407");
		
		// Trainer 2
		$placeholderTrainer5[5] = array();
		$placeholderTrainer5[5]["name"] = hex2bin("8A8E8D838E5050505050");
		$placeholderTrainer5[5]["class"] = hexdec("38");
		$placeholderTrainer5[5]["pokemon1"] = hex2bin("E9923B695C5E0000034BC0D6D8C350C350C350D6D8DDDE05140A0A640000003C000000DF00DF009500A1007D00B600AA8F8E9198868E8DF8505050");
		$placeholderTrainer5[5]["pokemon2"] = hex2bin("444907EE09590000034BC0C350C350AFC8C350C350FDEF0F050F0A640000003C000000E200E200D4009400780086009E8C808287808C8F50505050");
		$placeholderTrainer5[5]["pokemon3"] = hex2bin("91549C4155560000034BC0C350AFC8C350D6D8C350DDFD0A140F14640000003C000000E500E500A0009B00B100CB00A199808F838E925050505050");
		$placeholderTrainer5[5]["message_start"] = hex2bin("0D0D100E28040405");
		$placeholderTrainer5[5]["message_win"] = hex2bin("0B0D2F0702032608");
		$placeholderTrainer5[5]["message_lose"] = hex2bin("0D0D0F0E24062704");
		
		// Trainer 3
		$placeholderTrainer5[4] = array();
		$placeholderTrainer5[4]["name"] = hex2bin("8C888D88505050505050");
		$placeholderTrainer5[4]["class"] = hexdec("17");
		$placeholderTrainer5[4]["pokemon1"] = hex2bin("CAAE44F3C2DB0000034BC0C350C350AFC8C350AFC8FDED14140519640000003C0000015A015A005F007A005E005C007A968E818194858584935050");
		$placeholderTrainer5[4]["pokemon2"] = hex2bin("8E923F30592C0000034BC0AFC8C350C350AFC8AFC8FDDD05140A19640000003C000000D700D700B6008300D0007C008E8084918E83808293988B50");
		$placeholderTrainer5[4]["pokemon3"] = hex2bin("956D3FC455390000034BC0AFC8C3509C40C350AFC8DDFD050F0F0F640000003C000000E500E500D600A4009800AC00AC839180868E8D8893845050");
		$placeholderTrainer5[4]["message_start"] = hex2bin("1A0D1E0903063004");
		$placeholderTrainer5[4]["message_win"] = hex2bin("1D0430040B0D100A");
		$placeholderTrainer5[4]["message_lose"] = hex2bin("2B0D100A20060F0E");
		
		// Trainer 4
		$placeholderTrainer5[3] = array();
		$placeholderTrainer5[3]["name"] = hex2bin("8FEA9184995050505050");
		$placeholderTrainer5[3]["class"] = hexdec("25");
		$placeholderTrainer5[3]["pokemon1"] = hex2bin("C5AEBDEC5EB90000034BC0C350C350C350C350C350FDEF0A050A14640000003C000000E800E8008600B90084008000D4948C8191848E8D50505050");
		$placeholderTrainer5[3]["pokemon2"] = hex2bin("3B8A35F2F5E70000034BC0D6D8C3509C40D6D8C350FDED0F0F050F640000003C000000E400E400BC009200AA00AD0095809182808D888D84505050");
		$placeholderTrainer5[3]["pokemon3"] = hex2bin("E36DD3135CB60000034BC0C350C350AFC8C350C350FBEB190F0A0A640000003C000000C400C4009800DA008A00630087928A80918C8E9198505050");
		$placeholderTrainer5[3]["message_start"] = hex2bin("160A35031906080C");
		$placeholderTrainer5[3]["message_win"] = hex2bin("1706310D13060D0E");
		$placeholderTrainer5[3]["message_lose"] = hex2bin("0C0D44070E05160A");
		
		// Trainer 5
		$placeholderTrainer5[2] = array();
		$placeholderTrainer5[2]["name"] = hex2bin("80918850505050505050");
		$placeholderTrainer5[2]["class"] = hexdec("3C");
		$placeholderTrainer5[2]["pokemon1"] = hex2bin("F292875CB65E0000034BC0C350AFC8C350AFC8C350FBCD0A0A0A0A640000003C000001A801A80042003F0075008F00D7818B889292849850505050");
		$placeholderTrainer5[2]["pokemon2"] = hex2bin("8F689D3922590000034BC0C350AFC8C350C350C350FAFC0A0F0F0A640000003C00000133013300BA0080005C008200B8928D8E918B809750505050");
		$placeholderTrainer5[2]["pokemon3"] = hex2bin("D677B3E059440000034BC0C3509C40C350C350C350DFED0F0A0A14640000003C000000D600D600C80092009C006500A78784918082918E92925050");
		$placeholderTrainer5[2]["message_start"] = hex2bin("150202020006270D");
		$placeholderTrainer5[2]["message_win"] = hex2bin("1502020509020105");
		$placeholderTrainer5[2]["message_lose"] = hex2bin("1B02350507060C07");
		
		// Trainer 6
		$placeholderTrainer5[1] = array();
		$placeholderTrainer5[1]["name"] = hex2bin("82808C8F505050505050");
		$placeholderTrainer5[1]["class"] = hexdec("34");
		$placeholderTrainer5[1]["pokemon1"] = hex2bin("7C6D3B5EF7C40000034BC0C350C350C350C350C350FFEB050A0F0F640000003C000000C400C40074006200A800BD00A589988D9750505050505050");
		$placeholderTrainer5[1]["pokemon2"] = hex2bin("09AE3959E53B0000034BC0C350C350C350C350C350FEFE0F0A2805640000003C000000D100D1009B00AE0095009C00B4818B8092938E8892845050");
		$placeholderTrainer5[1]["pokemon3"] = hex2bin("70495939E79D0000034BC0C350C350C350C350C350FBFA0A0F0F0A640000003C000000F500F500D400C3006800680068918798838E8D5050505050");
		$placeholderTrainer5[1]["message_start"] = hex2bin("030527030D0D0405");
		$placeholderTrainer5[1]["message_win"] = hex2bin("0305010D2E0B0405");
		$placeholderTrainer5[1]["message_lose"] = hex2bin("030527030C0D0405");
		
		// Trainer 7
		$placeholderTrainer5[0] = array();
		$placeholderTrainer5[0]["name"] = hex2bin("859184848C808D505050");
		$placeholderTrainer5[0]["class"] = hexdec("36");
		$placeholderTrainer5[0]["pokemon1"] = hex2bin("1C8C59A33FAD0000034BC075307530753075307530B7670A14050F000000003C000000C900C900A400AB0074005D006992808D83928B8092875050");
		$placeholderTrainer5[0]["pokemon2"] = hex2bin("2FAE93CA3FBC0000034BC075307530753075307530665F0F05050A000000003C000000AB00AB009800860048007800908F80918092848293505050");
		$placeholderTrainer5[0]["pokemon3"] = hex2bin("4C03995907DA0000034BC0753075307530753075307657050A0F14000000003C000000CD00CD00AB00C2005A00690075868E8B848C505050505050");
		$placeholderTrainer5[0]["message_start"] = hex2bin("02050C0D44070105");
		$placeholderTrainer5[0]["message_win"] = hex2bin("12023A06120E0203");
		$placeholderTrainer5[0]["message_lose"] = hex2bin("02051C0E13040105");
		
		return $placeholderTrainer5[$number];
	}
	
	function getBattleTowerPlaceholderTrainerES6($number) {
		$placeholderTrainer6 = array();
		
		// Trainer 1
		$placeholderTrainer6[6] = array();
		$placeholderTrainer6[6]["name"] = hex2bin("86849193919450505050");
		$placeholderTrainer6[6]["class"] = hexdec("19");
		$placeholderTrainer6[6]["pokemon1"] = hex2bin("876D553FF72E0000053BD8EA60DAC0D6D8C350EA60FBEF0F050F146400000046000000E700E7009E009100F500DE00C9898E8B93848E8D50505050");
		$placeholderTrainer6[6]["pokemon2"] = hex2bin("86923BF739BD0000053BD8C350C350EA60EA60C350BFEF050F0F0A64000000460000013E013E00950098009E00DA00C595808F8E91848E8D505050");
		$placeholderTrainer6[6]["pokemon3"] = hex2bin("C5AEB9ECF45C0000053BD8D2F0EA60D6D8C350E290DDDD14050A0A6400000046000001120112009C00D90098009500F7948C8191848E8D50505050");
		$placeholderTrainer6[6]["message_start"] = hex2bin("0B052E04110D3D04");
		$placeholderTrainer6[6]["message_win"] = hex2bin("09051102010D0607");
		$placeholderTrainer6[6]["message_lose"] = hex2bin("050B360418050102");
		
		// Trainer 2
		$placeholderTrainer6[5] = array();
		$placeholderTrainer6[5]["name"] = hex2bin("83888D84915050505050");
		$placeholderTrainer6[5]["class"] = hexdec("20");
		$placeholderTrainer6[5]["pokemon1"] = hex2bin("F2AE4487F7550000053BD8D6D8D6D8D6D8E290C350DFED140A0F0F6400000046000001EF01EF004D0050008F00A600FA818B889292849850505050");
		$placeholderTrainer6[5]["pokemon2"] = hex2bin("8F929D593BF70000053BD8D6D8D6D8EA60D6D8C350DDDD0A0A050F64000000460000016D016D00D9009C0069009800D7928D8E918B809750505050");
		$placeholderTrainer6[5]["pokemon3"] = hex2bin("E552F235B92E0000053BD8E290C350D6D8EA60D6D8DDCD0F0F14146400000046000000F500F500BB008500C500D900AF878E948D838E8E8C505050");
		$placeholderTrainer6[5]["message_start"] = hex2bin("0C0E09030B0D3208");
		$placeholderTrainer6[5]["message_win"] = hex2bin("1C0E23080E060903");
		$placeholderTrainer6[5]["message_lose"] = hex2bin("02060903050B3604");
		
		// Trainer 3
		$placeholderTrainer6[4] = array();
		$placeholderTrainer6[4]["name"] = hex2bin("8980828A928E8D505050");
		$placeholderTrainer6[4]["class"] = hexdec("3E");
		$placeholderTrainer6[4]["pokemon1"] = hex2bin("F89259F29D3F0000053BD8C350AFC8AFC8C350AFC8DBDF0A0F0A05640000004600000117011700F700D3009300C400CB939891808D889380915050");
		$placeholderTrainer6[4]["pokemon2"] = hex2bin("91AE5541563F0000053BD8AFC8C350C350AFC8AFC8DBDF0F141405640000004600000108010800BB00B100C800EE00BD99808F838E925050505050");
		$placeholderTrainer6[4]["pokemon3"] = hex2bin("676D9C995ECA0000053BD8AFC8C3509C40C350AFC8DDED0A050A0564000000460000010C010C00C200B1008C00EB0097849784868694938E915050");
		$placeholderTrainer6[4]["message_start"] = hex2bin("0205190D02080105");
		$placeholderTrainer6[4]["message_win"] = hex2bin("11021C0E280D0A04");
		$placeholderTrainer6[4]["message_lose"] = hex2bin("02050D0D23080105");
		
		// Trainer 4
		$placeholderTrainer6[3] = array();
		$placeholderTrainer6[3]["name"] = hex2bin("828E8194505050505050");
		$placeholderTrainer6[3]["class"] = hexdec("1E");
		$placeholderTrainer6[3]["pokemon1"] = hex2bin("C5AEECB95EF70000053BD8C350C350AFC8AFC8C350FDEB05140A0F64000000460000010D010D009B00D60098008E00F0948C8191848E8D50505050");
		$placeholderTrainer6[3]["pokemon2"] = hex2bin("820339553F2E0000053BD8D6D8AFC8C350D6D8C350DBEF0F0F051464000000460000010F010F00EB00A900B2009400CC8698809180838E92505050");
		$placeholderTrainer6[3]["pokemon3"] = hex2bin("C36D5939BCE70000053BD8C350C350AFC8C350C350DEDD0A0F0A0F64000000460000010A010A00B400B4006E009800989094808692889184505050");
		$placeholderTrainer6[3]["message_start"] = hex2bin("3004030613064304");
		$placeholderTrainer6[3]["message_win"] = hex2bin("C5000B0D1D060208");
		$placeholderTrainer6[3]["message_lose"] = hex2bin("1105C3000B0D0807");
		
		// Trainer 5
		$placeholderTrainer6[2] = array();
		$placeholderTrainer6[2]["name"] = hex2bin("8B84CC8D505050505050");
		$placeholderTrainer6[2]["class"] = hexdec("16");
		$placeholderTrainer6[2]["pokemon1"] = hex2bin("D98CA3593F090000053BD8C350AFC8C350AFC8C350FDED140A050F640000004600000106010600F500A6008A00A600A69491928091888D86505050");
		$placeholderTrainer6[2]["pokemon2"] = hex2bin("7A5273075EE30000053BD8C350AFC8AFC8C350C350BDFB140F0A056400000046000000C300C30078009700BE00C600E28C91E88C888C8450505050");
		$placeholderTrainer6[2]["pokemon3"] = hex2bin("3949EE08099D0000053BD8C3509C40C350C350C350BDEF050F0F0A6400000046000000E300E300CA009100C4009400A28F91888C84808F84505050");
		$placeholderTrainer6[2]["message_start"] = hex2bin("3D063004100D0D08");
		$placeholderTrainer6[2]["message_win"] = hex2bin("1306060611040C05");
		$placeholderTrainer6[2]["message_lose"] = hex2bin("1B022D031D060807");
		
		// Trainer 6
		$placeholderTrainer6[1] = array();
		$placeholderTrainer6[1]["name"] = hex2bin("8C8091888D8050505050");
		$placeholderTrainer6[1]["class"] = hexdec("22");
		$placeholderTrainer6[1]["pokemon1"] = hex2bin("CBAE61E2F2590000053BD8C350C350C350C350C350FEFD1E280F0A6400000046000000E700E700B0009A00B700BB00988688918085809188865050");
		$placeholderTrainer6[1]["pokemon2"] = hex2bin("6A77B3CB22190000053BD8C350C350C350C350C350FEFE0F0A0F056400000046000000CA00CA00E8008900BA007000D98788938C8E8D8B84845050");
		$placeholderTrainer6[1]["pokemon3"] = hex2bin("D603B3CBE0590000053BD8C350C350C350C350C350F7F70F0A0A0A6400000046000000FB00FB00EF009E00B7006D00BA8784918082918E92925050");
		$placeholderTrainer6[1]["message_start"] = hex2bin("1C0E150D0A041A05");
		$placeholderTrainer6[1]["message_win"] = hex2bin("13040D0D1D060807");
		$placeholderTrainer6[1]["message_lose"] = hex2bin("1C0E160D0E060D0E");
		
		// Trainer 7
		$placeholderTrainer6[0] = array();
		$placeholderTrainer6[0]["name"] = hex2bin("96888B8B985050505050");
		$placeholderTrainer6[0]["class"] = hexdec("28");
		$placeholderTrainer6[0]["pokemon1"] = hex2bin("0303F14CEB3F0000053BD8753075307530753075307644050A05050000000046000000E900E9009F009F009800B400B495848D9492809491505050");
		$placeholderTrainer6[0]["pokemon2"] = hex2bin("068CA3593F350000053BD8753075307530753075305644140A050F0000000046000000E600E6009F009800B400C1009F8287809188998091835050");
		$placeholderTrainer6[0]["pokemon3"] = hex2bin("094938083FE70000053BD8753075307530753075307664050F050F0000000046000000E700E700A100B70098009F00BB818B8092938E8892845050");
		$placeholderTrainer6[0]["message_start"] = hex2bin("11020B060B042504");
		$placeholderTrainer6[0]["message_win"] = hex2bin("08050E063D063004");
		$placeholderTrainer6[0]["message_lose"] = hex2bin("3A060406160D430B");
		
		return $placeholderTrainer6[$number];
	}
	
	function getBattleTowerPlaceholderTrainerES7($number) {
		$placeholderTrainer7 = array();
		
		// Trainer 1
		$placeholderTrainer7[6] = array();
		$placeholderTrainer7[6]["name"] = hex2bin("809191BF925050505050");
		$placeholderTrainer7[6]["class"] = hexdec("32");
		$placeholderTrainer7[6]["pokemon1"] = hex2bin("876D5655E72E000007D000EA60D6D8EA60D6D8D6D8FDEB140F0F14640000005000000107010700B300AA011900F500DD898E8B93848E8D50505050");
		$placeholderTrainer7[6]["pokemon2"] = hex2bin("8F929CBBAD59000007D000EA60D6D8C350D8CCEA60DBED0A0A0F0A64000000500000019F019F00F800AA007900B200FA928D8E918B809750505050");
		$placeholderTrainer7[6]["pokemon3"] = hex2bin("E5549CF235F1000007D000D6D8C350D6D8D6D8D6D8FDDB0A0F0F05640000005000000118011800D9009800E000F500C5878E948D838E8E8C505050");
		$placeholderTrainer7[6]["message_start"] = hex2bin("10041C0E35042E04");
		$placeholderTrainer7[6]["message_win"] = hex2bin("10040C0D26062604");
		$placeholderTrainer7[6]["message_lose"] = hex2bin("30053204050B3604");
		
		// Trainer 2
		$placeholderTrainer7[5] = array();
		$placeholderTrainer7[5]["name"] = hex2bin("8E89EA8D505050505050");
		$placeholderTrainer7[5]["class"] = hexdec("29");
		$placeholderTrainer7[5]["pokemon1"] = hex2bin("80AE5922E73F000007D000C350C350C3507530C350FDDE0A0F0F05640000005000000114011400E900DD00EC008700B7938094918E925050505050");
		$placeholderTrainer7[5]["pokemon2"] = hex2bin("83549C396D5E000007D000C350C350C350D6D8C350DFDB0A0F0A0A64000000500000016E016E00CD00C900A800CA00DA8B808F9180925050505050");
		$placeholderTrainer7[5]["pokemon3"] = hex2bin("F86D9CF2599D000007D000C350D6D8C350D6D8C350DFDB0A0F0A0A64000000500000013E013E011E00F900A900DA00E2939891808D889380915050");
		$placeholderTrainer7[5]["message_start"] = hex2bin("2D060706010D1B07");
		$placeholderTrainer7[5]["message_win"] = hex2bin("11021C0E150D0604");
		$placeholderTrainer7[5]["message_lose"] = hex2bin("1C0E0C0D08064407");
		
		// Trainer 3
		$placeholderTrainer7[4] = array();
		$placeholderTrainer7[4]["name"] = hex2bin("8F848BCC8D5050505050");
		$placeholderTrainer7[4]["class"] = hexdec("1C");
		$placeholderTrainer7[4]["pokemon1"] = hex2bin("5E0055F76DA8000007D000C350C350AFC8D6D8C350DEDD0F0F0A0A6400000050000000F700F700AD00A500F8011500BD86848D8680915050505050");
		$placeholderTrainer7[4]["pokemon2"] = hex2bin("CD92995C4CCF000007D000AFC8C350C350AFC8C350FDED050A0A0F640000005000000111011100D90125008500A500A5858E919184939184929250");
		$placeholderTrainer7[4]["pokemon3"] = hex2bin("E6549C393BE1000007D000AFC8C3509C40D6D8C350FBED0A0F0514640000005000000111011100E100D600D100DD00DD8A888D8683918050505050");
		$placeholderTrainer7[4]["message_start"] = hex2bin("0F0B390615043004");
		$placeholderTrainer7[4]["message_win"] = hex2bin("0E07350319062C04");
		$placeholderTrainer7[4]["message_lose"] = hex2bin("3A0B330D2606120B");
		
		// Trainer 4
		$placeholderTrainer7[3] = array();
		$placeholderTrainer7[3]["name"] = hex2bin("80869480925050505050");
		$placeholderTrainer7[3]["class"] = hexdec("26");
		$placeholderTrainer7[3]["pokemon1"] = hex2bin("95AE563955C8000007D000C350C350C350C350AFC8DDDD140F0F0F64000000500000012F012F011C00DD00C500E400E4839180868E8D8893845050");
		$placeholderTrainer7[3]["pokemon2"] = hex2bin("E9925E693FA1000007D000D6D8C3509C40D6D8C350DFED0A14050A640000005000000125012500C500D500A900ED00DD8F8E9198868E8DF8505050");
		$placeholderTrainer7[3]["pokemon3"] = hex2bin("7C498E3B8A5E000007D000D6D8C350AFC8C350C350DFDF0A050F0A64000000500000010801080095007F00DD010100E189988D9750505050505050");
		$placeholderTrainer7[3]["message_start"] = hex2bin("1E061C0C0B0D4407");
		$placeholderTrainer7[3]["message_win"] = hex2bin("280406061906110A");
		$placeholderTrainer7[3]["message_lose"] = hex2bin("390B1E0E210C3806");
		
		// Trainer 5
		$placeholderTrainer7[2] = array();
		$placeholderTrainer7[2]["name"] = hex2bin("92849184925050505050");
		$placeholderTrainer7[2]["class"] = hexdec("18");
		$placeholderTrainer7[2]["pokemon1"] = hex2bin("E2AE396D3B11000007D0009C40AFC89C40AFC8C350DFDC0F0A05236400000050000001000100008400B500B400C401248C808D93888D8450505050");
		$placeholderTrainer7[2]["pokemon2"] = hex2bin("E349D313BD5C000007D000C350AFC888B8C350C350DDEF190F0A0A640000005000000102010200C4011E00B7008900B9928A80918C8E9198505050");
		$placeholderTrainer7[2]["pokemon3"] = hex2bin("928A358FD33F000007D000C3509C40C3509C40C350DDFE0F05190564000000500000012C012C00E100D500D5010F00CF8C8E8B9391849250505050");
		$placeholderTrainer7[2]["message_start"] = hex2bin("0C040E062E063004");
		$placeholderTrainer7[2]["message_win"] = hex2bin("0C0D160602081F02");
		$placeholderTrainer7[2]["message_lose"] = hex2bin("0B02210408060A02");
		
		// Trainer 6
		$placeholderTrainer7[1] = array();
		$placeholderTrainer7[1]["name"] = hex2bin("918E8250505050505050");
		$placeholderTrainer7[1]["class"] = hexdec("3A");
		$placeholderTrainer7[1]["pokemon1"] = hex2bin("8E6D3F9C592E000007D000C3509C40C3509C40C350FFED050A0A1464000000500000011A011A00ED00B1011300A500BD8084918E83808293988B50");
		$placeholderTrainer7[1]["pokemon2"] = hex2bin("65525599F35C000007D000C350C3509C409C40C350FFEF0F05140A6400000050000000FA00FA009900B5012300C900C9848B848293918E83845050");
		$placeholderTrainer7[1]["pokemon3"] = hex2bin("338CA359A8BD000007D000C350C3509C40C3509C40FDDD140A0A0A6400000050000000D600D600C900910105009100B18394869391888E50505050");
		$placeholderTrainer7[1]["message_start"] = hex2bin("0B02020200061D04");
		$placeholderTrainer7[1]["message_win"] = hex2bin("03052E0616040405");
		$placeholderTrainer7[1]["message_lose"] = hex2bin("11050D0D08064407");
		
		// Trainer 7
		$placeholderTrainer7[0] = array();
		$placeholderTrainer7[0]["name"] = hex2bin("958882848D9350505050");
		$placeholderTrainer7[0]["class"] = hexdec("19");
		$placeholderTrainer7[0]["pokemon1"] = hex2bin("4749CABC3F5C000007D000753075307530753075306565050A050A000000005000000104010400D9009700A100CF008F9588829391848481848B50");
		$placeholderTrainer7[0]["pokemon2"] = hex2bin("7FAE3F42465C000007D00075307530753075307530746405190F0A0000000050000000F100F100FA00CD00B90085009D8F888D9288915050505050");
		$placeholderTrainer7[0]["pokemon3"] = hex2bin("D2032EF73F09000007D000753075307530753075307657140F050F00000000500000011E011E00F200A90077009200928691808D81948B8B505050");
		$placeholderTrainer7[0]["message_start"] = hex2bin("200613090B0D3207");
		$placeholderTrainer7[0]["message_win"] = hex2bin("03051D0632070405");
		$placeholderTrainer7[0]["message_lose"] = hex2bin("0C051A080104180E");
		
		return $placeholderTrainer7[$number];
	}
	
	function getBattleTowerPlaceholderTrainerES8($number) {
		$placeholderTrainer8 = array();
		
		// Trainer 1
		$placeholderTrainer8[6] = array();
		$placeholderTrainer8[6]["name"] = hex2bin("958882848D9350505050");
		$placeholderTrainer8[6]["class"] = hexdec("41");
		$placeholderTrainer8[6]["pokemon1"] = hex2bin("C552B9BDEC6D00000B1FA8EA60EA60D6D8EA60D6D8FDED140A050A640000005A0000015D015D00CB011600CA00BC013A948C8191848E8D50505050");
		$placeholderTrainer8[6]["pokemon2"] = hex2bin("95497E3FC83B00000B1FA8FDE8C350DAC0EA60EA60FDED05050F05640000005A000001570157014200FC00E501070107839180868E8D8893845050");
		$placeholderTrainer8[6]["pokemon3"] = hex2bin("79926955395E00000B1FA8EA60EA60EA60D6D8EA60DFDD140F0F0A640000005A00000121012100DA00EF011F010700EC929380918C888450505050");
		$placeholderTrainer8[6]["message_start"] = hex2bin("0D0D0208190D0008");
		$placeholderTrainer8[6]["message_win"] = hex2bin("1A061A0D19040502");
		$placeholderTrainer8[6]["message_lose"] = hex2bin("1C0E0D0D00081605");
		
		// Trainer 2
		$placeholderTrainer8[5] = array();
		$placeholderTrainer8[5]["name"] = hex2bin("95808D83985050505050");
		$placeholderTrainer8[5]["class"] = hexdec("21");
		$placeholderTrainer8[5]["pokemon1"] = hex2bin("5B92993B39C400000B1FA8C350C350C350C350C350DBDF05050F0F640000005A0000010A010A00F8018E00CB00EA00A2828B8E9892938491505050");
		$placeholderTrainer8[5]["pokemon2"] = hex2bin("A9AE11723FCA00000B1FA8C350C350C350C350C350FDCF231E0505640000005A00000145014500F300DD013600CF00E182918E8180935050505050");
		$placeholderTrainer8[5]["pokemon3"] = hex2bin("E9495C5E69B600000B1FA8C350AFC8C350D6D8C350FDED0A0A140A640000005A00000145014500DF00EF00BE010A00F88F8E9198868E8DF8505050");
		$placeholderTrainer8[5]["message_start"] = hex2bin("0205010D0B0E0105");
		$placeholderTrainer8[5]["message_win"] = hex2bin("02050B0E02040105");
		$placeholderTrainer8[5]["message_lose"] = hex2bin("02050F0D0B0E0105");
		
		// Trainer 3
		$placeholderTrainer8[4] = array();
		$placeholderTrainer8[4]["name"] = hex2bin("91808D868E5050505050");
		$placeholderTrainer8[4]["class"] = hexdec("17");
		$placeholderTrainer8[4]["pokemon1"] = hex2bin("E692E1393F3B00000B1FA8C350C350D6D8C350AFC8DFDE140F0505640000005A00000135013500F800FF00E600F800F88A888D8683918050505050");
		$placeholderTrainer8[4]["pokemon2"] = hex2bin("F8493FF2599D00000B1FA8C350D6D8C350AFC8C350DFDE050F0A0A640000005A0000016201620141011700B900FA0103939891808D889380915050");
		$placeholderTrainer8[4]["pokemon3"] = hex2bin("83549C39555E00000B1FA8AFC8C350C350C350D6D8BDEF0A0F0F0A640000005A00000195019500E300DD00BB00ED00FF8B808F9180925050505050");
		$placeholderTrainer8[4]["message_start"] = hex2bin("0E0B00062E063004");
		$placeholderTrainer8[4]["message_win"] = hex2bin("030539030D080405");
		$placeholderTrainer8[4]["message_lose"] = hex2bin("180E1C0E0E0B1606");
		
		// Trainer 4
		$placeholderTrainer8[3] = array();
		$placeholderTrainer8[3]["name"] = hex2bin("82808C888D8E50505050");
		$placeholderTrainer8[3]["class"] = hexdec("27");
		$placeholderTrainer8[3]["pokemon1"] = hex2bin("C4AE5EF7F1EA00000B1FA8D6D8C350C350D6D8C350DDFE0A0F0505640000005A00000126012600C200B9011A013900FA84928F848E8D5050505050");
		$placeholderTrainer8[3]["pokemon2"] = hex2bin("4449EEE97E5900000B1FA8D6D8D6D8C350D6D8C350DDED050A050A640000005A000001510151013A00DD00B500C200E68C808287808C8F50505050");
		$placeholderTrainer8[3]["pokemon3"] = hex2bin("8F6D7E39593F00000B1FA8AFC8C350D6D8C350C350FEFD050F0A05640000005A000001C701C7011700C7008700C20113928D8E918B809750505050");
		$placeholderTrainer8[3]["message_start"] = hex2bin("02021A0D14042504");
		$placeholderTrainer8[3]["message_win"] = hex2bin("1D0514053F071404");
		$placeholderTrainer8[3]["message_lose"] = hex2bin("1C0E1D0D060D1402");
		
		// Trainer 5
		$placeholderTrainer8[2] = array();
		$placeholderTrainer8[2]["name"] = hex2bin("8994808D88938E505050");
		$placeholderTrainer8[2]["class"] = hexdec("16");
		$placeholderTrainer8[2]["pokemon1"] = hex2bin("3B54F135F59C00000B1FA8C350AFC8C350AFC8D6D8DFDE050F050A640000005A000001500150011200E100F7010600E2809182808D888D84505050");
		$placeholderTrainer8[2]["pokemon2"] = hex2bin("F2924CF1877E00000B1FA8C350AFC8C350C350C350BDFE0A050A05640000005A000001A801A8005A005F00B400D60142818B889292849850505050");
		$placeholderTrainer8[2]["pokemon3"] = hex2bin("E50335F2F14C00000B1FA8C3509C40C350C350C350DBFE0F0F050A640000005A00000135013500EB00A400FC011500DF878E948D838E8E8C505050");
		$placeholderTrainer8[2]["message_start"] = hex2bin("0C0D08070B060C04");
		$placeholderTrainer8[2]["message_win"] = hex2bin("25050F083D063004");
		$placeholderTrainer8[2]["message_lose"] = hex2bin("3605090B39070A05");
		
		// Trainer 6
		$placeholderTrainer8[1] = array();
		$placeholderTrainer8[1]["name"] = hex2bin("8083BF8D505050505050");
		$placeholderTrainer8[1]["class"] = hexdec("2B");
		$placeholderTrainer8[1]["pokemon1"] = hex2bin("E349C9D35CD800000B1FA8C350C350C350C350C350EFF70A190A14FF0000005A00000117011700DF014D00CF008B00C1928A80918C8E9198505050");
		$placeholderTrainer8[1]["pokemon2"] = hex2bin("D5925C23B6E300000B1FA8C350C350C350C350C350FEFE0A140A05640000005A000000CB00CB006301ED005A006101ED928794828A8B8450505050");
		$placeholderTrainer8[1]["pokemon3"] = hex2bin("88543F35F72E00000B1FA8C350C350C350C350C350F7F7050F0F14640000005A000001250125013B00AF00C600EE0109858B8091848E8D50505050");
		$placeholderTrainer8[1]["message_start"] = hex2bin("050638052B053C05");
		$placeholderTrainer8[1]["message_win"] = hex2bin("2505140525052905");
		$placeholderTrainer8[1]["message_lose"] = hex2bin("3605240516053905");
		
		// Trainer 7
		$placeholderTrainer8[0] = array();
		$placeholderTrainer8[0]["name"] = hex2bin("9280938E925050505050");
		$placeholderTrainer8[0]["class"] = hexdec("24");
		$placeholderTrainer8[0]["pokemon1"] = hex2bin("F192D059D52200000B1FA87530753075307530753047570A0A0F0F000000005A00000142014200C200F500E8008000B68C888B93808D8A50505050");
		$placeholderTrainer8[0]["pokemon2"] = hex2bin("8068553FD55900000B1FA87530753075307530753065760F050F0A000000005A0000011C011C00EA00DF00FE007E00B4938094918E925050505050");
		$placeholderTrainer8[0]["pokemon3"] = hex2bin("59495CBCD5CA00000B1FA87530753075307530753054440A0A0F05000000005A00000156015600F100B9008C00A700E68C948A5050505050505050");
		$placeholderTrainer8[0]["message_start"] = hex2bin("02051C0E03040105");
		$placeholderTrainer8[0]["message_win"] = hex2bin("1E050D0637073205");
		$placeholderTrainer8[0]["message_lose"] = hex2bin("030503060F0E0405");
		
		return $placeholderTrainer8[$number];
	}
	
	function getBattleTowerPlaceholderTrainerES9($number) {
		$placeholderTrainer9 = array();
		
		// Trainer 1
		$placeholderTrainer9[6] = array();
		$placeholderTrainer9[6]["name"] = hex2bin("93808988915050505050");
		$placeholderTrainer9[6]["class"] = hexdec("24");
		$placeholderTrainer9[6]["pokemon1"] = hex2bin("E554F2352E9C00000F4240EA60EA60EA60EA60EA60FDED0F0F140A64000000640000015B015B011400C0011C013800FC878E948D838E8E8C505050");
		$placeholderTrainer9[6]["pokemon2"] = hex2bin("4449EE593FE900000F4240EA60EA60EA60EA60EA60FDEF050A050A6400000064000001790179016400FC00CC00E2010A8C808287808C8F50505050");
		$placeholderTrainer9[6]["pokemon3"] = hex2bin("E69239E19C5C00000F4240EA60EA60EA60EA60EA60DFFE0F140A0A64000000640000015D015D011A011E010A011C011C8A888D8683918050505050");
		$placeholderTrainer9[6]["message_start"] = hex2bin("32050C0D08060208");
		$placeholderTrainer9[6]["message_win"] = hex2bin("090B08062D080C05");
		$placeholderTrainer9[6]["message_lose"] = hex2bin("0C050A051E053908");
		
		// Trainer 2
		$placeholderTrainer9[5] = array();
		$placeholderTrainer9[5]["name"] = hex2bin("95808280505050505050");
		$placeholderTrainer9[5]["class"] = hexdec("1E");
		$placeholderTrainer9[5]["pokemon1"] = hex2bin("8703552E56E700000F4240C350C350C3507530C350FDFE0F14140F640000006400000143014300DC00CE015201340116898E8B93848E8D50505050");
		$placeholderTrainer9[5]["pokemon2"] = hex2bin("80523F59E75500000F4240C350C350C350C350C350FDEF050A0F0F640000006400000155015501220114013400AA00E6938094918E925050505050");
		$placeholderTrainer9[5]["pokemon3"] = hex2bin("3B9235F5E73F00000F4240D6D8C350C350D6D8C350DDEF0F050F056400000064000001760176013200F60119012200FA809182808D888D84505050");
		$placeholderTrainer9[5]["message_start"] = hex2bin("3C04120E03061D04");
		$placeholderTrainer9[5]["message_win"] = hex2bin("0A05230D0C0E2C04");
		$placeholderTrainer9[5]["message_lose"] = hex2bin("0A051D04090B2F07");
		
		// Trainer 3
		$placeholderTrainer9[4] = array();
		$placeholderTrainer9[4]["name"] = hex2bin("828E8BC98D5050505050");
		$placeholderTrainer9[4]["class"] = hexdec("14");
		$placeholderTrainer9[4]["pokemon1"] = hex2bin("068C3559A31300000F4240C350C350D6D8D6D8D6D8FEDF0F0A140F6400000064000001570157010200F70121013701078287809188998091835050");
		$placeholderTrainer9[4]["pokemon2"] = hex2bin("6503565599F300000F4240AFC8C350C350AFC8AFC8FBEF140F0514640000006400000135013500BE00DE016E00F800F8848B848293918E83845050");
		$placeholderTrainer9[4]["pokemon3"] = hex2bin("706D39593F9D00000F4240D6D8C350D6D8C350AFC8FDEF0F0A050A6400000064000001940194015E014900A800B200B2918798838E8D5050505050");
		$placeholderTrainer9[4]["message_start"] = hex2bin("010D0E0E1C072106");
		$placeholderTrainer9[4]["message_win"] = hex2bin("020D03060A0A1A0D");
		$placeholderTrainer9[4]["message_lose"] = hex2bin("0A06090C160D2904");
		
		// Trainer 4
		$placeholderTrainer9[3] = array();
		$placeholderTrainer9[3]["name"] = hex2bin("8C8E9193505050505050");
		$placeholderTrainer9[3]["class"] = hexdec("29");
		$placeholderTrainer9[3]["pokemon1"] = hex2bin("D092593FCFF200000F4240C350C350D6D8EA60C350FDDE0A050F0F6400000064000001570157010401E9009800C600DA929384848B889750505050");
		$placeholderTrainer9[3]["pokemon2"] = hex2bin("165241D33FBD00000F4240D6D8C350C350D6D8C350FDCF1419050A6400000064000001440144010E00D8011F00D400D4858480918E965050505050");
		$placeholderTrainer9[3]["pokemon3"] = hex2bin("C877C3D4DCF700000F4240AFC8C350D6D8C350D6D8BDEF0505140F640000006400000135013500CA00D10102010701078C88928391848095949250");
		$placeholderTrainer9[3]["message_start"] = hex2bin("030D2B0D0103270D");
		$placeholderTrainer9[3]["message_win"] = hex2bin("140E0305080B0405");
		$placeholderTrainer9[3]["message_lose"] = hex2bin("030D260D030D140D");
		
		// Trainer 5
		$placeholderTrainer9[2] = array();
		$placeholderTrainer9[2]["name"] = hex2bin("91808C8E8D8050505050");
		$placeholderTrainer9[2]["class"] = hexdec("27");
		$placeholderTrainer9[2]["pokemon1"] = hex2bin("D78CA33B8AB900000F4240C350C350BB80AFC8C350FDEF14050F1464000000640000012D012D011800C3013C00A000F0928D848092848B50505050");
		$placeholderTrainer9[2]["pokemon2"] = hex2bin("D449D33FA35C00000F4240C350C350C350C350AFC8FBFE1905140A64000000640000014D014D015E011A00DC00C400F6928288998E915050505050");
		$placeholderTrainer9[2]["pokemon3"] = hex2bin("F292553B7E8700000F4240C3509C40C35075307530DDFE0F05050A6400000064000002BF02BF0065006A00BC00E2015A818B889292849850505050");
		$placeholderTrainer9[2]["message_start"] = hex2bin("0D0E110D08063D04");
		$placeholderTrainer9[2]["message_win"] = hex2bin("3A063606280D4004");
		$placeholderTrainer9[2]["message_lose"] = hex2bin("2705090B0B070905");
		
		// Trainer 6
		$placeholderTrainer9[1] = array();
		$placeholderTrainer9[1]["name"] = hex2bin("91948B88505050505050");
		$placeholderTrainer9[1]["class"] = hexdec("2D");
		$placeholderTrainer9[1]["pokemon1"] = hex2bin("DD549C3B3F5900000F4240C350C350C350C350C350FEF70A05050A6400000064000001830183012200F800BE00C200C28F888B8E9296888D845050");
		$placeholderTrainer9[1]["pokemon2"] = hex2bin("67495E5C99CA00000F4240C350C350C350C350C350FEFE0A0A050564000000640000017701770118010200C8015200DA849784868694938E915050");
		$placeholderTrainer9[1]["pokemon3"] = hex2bin("8B9239F63B5C00000F4240C350C350C350C350C350FBE70F05050A64000000640000014B014B00D2014C00C6013000D68E8C809293809150505050");
		$placeholderTrainer9[1]["message_start"] = hex2bin("02031C0E280D0A04");
		$placeholderTrainer9[1]["message_win"] = hex2bin("3E040C062C043604");
		$placeholderTrainer9[1]["message_lose"] = hex2bin("0205220601050606");
		
		// Trainer 7
		$placeholderTrainer9[0] = array();
		$placeholderTrainer9[0]["name"] = hex2bin("C9868D848E5050505050");
		$placeholderTrainer9[0]["class"] = hexdec("30");
		$placeholderTrainer9[0]["pokemon1"] = hex2bin("4C0399599D7E00000F4240753075307530753075307446050A0A050000000064000001490149011A013C009200AA00BE868E8B848C505050505050");
		$placeholderTrainer9[0]["pokemon2"] = hex2bin("6B774407090800000F4240753075307530753075306776140F0F0F0000000064000001090109010E00DC00D6008201188788938C8E8D8287808D50");
		$placeholderTrainer9[0]["pokemon3"] = hex2bin("AB4939F0C06D00000F42407530753075307530753076570F05050A0000000064000001A901A900B200B000C000D600D68B808D9394918D50505050");
		$placeholderTrainer9[0]["message_start"] = hex2bin("0B02020237041B0E");
		$placeholderTrainer9[0]["message_win"] = hex2bin("24040D0D08060208");
		$placeholderTrainer9[0]["message_lose"] = hex2bin("3204090B08060908");
		
		return $placeholderTrainer9[$number];
	}	
?>
