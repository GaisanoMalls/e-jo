<?php

namespace App\Http\Livewire\Requester\Account;

use App\Http\Requests\Requester\UpdateProfileRequest;
use App\Http\Traits\Utils;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads, Utils;

    public $imageUpload = 0; // This property is required to re-render the field resulting in "No File Chosen".
    public $first_name;
    public $middle_name;
    public $last_name;
    public $suffix;
    public $email;
    public $mobile_number;
    public $picture;


    public function mount()
    {
        $authUser = Auth::user();

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

    /** Reset the file input field after form submission. */
    public function resetFileField()
    {
        $this->picture = null;
        $this->imageUpload++;
    }

    /** Perform livewire events upon form submission. */
    private function actionOnSubmit()
    {
        $this->resetValidation();
        $this->emit('loadProfilePreview');
        $this->emit('loadNavProfilePic');
    }

    public function saveProfile()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $user = Auth::user();
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
                                ? (Storage::putFileAs("public/profile_picture/" . $this->fileDirByUserType(), $this->picture, $pictureName))
                                : $this->picture
                            )
                            : (Storage::putFileAs("public/profile_picture/" . $this->fileDirByUserType(), $this->picture, $pictureName))
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
                    ? flash()->addSuccess('Profile successfully updated.')
                    : flash()->addInfo('No changes have been made in your profile.');
            });

            $this->actionOnSubmit();

        } catch (Exception $e) {
            dump($e->getMessage());
            flash()->addError('Something went wrong while updating your profile.');
        }
    }

    public function render()
    {
        return view('livewire.requester.account.profile');
    }
}
