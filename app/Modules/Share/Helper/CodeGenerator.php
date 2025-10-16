<?php

namespace App\Modules\Share\Helper;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CodeGenerator
{
    /**
     * Generate unique, sequential, and prefixed code for any table.
     *
     * Format example: PREFIX-00001-NAM-20251008
     *
     * @param  string  $tableName  The target table name
     * @param  string|null  $prefix  Optional prefix (defaults to 3-letter from table name)
     * @param  string|null  $name  Optional name used for suffix
     * @param  string  $columnName  The column to check uniqueness (default: "code")
     */
    public static function generate(
        string $tableName,
        ?string $prefix = null,
        ?string $name = null,
        string $columnName = 'code'
    ): string {
        try {
            if (! $prefix) {
                $prefix = strtoupper(substr($tableName, 0, 3));
            }

            return DB::transaction(function () use ($tableName, $prefix, $name, $columnName) {
                $lastRecord = DB::table($tableName)
                    ->select($columnName)
                    ->orderByDesc('id')
                    ->lockForUpdate()
                    ->first();

                $lastNumber = 0;

                if ($lastRecord && preg_match("/{$prefix}-(\d+)-/", $lastRecord->{$columnName}, $matches)) {
                    $lastNumber = (int) $matches[1];
                }

                $nextNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);

                $suffix = '';
                if (! empty($name)) {
                    $suffix = strtoupper(Str::slug(Str::limit($name, 3, ''), ''));
                }

                $datePart = now()->format('Ymd');

                $code = "{$prefix}-{$nextNumber}"
                    .($suffix ? "-{$suffix}" : '')
                    ."-{$datePart}";

                $exists = DB::table($tableName)
                    ->where($columnName, $code)
                    ->exists();

                if ($exists) {
                    $uniqueSuffix = strtoupper(Str::random(4));
                    $code .= "-{$uniqueSuffix}";
                }

                return $code;
            });
        } catch (Exception $e) {
            Log::error("Code generation failed for {$tableName}: ".$e->getMessage());

            return strtoupper($prefix ?? 'GEN').'-'.Str::uuid()->toString();
        }
    }
}
