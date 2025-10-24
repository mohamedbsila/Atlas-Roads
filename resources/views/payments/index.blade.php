<x-layouts.app>
    <div>
        @if(session('success'))
            <div class="mb-4 p-4 text-white rounded-xl" style="background:linear-gradient(to right,#84cc16,#4ade80)">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-4 p-4 text-white rounded-xl" style="background:linear-gradient(to right,#ef4444,#dc2626)">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <div>
                <h5 class="mb-0 font-bold">Payments</h5>
                <p class="text-size-sm text-slate-400">Overview of your payments</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- My Payments (as borrower) -->
            <div class="bg-white rounded-2xl shadow p-4">
                <h6 class="font-bold mb-3">My Payments</h6>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-slate-500">
                                <th class="py-2">Book</th>
                                <th class="py-2">Type</th>
                                <th class="py-2">Total</th>
                                <th class="py-2">Per Day</th>
                                <th class="py-2">Status</th>
                                <th class="py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($myPayments as $p)
                                <tr class="border-t">
                                    <td class="py-2">{{ $p->book->title ?? '—' }}</td>
                                    <td class="py-2">{{ $p->type === 'purchase' ? 'Purchase' : 'Borrow' }}</td>
                                    <td class="py-2 font-bold">{{ $p->amount_total_formatted }}</td>
                                    <td class="py-2">{{ $p->type === 'purchase' ? '—' : $p->amount_per_day_formatted }}</td>
                                    <td class="py-2">
                                        @if($p->status === 'paid')
                                            <span class="px-2 py-1 rounded text-white" style="background:#22c55e">Paid</span>
                                        @else
                                            <span class="px-2 py-1 rounded text-white" style="background:#64748b">Pending</span>
                                        @endif
                                    </td>
                                    <td class="py-2">
                                        @if($p->status !== 'paid')
                                            <!-- Payer via Stripe si c'est un emprunt -->
                                            @if($p->isBorrow() && $p->borrowRequest)
                                                <form action="{{ route('borrow-requests.pay', $p->borrowRequest) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-2 text-xs text-white rounded" style="background:linear-gradient(to right,#8b5cf6,#7c3aed)">
                                                        💳 Pay with Stripe
                                                    </button>
                                                </form>
                                            @endif
                                            <!-- Fallback manuel -->
                                            <form action="{{ route('payments.mark-paid', $p) }}" method="POST" class="inline mt-1">
                                                @csrf
                                                <input type="hidden" name="method" value="cash" />
                                                <button type="submit" class="px-3 py-2 text-xs text-white rounded" style="background:linear-gradient(to right,#06b6d4,#0ea5e9)">Mark as Paid</button>
                                            </form>
                                        @else
                                            <span class="text-slate-400">
                                                ✅ {{ ucfirst($p->method ?? 'paid') }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td class="py-4 text-slate-400" colspan="6">No payments found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $myPayments->appends(request()->except('my_page'))->links() }}</div>
            </div>

            <!-- Received Payments (as owner) -->
            <div class="bg-white rounded-2xl shadow p-4">
                <h6 class="font-bold mb-3">Received Payments</h6>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-slate-500">
                                <th class="py-2">Book</th>
                                <th class="py-2">Borrower</th>
                                <th class="py-2">Type</th>
                                <th class="py-2">Total</th>
                                <th class="py-2">Status</th>
                                <th class="py-2">Paid At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($receivedPayments as $p)
                                <tr class="border-t">
                                    <td class="py-2">{{ $p->book->title ?? '—' }}</td>
                                    <td class="py-2">{{ $p->borrower->name ?? '—' }}</td>
                                    <td class="py-2">{{ $p->type === 'purchase' ? 'Purchase' : 'Borrow' }}</td>
                                    <td class="py-2 font-bold">{{ $p->amount_total_formatted }}</td>
                                    <td class="py-2">
                                        @if($p->status === 'paid')
                                            <span class="px-2 py-1 rounded text-white" style="background:#22c55e">Paid</span>
                                        @else
                                            <span class="px-2 py-1 rounded text-white" style="background:#64748b">Pending</span>
                                        @endif
                                    </td>
                                    <td class="py-2">{{ $p->paid_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr><td class="py-4 text-slate-400" colspan="6">No payments received.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $receivedPayments->appends(request()->except('rec_page'))->links() }}</div>
            </div>
        </div>
    </div>
</x-layouts.app>
