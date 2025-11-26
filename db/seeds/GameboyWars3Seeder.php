<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

/**
 * Seed Game Boy Wars 3 data from binary files
 *
 * Data files are located in db/seeds/gbwars3/:
 * - maps/map_XXXX.cgb - Map files
 * - messages_j/mbox_XX.cgb - Japanese messages
 * - messages_e/mbox_XX.cgb - English messages
 *
 * Usage: vendor/bin/phinx seed:run -s GameboyWars3Seeder
 */
class GameboyWars3Seeder extends AbstractSeed
{
    public function run(): void
    {
        require_once dirname(__DIR__) . '/web/classes/GameboyWars3Util.php';

        $this->importMaps();
        $this->importMessages();
    }

    private function importMaps(): void
    {
        echo "=== Importing Maps ===\n";

        $mapDir = __DIR__ . '/gbwars3/maps';
        $mapFiles = glob($mapDir . '/map_*.cgb');

        if (empty($mapFiles)) {
            echo "  No map files found in $mapDir\n";
            return;
        }

        $mapsTable = $this->table('bww_maps');
        $imported = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($mapFiles as $mapFile) {
            if (!preg_match('#/map_(\d{4})\.cgb$#', $mapFile, $matches)) {
                echo "  SKIP: Invalid filename: " . basename($mapFile) . "\n";
                $skipped++;
                continue;
            }

            $mapId = $matches[1];

            $exists = $this->fetchRow("SELECT id FROM bww_maps WHERE map_id = '$mapId'");
            if ($exists) {
                echo "  SKIP: Map $mapId already exists\n";
                $skipped++;
                continue;
            }

            try {
                $mapData = GameboyWars3Util::importMapFile($mapFile);

                $fullData = file_get_contents($mapFile);
                if (!GameboyWars3Util::validateMap($fullData, true)) {
                    echo "  WARN: Map $mapId has invalid checksum (importing anyway)\n";
                }

                $decodedName = GameboyWars3Util::decodeMapName($mapData['map_name']);
                if ($decodedName === '') {
                    $decodedName = "Map $mapId";
                }

                $mapsTable->insert([
                    'map_id' => $mapId,
                    'map_name' => $decodedName,
                    'width' => $mapData['width'],
                    'height' => $mapData['height'],
                    'price_yen' => 10,
                    'map_data' => $mapData['map_data'],
                    'is_active' => 1,
                ])->saveData();

                echo "  OK: Map $mapId '$decodedName' ({$mapData['width']}x{$mapData['height']})\n";
                $imported++;

            } catch (Exception $e) {
                echo "  ERROR: Map $mapId - " . $e->getMessage() . "\n";
                $errors++;
            }
        }

        echo "Maps: $imported imported, $skipped skipped, $errors errors\n\n";
    }

    private function importMessages(): void
    {
        echo "=== Importing Messages ===\n";

        $regions = [
            'j' => __DIR__ . '/gbwars3/messages_j',
            'e' => __DIR__ . '/gbwars3/messages_e',
        ];

        $messagesTable = $this->table('bww_messages');
        $imported = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($regions as $regionCode => $mboxDir) {
            echo "\n--- Region: $regionCode ---\n";

            $mboxFiles = glob($mboxDir . '/mbox_*.cgb');

            if (empty($mboxFiles)) {
                echo "  No message files found\n";
                continue;
            }

            foreach ($mboxFiles as $mboxFile) {
                if (!preg_match('#/mbox_(\d{2})\.cgb$#', $mboxFile, $matches)) {
                    echo "  SKIP: Invalid filename: " . basename($mboxFile) . "\n";
                    $skipped++;
                    continue;
                }

                $mailboxId = (int)$matches[1];

                $exists = $this->fetchRow(
                    "SELECT id FROM bww_messages WHERE game_region = '$regionCode' AND mailbox_id = $mailboxId"
                );
                if ($exists) {
                    echo "  SKIP: Message $mailboxId already exists\n";
                    $skipped++;
                    continue;
                }

                try {
                    $messageData = file_get_contents($mboxFile);
                    if ($messageData === false) {
                        throw new RuntimeException("Could not read file");
                    }

                    // Extract subject from message
                    $subject = null;
                    if (preg_match('/^BD=[0-9A-F]{2}\r?\n(.+?)\r?\n/s', $messageData, $match)) {
                        $subject = trim($match[1]);
                    } elseif (preg_match('/^       (.+?)\r?\n/s', $messageData, $match)) {
                        $subject = trim($match[1]);
                    }

                    $messagesTable->insert([
                        'game_region' => $regionCode,
                        'mailbox_id' => $mailboxId,
                        'serial_number' => '0001',
                        'subject' => $subject,
                        'message_data' => $messageData,
                        'is_active' => 1,
                    ])->saveData();

                    $subjectDisplay = $subject ? substr($subject, 0, 30) : '(no subject)';
                    echo "  OK: Message $mailboxId - $subjectDisplay\n";
                    $imported++;

                } catch (Exception $e) {
                    echo "  ERROR: Message $mailboxId - " . $e->getMessage() . "\n";
                    $errors++;
                }
            }
        }

        echo "\nMessages: $imported imported, $skipped skipped, $errors errors\n";
    }
}
