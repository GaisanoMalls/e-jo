<x-mail::message>
{{ $headerGreeting }},
<br><br>
{!! $message !!}
<br><br>
**Ticket Details**
<br>
**Subject**: {!! $subject !!}
<br>
**Branch**: {!! $branch !!}
<br>
**Department**: {!! $department !!}
<br>
**Date Created**: {!! $dateCreated !!}
<br><br>
You may now start reviewing the requested ticket and proceed with the approval process by logging in to your account in E-JO.
<br>
If you encounter any issues accessing the link or have any questions regarding the system, please don't hesitate to reach out to our support team at 
<a href="mailto:sysdev.smtp@dsgsonsgroup.com">sysdev.smtp@dsgsonsgroup.com</a>.
<br>
<x-mail::button :url="$ticketURL">
View Ticket
</x-mail::button>

Thank you,
<br>
EJO
</x-mail::message>
