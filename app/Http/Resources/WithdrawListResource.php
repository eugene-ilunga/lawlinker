<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WithdrawListResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [

            "id"              => $this->id,
            "lawyer"          => $this->lawyer->name,
            "method"          => $this->withdraw_method->name,
            "current_balance" => $this->status == 'approved' ? $this->current_balance : $this->lawyer->wallet_balance,
            "total_amount"    => $this->total_amount,
            "withdraw_amount" => $this->withdraw_amount,
            "withdraw_charge" => $this->withdraw_charge,
            "account_info"    => $this->account_info,
            "status"          => $this->status,
            "approved_date"   => $this->approved_date,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }
}
