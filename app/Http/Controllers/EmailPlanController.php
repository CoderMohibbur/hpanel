<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class EmailPlanController extends Controller
{
    /**
     * Email plans পেজ
     */
    public function index()
    {
        // চাইলে এখানে SEO meta/pass data করতে পারো
        return view('components.hpanel.email'); // <- তুমি যেটা বানিয়েছো
    }

    /**
     * Checkout প্রিভিউ (query: plan, months)
     * GET /checkout/email?plan=business-premium&months=48
     */
    public function checkout(Request $request)
    {
        $plans = collect($this->plans())->keyBy('slug');

        $validated = $request->validate([
            'plan'   => ['required', Rule::in($plans->keys()->toArray())],
            'months' => ['required', Rule::in([1, 12, 24, 48])],
        ]);

        $plan   = $plans[$validated['plan']];
        $months = (int) $validated['months'];

        // per-month price (tiers), Workspace locked
        $perMonth = $this->perMonth($plan, $months);
        $subtotal = round($perMonth * $months, 2);

        $payload = [
            'plan'       => $plan,
            'months'     => $months,
            'per_month'  => $perMonth,
            'subtotal'   => $subtotal,
            'renew'      => [
                'price_per_month' => $plan['rack'],
                'period_label'    => $months === 1 ? '1 month' : '1 year',
            ],
            'expires_at' => now()->addMonths($months)->toDateString(),
        ];

        // তোমার ইচ্ছায়: Blade view বা JSON—দুটোই রাখলাম।
        if ($request->wantsJson()) {
            return response()->json($payload);
        }

        // একটি সিম্পল checkout ভিউতে ডাটা পাঠাও (নিজে ডিজাইন করবে)
        return view('checkout.email', $payload);
    }

    /**
     * (Optional) অর্ডার/ইনভয়েস তৈরির উদাহরণ
     */
    public function placeOrder(Request $request)
    {
        $plans = collect($this->plans())->keyBy('slug');

        $data = $request->validate([
            'plan'   => ['required', Rule::in($plans->keys()->toArray())],
            'months' => ['required', Rule::in([1, 12, 24, 48])],
            'quantity' => ['nullable', 'integer', 'min:1'], // কতগুলো মেইলবক্স
        ]);

        $plan     = $plans[$data['plan']];
        $months   = (int) $data['months'];
        $qty      = max(1, (int) ($data['quantity'] ?? 1));

        $perMonth = $this->perMonth($plan, $months);
        $subtotal = round($perMonth * $months * $qty, 2);

        // TODO: এখানে তোমার Order/Invoice মডেল তৈরি করো
        // Order::create([...]);

        // ডেমো: পেমেন্ট গেটওয়েতে রিডাইরেক্ট/সাকসেস পেজ
        return redirect()
            ->route('email.plans')
            ->with('success', "Order placed for {$qty} mailbox(es) of {$plan['name']} – total \${$subtotal}");
    }

    /**
     * পরিকল্পনার সোর্স: blade-এর সাথে একই ডাটা
     */
    private function plans(): array
    {
        return [
            [
                'slug'     => 'business-starter',
                'name'     => 'Business Starter',
                'tag'      => null,
                'accent'   => false,
                'price_m'  => 0.39,  // 48m best
                'rack'     => 2.99,  // 1m
                'locked'   => false, // discounts apply
                'features' => [
                    '10 GB Storage per Mailbox',
                    '10 Forwarding Rules',
                    '10 Email Aliases',
                    '1000 emails/day per Mailbox',
                    'Free domain',
                    'Advanced spam, malware, and phishing protection',
                    'Option to get extra mailbox storage',
                    'No Hostinger signature in Webmail',
                ],
            ],
            [
                'slug'     => 'business-premium',
                'name'     => 'Business Premium',
                'tag'      => 'MOST POPULAR',
                'accent'   => true,
                'price_m'  => 1.99,  // 48m best
                'rack'     => 5.99,  // 1m
                'locked'   => false,
                'features' => [
                    '50 GB Storage per Mailbox',
                    '50 Forwarding Rules',
                    '50 Email Aliases',
                    '3000 emails/day per Mailbox',
                    'Free domain',
                    'Advanced spam, malware, and phishing protection',
                    'Option to get extra mailbox storage',
                    'No Hostinger signature in Webmail',
                ],
            ],
            [
                'slug'     => 'google-workspace',
                'name'     => 'Google Workspace',
                'tag'      => null,
                'accent'   => false,
                'price_m'  => 5.99,  // shown on card (but locked)
                'rack'     => 5.99,  // same monthly
                'locked'   => true,  // no discount tiers
                'features' => [
                    '30 GB Storage per Mailbox',
                    'Email Matching your Domain Name',
                    'Gmail', 'Calendar',
                    'Docs, Sheets, Slides',
                    'Chat Team Messaging',
                    'AppSheet', 'Meet Video Conferencing',
                ],
            ],
        ];
    }

    /**
     * নির্বাচিত মাস অনুযায়ী per‑month দাম
     * 48(best), 24(+15%), 12(+50%), 1=rack; locked হলে সবসময় rack
     */
    private function perMonth(array $plan, int $months): float
    {
        if (!in_array($months, [1, 12, 24, 48], true)) {
            $months = 12;
        }

        if (!empty($plan['locked'])) {
            return (float) $plan['rack'];
        }

        $base = (float) $plan['price_m']; // 48 months best
        return match ($months) {
            48 => $base,
            24 => round($base * 1.15, 2),
            12 => round($base * 1.50, 2),
            default => (float) $plan['rack'],
        };
    }
}
