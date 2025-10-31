<div class="balance-grid">
    @foreach ($balances as $twister => $brands)
        @php
            $totalBalance = array_sum($brands);
        @endphp

        <div class="balance-card">
            <div class="balance-header">
                <h3>{{ $twister }}</h3>
                <span>Total: {{ App\Services\UnitService::formatUnitConversions($totalBalance, $rawMaterial->unit) }}
                </span>
            </div>

            <div class="brand-list">
                @foreach ($brands as $brand => $balance)
                    <div class="brand-item">
                        <span class="brand-name">{{ $brand }}</span>
                        <span
                            class="brand-amount">{{ App\Services\UnitService::formatUnitConversions($balance, $rawMaterial->unit) }}

                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
