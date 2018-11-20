<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use Closure;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Exceptions\UnauthorizedException;

class Permission
{
    // 菜单缓存key
    protected $cache_key = 'current_user_menus';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $action = $request->route()->action;
        $user = auth()->user();
        $menus = $this->cacheMenus();
        view()->share($this->cache_key, $menus);
        if (!isset($action['as']) || $user->can($action['as'])) {
            return $next($request);
        }
        throw UnauthorizedException::forPermissions([$action['as']]);
    }

    protected function cacheMenus()
    {
        $key = $this->cache_key;
        if (Cache::has($key)) {
            $list = Cache::get($key);
        } else {
            $list = auth()->user()->menus;
            Cache::forever($key, $list);
        }
        $menus = [];
        foreach ($list->where('pid', 0)->sortBy('sort')->all() as $item) {
            $menu = $this->getMenu($list, $item);
            array_push($menus, $menu);
        }
        return $menus;
    }

    protected function getMenu($list, Menu $item)
    {
        $menu = [
            'id' => $item->id,
            'text' => $item->name,
            'icon' => $item->key?:'fa fa-list',
            'active' => false,
        ];
        $current_url = url()->current();
        if (!$item->url) {
            $children = [];
            $active = false;
            foreach ($list->where('pid', $item->id)->sortBy('sort')->all() as $item1) {
                $children_menu = $this->getMenu($list, $item1);
                if ($children_menu['active']) {
                    $active = true;
                }
                array_push($children, $children_menu);
            }
            $menu['active'] = $active;
            $menu['children'] = $children;
        } else {
            $url = $item->url;
            if (str_contains($url, '.')) {
                $url = route($url);
            } else {
                $url = url($url);
            }
            if ($current_url == $url) {
                $menu['active'] = true;
            }
            $menu['url'] = $url;
            $menu['urlType'] = 'absolute';
            $menu['targetType'] = 'iframe-tab';
        }

        return $menu;
    }
}
