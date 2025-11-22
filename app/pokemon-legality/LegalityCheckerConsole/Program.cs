// SPDX-License-Identifier: MIT

using System;
using System.IO;
using System.Linq;
using System.Text.Json;
using PKHeX.Core;

namespace LegalityCheckerConsole
{
    internal static class Program
    {
        private const int SIZE_2_STORED = 32;
        private const int SIZE_2_PARTY  = 48;
        private const int SIZE_2_ULIST  = 73;
        private const int SIZE_2_JLIST  = 63;

        // BXT/Mobile Trade Corner (JP) sizes
        private const int SIZE_BXT_FULL  = 143;
        private const int SIZE_BXT_EMAIL = 105;

        // Trade Corner EN trade blob size (DB format)
        private const int SIZE_TC_PKM = 65;

        // Battle Tower Pokémon blob sizes
        // EN/intl: 48 core + 11 nickname
        private const int SIZE_BT_PKM_INT = 59;
        // JP: 48 core + 6 unknown tail (no complete nickname/OT)
        private const int SIZE_BT_PKM_JP  = 54;        // Gen 2 PKM string lengths (OT/Nickname)
        private const int SIZE_G2_STRING    = 11; // non-JP (EN/etc)
        private const int SIZE_G2_STRING_JP = 6;  // JP-only buffers
private static PK2 LoadPk2(byte[] data)
        {
            int len = data.Length;
            Console.Error.WriteLine($"[LegalityCheckerConsole BXT] input_len={len}");

            // Plain Gen 2 PKM bodies
            if (len == SIZE_2_STORED || len == SIZE_2_PARTY)
            {
                Console.Error.WriteLine("[LegalityCheckerConsole BXT] loader=PK2(core)");
                return new PK2(data, jp: false);
            }

            // Trade Corner 65-byte trade blob: 48 core + 7 OT + 10 Nick
            if (len == SIZE_TC_PKM)
            {
                Console.Error.WriteLine("[LegalityCheckerConsole BXT] loader=TradeCornerBlob65");
                return LoadPk2FromTradeCornerBlob65(data);
            }

            // Battle Tower 59-byte Pokémon blob (intl): 48 core + 11 nickname
            if (len == SIZE_BT_PKM_INT)
            {
                Console.Error.WriteLine("[LegalityCheckerConsole BXT] loader=BattleTowerBlob59");
                return LoadPk2FromBattleTowerBlob59(data);
            }

            // Battle Tower 54-byte Pokémon blob (JP): 48 core + 6 tail bytes
            if (len == SIZE_BT_PKM_JP)
            {
                Console.Error.WriteLine("[LegalityCheckerConsole BXT] loader=JP_BattleTowerBlob54");
                return LoadPk2FromJP_BattleTowerBlob54(data);
            }

            // 73-byte: either PKHeX ULIST or old padded 65→73 blob
            if (len == SIZE_2_ULIST)
            {
                // PKHeX ULIST single containers begin with 0x01 (header)
                if (data.Length >= 1 && data[0] == 0x01)
                {
                    Console.Error.WriteLine("[LegalityCheckerConsole BXT] loader=ULIST(PokeList2.ReadFromSingle)");
                    return PokeList2.ReadFromSingle(data);
                }

                Console.Error.WriteLine("[LegalityCheckerConsole BXT] loader=TradeCornerBlob73(padded)");
                return LoadPk2FromTradeCornerBlob73(data);
            }

            // JP BXT/Mobile Trade Corner blobs (143-byte full or 105-byte email body)
            if (len == SIZE_BXT_FULL || len == SIZE_BXT_EMAIL)
            {
                Console.Error.WriteLine("[LegalityCheckerConsole BXT] loader=BXTTradeBlobJP");
                return LoadPk2FromBxtTradeBlobJP(data);
            }

            // JP single-entry list or JP minimal trade blob (63 bytes)
            if (len == SIZE_2_JLIST)
            {
                // PKHeX JLIST single containers begin with 0x01 (header)
                if (data.Length >= 1 && data[0] == 0x01)
                {
                    Console.Error.WriteLine("[LegalityCheckerConsole BXT] loader=JLIST(PokeList2.ReadFromSingle)");
                    return PokeList2.ReadFromSingle(data);
                }

                Console.Error.WriteLine("[LegalityCheckerConsole BXT] loader=BXT_JP_Trade63");
                return LoadPk2FromBxtTradeBlobJP63(data);
            }

            // Fallback: match PKHeX list lengths in case constants change upstream
            int listEN = PokeList2.GetListLengthSingle(jp: false);
            int listJP = PokeList2.GetListLengthSingle(jp: true);

            if (len == listEN || len == listJP)
            {
                Console.Error.WriteLine("[LegalityCheckerConsole BXT] loader=PokeList2.ReadFromSingle(len-match)");
                return PokeList2.ReadFromSingle(data);
            }

            throw new InvalidDataException(
                $"Unsupported Gen2 PKM/BXT container length: {len} bytes. " +
                $"Expected {SIZE_2_STORED}, {SIZE_2_PARTY}, {SIZE_TC_PKM}, " +
                $"{SIZE_BT_PKM_INT}, {SIZE_BT_PKM_JP}, {SIZE_2_ULIST}/{SIZE_2_JLIST}, " +
                $"{SIZE_BXT_FULL}, or {SIZE_BXT_EMAIL}.");
        }

