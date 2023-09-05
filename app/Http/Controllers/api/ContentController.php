<?php

namespace App\Http\Controllers\api;

use App\Content;
use App\User;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller\api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContentController extends ApiResponseController
{
    public function __construct(Request $request)
    {
        $this->user = User::from('users as c')
                        ->selectRaw('c.*, members.rol_id as rol_id')
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
        $category_id = $request->segment(5);

        if(($section_id == 'null' && $category_id == 'null')
        || ($section_id == '' && $category_id == '')
        ) {
            $contents = Content::from('contents as c')
                        ->selectRaw('c.category_id as category_id, alliances.name as alliance, c.content_id as content_id, CONCAT(c.title) as title, categories.title as category, sections.section_title as section, c.start_date as start_date, c.end_date as end_date, c.status as status, c.position as position')
                        ->leftJoin('categories', 'categories.category_id', '=', 'c.category_id')
                        ->leftJoin('sections', 'sections.section_id', '=', 'categories.section_id')
                        ->leftJoin('alliances', 'alliances.rut', '=', 'categories.alliance_id')
                        ->orderBy('c.position', 'ASC')
                        ->paginate(10);
        } else {
            
            $query = "";

            if ($section_id != 'null') {
                $query .= 'sections.section_id = "'.$section_id.'"';
            }

            if ($category_id != 'null') {
                if ($section_id != 'null') {
                    $query .= ' AND ';
                }

                $query .= 'categories.category_id = "'.$category_id.'"';
            }

            $contents = Content::from('contents as c')
                        ->selectRaw('c.category_id as category_id, alliances.name as alliance, c.content_id as content_id, CONCAT(c.title) as title, categories.title as category, sections.section_title as section, c.start_date as start_date, c.end_date as end_date, c.status as status, c.position as position')
                        ->leftJoin('categories', 'categories.category_id', '=', 'c.category_id')
                        ->whereRaw($query)
                        ->leftJoin('sections', 'sections.section_id', '=', 'categories.section_id')
                        ->leftJoin('alliances', 'alliances.rut', '=', 'categories.alliance_id')
                        ->orderBy('c.position', 'ASC')
                        ->paginate(10);
        }
        
        return $this->successResponse($contents);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $id = $request->segment(4);

        if($id != '') {
            $contents = Content::where('category_id', $id)->where('status', 1)->orderBy('title', 'ASC')->get();
        } else {
            $contents = Content::where('status', 1)->orderBy('title', 'ASC')->get();
        }
        
        return $this->successResponse($contents);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function date()
    {
        $contents = Content::all();

        foreach($contents as $content) {
            if(date('Y-m-d') >= $content->end_date) {
                $update_content = Content::find($content->content_id);
                $update_content->status = 2;
                $update_content->save();
            }
        }
        
        return $this->successResponse($contents);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->icon_type_id == 1) {
            if($request->file != 'undefined') {
                $fileName = time().'_'.'contenido'.'_'.$request->category_id.'.'.$request->file->getClientOriginalExtension();
            }
        } else {
            $fileName = $request->icon;
        }

        if($request->pdf != 'undefined') { 
            $pdfName = time().'_'.'pdf'.'_'.$request->category_id.'.'.$request->pdf->getClientOriginalExtension();
        } else {
            $pdfName = '';
        }

        $content = new Content();
        $content->category_id = $request->category_id;
        $content->type_id = $request->type_id;
        $content->video_id = $request->video_id;
        if($request->video_type_id == 'null') {
            $content->video_type_id = '';
        } else {
            $content->video_type_id = $request->video_type_id;
        }
        
        $content->title = $request->title;
        $content->google_tag = 'content_' . $request->google_tag;
        $content->start_date = $request->start_date;
        $content->icon_type_id = $request->icon_type_id;
        if($request->icon_type_id == 2) {
            $content->icon = 'icon '.$fileName.' home_icon_size2';
        } else if($request->icon_type_id == 3) {
            $content->icon = 'icon ion-'.$fileName.' home_icon_size2';
        }
        $content->end_date = $request->end_date;
        $content->color = $request->color;
        $content->description = $request->description;
        $content->iframe = $request->iframe;
        $content->position = $request->position;
        $content->icon_available_id = $request->icon_available_id;
        $content->whatsapp_description = $request->whatsapp_description;
        $content->pdf = $pdfName;

        $move_position_contents = Content::where('category_id', $request->category_id)->where('position', '>=', $request->position)->orderBy('position', 'ASC')->get();
        $position = $request->position;
        foreach($move_position_contents as $move_position_content) {
            $position = $position + 1;
            $detail_content = Content::find($move_position_content->content_id);
            $detail_content->position = $position;
            $detail_content->save();
        }

        $content->src = $request->src;
        if($this->user->rol_id == 1 || $this->user->rol_id == 3) {
            $content->status = 1;
        } else {
            $content->status = 2;
        }
        if($content->save()) {
            if ($request->file != 'undefined') { 
                Storage::disk('local')->putFileAs(
                    'files', // Ruta modificada aquí
                    $request->file,
                    $fileName
                );
            }
            
            if ($request->pdf != 'undefined') { 
                Storage::disk('local')->putFileAs(
                    'files', // Ruta modificada aquí
                    $request->pdf,
                    $pdfName
                );
            }            
        }

        return $this->successResponse($content);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function accept(Request $request)
    {
        $id = $request->segment(4);

        $content = Content::find($id);
        $content->status = 1;
        $content->save();

        return $this->successResponse($content);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $fileName = $request->icon;

        if($request->pdf != 'undefined') { 
            $pdfName = time().'_'.'pdf'.'_'.$request->category_id.'.'.$request->pdf->getClientOriginalExtension();
        } else {
            $pdfName = '';
        }

        $content = Content::find($id);
        $content->category_id = $request->category_id;
        $content->type_id = $request->type_id;
        $content->video_id = $request->video_id;
        $content->title = $request->title;
        $content->google_tag = 'content_' . $request->google_tag;
        $content->start_date = $request->start_date;
        $content->icon_type_id = $request->icon_type_id;
        $content->icon = 'icon '. $fileName.' home_icon_size2';
        $content->end_date = $request->end_date;
        $content->description = $request->description;
        $content->iframe = $request->iframe;
        $content->position = $request->position;
        $old_position = $content->position;
        $content->position = $request->position;
        $content->icon_available_id = $request->icon_available_id;
        $content->whatsapp_description = $request->whatsapp_description;

        if ($pdfName != '') {
            $content->pdf = $pdfName;
        }
        
        if($old_position != $request->position) {
            $move_position_contents = Content::where('category_id', $request->category_id)->where('position', '>=', $request->position)->orderBy('position', 'ASC')->get();
            $position = $request->position;
            foreach($move_position_contents as $move_position_content) {
                $position = $position + 1;
                $detail_content = Content::find($move_position_content->section_id);
                $detail_content->position = $position;
                $detail_content->save();
            }
        }

        if($this->user->rol_id == 1 ||  $this->user->rol_id == 3) {
            $content->status = 1;
        } else {
            $content->status = 2;
        }

        if($content->save()) {
            if($request->file != 'undefined') { 
                Storage::disk('local')->putFileAs(
                    '/files',
                    $request->file,
                    $fileName
                );
            }

            if ($request->pdf != 'undefined') { 
                if (Storage::disk('local')->exists('files/' . $pdfName)) {
                    Storage::disk('local')->delete('files/' . $pdfName);
                }

                Storage::disk('local')->putFileAs(
                    'files', // Ruta modificada aquí
                    $request->pdf,
                    $pdfName
                );
            }  
        }

        return $this->successResponse($content);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $id = $request->segment(4);
        $contents = Content::where('category_id', $id)->where('status', 1)->orderBy('position', 'ASC')->get();

        return $this->successResponse($contents);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function image(Request $request)
    {
        $id = $request->segment(4);
        $content = Content::find($id);
echo \Storage::url($content->image);
        die();
        return $this->successResponse($content);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail(Request $request)
    {
        $id = $request->segment(4);
        $content = Content::where('content_id', $id)->where('status', 1)->first();

        return $this->successResponse($content);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $content = Content::find($id);

        return $this->successResponse($content);
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
        $category_id = $request->segment(6);

        $fix_contents = Content::where('category_id', $category_id)->orderBy('position', 'ASC')->get();

        $i = 1;

        foreach($fix_contents as $fix_content) {
            $content_detail = Content::find($fix_content->content_id);
            $content_detail->position = $i;
            $content_detail->save();
            $i = $i + 1;
        }

        $another_content = Content::where('category_id', $category_id)->where('position', $position)->first();
        $content = Content::find($id);

        if($content->position > $another_content->position) {
            $content->position = $position;

            $another_content->position = $another_content->position + 1;
        } else {
            $content->position = $position;

            $another_content->position = $another_content->position - 1;
        }
        
        $another_content->save();
        $content->save();

        return $this->errorResponse($content);
    }

    /**
     * Destroy the specified resource.
     *
     * @param  int  $id
     * @return \App\Http\Controllers\ApiResponseController
     */
    public function destroy($id)
    {
        $content = Content::find($id);

        if($content->delete()) {
            $fix_contents = Content::where('category_id', $content->category_id)->orderBy('position', 'ASC')->get();

            $i = 1;

            foreach($fix_contents as $fix_content) {
                $content_detail = Content::find($fix_content->content_id);
                $content_detail->position = $i;
                $content_detail->save();
                $i = $i + 1;
            }

        }

        return $this->successResponse($content);
    }

    /**
     * Destroy the specified resource.
     *
     * @param  int  $id
     * @return \App\Http\Controllers\ApiResponseController
     */
    public function stop($id)
    {
        $content = Content::find($id);
        $content->status = 2;
        $content->save();

        return $this->successResponse($content);
    }

    /**
     * Destroy the specified resource.
     *
     * @param  int  $id
     * @return \App\Http\Controllers\ApiResponseController
     */
    public function play($id)
    {
        $content = Content::find($id);
        $content->status = 1;
        $content->save();

        return $this->successResponse($content);
    }
}