<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseController extends Controller
{
    public function export(Request $request)
    {
        $format = $request->query('format') === 'sqlite' ? 'sqlite' : 'sql';
        $stamp = now()->format('Ymd-His');

        $databaseFile = DB::connection()->getDatabaseName();

        if ($format === 'sqlite') {
            return response()->download($databaseFile, 'rcb-database-' . $stamp . '.sqlite');
        }

        return response()->streamDownload(function () use ($stamp) {
            echo "-- RCB database export\n";
            echo "-- Generated: " . now()->toDateTimeString() . "\n";
            echo "SET NAMES utf8mb4;\n";
            echo "SET FOREIGN_KEY_CHECKS=0;\n";

            $tables = collect(DB::select(
                "SELECT name FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%' ORDER BY name"
            ))->pluck('name');

            foreach ($tables as $table) {
                echo "\n-- ----------------------------\n";
                echo "-- Table: {$table}\n";
                echo "-- ----------------------------\n";
                echo "DELETE FROM `{$table}`;\n";

                $columns = Schema::getColumnListing($table);
                $quoted = implode(', ', array_map(fn ($c) => '`' . $c . '`', $columns));

                foreach (DB::table($table)->get() as $row) {
                    $row = (array) $row;
                    $values = implode(', ', array_map(fn ($v) => $this->quoteValue($v), array_values($row)));
                    echo "INSERT INTO `{$table}` ({$quoted}) VALUES ({$values});\n";
                }
            }

            echo "\nSET FOREIGN_KEY_CHECKS=1;\n";
        }, 'rcb-database-' . $stamp . '.sql', [
            'Content-Type' => 'application/sql',
        ]);
    }

    private function quoteValue($value): string
    {
        if ($value === null) {
            return 'NULL';
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        $escaped = str_replace(
            ["\\", "\n", "\r", "\x00", "\x1a"],
            ["\\\\", "\\n", "\\r", "\\0", "\\Z"],
            (string) $value
        );

        return "'" . str_replace("'", "''", $escaped) . "'";
    }
}
