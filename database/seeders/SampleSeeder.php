<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkOrder;
use App\Models\Sample;
use App\Models\SampleTest;
use App\Models\TestResult;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Report;
use Carbon\Carbon;

class SampleSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('role', 'admin')->first();
        $technician = User::where('role', 'technician')->first();
        $approver = User::where('role', 'approver')->first();
        $receptionist = User::where('role', 'receptionist')->first();

        // Work Order 1 – Submitted
        $wo1 = WorkOrder::create([
            'wo_number' => WorkOrder::generateWONumber(),
            'client_name' => 'ABC Industries',
            'client_email' => 'contact@abc.com',
            'client_phone' => '012345678',
            'client_organization' => 'ABC Corp',
            'project_description' => 'River water quality assessment',
            'order_date' => Carbon::now()->subDays(5),
            'expected_completion_date' => Carbon::now()->addDays(10),
            'priority' => 'high',
            'status' => 'submitted',
            'created_by' => $receptionist->id,
        ]);

        $sample1 = $wo1->samples()->create([
            'sample_code' => Sample::generateSampleCode($wo1->id),
            'sample_type' => 'Water',
            'sample_matrix' => 'River Water',
            'sampling_location' => 'Mekong River',
            'sampling_date' => Carbon::now()->subDays(4),
            'sampling_time' => '10:30:00',
            'sampling_method' => 'Grab Sampling',
            'container_type' => 'Plastic Bottle',
            'preservation_method' => 'HNO3',
            'received_date' => Carbon::now()->subDays(3),
            'received_time' => '14:00:00',
            'received_by' => 'Reception Staff',
            'sample_description' => 'Surface water sample',
            'sample_condition' => 'Good',
            'sample_quantity' => 2.0,
            'quantity_unit' => 'L',
            'status' => 'received',
        ]);

        $test1 = $sample1->tests()->create([
            'test_name' => 'pH',
            'test_code' => 'PH-001',
            'test_category' => 'Physical',
            'parameter' => 'pH Level',
            'unit' => 'pH',
            'method' => 'Electrometric',
            'price' => 20.00,
        ]);
        $test1->result()->create(['status' => 'pending']);

        $test2 = $sample1->tests()->create([
            'test_name' => 'Lead (Pb)',
            'test_code' => 'HM-002',
            'test_category' => 'Heavy Metal',
            'parameter' => 'Lead Concentration',
            'unit' => 'mg/L',
            'method' => 'AAS',
            'price' => 50.00,
        ]);
        $test2->result()->create(['status' => 'pending']);

        // Work Order 2 – Results entered
        $wo2 = WorkOrder::create([
            'wo_number' => WorkOrder::generateWONumber(),
            'client_name' => 'Green Earth Ltd.',
            'client_email' => 'info@greenearth.com',
            'client_phone' => '098765432',
            'client_organization' => 'Green Earth NGO',
            'project_description' => 'Soil nutrient analysis',
            'order_date' => Carbon::now()->subDays(10),
            'expected_completion_date' => Carbon::now()->addDays(5),
            'priority' => 'medium',
            'status' => 'in_progress',
            'created_by' => $technician->id,
        ]);

        $sample2 = $wo2->samples()->create([
            'sample_code' => Sample::generateSampleCode($wo2->id),
            'sample_type' => 'Soil',
            'sample_matrix' => 'Agricultural Soil',
            'sampling_location' => 'Kampong Cham',
            'sampling_date' => Carbon::now()->subDays(8),
            'sampling_time' => '08:00:00',
            'sampling_method' => 'Composite Sampling',
            'container_type' => 'Plastic Bag',
            'preservation_method' => 'Air Dried',
            'received_date' => Carbon::now()->subDays(7),
            'received_time' => '11:30:00',
            'received_by' => 'Lab Staff',
            'sample_description' => 'Topsoil from rice field',
            'sample_condition' => 'Good',
            'sample_quantity' => 500,
            'quantity_unit' => 'g',
            'status' => 'results_entered',
        ]);

        $test3 = $sample2->tests()->create([
            'test_name' => 'Total Nitrogen',
            'test_code' => 'NUT-001',
            'test_category' => 'Nutrient',
            'parameter' => 'Nitrogen Content',
            'unit' => '%',
            'method' => 'Kjeldahl',
            'price' => 80.00,
        ]);
        $test3->result()->create([
            'result_value' => 2.5,
            'status' => 'entered',
            'entered_by' => $technician->id,
            'entered_at' => now()->subHours(2),
        ]);

        $test4 = $sample2->tests()->create([
            'test_name' => 'Available Phosphorus',
            'test_code' => 'NUT-002',
            'test_category' => 'Nutrient',
            'parameter' => 'Phosphorus Content',
            'unit' => 'ppm',
            'method' => 'Bray-1',
            'price' => 70.00,
        ]);
        $test4->result()->create([
            'result_value' => 15.3,
            'status' => 'entered',
            'entered_by' => $technician->id,
            'entered_at' => now()->subHours(1),
        ]);

        // Work Order 3 – Approved
        $wo3 = WorkOrder::create([
            'wo_number' => WorkOrder::generateWONumber(),
            'client_name' => 'Clean Water NGO',
            'client_email' => 'test@cleanwater.org',
            'client_phone' => '011223344',
            'client_organization' => 'Clean Water Initiative',
            'project_description' => 'Wastewater effluent analysis',
            'order_date' => Carbon::now()->subDays(15),
            'expected_completion_date' => Carbon::now()->addDays(2),
            'priority' => 'high',
            'status' => 'completed',
            'created_by' => $admin->id,
        ]);

        $sample3 = $wo3->samples()->create([
            'sample_code' => Sample::generateSampleCode($wo3->id),
            'sample_type' => 'Wastewater',
            'sample_matrix' => 'Industrial Effluent',
            'sampling_location' => 'Textile Factory',
            'sampling_date' => Carbon::now()->subDays(14),
            'sampling_time' => '09:00:00',
            'sampling_method' => 'Grab Sampling',
            'container_type' => 'Glass Bottle',
            'preservation_method' => 'Refrigeration',
            'received_date' => Carbon::now()->subDays(13),
            'received_time' => '10:30:00',
            'received_by' => 'Lab Staff',
            'sample_description' => 'Effluent before treatment',
            'sample_condition' => 'Good',
            'sample_quantity' => 1.5,
            'quantity_unit' => 'L',
            'status' => 'approved',
        ]);

        $test5 = $sample3->tests()->create([
            'test_name' => 'BOD',
            'test_code' => 'ORG-001',
            'test_category' => 'Organic',
            'parameter' => 'Biochemical Oxygen Demand',
            'unit' => 'mg/L',
            'method' => '5-Day BOD',
            'price' => 90.00,
        ]);
        $test5->result()->create([
            'result_value' => 45.0,
            'status' => 'approved',
            'entered_by' => $technician->id,
            'entered_at' => now()->subDays(4),
            'approved_by' => $approver->id,
            'approved_at' => now()->subDays(2),
        ]);

        $test6 = $sample3->tests()->create([
            'test_name' => 'COD',
            'test_code' => 'ORG-002',
            'test_category' => 'Organic',
            'parameter' => 'Chemical Oxygen Demand',
            'unit' => 'mg/L',
            'method' => 'Dichromate',
            'price' => 80.00,
        ]);
        $test6->result()->create([
            'result_value' => 120.0,
            'status' => 'approved',
            'entered_by' => $technician->id,
            'entered_at' => now()->subDays(4),
            'approved_by' => $approver->id,
            'approved_at' => now()->subDays(2),
        ]);

        // Invoice for WO3
        $wo3->invoice()->create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'issue_date' => now()->subDays(13),
            'due_date' => now()->addDays(17),
            'subtotal' => 170.00,
            'tax' => 17.00,
            'total' => 187.00,
            'pdf_path' => 'invoices/INV-20250103-3.pdf',
        ]);
        $wo3->update(['invoice_number' => 'INV-20250103-3']);

        // Report for WO3
        $wo3->report()->create([
            'report_number' => Report::generateReportNumber(),
            'report_date' => now()->subDays(1),
            'executive_summary' => 'Water quality meets standards',
            'methodology' => 'Standard APHA methods',
            'conclusions' => 'All parameters within limits',
            'recommendations' => 'Continue monitoring',
            'generated_by' => $admin->id,
            'pdf_path' => 'reports/report-' . $wo3->id . '.pdf',
        ]);
    }
}