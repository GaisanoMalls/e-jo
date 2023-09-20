<?php

namespace App\Http\Livewire\Requester\Account;

use App\Http\Requests\Requester\UpdateProfileRequest;
use App\Http\Traits\Utils;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads, Utils;

    public $first_name, $middle_name, $last_name, $suffix, $email, $mobile_number, $picture;
    public int $imageUpload = 0;

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

    protected function rules()
    {
        return (new UpdateProfileRequest())->rules();
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function resetFileField()
    {
        $this->imageUpload++;
    }

    public function saveProfile()
    {
        $validatedData = $this->validate();

        try {
            DB::transaction(function () use ($validatedData) {
                $user = Auth::user();
                $pictureName = $validatedData['picture'] ? $this->generateNewProfilePictureName($validatedData['picture']) : null;

                $user->update(['email' => $this->email]);
                $user->profile->update([
                    'first_name' => $validatedData['first_name'],
                    'middle_name' => $validatedData['middle_name'],
                    'last_name' => $validatedData['last_name'],
                    'suffix' => $validatedData['suffix'],
                    'mobile_number' => $validatedData['mobile_number'],
                    'picture' => $validatedData['picture'] != null
                        ? (
                            $user->profile->picture != null && Storage::exists($user->profile->picture)
                            ? (
                                Storage::delete($user->profile->picture)
                                ? (Storage::putFileAs("public/profile_picture/" . $this->fileDirByUserType(), $validatedData['picture'], $pictureName))
                                : $validatedData['picture']
                            )
                            : (Storage::putFileAs("public/profile_picture/" . $this->fileDirByUserType(), $validatedData['picture'], $pictureName))
                        )
                        : $user->profile->picture
                ]);

                ($user->wasChanged('email') || $user->profile->wasChanged([
                    'first_name',
                    'middle_name',
                    'last_name',
                    'suffix',
                    'mobile_number',
                    'picture'
                ]))
                    ? flash()->addSuccess('Profile successfully updated.')
                    : flash()->addInfo('No changes have been made in your profile.');

                $this->resetValidation();
                $this->emit('loadProfilePreview');
                $this->emit('loadNavProfilePic');

            });
        } catch (\Exception $e) {
            flash()->addError('Something went wrong while updating your profile.');
        }
    }

    public function render()
    {
        return view('livewire.requester.account.profile');
    }
}