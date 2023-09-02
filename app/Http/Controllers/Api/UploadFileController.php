<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UploadImageRequest;
use Exception;
use Illuminate\Support\Facades\Validator;

class UploadFileController extends Controller
{

    public function __construct()
    {
    }

    public function storeImage(UploadImageRequest $req)
    {
        try {
            $files = [];
            $size = config('image.size');
            $mimes = config('image.mines');
            $path = 'storage/temps/files/images/' . $req->type;
            switch ($req->type) {
                case 'book_delivery':
                case 'category':
                case 'product':
                case 'staff':
                case 'shipper':
                    if (is_array($req->file('files'))) {
                        foreach ($req->file('files') as $index => $image) {
                            $validator = Validator::make($req->file('files'), [
                                $index => 'image|mimes:' . $mimes . '|max:' . $size
                            ]);
                            if ($validator->fails()) {
                                throw new Exception($validator->errors()->first());
                            }
                            $files[] = $req->file('files')[$index]->store('public/temps/files/images/' . $req->type);
                        }
                    } else {
                        $validator = Validator::make($req->file('files'), [
                            "0" => 'image|mimes:' . $mimes . '|max:' . $size
                        ]);
                        if ($validator->fails()) {
                            throw new Exception($validator->errors()->first());
                        }
                        $files[] = $req->file('files')->store('public/temps/files/images/' . $req->type);
                    }

                    break;
                case 'member':
                    if (is_array($req->file('files'))) {
                        foreach ($req->file('files') as $key => $image) {
                            $validator = Validator::make($req->file('files'), [
                                $key => 'image|mimes:' . $mimes . '|max:' . $size
                            ]);
                            if ($validator->fails()) {
                                throw new Exception($validator->errors()->first());
                            }

                            $files[] = $req->file('files')[$key]->store('public/temps/files/images/' . $req->type);
                        }
                    } else {
                        $files[] = $req->file('files')->store('public/temps/files/images/' . $req->type);
                    }
                    break;
                case 'banner':
                    if (is_array($req->file('files'))) {
                        foreach ($req->file('files') as $index => $image) {
                            $validator = Validator::make($req->file('files'), [
                                $index => 'image|mimes:' . $mimes . '|max:' . $size
                            ]);
                            if ($validator->fails()) {
                                throw new Exception($validator->errors()->first());
                            }
                            $files[] = $req->file('files')[$index]->store('public/temps/files/images/' . $req->type);
                        }
                    } else {
                        $validator = Validator::make($req->file('files'), [
                            "0" => 'image|mimes:' . $mimes . '|max:' . $size
                        ]);
                        if ($validator->fails()) {
                            throw new Exception($validator->errors()->first());
                        }
                        $files[] = $req->file('files')->store('public/temps/files/images/' . $req->type);
                    }

                    break;
                default:
                    return apiError('Không hỗ trợ kiêu này');
            }
            return apiOk($files);
        } catch (\Exception $e) {
            return apiError($e->getMessage());
        }
    }

    public function storeVideo(Request $req)
    {

        switch ($req->type) {
            case 'staff':
                $file = $req->file('files')->store('public/temps/files/videos' . $req->type);

                break;
            case 'member':
                $file = $req->file('files')->store('public/temps/files/videos' . $req->type);
                break;
            default:
                return apiError('Không hỗ trợ kiêu này');
        }
        $getID3 = new \getID3;
        $file_path = storage_path('app/' . $file);
        $video_file = $getID3->analyze($file_path);

        // Get the duration in seconds, e.g.: 277 (seconds)
        $duration_seconds = $video_file['playtime_seconds'];
        $video_max_duration = config('image.video_duration');
        if ($duration_seconds > config('image.video_duration')) {
            unlink($file);
            return apiError('Thời lượng video không quá ' . $video_max_duration . 's');
        }
        return apiOk($file);
    }
    public function storeMedia(Request $req)
    {
        try {
            $files = [];
            if (is_array($req->file('files'))) {
                foreach ($req->file('files') as $index => $file) {
                    $extension = $file->getClientOriginalExtension();
                    $files[] = $req->file('files')[$index]->store('public/temps/files/medias/' . $extension);
                }
            } else {
                $file = $req->file('files');
                $extension = $file->getClientOriginalExtension();
                $files[] = $req->file('files')->store('public/temps/files/medias/' . $extension);
            }
            return apiOk($files);
        } catch (\Exception $e) {
            return apiError($e->getMessage());
        }
    }
    // public function removeImage($id,$type){
    //     $user = auth('api')->user();
    //     if($type == "product")
    //     {
    //        $item =  Media::find($id);
    //        if($item && $item->model_type == Product::class)
    //           if($item->model && $item->model->shop_id && $user->getAccountTypeByKey('shop')->id)
    //           {
    //             $item->model->deleteMedia($item->id);
    //             return apiOk("Xoá thành công");
    //           }

    //       return apiError("Không tìm thấy dữ liệu");
    //     }
    //     if($type == "shop")
    //     {
    //        $item =  Media::find($id);
    //        if($item && $item->model_type == Shop::class)
    //           if($item->model && $item->model_id && $user->getAccountTypeByKey('shop')->id)
    //           {
    //             $item->model->deleteMedia($item->id);
    //             return apiOk("Xoá thành công");
    //           }

    //       return apiError("Không tìm thấy dữ liệu");
    //     }
    //     return apiError("Chưa hổ trợ type này");
    // }

}
