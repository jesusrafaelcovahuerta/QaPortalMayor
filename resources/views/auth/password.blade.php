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
                    <form class="user" method="POST" action="{{ url('password/update') }}">
                        @csrf
                        <div class="form-group">
                            <input type="password" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Ingresa la nueva contraseña" id="password" name="password">
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-user btn-block">
                            {{ __('Actualizar') }}
                        </button>
                        <br>
                        <input id="hidden_password" name="hidden_password" type="hidden" value="{{ Request::segment(2) }}">
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
