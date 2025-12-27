<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Adds message_start and message_start_decode to bxt_battle_tower_honor_roll,
 * positioned immediately after class_decode:
 *   | class_decode | message_start | message_start_decode |
 *
 * This migration is written for Phinx 0.16.x (no AbstractMigration::hasColumn()).
 * It uses INFORMATION_SCHEMA checks and is idempotent.
 *
 * Column definitions are copied from bxt_battle_tower_records.message_start
 * and bxt_battle_tower_records.message_start_decode when available.
 */
final class BattleTowerAddMessageStartToHonorRoll extends AbstractMigration
{
    private function tableExists(string $table): bool
    {
        $pdo = $this->getAdapter()->getConnection();
        $row = $this->fetchRow(
            "SELECT 1 AS ok
             FROM INFORMATION_SCHEMA.TABLES
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = " . $pdo->quote($table) . "
             LIMIT 1"
        );
        return (bool)$row;
    }

    private function columnExists(string $table, string $column): bool
    {
        $pdo = $this->getAdapter()->getConnection();
        $row = $this->fetchRow(
            "SELECT 1 AS ok
             FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = " . $pdo->quote($table) . "
               AND COLUMN_NAME = " . $pdo->quote($column) . "
             LIMIT 1"
        );
        return (bool)$row;
    }

    /**
     * Returns INFORMATION_SCHEMA.COLUMNS fields for a specific column.
     *
     * @return array<string, mixed>|null
     */
    private function getColumnSpec(string $table, string $column): ?array
    {
        $pdo = $this->getAdapter()->getConnection();
        $row = $this->fetchRow(
            "SELECT
                COLUMN_NAME,
                COLUMN_TYPE,
                DATA_TYPE,
                IS_NULLABLE,
                COLUMN_DEFAULT,
                EXTRA,
                COLUMN_COMMENT,
                CHARACTER_SET_NAME,
                COLLATION_NAME
             FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = " . $pdo->quote($table) . "
               AND COLUMN_NAME = " . $pdo->quote($column) . "
             LIMIT 1"
        );

        return $row ?: null;
    }

    /**
     * Builds a column definition fragment:
     *   `col` <COLUMN_TYPE> [CHARSET/COLLATE] [NULL/NOT NULL] [DEFAULT ...] [EXTRA] [COMMENT ...]
     */
    private function buildColumnDefinitionSql(array $spec, string $newName): string
    {
        $pdo = $this->getAdapter()->getConnection();

        $type = (string)$spec['COLUMN_TYPE']; // includes length + unsigned etc.
        $dataType = strtolower((string)$spec['DATA_TYPE']);

        $sql = "`{$newName}` {$type}";

        // Preserve charset/collation for character types when present.
        if (!empty($spec['CHARACTER_SET_NAME']) && !empty($spec['COLLATION_NAME'])) {
            $charset = (string)$spec['CHARACTER_SET_NAME'];
            $collation = (string)$spec['COLLATION_NAME'];
            $sql .= " CHARACTER SET {$charset} COLLATE {$collation}";
        }

        $nullable = ((string)$spec['IS_NULLABLE'] === 'YES');
        $sql .= $nullable ? " NULL" : " NOT NULL";

        // DEFAULT handling
        $default = $spec['COLUMN_DEFAULT']; // can be null
        if ($default === null) {
            if ($nullable) {
                $sql .= " DEFAULT NULL";
            }
        } else {
            $defaultStr = (string)$default;

            // Recognize MySQL temporal defaults and other expressions.
            $upper = strtoupper($defaultStr);
            $isFuncDefault = in_array($upper, ['CURRENT_TIMESTAMP', 'CURRENT_TIMESTAMP()', 'NOW()', 'LOCALTIME', 'LOCALTIMESTAMP'], true);

            if ($isFuncDefault) {
                $sql .= " DEFAULT {$upper}";
            } else {
                $numericTypes = [
                    'tinyint','smallint','mediumint','int','integer','bigint',
                    'decimal','numeric','float','double','real',
                    'bit','bool','boolean',
                ];

                if (in_array($dataType, $numericTypes, true)) {
                    $sql .= " DEFAULT {$defaultStr}";
                } else {
                    $sql .= " DEFAULT " . $pdo->quote($defaultStr);
                }
            }
        }

        // Preserve EXTRA (e.g., "on update CURRENT_TIMESTAMP" if it ever applies)
        if (!empty($spec['EXTRA'])) {
            $extra = trim((string)$spec['EXTRA']);
            // Avoid duplicating auto_increment (not expected here, but harmless guard)
            if ($extra !== '' && stripos($extra, 'auto_increment') === false) {
                $sql .= " {$extra}";
            }
        }

        // COMMENT
        if (isset($spec['COLUMN_COMMENT']) && (string)$spec['COLUMN_COMMENT'] !== '') {
            $sql .= " COMMENT " . $pdo->quote((string)$spec['COLUMN_COMMENT']);
        }

        return $sql;
    }

