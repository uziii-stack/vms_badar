<?php

namespace App\Livewire;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use App\Models\Permission;
use App\Models\TemporaryPass;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Hash;

class AddUserComponent extends Component
{

    public $type = "user";


    public function mount(?string $type = "user")
    {
        $this->type = $type;
    }


    protected function badge($characters, $prefix)
    {
        $possible = '0123456789';
        $code = $prefix;
        $i = 0;
        while ($i < $characters) {
            $code .= substr($possible, mt_rand(0, strlen($possible) - 1), 1);
            if ($i < $characters - 1) {
                $code .= "";
            }
            $i++;
        }
        return $code;
    }
    protected function basicRolesAndTeams($user)
    {
        $role = Role::where('name', 'user')->first();
        $permission = Permission::where('name', 'read')->first();
        $user->addRole($role);
        $user->givePermission($permission);
    }

    public $savedUser = 0;

    public $email = '';
    public $identity = '';
    public $organisation = '';
    public $username = '';
    public $password = '';
    public $confirmPassword = '';

    public function save()
    {
        $uid = (string) Str::uuid();
        if ($this->type == 'user') {
            $user = new User();
            $user->uid = $uid;
            $user->name = $this->username;
            $user->email = $this->email;
            $user->password = Hash::make($this->password);
            $user->activation_code = $this->badge(8, "");
            $user->activated = 1;
            $this->savedUser = $user->save();
            if ($this->savedUser) {
                $this->basicRolesAndTeams($user);
                $this->reset();
                $this->dispatch('addUser')->to(UserListComponent::class);
                $this->js("alert('User Added Successfully!')");
            }
        } else {
            $temporaryPass = new TemporaryPass();
            $temporaryPass->name = $this->username;
            $temporaryPass->identity = $this->identity;
            $temporaryPass->organisation = $this->organisation;
            $temporaryPass->code = $this->badge(8, "TMPP");
            $temporaryPass->status = 1;
            $this->savedUser = $temporaryPass->save();
            if ($this->savedUser) {
                $this->reset();
                $this->type="temporary";
                $this->dispatch('userSlipPrint',$temporaryPass)->self();
                $this->js("alert('User Added Successfully!')");
            }
        }
    }


    #[On('addUser')]
    public function render()
    {
        return view('livewire.add-user-component');
    }
}
