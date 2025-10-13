<?php

namespace App\Modules\Share\Traits;

use App\Modules\Share\Models\LogActivityUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait HasActivityUser
{
    protected static function bootHasActivityUser()
    {
        static::created(function (Model $model) {
            static::recordActivity('created', $model, 'Record created', [
                'data' => $model->toArray(),
            ]);
        });

        static::updated(function (Model $model) {
            $changes = $model->getChanges();

            if (!empty($changes)) {
                static::recordActivity('updated', $model, 'Record updated', [
                    'before' => $model->getOriginal(),
                    'after'  => $changes,
                ]);
            }
        });

        static::deleted(function (Model $model) {
            static::recordActivity('deleted', $model, 'Record deleted', [
                'data' => $model->toArray(),
            ]);
        });
    }

    protected static function recordActivity(string $type, Model $model, string $message, array $extra = []): void
    {
        $userId = optional(Auth::user())->id;

        DB::afterCommit(function () use ($type, $model, $message, $extra, $userId) {
            LogActivityUser::create([
                'type' => $type,
                'description' => json_encode(array_merge([
                    'model' => $model::class,
                    'model_id' => $model->getKey(),
                    'message' => $message,
                ], $extra)),
                'user_id' => $userId,
                'created_at' => now(),
            ]);
        });
    }
}
