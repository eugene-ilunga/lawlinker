<?php

namespace Modules\Lawyer\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Modules\Lawyer\app\Models\Department;
use Modules\Lawyer\app\Models\DepartmentImage;
use Modules\Lawyer\app\Models\DepartmentVideo;

class DepartmentUtilityController extends Controller {
    use RedirectHelperTrait;
    public function showGallery($id) {
        $gallery = DepartmentImage::where('department_id', $id)->get();
        $department = Department::findOrFail($id);
        if (!$department) {
            abort(404);
        }

        return view('lawyer::department.utilities.gallery', compact('department', 'gallery'));
    }

    public function updateGallery(Request $request, $id) {
        checkAdminHasPermissionAndThrowException('department.update');
        foreach ($request->file as $image) {
            $departmentImage = new DepartmentImage();
            $departmentImage->department_id = $id;

            $large_name = uploadAndOptimizeImage(
                file: $image,
                resize: [730, 486]
            );

            $small_name = uploadAndOptimizeImage(
                file: $image,
                resize: [175, 116]
            );
            $departmentImage->large_image = $large_name;
            $departmentImage->small_image = $small_name;
            $departmentImage->save();
        }
        if ($departmentImage) {
            return response()->json([
                'message' => __('Images Saved Successfully'),
                'url'     => route('admin.department.gallery', $id),
            ]);
        } else {
            return $this->redirectWithMessage(RedirectType::ERROR->value);
        }
    }

    public function deleteGallery($id) {
        checkAdminHasPermissionAndThrowException('department.delete');
        $departmentImage = DepartmentImage::findOrFail($id);

        if ($departmentImage->large_image && !str($departmentImage->large_image)->contains('website/images')) {
            if (@File::exists(public_path($departmentImage->large_image))) {
                @unlink(public_path($departmentImage->large_image));
            }
        }
        if ($departmentImage->small_image && !str($departmentImage->small_image)->contains('website/images')) {
            if (@File::exists(public_path($departmentImage->small_image))) {
                @unlink(public_path($departmentImage->small_image));
            }
        }

        $departmentImage->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value);
    }

    public function showVideos($id) {
        $videos = DepartmentVideo::where('department_id', $id)->get();
        $department = Department::findOrFail($id);
        if (!$department) {
            abort(404);
        }

        return view('lawyer::department.utilities.videos', compact('department', 'videos'));
    }

    public function updateVideos(Request $request, $id) {
        checkAdminHasPermissionAndThrowException('department.update');
        $request->validate(['link'=>'required|string|max:190'], [
            'link.required'      => __('The video link field is required.'),
            'link.string'        => __('The video link field must be a string.'),
            'link.max'           => __('The video link field must not exceed 190 characters.')
        ]);

        $url = $request->link;

        if (preg_match("/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w\-]{11})(?:\S+)?$/", $url, $matches)) {
            $video = new DepartmentVideo();
            $video->department_id = $request->id;
            $video->link = $request->link;
            $video->code = $matches[1];
            $video->save();

            return $this->redirectWithMessage(RedirectType::UPDATE->value);
        }
        $notification = __('Invalid Link!');
        $notification = ['message' => $notification, 'alert-type' => 'error'];

        return $this->redirectWithMessage(RedirectType::ERROR->value, '', [], $notification);
    }

    public function deleteVideos($id) {
        checkAdminHasPermissionAndThrowException('department.delete');
        DepartmentVideo::findOrFail($id)?->delete();
        return $this->redirectWithMessage(RedirectType::DELETE->value);
    }
}