        /// <summary>
        /// Battle Tower 59-byte Pokémon blob (intl) -> PK2.
        /// Layout:
        ///   [0..47]  48-byte PK2 party core
        ///   [48..58] 11-byte nickname (Gen 2 string)
        /// OT is not present; synthesize OT = "KRIS" (Gen2 EN),
        /// then pad with 0x50, matching trade-blob padding approach.
        /// </summary>
        private static PK2 LoadPk2FromBattleTowerBlob59(ReadOnlySpan<byte> blob)
        {
            if (blob.Length != SIZE_BT_PKM_INT)
                throw new InvalidDataException($"BattleTowerBlob59: unexpected length {blob.Length}");

            ReadOnlySpan<byte> core48   = blob.Slice(0, SIZE_2_PARTY);             // 0..47
            ReadOnlySpan<byte> nickPart = blob.Slice(SIZE_2_PARTY, SIZE_G2_STRING); // 48..58

            Console.Error.WriteLine("[LegalityCheckerConsole BXT] BattleTowerBlob59 core_species=" + core48[0]);

            Span<byte> ot11   = stackalloc byte[SIZE_G2_STRING];
            Span<byte> nick11 = stackalloc byte[SIZE_G2_STRING];

            ot11.Fill(0x50);
            nick11.Fill(0x50);

            // copy nickname from blob (11 bytes)
            nickPart.CopyTo(nick11);

            // Synthesize OT = "KRIS" in Gen 2 EN charset:
            // A=0x80, so: K=0x8A, R=0x91, I=0x88, S=0x92
            ot11[0] = 0x8A; // K
            ot11[1] = 0x91; // R
            ot11[2] = 0x88; // I
            ot11[3] = 0x92; // S

            return new PK2(core48, ot11, nick11);
        }

        /// <summary>
        /// Battle Tower 54-byte Pokémon blob (JP) -> PK2.
        ///   [0..47] 48-byte PK2 party core (matches pk2[3..50])
        ///   [48..53] 6 tail bytes (do not form a complete nickname)
        /// No OT is present, and nickname is incomplete.
        /// We:
        ///   - use the 48-byte core as-is,
        ///   - synthesize OT = "クリス" (Gen2 JP),
        ///   - build a nickname from the 6 tail bytes, padded with 0x50.
        /// This preserves legality-critical data (core48); OT/nickname content
        /// is not used by PKHeX for any Gen 2 legality condition beyond length.
        /// </summary>
        private static PK2 LoadPk2FromJP_BattleTowerBlob54(ReadOnlySpan<byte> blob)
{
    if (blob.Length != SIZE_BT_PKM_JP)
        throw new InvalidDataException($"JP_BattleTowerBlob54: unexpected length {blob.Length}");

    ReadOnlySpan<byte> core48 = blob.Slice(0, SIZE_2_PARTY);   // 0..47
    ReadOnlySpan<byte> tail   = blob.Slice(SIZE_2_PARTY);      // 48..53 (6 bytes)

    Console.Error.WriteLine("[LegalityCheckerConsole BXT] JP_BattleTowerBlob54 core_species=" + core48[0]);

    // For JP, PKHeX decides charset from OT buffer length (6 => Japanese).
    Span<byte> ot6   = stackalloc byte[SIZE_G2_STRING_JP];
    Span<byte> nick6 = stackalloc byte[SIZE_G2_STRING_JP];

    ot6.Fill(0x50);
    nick6.Fill(0x50);

    // Optional: keep the tail bytes in the nickname (best-effort preservation)
    int copyLen = Math.Min(tail.Length, SIZE_G2_STRING_JP);
    if (copyLen > 0)
        tail.Slice(0, copyLen).CopyTo(nick6);

    // Synthesize OT = "クリス" in Gen 2 JP charset:
    // A=0x80, so: ク=0x87, リ=0xD8, ス=0x8C, terminator=0x50
    ot6[0] = 0x87; // ク
    ot6[1] = 0xD8; // リ
    ot6[2] = 0x8C; // ス
    ot6[3] = 0x50; // terminator

    return new PK2(core48, ot6, nick6);
}


