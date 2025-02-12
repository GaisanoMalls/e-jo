<div wire:poll.6s>
    <small class="rounded-2 px-2 pb-4 {{ $this->isSlaOverdue($ticket) ? 'text-danger fw-bold' : '' }}" id="slaTimer" style="font-size: 12px;">
        {{ $slaTimer['timer'] }}
    </small>
</div>
