@component('mail::message')
# Reimbursement 

Reimbursement oleh {{ $user->username }} sebesar {{ number_format($reimbursement->amount) }} telah diajukan.

@component('mail::button', ['url' => 'http://localhost:5173'])
Lihat Detail
@endcomponent

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent
