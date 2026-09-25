<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

class NikVerificationService
{
    /**
     * Kukar District Administrative Code Mapping
     */
    public const KUKAR_DISTRICTS = [
        '640201' => 'Muara Jawa',
        '640202' => 'Samboja',
        '640203' => 'Sangasanga',
        '640204' => 'Loa Janan',
        '640205' => 'Loa Kulu',
        '640206' => 'Tenggarong',
        '640207' => 'Sebulu',
        '640208' => 'Kota Bangun',
        '640209' => 'Kenohan',
        '640210' => 'Kembang Janggut',
        '640211' => 'Tabang',
        '640212' => 'Anggana',
        '640213' => 'Muara Badak',
        '640214' => 'Marang Kayu',
        '640215' => 'Muara Kaman',
        '640216' => 'Tenggarong Seberang',
        '640217' => 'Muara Wis',
        '640218' => 'Muara Muntai',
        '640219' => 'Samboja Barat',
        '640220' => 'Kota Bangun Darat',
    ];

    /**
     * Validate NIK format, Kukar region, and birth date consistency
     *
     * @param string $nik 16-digit NIK string
     * @param string|Carbon $dob Date of birth (Y-m-d)
     * @return array Validation results and metadata
     */
    public function analyze(string $nik, $dob): array
    {
        $nik = trim($nik);
        $dobCarbon = $dob instanceof Carbon ? $dob : Carbon::parse($dob);

        // 1. Basic format validation: exactly 16 numeric digits
        if (!preg_match('/^[0-9]{16}$/', $nik)) {
            return [
                'is_valid_format' => false,
                'nik_region_valid' => false,
                'birth_date_valid' => false,
                'district_name' => null,
                'gender' => null,
                'extracted_dob' => null,
                'error' => 'NIK harus berupa 16 digit angka yang valid.',
            ];
        }

        // 2. Kukar Region Check (6402xx)
        $districtCode = substr($nik, 0, 6);
        $nikRegionValid = array_key_exists($districtCode, self::KUKAR_DISTRICTS);
        $districtName = self::KUKAR_DISTRICTS[$districtCode] ?? null;

        // If prefix is not Kukar, allow valid Indonesian region but flag region validity
        $provKabCode = substr($nik, 0, 4);
        $isKukarRegency = ($provKabCode === '6402');

        // 3. Birth date parsing from NIK
        // Digits: 7-8 (Day), 9-10 (Month), 11-12 (Year)
        $rawDay = (int) substr($nik, 6, 2);
        $rawMonth = (int) substr($nik, 8, 2);
        $rawYear = (int) substr($nik, 10, 2);

        $isFemale = $rawDay > 40;
        $day = $isFemale ? ($rawDay - 40) : $rawDay;
        $gender = $isFemale ? 'Perempuan' : 'Laki-laki';

        // Check if extracted day and month are valid calendar units
        $birthDateValid = false;
        if ($day >= 1 && $day <= 31 && $rawMonth >= 1 && $rawMonth <= 12) {
            $inputDay = (int) $dobCarbon->format('d');
            $inputMonth = (int) $dobCarbon->format('m');
            $inputYearLastTwo = (int) $dobCarbon->format('y');

            if ($day === $inputDay && $rawMonth === $inputMonth && $rawYear === $inputYearLastTwo) {
                $birthDateValid = true;
            }
        }

        $encrypted = Crypt::encryptString($nik);
        $hash = hash_hmac('sha256', $nik, config('app.key'));

        return [
            'is_valid_format' => true,
            'is_kukar' => $isKukarRegency,
            'nik_region_valid' => $nikRegionValid || $isKukarRegency,
            'birth_date_valid' => $birthDateValid,
            'district_code' => $districtCode,
            'district_name' => $districtName ?: 'Kutai Kartanegara',
            'gender' => $gender,
            'nik_encrypted' => $encrypted,
            'nik_hash' => $hash,
            'masked_nik' => substr($nik, 0, 6) . '******' . substr($nik, -4),
            'error' => !$birthDateValid ? 'Data tanggal lahir tidak sesuai dengan struktur NIK.' : null,
        ];
    }

    /**
     * Compute blind lookup hash
     */
    public function hash(string $nik): string
    {
        return hash_hmac('sha256', trim($nik), config('app.key'));
    }
}
