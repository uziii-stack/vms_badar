<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Lazy;
use Illuminate\Support\Str;
use App\Models\Visitors;
use Livewire\Attributes\On;
// use Carbon\Carbon;
// use DateTime;

#[Lazy]
class AddAttandeeComponent extends Component
{

    protected $listeners = [
        'refreshingComponent' => '$refresh'
    ];

    // For Modal 
    public $isOpen = false;
    public $isNew = true;
    public $visitorUid = '';
    public $eventName;
    // public $masterClass = [];

    // For Adding New Visitor
    #[Validate('required|min:3')]
    public $name = '';

    #[Validate('required|min:3')]
    public $company = '';

    // #[Validate('required|min:3')]
    public $nationality = '';

    #[Validate('required|min:3')]
    public $designation = '';

    #[Validate('required|min:9')]
    public $identity = '';

    #[Validate('numeric|min:9')]
    public $contact = '';

    #[Validate('required|email')]
    public $email = '';

    #[Validate('nullable|string|max:50')]
    public $attandeePMDC = '';


    // For date validity
    public $maxDate;

    public function toggleModal()
    {
        $this->isOpen = !$this->isOpen;
        if ($this->isOpen && $this->isNew) {
            $this->name = '';
            $this->company = '';
            $this->designation = '';
            // $this->masterClass = [];
            $this->nationality = 'Pakistan';
            $this->identity = '';
            $this->contact = '';
            $this->email = '';
            $this->attandeePMDC = '';
            $this->eventName = '';
        } elseif ($this->isOpen && $this->isNew && $this->visitorUid) {
            $visitor = Visitors::where('identity', $this->visitorUid)->first();
            // $masterClass = json_decode($visitor->masterclass, true);
            $this->name = $visitor->name;
            $this->company = $visitor->company;
            $this->designation = $visitor->designation;
            // $this->masterClass =$masterClass;
            $this->nationality = 'Pakistan';
            $this->identity = $visitor->identity;
            $this->contact = $visitor->contact;
            $this->email = $visitor->email;
            $this->attandeePMDC = $visitor->attandeePMDC ?? '';
            $this->eventName = $visitor->event ?? '';
        }
    }

    // Badge code function
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


    public function mount($isNew, $visitorUid = '')
    {
        // Calculate the date 18 years ago
        // $this->maxDate = Carbon::now()->subYears(18)->toDateString();
        $this->isNew = $isNew;
        $this->visitorUid = $visitorUid;
        if (!$this->isNew) {
            $visitor = Visitors::where('identity', $this->visitorUid)->first();
            // $masterClass = json_decode($visitor->masterclass, true);
            // $carbonDate=Carbon::parse($visitor->dob);
            // $this->dob=$carbonDate->format('Y-m-d');
            $this->name = $visitor->name;
            $this->company = $visitor->company;
            $this->designation = $visitor->designation;
            // $this->masterClass =$masterClass;
            $this->nationality = 'Pakistan';
            $this->identity = $visitor->identity;
            $this->contact = $visitor->contact;
            $this->email = $visitor->email;
            $this->attandeePMDC = $visitor->attandeePMDC ?? '';
            $this->eventName = $visitor->event ?? '';
        }
    }

    #[On('searchAttandeeUpdate')]
    public function handleEvent($data)
    {
        $visitor = Visitors::where('uid', $data)->first();
        // $masterClass = json_decode($visitor->masterclass, true);
        // $carbonDate=Carbon::parse($visitor->dob);
        // $this->dob=$carbonDate->format('Y-m-d');
        $this->name = $visitor ? $visitor->name : '';
        $this->company = $visitor ? $visitor->company : '';
        $this->designation = $visitor ? $visitor->designation : '';
        // $this->masterClass = $visitor ?$masterClass : [];
        $this->nationality = 'Pakistan';
        $this->identity = $visitor ? $visitor->identity : '';
        $this->contact = $visitor ? $visitor->contact : '';
        $this->email = $visitor ? $visitor->email : '';
        $this->attandeePMDC = $visitor ? ($visitor->attandeePMDC ?? '') : '';
        $this->eventName = $visitor ? ($visitor->event ?? '') : '';
    }


    public function update()
    {

        $validatedData = $this->validate();
        // $masterclass = json_encode($this->masterClass);
        // return $validatedData;
        try {
            // $visitorCreated = Visitors::create([
            //     ...$validatedData,
            //     'uid' => (string) Str::uuid(),
            //     'code' => $this->badge(6, 'TVFA'),
            // ]);

            $visitorUpdated = Visitors::where('identity', $this->visitorUid)->update([
                ...$validatedData,
                'event' => $this->eventName,
                // 'masterclass' => (string) $masterclass,
            ]);

            if ($visitorUpdated) {
                $visitors = Visitors::where('identity', $validatedData['identity'])->first();
                session()->flash('message', 'Visitor has been updated successfully!');
                $this->dispatch('userUpdate', $visitors)->to(AttandeeListComponent::class);
                // $this->maxDate = Carbon::now()->subYears(18)->toDateString();
            } else {
                session()->flash('error', 'Visitor not updated, SomeThing Went Wrong!');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // If an exception is caught, flash an error
            if ($e->errorInfo[1] == 1062) {
                // Duplicate entry error
                session()->flash('error', 'The identity number is already in use. Please provide a unique identity.');
            } else {
                // General error
                session()->flash('error', 'Something went wrong. Please try again.');
            }
            return $e->errorInfo;   
        }
        $this->dispatch('refreshingComponent');
    }

    // For Adding New Visitor
    public function addNew()
    {

        $validatedData = $this->validate();
        // $masterclass = json_encode($this->masterClass);
        // return $validatedData;
        // return $this->eventName;
        try {
            $visitorCreated = Visitors::create([
                ...$validatedData,
                // 'masterclass' => (string) $masterclass,
                'uid' => (string) Str::uuid(),
                'code' => $this->badge(6, 'TVFA'),
                'event' => $this->eventName,
            ]);

            if ($visitorCreated) {
                $visitors = Visitors::where('identity', $validatedData['identity'])->first();
                session()->flash('message', 'Visitor has been updated successfully!');
                $this->reset();
                $this->dispatch('userUpdate', $visitors)->to(AttandeeListComponent::class);
                // $this->maxDate = Carbon::now()->subYears(18)->toDateString();
            } else {
                session()->flash('error', 'Visitor not updated, SomeThing Went Wrong!');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // If an exception is caught, flash an error
            if ($e->errorInfo[1] == 1062) {
                // Duplicate entry error
                session()->flash('error', 'The identity number is already in use. Please provide a unique identity.');
            } else {
                // General error
                session()->flash('error', $e->errorInfo[1]);
            }
            return $e->errorInfo;
        }
        $this->dispatch('refreshingComponent');
    }


    public function render()
    {
        return view('livewire.add-attandee-component');
    }
}
