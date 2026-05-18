<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    @vite(["resources/css/app.css", "resources/js/app.js"])
</head>
<body class="bg-surface-50 antialiased overflow-x-hidden">
    <div class="relative flex min-h-screen items-center justify-center p-4 sm:p-6">

        {{-- ── Background hero panel ──────────────────────────────────────── --}}
        <div class="pointer-events-none fixed inset-0 z-0 overflow-hidden">
            {{-- Floating abstract card — top right --}}
            <div class="
                absolute top-16 right-16
                h-44 w-72
                rounded-2xl
                border border-brand-500/[.10]
                bg-brand-500/[.03]
                shadow-lg
                backdrop-blur-[2px]
                animate-float-1
            ">
                <div class="space-y-3 p-5">
                    <div class="flex items-center gap-2">
                        <div class="h-8 w-8 rounded-lg bg-brand-500/15"></div>
                        <div>
                            <div class="h-2.5 w-20 rounded-full bg-surface-300/50"></div>
                            <div class="mt-1.5 h-2 w-16 rounded-full bg-surface-200/40"></div>
                        </div>
                    </div>
                    <div class="h-2 w-full rounded-full bg-surface-200/40"></div>
                    <div class="h-2 w-3/4 rounded-full bg-surface-200/40"></div>
                    <div class="mt-2 flex gap-2">
                        <div class="h-8 w-16 rounded-md bg-brand-500/12"></div>
                        <div class="h-8 w-16 rounded-md bg-brand-b-500/12"></div>
                    </div>
                </div>
            </div>

            {{-- Floating abstract card — bottom left --}}
            <div class="
                absolute bottom-28 left-16
                h-72 w-44
                rounded-3xl
                border border-brand-b-500/[.10]
                bg-brand-b-500/[.03]
                shadow-lg
                backdrop-blur-[2px]
                animate-float-2
            ">
                <div class="space-y-4 p-4">
                    <div class="h-8 w-8 rounded-xl bg-gradient-to-br from-brand-500/20 to-brand-b-500/20"></div>
                    <div class="space-y-2">
                        <div class="h-2 w-full rounded-full bg-surface-300/50"></div>
                        <div class="h-2 w-2/3 rounded-full bg-surface-200/40"></div>
                    </div>
                    <div class="h-24 rounded-xl border border-brand-500/08 bg-brand-500/[.02]"></div>
                    <div class="flex justify-center gap-1">
                        <div class="h-1.5 w-1.5 rounded-full bg-brand-500/30"></div>
                        <div class="h-1.5 w-1.5 rounded-full bg-brand-b-500/20"></div>
                        <div class="h-1.5 w-1.5 rounded-full bg-brand-l-300/20"></div>
                    </div>
                </div>
            </div>

            {{-- Chart mock-up — left centre --}}
            <div class="
                absolute left-8 top-1/3
                h-32 w-60
                rounded-xl
                border border-brand-500/[.08]
                bg-brand-500/[.02]
                shadow-lg
                backdrop-blur-[2px]
                animate-float-3
            ">
                <div class="space-y-2 p-4">
                    <div class="flex items-center justify-between">
                        <div class="h-2.5 w-16 rounded-full bg-surface-300/50"></div>
                        <div class="h-5 w-5 rounded-full bg-surface-200/40"></div>
                    </div>
                    <div class="flex h-12 items-end gap-1">
                        <div class="w-4 rounded-t-sm bg-brand-500/20" style="height:70%"></div>
                        <div class="w-4 rounded-t-sm bg-brand-b-500/20" style="height:40%"></div>
                        <div class="w-4 rounded-t-sm bg-brand-500/20" style="height:90%"></div>
                        <div class="w-4 rounded-t-sm bg-brand-b-500/20" style="height:55%"></div>
                        <div class="w-4 rounded-t-sm bg-brand-l-300/20" style="height:75%"></div>
                    </div>
                </div>
            </div>

            {{-- Notification card — bottom right --}}
            <div class="
                absolute bottom-36 right-28
                h-24 w-48
                rounded-lg
                border border-brand-b-500/[.10]
                bg-surface-50/[.08]
                shadow-lg
                backdrop-blur-[2px]
                animate-float-4
            ">
                <div class="flex items-center gap-3 p-4">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-brand-500/22 to-brand-b-500/22"></div>
                    <div class="flex-1 space-y-1.5">
                        <div class="h-2 w-16 rounded-full bg-surface-300/40"></div>
                        <div class="h-3 w-10 rounded-full bg-surface-400/40"></div>
                    </div>
                </div>
            </div>

            {{-- Orb — right centre --}}
            <div class="
                absolute right-1/3 top-24
                h-16 w-16
                rounded-full
                border border-brand-l-300/20
                bg-brand-l-300/[.02]
                shadow-lg
                backdrop-blur-[2px]
                animate-float-5
            "></div>

            {{-- Soft brand ambient glows --}}
            <div class="pointer-events-none absolute -top-24 -right-24 h-56 w-56 rounded-full bg-brand-500/08 blur-3xl saturate-150"></div>
            <div class="pointer-events-none absolute -bottom-20 -left-20 h-48 w-48 rounded-full bg-brand-b-500/08 blur-3xl saturate-150"></div>
            <div class="pointer-events-none absolute left-1/2 top-1/2 h-28 w-28 -translate-x-1/2 -translate-y-1/2 rounded-full bg-brand-l-400/05 blur-2xl saturate-150"></div>
        </div>

        {{-- ── Auth card ────────────────────────────────────────────────────── --}}
        <div class="
            relative z-10
            flex w-full
            max-w-[960px]
            overflow-hidden
            rounded-[var(--radius-auth-card)]
            border-[3px] border-brand-500/70
            bg-white
            shadow-[0_0_60px_rgba(108,59,255,.15)]
        ">

            {{-- Tab notch at top centre --}}
            <div class="absolute -top-3 left-1/2 z-20 -translate-x-1/2">
                <div class="
                    h-6 w-20
                    rounded-b-lg
                    bg-gradient-to-r from-brand-500 to-brand-b-500
                    shadow-[0_0_20px_rgba(108,59,255,.30)]
                "></div>
            </div>

            {{-- ── Left panel — dark brand gradient ──────────────────────────── --}}
            <div class="relative hidden w-[35%] overflow-hidden lg:block">
                <div class="relative h-full w-full
                    bg-gradient-to-br
                    from-[#0F0A1E] via-[#1A0A2E] to-[#0D0620]
                ">
                    {{-- Grid overlay --}}
                    <div class="cyberpunk-grid absolute inset-0 opacity-40"></div>

                    {{-- Scanning line --}}
                    <div class="
                        absolute inset-0
                        animate-scan-line
                        bg-gradient-to-b from-transparent via-brand-500/[.04] to-transparent
                    "></div>

                    {{-- Figure 1 (left) --}}
                    <div class="absolute bottom-16 left-7 w-28 h-44">
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-14 h-14 rounded-full bg-brand-500/18 blur-xl"></div>
                        <div class="absolute top-2 left-1/2 -translate-x-1/2 w-11 h-13 rounded-full border border-brand-500/38"></div>
                        <div class="absolute top-13 left-0 w-full h-14 rounded-t-full border border-brand-500/28"></div>
                        <div class="absolute top-3 left-1/2 -translate-x-1/2 w-7 h-0.5 bg-brand-500 blur-sm"></div>
                        <div class="absolute top-8 left-1/2 -translate-x-1/2 w-5 h-5 rounded-full border border-brand-500/18"></div>
                    </div>

                    {{-- Figure 2 (right) --}}
                    <div class="absolute bottom-20 right-8 w-24 h-40">
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-12 rounded-full bg-brand-b-500/18 blur-xl"></div>
                        <div class="absolute top-2 left-1/2 -translate-x-1/2 w-9 h-11 rounded-full border border-brand-b-500/38"></div>
                        <div class="absolute top-11 left-0 w-full h-12 rounded-t-full border border-brand-b-500/28"></div>
                        <div class="absolute top-3 left-1/2 -translate-x-1/2 w-6 h-0.5 bg-brand-b-500 blur-sm"></div>
                        <div class="absolute top-8 left-1/2 -translate-x-1/2 w-4 h-4 rounded-full border border-brand-b-500/18"></div>
                    </div>

                    {{-- Holographic ring — top right --}}
                    <div class="absolute top-6 right-5 w-24 h-24 rounded-full border-2 border-brand-l-300/28">
                        <div class="absolute inset-2 rounded-full border border-brand-l-300/18"></div>
                        <div class="absolute top-1/2 left-0 w-full h-px bg-gradient-to-r from-transparent via-brand-l-300/38 to-transparent"></div>
                        <div class="absolute left-1/2 top-0 h-full w-px bg-gradient-to-b from-transparent via-brand-l-300/38 to-transparent"></div>
                        <div class="absolute -right-1.5 top-1/2 -translate-y-1/2 h-2 w-2 animate-pulse-glow rounded-full bg-brand-l-300"></div>
                        <div class="absolute -top-1.5 left-1/2 -translate-x-1/2 h-2 w-2 animate-pulse-glow rounded-full bg-brand-b-500"></div>
                    </div>

                    {{-- Data particles --}}
                    <div class="absolute top-1/4 right-1/4 h-1 w-1 animate-pulse-glow rounded-full bg-brand-500/55" style="animation-delay:1s"></div>
                    <div class="absolute top-1/3 left-1/3 h-1.5 w-1.5 animate-pulse-glow rounded-full bg-brand-b-500/45" style="animation-delay:2s"></div>
                    <div class="absolute bottom-1/3 right-1/3 h-1 w-1 animate-pulse-glow rounded-full bg-brand-l-300/35" style="animation-delay:.5s"></div>
                    <div class="absolute left-1/4 top-2/3 h-1.5 w-1.5 animate-pulse-glow rounded-full bg-brand-500/28" style="animation-delay:1.5s"></div>
                    <div class="absolute left-1/2 top-1/4 h-1 w-1 animate-pulse-glow rounded-full bg-brand-b-500/38" style="animation-delay:2.5s"></div>

                    {{-- Bottom branding lockup --}}
                    <div class="absolute bottom-5 left-1/2 -translate-x-1/2 text-center">
                        <p class="font-mono text-[10px] tracking-[0.22em] text-white/18 uppercase">Secure Platform</p>
                        <div class="mt-1 flex justify-center gap-1">
                            <div class="h-1 w-1 rounded-full bg-brand-500/38"></div>
                            <div class="h-1 w-1 rounded-full bg-brand-b-500/38"></div>
                            <div class="h-1 w-1 rounded-full bg-brand-l-300/38"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Right panel — white form ────────────────────────────────────── --}}
            <div class="
                w-full lg:w-[65%]
                bg-white
                px-6 py-8 sm:px-8 sm:py-10
                lg:px-12 lg:py-14
            ">
                {{ $slot }}
            </div>

        </div>
    </div>
</body>
</html>
