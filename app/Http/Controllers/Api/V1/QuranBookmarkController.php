<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\QuranBookmark;
use Illuminate\Http\Request;

class QuranBookmarkController extends Controller
{
    public function addBookmark(Request $request)
    {
        $bookmark = new QuranBookmark();
        $bookmark->customer_id = $request->customer_id;
        $bookmark->bookmark_title = $request->bookmark_title;
        $bookmark->surah_name = $request->surah_name;
        $bookmark->surah_no = $request->surah_no;
        $bookmark->scroll_offset = $request->scroll_offset;
        $save = $bookmark->save();
        if ($save) {
            return response()->json(
                [
                    'success' => true,

                    'message' => 'Bookmark Added successfully.'
                ],
                200
            );
        }
    }
    public function viewBookmark($id)
    {
        $bookmark = QuranBookmark::where('customer_id', $id)->get();
        return $bookmark;
        // return response()->json($bookmark);

    }
    public function removeBookmark($id)
    {
        $bookmark = QuranBookmark::findOrFail($id);
        $del = $bookmark->delete();
        if ($del) {
            return response()->json(
                [
                    'success' => true,

                    'message' => 'Bookmark removed.'
                ],
                200
            );
        }
    }
}
