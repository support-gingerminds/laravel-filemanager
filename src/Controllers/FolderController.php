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
                    $tree = json_decode(json_encode($this->helper->getSubfoldersTree($path->path('working_dir'), $this->lfm)));

                    return (object) [
                        'name' => trans('laravel-filemanager::lfm.title-' . $type),
                        'url' => $path->path('working_dir'),
                        'children' => $tree,
                        'has_next' => ! ($type == end($folder_types)),
                    ];
                }, $folder_types),
            ])
            ->withHelper($this->helper);
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
