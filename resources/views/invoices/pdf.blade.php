<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Invoice</title>
<style>
body { font-family: DejaVu Sans, sans-serif; }
.header { background: #2e7d32; color: #fff; padding: 20px; text-align: center; }
table { width: 100%; border-collapse: collapse; margin: 20px 0; }
th { background: #a5d6a7; padding: 8px; text-align: left; }
td { padding: 8px; border-bottom: 1px solid #ddd; }
.total-row { font-weight: bold; background: #e8f5e9; }
</style>
</head>
<body>
<div class="header"><h1>INVOICE</h1><p>MOE LIMS</p></div>
<p><strong>Invoice:</strong> {{ $invoice_number }}</p>
<p><strong>Client:</strong> {{ $workOrder->client_name }}</p>
<p><strong>Work Order:</strong> {{ $workOrder->wo_number }}</p>
<table>
    <thead><tr><th>Sample</th><th>Test</th><th>Price</th></tr></thead>
    <tbody>
        @foreach($samples as $s)
            @foreach($s->tests as $t)
            <tr><td>{{ $s->sample_code }}</td><td>{{ $t->test_name }}</td><td>${{ number_format($t->price, 2) }}</td></tr>
            @endforeach
        @endforeach
        <tr class="total-row"><td colspan="2" style="text-align:right;">Subtotal</td><td>${{ number_format($subtotal, 2) }}</td></tr>
        <tr class="total-row"><td colspan="2" style="text-align:right;">Tax (10%)</td><td>${{ number_format($tax, 2) }}</td></tr>
        <tr class="total-row"><td colspan="2" style="text-align:right;">Total</td><td>${{ number_format($total, 2) }}</td></tr>
    </tbody>
</table>
</body>
</html>