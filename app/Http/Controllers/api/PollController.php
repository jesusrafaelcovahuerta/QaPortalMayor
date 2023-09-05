<?php

namespace App\Http\Controllers\api;

use App\Poll;
use App\PollQuestion;
use App\PollQuestionAnswer;
use App\User;
use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller\api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PollController extends ApiResponseController
{
    public function __construct(Request $request)
    {
        $this->user = User::where('api_token', $request->api_token)->first();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $polls = Poll::from('polls as c')
                        ->selectRaw('c.title as title, c.poll_id as poll_id, sections.section_title as section, c.status as status')
                        ->leftJoin('sections', 'sections.section_id', '=', 'c.section_id')
                        ->orderBy('c.created_at', 'DESC')
                        ->paginate(10);
        
        return $this->successResponse($polls);
    }

    /**
     * Store the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $informations = json_decode($request->informations);

        $poll = new Poll;
        $poll->title = $request->title;
        $poll->section_id = $request->section_id;
        if($request->category_id == 'null') {
            $poll->category_id = 0;
        } else {
            $poll->category_id = $request->category_id;
        }
        if($request->content_id == 'null') {
            $poll->content_id = 0;
        } else {
            $poll->content_id = $request->content_id;
        }
        $poll->status = 1;
        $poll->save();

        foreach($informations as $information) {
            $poll_question = new PollQuestion;
            $poll_question->poll_id = $poll->poll_id;
            $poll_question->answer_type_id = $information->answer_type_id;
            $poll_question->question = $information->question;
            $poll_question->save();
        }

        return $this->successResponse($poll);
    }

    /**
     * Store the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $id = $request->segment(4);
        $poll = Poll::where('section_id', $id)->first();
        $poll_section_qty = Poll::where('section_id', $id)->count();
        $poll_category_qty = Poll::where('category_id', $id)->count();
        $poll_content_qty = Poll::where('content_id', $id)->count();

        if($poll_category_qty != 0) {
            $poll = Poll::where('category_id', $id)->first();
        }

        if($poll_content_qty != 0) {
            $poll = Poll::where('content_id', $id)->first();
        }

        if($poll_section_qty > 0 || $poll_category_qty > 0 || $poll_content_qty > 0) {
            $poll_questions = PollQuestion::where('poll_id', $poll->poll_id)->get();

            return $this->successResponse($poll_questions);
        } else {
            return $this->successResponse('');
        }
    }

    /**
     * Store the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail(Request $request)
    {
        $id = $request->segment(4);
        $poll = Poll::find($id);

        $poll_questions = PollQuestion::where('poll_id', $poll->poll_id)->get();

        return $this->successResponse($poll_questions);
    }

    /**
     * Store the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function excel(Request $request)
    {
        $id = $request->segment(4);

        $poll_question_answers = PollQuestionAnswer::from('poll_question_answers as c')
                        ->selectRaw('polls.title as Encuesta, poll_questions.question as Pregunta, c.answer as Respuesta')
                        ->leftJoin('polls', 'polls.poll_id', '=', 'c.poll_id')
                        ->leftJoin('poll_questions', 'poll_questions.poll_question_id', '=', 'c.question_id')
                        ->where('c.poll_id', $id)
                        ->get();

        return $this->successResponse($poll_question_answers);
    }

    /**
     * Store the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function result(Request $request)
    {
        $id = $request->segment(4);

        $poll_questions = PollQuestion::from('poll_questions as c')
                        ->selectRaw('c.question as question, c.poll_question_id as poll_question_id')
                        ->where('poll_id', $id)
                        ->get();

        $i = 0;

        foreach($poll_questions as $poll_question) {
            $poll_answer_question_qty = PollQuestionAnswer::from('poll_question_answers as c')
                        ->where('c.question_id', $poll_question->poll_question_id)
                        ->where('c.poll_id', $id)
                        ->where('c.answer', '<>', '')
                        ->count();

            $yes_poll_answer_question_qty = PollQuestionAnswer::from('poll_question_answers as c')
                        ->where('c.question_id', $poll_question->poll_question_id)
                        ->where('c.poll_id', $id)
                        ->where('c.answer', 'Si')
                        ->count();

            $no_poll_answer_question_qty = PollQuestionAnswer::from('poll_question_answers as c')
                        ->where('c.question_id', $poll_question->poll_question_id)
                        ->where('c.poll_id', $id)
                        ->where('c.answer', 'No')
                        ->count();

            $data[$i]['question'] = $poll_question->question;
            $data[$i]['yes_answer'] = round(($yes_poll_answer_question_qty*100)/$poll_answer_question_qty);
            $data[$i]['no_answer'] = round(($no_poll_answer_question_qty*100)/$poll_answer_question_qty);

            $i = $i + 1;
        }
        
        return $data;
    }

    /**
     * Store the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all(Request $request)
    {
        $id = $request->segment(4);
        $polls = Poll::where('section_id', $id)->get();
        $poll_section_qty = Poll::where('section_id', $id)->count();
        $poll_category_qty = Poll::where('category_id', $id)->count();
        $poll_content_qty = Poll::where('content_id', $id)->count();

        if($poll_category_qty != 0) {
            $polls = Poll::where('category_id', $id)->get();
        }

        if($poll_content_qty != 0) {
            $polls = Poll::where('content_id', $id)->get();
        }

        return $this->successResponse($polls);
    }


    /**
     * Store the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function quantity(Request $request)
    {
        $id = $request->segment(4);
        $poll_qty = Poll::where('section_id', $id)->count();

        if($poll_qty == 0) {
            $poll_qty = Poll::where('category_id', $id)->count();

            if($poll_qty == 0) {
                $poll_qty = Poll::where('content_id', $id)->count();
            }
        }

        return $this->successResponse($poll_qty);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $poll = Poll::find($id);

        return $this->successResponse($poll);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $poll = Poll::find($id);
        $poll->title = $request->title;
        $poll->section_id = $request->section_id;
        $poll->category_id = $request->category_id;
        $poll->content_id = $request->content_id;
        $poll->save();

        $poll_questions = PollQuestion::where('poll_id', $id)->get();

        foreach($poll_questions as $poll_question) {
            $poll_question_delete = PollQuestion::find($poll_question->poll_question_id);
            $poll_question_delete->delete();

            $poll_question_answers = PollQuestionAnswer::where('question_id', $poll_question->poll_question_id)->get();

            foreach($poll_question_answers as $poll_question_answer) {
                $poll_question_answer_delete = PollQuestionAnswer::find($poll_question_answer->poll_question_answer_id);
                $poll_question_answer_delete->delete();
            }
        }

        $informations = json_decode($request->informations);

        foreach($informations as $information) {
            $poll_question = new PollQuestion;
            $poll_question->poll_id = $poll->poll_id;
            $poll_question->answer_type_id = $information->answer_type_id;
            $poll_question->question = $information->question;
            $poll_question->save();
        }

        return $this->successResponse($poll);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function question(Request $request)
    {
        $id = $request->segment(4);

        $poll_questions = PollQuestion::where('poll_id', $id)->get();

        return $this->successResponse($poll_questions);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $poll = Poll::find($id);
        $poll_id = $poll->poll_id;
        $poll->delete();

        $poll_questions = PollQuestion::where('poll_id', $poll_id)->get();

        foreach($poll_questions as $poll_question) {
            $poll_question_delete = PollQuestion::find($poll_question->poll_question_id);
            $poll_question_delete->delete();
        }

        $poll_answers = PollQuestionAnswer::where('poll_id', $poll_id)->get();

        foreach($poll_answers as $poll_answer) {
            $poll_answer_delete = PollQuestionAnswer::find($poll_answer->poll_question_answer_id);
            $poll_answer_delete->delete();
        }

        return $this->successResponse($poll);
    }

    public function answer(Request $request)
    {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
            $ip = $_SERVER['HTTP_CLIENT_IP'];  
            $ip = explode(":", $ip);
            $ip = $ip[3];
        } 
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
            $ip = explode(":", $ip);
            $ip = $ip[3];
        }
        else{  
            $ip = $_SERVER['REMOTE_ADDR'];  
        }

        $poll = Poll::where('section_id', $request->poll_id)->first();

        $poll_question_answer_check = PollQuestionAnswer::where('poll_id', $poll->poll_id)->where('user_ip', $ip)->count();

        if($poll_question_answer_check == 0) {
            $yes_no_asnwers = explode(',', $request->yes_no_answers);
            $text_answers = explode(',', $request->text_answers);

            
            $poll_qty = Poll::where('section_id', $request->poll_id)->count();
            
            if($poll_qty == 0) {
                $poll = Poll::where('category_id', $request->poll_id)->first();
                $poll_qty = Poll::where('category_id', $request->poll_id)->count();

                if($poll_qty == 0) {
                    $poll = Poll::where('content_id', $request->poll_id)->first();
                    $poll_qty = Poll::where('content_id', $request->poll_id)->count();
                }
            }

            $poll_questions = PollQuestion::where('poll_id', $poll->poll_id)->get();

            $i = 0;

            foreach($poll_questions as $poll_question) {
                $poll_question_answer = new PollQuestionAnswer;
                $poll_question_answer->poll_id = $poll->poll_id;
                $poll_question_answer->question_id = $poll_question->poll_question_id;
                $poll_question_answer->user_ip = $ip;
                if($poll_question->answer_type_id == 1) {
                    $poll_question_answer->answer = $yes_no_asnwers[$i];
                } else if($poll_question->answer_type_id == 2) {
                    $poll_question_answer->answer = $text_answers[$i];
                }
                
                $i = $i + 1;

                $poll_question_answer->save();
            }
        }
    }

    public function specialanswer(Request $request)
    {
        $yes_no_asnwers = explode(',', $request->yes_no_answers);
        $text_answers = explode(',', $request->text_answers);

        $poll = Poll::find($request->poll_id);

        $poll_questions = PollQuestion::where('poll_id', $poll->poll_id)->get();

        $i = 0;

        foreach($poll_questions as $poll_question) {
            $poll_question_answer = new PollQuestionAnswer;
            $poll_question_answer->poll_id = $poll->poll_id;
            $poll_question_answer->question_id = $poll_question->poll_question_id;
            if($poll_question->answer_type_id == 1) {
                $poll_question_answer->answer = $yes_no_asnwers[$i];
            } else if($poll_question->answer_type_id == 2) {
                $poll_question_answer->answer = $text_answers[$i];
            }
            
            $i = $i + 1;

            $poll_question_answer->save();
        }
    }
}