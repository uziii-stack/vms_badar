<div class="d-flex mx-auto  align-items-center gap-2">
    <span class="mx-auto">
        @if(time() < strtotime($dataTimeStartVar))
            <span class="badge mx-auto  bg-primary rounded-3 fw-semibold">Upcoming</span>
        @elseif(time() > strtotime($dataTimeStartVar)&& time() < strtotime($dataTimeEndVar) && $attended == 0)
        <button wire:click="save" class="btn btn-outline-success" data-toggle="tooltip" data-placement="top" title="Present"><i class="ti ti-circle-check" style="font-size:24px"></i></button>
        @elseif(time() > strtotime($dataTimeStartVar) && time() < strtotime($dataTimeEndVar) && $attended == 1)
            <button wire:click="save" class="btn btn-outline-badar" data-toggle="tooltip" data-placement="bottom" title="Absent"><i class="ti ti-circle-x" style="font-size:24px"></i></button>
            @elseif(time() > strtotime($dataTimeEndVar) && $attended)
            <span class="badge mx-auto  bg-success rounded-3 fw-semibold">Present</span>
            @elseif(time() > strtotime($dataTimeEndVar) && !$attended)
            <span class="badge mx-auto  bg-badar rounded-3 fw-semibold">Absent</span>
            @else
            <span class="badge mx-auto  bg-success rounded-3 fw-semibold">Past</span>
            @endif
</div>