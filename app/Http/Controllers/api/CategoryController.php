<?php

namespace App\Http\Controllers\api;

use App\Category;
use App\Content;
use App\User;
use App\Poll;
use App\Region;
use App\Commune;
use App\CategoryRegion;
use App\CategoryCommune;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller\api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends ApiResponseController
{
    public function __construct(Request $request)
    {
        $this->user = User::from('users as c')
                        ->selectRaw('c.*, members.rol_id as rol_id, members.alliance_id as alliance_id')
                        ->leftJoin('members', 'members.user_id', '=', 'c.rut')
                        ->where('api_token', $request->api_token)
                        ->first();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $section_id = $request->segment(4);

        if(($section_id == 'null')
        || ($section_id == '')
        ) {
            $categories = Category::from('categories as c')
                        ->selectRaw('c.section_id as section_id, c.category_id as category_id, c.title as title, alliances.name as alliance, c.position as position, sections.section_title as section_title, c.status as status')
                        ->leftJoin('alliances', 'alliances.rut', '=', 'c.alliance_id')
                        ->leftJoin('sections', 'sections.section_id', '=', 'c.section_id')
                        ->orderBy('c.section_id', 'ASC')
                        ->orderBy('c.position', 'ASC')
                        ->paginate(10);
        } else {
            $query = "";

            if ($section_id != 'null') {
                $query .= 'sections.section_id = "'.$section_id.'"';
            }

            $categories = Category::from('categories as c')
                        ->selectRaw('c.section_id as section_id, c.category_id as category_id, c.title as title, alliances.name as alliance, c.position as position, sections.section_title as section_title, c.status as status')
                        ->leftJoin('alliances', 'alliances.rut', '=', 'c.alliance_id')
                        ->leftJoin('sections', 'sections.section_id', '=', 'c.section_id')
                        ->whereRaw($query)
                        ->orderBy('c.section_id', 'ASC')
                        ->orderBy('c.position', 'ASC')
                        ->paginate(10);
        }
        
        return $this->successResponse($categories);
    }

        /**
     * Update the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function move(Request $request)
    {
        $id = $request->segment(4);
        $position = $request->segment(5) + 1;
        $section_id = $request->segment(6);

        $fix_categories = Category::where('section_id', $section_id)->orderBy('position', 'ASC')->get();

        $i = 1;

        foreach($fix_categories as $fix_category) {
            $category_detail = Category::find($fix_category->category_id);
            $category_detail->position = $i;
            $category_detail->save();
            $i = $i + 1;
        }

        $another_category = Category::where('section_id', $section_id)->where('position', $position)->first();
        $category = Category::find($id);

        if($category->position > $another_category->position) {
            $category->position = $position;

            $another_category->position = $another_category->position + 1;
        } else {
            $category->position = $position;

            $another_category->position = $another_category->position - 1;
        }
        
        $another_category->save();
        $category->save();

        return $this->errorResponse($category);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function poll(Request $request)
    {
        $id = $request->segment(4);

        $category = Poll::where('category_id', $id)->where('status', 1)->first();
        $category_qty = Poll::where('category_id', $id)->where('status', 1)->count();
        
        if($category_qty > 0) {
            return $this->successResponse($category->content_id);
        } else {
            return $this->successResponse('');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $id = $request->segment(4);

        if ($id != '') {
            $categories = Category::where('categories.section_id', $id)
                ->leftJoin('sections', 'categories.section_id', '=', 'sections.section_id')
                ->where('categories.status', 1)
                ->orderBy('categories.title', 'ASC')
                ->get(['categories.*', 'sections.*']);
        } else {
            if ($this->user->rol_id == 2) {
                $categories = Category::where('categories.alliance_id', $this->user->alliance_id)
                    ->leftJoin('sections', 'categories.section_id', '=', 'sections.section_id')
                    ->where('categories.status', 1)
                    ->orderBy('categories.title', 'ASC')
                    ->get(['categories.*', 'sections.*']);
            } else {
                $categories = Category::where('categories.status', 1)
                    ->leftJoin('sections', 'categories.section_id', '=', 'sections.section_id')
                    ->orderBy('categories.title', 'ASC')
                    ->get(['categories.*', 'sections.*']);
            }
        }
        
        return $this->successResponse($categories);
    }

    /**
     * Store the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $icon_fileName = $request->icon;

        $category = new Category();
        $category->alliance_id = $request->alliance_id;
        $category->section_id = $request->section_id;
        $category->highlight_id = $request->highlight_id;
        $category->title = $request->title;
        $category->iframe = $request->iframe;
        $category->subtitle = $request->subtitle;
        $category->color = $request->color;
        $category->link_question_id = $request->link_question_id;
        $category->url = $request->url;
        $category->google_tag = 'category_' . $request->google_tag;
        $category->position = $request->position;
        $category->icon_available_id = $request->icon_available_id;
        $category->georeferencing_type_id = $request->georeferencing_type_id;

        $category->icon = $icon_fileName.' home_icon_size2';

        $move_position_categories = Category::where('section_id', $category->section_id)->where('position', '>=', $request->position)->orderBy('position', 'ASC')->get();
        $position = $request->position;
        foreach($move_position_categories as $move_position_category) {
            $position = $position + 1;
            $detail_category = Category::find($move_position_category->category_id);
            $detail_category->position = $position;
            $detail_category->save();
        }

        $category->status = 1;

        if($category->save()) {
            if($request->georeferencing_type_id == 1) {
                if ($request->region_id != 1000) {
                    $region_data = explode(',', $request->region_id);

                    for ($i=0; $i < count($region_data); $i++) { 
                        $category_region = new CategoryRegion();
                        $category_region->category_id = $category->category_id;
                        $category_region->region_id = trim($region_data[$i]);
                        $category_region->save();
                    }
        
                    if ($request->commune_id != 'null') {
                        $commune_data = explode(',', $request->commune_id);
        
                        for ($i=0; $i < count($commune_data); $i++) { 
                            $category_commune = new CategoryCommune();
                            $category_commune->category_id = $category->category_id;
                            $category_commune->commune_id = trim($commune_data[$i]);
                            $category_commune->save();
                        }
                    } else {
                        $region_data = explode(',', $request->region_id);
        
                        for ($i=0; $i < count($region_data); $i++) { 
                            $communes = Commune::where('region_id', trim($region_data[$i]))->get();
        
                            foreach ($communes as $commune) {
                                $category_commune = new CategoryCommune();
                                $category_commune->category_id = $category->category_id;
                                $category_commune->commune_id = $commune->commune_id;
                                $category_commune->save();
                            }
                        }
                    }
                } else {
                    $regions = Region::all();

                    foreach ($regions as $region) {
                        $category_region = new SectionRegion();
                        $category_region->category_id = $category->category_id;
                        $category_region->region_id = $region->region_id;
                        $category_region->save();
                    }

                    $communes = Commune::all();

                    foreach ($communes as $commune) {
                        $category_commune = new CategoryCommune();
                        $category_commune->category_id = $category->category_id;
                        $category_commune->commune_id = $commune->commune_id;
                        $category_commune->save();
                    }
                }
            }

            return $this->successResponse($category);
        } else {
            return $this->errorResponse($category);
        }
    }

    /**
     * Store the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $icon_fileName = $request->icon;

        $category = Category::find($id);
        $category->alliance_id = $request->alliance_id;
        $category->section_id = $request->section_id;
        $category->title = $request->title;
        $category->color = $request->color;
        $category->subtitle = $request->subtitle;

        if($request->iframe_question_id == 1) {
            $category->iframe = $request->iframe;
        } else {
            $category->iframe = '';
        }

        $category->google_tag = 'category_' . $request->google_tag;
        $old_position = $category->position;
        $category->position = $request->position;
        $category->georeferencing_type_id = $request->georeferencing_type_id;

        $category->icon = $icon_fileName.' home_icon_size2';
        
        if($old_position != $request->position) {
            $move_position_categories = Category::where('section_id', $category->section_id)->where('position', '>=', $request->position)->orderBy('position', 'ASC')->get();
            $position = $request->position;
            foreach($move_position_categories as $move_position_category) {
                $position = $position + 1;
                $detail_category = Category::find($move_position_category->category_id);
                $detail_category->position = $position;
                $detail_category->save();
            }
        }

        $category->highlight_id = $request->highlight_id;
        $category->status = 1;

        if($category->save()) {
            if($request->georeferencing_type_id == 1) {
                $category_regions = CategoryRegion::where('category_id', $id)->get();

                foreach ($category_regions as $category_region) {
                    $category_region_detail = CategoryRegion::find($category_region->category_region_id);
                    $category_region_detail->delete();
                }

                $category_communes = CategoryCommune::where('category_id', $id)->get();

                foreach ($category_communes as $category_commune) {
                    $category_commune_detail = CategoryCommune::find($category_commune->category_commune_id);
                    $category_commune_detail->delete();
                }

                if ($request->region_id != 1000) {
                    $region_data = explode(',', $request->region_id);

                    for ($i=0; $i < count($region_data); $i++) { 
                        $category_region = new CategoryRegion();
                        $category_region->category_id = $category->category_id;
                        $category_region->region_id = trim($region_data[$i]);
                        $category_region->save();
                    }
        
                    if ($request->commune_id != 'null') {
                        $commune_data = explode(',', $request->commune_id);
        
                        for ($i=0; $i < count($commune_data); $i++) { 
                            $category_commune = new CategoryCommune();
                            $category_commune->category_id = $category->category_id;
                            $category_commune->commune_id = trim($commune_data[$i]);
                            $category_commune->save();
                        }
                    } else {
                        $region_data = explode(',', $request->region_id);
        
                        for ($i=0; $i < count($region_data); $i++) { 
                            $communes = Commune::where('region_id', trim($region_data[$i]))->get();
        
                            foreach ($communes as $commune) {
                                $category_commune = new CategoryCommune();
                                $category_commune->category_id = $category->category_id;
                                $category_commune->commune_id = $commune->commune_id;
                                $category_commune->save();
                            }
                        }
                    }
                } else {
                    $regions = Region::all();

                    foreach ($regions as $region) {
                        $category_region = new SectionRegion();
                        $category_region->category_id = $category->category_id;
                        $category_region->region_id = $region->region_id;
                        $category_region->save();
                    }

                    $communes = Commune::all();

                    foreach ($communes as $commune) {
                        $category_commune = new CategoryCommune();
                        $category_commune->category_id = $category->category_id;
                        $category_commune->commune_id = $commune->commune_id;
                        $category_commune->save();
                    }
                }
            }

            return $this->successResponse($category);
        } else {
            return $this->errorResponse($category);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);

        return $this->successResponse($category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $category_qty = Category::select('categories.*')
            ->distinct() // Agrega la función distinct()
            ->leftJoin('category_regions', 'category_regions.category_id', '=', 'categories.category_id')
            ->leftJoin('communes', 'communes.region_id', '=', 'category_regions.region_id')
            ->leftJoin('category_communes', 'category_communes.commune_id', '=', 'communes.commune_id')
            ->where('categories.status', 1)
            ->where('categories.section_id', $request->section_id)
            ->where('category_regions.region_id', $request->region)
            ->where('category_communes.commune_id', $request->commune)
            ->orderBy('categories.position', 'ASC')
            ->count();

        if($category_qty > 0) {
            $categories = Category::select('categories.*')
                ->distinct() // Agrega la función distinct()
                ->leftJoin('category_regions', 'category_regions.category_id', '=', 'categories.category_id')
                ->leftJoin('communes', 'communes.region_id', '=', 'category_regions.region_id')
                ->leftJoin('category_communes', 'category_communes.commune_id', '=', 'communes.commune_id')
                ->where('categories.status', 1)
                ->where('categories.section_id', $request->section_id)
                ->where('category_regions.region_id', $request->region)
                ->where('category_communes.commune_id', $request->commune)
                ->orderBy('categories.position', 'ASC')
                ->get();
        } else {
            $categories = Category::select('categories.*')
                ->distinct() // Agrega la función distinct()
                ->leftJoin('category_regions', 'category_regions.category_id', '=', 'categories.category_id')
                ->leftJoin('communes', 'communes.region_id', '=', 'category_regions.region_id')
                ->leftJoin('category_communes', 'category_communes.commune_id', '=', 'communes.commune_id')
                ->where('categories.georeferencing_type_id', 2)
                ->where('categories.status', 1)
                ->where('categories.section_id', $request->section_id)
                ->orderBy('categories.position', 'ASC')
                ->get();
        }

        return $this->successResponse($categories);
    }

    /**
     * Destroy the specified resource.
     *
     * @param  int  $id
     * @return \App\Http\Controllers\ApiResponseController
     */
    public function destroy($id)
    {
        $check_category_contents = Content::where('category_id', $id)->count();

        if($check_category_contents == 0) {
            $category = Category::find($id);
            if(Storage::exists('public/'.$category->icon)) {
                if(Storage::delete('public/'.$category->icon)) {
                    if($category->delete()) {
                        $category_regions = CategoryRegion::where('category_id', $id)->get();

                        foreach ($category_regions as $category_region) {
                            $category_region_detail = CategoryRegion::find($category_region->category_region_id);
                            $category_region_detail->delete();
                        }
            
                        $category_communes = CategoryCommune::where('category_id', $id)->get();
            
                        foreach ($category_communes as $category_commune) {
                            $category_commune_detail = CategoryCommune::find($category_commune->category_commune_id);
                            $category_commune_detail->delete();
                        }

                        $fix_categories = Category::where('section_id', $category->section_id)->orderBy('position', 'ASC')->get();

                        $i = 1;

                        foreach($fix_categories as $fix_category) {
                            $category_detail = Category::find($fix_category->category_id);
                            $category_detail->position = $i;
                            $category_detail->save();
                            $i = $i + 1;
                        }

                        return $this->successResponse($category);
                    } else {
                        return $this->errorResponse($category);
                    }
                }
            } else {
                if($category->delete()) {
                    $category_regions = CategoryRegion::where('category_id', $id)->get();

                    foreach ($category_regions as $category_region) {
                        $category_region_detail = CategoryRegion::find($category_region->category_region_id);
                        $category_region_detail->delete();
                    }
        
                    $category_communes = CategoryCommune::where('category_id', $id)->get();
        
                    foreach ($category_communes as $category_commune) {
                        $category_commune_detail = CategoryCommune::find($category_commune->category_commune_id);
                        $category_commune_detail->delete();
                    }
                    
                    $fix_categories = Category::where('section_id', $category->section_id)->orderBy('position', 'ASC')->get();

                    $i = 1;

                    foreach($fix_categories as $fix_category) {
                        $category_detail = Category::find($fix_category->category_id);
                        $category_detail->position = $i;
                        $category_detail->save();
                        $i = $i + 1;
                    }

                    return $this->successResponse($category);
                } else {
                    return $this->errorResponse($category);
                }
            }
        } else {
            $category = Category::find($id);
            $category->status = 0;
            if($category->save()) {
                return $this->successResponse($category);
            } else {
                return $this->errorResponse($category);
            }
        }
    }
}