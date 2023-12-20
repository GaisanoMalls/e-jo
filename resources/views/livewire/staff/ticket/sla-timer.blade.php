<div>
    @if ($isTicketApprovedForSLA)
        <small class="rounded-2" id="slaTimer" style="font-size: 11px; padding: 2px 5px;"></small>
    @endif
</div>

{{-- Modal Scripts --}}
@push('extra')
    <script>
        const slaTimer = document.querySelector('#slaTimer')
        // Set the number of days for the countdown
        const slaDays = @json($slaDays);
        const hoursPerDay = 24;

        let targetDate = localStorage.getItem('targetDate');
        if (!targetDate) {
            targetDate = new Date();
            targetDate.setHours(targetDate.getHours() + slaDays * hoursPerDay);
            localStorage.setItem('targetDate', targetDate);
        } else {
            targetDate = new Date(targetDate);
        }

        // Update the countdown every second
        const countdownInterval = setInterval(updateCountdown, 1000);

        function updateCountdown() {
            // Get the current date and time
            const currentDate = new Date().getTime();

            // Calculate the time remaining
            const timeRemaining = targetDate - currentDate;

            // Calculate days, hours, and minutes
            const days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));

            // Display the countdown in the specified format
            slaTimer.innerHTML = `${days} days, ${hours} hours, ${minutes} minutes`;

            // Check if the countdown has reached zero
            if (timeRemaining <= 0) {
                clearInterval(countdownInterval); // Stop the countdown
                slaTimer.innerHTML = 'Ticket is overdue';
                slaTimer.style.backgroundColor = 'red';
                slaTimer.style.color = 'white';
                localStorage.removeItem('targetDate');
            }
        }
    </script>
@endpush
