<?php

namespace App\Modules\Product\Observers;

use App\Modules\Product\Models\Category;
use Illuminate\Support\Str;

class CategoryObserver
{
    public function creating(Category $category): void
    {
        if (empty($category->slug)) {
            $category->slug = Str::slug($category->name);
        }

        if (empty($category->code) && method_exists($category, 'generateCode')) {
            $category->code = $category->generateCode('CAT');
        }

        if (auth()->check() && $category->isFillable('created_by')) {
            $category->created_by = auth()->id();
        }
    }

    public function created(Category $category): void
    {
        $category->logActivity('created', "Category '{$category->name}' success created.", [
            'data' => $category->getAttributes(),
        ]);
    }

    public function updating(Category $category): void
    {
        if ($category->isDirty('name')) {
            $category->slug = Str::slug($category->name);
        }

        if (auth()->check() && $category->isFillable('updated_by')) {
            $category->updated_by = auth()->id();
        }
    }

    public function deleted(Category $category): void
    {
        // activity()
        //     ->performedOn($category)
        //     ->causedBy(auth()->user() ?? null)
        //     ->log("Category '{$category->name}' telah dihapus.");
    }
}
