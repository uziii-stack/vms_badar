<div class="col-md-10">
    <div class="input-group mb-3">
        <input type="text" class="form-control"
            placeholder="@if(session()->has('error')) {{session('error')}} @else Search By Code @endif"
            aria-label="Code" aria-describedby="button-search" wire:model="valueToBeSearch" wire:keydown.enter="search"
            required>
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" wire:loading.remove wire:click="search" type="submit"
                style="font-size: 24px;"><i class="ti ti-user-search"></i></button>
            <button wire:loading class="btn btn-outline-secondary" style="font-size: 18px;">
                <div class="spinner-border text-secondary" role="status">
                </div>
            </button>
        </div>
    </div>
</div>