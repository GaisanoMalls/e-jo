<div wire:poll.visible.60s>
    @if ($isTicketApprovedForSLA)
        <small class="rounded-2" id="slaTimer" style="font-size: 11px; padding: 2px 5px;">
            {{ $slaTimer }}
        </small>
    @endif
</div>
