<?php

namespace App\Http\Traits;

use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait Utils
{
    /**
     * Validate Login Credentials
     * Method: validateLoginCrendentials()
     */
    public function validateLoginCrendentials(Request $request, string $field1, string $field2)
    {
        $validator = Validator::make($request->all(), [
            $field1 => ['required', 'email'],
            $field2 => ['required']
        ]);

        return $validator->validate();
    }

    /**
     * Timestamps
     * Methods:
     * - createdAt()
     * - updatedAt()
     */

    public function createdAt($created_field)
    {
        return Carbon::parse($created_field)->format('M d, Y');
    }

    public function updatedAt($created_field, $updated_field)
    {
        $created_at = Carbon::parse($created_field)->isoFormat('MMM DD, YYYY HH:mm:ss');
        $updated_at = Carbon::parse($updated_field)->isoFormat('MMM DD, YYYY HH:mm:ss');

        return $updated_at === $created_at
            ? "----"
            : Carbon::parse($updated_field)->format('M d, Y | h:i A');
    }

    /**
     * Ticket Number Generator
     * Methods:
     * - generatedTicketNumber()
     * - currentMonth()
     * - alphaNum()
     */
    public static function generatedTicketNumber()
    {
        return self::alphaNum() . "-" . self::currentMonth();
    }

    private static function currentMonth(): string
    {
        return date('m');
    }

    private static function alphaNum(): string
    {
        $generatedValues = []; // Array to store previously generated values
        $maxAttempts = 10; // Maximum number of attempts to generate a unique value
        $letters = 'abcdefghijklmnopqrstuvwxyz';

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            $alpha = strtoupper(str_shuffle($letters));
            $num = mt_rand(100, 999);
            $value = $alpha[0] . $num;

            if (!in_array($value, $generatedValues)) {
                $generatedValues[] = $value;
                return $value;
            }
        }

        throw new \Exception('Unable to generate a unique value after ' . $maxAttempts . ' attempts.');
    }

    /**
     * Slug Generator
     * Method:
     * - slugify()
     */
    public static function slugify(string $word): string
    {
        return mt_rand(10000, 99999) . "-" . \Str::slug($word);
    }

    /**
     * Multi Select
     * Method:
     * - getSelectedValue()
     */
    public function getSelectedValue($field)
    {
        return array_map('intval', explode(',', $field[0]));
    }

    /**
     * File Upload Dir.
     * Method:
     * - fileDirByUserType()
     * - generateNewProfilePictureName()
     */
    public function fileDirByUserType()
    {
        $staffRolePath = '';
        switch (auth()->user()->role_id) {
            case Role::SYSTEM_ADMIN:
                $staffRolePath = 'system_admin';
                break;
            case Role::SERVICE_DEPARTMENT_ADMIN:
                $staffRolePath = 'service_department_admin';
                break;
            case Role::APPROVER:
                $staffRolePath = 'approver';
                break;
            case Role::AGENT:
                $staffRolePath = 'agent';
                break;
            case Role::USER:
                $staffRolePath = 'requester';
                break;
            default:
                $staffRolePath = 'guest';
        }

        return $staffRolePath;
    }

    public function generateNewProfilePictureName($picture)
    {
        return time() . "_" . \Str::slug(auth()->user()->profile->getFullName()) . "." . $picture->getClientOriginalExtension();
    }
}