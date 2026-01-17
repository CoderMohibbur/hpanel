<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'HPanel') }}</title>
    <meta name="robots" content="noindex, nofollow">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100">
    {{-- Header --}}
    @include('components.hpanel.header')

    {{-- Sidebar --}}
    @include('components.hpanel.sidebar')

    {{-- @include('layouts.bottom-menu') --}}
    <div id="smooth-content"
     class="w-full mx-auto pt-16
            lg:[padding-left:var(--sidebar-pad,16rem)]
            transition-[padding-left]
            font-sans text-gray-900 antialiased">

            {{-- <div id="smooth-content"
     class="w-full mx-auto pt-16 lg:pl-64 transition-[padding]
            font-sans text-gray-900 antialiased"> --}}
        {{ $slot }}
    </div>

    {{-- footer start --}}
    {{-- @include('components.footer') --}}


    <style>
        /* --- Section reveal utility --- */
        .reveal {
            opacity: 0;
            transform: translateY(14px);
            transition: opacity .6s ease, transform .6s ease;
            will-change: opacity, transform;
        }

        .reveal-in {
            opacity: 1;
            transform: translateY(0);
        }

        @media (prefers-reduced-motion: reduce) {
            .reveal {
                opacity: 1;
                transform: none;
                transition: none;
            }
        }
    </style>

    <script>
        (function() {
            const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
            const touch = matchMedia('(hover: none), (pointer: coarse)').matches;

            /* =========================
               1) Inertia Smooth Scroll
               ========================= */
            // ভার্চুয়াল স্ক্রল: body নর্মাল স্ক্রল নেয়, কিন্তু কন্টেন্টকে translateY দিয়ে ইজ করে ফলো করাই
            // নোট: ফিক্সড হেডার/ফুটারে কোনো সমস্যা হবে না
            (function() {
                if (reduce || touch) return; // মোবাইল/টাচ বা reduce motion হলে বাদ

                const scroller = document.getElementById('smooth-content');
                if (!scroller) return;

                let current = window.scrollY;
                let target = current;
                const ease = 0.12; // ইনারশিয়ার কোমলতা (0.08–0.2 টিউন করুন)
                let ticking = false;

                // ডকুমেন্ট হাইট ঠিক রাখা
                const setBodyHeight = () => {
                    // scroller-এর আসল হাইট নিন
                    document.body.style.height = scroller.getBoundingClientRect().height + window.innerHeight *
                        0.001 + 'px';
                };
                setBodyHeight();
                new ResizeObserver(setBodyHeight).observe(scroller);
                window.addEventListener('resize', setBodyHeight);

                // রেন্ডার লুপ
                const raf = () => {
                    const y = window.scrollY || window.pageYOffset;
                    target = y;
                    current += (target - current) * ease;
                    // subpixel jitter কমাতে 3 decimal
                    scroller.style.transform = `translateY(${-current.toFixed(3)}px)`;
                    ticking = true;
                    requestAnimationFrame(raf);
                };
                requestAnimationFrame(raf);

                // performance hints
                scroller.style.willChange = 'transform';
                scroller.style.backfaceVisibility = 'hidden';
                scroller.style.transform = 'translateZ(0)';

                // anchor clicks থাকলে native scroll চলবে; আমরা transform দিয়ে ফলো করব
            })();

            /* =========================
               2) Section Reveal on View
               ========================= */
            (function() {
                const root = document.getElementById('smooth-content') || document;
                const els = Array.from(root.querySelectorAll('section, .reveal-item'));
                if (!els.length) return;
                if (reduce) {
                    els.forEach(el => el.classList.add('reveal-in'));
                    return;
                }
                // প্রথমে সব এলিমেন্টে .reveal লাগাই (except hero/topfold)
                els.forEach((el, idx) => {
                    // হিরো/হেডারের ঠিক পরের ওপরের অংশটা সাথে সাথে দেখাতে চাইলে skip করতে পারেন
                    if (idx < 1) return; // চাইলে 0 এর জায়গায় অন্য সংখ্যা
                    el.classList.add('reveal');
                });

                if (!('IntersectionObserver' in window)) {
                    els.forEach(el => el.classList.add('reveal-in'));
                    return;
                }

                const io = new IntersectionObserver((entries) => {
                    entries.forEach(e => {
                        if (e.isIntersecting) {
                            const delay = e.target.dataset.revealDelay ? parseInt(e.target.dataset
                                .revealDelay, 10) : 0;
                            setTimeout(() => {
                                e.target.classList.add('reveal-in');
                                e.target.classList.remove('reveal');
                            }, delay);
                            io.unobserve(e.target);
                        }
                    });
                }, {
                    root: null,
                    rootMargin: '0px 0px -10% 0px',
                    threshold: 0.15
                });

                els.forEach(el => {
                    // stagger-এর জন্য প্রতিটা সেকশনে ছোট delay দিতে পারেন
                    io.observe(el);
                });
            })();
        })();
    </script>

    {{-- lucid --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>

</body>

</html>
