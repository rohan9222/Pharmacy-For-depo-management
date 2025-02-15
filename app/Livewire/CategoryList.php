<?php

namespace App\Livewire;

use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

use App\Models\Category;

use Livewire\Component;

class CategoryList extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $categoryId, $name, $description, $status, $address, $balance, $category_type, $search;

    public function mount()
    {
        if(auth()->user()->hasRole('Super Admin')) {
            return true;
        }
        if (!auth()->user()->hasAnyPermission(['create-category', 'edit-category', 'delete-category'])) {
            abort(403, 'Unauthorized action.');
        }
        return true;
    }

    public function render()
    {
        $categories = Category::search($this->search)->paginate(10);

        return view('livewire.category-list', ['categories' => $categories])->layout('layouts.app');
    }

    // Updated rules method
    public function role()
    {
        return [
            'name' => 'required|string|max:255|unique:categories,name,' . $this->categoryId,
            'description' => 'nullable|string|max:255',
            'status' => 'required|boolean',
        ];
    }

    public function submit()
    {
        $this->validate($this->role());

        try {
            Category::updateOrCreate(
                ['id' => $this->categoryId],
                [
                    'name' => $this->name,
                    'description' => $this->description,
                    'status' => $this->status,
                ]
            );
            $this->reset();
            flash()->success('category added successfully!');
        } catch (\Exception $e) {
            flash()->error('Error: ' . $e->getMessage());
            return;
        }
    }

    public function edit($id)
    {
        $category = Category::find($id);
        if ($category) {
            $this->categoryId = $id;
            $this->name = $category->name;
            $this->description = $category->description;
            $this->status = $category->status;
        }else {
            flash()->error('Something went wrong!');
        }
    }

    public function delete($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->forceDelete();
            flash()->success('category deleted successfully!');
        }else {
            flash()->error('Something went wrong!');
        }
    }
}
