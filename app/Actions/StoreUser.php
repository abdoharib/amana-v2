<?php

namespace App\Actions;

class storeUser
{
    protected $fileHelper;
    public function __construct(FileHelper $fileHelper)
    {
        $this->fileHelper = $fileHelper;
    }
    public function execute($type, $request)
    {
        if ($request->hasFile('image')) {
            $filePath = $this->fileHelper->execute($request->file('image'), 'guardians');
            $request->merge(['image' => $filePath]);
        }
        $user = $type::create($request->all());

        return $user;
    }
}
