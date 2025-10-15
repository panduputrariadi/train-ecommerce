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
        // $userId = Auth::user();

        static::created(function (Model $model) {
            static::recordActivity('created', $model, 'Record created', [
                'data' => $model->getAttributes(),
            ], Auth::id());
        });

        static::updated(function (Model $model) {
            $changes = $model->getChanges();

            if (! empty($changes)) {
                static::recordActivity('updated', $model, 'Record updated', [
                    'before' => $model->getOriginal(),
                    'after'  => $changes,
                ], Auth::id());
            }
        });

        static::deleted(function (Model $model) {
            static::recordActivity('deleted', $model, 'Record deleted', [
                'data' => $model->getOriginal(),
            ], Auth::id());
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
    ): void {
        // Gunakan afterCommit agar tidak mengganggu transaksi utama
        DB::afterCommit(function () use ($type, $model, $message, $extra) {
            LogActivityUser::create([
                'type'        => $type,
                'description' => [
                    'model'    => class_basename($model),
                    'model_id' => $model->getKey(),
                    'message'  => $message,
                    'data'     => $extra,
                ],
                'user_id'     => Auth::id(),
                'created_at'  => now(),
            ]);
        });
    }

     public function logActivity(string $type, string $message, array $extra = []): void
    {
        DB::afterCommit(function () use ($type, $message, $extra) {
            $userId = Auth::id();
            LogActivityUser::create([
                'type' => $type,
                'description' => [
                    'model' => class_basename($this),
                    'model_id' => $this->getKey(),
                    'message' => $message,
                    'data' => $extra,
                ],
                'user_id' => $userId,
                'created_at' => now(),
            ]);
        });
    }
}
