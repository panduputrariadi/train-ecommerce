<?php

namespace App\Modules\Product\DTOs\Update;

use App\Base\BaseDto;
use Illuminate\Http\UploadedFile;

class UpdateProductDto extends BaseDto
{
    public ?string $name;

    public ?string $description = null;

    public ?int $price;

    public ?int $stock;

    public ?int $categoryId;

    public ?bool $isDiscount;

    public ?UploadedFile $photo = null;
}
