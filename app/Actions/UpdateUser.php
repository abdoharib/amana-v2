<?php

namespace App\Actions;

class UpdateUser
{
    protected $fileHelper;
    public function __construct(FileHelper $fileHelper)
    {
        $this->fileHelper = $fileHelper;
    }
    public function execute($user, $request)
    {

        // Delete the old image if a new one is being uploaded
        $request->hasFile('image') && $this->fileHelper->deleteFile($user->image);

        // Set the new file path if an image is uploaded, or retain the old path otherwise
        $filePath = $request->hasFile('image')
            ? $this->fileHelper->execute($request->file('image'), 'guardians')
            : $user->image;


        // Update the Guardian model with both the new request data and the processed image path
        $user->update(array_merge($request->all(), ['image' => $filePath]));

        $user->update($request->all());
        return $user;
    }
}
