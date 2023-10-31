<?php

namespace App\Http\Traits;

use App\Models\Role;
use Carbon\Carbon;
use Exception;
use Str;

trait Utils
{
    /**
     * Timestamps
     * Methods:
     * - createdAt()
     * - updatedAt()
     */

    public function createdAt($created_field): string
    {
        return Carbon::parse($created_field)->format('M d, Y');
    }

    public function updatedAt($created_field, $updated_field): string
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
    public static function generatedTicketNumber(): string
    {
        return self::alphaNum() . "-" . self::currentMonth();
    }

    private static function currentMonth(): string
    {
        return date('m');
    }

    /**
     * @throws Exception
     */
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

        throw new Exception('Unable to generate a unique value after ' . $maxAttempts . ' attempts.');
    }

    /**
     * Slug Generator
     * Method:
     * - slugify()
     */
    public static function slugify(string $word): string
    {
        return mt_rand(10000, 99999) . "-" . Str::slug($word);
    }

    /**
     * Multi Select
     * Method:
     * - getSelectedValue()
     */
    public function getSelectedValue($field): array
    {
        return array_map('intval', explode(',', $field[0]));
    }

    /**
     * File Upload Dir.
     * Method:
     * - fileDirByUserType()
     * - generateNewProfilePictureName()
     */
    public function fileDirByUserType(): string
    {
//        $staffRolePath = '';
        return match (auth()->user()->role_id) {
            Role::SYSTEM_ADMIN => 'system_admin',
            Role::SERVICE_DEPARTMENT_ADMIN => 'service_department_admin',
            Role::APPROVER => 'approver',
            Role::AGENT => 'agent',
            Role::USER => 'requester',
            default => 'guest',
        };
    }

    public function generateNewProfilePictureName($picture): string
    {
        return time() . "_" . Str::slug(auth()->user()->profile->getFullName()) . "." . $picture->getClientOriginalExtension();
    }
}
