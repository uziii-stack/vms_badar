<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Illuminate\Database\QueryException;

#[Lazy]
class ModuleSearchComponent extends Component
{
    public $valueToBeSearch = '';
    public $className = '';
    public $classAssocName = '';
    public $searchParams = [];
    public $searchAssocParams = [];
    public $attandees = [];
    public $renderComponent = [];


    public function mount($className, $searchParams,$classAssocName,$renderComponent)
    {
        $this->className = $className;
        $this->classAssocName = $classAssocName;
        $this->searchParams = $searchParams;
        $this->renderComponent = $renderComponent;
    }

    public function search()
    {
        $module = new $this->className;
        $moduleAssoc = new $this->classAssocName;
        $search = $this->searchParams;
        $value = $this->valueToBeSearch;
        $value2 = $this->searchAssocParams;
        try {
            $this->attandees = $module::where(function ($query) use ($search, $value) {
                foreach ($search as $column) {
                    $query->orWhere($column, $value);
                }
            })->first()?->toArray();
            $companyData = $moduleAssoc::where('uid', $this->attandees[$value2])->first()?->toArray();
            // return $companyData;

            $this->attandees = [...$this->attandees,...$companyData];
            $this->valueToBeSearch = '';
            // $this->dispatch('moduleSearchCompRefresh')->self();
            return $this->attandees;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    #[On('moduleSearchCompRefresh')]
    public function render()
    {
        return view('livewire.module-search-component');
    }
}
