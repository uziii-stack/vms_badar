<div class="mb-3">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <button type="button" class="btn btn-outline-{{$colorClass}}" data-bs-toggle="modal"
                    data-bs-target="#{{$modalId}}">
                    {{$btnName}}
                </button>
                <div class="modal fade" id="{{$modalId}}" tabindex="-1" role="dialog"
                    aria-labelledby="ModalFormLabel_{{$modalId}}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content text-capitalize">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalFormLabel_{{$modalId}}">Add {{$name}}</h5>
                            </div>
                            <div class="modal-body">
                                <form id="{{$name}}_form_id" name="{{$name}}_form" wire:submit="save">
                                    <div class="form-group">
                                        <label for="name_{{$modalId}}" class="col-form-label">
                                            Name
                                        </label>
                                        <input type="text" wire:model='field1' class="form-control"
                                            id="name_{{$modalId}}">
                                    </div>
                                    <br />
                                    <div class="form-group">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Add
                                            {{$name}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>