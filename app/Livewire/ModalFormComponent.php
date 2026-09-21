<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Lazy;

#[Lazy]
class ModalFormComponent extends Component
{

    public $modalId = '';
    public $name = '';
    public $field1 = '';
    public $field2 = '';
    public $field3 = '';
    public $className = '';
    public $colorClass = '';
    public $oldData = '';
    public $btnName = '';
    public $outPut = '';
    public bool $rank = false;

    public function mount($modalId, $name, $className, $colorClass, $oldData, $btnName, $outPut = 'display_name', ?bool $rank = false)
    {
        $this->modalId = $modalId;
        $this->name = $name;
        $this->className = $className;
        $this->$colorClass = $colorClass;
        $this->$oldData = $oldData;
        $this->$btnName = $btnName;
        $this->outPut = $outPut;
        $this->rank = $rank;
    }

    public function placeholder()
    {
        return <<<'HTML'
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="mb-3">
                            <label class="form-label">{{$name}} </label>
                            <select class="form-select">
                                <option value="" selected disabled hidden> Select {{$name}}
                                </option>
                            </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">{{$btnName}}</label>
                            <button type="button" id="triger-{{$modalId}}" class="btn btn-{{$colorClass}}" data-bs-toggle="modal"
                        data-bs-target="#{{$modalId}}">+</button>
                        </div>
                        </div>
                    </div>  
                </div>
        HTML;
    }

    public function save()
    {
        if ($this->rank) {
            $isExist = $this->className::where('ranks_name', $this->field1)->first();
            if ($isExist) {
                $this->js("alert('Already Exist!')");
                $this->dispatch('category-updated')->self();
                $this->pull(['field1']);
            } else {
                $field = new $this->className;
                $field->ranks_name = $this->field1;
                $fieldSaved = $field->save();
                if ($fieldSaved) {
                    $this->js("alert('Updated!')");
                    $this->dispatch('category-updated')->self();
                    $this->pull(['field1']);
                } else {
                    $this->js("alert('SomeThing Went Wrong!')");
                }
            }
        } else {
            $field = new $this->className;
            $field->name = $this->field1;
            $field->display_name = $this->field1 ?? $this->field2;
            $field->description = $this->field3;
            // return [$this->field1, $this->field2, $this->field3];
            $fieldSaved = $field->save();
            if ($fieldSaved) {
                $this->js("alert('Updated!')");
                $this->dispatch('category-updated')->self();
                $this->pull(['field1', 'field3']);
            } else {
                $this->js("alert('SomeThing Went Wrong!')");
            }
        }
    }

    #[On('category-updated')]
    public function render()
    {
        $categories = $this->className::all();
        return view('livewire.modal-form-component', ['categories' => $categories]);
    }
}
