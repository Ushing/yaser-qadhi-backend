<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurahReciteFileRequest;
use App\Models\ReciteLanguage;
use App\Models\SurahRecitation;
use App\Models\SurahReciteFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecitationFileController extends Controller
{

    const moduleDirectory = 'admin.surah-recitations.files.';

    public function create($surah): View
    {
        $data = [
            'languages' => ReciteLanguage::query()->orderBy('id', 'asc')->get(),
            'surahId' => $surah,
        ];
        return view(self::moduleDirectory . 'create', $data);
    }

    public function store(SurahReciteFileRequest $request): string
    {
        try {
            $existance = SurahReciteFile::where('recite_language_id', $request->recite_language_id)->where('surah_recitation_id', $request->surah_recitation_id)->exists();
            if ($existance) {
                alert()->error('Surah Recite Files', 'File is already attached for this language');
                return redirect()->route('admin.surah_recitations.index');
            } else {
                if ($request->hasFile('sub_title_file')){
                    $subTitleFile = $request->file('sub_title_file');
                    $subTitleFileNameToStore = 'surah_sub'.time() . '.' . $subTitleFile->getClientOriginalExtension();
                    if (!file_exists('uploads/surah/srtFile')){
                        mkdir('uploads/surah/srtFile', 0777, true);
                    }
                    $subTitleFile->move('uploads/surah/srtFile', $subTitleFileNameToStore);
                }else {
                    $subTitleFileNameToStore = null;
                }

                $storeReciteFiles = SurahReciteFile::create([
                    'recite_language_id' => $request->recite_language_id,
                    'surah_recitation_id' => $request->surah_recitation_id,
                    'translation' => $request->translation ?? null,
                    'transliteration' => $request->transliteration ?? null,
                    'sub_title_file' => $subTitleFileNameToStore,
                ]);

                if ($storeReciteFiles) {
                    alert()->success('Surah Recite Files', 'Files Added Successfully');
                    return redirect()->route('admin.surah_recitations.index');
                } else {
                    alert()->error('Surah Recite Files', 'Failed To Attach');
                    return redirect()->route('admin.surah_recitations.index');
                }
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function edit($id): View
    {
        $data = [
            'moduleName' => 'Surah Recitation File Edit',
            'reciteFile' => SurahReciteFile::query()->find($id),
            'languages' => ReciteLanguage::query()->orderBy('id', 'asc')->get(),
        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    public function update(Request $request, $id)
    {
         $reciteFile=SurahReciteFile::query()->find($id);

        if ($request->hasFile('sub_title_file')){
            $subTitleFile = $request->file('sub_title_file');
            $subTitleFileNameToStore = 'surah_sub'.time() . '.' . $subTitleFile->getClientOriginalExtension();
            if (!file_exists('uploads/surah/srtFile')){
                mkdir('uploads/surah/srtFile', 0777, true);
            }
//            if (file_exists('uploads/surah/srtFile'.$reciteFile->sub_title_file)){
//                unlink('uploads/surah/srtFile'.$reciteFile->sub_title_file);
//            }
            $subTitleFile->move('uploads/surah/srtFile', $subTitleFileNameToStore);
        }else {
            $subTitleFileNameToStore = $reciteFile->sub_title_file;
        }
        $updateReciteFiles = $reciteFile->update([
            'recite_language_id' => $request->recite_language_id,
            'surah_recitation_id' => $request->surah_recitation_id,
            'translation' => $request->translation ?? null,
            'transliteration' => $request->transliteration ?? null,
            'sub_title_file' => $subTitleFileNameToStore,
        ]);

        if ($updateReciteFiles) {
            alert()->success('Surah Recite Files', 'Files Added Successfully');
            return redirect()->route('admin.surah_recitations.index');
        } else {
            alert()->error('Surah Recite Files', 'Failed To Attach');
            return redirect()->route('admin.surah_recitations.index');
        }
    }


}
