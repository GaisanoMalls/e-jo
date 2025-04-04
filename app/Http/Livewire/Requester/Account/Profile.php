<?php

namespace App\Http\Livewire\Requester\Account;

use App\Http\Requests\Requester\UpdateProfileRequest;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\Utils;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads, Utils;

    public int $imageUpload = 0; // This property is required to re-render the field resulting in "No File Chosen".
    public ?string $first_name = null;
    public ?string $middle_name = null;
    public ?string $last_name = null;
    public ?string $suffix = null;
    public ?string $email = null;
    public ?string $mobile_number = null;
    public ?string $picture = null;


    public function mount()
    {
        $authUser = auth()->user();

        $this->first_name = $authUser->profile->first_name;
        $this->middle_name = $authUser->profile->middle_name;
        $this->last_name = $authUser->profile->last_name;
        $this->suffix = $authUser->profile->suffix;
        $this->email = $authUser->email;
        $this->mobile_number = $authUser->profile->mobile_number;
    }

    public function rules()
    {
        return (new UpdateProfileRequest())->rules();
    }

    /**
     * Resets the profile picture upload field and increments the upload counter.
     * 
     * Performs two actions:
     * 1. Clears the current picture selection (resets the 'picture' property)
     * 2. Increments the imageUpload counter to force UI refresh of the file input
     *
     * This is typically used to:
     * - Cancel an upload selection
     * - Reset the file input after processing
     * - Force re-rendering of the file input component
     *
     * @return void
     */
    public function resetFileField()
    {
        $this->reset('picture');
        $this->imageUpload++;
    }

    /**
     * Handles post-submission cleanup and UI refresh for profile updates.
     * 
     * Performs three key actions:
     * 1. Resets validation errors
     * 2. Emits event to refresh profile preview
     * 3. Emits event to refresh navigation profile picture
     *
     * @return void
     * @fires loadProfilePreview To refresh profile preview component
     * @fires loadNavProfilePic To refresh navigation profile picture
     */
    private function actionOnSubmit()
    {
        $this->resetValidation();
        $this->emit('loadProfilePreview');
        $this->emit('loadNavProfilePic');
    }

    /**
     * Saves and updates user profile information.
     * 
     * Handles complete profile update workflow including:
     * 1. Validating input fields
     * 2. Processing updates in a database transaction:
     *    - Updates user email
     *    - Updates profile attributes (name, contact info)
     *    - Manages profile picture upload/storage:
     *      - Generates new filename for uploaded pictures
     *      - Handles existing picture deletion
     *      - Stores new pictures in appropriate directory
     * 3. Provides appropriate user feedback:
     *    - Success notification for changes
     *    - Info notification when no changes made
     * 4. Performs post-submission cleanup
     * 
     * @return void
     * @throws \Illuminate\Validation\ValidationException If validation fails
     * @throws \Exception On database or file operation errors (handled internally)
     *
     * @uses \Illuminate\Support\Facades\Storage For file operations
     * @uses \Illuminate\Support\Facades\DB For transaction safety
     * @uses \App\Models\AppErrorLog For error tracking
     * @uses noty() For user notifications
     *
     * @fires actionOnSubmit After successful processing
     */
    public function saveProfile()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $user = auth()->user();
                $pictureName = $this->picture ? $this->generateNewProfilePictureName($this->picture) : null;

                $user->update(['email' => $this->email]);
                $user->profile->update([
                    'first_name' => $this->first_name,
                    'middle_name' => $this->middle_name,
                    'last_name' => $this->last_name,
                    'suffix' => $this->suffix,
                    'mobile_number' => $this->mobile_number,
                    'picture' => $this->picture != null
                        ? (
                            $user->profile->picture != null && Storage::exists($user->profile->picture)
                            ? (
                                Storage::delete($user->profile->picture)
                                ? (Storage::putFileAs("public/profile_picture/{$this->fileDirByUserType()}", $this->picture, $pictureName))
                                : $this->picture
                            )
                            : (Storage::putFileAs("public/profile_picture/{$this->fileDirByUserType()}", $this->picture, $pictureName))
                        )
                        : $user->profile->picture
                ]);

                // Check if the user attributes has changes, then show a message.
                ($user->wasChanged('email') || $user->profile->wasChanged([
                    'first_name',
                    'middle_name',
                    'last_name',
                    'suffix',
                    'mobile_number',
                    'picture',
                ]))
                    ? noty()->addSuccess('Profile successfully updated.')
                    : noty()->addInfo('No changes have been made in your profile.');
            });

            $this->actionOnSubmit();

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.requester.account.profile');
    }
}