    public function up(): void
    {
        $target = 'bxt_battle_tower_honor_roll';
        if (!$this->tableExists($target)) {
            return;
        }

        // Determine the "AFTER" anchor column.
        $afterClassDecode = $this->columnExists($target, 'class_decode') ? 'class_decode'
            : ($this->columnExists($target, 'class') ? 'class' : null);

        // Prefer copying definitions from bxt_battle_tower_records.
        $source = 'bxt_battle_tower_records';
        $srcHasMessageStart = $this->tableExists($source) && $this->columnExists($source, 'message_start');
        $srcHasMessageStartDecode = $this->tableExists($source) && $this->columnExists($source, 'message_start_decode');

        // Fallback: copy decode column's charset/collation from honor_roll.class_decode if needed.
        $fallbackDecodeSpec = $this->getColumnSpec($target, 'class_decode');

        if (!$this->columnExists($target, 'message_start')) {
            $spec = $srcHasMessageStart ? $this->getColumnSpec($source, 'message_start') : null;

            if ($spec) {
                $colSql = $this->buildColumnDefinitionSql($spec, 'message_start');
                $afterSql = $afterClassDecode ? " AFTER `{$afterClassDecode}`" : "";
                $this->execute("ALTER TABLE `{$target}` ADD COLUMN {$colSql}{$afterSql}");
            } else {
                // Conservative fallback: unsigned int, nullable.
                $afterSql = $afterClassDecode ? " AFTER `{$afterClassDecode}`" : "";
                $this->execute("ALTER TABLE `{$target}` ADD COLUMN `message_start` int(10) unsigned NULL DEFAULT NULL{$afterSql}");
            }
        }

        if (!$this->columnExists($target, 'message_start_decode')) {
            $spec = $srcHasMessageStartDecode ? $this->getColumnSpec($source, 'message_start_decode') : null;

            if ($spec) {
                $colSql = $this->buildColumnDefinitionSql($spec, 'message_start_decode');
                $this->execute("ALTER TABLE `{$target}` ADD COLUMN {$colSql} AFTER `message_start`");
            } elseif ($fallbackDecodeSpec) {
                // If we couldn't read records, mirror the existing decode-column format.
                $colSql = $this->buildColumnDefinitionSql($fallbackDecodeSpec, 'message_start_decode');
                $this->execute("ALTER TABLE `{$target}` ADD COLUMN {$colSql} AFTER `message_start`");
            } else {
                // Last-resort fallback.
                $this->execute("ALTER TABLE `{$target}` ADD COLUMN `message_start_decode` varchar(50) NULL DEFAULT NULL AFTER `message_start`");
            }
        }
    }

    public function down(): void
    {
        $target = 'bxt_battle_tower_honor_roll';
        if (!$this->tableExists($target)) {
            return;
        }

        $t = $this->table($target);

        if ($this->columnExists($target, 'message_start_decode')) {
            $t->removeColumn('message_start_decode');
        }
        if ($this->columnExists($target, 'message_start')) {
            $t->removeColumn('message_start');
        }

        $t->update();
    }
}
