<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestParameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'category', 'matrix', 'unit', 'method',
        'reference_method', 'detection_limit', 'quantification_limit',
        'accreditation', 'is_subcontracted', 'default_price', 'description'
    ];

    protected $casts = [
        'is_subcontracted' => 'boolean',
    ];

    /**
     * Generate the next test parameter code for a given matrix.
     * Format: MATRIX_ABBREVIATION-### (e.g., LIQ-001, SOL-001, GAS-001)
     */
    public static function generateCode($matrix)
    {
        $abbr = self::getMatrixAbbreviation($matrix);
        // Find the last code for this matrix
        $last = self::where('code', 'LIKE', $abbr . '-%')
                    ->orderBy('code', 'desc')
                    ->first();
        if ($last) {
            $number = intval(substr($last->code, -3)) + 1;
        } else {
            $number = 1;
        }
        return $abbr . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get abbreviation for a matrix name.
     */
    public static function getMatrixAbbreviation($matrix)
    {
        $map = [
            'Liquid' => 'LIQ',
            'Solid'  => 'SOL',
            'Gas'    => 'GAS',
            null     => 'GEN', // for universal tests
        ];
        return $map[$matrix] ?? 'GEN';
    }
}