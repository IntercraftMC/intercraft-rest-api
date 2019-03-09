<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilesystemRegisterRequest;
use App\Models\Filesystem;
use Illuminate\Http\Request;

class FilesystemController extends Controller
{
    public function filesystems()
    {

    }

    /**
     * Register filesystems for the authenticated user
     *
     * @param [App\Http\Request\FilesystemRegisterRequest] $request
     */
    public function register(FilesystemRegisterRequest $request)
    {
        $fsCreative = env("MC_CREATIVE_FS");
        $fsSurvival = env("MC_SURVIVAL_FS");

        $alreadyRegistered = [];
        $notFound          = [];
        foreach ($request->filesystem as $fs) {
            $result = Null;
            if (file_exists(path_join($fsCreative, $fs))) {
                $result = $this->registerFilesystem(True, $fs);
            }
            elseif (file_exists(path_join($fsSurvival, $fs))) {
                $result = $this->registerFilesystem(False, $fs);
            }
            if ($result === Null) {
                $notFound[] = $fs;
            } elseif ($result == False) {
                $alreadyRegistered[] = $fs;
            }
        }
        $response = [];
        if (count($alreadyRegistered) > 0 || count($notFound) > 0) {
            $response["errors"] = [];
            if (count($notFound) > 0) {
                $response["errors"]["not_found"] = $notFound;
            }
            if (count($alreadyRegistered) > 0) {
                $response["errors"]["already_registered"] = $alreadyRegistered;
            }
        }
        return response()->json($response);
    }

    /**
     * Unregister filesystems for the authenticated user
     *
     * @param [App\Http\Request\FilesystemRegisterRequest] $request
     */
    public function unregister(FilesystemRegisterRequest $request)
    {
        foreach ($request->filesystem as $fs) {
            auth()->user()->filesystems()->where("uuid", $fs)->delete();
        }
        return response()->json([]);
    }

    /**
     * Register a filesystem for the current authenticated user
     *
     * @param [boolean] $creative
     * @return boolean
     */
    protected function registerFilesystem($creative, $uuid)
    {
        if (Filesystem::where("is_creative", $creative)->where("uuid", $uuid)->count() > 0) {
            return False;
        }
        $fs = new Filesystem();
        $fs->fill(["is_creative" => $creative, "uuid" => $uuid])
           ->user()->associate(auth()->user())
           ->save();
        return True;
    }
}
