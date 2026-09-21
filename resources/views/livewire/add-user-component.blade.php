<form action="{{route('request.signUp')}}" method="POST" wire:submit="save">
    <div class="row">
        <div class="col">
            <div class="mb-3">
                <label for="username" class="form-label">User Name</label>
                <input type="text" wire:model="username" class="form-control" aria-describedby="textHelp" id="username"
                    name="username" required>
            </div>
            @if($type == "user")
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" wire:model="email" class="form-control" id="email" aria-describedby="emailHelp"
                    name="email" required>
            </div>
            @else
            <div class="mb-3">
                <label for="identity" class="form-label">Identity</label>
                <input type="text" wire:model="identity" class="form-control" id="identity"
                    aria-describedby="identityHelp" name="identity" required>
            </div>
            @endif
        </div>
        <div class="col">
            @if($type == "user")
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" wire:model="password" class="form-control" id="password" name="password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required>
            </div>
            @else
            <div class="mb-3">
                <label for="organisation" class="form-label">Organisation</label>
                <input type="text" wire:model="organisation" class="form-control" id="organisation"
                    aria-describedby="organisationHelp" name="organisation" required>
            </div>
            @endif
            <div class="mb-3">
                <label for="addUser" class="form-label">Action Button</label>
                <button type="submit" wire:loading.attr="disabled"
                    class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">
                    <span wire:loading class="spinner-grow text-white" style="width:20px;height:20px;" role="status">
                    </span>
                    &nbsp;
                    <span>
                        Add User
                    </span>
                </button>
            </div>
        </div>
    </div>
</form>
@script
<script>
    $wire.on('userSlipPrint', (data) => {
        console.log(data)
        window.open(`{{url('')}}/temporarySlip/${data[0]['identity']}`, "_blank", "width=500,height=500");
    });
</script>
@endscript