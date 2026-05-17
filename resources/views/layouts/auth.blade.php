<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config("app.name") }}</title>
    @vite(["resources/css/app.css", "resources/js/app.js"])
</head>
<body class="bg-dark-bg antialiased overflow-x-hidden">
    <div class="relative flex min-h-screen items-center justify-center p-4">

        {{-- Floating UI mockups in background --}}
        <div class="pointer-events-none fixed inset-0 z-0 overflow-hidden">
            <div class="absolute top-16 right-16 h-44 w-72 animate-float-1 rounded-2xl border border-white/10 bg-white/[0.03] shadow-lg backdrop-blur-[2px]">
                <div class="space-y-3 p-5">
                    <div class="flex items-center gap-2">
                        <div class="h-8 w-8 rounded-lg bg-neon-purple/20"></div>
                        <div>
                            <div class="h-2.5 w-20 rounded-full bg-white/10"></div>
                            <div class="mt-1.5 h-2 w-16 rounded-full bg-white/5"></div>
                        </div>
                    </div>
                    <div class="h-2 w-full rounded-full bg-white/5"></div>
                    <div class="h-2 w-3/4 rounded-full bg-white/5"></div>
                    <div class="mt-2 flex gap-2">
                        <div class="h-8 w-16 rounded-md bg-neon-purple/10"></div>
                        <div class="h-8 w-16 rounded-md bg-deep-blue/10"></div>
                    </div>
                </div>
            </div>

            <div class="absolute bottom-28 left-16 h-72 w-44 animate-float-2 rounded-3xl border border-white/10 bg-white/[0.03] shadow-lg backdrop-blur-[2px]">
                <div class="space-y-4 p-4">
                    <div class="h-8 w-8 rounded-xl bg-gradient-to-br from-neon-purple/20 to-deep-blue/20"></div>
                    <div class="space-y-2">
                        <div class="h-2 w-full rounded-full bg-white/10"></div>
                        <div class="h-2 w-2/3 rounded-full bg-white/5"></div>
                    </div>
                    <div class="h-24 rounded-xl border border-white/5 bg-white/[0.02]"></div>
                    <div class="flex justify-center gap-1">
                        <div class="h-1.5 w-1.5 rounded-full bg-white/20"></div>
                        <div class="h-1.5 w-1.5 rounded-full bg-white/5"></div>
                        <div class="h-1.5 w-1.5 rounded-full bg-white/5"></div>
                    </div>
                </div>
            </div>

            <div class="absolute left-8 top-1/3 h-32 w-60 animate-float-3 rounded-xl border border-white/10 bg-white/[0.03] shadow-lg backdrop-blur-[2px]">
                <div class="space-y-2 p-4">
                    <div class="flex items-center justify-between">
                        <div class="h-2.5 w-16 rounded-full bg-white/10"></div>
                        <div class="h-5 w-5 rounded-full bg-white/5"></div>
                    </div>
                    <div class="flex h-12 items-end gap-1">
                        <div class="w-4 rounded-t-sm bg-neon-purple/20" style="height: 70%"></div>
                        <div class="w-4 rounded-t-sm bg-deep-blue/20" style="height: 40%"></div>
                        <div class="w-4 rounded-t-sm bg-neon-purple/20" style="height: 90%"></div>
                        <div class="w-4 rounded-t-sm bg-deep-blue/20" style="height: 55%"></div>
                        <div class="w-4 rounded-t-sm bg-lavender/20" style="height: 75%"></div>
                    </div>
                </div>
            </div>

            <div class="absolute bottom-36 right-28 h-24 w-48 animate-float-4 rounded-lg border border-white/10 bg-white/[0.03] shadow-lg backdrop-blur-[2px]">
                <div class="flex items-center gap-3 p-4">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-neon-purple/20 to-deep-blue/20"></div>
                    <div class="flex-1 space-y-1.5">
                        <div class="h-2 w-16 rounded-full bg-white/10"></div>
                        <div class="h-3 w-10 rounded-full bg-white/15"></div>
                    </div>
                </div>
            </div>

            <div class="absolute right-1/3 top-24 h-16 w-16 animate-float-5 rounded-full border border-white/5 bg-white/[0.02] shadow-lg backdrop-blur-[2px]"></div>
        </div>

        {{-- Main authentication card --}}
        <div class="relative z-10 flex w-full max-w-5xl overflow-hidden rounded-[30px] border-[3px] border-neon-purple bg-white shadow-[0_0_60px_rgba(108,59,255,0.15)]">

            {{-- Tab/notch at top center --}}
            <div class="absolute -top-3 left-1/2 z-20 -translate-x-1/2">
                <div class="h-6 w-20 rounded-b-lg bg-gradient-to-r from-neon-purple to-deep-blue shadow-[0_0_20px_rgba(108,59,255,0.3)]"></div>
            </div>

            {{-- Left Panel — Dark neon cyberpunk visual --}}
            <div class="relative hidden w-[35%] overflow-hidden lg:block">
                <div class="relative h-full w-full bg-gradient-to-br from-[#0F0A1E] via-[#1A0A2E] to-[#0D0620]">

                    {{-- Grid overlay --}}
                    <div class="cyberpunk-grid absolute inset-0 opacity-40"></div>

                    {{-- Scan line --}}
                    <div class="absolute inset-0 animate-scan-line bg-gradient-to-b from-transparent via-neon-purple/[0.04] to-transparent"></div>

                    {{-- Glowing figure 1 (left) --}}
                    <div class="absolute bottom-16 left-7 w-28 h-44">
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-14 h-14 rounded-full bg-neon-purple/20 blur-xl"></div>
                        <div class="absolute top-2 left-1/2 -translate-x-1/2 w-11 h-13 rounded-full border border-neon-purple/40"></div>
                        <div class="absolute top-13 left-0 w-full h-14 rounded-t-full border border-neon-purple/30"></div>
                        <div class="absolute top-3 left-1/2 -translate-x-1/2 w-7 h-0.5 bg-neon-purple blur-sm"></div>
                        <div class="absolute top-8 left-1/2 -translate-x-1/2 w-5 h-5 rounded-full border border-neon-purple/20"></div>
                    </div>

                    {{-- Glowing figure 2 (right) --}}
                    <div class="absolute bottom-20 right-8 w-24 h-40">
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-12 rounded-full bg-deep-blue/20 blur-xl"></div>
                        <div class="absolute top-2 left-1/2 -translate-x-1/2 w-9 h-11 rounded-full border border-deep-blue/40"></div>
                        <div class="absolute top-11 left-0 w-full h-12 rounded-t-full border border-deep-blue/30"></div>
                        <div class="absolute top-3 left-1/2 -translate-x-1/2 w-6 h-0.5 bg-deep-blue blur-sm"></div>
                        <div class="absolute top-8 left-1/2 -translate-x-1/2 w-4 h-4 rounded-full border border-deep-blue/20"></div>
                    </div>

                    {{-- Holographic interface ring --}}
                    <div class="absolute top-6 right-5 w-24 h-24 rounded-full border-2 border-lavender/30">
                        <div class="absolute inset-2 rounded-full border border-lavender/20"></div>
                        <div class="absolute top-1/2 left-0 w-full h-px bg-gradient-to-r from-transparent via-lavender/40 to-transparent"></div>
                        <div class="absolute left-1/2 top-0 h-full w-px bg-gradient-to-b from-transparent via-lavender/40 to-transparent"></div>
                        <div class="absolute -right-1.5 top-1/2 -translate-y-1/2 h-2 w-2 animate-pulse-glow rounded-full bg-lavender"></div>
                        <div class="absolute -top-1.5 left-1/2 -translate-x-1/2 h-2 w-2 animate-pulse-glow rounded-full bg-deep-blue"></div>
                    </div>

                    {{-- Floating data particles --}}
                    <div class="absolute top-1/4 right-1/4 h-1 w-1 animate-pulse-glow rounded-full bg-neon-purple/60" style="animation-delay: 1s;"></div>
                    <div class="absolute top-1/3 left-1/3 h-1.5 w-1.5 animate-pulse-glow rounded-full bg-deep-blue/50" style="animation-delay: 2s;"></div>
                    <div class="absolute bottom-1/3 right-1/3 h-1 w-1 animate-pulse-glow rounded-full bg-lavender/40" style="animation-delay: 0.5s;"></div>
                    <div class="absolute left-1/4 top-2/3 h-1.5 w-1.5 animate-pulse-glow rounded-full bg-neon-purple/30" style="animation-delay: 1.5s;"></div>
                    <div class="absolute left-1/2 top-1/4 h-1 w-1 animate-pulse-glow rounded-full bg-deep-blue/40" style="animation-delay: 2.5s;"></div>

                    {{-- Decorative neon blurs --}}
                    <div class="absolute -top-20 -right-20 h-48 w-48 rounded-full bg-neon-purple/10 blur-3xl"></div>
                    <div class="absolute -bottom-16 -left-16 h-40 w-40 rounded-full bg-deep-blue/10 blur-3xl"></div>
                    <div class="absolute left-1/2 top-1/2 h-24 w-24 -translate-x-1/2 -translate-y-1/2 rounded-full bg-lavender/5 blur-2xl"></div>

                    {{-- Bottom branding --}}
                    <div class="absolute bottom-5 left-1/2 -translate-x-1/2 text-center">
                        <p class="font-mono text-[10px] tracking-[0.2em] text-white/20">SECURE PLATFORM</p>
                        <div class="mt-1 flex justify-center gap-1">
                            <div class="h-1 w-1 rounded-full bg-neon-purple/40"></div>
                            <div class="h-1 w-1 rounded-full bg-deep-blue/40"></div>
                            <div class="h-1 w-1 rounded-full bg-lavender/40"></div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Right Panel — White form container --}}
            <div class="w-full lg:w-[65%] bg-white p-8 lg:p-12">
                {{ $slot }}
            </div>

        </div>
    </div>
</body>
</html>
