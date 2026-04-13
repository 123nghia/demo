<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(20);

        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        Page::query()->create($data);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Đã tạo trang mới thành công.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $data = $this->validatedData($request, $page);
        $page->update($data);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Đã cập nhật trang thành công.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        if ($page->slug === 'home') {
            return redirect()
                ->route('admin.pages.index')
                ->withErrors(['delete' => 'Không thể xoá trang chủ.']);
        }

        $page->delete();

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Đã xoá trang thành công.');
    }

    private function validatedData(Request $request, Page $page = null): array
    {
        $pageId = $page ? $page->id : 'NULL';

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'slug' => 'required|string|max:150|regex:/^[a-z0-9\-]+$/|unique:pages,slug,' . $pageId,
            'legacy_file' => 'required|string|max:190',
            'page_key' => 'required|string|max:60',
            'seo_title' => 'nullable|string|max:190',
            'seo_description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_published' => 'nullable|boolean',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_published'] = $request->boolean('is_published');

        return $validated;
    }
}
