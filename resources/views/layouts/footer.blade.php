<footer style="text-align:center;padding:1rem;font-size:.75rem;color:var(--muted)">
    &copy; {{ date('Y') }} {{ tenant()?->name ?? config('app.name') }} &middot; Powered by {{ config('app.name') }}
</footer>