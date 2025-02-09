<!-- ticket/show_details.blade.php -->
@php
    use App\Http\Helpers\AppHelper;
@endphp
<div>
    <h4>Ticket ID: {{ $ticket->id }}</h4>
    <p><strong>Subject:</strong> {{ $ticket->subject }}</p>
    <p><strong>Description:</strong> {{ $ticket->description }}</p>
    <p><strong>Department:</strong> {{ $ticket->department->name }}</p>
    <p><strong>Assigned User:</strong> {{ $ticket->user ? $ticket->user->name : 'Unknown' }}</p>
    <p><strong>Status:</strong> {{ AppHelper::STATUS[$ticket->status_id] ?? 'Unknown' }}</p>
    <p><strong>Priority:</strong> {{ AppHelper::PRIORITY[$ticket->priority_id] ?? 'Unknown' }}</p>
</div>
