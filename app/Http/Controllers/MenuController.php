<?php

namespace App\Http\Controllers;

use App\Http\Resources\FolderResource;
use App\Http\Resources\LinkResource;
use App\Http\Resources\PageResource;
use App\Models\Folder;
use App\Models\Link;
use App\Models\Page;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $array = $this->nav(0);

//        usort($array, function ($a, $b) {
//            if ($a["order"] === $b["order"]) {
//                return 0;
//            }
//            return ($a["order"] < $b["order"]) ? -1 : 1;
//        });
        return $array;
    }

    static function nav($id)
    {
        $navItems = [];

        $folders = Folder::query()->where('folder_id', $id)->get();

        foreach ($folders as $folder) {
            $navItems[] = new FolderResource($folder);
        }

        $pages = Page::query()->select('id', 'name', 'order')->where('folder_id', $id)->get();

        foreach ($pages as $page) {
            $navItems[] = new PageResource($page);
        }

        $links = Link::query()->where('folder_id', $id)->get();

        foreach ($links as $link) {
            $navItems[] = new LinkResource($link);
        }

        usort($navItems, function ($a, $b) {
            if ($a["order"] === $b["order"]) {
                return 0;
            }
            return ($a["order"] < $b["order"]) ? -1 : 1;
        });
        return $navItems;
    }

    public function update(Request $request)
    {
        $this->sortMenu($request->items, 0);
        return $this->sendResponse($this->index(), 'Successful');
    }

    static function sortMenu($items, $folder_id)
    {
        foreach ($items as $key => $item) {
            if ($item['type'] == 'folder') {

                Folder::query()->find($item['id'])->update([
                    'order' => $key,
                    'folder_id' => $folder_id,
                ]);

                MenuController::sortMenu($item['items'], $item['id']);
               //Сохраняем позицию папки и сортируем внутри папки
            }

            if ($item['type'] == 'link') {
                Link::query()->find($item['id'])->update([
                    'order' => $key,
                    'folder_id' => $folder_id,
                ]);
                //Сохраняем позицию ссылки и id папки
            }

            if ($item['type'] == 'page') {
                Page::query()->find($item['id'])->update([
                    'order' => $key,
                    'folder_id' => $folder_id,
                ]);
                //Сохраняем позицию страницы
            }
        }
    }
}
