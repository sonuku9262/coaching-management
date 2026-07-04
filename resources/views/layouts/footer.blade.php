<footer class="bg-white border-top text-center p-3 text-muted small">

    © {{ date('Y') }}

    {{ \App\Models\Setting::get('institute_name', 'Coaching Management System') }}

</footer>