        /// <summary>
        /// Trade Corner 65-byte trade blob -> PK2
        /// Layout:
        ///   [0..47]  48-byte PK2 party core
        ///   [48..54] 7 bytes OT
        ///   [55..64] 10 bytes nickname
        /// We pad OT (7→11) and Nick (10→11) with 0x50 for PK2.
        /// </summary>
        private static PK2 LoadPk2FromTradeCornerBlob65(ReadOnlySpan<byte> blob)
        {
            if (blob.Length != SIZE_TC_PKM)
                throw new InvalidDataException($"TradeCornerBlob65: unexpected length {blob.Length}");

            ReadOnlySpan<byte> core48   = blob.Slice(0, SIZE_2_PARTY); // 0..47
            ReadOnlySpan<byte> otPart   = blob.Slice(48, 7);           // 48..54
            ReadOnlySpan<byte> nickPart = blob.Slice(55, 10);          // 55..64

            Console.Error.WriteLine("[LegalityCheckerConsole BXT] TradeCornerBlob65 core_species=" + core48[0]);

            Span<byte> ot11   = stackalloc byte[SIZE_G2_STRING];
            Span<byte> nick11 = stackalloc byte[SIZE_G2_STRING];

            ot11.Fill(0x50);
            nick11.Fill(0x50);

            otPart.CopyTo(ot11);
            nickPart.CopyTo(nick11);

            return new PK2(core48, ot11, nick11);
        }

        /// <summary>
        /// Old padded 73-byte Trade Corner blob (65 bytes + 8 zeros) -> PK2.
        /// Uses the same mapping but ignores trailing padding.
        /// </summary>
        private static PK2 LoadPk2FromTradeCornerBlob73(ReadOnlySpan<byte> blob)
        {
            if (blob.Length != SIZE_2_ULIST)
                throw new InvalidDataException($"TradeCornerBlob73: unexpected length {blob.Length}");

            ReadOnlySpan<byte> core48   = blob.Slice(0, SIZE_2_PARTY); // 0..47
            ReadOnlySpan<byte> otPart   = blob.Slice(48, 7);           // 48..54
            ReadOnlySpan<byte> nickPart = blob.Slice(55, 10);          // 55..64; 65..72 padding zeros

            Console.Error.WriteLine("[LegalityCheckerConsole BXT] TradeCornerBlob73 core_species=" + core48[0]);

            Span<byte> ot11   = stackalloc byte[SIZE_G2_STRING];
            Span<byte> nick11 = stackalloc byte[SIZE_G2_STRING];

            ot11.Fill(0x50);
            nick11.Fill(0x50);

            otPart.CopyTo(ot11);
            nickPart.CopyTo(nick11);

            return new PK2(core48, ot11, nick11);
        }

