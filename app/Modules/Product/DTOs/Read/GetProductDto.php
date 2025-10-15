<?php

namespace App\Modules\Product\DTOs\Read;

use App\Base\BaseDto;

class GetProductDto extends BaseDto
{
    public ?string $search = null;

    public int $perPage;

    public ?int $categoryId = null;
    public ?int $createdBy = null;
    public ?float $minPrice = null;
    public ?float $maxPrice = null;
    public ?bool $hasDiscount = null;
    public ?string $createdFrom = null;
    public ?string $createdTo = null;
    public ?bool $inStock = null;
}
