<?php

namespace App\Livewire;

use App\Models\Country;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Lazy;

#[Lazy]
class EssentialModalFormComponent extends Component
{


    public $name = '';
    public $field1 = '';
    public $className = '';
    public $colorClass = '';
    public $btnName = '';
    public $modalId = '';
    public $tableId = '';

    public function mount($modalId, $name, $className, $colorClass, $btnName, $tableId)
    {
        $this->modalId = $modalId;
        $this->name = $name;
        $this->className = $className;
        $this->colorClass = $colorClass;
        $this->btnName = $btnName;
        $this->tableId = $tableId;
    }


    public function placeholder()
    {
        return <<<'HTML'
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-12">
                        <div class="mb-3">
                            <button type="button" class="btn btn-outline-{{$colorClass}}">{{$btnName}}</button>
                        </div>
                        </div>
                    </div>  
                </div>
        HTML;
    }


    public function save()
    {
        $field = new $this->className;
        $field->name = $this->field1;
        $fieldSaved = $field->save();
        if ($fieldSaved) {
            $this->js("refreshTable('" . $this->tableId . "')");
            $this->dispatch('essential-updated')->self();
            $this->pull(['field1']);
        } else {
            $this->js("alert('SomeThing Went Wrong!')");
        }
    }


    #[On('essential-updated')]
    public function render()
    {
        return view('livewire.essential-modal-form-component');
    }
}
