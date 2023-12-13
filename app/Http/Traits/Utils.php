<?php

namespace App\Http\Traits;

use App\Models\Role;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Str;

trait Utils
{
    /**
     * Timestamps
     * Methods:
     * - createdAt()
     * - updatedAt()
     * @return string
     */

    public function createdAt($created_field)
    {
        return Carbon::parse($created_field)->format('M d, Y');
    }

    /**
     * @return string
     */
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
     * @return string
     */
    public static function generatedTicketNumber()
    {
        return self::alphaNum() . "-" . self::currentMonth();
    }

    /**
     * @return string
     */
    private static function currentMonth()
    {
        return date('m');
    }

    /**
     * @throws Exception
     * @return string
     */
    private static function alphaNum()
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
     * @return string
     */
    public static function slugify(string $word)
    {
        return mt_rand(10000, 99999) . "-" . Str::slug($word);
    }

    /**
     * Multi Select
     * Method:
     * - getSelectedValue()
     * @return array
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
     * @return string
     */
    public function fileDirByUserType()
    {
        //        $staffRolePath = '';
        return match (true) {
            Auth::user()->hasRole(Role::SYSTEM_ADMIN)             => 'system_admin',
            Auth::user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN) => 'service_department_admin',
            Auth::user()->hasRole(Role::APPROVER)                 => 'approver',
            Auth::user()->hasRole(Role::AGENT)                    => 'agent',
            Auth::user()->hasRole(Role::USER)                     => 'requester',
            default                                               => 'guest',
        };
    }

    /**
     * Generate a new name for the uploaded user profile picture using its full name.
     * @return string
     * */
    public function generateNewProfilePictureName($picture)
    {
        return time() . "_" . Str::slug(auth()->user()->profile->getFullName()) . "." . $picture->getClientOriginalExtension();
    }
}
