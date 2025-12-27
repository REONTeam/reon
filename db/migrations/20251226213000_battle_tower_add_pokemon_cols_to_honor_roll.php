<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Adds the following columns to bxt_battle_tower_honor_roll, positioned after class_decode:
 *   | class_decode | pokemon1 | pokemon1_decode | pokemon2 | pokemon2_decode | pokemon3 | pokemon3_decode | message_start | message_start_decode |
 *
 * Column definitions are copied from bxt_battle_tower_records when available.
 * If message_start/message_start_decode already exist, they are re-positioned to the right of pokemon3_decode.
 *
 * Phinx 0.16.x compatible (no AbstractMigration::hasColumn()).
 * Uses INFORMATION_SCHEMA checks; safe to run multiple times.
 */
final class BattleTowerAddPokemonColsToHonorRoll extends AbstractMigration
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

        if (!empty($spec['EXTRA'])) {
            $extra = trim((string)$spec['EXTRA']);
            if ($extra !== '' && stripos($extra, 'auto_increment') === false) {
                $sql .= " {$extra}";
            }
        }

        if (isset($spec['COLUMN_COMMENT']) && (string)$spec['COLUMN_COMMENT'] !== '') {
            $sql .= " COMMENT " . $pdo->quote((string)$spec['COLUMN_COMMENT']);
        }

        return $sql;
    }

    private function addColumnLike(string $targetTable, string $newCol, ?array $sourceSpec, string $afterCol, string $fallbackSql): void
    {
        if ($this->columnExists($targetTable, $newCol)) {
            return;
        }

        $colSql = $sourceSpec
            ? $this->buildColumnDefinitionSql($sourceSpec, $newCol)
            : "`{$newCol}` {$fallbackSql}";

        $this->execute("ALTER TABLE `{$targetTable}` ADD COLUMN {$colSql} AFTER `{$afterCol}`");
    }

    private function modifyColumnPosition(string $targetTable, string $col, string $afterCol, ?array $spec, string $fallbackSql): void
    {
        if (!$this->columnExists($targetTable, $col)) {
            return;
        }

        $colSql = $spec
            ? $this->buildColumnDefinitionSql($spec, $col)
            : "`{$col}` {$fallbackSql}";

        $this->execute("ALTER TABLE `{$targetTable}` MODIFY COLUMN {$colSql} AFTER `{$afterCol}`");
    }

    public function up(): void
    {
        $target = 'bxt_battle_tower_honor_roll';
        if (!$this->tableExists($target)) {
            return;
        }

        // Anchor after class_decode (or class if decode isn't present).
        $anchor = $this->columnExists($target, 'class_decode') ? 'class_decode'
            : ($this->columnExists($target, 'class') ? 'class' : null);

        if ($anchor === null) {
            // Unexpected schema; avoid making a wrong assumption.
            return;
        }

        // Source specs from records table (preferred).
        $source = 'bxt_battle_tower_records';
        $srcTableOk = $this->tableExists($source);

        $specP1  = $srcTableOk ? $this->getColumnSpec($source, 'pokemon1') : null;
        $specP1d = $srcTableOk ? $this->getColumnSpec($source, 'pokemon1_decode') : null;
        $specP2  = $srcTableOk ? $this->getColumnSpec($source, 'pokemon2') : null;
        $specP2d = $srcTableOk ? $this->getColumnSpec($source, 'pokemon2_decode') : null;
        $specP3  = $srcTableOk ? $this->getColumnSpec($source, 'pokemon3') : null;
        $specP3d = $srcTableOk ? $this->getColumnSpec($source, 'pokemon3_decode') : null;

        // Reasonable fallbacks if records isn't available:
        // - pokemon# as BLOB nullable
        // - pokemon#_decode as varchar(50) nullable (mirrors typical *_decode pattern)
        $this->addColumnLike($target, 'pokemon1',        $specP1,  $anchor,           "blob NULL");
        $this->addColumnLike($target, 'pokemon1_decode', $specP1d, 'pokemon1',        "varchar(50) NULL DEFAULT NULL");
        $this->addColumnLike($target, 'pokemon2',        $specP2,  'pokemon1_decode', "blob NULL");
        $this->addColumnLike($target, 'pokemon2_decode', $specP2d, 'pokemon2',        "varchar(50) NULL DEFAULT NULL");
        $this->addColumnLike($target, 'pokemon3',        $specP3,  'pokemon2_decode', "blob NULL");
        $this->addColumnLike($target, 'pokemon3_decode', $specP3d, 'pokemon3',        "varchar(50) NULL DEFAULT NULL");

        // If message_start columns already exist (from the prior migration), ensure they sit AFTER pokemon3_decode.
        if ($this->columnExists($target, 'message_start')) {
            $specMs = $this->getColumnSpec($target, 'message_start');
            $this->modifyColumnPosition($target, 'message_start', 'pokemon3_decode', $specMs, "int(10) unsigned NULL DEFAULT NULL");
        }

        if ($this->columnExists($target, 'message_start_decode')) {
            $specMsd = $this->getColumnSpec($target, 'message_start_decode');
            $after = $this->columnExists($target, 'message_start') ? 'message_start' : 'pokemon3_decode';
            $fallback = "varchar(50) NULL DEFAULT NULL";
            $this->modifyColumnPosition($target, 'message_start_decode', $after, $specMsd, $fallback);
        }
    }

    public function down(): void
    {
        $target = 'bxt_battle_tower_honor_roll';
        if (!$this->tableExists($target)) {
            return;
        }

        $t = $this->table($target);

        foreach (['pokemon3_decode','pokemon3','pokemon2_decode','pokemon2','pokemon1_decode','pokemon1'] as $col) {
            if ($this->columnExists($target, $col)) {
                $t->removeColumn($col);
            }
        }

        $t->update();

        // Optional: re-anchor message_start back after class_decode/class if it exists.
        $anchor = $this->columnExists($target, 'class_decode') ? 'class_decode'
            : ($this->columnExists($target, 'class') ? 'class' : null);

        if ($anchor !== null) {
            if ($this->columnExists($target, 'message_start')) {
                $specMs = $this->getColumnSpec($target, 'message_start');
                $this->modifyColumnPosition($target, 'message_start', $anchor, $specMs, "int(10) unsigned NULL DEFAULT NULL");
            }
            if ($this->columnExists($target, 'message_start_decode')) {
                $specMsd = $this->getColumnSpec($target, 'message_start_decode');
                $after = $this->columnExists($target, 'message_start') ? 'message_start' : $anchor;
                $this->modifyColumnPosition($target, 'message_start_decode', $after, $specMsd, "varchar(50) NULL DEFAULT NULL");
            }
        }
    }
}
