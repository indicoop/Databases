<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->transform(
            fn($product) => [
                'id' => $product->id,
                'thumbnail' => $product->thumbnail,
                'category' => $product->productCategory->name,
                'name' => $product->name,
                'price' => $product->price,
                'stock' => $product->stock,
                'description' => $product->description,
                'cooperative' => $product->businessDetail->cooperative->name
            ]
        );
    }
}
