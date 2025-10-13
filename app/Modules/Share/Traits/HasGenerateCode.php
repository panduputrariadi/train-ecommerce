<?php

namespace App\Modules\Share\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

trait HasGenerateCode
{
    /**
     * Boot the trait and hook into model events
     */
    public static function bootHasGenerateCode(): void
    {
        static::creating(function ($model) {
            if (empty($model->code)) {
                $model->code = self::generateCode(
                    tableName: $model->getTable(),
                    prefix: $model->getCodePrefix(),
                    name: method_exists($model, 'getCodeName') ? $model->getCodeName() : null,
                    columnName: 'code'
                );
            }
        });
    }

    /**
     * Generate unique, sequential, and prefixed code
     */
    public static function generateCode(string $tableName, ?string $prefix = null, ?string $name = null, string $columnName = 'code'): string
    {
        try {
            if (!$prefix) {
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
                $suffix = $name ? strtoupper(Str::slug(Str::limit($name, 3, ''), '')) : '';
                $datePart = now()->format('Ymd');

                $code = "{$prefix}-{$nextNumber}"
                    . ($suffix ? "-{$suffix}" : '')
                    . "-{$datePart}";

                if (DB::table($tableName)->where($columnName, $code)->exists()) {
                    $code .= '-' . strtoupper(Str::random(4));
                }

                return $code;
            });
        } catch (Exception $e) {
            Log::error("Code generation failed for {$tableName}: " . $e->getMessage());
            return strtoupper($prefix ?? 'GEN') . '-' . Str::uuid()->toString();
        }
    }

    /**
     * Default prefix (override di model jika ingin custom)
     */
    protected function getCodePrefix(): string
    {
        return strtoupper(substr(class_basename(static::class), 0, 3));
    }
}
