<?php

namespace App\Modules\Share\Helper;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Exception;

class CodeGenerator
{
    /**
     *
     *
     * @param string $tableName
     * @param string|null $prefix
     * @param string|null $name
     * @param string $columnName
     * @return string
     */
    public static function generate(string $tableName, ?string $prefix = null, ?string $name = null, string $columnName = 'code'): string
    {
        try {
            if (!$prefix) {
                $prefix = strtoupper(substr($tableName, 0, 3));
            }
            return DB::transaction(function () use ($tableName, $prefix, $name, $columnName) {
                DB::statement("LOCK TABLES {$tableName} WRITE");

                $lastRecord = DB::table($tableName)
                    ->select($columnName)
                    ->orderByDesc('id')
                    ->first();

                $lastNumber = 0;

                if ($lastRecord && preg_match("/{$prefix}-(\d+)-/", $lastRecord->{$columnName}, $matches)) {
                    $lastNumber = (int) $matches[1];
                }

                $nextNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);

                $suffix = '';
                if (!empty($name)) {
                    $suffix = strtoupper(Str::slug(Str::limit($name, 3, ''), ''));
                }
                $datePart = now()->format('Ymd');

                $code = "{$prefix}-{$nextNumber}"
                    . ($suffix ? "-{$suffix}" : '')
                    . "-{$datePart}";

                $exists = DB::table($tableName)
                    ->where($columnName, $code)
                    ->exists();

                if ($exists) {
                    $uniqueSuffix = strtoupper(Str::random(4));
                    $code .= "-{$uniqueSuffix}";
                }

                DB::statement('UNLOCK TABLES');

                return $code;
            });
        } catch (Exception $e) {
            Log::error("Code generation failed for {$tableName}: " . $e->getMessage());
            return strtoupper($prefix ?? 'GEN') . '-' . Str::uuid()->toString();
        }
    }
}