        /// <summary>
        /// JP BXT Trade Corner blob (143-byte full or 105-byte email body) -> PK2,
        /// using the documented Mobile Adapter GB layout.
        /// </summary>
        private static PK2 LoadPk2FromBxtTradeBlobJP(ReadOnlySpan<byte> bxt)
        {
            int len = bxt.Length;
            int baseOffset;

            if (len == SIZE_BXT_FULL)
                baseOffset = 0x26;
            else if (len == SIZE_BXT_EMAIL)
                baseOffset = 0x00;
            else
                throw new InvalidDataException($"Unsupported BXT blob length: {len}");

            const int nameCoreLen = 5; // JP: 5-byte trainer/OT/nickname

            int trainerNameOffset = baseOffset + 0;
            int coreOffset        = baseOffset + nameCoreLen;
            int otOffset          = coreOffset + SIZE_2_PARTY;
            int nickOffset        = otOffset + nameCoreLen;

            if (coreOffset + SIZE_2_PARTY > bxt.Length ||
                nickOffset + nameCoreLen > bxt.Length)
            {
                throw new InvalidDataException(
                    $"BXT blob length {len} too short for expected Trade Corner layout.");
            }

            ReadOnlySpan<byte> core48 = bxt.Slice(coreOffset, SIZE_2_PARTY);
            ReadOnlySpan<byte> otN    = bxt.Slice(otOffset, nameCoreLen);
            ReadOnlySpan<byte> nickN  = bxt.Slice(nickOffset, nameCoreLen);

            Console.Error.WriteLine($"[LegalityCheckerConsole BXT] BXT_JP base={baseOffset}, core_species={core48[0]}");

            Span<byte> ot11   = stackalloc byte[SIZE_G2_STRING];
            Span<byte> nick11 = stackalloc byte[SIZE_G2_STRING];

            ot11.Fill(0x50);
            nick11.Fill(0x50);

            otN.CopyTo(ot11);
            nickN.CopyTo(nick11);

            return new PK2(core48, ot11, nick11);
        }

        /// <summary>
        /// JP minimal 63-byte trade blob (core + two 5-byte names) -> PK2.
        /// Layout as observed from your BXTJ Snubbull pair:
        ///   [0..47]  48-byte PK2 party core
        ///   [48..52] 5-byte OT (JP)
        ///   [53..57] 5-byte nickname (JP)
        ///   [58..62] padding (0x00)
        /// We pad both OT and Nick to 11 bytes with 0x50 to satisfy PK2.
        /// </summary>
        private static PK2 LoadPk2FromBxtTradeBlobJP63(ReadOnlySpan<byte> bxt)
{
    if (bxt.Length != SIZE_2_JLIST)
        throw new InvalidDataException($"BXTTradeBlobJP63: unexpected length {bxt.Length}");

    ReadOnlySpan<byte> core48 = bxt.Slice(0, SIZE_2_PARTY);      // 0..47
    ReadOnlySpan<byte> ot5    = bxt.Slice(SIZE_2_PARTY, 5);      // 48..52
    ReadOnlySpan<byte> nick5  = bxt.Slice(SIZE_2_PARTY + 5, 5);  // 53..57

    Console.Error.WriteLine("[LegalityCheckerConsole BXT] BXT_JP_Trade63 core_species=" + core48[0]);

    // For JP, PKHeX decides charset from OT buffer length (6 => Japanese).
    Span<byte> ot6   = stackalloc byte[SIZE_G2_STRING_JP];
    Span<byte> nick6 = stackalloc byte[SIZE_G2_STRING_JP];

    ot6.Fill(0x50);
    nick6.Fill(0x50);

    // Copy up to 5 chars into the 6-byte JP buffers (last stays 0x50 terminator).
    ot5.CopyTo(ot6);
    nick5.CopyTo(nick6);

    return new PK2(core48, ot6, nick6);
}


        private sealed class IssueDto
        {
            public string id  { get; set; } = string.Empty;
            public string? sev { get; set; }
            public string msg { get; set; } = string.Empty;
        }

        
        private sealed class ResultDto
        {
            public bool   ok        { get; set; }
            public string species   { get; set; } = string.Empty;
            public int    speciesId { get; set; }
            public int    level     { get; set; }
            public string nickname  { get; set; } = string.Empty;
            public string trainerOT { get; set; } = string.Empty;
            public int    TID       { get; set; }
            public int    SID       { get; set; }
            public string language      { get; set; } = string.Empty;
            public string languageName  { get; set; } = string.Empty;
            public string version       { get; set; } = string.Empty;
            public bool   shiny         { get; set; }
            public int    heldItem      { get; set; }
            public bool   isEgg         { get; set; }

