<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Report</title>
<style>
body { font-family: DejaVu Sans, sans-serif; }
.header { background: #2e7d32; color: #fff; padding: 20px; text-align: center; }
table { width: 100%; border-collapse: collapse; margin: 20px 0; }
th { background: #a5d6a7; padding: 8px; }
td { padding: 8px; border-bottom: 1px solid #ddd; }
</style>
</head>
<body>
<div class="header"><h1>TEST REPORT</h1><p>MOE LIMS</p></div>
<p><strong>Report #:</strong> {{ $workOrder->report->report_number }}</p>
<p><strong>Client:</strong> {{ $workOrder->client_name }}</p>
<p><strong>Work Order:</strong> {{ $workOrder->wo_number }}</p>
<p><strong>Generated:</strong> {{ $generated_at->format('d/m/Y H:i') }}</p>
<h4>Results</h4>
<table>
    <thead><tr><th>Sample</th><th>Test</th><th>Result</th><th>Unit</th><th>Status</th></tr></thead>
    <tbody>
        @foreach($samples as $sample)
            @foreach($sample->tests as $test)
            <tr>
                <td>{{ $sample->sample_code }}</td>
                <td>{{ $test->test_name }}</td>
                <td>{{ $test->result->result_value ?? 'N/A' }}</td>
                <td>{{ $test->unit ?? 'N/A' }}</td>
                <td>{{ ucfirst($test->result->status ?? 'N/A') }}</td>
            </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
</body>
</html>