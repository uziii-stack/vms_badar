<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Search Data</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">{!! $modalBody !!}</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="text" class="form-control"
                placeholder="@if(session()->has('error')) {{session('error')}} @else Search By Code @endif"
                aria-label="Code" aria-describedby="button-search" wire:model="valueToBeSearch"
                wire:keydown.enter="search" required>
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
</div>
@script
<script>
    $wire.on('alertRefresh', (data) => {
        const myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
        myModal.show();
    });
</script>
@endscript