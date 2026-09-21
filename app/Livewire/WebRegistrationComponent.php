<?php

namespace App\Livewire;

use App\Models\Visitors;
use Livewire\Component;
use Illuminate\Http\Response;

class WebRegistrationComponent extends Component
{
    public int $step = 1;

    // Step 1
    public $nationality;
    public $identity;
    public $dob;
    public $gender;

    // Step 2
    public $name;
    public $city;
    public $designation;
    public $address;
    public $mobile;
    public $email;

    // Step 3
    public $company;
    public $companyType;
    public $companyContact;
    public $companyWebsite;
    public $companyEmail;


    public $eventName;

    public function mount($eventName)
    {
        $this->eventName = $eventName;
    }

    protected function rules()
    {
        $rules = [];

        if ($this->step === 1) {
            $rules = [
                'nationality' => 'required|string',
                'identity' => 'required|string',
                'dob' => 'required|date',
                'gender' => 'required|in:Male,Female',
            ];
        }

        if ($this->step === 2) {
            $rules = [
                'name' => 'required|string',
                'city' => 'required|string',
                'designation' => 'required|string',
                'address' => 'required|string',
                'mobile' => 'required|string|regex:/^\d{10,15}$/',
                'email' => 'required|email',
            ];
        }

        if ($this->step === 3) {
            $rules = [
                'company' => 'required|string',
                'companyType' => 'required|string',
                'companyContact' => 'nullable|string|regex:/^\d{10,15}$/',
                'companyWebsite' => 'nullable|url',
                'companyEmail' => 'required|email',
            ];
        }

        return $rules;
    }

    protected function getData(): array
    {
        return [
            'nationality'     => $this->nationality,
            'identity'   => $this->identity,
            'dob'             => $this->dob,
            'gender'          => $this->gender,
            'name'            => $this->name,
            'city'            => $this->city,
            'designation'     => $this->designation,
            'address'         => $this->address,
            'contact'          => $this->mobile,
            'email'           => $this->email,
            'company'        => $this->company,
            'companyType'        => $this->companyType,
            'companyContact'     => $this->companyContact,
            'companyWebsite'     => $this->companyWebsite,
            'companyEmail'  => $this->companyEmail,
            'event'          => $this->eventName,
        ];
    }


    public function next()
    {
        $this->validate();

        if ($this->step < 3) {
            $this->step++;
        }
    }

    public function back()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function submit()
    {
        // Save to DB or emit an event, etc.
        try {
            $visitor = Visitors::create($this->getData());
            session()->flash('success', 'Form submitted successfully!');
            $this->pull(['nationality', 'identity', 'dob', 'gender', 'name', 'city', 'designation', 'address', 'mobile', 'email', 'company', 'companyType', 'companyContact', 'companyWebsite', 'companyEmail']);
            $this->step = 1;
            return $visitor;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function render()
    {
        return view('livewire.web-registration-component');
    }
}
