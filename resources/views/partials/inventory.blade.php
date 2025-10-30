<div class="balance-grid">
    @foreach ($balances as $location => $brands)
        @php $totalBalance = array_sum($brands); @endphp

        <div class="balance-card">
            <div class="balance-header">
                <h3>{{ $location }}</h3>
                <span>Total: {{ number_format($totalBalance, 2) }} Kg</span>
            </div>

            <div class="brand-list">
                @foreach ($brands as $brand => $balance)
                    <div class="brand-item">
                        <span class="brand-name">{{ $brand }}</span>
                        <span class="brand-amount">{{ number_format($balance, 2) }} Kg</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
