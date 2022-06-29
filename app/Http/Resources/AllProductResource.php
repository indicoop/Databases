<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AllProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'thumbnail' => $this->thumbnail,
            'category' => $this->productCategory->name,
            'name' => $this->name,
            'price' => $this->price,
            'stock' => $this->stock,
            'description' => $this->description,
            'cooperative_id' => $this->businessDetail->cooperative->id,
            'cooperative' => $this->businessDetail->cooperative->name,
            'rating' => $this->ratingValue(),
            'total_transaction' => $this->totalTransactionCount->total_transaction_count,
            'total_product_quantity_sold' => $this->totalQuantity->total_quantity
        ];
    }
}
