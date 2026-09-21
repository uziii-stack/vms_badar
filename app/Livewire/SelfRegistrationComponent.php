<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Lazy;
use App\Services\EmailService;
use App\Models\{Visitors, Events};
use Illuminate\Support\Facades\Mail;
use App\Mail\{MailConfirmationController};
use Illuminate\Support\Facades\Http;

#[Lazy]
class SelfRegistrationComponent extends Component
{
    protected $emailService;


    protected $listeners = [
        'refreshingSelfRegComponent' => '$refresh',
    ];

    public $nationality = '';
    public $identity; // CNIC or passport
    public $searchIdentity; // CNIC or passport
    public $name;
    public $designation;
    public $sector;
    public $contact;
    public $email;
    public $company;
    public $messageType;
    public $message;
    public $anchor;
    public $eventName;
    public bool $isInternational = false;
    public $otherDesignation;

    public $maxDate;





    public function mount($eventName, ?bool $isInternational = false)
    {
        $this->eventName = $eventName;
        if (isset($isInternational)) {
            $this->isInternational = $isInternational;
        } else {
            $this->isInternational = false;
        }
    }

    protected function rules()
    {
        return [
            'nationality' => 'required|string|max:100',
            'identity' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'otherDesignation'  => 'required_if:designation,Other|max:255',
            'sector' => 'required|string|max:255',
            'contact' => 'required|string|regex:/^\d{10,15}$/',
            'email' => 'required|email|max:255',
            'company' => 'required|string|max:255',
        ];
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

    // protected function getData(): array
    // {
    //     return [
    //         'name'        => $this->name,
    //         'identity'    => $this->identity,
    //         'designation' => $this->designation,
    //         'company'     => $this->company,
    //         'contact'     => $this->contact,
    //         'email'       => $this->email,
    //         'nationality' => $this->nationality,
    //     ];
    // }

    public function searchVisitor()
    {
        if (strlen($this->searchIdentity) < 8) {
            session()->flash('searchError', 'Data Not Exist');
            $this->messageType = "danger";
            $this->message = 'Your Registration data does not exist';
            $this->pull(['searchIdentity']);
            return null;
        }

        $visitorCheck = Visitors::where('identity', $this->searchIdentity)->first();
        if (is_null($visitorCheck)) {
            session()->flash('searchError', 'Data Not Exist');
            $this->messageType = "danger";
            $this->message = 'Your Registration data does not exist';
            $this->pull(['searchIdentity']);
            return null;
        }
        $this->dispatch('slipPrint', $this->searchIdentity)->self();
        $this->dispatch('refreshingSelfRegComponent');

        session()->flash('success', 'You have already registered!');
        $this->messageType = "success";
        $this->message = 'Your Registration data retrieved Successfully';
        $this->pull(['searchIdentity']);
        return $visitorCheck;
    }

    public function addNew(EmailService $emailService)
    {
        $validated = $this->validate();
        //
        try {
            $code = $this->badge(8, 'VW');
            $finalDesignation = $validated['designation'] === 'Other'
                ? $this->otherDesignation
                : $validated['designation'];

            $visitor = Visitors::create([
                ...$validated,
                'designation' => $finalDesignation,
                'event'       => $this->eventName,
                'code'        => $code,
            ]);

            $emailService->sendAsync($visitor->email, $visitor);

            // Assuming you want to use identity in the print event
            $this->dispatch('slipPrint', $validated['identity'])->self();

            $this->dispatch('refreshingSelfRegComponent');

            session()->flash('success', 'Form submitted successfully!');
            $this->messageType = "success";
            $this->message = 'Visitor created Successfully';
            $this->pull(['nationality', 'identity', 'name', 'designation', 'sector', 'otherDesignation', 'contact', 'company', 'email']);
            return $visitor;
        } catch (\Illuminate\Database\QueryException $e) {
            $this->messageType = "danger";
            if ($e->getCode() == 23000 && str_contains($e->getMessage(), 'Duplicate entry')) {
                // Extract the key name using regex
                preg_match("/for key '([^']+)'/", $e->getMessage(), $matches);
                $keyName = $matches[1] ?? 'unknown_key';
                $this->message = "Duplicate entry found for key: {$keyName}";
                $this->anchor = url('') . '/badge/' . $this->identity;
            }
            return ['error' => $e->getMessage()];
        } catch (\Exception $e) {
            // Non-SQL exceptions
            $this->messageType = "danger";
            $this->message = $e->getMessage();
            return ['error' => $e->getMessage()];
        }
    }

    public function sendConfirmationEmail()
    {
        // Logic to send confirmation email

    }

    public function render()
    {
        return view('livewire.self-registration-component');
    }
}