            // Extended details used by the PHP summarizer for pokemon_decode
            public int    gender        { get; set; }
            public int    friendship    { get; set; }
            public uint   exp           { get; set; }

            public int    metLocation   { get; set; }
            public int    metLevel      { get; set; }
            public int    timeOfDay     { get; set; }

            // IVs
            public int    ivHP          { get; set; }
            public int    ivATK         { get; set; }
            public int    ivDEF         { get; set; }
            public int    ivSPA         { get; set; }
            public int    ivSPD         { get; set; }
            public int    ivSPE         { get; set; }

            // Battle stats
            public int    statHP        { get; set; }
            public int    statATK       { get; set; }
            public int    statDEF       { get; set; }
            public int    statSPA       { get; set; }
            public int    statSPD       { get; set; }
            public int    statSPE       { get; set; }

            // Moves
            public int    move1         { get; set; }
            public int    move2         { get; set; }
            public int    move3         { get; set; }
            public int    move4         { get; set; }

            public IssueDto[] issues    { get; set; } = Array.Empty<IssueDto>();
        }

private static int Main(string[] args)
        {
            if (args.Length != 1)
            {
                Console.Error.WriteLine("Usage: LegalityCheckerConsole <path or ->");
                return 1;
            }

            byte[] raw;
            string arg = args[0];

            try
            {
                if (arg == "-" || arg == "/dev/stdin")
                {
                    using var ms    = new MemoryStream();
                    using var stdin = Console.OpenStandardInput();
                    stdin.CopyTo(ms);
                    raw = ms.ToArray();
                }
                else
                {
                    if (!File.Exists(arg))
                    {
                        Console.Error.WriteLine($"File not found: {arg}");
                        return 1;
                    }

                    raw = File.ReadAllBytes(arg);
                }

                var pk = LoadPk2(raw);
                var la = new LegalityAnalysis(pk);

                
                // Compute full stats for summary output (pokemon_decode, Battle Tower, etc.)
                var stats = pk.GetStats(pk.PersonalInfo);

                var dto = new ResultDto
                {
                    ok           = la.Valid,
                    speciesId    = pk.Species,
                    species      = ((Species)pk.Species).ToString(),
                    level        = pk.CurrentLevel,
                    nickname     = pk.Nickname,
                    trainerOT    = pk.OriginalTrainerName,
                    TID          = pk.TID16,
                    SID          = pk.SID16,
                    language     = pk.Language.ToString(),
                    languageName = pk.Language.ToString(),
                    version      = pk.Version.ToString(),
                    shiny        = pk.IsShiny,
                    heldItem     = pk.HeldItem,
                    isEgg        = pk.IsEgg,

                    gender       = pk.Gender,
                    friendship   = pk.CurrentFriendship,
                    exp          = pk.EXP,

                    metLocation  = pk.MetLocation,
                    metLevel     = pk.MetLevel,
                    timeOfDay    = pk.MetTimeOfDay,

                    ivHP         = pk.IV_HP,
                    ivATK        = pk.IV_ATK,
                    ivDEF        = pk.IV_DEF,
                    ivSPA        = pk.IV_SPA,
                    ivSPD        = pk.IV_SPD,
                    ivSPE        = pk.IV_SPE,

                    // PKHeX.GetStats returns H/A/B/S/C/D -> HP/ATK/DEF/SPE/SPA/SPD
                    statHP       = stats[0],
                    statATK      = stats[1],
                    statDEF      = stats[2],
                    statSPA      = stats[4],
                    statSPD      = stats[5],
                    statSPE      = stats[3],

                    move1        = pk.Move1,
                    move2        = pk.Move2,
                    move3        = pk.Move3,
                    move4        = pk.Move4,

                    issues       = la.Results
                        .Select(r => new IssueDto
                        {
                            id  = r.Identifier.ToString(),
                            sev = r.Judgement == Severity.Valid ? null : r.Judgement.ToString(),
                            msg = r.ToString(),
                        })
                        .ToArray()
                };

                var json = JsonSerializer.Serialize(dto, new JsonSerializerOptions
                {
                    WriteIndented = false
                });



                Console.Out.WriteLine(json);

                return la.Valid ? 0 : 2;
            }
            catch (Exception ex)
            {
                Console.Error.WriteLine(ex.ToString());
                return 1;
            }
        }
    }
}
