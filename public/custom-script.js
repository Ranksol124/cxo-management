console.log("Custom script loaded");

document.addEventListener("DOMContentLoaded", () => {
    document.addEventListener("click", (e) => {
        // Copy button
        if (e.target.closest(".copy-password")) {
            const wrapper = e.target.closest(".fi-fo-field-wrp, .fi-fo-field, .filament-forms-field-wrapper");
            const input = wrapper ? wrapper.querySelector("input, textarea") : null;
            if (!input) return;

            const value = input.value ?? "";
            if (!value) return;

            if (navigator.clipboard?.writeText) {
                navigator.clipboard.writeText(value).then(() => {
                    console.log("Password copied!");
                }).catch(() => fallbackCopy(value));
            } else {
                fallbackCopy(value);
            }

            function fallbackCopy(text) {
                const temp = document.createElement("input");
                temp.style.position = "fixed";
                temp.style.left = "-9999px";
                temp.value = text;
                document.body.appendChild(temp);
                temp.select();
                document.execCommand("copy");
                document.body.removeChild(temp);
                console.log("Password copied (fallback)!");
            }
        }

        // Eye toggle button
        if (e.target.closest(".eye-toggle")) {
            const wrapper = e.target.closest(".fi-fo-field-wrp, .fi-fo-field, .filament-forms-field-wrapper");
            const input = wrapper ? wrapper.querySelector("input, textarea") : null;
            if (!input) return;

            if (input.type === "password") {
                input.type = "text";
                e.target.closest("button").innerHTML =
                    `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>`;
            } else {
                input.type = "password";
                e.target.closest("button").innerHTML =
                    `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.959 9.959 0 012.241-3.61m3.257-2.525A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.123 5.411M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" /></svg>`;
            }
        }
    });
});

