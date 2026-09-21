<?php

namespace App\Imports;

use App\Models\DepoGuest;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;


class DepoGuestImport implements ToModel, WithHeadingRow, WithValidation
{
    public int $createdCount = 0;
    public int $skippedCount = 0;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $identity = $row['identity'] ?? null;
        // Skip rows where identity already exists
        $existingRecord = $identity
            ? DepoGuest::where('depo_identity', $identity)->first()
            : null;

        if ($existingRecord) {
            $this->skippedCount++;
            return null; // Skip this row
        }

        $this->createdCount++;

        return new DepoGuest([
            'depo_guest_name'  => $row['name'],
            'depo_guest_rank' => $row['rank'] ?? null,
            'depo_guest_designation' => $row['designation'] ?? null,
            'depo_guest_contact' => $row['contact'] ?? null,
            'depo_guest_service' => $row['service'] ?? null,
            'depo_identity' => $identity ?? null,
            'depo_guest_email' => $row['email'] ?? null,
            'badge_type' => $row['badge_type'] ?? null,
            'depo_uid' => $row['host_uid'],
            'depo_address' => $row['address'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.name' => ['required', 'string', 'max:255'],
            '*.rank' => ['nullable'],
            '*.designation' => ['nullable', 'string', 'max:255'],
            '*.contact' => ['nullable', 'max:50'],
            '*.service' => ['nullable', 'string', 'max:255'],
            '*.identity' => ['nullable', 'distinct', 'max:100'],
            '*.email' => ['nullable', 'email', 'max:255'],
            '*.badge_type' => ['nullable', 'string', 'max:100'],
            '*.host_uid' => ['required', 'string', 'max:255'],
            '*.address' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            '*.name.required' => 'Name is required.',
            '*.designation.required' => 'Designation is required.',
            '*.identity.required' => 'Identity is required.',
            '*.identity.distinct' => 'Identity is duplicated in the uploaded file.',
            '*.email.email' => 'Email must be a valid email address.',
            '*.host_uid.required' => 'Host UID is required.',
        ];
    }
}
