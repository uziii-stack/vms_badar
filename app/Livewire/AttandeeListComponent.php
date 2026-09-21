<?php

namespace App\Livewire;

use App\Models\{Visitors, Stall, Event};
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Lazy;

use function Ramsey\Uuid\v1;

// use Carbon\Carbon;
// use Illuminate\Support\Facades\Http;


#[Lazy]
class AttandeeListComponent extends Component
{
    // For Searches
    public $attandees = [];
    public $badgeData = [];
    public array $selectedStalls = [];
    public array $stallList = [];
    public string $currentDay = 'day_1';

    public function loadStalls()
    {
        $this->stallList = Stall::all('id', 'name')->toArray();
    }

    #[On('open-stall-modal')]
    public function openStallModal($day = 'day_1')
    {
        $this->currentDay = $day;
        $this->loadStalls();
        $this->reset('selectedStalls');

        if (!empty($this->attandees)) {
            $visitor = Visitors::where('uid', $this->attandees['uid'])->first();

            $this->selectedStalls = $visitor
                ? $visitor->stalls()->wherePivot('day', $this->currentDay)->pluck('stalls.id')->toArray()
                : [];
        }
    }

    public function saveStalls()
    {
        if (empty($this->attandees)) {
            return;
        }

        $visitor = Visitors::where('uid', $this->attandees['uid'])->first();

        if (!$visitor) {
            return;
        }

        $syncData = [];
        foreach ($this->selectedStalls as $stallId) {
            $syncData[$stallId] = ['day' => $this->currentDay];
        }

        $visitor->stalls()
            ->wherePivot('day', $this->currentDay)
            ->sync($syncData);


        $this->dispatch('close-stall-modal');
    }


    #[On('userUpdate')]
    public function handleEvent($data)
    {
        // if (!empty($data) && !empty($data['dob']) ? $data['dob'] : false) {
        //     $carbonDate = Carbon::parse($data['dob']);
        //     $data['dob'] = $carbonDate->format('Y-m-d');
        // }
        $this->reset('selectedStalls');
        $this->attandees = $data && isset($data['uid'])
            ? Visitors::with('eventSessions')->where('uid', $data['uid'])->first()
            : [];
        $this->badgeData = $this->attandees ? Visitors::where('uid', $this->attandees['uid'])->first() : [];
        if ($this->badgeData) {
            $this->dispatch('dataupdate')->self();
        }
    }

    #[On('dataupdate')]
    public function redirectToBadge()
    {
        Visitors::where('code', $this->attandees['code'])->update(['dupe_badge_print' => $this->badgeData['dupe_badge_print'] + 1]);
        $this->attandees = Visitors::where('uid', $this->attandees['uid'])->first();
        $this->dispatch('dataupdate')->self();
        $this->dispatch('redirectNow', $this->attandees)->self();
        // $badgePrinted = $this->badgeData['badge_print'];
        // if (!$badgePrinted) {
        //     Visitors::where('code', $this->attandees['code'])->update(['badge_print' => 1]);
        //     $this->attandees = Visitors::where('uid', $this->attandees['uid'])->first();
        //     $this->dispatch('dataupdate')->self();
        //     $this->dispatch('redirectToA4Badge', $this->attandees)->self();
        //     // return redirect()->to('https://www.example.com')->with('target', '_blank');
        // } else {
        //     Visitors::where('code', $this->attandees['code'])->update(['dupe_badge_print' => $this->badgeData['dupe_badge_print'] + 1]);
        //     $this->attandees = Visitors::where('uid', $this->attandees['uid'])->first();
        //     $this->dispatch('dataupdate')->self();
        //     $this->dispatch('redirectNow', $this->attandees)->self();
        // }
    }

    public function redirectToA4Badge()
    {
        $this->attandees = Visitors::where('uid', $this->attandees['uid'])->first();
        $this->dispatch('openA4BadgeOptions', $this->attandees)->self();
    }

    public function redirectToSlip()
    {
        $this->dispatch('redirectToSlip', $this->attandees)->self();
        // return $this->attandees;
    }


    #[On('dataupdate')]
    public function render()
    {
        return view('livewire.attandee-list-component', [
            'events' => Event::active()->orderBy('start_date', 'desc')->get(),
        ]);
    }
}
