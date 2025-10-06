<?php

namespace App\Modules\Product\DTOs;

use App\Base\BaseDto;
use Illuminate\Http\UploadedFile;

class CreateProductDto extends BaseDto
{
    public string $name;

    public string $description;

    public int $price;

    public int $stock;

    public int $categoryId;

    public bool $isDiscount;

    public ?UploadedFile $photo = null;
}
