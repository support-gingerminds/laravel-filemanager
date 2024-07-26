<?php

namespace UniSharp\LaravelFilemanager\Controllers;

use UniSharp\LaravelFilemanager\Events\FolderIsCreating;
use UniSharp\LaravelFilemanager\Events\FolderWasCreated;

class FolderController extends LfmController
{
    /**
     * Get list of folders as json to populate treeview.
     *
     * @return mixed
     */
    public function getFolders()
    {
        $folder_types = array_filter(['user', 'share'], function ($type) {
            return $this->helper->allowFolderType($type);
        });

        return view('laravel-filemanager::tree')
            ->with([
                'root_folders' => array_map(function ($type) use ($folder_types) {
                    $path = $this->lfm->dir($this->helper->getRootFolder($type));

                    $childrens = [];
                    $f = 0;
                    foreach($path->folders() as $folder) {
                        $childrens[$f] = [
                            'name' => $folder->name(),
                            'url' => $path->path('working_dir').'/'.$folder->name(),
                        ];
                        $child_path = $path->path('working_dir').'/'.$folder->name();
                        $child_path_folders = $this->lfm->dir($child_path)->folders();
                        if($child_path_folders){
                            $childrens[$f]['children'] = [];
                            $c = 0;
                            foreach($child_path_folders as $child_folder) {
                                $childrens[$f]['children'][$c] = [
                                    'name' => $child_folder->name(),
                                    'url' => $child_path.'/'.$child_folder->name(),
                                ];
                                $c++;
                            }
                        }
                        $f++;
                    }
                    $childrens = json_decode(json_encode($childrens));

                    return (object) [
                        'name' => trans('laravel-filemanager::lfm.title-' . $type),
                        'url' => $path->path('working_dir'),
                        'children' => $childrens,
                        'has_next' => ! ($type == end($folder_types)),
                    ];
                }, $folder_types),
            ]);
    }

    /**
     * Add a new folder.
     *
     * @return mixed
     */
    public function getAddfolder()
    {
        $folder_name = $this->helper->input('name');

        $new_path = $this->lfm->setName($folder_name)->path('absolute');

        event(new FolderIsCreating($new_path));

        try {
            if ($folder_name === null || $folder_name == '') {
                return $this->helper->error('folder-name');
            } elseif ($this->lfm->setName($folder_name)->exists()) {
                return $this->helper->error('folder-exist');
            } elseif (config('lfm.alphanumeric_directory') && preg_match('/[^\w-]/i', $folder_name)) {
                return $this->helper->error('folder-alnum');
            } else {
                $this->lfm->setName($folder_name)->createFolder();
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        event(new FolderWasCreated($new_path));

        return parent::$success_response;
    }
}
