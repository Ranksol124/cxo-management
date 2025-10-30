// --- Load from CDN instead of import ---
if (typeof Echo === 'undefined') {
    const pusherScript = document.createElement('script');
    pusherScript.src = "https://js.pusher.com/8.2.0/pusher.min.js";
    document.head.appendChild(pusherScript);

    const echoScript = document.createElement('script');
    echoScript.src = "https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js";
    document.head.appendChild(echoScript);
}

window.addEventListener('load', function () {
    // Wait until libraries are ready
    setTimeout(() => {
        console.log("✅ Filament Echo initialized via CDN");

        window.Echo = new Echo({
            broadcaster: "pusher",
            key: "{{ config('broadcasting.connections.pusher.key') }}",
            cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
            forceTLS: true,
            authEndpoint: "/broadcasting/auth",
            auth: {
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                },
            },
        });

        window.Echo.private("members")
            .listen(".event.created", (data) => {
                console.log("✅ New event received:", data);
                window.dispatchEvent(new CustomEvent("member-notification", { detail: data }));
            })
            .error((e) => console.error("❌ Echo error:", e));
    }, 500);
});
