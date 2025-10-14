<?php

namespace App\Modules\Share\Traits;

use App\Modules\Share\Models\LogActivityUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait HasActivityUser
{
    /**
     * Boot the HasActivityUser trait.
     */
    protected static function bootHasActivityUser(): void
    {
        // Cache user id sekali saja per request
        $userId = Auth::id();

        static::created(function (Model $model) use ($userId) {
            static::recordActivity('created', $model, 'Record created', [
                'data' => $model->getAttributes(),
            ], $userId);
        });

        static::updated(function (Model $model) use ($userId) {
            $changes = $model->getChanges();

            if (! empty($changes)) {
                static::recordActivity('updated', $model, 'Record updated', [
                    'before' => $model->getOriginal(),
                    'after'  => $changes,
                ], $userId);
            }
        });

        static::deleted(function (Model $model) use ($userId) {
            static::recordActivity('deleted', $model, 'Record deleted', [
                'data' => $model->getOriginal(),
            ], $userId);
        });
    }

    /**
     * Record activity log for user actions.
     */
    protected static function recordActivity(
        string $type,
        Model $model,
        string $message,
        array $extra = [],
        ?int $userId = null
    ): void {
        // Gunakan afterCommit agar tidak mengganggu transaksi utama
        DB::afterCommit(function () use ($type, $model, $message, $extra, $userId) {
            LogActivityUser::create([
                'type'        => $type,
                'description' => [
                    'model'    => class_basename($model),
                    'model_id' => $model->getKey(),
                    'message'  => $message,
                    'data'     => $extra,
                ],
                'user_id'     => $userId,
                'created_at'  => now(),
            ]);
        });
    }
}
