<?php

namespace App\Http\Admin\Resources\Detail;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class GetDetailOrderPaymentResource extends JsonResource
{
    /**
     * Transform the payment resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->code,
            'amount' => $this->amount,
            'status' => $this->status?->value,
            'note' => $this->note,
            'paid_at' => $this->paid_at?->format('Y-m-d H:i:s'),

            'method' => $this->whenLoaded('method', function () {
                return [
                    'id' => $this->method->id,
                    'name' => $this->method->name,
                ];
            }),

            'bank_account' => $this->whenLoaded('bankAccount', function () {
                return [
                    'id' => $this->bankAccount->id,
                    'bank_name' => $this->bankAccount->bank_name,
                    'account_name' => $this->bankAccount->account_name,
                    'account_number' => $this->bankAccount->account_number,
                ];
            }),

            'receipts' => $this->whenLoaded('receipts', function () {
                return $this->receipts->map(function ($receipt) {
                    return [
                        'id' => $receipt->id,
                        'file_path' => url(Storage::url($receipt->file_path)),
                        'mime_type' => $receipt->mime_type,
                        'uploaded_at' => $receipt->uploaded_at?->format('Y-m-d H:i:s'),
                    ];
                });
            }),

            'creator' => $this->whenLoaded('creator', function () {
                return [
                    'id' => $this->creator->id,
                    'name' => $this->creator->name,
                ];
            }),

            'verifier' => $this->whenLoaded('verifier', function () {
                return [
                    'id' => $this->verifier->id,
                    'name' => $this->verifier->name,
                ];
            }),
        ];
    }
}
