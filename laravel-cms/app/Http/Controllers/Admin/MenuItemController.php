<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $zoneFilter = (string) request()->query('menu_zone', 'all');
        if (!in_array($zoneFilter, ['all', MenuItem::ZONE_MAIN, MenuItem::ZONE_ABOUT_US], true)) {
            $zoneFilter = 'all';
        }

        $menuItems = MenuItem::query()
            ->with('parent')
            ->when($zoneFilter !== 'all', function ($query) use ($zoneFilter) {
                $query->inZone($zoneFilter);
            })
            ->orderedHierarchy()
            ->paginate(30)
            ->withQueryString();

        return view('admin.menu-items.index', [
            'menuItems' => $menuItems,
            'zoneFilter' => $zoneFilter,
            'zones' => MenuItem::zones(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.menu-items.create', [
            'menuItem' => new MenuItem([
                'menu_zone' => MenuItem::ZONE_MAIN,
                'parent_id' => null,
                'sort_order' => 0,
                'is_active' => true,
                'open_in_new_tab' => false,
                'is_home_icon' => false,
            ]),
            'zones' => MenuItem::zones(),
            'parentOptions' => $this->getParentOptions(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        MenuItem::query()->create($this->validateInput($request));

        return redirect()
            ->route('admin.menu-items.index')
            ->with('success', 'Đã thêm mục menu mới.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function edit(MenuItem $menuItem)
    {
        return view('admin.menu-items.edit', [
            'menuItem' => $menuItem,
            'zones' => MenuItem::zones(),
            'parentOptions' => $this->getParentOptions($menuItem),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MenuItem $menuItem)
    {
        $menuItem->update($this->validateInput($request, $menuItem));

        return redirect()
            ->route('admin.menu-items.index')
            ->with('success', 'Đã cập nhật menu thành công.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(MenuItem $menuItem)
    {
        $menuItem->children()->update(['parent_id' => null]);
        $menuItem->delete();

        return redirect()
            ->route('admin.menu-items.index')
            ->with('success', 'Đã xoá mục menu.');
    }

    private function validateInput(Request $request, ?MenuItem $editingMenu = null): array
    {
        $validated = $request->validate([
            'label' => 'required|string|max:100',
            'url' => 'required|string|max:255',
            'menu_zone' => 'required|string|in:main,about-us',
            'parent_id' => 'nullable|integer|exists:menu_items,id',
            'page_key' => 'nullable|string|max:120',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'open_in_new_tab' => 'nullable|boolean',
            'is_home_icon' => 'nullable|boolean',
        ]);

        $validated['label'] = trim((string) $validated['label']);
        $validated['url'] = trim((string) $validated['url']);
        $validated['menu_zone'] = trim((string) $validated['menu_zone']);
        $validated['parent_id'] = !empty($validated['parent_id']) ? (int) $validated['parent_id'] : null;
        $validated['page_key'] = empty($validated['page_key']) ? null : trim((string) $validated['page_key']);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['open_in_new_tab'] = $request->boolean('open_in_new_tab');
        $validated['is_home_icon'] = $request->boolean('is_home_icon');

        if (!is_null($validated['parent_id'])) {
            if ($editingMenu instanceof MenuItem && $editingMenu->id === $validated['parent_id']) {
                throw ValidationException::withMessages([
                    'parent_id' => 'Menu cha không được trùng chính mục menu hiện tại.',
                ]);
            }

            $parent = MenuItem::query()->find($validated['parent_id']);
            if (!$parent instanceof MenuItem) {
                throw ValidationException::withMessages([
                    'parent_id' => 'Không tìm thấy menu cha đã chọn.',
                ]);
            }

            if (!is_null($parent->parent_id)) {
                throw ValidationException::withMessages([
                    'parent_id' => 'Chỉ được chọn menu cha cấp 1 để đảm bảo quản lý cha/con rõ ràng.',
                ]);
            }

            if ($parent->menu_zone !== $validated['menu_zone']) {
                throw ValidationException::withMessages([
                    'parent_id' => 'Menu cha và menu con phải cùng vùng menu.',
                ]);
            }

            if ($editingMenu instanceof MenuItem && $editingMenu->children()->exists()) {
                throw ValidationException::withMessages([
                    'parent_id' => 'Mục menu đang có menu con. Vui lòng chuyển/xoá menu con trước khi đổi thành menu con.',
                ]);
            }
        }

        return $validated;
    }

    private function getParentOptions(?MenuItem $editingMenu = null)
    {
        return MenuItem::query()
            ->topLevel()
            ->when($editingMenu instanceof MenuItem, function ($query) use ($editingMenu) {
                $query->where('id', '!=', $editingMenu->id);
            })
            ->orderedHierarchy()
            ->get();
    }
}
