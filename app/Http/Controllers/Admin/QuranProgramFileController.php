<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurahReciteFileRequest;
use App\Models\QuranProgramFiles;
use App\Models\ReciteLanguage;
use App\Models\SurahRecitation;
use App\Models\SurahReciteFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuranProgramFileController extends Controller
{

    const moduleDirectory = 'admin.quran-program-lists.files.';

    public function create($quran): View
    {
        $data = [
            'quranId' => $quran,
        ];
        return view(self::moduleDirectory . 'create', $data);
    }

    public function store(Request $request): string
    {
        try {
            if ($request->hasFile('sub_title_file')){
                    $subTitleFile = $request->file('sub_title_file');
                    $subTitleFileNameToStore = 'surah_sub'.time() . '.' . $subTitleFile->getClientOriginalExtension();
                    if (!file_exists('uploads/quranProgram/srtFile')){
                        mkdir('uploads/quranProgram/srtFile', 0777, true);
                    }
                    $subTitleFile->move('uploads/quranProgram/srtFile', $subTitleFileNameToStore);
                }else {
                    $subTitleFileNameToStore = null;
                }

                $storeReciteFiles = QuranProgramFiles::create([
                    'quran_program_list_id' => $request->quran_program_list_id,
                    'translation' => $request->translation ?? null,
                    'transliteration' => $request->transliteration ?? null,
                    'sub_title_file' => $subTitleFileNameToStore,
                ]);

                if ($storeReciteFiles) {
                    alert()->success('Quran Program Files', 'Files Added Successfully');
                    return redirect()->route('admin.quran_program_lists.index');
                } else {
                    alert()->error('Quran Program Files', 'Failed To Attach');
                    return redirect()->route('admin.quran_program_lists.index');
                }

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function edit($id): View
    {
        $data = [
            'moduleName' => 'Quran Program File Edit',
            'quranProgramFile' => QuranProgramFiles::query()->find($id),
        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    public function update(Request $request, $id)
    {
        $programFile=QuranProgramFiles::query()->find($id);

        if ($request->hasFile('sub_title_file')){
            $subTitleFile = $request->file('sub_title_file');
            $subTitleFileNameToStore = 'surah_sub'.time() . '.' . $subTitleFile->getClientOriginalExtension();
            if (!file_exists('uploads/quranProgram/srtFile')){
                mkdir('uploads/quranProgram/srtFile', 0777, true);
            }
//            if (file_exists('uploads/quranProgram/srtFile'.$programFile->sub_title_file)){
//                unlink('uploads/quranProgram/srtFile'.$programFile->sub_title_file);
//            }
            $subTitleFile->move('uploads/quranProgram/srtFile', $subTitleFileNameToStore);
        }else {
            $subTitleFileNameToStore = $programFile->sub_title_file;
        }
        $updateFiles = $programFile->update([
            'quran_program_list_id' => $request->quran_program_list_id,
            'translation' => $request->translation ?? null,
            'transliteration' => $request->transliteration ?? null,
            'sub_title_file' => $subTitleFileNameToStore,
        ]);

        if ($updateFiles) {
            alert()->success('Quran Program Files', 'Files Added Successfully');
            return redirect()->route('admin.quran_program_lists.index');
        } else {
            alert()->error('Quran Program Files', 'Failed To Attach');
            return redirect()->route('admin.quran_program_lists.index');
        }
    }


}
