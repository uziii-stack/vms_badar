<?php

namespace App\Livewire;

use App\Models\Visitors;
use Livewire\Component;
use Livewire\Attributes\On;


class MarkAttendanceComponent extends Component
{
    public $attended = 0;
    public $dataTimeStartVar = '';
    public $dataTimeEndVar = '';
    public $uid = '';
    public $day = '';

    public function mount($day, $uid)
    {
        $this->$uid = $uid;
        $this->$day = $day;
        switch ($this->day) {
            case "day_1":
                $this->dataTimeStartVar = '2024-11-19 00:01:00';
                $this->dataTimeEndVar = '2024-11-19 23:59:00';
                break;
            case "seminar":
                $this->dataTimeStartVar = '2024-11-21 00:01:00';
                $this->dataTimeEndVar = '2024-11-21 23:59:00';
                break;
            case "day_2":
                $this->dataTimeStartVar = '2024-11-20 00:01:00';
                $this->dataTimeEndVar = '2024-11-20 23:59:00';
                break;
            case "day_3":
                $this->dataTimeStartVar = '2024-11-21 00:01:00';
                $this->dataTimeEndVar = '2024-11-21 23:59:00';
                break;
            case "day_4":
                $this->dataTimeStartVar = '2024-11-22 00:01:00';
                $this->dataTimeEndVar = '2024-11-22 23:59:00';
                break;

            default:
                $this->dataTimeStartVar = '2024-11-19 00:01:00';
                $this->dataTimeEndVar = '2024-11-19 23:59:00';
                break;
        }
    }

    public function save()
    {
        $present = Visitors::where('uid', $this->uid)->first($this->day);
        $present[$this->day] == 1 ? Visitors::where('uid', $this->uid)->update([$this->day => 0]) : Visitors::where('uid', $this->uid)->update([$this->day => 1]);
        $this->dispatch('attendance-update')->self();
    }

    #[On('searchUpdate')]
    public function handleEvent($data)
    {
        if ($data) {
            $this->attended = $data;
            $this->uid = $data;
            $this->dispatch('attendance-update')->self();
        }
    }

    #[On('attendance-update')]
    public function render()
    {
        $attended = Visitors::where('uid', $this->uid)->first($this->day);
        $this->attended = $attended[$this->day];
        return view('livewire.mark-attendance-component');
    }
}
