@extends('auth.layouts.app')

@section('content')
    <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-8 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-12">
                <div class="p-5">
                    <br>
                    <div class="text-center">
                        <img src="{{ url('backend/img/logo.jpg') }}" id="logo" style="height: 80px;">
                        <br>
                        <br>
                    </div>
                    @if(Request::segment(2) == 'error')
                      <div class="alert alert-danger" role="alert">
                          La pregunta o respuesta son incorrectas
                      </div>
                    @endif
                    <form class="user" method="POST" action="{{ url('question/verify') }}">
                        @csrf
                        <div class="form-group">
                            <select class="form-control" id="exampleFormControlSelect1" name="question_id">
                                <option :value="null">-Seleccionar-</option>
                                <option value="1">¿Donde nacistes?</option>
                                <option value="2">¿Cuál es el nombre de tu mamá?</option>
                                <option value="3">¿Cuál es el nombre de tu papá?</option>
                                <option value="4">¿Cuál es el nombre de tu mascota?</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Respuesta" id="rut" name="answer">
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-user btn-block">
                            {{ __('Verificar') }}
                        </button>
                        <br>
                    </form>
                  <hr>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>
@endsection
