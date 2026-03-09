<?php

namespace Modules\Service\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Modules\Service\app\Models\Service;
use Modules\Service\app\Models\ServiceImage;
use Modules\Service\app\Models\ServiceVideo;

class ServiceUtilityController extends Controller {
    use RedirectHelperTrait;
    public function showGallery($id) {
        $gallery = ServiceImage::where('service_id', $id)->get();
        $service = Service::findOrFail($id);
        if(! $service) abort( 404 );

        return view('service::utilities.gallery', compact('service', 'gallery'));
    }

    public function updateGallery(Request $request, $id) {
        checkAdminHasPermissionAndThrowException('service.update');
        foreach ($request->file as $image) {
            $serviceImage = new ServiceImage();
            $serviceImage->service_id = $id;

            $large_name = uploadAndOptimizeImage(
                file: $image,
                resize: [730, 486]
            );

            $small_name = uploadAndOptimizeImage(
                file: $image,
                resize: [175, 116]
            );
            $serviceImage->large_image = $large_name;
            $serviceImage->small_image = $small_name;
            $serviceImage->save();
        }
        if ($serviceImage) {
            return response()->json([
                'message' => __('Images Saved Successfully'),
                'url'     => route('admin.service.gallery', $id),
            ]);
        } else {
            return $this->redirectWithMessage(RedirectType::ERROR->value);
        }
    }

    public function deleteGallery($id) {
        checkAdminHasPermissionAndThrowException('service.delete');
        $serviceImage = ServiceImage::findOrFail($id);

        if ($serviceImage->large_image && !str($serviceImage->large_image)->contains('website/images')) {
            if (@File::exists(public_path($serviceImage->large_image))) {
                @unlink(public_path($serviceImage->large_image));
            }
        }
        if ($serviceImage->small_image && !str($serviceImage->small_image)->contains('website/images')) {
            if (@File::exists(public_path($serviceImage->small_image))) {
                @unlink(public_path($serviceImage->small_image));
            }
        }

        $serviceImage->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value);
    }


    public function showVideos($id)
    {
        $videos = ServiceVideo::where('service_id', $id)->get();
        $service = Service::findOrFail($id);
        if(! $service) abort( 404 );

        return view('service::utilities.videos', compact('service', 'videos'));
    }

    public function updateVideos(Request $request, $id)
    {
        checkAdminHasPermissionAndThrowException('service.update');
        $request->validate(['link' => 'required|string|max:190'], [
            'link.required' => __('The video link field is required.'),
            'link.string' => __('The video link field must be a string.'),
            'link.max' => __('The video link field must not exceed 190 characters.'),
        ]);

        $url = $request->link;

        if (preg_match("/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w\-]{11})(?:\S+)?$/", $url, $matches)) {
            $video = new ServiceVideo();
            $video->service_id = $request->id;
            $video->link = $request->link;
            $video->code = $matches[1];
            $video->save();

            return $this->redirectWithMessage(RedirectType::UPDATE->value);
        }
        $notification = __('Invalid Link!');
        $notification = ['message' => $notification, 'alert-type' => 'error'];

        return $this->redirectWithMessage(RedirectType::ERROR->value, '', [], $notification);
    }

    public function deleteVideos($id)
    {
        checkAdminHasPermissionAndThrowException('service.delete');
        ServiceVideo::findOrFail($id)?->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value);
    }
}
