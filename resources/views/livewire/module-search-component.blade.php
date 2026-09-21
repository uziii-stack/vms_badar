<div>
    <div class="card-title fw-semibold mb-4 d-flex justify-content-end">
        <div class="row">
            <div class="col-md-10">
                <div class="input-group mb-3">
                    <input type="text" class="form-control"
                        placeholder="@if(session()->has('error')) {{session('error')}} @else Search By Code @endif"
                        aria-label="Code" aria-describedby="button-search" wire:model="valueToBeSearch"
                        wire:keydown.enter="search" required>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" wire:loading.remove wire:click="search"
                            style="font-size: 24px;"><i class="ti ti-user-search"></i></button>
                        <button wire:loading class="btn btn-outline-secondary" style="font-size: 18px;">
                            <div class="spinner-border text-secondary" role="status">
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table text-nowrap mb-0 align-middle">
            <thead class="text-dark fs-4">
                <tr>
                    <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                        <h6 class="fw-semibold mb-0">Name</h6>
                    </th>
                    <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                        <h6 class="fw-semibold mb-0">Company/Institute Name</h6>
                    </th>
                    <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                        <h6 class="fw-semibold mb-0">Designation</h6>
                    </th>
                    <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                        <h6 class="fw-semibold mb-0">Identity</h6>
                    </th>
                    <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                        <h6 class="fw-semibold mb-0">Contact</h6>
                    </th>
                    <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                        <h6 class="fw-semibold mb-0">Email</h6>
                    </th>
                    <th class="border-bottom-0 display: flex; justify-content: center; align-items: center;">
                        <h6 class="fw-semibold mb-0">Code</h6>
                    </th>
                </tr>
            </thead>
            @if(!empty($attandees))
            <tbody>
                <tr>
                    @foreach($renderComponent as $field)
                    <td class="border-bottom-0 text-center align-middle">
                        <p class="mb-0 fw-normal text-capitalize">
                            {{ data_get($attandees, $field, 'N/A') }}
                        </p>
                    </td>
                    @endforeach
                </tr>
                @else
                <tr>
                    <td colspan="12">No Data Available</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>